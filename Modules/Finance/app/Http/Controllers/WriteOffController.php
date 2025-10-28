<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Customers;
use App\Models\Invoices;
use App\Models\CreditNote;
use App\Models\WriteOffs;
use Illuminate\Support\Facades\DB;

class WriteOffController extends Controller
{
    public function index()
    {
        $customers = Customers::select('customer_id', 'name')->get();
        return view('finance::write_off.write_off', compact('customers'));
    }

    public function getInvoices(Request $request)
    {
        $customerIds = $request->customer_ids;

        $invoices = Invoices::whereIn('customer_id', $customerIds)
            ->select('invoice_or_cheque_no', DB::raw('COALESCE(updated_amount, amount) AS amount'), 'customer_id')
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

            // Handle Invoices
            foreach ($writeOffInvoices as $invNo => $writeOffAmount) {
                $invoice = Invoices::where('invoice_or_cheque_no', $invNo)->first();
                if (!$invoice) continue;

                $currentAmount = $invoice->updated_amount ?? $invoice->amount;
                $writeOffAmount = floatval($writeOffAmount);
                $updatedAmount = max(0, $currentAmount - $writeOffAmount);


                // Update invoice
                $invoice->update([
                    'write_off_amount' => $writeOffAmount,
                    'updated_amount' => $updatedAmount,
                ]);

                $invoiceJson[] = [
                    'invoice' => $invNo,
                    'write_off_amount' => $writeOffAmount
                ];

                $finalAmount += $writeOffAmount;
            }

            // 💳 Handle Credit Notes
            foreach ($writeOffCreditNotes as $cnNo => $writeOffAmount) {
                $credit = CreditNote::where('credit_note_id', $cnNo)->first();
                if (!$credit) continue;

                $currentAmount = $credit->updated_amount ?? $credit->amount;
                $writeOffAmount = floatval($writeOffAmount);
                $updatedAmount = max(0, $currentAmount - $writeOffAmount);


                // Update credit note
                $credit->update([
                    'write_off_amount' => $writeOffAmount,
                    'updated_amount' => $updatedAmount,
                ]);

                $creditNoteJson[] = [
                    'credit_note' => $cnNo,
                    'write_off_amount' => $writeOffAmount
                ];
            }

            // Save Write-Off Record
            WriteOffs::create([
                'invoice_or_cheque_no' => $invoiceJson,
                'extraPayment_or_creditNote_no' => $creditNoteJson,
                'final_amount' => $finalAmount,
                'reason' => $reason,
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Write-Off successfully saved!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function main()
    {
        $writeOffs = WriteOffs::select('id', 'final_amount', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('finance::write_off.write_off_main', compact('writeOffs'));
    }
}
