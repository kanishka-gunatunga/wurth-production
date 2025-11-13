<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\UserDetails;
use App\Models\Invoices;
use App\Models\Customers;
use App\Models\InvoicePayments;
use App\Models\InvoicePaymentBatches;
use App\Models\AdvancedPayment;

use File;
use Mail;
use Image;
use PDF;
class CollectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
public function all_outstanding(Request $request)
{
    $search = $request->input('search');
    $outstandingRanges = $request->input('adoutstanding_dates', []); // Array of selected ranges

    $invoices = Invoices::with(['customer.admDetails','customer.secondaryAdm'])
        ->where('type', 'invoice')
        ->whereColumn('amount', '>', 'paid_amount')
        ->whereHas('customer', function ($query) {
            $query->where('is_temp', 0);
        });

    // 🔍 Search Filter
    if (!empty($search)) {
        $invoices->where(function ($query) use ($search) {
            $query->where('invoice_or_cheque_no', 'like', "%{$search}%")
                ->orWhereHas('customer', function ($q) use ($search) {
                    $q->where('customers.name', 'like', "%{$search}%");
                })
                ->orWhereHas('customer.admDetails', function ($q) use ($search) {
                    $q->where('user_details.adm_number', 'like', "%{$search}%")
                    ->orWhere('user_details.name', 'like', "%{$search}%");
                })
                ->orWhereHas('customer.secondaryAdm', function ($q) use ($search) {
                    $q->where('user_details.adm_number', 'like', "%{$search}%")
                    ->orWhere('user_details.name', 'like', "%{$search}%");
                });
        });
    }

    // 📅 Outstanding Days Filter
    if (!empty($outstandingRanges)) {
        $invoices->where(function ($query) use ($outstandingRanges) {
            foreach ($outstandingRanges as $range) {
                // Calculate based on current date and invoice_date
                switch ($range) {
                    case '0-30':
                        $query->orWhereRaw('DATEDIFF(NOW(), invoice_date) BETWEEN 0 AND 30');
                        break;
                    case '31-60':
                        $query->orWhereRaw('DATEDIFF(NOW(), invoice_date) BETWEEN 31 AND 60');
                        break;
                    case '61-90':
                        $query->orWhereRaw('DATEDIFF(NOW(), invoice_date) BETWEEN 61 AND 90');
                        break;
                    case '91-120':
                        $query->orWhereRaw('DATEDIFF(NOW(), invoice_date) BETWEEN 91 AND 120');
                        break;
                    case '120-plus':
                        $query->orWhereRaw('DATEDIFF(NOW(), invoice_date) > 120');
                        break;
                }
            }
        });
    }

    $invoices = $invoices->paginate(15)->appends([
        'search' => $search,
        'adoutstanding_dates' => $outstandingRanges,
    ]);

    return view('collections.all_outstanding', compact('invoices', 'search', 'outstandingRanges'));
}

public function all_receipts()
{
    $regular_receipts = InvoicePayments::with(['invoice.customer', 'batch', 'adm.userDetails'])
        ->whereHas('batch', function($query) {
            $query->where('temp_receipt', 0);
        })
        ->paginate(15, ['*'], 'regular_page');

    // Receipts where batch->temp_receipt != 0
    $temp_receipts = InvoicePayments::with(['invoice.customer', 'batch', 'adm.userDetails'])
        ->whereHas('batch', function($query) {
            $query->where('temp_receipt', '!=', 0);
        })
        ->paginate(5, ['*'], 'temp_page');

    $advanced_payments = AdvancedPayment::with(['customerData','adm.userDetails'])
         ->paginate(15, ['*'], 'advance_page');

    return view('collections.all_receipts', [
        'regular_receipts' => $regular_receipts,
        'temp_receipts' => $temp_receipts,
        'advanced_payments' => $advanced_payments,
    ]);
}
public function resend_receipt()
{
     $request->validate([
        'receipt_id' => 'required|exists:invoice_payments,id',
        'mobile' => 'nullable|string',
        'optional_number' => 'nullable|string',
    ]);

    $id = $request->receipt_id;
    $mobile = $request->optional_number ?: $request->mobile;

    if (!$mobile) {
        return back()->with('error', 'No mobile number provided.');
    }

    $payment = InvoicePayments::findOrFail($id);
    $invoice = Invoices::findOrFail($payment->invoice_id);
    $customer = Customers::where('customer_id', $invoice->customer_id)->firstOrFail();
    $adm = UserDetails::where('adm_number', $customer->adm)->first();

    // Folder for saving duplicate receipts
    $folderPath = public_path('uploads/adm/collections/receipts/duplicates');
    if (!File::exists($folderPath)) {
        File::makeDirectory($folderPath, 0755, true);
    }

    // Check if duplicate already exists
    if ($payment->duplicate_pdf && File::exists(public_path($payment->duplicate_pdf))) {
        // Use existing file
        $filePath = public_path($payment->duplicate_pdf);
    } else {
        // Generate new duplicate PDF
        $pdf_name = 'duplicate_receipt_' . $payment->id . '_' . time() . '.pdf';
        $filePath = $folderPath . '/' . $pdf_name;

        // Select correct receipt view by payment type
        switch ($payment->type) {
            case 'cash':
                $view = 'pdfs.collections.receipts.cash';
                break;
            case 'fund-transfer':
                $view = 'pdfs.collections.receipts.fund-transfer';
                break;
            case 'cheque':
                $view = 'pdfs.collections.receipts.cheque';
                break;
            case 'card':
                $view = 'pdfs.collections.receipts.card';
                break;
            default:
                return back()->with('error', 'Invalid payment type');
        }

        // Generate and save PDF
        $pdf = PDF::loadView($view, [
            'is_duplicate' => 1,
            'payment' => $payment,
            'invoice' => $invoice,
            'customer' => $customer,
            'adm' => $adm
        ])->setPaper('a4', 'portrait');

        $pdf->save($filePath);

        $payment->duplicate_pdf = 'uploads/adm/collections/receipts/duplicates/' . $pdf_name;
        $payment->save();
    }

    // Send email with the duplicate PDF attachment
    if ($customer->email) {
        Mail::to($customer->email)->send(new SendReceiptMail($payment, $filePath));
    }

    return back()->with('success', 'Receipt resent successfully to the customer.');
}
public function remove_advanced_payment($id)
{
AdvancedPayment::where('id',$id)->delete();
  return back()->with('success', 'Advanced Payment Removed');
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('admin::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
} 
