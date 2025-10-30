<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Customers;
use App\Models\Invoices;
use App\Models\CreditNote;
use App\Models\SetOffs;
use Illuminate\Support\Facades\DB;

class SetOffController extends Controller
{
    public function index()
    {
        $customers = Customers::select('customer_id', 'name')->get();
        return view('finance::set_off.set_off', compact('customers'));
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

    public function submitSetOff(Request $request)
    {
        $request->validate([
            'set_off_invoices' => 'nullable|array',
            'set_off_credit_notes' => 'nullable|array',
            'final_amount' => 'required|numeric',
            'reason' => 'nullable|string|max:255',
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

            // ✅ Handle Credit Notes (update remaining amount, but don’t affect final total)
            foreach ($setOffCreditNotes as $cnNo => $setOffAmount) {
                $credit = CreditNote::where('credit_note_id', $cnNo)->first();
                if (!$credit) continue;

                $setOffAmount = floatval($setOffAmount);

                // Determine current available amount
                $currentAmount = $credit->updated_amount ?? $credit->amount;
                $newRemaining = max(0, $currentAmount - $setOffAmount);

                // Update the credit note's updated_amount
                $credit->update(['updated_amount' => $newRemaining]);

                $creditNoteJson[] = [
                    'credit_note' => $cnNo,
                    'set_off_amount' => $setOffAmount,
                    'remaining_balance' => $newRemaining
                ];
            }

            // ✅ Save Set-Off Record (final_amount only from invoices)
            SetOffs::create([
                'invoice_or_cheque_no' => $invoiceJson,
                'extraPayment_or_creditNote_no' => $creditNoteJson,
                'final_amount' => $finalAmount,
                'reason' => $reason,
            ]);

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

        return view('finance::set_off.set_off_main', compact('setOffs'));
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

        // Prepare Extra Payment/Credit Note data
        $creditNotesData = collect($setOff->extraPayment_or_creditNote_no)->map(function ($item) {
            $credit = CreditNote::where('credit_note_id', $item['credit_note'])->first();
            if (!$credit) return null;

            return [
                'extraPaymentNo' => $item['credit_note'],
                'customerName' => $credit->customer_name ?? '-',
                'customerId' => $credit->customer_id ?? '-',
                'admNo' => $credit->adm_id ?? '-',
                'setOffAmount' => $item['set_off_amount'],
            ];
        })->filter()->values();

        return view('finance::set_off.set_off_details', compact(
            'setOff',
            'invoicesData',
            'creditNotesData'
        ));
    }

    public function download($id)
    {
        // Temporary fake download (for now)
        $setOff = SetOffs::findOrFail($id);

        $content = "Set-Off Receipt\n\n" .
            "Set-Off ID: {$setOff->id}\n" .
            "Final Amount: {$setOff->final_amount}\n" .
            "Date: {$setOff->created_at}\n" .
            "Reason: {$setOff->reason}\n\n" .
            "This is a placeholder receipt. Actual PDF format will be added later.";

        $fileName = "set_off_receipt_{$setOff->id}.txt";

        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', "attachment; filename={$fileName}");
    }
}
