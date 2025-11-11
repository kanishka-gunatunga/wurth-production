<?php

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposits;
use App\Models\UserDetails;
use App\Models\InvoicePayments;
use App\Models\Invoices;
use App\Models\Customers;

class FinanceCashController extends Controller
{
    public function index(Request $request)
    {
        // Fetch only finance_cash deposits
        $financeCashDeposits = Deposits::where('type', 'finance_cash')
            ->orderByDesc('created_at')
            ->paginate(10);

        // Transform the results
        $financeCashDeposits->getCollection()->transform(function ($deposit) {
            $admDetails = UserDetails::where('user_id', $deposit->adm_id)->first();

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

        return view('finance::finance_cash.finance_cash', compact('financeCashDeposits'));
    }

    public function show($id)
    {
        $deposit = Deposits::findOrFail($id);
        $admDetails = UserDetails::where('user_id', $deposit->adm_id)->first();

        $decodedReceipts = json_decode($deposit->reciepts, true) ?? [];
        $receiptIds = collect($decodedReceipts)->pluck('reciept_id')->toArray();
        $invoicePayments = InvoicePayments::whereIn('id', $receiptIds)->paginate(10);

        $receiptDetails = $invoicePayments->map(function ($payment) {
            $invoice = Invoices::find($payment->invoice_id);
            $customer = $invoice ? Customers::where('customer_id', $invoice->customer_id)->first() : null;

            return [
                'receipt_number' => $payment->id,
                'customer_name' => $customer->name ?? 'N/A',
                'customer_id' => $invoice->customer_id ?? 'N/A',
                'paid_date' => $payment->created_at ? date('Y-m-d', strtotime($payment->created_at)) : 'N/A',
                'paid_amount' => $payment->final_payment ?? 0,
            ];
        });

        $admName = $admDetails->name ?? 'N/A';
        $admNumber = $admDetails->adm_number ?? 'N/A';
        $depositDate = $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A';
        $totalAmount = $deposit->amount ?? 0;

        $status = strtolower($deposit->status ?? '');
        if ($status === 'pending') {
            $status = 'deposited';
        }
        $status = ucfirst($status ?: 'Deposited');

        return view('finance::finance_cash.payment_slip', compact(
            'deposit',
            'admName',
            'admNumber',
            'depositDate',
            'totalAmount',
            'receiptDetails',
            'invoicePayments',
            'status'
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

        $deposit->status = $request->status;
        $deposit->save();

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
