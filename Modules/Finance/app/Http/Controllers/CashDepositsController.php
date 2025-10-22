<?php

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposits;
use App\Models\UserDetails;
use App\Models\InvoicePayments;
use App\Models\Invoices;
use App\Models\Customers;

class CashDepositsController extends Controller
{
    public function index(Request $request)
    {
        // Fetch only cash deposits
        $cashDeposits = Deposits::where('type', 'cash')
            ->orderByDesc('created_at')
            ->paginate(10);

        // Transform the results before sending to the view
        $cashDeposits->getCollection()->transform(function ($deposit) {
            // Get ADM details from user_details table using adm_id
            $admDetails = UserDetails::where('user_id', $deposit->adm_id)->first();

            // Format status
            $status = strtolower($deposit->status ?? '');
            if ($status === 'pending') {
                $status = 'deposited';
            }

            return [
                'id' => $deposit->id,
                'date' => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
                'adm_number' => $admDetails->adm_number ?? 'N/A',
                'adm_name' => $admDetails->name ?? 'N/A',
                'amount' => $deposit->amount ?? 0,
                'status' => ucfirst($status ?: 'Deposited'),
                'attachment_path' => $deposit->attachment_path ?? null,
            ];
        });

        return view('finance::cash_deposits.cash_deposits', compact('cashDeposits'));
    }

    public function show($id)
    {
        // Get deposit record
        $deposit = Deposits::findOrFail($id);

        // Get ADM details
        $admDetails = UserDetails::where('user_id', $deposit->adm_id)->first();

        // Decode receipts JSON properly
        $decodedReceipts = json_decode($deposit->reciepts, true) ?? [];

        // Extract receipt IDs safely
        $receiptIds = collect($decodedReceipts)->pluck('reciept_id')->toArray();

        // Fetch all matching invoice payments
        $invoicePayments = InvoicePayments::whereIn('id', $receiptIds)->paginate(10);

        // Prepare detailed receipt data
        $receiptDetails = $invoicePayments->map(function ($payment) {
            $invoice = Invoices::find($payment->invoice_id);
            $customer = $invoice ? Customers::where('customer_id', $invoice->customer_id)->first() : null;

            return [
                'receipt_number' => $payment->id,
                'customer_name' => $customer->name ?? 'N/A',
                'customer_id' => $invoice->customer_id ?? 'N/A',
                'paid_date' => $payment->created_at
                    ? date('Y-m-d', strtotime($payment->created_at))
                    : 'N/A',
                'paid_amount' => $payment->final_payment ?? 0,
            ];
        });

        // Define display info
        $admName = $admDetails->name ?? 'N/A';
        $admNumber = $admDetails->adm_number ?? 'N/A';
        $depositDate = $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A';
        $totalAmount = $deposit->amount ?? 0;

        // 🟢 Format status
        $status = strtolower($deposit->status ?? '');
        if ($status === 'pending') {
            $status = 'deposited';
        }
        $status = ucfirst($status ?: 'Deposited');

        return view('finance::cash_deposits.payment_slip', compact(
            'deposit',
            'admName',
            'admNumber',
            'depositDate',
            'totalAmount',
            'receiptDetails',
            'invoicePayments',
            'status' // ✅ pass status to view
        ));
    }

    public function downloadAttachment($id)
    {
        $deposit = Deposits::findOrFail($id);

        if (!$deposit->attachment_path || !file_exists(public_path($deposit->attachment_path))) {
            return back()->with('error', 'No file found for this record.');
        }

        return response()->download(public_path($deposit->attachment_path));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->merge(['status' => ucfirst(strtolower($request->status))]);

        $request->validate([
            'status' => 'required|in:Approved,Rejected',
        ]);


        $deposit = Deposits::findOrFail($id);

        // Update deposit status
        $deposit->status = $request->status;
        $deposit->save();

        // Update related receipts status
        $receiptIds = collect(json_decode($deposit->reciepts, true))
            ->pluck('reciept_id')
            ->toArray();

        if (!empty($receiptIds)) {
            InvoicePayments::whereIn('id', $receiptIds)
                ->update(['status' => $request->status]);
        }

        return response()->json([
            'success' => true,
            'status' => $request->status,
        ]);
    }
}
