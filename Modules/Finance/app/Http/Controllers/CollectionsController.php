<?php

namespace Modules\Finance\Http\Controllers;

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
    

   public function all_receipts()
{
    // Receipts where batch->temp_receipt = 0
    $regular_receipts = InvoicePayments::with(['invoice.customer.admDetails', 'batch'])
        ->whereHas('batch', function($query) {
            $query->where('temp_receipt', 0);
        })
        ->paginate(15, ['*'], 'regular_page');

    // Receipts where batch->temp_receipt != 0
    $temp_receipts = InvoicePayments::with(['invoice.customer.admDetails', 'batch'])
        ->whereHas('batch', function($query) {
            $query->where('temp_receipt', '!=', 0);
        })
        ->paginate(5, ['*'], 'temp_page');

    $advanced_payments = AdvancedPayment::with(['customerData','adm.userDetails'])
         ->paginate(15, ['*'], 'advance_page');

    return view('finance::collections.all_receipts', [
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
