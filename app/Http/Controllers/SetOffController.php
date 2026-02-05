<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Customers;
use App\Models\Invoices;
use App\Models\CreditNote;
use App\Models\SetOffs;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Services\ActivitLogService;

class SetOffController extends Controller
{
    public function index()
    {
        $customers = Customers::select('customer_id', 'name')->get();
        return view('set_off.set_off', compact('customers'));
    }

    public function getInvoices(Request $request)
    {
        $customerIds = $request->customer_ids;

        $invoices = Invoices::whereIn('customer_id', $customerIds)
            ->whereRaw('amount - COALESCE(paid_amount, 0) > 0') // only unbalanced invoices
            ->select(
                'invoice_or_cheque_no',
                'customer_id',
                'amount',
                'paid_amount',
                DB::raw('(amount - COALESCE(paid_amount, 0)) AS balance')
            )
            ->get();

        return response()->json($invoices);
    }

    public function getCreditNotes(Request $request)
    {
        $customerIds = $request->customer_ids;

        $creditNotes = CreditNote::whereIn('customer_id', $customerIds)
            ->select('credit_note_id', DB::raw('COALESCE(updated_amount, amount) AS amount'), 'customer_id')
            ->get();

        return response()->json($creditNotes);
    }

    public function getExtraPayments(Request $request)
    {
        $customerIds = $request->customer_ids;

        $extraPayments = \App\Models\ExtraPayment::whereIn('customer_id', $customerIds)
            ->select('extra_payment_id', DB::raw('COALESCE(updated_amount, amount) AS amount'), 'customer_id')
            ->get();

        return response()->json($extraPayments);
    }

