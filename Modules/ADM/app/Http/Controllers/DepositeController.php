<?php

namespace Modules\ADM\Http\Controllers;

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
use Illuminate\Support\Str;
use App\Mail\SendReceiptMail;

use App\Models\User;
use App\Models\UserDetails;
use App\Models\Invoices;
use App\Models\Customers;
use App\Models\InvoicePayments;
use App\Models\InvoicePaymentBatches;

use File;
use Mail;
use Image;
use PDF;
class DepositeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 
    public function daily_deposit(Request $request)
    { 
    if ($request->isMethod('get')) {
        $adm_no = UserDetails::where('user_id', Auth::user()->id)->value('adm_number');

        // Get all customers under this ADM
        $customers = Customers::where('adm', $adm_no)->pluck('customer_id');

        // Get all invoices belonging to those customers
        $invoices = Invoices::whereIn('customer_id', $customers)->paginate(15);
        $all_invoices = Invoices::whereIn('customer_id', $customers)->get();
        $all_customers = Customers::where('adm', $adm_no)->get();

        // ✅ Get all invoice payments related to those invoices
        $receipts = InvoicePayments::with(['invoice.customer.admDetails', 'batch'])
            ->whereHas('batch', function($query) {
                $query->where('temp_receipt', 0);
            })
            ->whereHas('invoice', function($query) use ($customers) {
                $query->whereIn('customer_id', $customers);
            })
            ->get();

        return view('adm::deposite.daily_deposit', [
            'receipts' => $receipts,
        ]);
    }

    if($request->isMethod('post')){
        
        try {
        $request->validate([
            'cash_amount'   => 'required',
        ]);

        $discount =  ($request->cash_amount * ($request->cash_discount ?? 0)) / 100;
        $final_payment = $request->cash_amount-$discount;

        if($request->payment_batch_id == ''){
            $payment_batch = new InvoicePaymentBatches();
            $payment_batch->save();  
        }
        else{
            $payment_batch =  InvoicePaymentBatches::where('id', $request->payment_batch_id)->first();
        }


        $payment = new InvoicePayments();
        $payment->invoice_id = $id;
        $payment->batch_id = $payment_batch->id;
        $payment->type = 'cash';
        $payment->is_bulk = 0;
        $payment->amount = $request->cash_amount;
        $payment->discount = $request->cash_discount;
        $payment->final_payment = $final_payment;
        $payment->save();

        $invoice =  Invoices::where('id', $id)->first();
        $invoice->paid_amount = $invoice->paid_amount + $request->cash_amount;
        $invoice->update();


        $invoice= Invoices::where('id', $payment->invoice_id)->first();
        $customer= Customers::where('customer_id', $invoice->customer_id)->first();
        $adm= UserDetails::where('adm_number', $customer->adm)->first();

        $pdf = PDF::loadView('pdfs.collections.receipts.cash', [
            'is_duplicate' => 0,
            'payment' => $payment,
            'invoice' => $invoice,
            'customer' => $customer,
            'adm' => $adm
        ])->setPaper('a4', 'portrait');

        $folder = public_path('uploads/adm/collections/receipts/original/');
        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0755, true);
        }

        $pdf_name = 'receipt_'.$payment->id.'_'.time().'.pdf';
        $filePath = $folder.'/'.$pdf_name;
        $pdf->save($filePath);

        $payment->pdf_path = 'uploads/adm/collections/receipts/original/'.$pdf_name;
        $payment->save();

        Mail::to($customer->email)->send(new SendReceiptMail($payment, $filePath));

        return response()->json([
            'status' => "success",
            'message' => 'Payment added successfully',
            'amount' => $request->cash_amount,
            'discount' => $request->cash_discount,
            'payment_batch_id' => $payment_batch->id,
        ], 201);
        
    }
    catch (\Exception $e) {
        return response()->json([
            'status' => "fail",
            'message' => 'Request failed',
            'error' => $e->getMessage()
        ], 500);
    }    
    }
}

}
