<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\InvoicePayments;
use App\Models\Invoices;
use App\Models\Customers;
use App\Models\UserDetails;

class CardPaymentController extends Controller
{
    public function index(Request $request)
    {
        // Fetch card payments with relations
        $cardPayments = InvoicePayments::with([
            'invoice.customer',      // invoice -> customer
            'invoice.customer.userDetail'  // customer -> user_detail
        ])
            ->where('type', 'card')
            ->orderBy('card_transfer_date', 'desc')
            ->paginate(10); // Laravel pagination

        return view('finance::card_payment.card_payments', compact('cardPayments'));
    }

    public function show($id)
    {
        // Fetch single card payment with related invoice, customer, and user detail
        $cardPayment = InvoicePayments::with([
            'invoice.customer.userDetail'  // invoice -> customer -> user_detail
        ])->findOrFail($id);

        return view('finance::card_payment.card_payment_details', compact('cardPayment'));
    }
}