    public function submitSetOff(Request $request)
    {
        $request->validate([
            'set_off_invoices' => 'nullable|array',
            'set_off_credit_notes' => 'nullable|array',
            'final_amount' => 'required|numeric',
            'reason' => 'nullable|string|max:255',
            'gl_breakdown' => 'required|array|min:1',
        ]);

        $setOffInvoices = $request->set_off_invoices ?? [];
        $setOffCreditNotes = $request->set_off_credit_notes ?? [];
        $reason = $request->reason;

        if (empty($setOffInvoices) && empty($setOffCreditNotes)) {
            return response()->json([
                'success' => false,
                'message' => 'Please select at least one invoice or credit note.'
            ], 422);
        }

        DB::beginTransaction();

        try {
            $invoiceJson = [];
            $creditNoteJson = [];
            $finalAmount = 0;

            // ✅ Handle Invoices (add to final total)
            foreach ($setOffInvoices as $invNo => $setOffAmount) {
                $invoice = Invoices::where('invoice_or_cheque_no', $invNo)->first();
                if (!$invoice) continue;

                $setOffAmount = floatval($setOffAmount);
                $balance = $invoice->amount - $invoice->paid_amount;
                if ($balance <= 0) continue;

                $setOffAmount = min($setOffAmount, $balance);
                $newPaidAmount = $invoice->paid_amount + $setOffAmount;

                $invoice->update([
                    'paid_amount' => $newPaidAmount,
                ]);

                $invoiceJson[] = [
                    'invoice' => $invNo,
                    'set_off_amount' => $setOffAmount,
                ];

                $finalAmount += $setOffAmount; // ✅ Add to final total
            }

            // ✅ Handle Credit Notes + Extra Payments (update remaining amount, no effect on final total)
            $creditNoteJson = [];

            foreach ($setOffCreditNotes as $itemId => $setOffAmount) {

                $setOffAmount = floatval($setOffAmount);

                // Try Credit Note
                $credit = CreditNote::where('credit_note_id', $itemId)->first();
                if ($credit) {
                    $currentAmount = $credit->updated_amount ?? $credit->amount;
                    $newRemaining = max(0, $currentAmount - $setOffAmount);

                    $credit->update(['updated_amount' => $newRemaining]);

                    // Save simplified structure
                    $creditNoteJson[] = [
                        'id' => $itemId,
                        'set_off_amount' => $setOffAmount
                    ];

                    continue;
                }

                // Try Extra Payment
                $extra = \App\Models\ExtraPayment::where('extra_payment_id', $itemId)->first();
                if ($extra) {

                    $currentAmount = $extra->updated_amount ?? $extra->amount;
                    $newRemaining = max(0, $currentAmount - $setOffAmount);

                    $extra->update(['updated_amount' => $newRemaining]);

                    // Save simplified structure
                    $creditNoteJson[] = [
                        'id' => $itemId,
                        'set_off_amount' => $setOffAmount
                    ];
                }
            }

            foreach ($request->gl_breakdown as $glKey => $gl) {
                if (
                    empty($gl['name']) ||
                    !isset($gl['amount']) ||
                    !is_numeric($gl['amount']) ||
                    $gl['amount'] <= 0
                ) {
                    return response()->json([
                        'success' => false,
                        'message' => "Invalid GL entry for {$glKey}. Both name and amount are required."
                    ], 422);
                }
            }

            // ✅ Save Set-Off Record (final_amount only from invoices)
            $setOffRecord = SetOffs::create([
                'invoice_or_cheque_no' => $invoiceJson,
                'extraPayment_or_creditNote_no' => $creditNoteJson,
                'final_amount' => $finalAmount,
                'reason' => $reason,
                'gl_breakdown' => $request->gl_breakdown, // ✅ add
            ]);

            ActivitLogService::log('set_off', "Set-off ID: {$setOffRecord->id} created with total amount: {$setOffRecord->final_amount}");

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Set-Off successfully saved!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function main()
    {
        $setOffs = SetOffs::select('id', 'final_amount', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('set_off.set_off_main', compact('setOffs'));
    }

    public function details($id)
    {
        // Fetch the set-off record
        $setOff = SetOffs::findOrFail($id);

        // Prepare Invoices/Return Cheque data
        $invoicesData = collect($setOff->invoice_or_cheque_no)->map(function ($item) {
            $invoice = Invoices::where('invoice_or_cheque_no', $item['invoice'])->first();
            if (!$invoice) return null;

            $customer = $invoice->customer;
            return [
                'invoiceNo' => $item['invoice'],
                'customerName' => $customer?->name ?? '-',
                'customerId' => $customer?->customer_id ?? '-',
                'admNo' => $customer?->adm ?? '-',
                'setOffAmount' => $item['set_off_amount'],
            ];
        })->filter()->values(); // remove nulls

        // Prepare Extra Payment / Credit Note data
        $creditNotesData = collect($setOff->extraPayment_or_creditNote_no)->map(function ($item) {

            $id = $item['id'];
            $setOffAmount = $item['set_off_amount'] ?? 0;

            // Try to find a Credit Note first
            $credit = CreditNote::where('credit_note_id', $id)->first();
            if ($credit) {
                $customer = $credit->customer;
                return [
                    'id' => $id,
                    'type' => 'Credit Note',
                    'customerName' => $customer?->name ?? '-',
                    'customerId' => $customer?->customer_id ?? '-',
                    'admNo' => $customer?->adm ?? '-',
                    'setOffAmount' => $setOffAmount,
                ];
            }

            // Try Extra Payment
            $extra = \App\Models\ExtraPayment::where('extra_payment_id', $id)->first();
            if ($extra) {
                $customer = $extra->customer;
                return [
                    'id' => $id,
                    'type' => 'Extra Payment',
                    'customerName' => $customer?->name ?? '-',
                    'customerId' => $customer?->customer_id ?? '-',
                    'admNo' => $customer?->adm ?? '-',
                    'setOffAmount' => $setOffAmount,
                ];
            }

            return null; // not found
        })->filter()->values();

        return view('set_off.set_off_details', compact(
            'setOff',
            'invoicesData',
            'creditNotesData'
        ));
    }

    public function download($id)
    {
        $setOff = SetOffs::findOrFail($id);

        /* ----------------------------------------
       1️⃣ Collect UNIQUE customers and customer IDs
    -----------------------------------------*/
        $customerNames = [];
        $customerIds   = [];

        // From invoices
        foreach ($setOff->invoice_or_cheque_no ?? [] as $item) {
            $invoice = Invoices::where('invoice_or_cheque_no', $item['invoice'])->first();
            if ($invoice) {
                $customer = \App\Models\Customers::where('customer_id', $invoice->customer_id)->first();
                if ($customer) {
                    $customerNames[$customer->customer_id] = $customer->name;
                    $customerIds[$customer->customer_id]   = $customer->customer_id;
                }
            }
        }

        // From credit notes & extra payments
        foreach ($setOff->extraPayment_or_creditNote_no ?? [] as $item) {

            // Try Credit Note
            $credit = CreditNote::where('credit_note_id', $item['id'])->first();
            if ($credit) {
                $customerNames[$credit->customer_id] = $credit->customer_name;
                $customerIds[$credit->customer_id]   = $credit->customer_id;
            }

            // Try Extra Payment
            $extra = \App\Models\ExtraPayment::where('extra_payment_id', $item['id'])->first();
            if ($extra) {
                $customerNames[$extra->customer_id] = $extra->customer_name;
                $customerIds[$extra->customer_id]   = $extra->customer_id;
            }
        }

        /* ----------------------------------------
       2️⃣ Prepare Excel
    -----------------------------------------*/
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Base headers
        $headers = [
            'SO ID',
            'SO Date',
            'SO AMOUNT',
            'CUSTOMER NAME',
            'CUSTOMER NUMBER',
            'DOCUMENT NUMBER',
        ];

        // Dynamic GL headers (from gl_breakdown array)
        foreach ($setOff->gl_breakdown ?? [] as $gl) {
            $headers[] = $gl['name'];
        }

        // Write headers
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        /* ----------------------------------------
       3️⃣ Write data row
    -----------------------------------------*/
        $row = 2;
        $col = 'A';

        $sheet->setCellValue($col++ . $row, $setOff->id);
        $sheet->setCellValue($col++ . $row, $setOff->created_at->format('Y-m-d'));
        $sheet->setCellValue($col++ . $row, $setOff->final_amount);
        $sheet->setCellValue($col++ . $row, implode(', ', $customerNames));
        $sheet->setCellValue($col++ . $row, implode(', ', $customerIds));
        $sheet->setCellValue($col++ . $row, ''); // Document Number (empty)

        // GL values
        foreach ($setOff->gl_breakdown ?? [] as $gl) {
            $sheet->setCellValue($col++ . $row, $gl['amount']);
        }

        /* ----------------------------------------
       4️⃣ Download
    -----------------------------------------*/
        $fileName = "set_off_{$setOff->id}.xlsx";
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
