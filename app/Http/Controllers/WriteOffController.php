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

            // ✅ Save Write-Off Record (final_amount only from invoices)
            WriteOffs::create([
                'invoice_or_cheque_no' => $invoiceJson,
                'extraPayment_or_creditNote_no' => $creditNoteJson,
                'final_amount' => $finalAmount,
                'reason' => $reason,
            ]);

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
        // Temporary fake download (for now)
        $writeOff = WriteOffs::findOrFail($id);

        $content = "Write-Off Receipt\n\n" .
            "Write-Off ID: {$writeOff->id}\n" .
            "Final Amount: {$writeOff->final_amount}\n" .
            "Date: {$writeOff->created_at}\n" .
            "Reason: {$writeOff->reason}\n\n" .
            "This is a placeholder receipt. Actual PDF format will be added later.";

        $fileName = "write_off_receipt_{$writeOff->id}.txt";

        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', "attachment; filename={$fileName}");
    }
}
