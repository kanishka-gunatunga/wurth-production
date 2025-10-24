<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Customers;
use App\Models\Invoices;
use App\Models\CreditNote; // make sure your model is correct

class WriteOffController extends Controller
{
    // Show the write-off page
    public function index()
    {
        // Get all customers (ID + Name)
        $customers = Customers::select('customer_id', 'name')->get();

        return view('finance::write_off.write_off', compact('customers'));
    }

    // Fetch invoices for selected customers (AJAX)
    public function getInvoices(Request $request)
    {
        $customerIds = $request->customer_ids; // array of customer_ids

        $invoices = Invoices::whereIn('customer_id', $customerIds)
            ->select('invoice_or_cheque_no', 'amount', 'customer_id')
            ->get();

        return response()->json($invoices);
    }

    // Fetch credit notes for selected customers (AJAX)
    public function getCreditNotes(Request $request)
    {
        $customerIds = $request->customer_ids; // array of customer_ids

        $creditNotes = CreditNote::whereIn('customer_id', $customerIds)
            ->select('credit_note_id', 'amount', 'customer_id')
            ->get();

        return response()->json($creditNotes);
    }
}
