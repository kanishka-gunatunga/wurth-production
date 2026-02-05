<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Customers;
use App\Models\Invoices;
use App\Models\CreditNote;
use App\Models\ExtraPayment;
use App\Models\WriteOffs;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Services\ActivitLogService;


class WriteOffController extends Controller
{
    public function index()
    {
        $customers = Customers::select('customer_id', 'name')->get();
        return view('write_off.write_off', compact('customers'));
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

    public function submitWriteOff(Request $request)
    {
        $request->validate([
            'write_off_invoices' => 'nullable|array',
            'write_off_credit_notes' => 'nullable|array',
            'final_amount' => 'required|numeric',
            'reason' => 'nullable|string|max:255',
            'gl_breakdown' => 'required|array|min:1',
        ]);

        $writeOffInvoices = $request->write_off_invoices ?? [];
        $writeOffCreditNotes = $request->write_off_credit_notes ?? [];
        $reason = $request->reason;

        if (empty($writeOffInvoices) && empty($writeOffCreditNotes)) {
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
            foreach ($writeOffInvoices as $invNo => $writeOffAmount) {
                $invoice = Invoices::where('invoice_or_cheque_no', $invNo)->first();
                if (!$invoice) continue;

                $writeOffAmount = floatval($writeOffAmount);
                $balance = $invoice->amount - $invoice->paid_amount;
                if ($balance <= 0) continue;

                $writeOffAmount = min($writeOffAmount, $balance);
                $newPaidAmount = $invoice->paid_amount + $writeOffAmount;

                $invoice->update([
                    'paid_amount' => $newPaidAmount,
                ]);

                $invoiceJson[] = [
                    'invoice' => $invNo,
                    'write_off_amount' => $writeOffAmount,
                ];

                $finalAmount += $writeOffAmount; // ✅ Add to final total
            }

            // ✅ Handle Credit Notes + Extra Payments (update remaining amount, no effect on final total)
            $creditNoteJson = [];

            foreach ($writeOffCreditNotes as $itemId => $writeOffAmount) {
                $writeOffAmount = floatval($writeOffAmount);

                // Try Credit Note first
                $credit = CreditNote::where('credit_note_id', $itemId)->first();

                if ($credit) {
                    $currentAmount = $credit->updated_amount ?? $credit->amount;
                    $newRemaining = max(0, $currentAmount - $writeOffAmount);
                    $credit->update(['updated_amount' => $newRemaining]);

                    $creditNoteJson[] = [
                        'id' => $itemId,
                        'write_off_amount' => $writeOffAmount,
                        'type' => 'credit_note', // ✅ Add this 
                    ];
                    continue;
                }

                // Try Extra Payment next
                $extra = \App\Models\ExtraPayment::where('extra_payment_id', $itemId)->first();

                if ($extra) {
                    $currentAmount = $extra->updated_amount ?? $extra->amount;
                    $newRemaining = max(0, $currentAmount - $writeOffAmount);
                    $extra->update(['updated_amount' => $newRemaining]);

                    $creditNoteJson[] = [
                        'id' => $itemId,
                        'write_off_amount' => $writeOffAmount,
                        'type' => 'extra_payment', // ✅ Add this
                    ];
                }
            }

            $glBreakdown = $request->gl_breakdown;

            foreach ($glBreakdown as $glCode => $gl) {

                if (
                    empty($gl['name']) ||
                    !isset($gl['amount']) ||
                    !is_numeric($gl['amount']) ||
                    $gl['amount'] <= 0
                ) {
                    return response()->json([
                        'success' => false,
                        'message' => "Invalid GL entry for {$glCode}. Both name and amount are required."
                    ], 422);
                }
            }

            // ✅ Save Write-Off Record (final_amount only from invoices)
            $writeOff = WriteOffs::create([
                'invoice_or_cheque_no' => $invoiceJson,
                'extraPayment_or_creditNote_no' => $creditNoteJson,
                'final_amount' => $finalAmount,
                'reason' => $reason,
                'gl_breakdown' => $request->gl_breakdown, // ✅ NEW
            ]);
            $customerIdsInvolved = Invoices::whereIn('invoice_or_cheque_no', array_keys($writeOffInvoices))->pluck('customer_id')->toArray();
            $uniqueCustomerIds = array_unique($customerIdsInvolved);
            $customerNames = Customers::whereIn('customer_id', $uniqueCustomerIds)->pluck('name', 'customer_id')->toArray();
            $customerInfo = [];
            foreach ($uniqueCustomerIds as $id) {
                $customerInfo[] = (isset($customerNames[$id]) ? $customerNames[$id] : 'Unknown') . " ($id)";
            }
            $customerString = implode(', ', $customerInfo);

            ActivitLogService::log('write_off', "Write-off created for Customer(s): $customerString - Amount: $finalAmount");

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Write-Off successfully saved!'
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
        $writeOffs = WriteOffs::select('id', 'final_amount', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('write_off.write_off_main', compact('writeOffs'));
    }

    public function details($id)
    {
        // Fetch the write-off record
        $writeOff = WriteOffs::findOrFail($id);

        // Prepare Invoices/Return Cheque data
        $invoicesData = collect($writeOff->invoice_or_cheque_no)->map(function ($item) {
            $invoice = Invoices::where('invoice_or_cheque_no', $item['invoice'])->first();
            if (!$invoice) return null;

            $customer = $invoice->customer;
            return [
                'invoiceNo' => $item['invoice'],
                'customerName' => $customer?->name ?? '-',
                'customerId' => $customer?->customer_id ?? '-',
                'admNo' => $customer?->adm ?? '-',
                'writeOffAmount' => $item['write_off_amount'],
            ];
        })->filter()->values(); // remove nulls

        // Prepare Extra Payment / Credit Note data
        $creditNotesData = collect($writeOff->extraPayment_or_creditNote_no)->map(function ($item) {

            $credit = CreditNote::where('credit_note_id', $item['id'])->first();
            if ($credit) {
                return [
                    'type' => 'Credit Note',
                    'id' => $item['id'],
                    'customerName' => $credit->customer_name ?? '-',
                    'customerId' => $credit->customer_id ?? '-',
                    'admNo' => $credit->adm_id ?? '-',
                    'writeOffAmount' => $item['write_off_amount'],
                ];
            }

            $extra = ExtraPayment::where('extra_payment_id', $item['id'])->first();
            if ($extra) {
                return [
                    'type' => 'Extra Payment',
                    'id' => $item['id'],
                    'customerName' => $extra->customer_name ?? '-',
                    'customerId' => $extra->customer_id ?? '-',
                    'admNo' => $extra->adm_id ?? '-',
                    'writeOffAmount' => $item['write_off_amount'],
                ];
            }

            return null;
        })->filter()->values();

        return view('write_off.write_off_details', compact(
            'writeOff',
            'invoicesData',
            'creditNotesData'
        ));
    }

    public function download($id)
    {
        $writeOff = WriteOffs::findOrFail($id);

        /* ----------------------------------------
       1️⃣ Collect UNIQUE customers
    -----------------------------------------*/
        $customerNames = [];
        $customerIds   = [];

        // From invoices
        foreach ($writeOff->invoice_or_cheque_no ?? [] as $item) {
            $invoice = Invoices::where('invoice_or_cheque_no', $item['invoice'])->first();
            if ($invoice) {
                $customer = Customers::where('customer_id', $invoice->customer_id)->first();
                if ($customer) {
                    $customerNames[$customer->customer_id] = $customer->name;
                    $customerIds[$customer->customer_id]   = $customer->customer_id;
                }
            }
        }

        // From credit notes & extra payments
        foreach ($writeOff->extraPayment_or_creditNote_no ?? [] as $item) {

            if ($item['type'] === 'extra_payment') {
                $extra = ExtraPayment::where('extra_payment_id', $item['id'])->first();
                if ($extra) {
                    $customerNames[$extra->customer_id] = $extra->customer_name;
                    $customerIds[$extra->customer_id]   = $extra->customer_id;
                }
            }

            if ($item['type'] === 'credit_note') {
                $credit = CreditNote::where('credit_note_id', $item['id'])->first();
                if ($credit) {
                    $customerNames[$credit->customer_id] = $credit->customer_name;
                    $customerIds[$credit->customer_id]   = $credit->customer_id;
                }
            }
        }

        /* ----------------------------------------
       2️⃣ Prepare Excel
    -----------------------------------------*/
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Base headers
        $headers = [
            'WO ID',
            'WO Date',
            'WO Amount',
            'Customer Name',
            'Customer Number',
            'Document Number',
        ];

        // Dynamic GL headers
        foreach ($writeOff->gl_breakdown ?? [] as $gl) {
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

        $sheet->setCellValue($col++ . $row, $writeOff->id);
        $sheet->setCellValue($col++ . $row, $writeOff->created_at->format('Y-m-d'));
        $sheet->setCellValue($col++ . $row, $writeOff->final_amount);
        $sheet->setCellValue($col++ . $row, implode(', ', $customerNames));
        $sheet->setCellValue($col++ . $row, implode(', ', $customerIds));
        $sheet->setCellValue($col++ . $row, ''); // Document Number (empty)

        // GL values
        foreach ($writeOff->gl_breakdown ?? [] as $gl) {
            $sheet->setCellValue($col++ . $row, $gl['amount']);
        }

        /* ----------------------------------------
       4️⃣ Download
    -----------------------------------------*/
        $fileName = "write_off_{$writeOff->id}.xlsx";
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
