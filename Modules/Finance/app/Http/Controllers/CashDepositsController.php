<?php

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvoicePayments;
use App\Models\Invoices;
use App\Models\Customers;
use App\Models\UserDetails;

class CashDepositsController extends Controller
{
    public function index(Request $request)
    {
        // Fetch invoice payments with pagination
        $cashPayments = InvoicePayments::where('type', 'cash')
            ->with(['invoice.customer.userDetail'])
            ->orderByDesc('created_at')
            ->paginate(10); // ✅ Paginate 10 records per page

        // Transform each record before sending to view
        $cashPayments->getCollection()->transform(function ($payment) {
            // Change pending → deposited
            $status = strtolower($payment->status ?? '');
            if ($status === 'pending') {
                $status = 'deposited';
            }

            return [
                'id' => $payment->id,
                'date' => $payment->created_at ? $payment->created_at->format('Y-m-d') : 'N/A',
                'adm_number' => $payment->invoice->customer->userDetail->adm_number ?? 'N/A',
                'adm_name' => $payment->invoice->customer->userDetail->name ?? 'N/A',
                'amount' => $payment->final_payment ?? 0, // ✅ Use final_payment
                'status' => ucfirst($status ?: 'Deposited'),
            ];
        });

        return view('finance::cash_deposits.cash_deposits', compact('cashPayments'));
    }
}
