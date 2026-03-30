<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceRequest;
use App\Models\InvoiceRequestPayment;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ShopController extends Controller
{
    /**
     * Show the invoice request list.
     */
    public function invoice_request()
    {
        $invoiceRequests = InvoiceRequest::where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('shop.invoice_request', compact('invoiceRequests'));
    }

    /**
     * Show the form to add a new invoice request.
     */
    public function add_invoice_request()
    {
        return view('shop.add_invoice_request');
    }

    /**
     * Store a newly created invoice request in storage.
     */
    public function store_invoice_request(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:20',
            'address' => 'required|string',
            'invoice_no' => 'required|string|max:100',
            'invoice_date' => 'required|date',
            'total_amount' => 'required|numeric',
        ]);

        try {
            InvoiceRequest::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'mobile_number' => $request->mobile_number,
                'address' => $request->address,
                'invoice_no' => $request->invoice_no,
                'invoice_date' => $request->invoice_date,
                'total_amount' => $request->total_amount,
                'status' => 'pending',
            ]);

             return back()->with('success', 'Invoice request submitted successfully!');
             
        } catch (\Exception $e) {
            return back()->withInput()->with('fail', 'Failed to submit invoice request. ' . $e->getMessage());
        }
    }
    /**
     * Show the collections list.
     */
    public function collections()
    {
        $depositedInvoices = InvoiceRequest::where('user_id', Auth::id())
            ->where('status', 'deposited')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
            
        return view('shop.collection', compact('depositedInvoices'));
    }

    /**
     * Show the form to add a new payment.
     */
    public function add_new_payment()
    {
        // Only load approved invoice requests for payment
        $invoiceRequests = InvoiceRequest::where('user_id', Auth::id())
            ->where('status', 'approved')
            ->orderBy('id', 'desc')
            ->get();
            
        $customers = InvoiceRequest::where('user_id', Auth::id())
            ->where('status', 'approved')
            ->select('name', 'mobile_number', 'address')
            ->distinct()
            ->get();

        return view('shop.add_new_payment', compact('invoiceRequests', 'customers'));
    }

    /**
     * Process the payment selection and store in session.
     */
    public function process_payment_selection(Request $request)
    {
        $request->validate([
            'invoice_ids' => 'required|array',
            'invoice_ids.*' => 'exists:invoice_requests,id',
        ]);

        session(['selected_invoice_ids' => $request->invoice_ids]);

        return redirect()->route('payment_details');
    }

    /**
     * Show the payment details summary.
     */
    public function payment_details()
    {
        $invoiceIds = session('selected_invoice_ids', []);
        
        if (empty($invoiceIds)) {
            return redirect()->route('add_new_payment')->with('fail', 'No invoices selected.');
        }

        $invoiceRequests = InvoiceRequest::whereIn('id', $invoiceIds)->get();
        $totalAmount = $invoiceRequests->sum('total_amount');
        
        // Group by customer for display
        $groupedRequests = $invoiceRequests->groupBy(function($item) {
            return $item->name . ' - ' . $item->mobile_number;
        });

        return view('shop.payment-details', compact('invoiceRequests', 'totalAmount', 'groupedRequests'));
    }

    /**
     * Complete the payment process.
     */
    public function complete_payment()
    {
        $invoiceIds = session('selected_invoice_ids', []);
        
        if (empty($invoiceIds)) {
            return redirect()->route('add_new_payment')->with('fail', 'No invoices selected.');
        }

        try {
            $invoiceRequests = InvoiceRequest::whereIn('id', $invoiceIds)->get();
            $totalAmount = $invoiceRequests->sum('total_amount');

            // 1. Create a payment record
            $payment = InvoiceRequestPayment::create([
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'status' => 'pending', // Payment status as pending
            ]);

            // 2. Update invoice requests status as deposited and link to payment
            InvoiceRequest::whereIn('id', $invoiceIds)->update([
                'status' => 'deposited',
                'payment_id' => $payment->id,
            ]);

            session()->forget('selected_invoice_ids');
            
            return redirect()->route('collections')->with('success', 'Payment submitted successfully!');

        } catch (\Exception $e) {
            return redirect()->route('payment_details')->with('fail', 'Failed to complete payment. ' . $e->getMessage());
        }
    }
}
