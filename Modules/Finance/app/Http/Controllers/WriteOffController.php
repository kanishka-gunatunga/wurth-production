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
            ->select('invoice_or_cheque_no', 'amount', 'customer_id')
            ->get();

        return response()->json($invoices);
    }

    public function getCreditNotes(Request $request)
    {
        $customerIds = $request->customer_ids;

        $creditNotes = CreditNote::whereIn('customer_id', $customerIds)
            ->select('credit_note_id', 'amount', 'customer_id')
            ->get();

        return response()->json($creditNotes);
    }

    public function submitWriteOff(Request $request)
{
    $request->validate([
        'write_off_invoices' => 'nullable|array',
        'write_off_credit_notes' => 'nullable|array',
        'final_amount' => 'required|numeric',
        'reason' => 'required|string|max:255',
    ]);

    $writeOffInvoices = $request->write_off_invoices ?? [];
    $writeOffCreditNotes = $request->write_off_credit_notes ?? [];
    $finalAmount = $request->final_amount;
    $reason = $request->reason;

    if (empty($writeOffInvoices) && empty($writeOffCreditNotes)) {
        return response()->json([
            'success' => false,
            'message' => 'Please select at least one invoice or credit note.'
        ], 422);
    }

    DB::beginTransaction();

    try {
        // Prepare JSON arrays
        $invoiceJson = [];
        foreach ($writeOffInvoices as $invNo => $amount) {
            $invoiceJson[] = ['invoice' => $invNo];
            // Update invoice write_off_amount
            Invoices::where('invoice_or_cheque_no', $invNo)
                ->update(['write_off_amount' => $amount]);
        }

        $creditNoteJson = [];
        foreach ($writeOffCreditNotes as $cnNo => $amount) {
            $creditNoteJson[] = ['credit_note' => $cnNo];
            // Update credit note write_off_amount
            CreditNote::where('credit_note_id', $cnNo)
                ->update(['write_off_amount' => $amount]);
        }

        // Store in write_offs table
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

}
