<?php

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdvancedPayment;

class AdvancedPaymentsController extends Controller
{
    public function index()
    {
        // Fetch data with pagination (10 per page)
        $payments = \App\Models\AdvancedPayment::with(['admDetails', 'customerData'])
            ->orderBy('created_at', 'desc')
            ->paginate(10); // 👈 Laravel handles pagination automatically

        return view('finance::advanced_payments.index', compact('payments'));
    }

    public function show($id)
    {
        // Find the payment by its ID
        $payment = \App\Models\AdvancedPayment::with(['admDetails', 'customerData'])
            ->findOrFail($id);

        // Pass it to a details view
        return view('finance::advanced_payments.details', compact('payment'));
    }
}
