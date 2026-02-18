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
use Illuminate\Support\Facades\Log;

use App\Models\User;
use App\Models\UserDetails;
use App\Models\Invoices;
use App\Models\Customers;
use App\Models\InvoicePayments;
use App\Models\InvoicePaymentBatches;
use App\Services\MobitelInstantSmsService;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\Deposits;
use App\Services\ActivitLogService;

use File;
use Mail;
use Image;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
class CollectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $smsService;

    public function __construct(MobitelInstantSmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function collections()
{
    if(Auth::user()->user_role == 6 ){
    $adm_no = UserDetails::where('user_id', Auth::id())->value('adm_number');

    // Eager load related customer data for efficiency
    $invoices = Invoices::with('customer')
        ->whereHas('customer', function ($query) use ($adm_no) {
            $query->where('adm', $adm_no)
                  ->orWhere('secondary_adm', $adm_no);
        })
        ->where(function ($q) {
            $q->whereColumn('amount', '>', 'paid_amount')
            ->orWhereNull('paid_amount');
        })
        ->paginate(15);

    // Fetch all invoices (no pagination) for totals
    $all_invoices = Invoices::with('customer')
        ->whereHas('customer', function ($query) use ($adm_no) {
            $query->where('adm', $adm_no)
                  ->orWhere('secondary_adm', $adm_no);
        })
       ->where(function ($q) { 
            $q->whereColumn('amount', '>', 'paid_amount')
            ->orWhereNull('paid_amount');
        })
        ->get();

    // Get all customers under this ADM (primary or secondary)
    $all_customers = Customers::where('adm', $adm_no)
        ->orWhere('secondary_adm', $adm_no)
        ->get();
    }
    else{
         $invoices = Invoices::with('customer')
         ->where(function ($q) {
            $q->whereColumn('amount', '>', 'paid_amount')
            ->orWhereNull('paid_amount');
        })
         ->paginate(15);
         $all_invoices = Invoices::with('customer')
         ->where(function ($q) {
            $q->whereColumn('amount', '>', 'paid_amount')
            ->orWhereNull('paid_amount');
        })
         ->get();
         $all_customers = Customers::get();
    }
  
 
    return view('adm::collection.collections', [
        'invoices' => $invoices,
        'all_invoices' => $all_invoices,
        'customers' => $all_customers,
    ]);
}


public function bulk_payment()
{
    if(Auth::user()->user_role == 6 ){
    $adm_no = UserDetails::where('user_id', Auth::user()->id)->value('adm_number');

    // Get all customers under this ADM (primary or secondary)
    $customers = Customers::where(function($q) use ($adm_no) {
            $q->where('adm', $adm_no)
              ->orWhere('secondary_adm', $adm_no);
        })
        ->pluck('customer_id');

    // Get invoices of those customers
    $invoices = Invoices::whereIn('customer_id', $customers)
    ->where(function ($q) {
        $q->whereColumn('amount', '>', 'paid_amount')
          ->orWhereNull('paid_amount');
    })
    ->paginate(15);
    $all_invoices = Invoices::whereIn('customer_id', $customers)
    ->where(function ($q) {
        $q->whereColumn('amount', '>', 'paid_amount')
          ->orWhereNull('paid_amount');
    })
    ->get();

    // Get full customer details (primary or secondary ADM)
    $all_customers = Customers::where(function($q) use ($adm_no) {
            $q->where('adm', $adm_no)
              ->orWhere('secondary_adm', $adm_no);
        })
        ->get();
    } else{
        $customers = Customers::pluck('customer_id');

        $invoices = Invoices::whereIn('customer_id', $customers)
        ->where(function ($q) {
            $q->whereColumn('amount', '>', 'paid_amount')
              ->orWhereNull('paid_amount');
        })
        ->paginate(15);
        $all_invoices = Invoices::whereIn('customer_id', $customers)
        ->where(function ($q) {
            $q->whereColumn('amount', '>', 'paid_amount')
              ->orWhereNull('paid_amount');
        })
        ->get();

        $all_customers = Customers::get();
    }

    return view('adm::collection.bulk_payment', [
        'invoices' => $invoices,
        'all_invoices' => $all_invoices,
        'customers' => $all_customers,
    ]);
}

  public function search_invoices(Request $request)
{
    $query = $request->input('query');

    if(Auth::user()->user_role == 6 ){
    $adm_no = UserDetails::where('user_id', Auth::id())->value('adm_number');

    // Fetch invoices with related customer data (include primary + secondary ADM)
    $invoices = Invoices::with('customer')
        ->whereHas('customer', function ($q) use ($adm_no) {
            $q->where('adm', $adm_no)
              ->orWhere('secondary_adm', $adm_no);
        })
        ->where(function ($q) {
            $q->whereColumn('amount', '>', 'paid_amount')
              ->orWhereNull('paid_amount');
        })
        ->where(function ($q) use ($query) {
            $q->where('invoice_or_cheque_no', 'LIKE', "%{$query}%")
              ->orWhereHas('customer', function ($q) use ($query) {
                  $q->where('name', 'LIKE', "%{$query}%");
              });
        })
        ->get();
    }
    else{
         $invoices = Invoices::with('customer')
         ->where(function ($q) use ($query) {
            $q->where('invoice_or_cheque_no', 'LIKE', "%{$query}%")
              ->orWhereHas('customer', function ($q) use ($query) {
                  $q->where('name', 'LIKE', "%{$query}%");
              });
        })
        ->where(function ($q) {
            $q->whereColumn('amount', '>', 'paid_amount')
            ->orWhereNull('paid_amount');
        })
        ->get();
    }

    // Build HTML rows efficiently
    $invoice_data = $invoices->map(function ($invoice) {
        $customer_name = $invoice->customer->name ?? '-';
        return '
            <tr>
                <td><a href="' . url('adm/view-invoice/' . $invoice->id) . '">' . e($invoice->invoice_or_cheque_no) . '</a></td>
                <td>' . e($customer_name) . '</td>
                <td>' . e($invoice->invoice_date) . '</td>
            </tr>';
    })->implode('');

    return response()->json($invoice_data);
}


    public function view_invoice($id,Request $request)
    {
    if($request->isMethod('get')){
        $invoice_details = Invoices::where('id',$id)->first();
        $customer_details = Customers::where('customer_id', $invoice_details->customer_id)->first();
        $payments  = InvoicePayments::where('invoice_id',$id)->get();
        $banks = Bank::all();
        return view('adm::collection.view_invoice', ['invoice_details' => $invoice_details,'customer_details' => $customer_details,'payments' => $payments,'banks' => $banks]);
    }
    }    

    public function add_cash_payment($id,Request $request)
    { 
    if($request->isMethod('post')){
        
        try {
        $request->validate([
            'cash_amount'   => 'required',
        ]);
        $invoice = Invoices::with('customer')->find($id);
        $discount =  ($request->cash_amount * ($request->cash_discount ?? 0)) / 100;
        $final_payment = $request->cash_amount-$discount;

        if($request->payment_batch_id == ''){
            $payment_batch = new InvoicePaymentBatches();
            if(Auth::user()->user_role == 6 ){
                $payment_batch->adm_id = Auth::user()->id;
            }
            else {
                $customer = $invoice->customer;

                $adm_number = !empty($customer->secondary_adm) ? $customer->secondary_adm : $customer->adm;

                $adm = UserDetails::where('adm_number', $adm_number)->first();

                $payment_batch->adm_id = $adm ? $adm->user_id : null; 
            }
            $payment_batch->save();  
        }
        else{
            $payment_batch =  InvoicePaymentBatches::where('id', $request->payment_batch_id)->first();
        }

        $uniqid = uniqid();
        $payment = new InvoicePayments();
        $payment->uniqid = $uniqid;
        $payment->invoice_id = $id;
        $payment->batch_id = $payment_batch->id;
        $payment->type = 'cash';
        $payment->is_bulk = 0;
        $payment->amount = $request->cash_amount;
        $payment->discount = $request->cash_discount;
        $payment->final_payment = $final_payment;
        if(Auth::user()->user_role == 6 ){
            $payment->adm_id = Auth::user()->id;
        }
        else {
            $customer = $invoice->customer;

            $adm_number = !empty($customer->secondary_adm) ? $customer->secondary_adm : $customer->adm;

            $adm = UserDetails::where('adm_number', $adm_number)->first();

            $payment->adm_id = $adm ? $adm->user_id : null; 
        }
        $payment->status = 'pending';
        $payment->save();

        $invoice->paid_amount = $invoice->paid_amount + $request->cash_amount;
        $invoice->update();


        // $pdf = PDF::loadView('pdfs.collections.receipts.cash', [
        //     'is_duplicate' => 0,
        //     'payment' => $payment,
        //     'invoice' => $invoice,
        //     'customer' => $customer,
        //     'adm' => $adm
        // ])->setPaper('a4', 'portrait');
        
        // $updated_payment = InvoicePayments::with(['invoice.customer', 'adm.userDetails'])
        // ->find($payment->id);

        // $pdf = PDF::loadView('pdfs.collections.receipts.pdf', ['payment' => $updated_payment])
        // ->setPaper('a4')
        // ->setOption('margin-bottom', 50);

        // $folder = public_path('uploads/adm/collections/receipts/original/');
        // if (!File::exists($folder)) {
        //     File::makeDirectory($folder, 0755, true);
        // }

        // $pdf_name = 'receipt_'.$uniqid.'_'.time().'.pdf';
        // $filePath = $folder.'/'.$pdf_name;
        // $pdf->save($filePath);

        // $payment->pdf_path = 'uploads/adm/collections/receipts/original/'.$pdf_name;
        // $payment->save();

        // if (!empty($updated_payment->invoice->customer->email)) {
        // try {
        //     Mail::to($updated_payment->invoice->customer->email)->send(new SendReceiptMail($updated_payment, $filePath));
        // } catch (\Exception $e) {
        //     Log::error('Email sending failed for Receipt ID ' . $updated_payment->id . ': ' . $e->getMessage());
        // }
        // }
        
        // $toNumber = preg_replace('/^0/', '94', $updated_payment->invoice->customer->mobile_number ?? '');

        // $smsMessage = "Thank you for your payment.\n";
        // $smsMessage .= "Receipt No: " . $updated_payment->id . "\n";
        // $smsMessage .= "Amount: " . number_format($updated_payment->final_payment, 2) . "\n";
        // $smsMessage .= "Payment Method: " . ucfirst($updated_payment->type) . "\n";
        // $smsMessage .= "ADM No:  " . $updated_payment->adm->userDetails->adm_number . "\n";

        // if (strtolower($updated_payment->type) === 'cheque') {
        //     $smsMessage .= "Cheque No: " . ($updated_payment->cheque_number ?? '-') . "\n";
        //     $smsMessage .= "Deposit Date: " . ($updated_payment->cheque_date ?? '-') . "\n";
        //     $smsMessage .= "Bank: " . ($updated_payment->bank_name ?? '-') . "\n";
        //     $smsMessage .= "Branch: " . ($updated_payment->branch_name ?? '-') . "\n";
        // }

        // $smsMessage .= "\nInvoices:\n";
        // $smsMessage .= " - " . $updated_payment->invoice->invoice_or_cheque_no . " : " . number_format($updated_payment->invoice->amount, 2) . "\n";
        // $smsMessage .= "\nView your receipt:\n" . url('/receipt/view/' . $uniqid.' .');

        // try {
        //     $numbers = [(string) $toNumber];
        //     $this->smsService->sendInstantSms($numbers, $smsMessage, "Receipt");
        //     Log::info('SMS sent to ' . $toNumber, (array)$smsResponse);
        // } catch (\Exception $e) {
        //     Log::error('SMS sending failed for Receipt ID ' . $updated_payment->id . ': ' . $e->getMessage());
        // }

        ActivitLogService::log('collection', "Cash collection added. Invoice: $id, Amount: {$request->cash_amount}"); 

        return response()->json([
            'status' => "success",
            'message' => "Cash collection added. Amount: {$request->cash_amount}",
            'amount' => $request->cash_amount,
            'discount' => $request->cash_discount,
            'payment_batch_id' => $payment_batch->id,
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'status' => "fail",
            'message' => 'Request failed',
            'error' => $e->getMessage()
        ], 500);
    }    
    }
}

public function add_fund_transfer($id,Request $request)
    { 
    if($request->isMethod('post')){
        
        try {
        $request->validate([
            'amount'   => 'required',
            'transfer_date'   => 'required',
            'transfer_reference_number'   => 'required',
            'screenshot'   => 'required',
        ]);
        $invoice = Invoices::with('customer')->find($id);
        $discount =  ($request->amount * ($request->discount ?? 0)) / 100;
        $final_payment = $request->amount-$discount;

        $screenshot_name = time() . '-' . Str::uuid()->toString() .'.' . $request->screenshot->extension();
        $request->screenshot->move(public_path('uploads/adm/collections/fund_transfer_reciepts/'), $screenshot_name);

        if($request->payment_batch_id == ''){
            $payment_batch = new InvoicePaymentBatches();
            if(Auth::user()->user_role == 6 ){
                $payment_batch->adm_id = Auth::user()->id;
            }
            else {
                $customer = $invoice->customer;

                $adm_number = !empty($customer->secondary_adm) ? $customer->secondary_adm : $customer->adm;

                $adm = UserDetails::where('adm_number', $adm_number)->first();

                $payment_batch->adm_id = $adm ? $adm->user_id : null; 
            }
            $payment_batch->save();  
        }
        else{
            $payment_batch =  InvoicePaymentBatches::where('id', $request->payment_batch_id)->first();
        }
        $uniqid = uniqid();
        
        $payment = new InvoicePayments();
        $payment->invoice_id = $id;
        $payment->uniqid = $uniqid;
        $payment->batch_id = $payment_batch->id;
        $payment->type = 'fund-transfer';
        $payment->is_bulk = 0;
        $payment->amount = $request->amount;
        $payment->discount = $request->discount;
        $payment->final_payment = $final_payment;
        $payment->transfer_date = $request->transfer_date;
        $payment->transfer_reference_number = $request->transfer_reference_number;
        $payment->screenshot = $screenshot_name;
        $payment->status = 'pending';
        if(Auth::user()->user_role == 6 ){
            $payment->adm_id = Auth::user()->id;
        }
        else {
            $customer = $invoice->customer;

            $adm_number = !empty($customer->secondary_adm) ? $customer->secondary_adm : $customer->adm;

            $adm = UserDetails::where('adm_number', $adm_number)->first();

            $payment->adm_id = $adm ? $adm->user_id : null; 
        }
        $payment->save();
        
        $invoice->paid_amount = $invoice->paid_amount + $request->amount;
        $invoice->update();

       

        // $pdf = PDF::loadView('pdfs.collections.receipts.fund-transfer', [
        //     'is_duplicate' => 0,
        //     'payment' => $payment,
        //     'invoice' => $invoice,
        //     'customer' => $customer,
        //     'adm' => $adm
        // ])->setPaper('a4', 'portrait');
        // $updated_payment = InvoicePayments::with(['invoice.customer', 'adm.userDetails'])
        // ->find($payment->id);

        // $pdf = PDF::loadView('pdfs.collections.receipts.pdf', ['payment' => $updated_payment])
        // ->setPaper('a4')
        // ->setOption('margin-bottom', 50);

        // $folder = public_path('uploads/adm/collections/receipts/original');
        // if (!File::exists($folder)) {
        //     File::makeDirectory($folder, 0755, true);
        // }

        // $pdf_name = 'receipt_'.$payment->id.'_'.time().'.pdf';
        // $filePath = $folder.'/'.$pdf_name;
        // $pdf->save($filePath);

        // $payment->pdf_path = 'uploads/adm/collections/receipts/original/'.$pdf_name;
        // $payment->save();

        // if (!empty($updated_payment->invoice->customer->email)) {
        // try {
        //     Mail::to($updated_payment->invoice->customer->email)->send(new SendReceiptMail($updated_payment, $filePath));
        // } catch (\Exception $e) {
        //     Log::error('Email sending failed for Receipt ID ' . $updated_payment->id . ': ' . $e->getMessage());
        // }
        // }

        // $toNumber = preg_replace('/^0/', '94', $updated_payment->invoice->customer->mobile_number ?? '');

        // $smsMessage = "Thank you for your payment.\n";
        // $smsMessage .= "Receipt No: " . $updated_payment->id . "\n";
        // $smsMessage .= "Amount: " . number_format($updated_payment->final_payment, 2) . "\n";
        // $smsMessage .= "Payment Method: " . ucfirst($updated_payment->type) . "\n";
        // $smsMessage .= "ADM No:  " . $updated_payment->adm->userDetails->adm_number . "\n";

        // if (strtolower($updated_payment->type) === 'cheque') {
        //     $smsMessage .= "Cheque No: " . ($updated_payment->cheque_number ?? '-') . "\n";
        //     $smsMessage .= "Deposit Date: " . ($updated_payment->cheque_date ?? '-') . "\n";
        //     $smsMessage .= "Bank: " . ($updated_payment->bank_name ?? '-') . "\n";
        //     $smsMessage .= "Branch: " . ($updated_payment->branch_name ?? '-') . "\n";
        // }

        // $smsMessage .= "\nInvoices:\n";
        // $smsMessage .= " - " . $updated_payment->invoice->invoice_or_cheque_no . " : " . number_format($updated_payment->invoice->amount, 2) . "\n";
        // $smsMessage .= "\nView your receipt:\n" . url('/receipt/view/' . $uniqid.' .');

        // try {
        //     $numbers = [(string) $toNumber];
        //     $this->smsService->sendInstantSms($numbers, $smsMessage, "Receipt");
        //     Log::info('SMS sent to ' . $toNumber, (array)$smsResponse);
        // } catch (\Exception $e) {
        //     Log::error('SMS sending failed for Receipt ID ' . $updated_payment->id . ': ' . $e->getMessage());
        // }
        
        // ActivitLogService::log('collection', "Invoice/Receipts saved. Batch ID: {$payment_batch->id}");

        ActivitLogService::log('collection', "Fund transfer collection added. Invoice: $id, Amount: {$request->amount}");

        return response()->json([
            'status' => "success",
            'message' =>  "Fund transfer collectionadded. Amount: {$request->amount}",
            'amount' => $request->amount,
            'discount' => $request->discount,
            'payment_batch_id' => $payment_batch->id,
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'status' => "fail",
            'message' => 'Request failed',
            'error' => $e->getMessage()
        ], 500);
    }    
    }
}

public function add_cheque_payment($id,Request $request)
    { 
    if($request->isMethod('post')){
        
        try {
        $request->validate([
            'cheque_number'   => 'required',
            'cheque_date'   => 'required',
            'cheque_amount'   => 'required',
            'bank_name'   => 'required',
            'branch_name'   => 'required',
            'cheque_image'   => 'required',
        ]);
        $invoice = Invoices::with('customer')->find($id);
        $discount =  ($request->cheque_amount * ($request->discount ?? 0)) / 100;
        $final_payment = $request->cheque_amount-$discount;

        $image_name = time() . '-' . Str::uuid()->toString() .'.' . $request->cheque_image->extension();
        $request->cheque_image->move(public_path('uploads/adm/collections/cheque_images/'), $image_name);

        if($request->payment_batch_id == ''){
            $payment_batch = new InvoicePaymentBatches();
            if(Auth::user()->user_role == 6 ){
                $payment_batch->adm_id = Auth::user()->id;
            }
            else {
                $customer = $invoice->customer;

                $adm_number = !empty($customer->secondary_adm) ? $customer->secondary_adm : $customer->adm;

                $adm = UserDetails::where('adm_number', $adm_number)->first();

                $payment_batch->adm_id = $adm ? $adm->user_id : null; 
            }
            $payment_batch->save();  
        }
        else{
            $payment_batch =  InvoicePaymentBatches::where('id', $request->payment_batch_id)->first();
        }

        $uniqid = uniqid();
        
        $payment = new InvoicePayments();
        $payment->invoice_id = $id;
        $payment->uniqid = $uniqid;
        $payment->batch_id = $payment_batch->id;
        $payment->type = 'cheque';
        $payment->is_bulk = 0;
        $payment->amount = $request->cheque_amount;
        $payment->cheque_amount = $request->cheque_amount;
        $payment->discount = $request->discount;
        $payment->final_payment = $final_payment;
        $payment->cheque_number = $request->cheque_number;
        $payment->cheque_date = $request->cheque_date;
        $payment->cheque_image = $image_name;
        $payment->bank_name = $request->bank_name;
        $payment->branch_name = $request->branch_name;
        $payment->post_dated = $request->post_dated;
        if(Auth::user()->user_role == 6 ){
            $payment->adm_id = Auth::user()->id;
        }
        else {
            $customer = $invoice->customer;

            $adm_number = !empty($customer->secondary_adm) ? $customer->secondary_adm : $customer->adm;

            $adm = UserDetails::where('adm_number', $adm_number)->first();

            $payment->adm_id = $adm ? $adm->user_id : null; 
        }
        $payment->status = 'pending';
        $payment->save();
        
       
        $invoice->paid_amount = $invoice->paid_amount + $request->cheque_amount;
        $invoice->update();


        // $invoice= Invoices::where('id', $payment->invoice_id)->first();
        // $customer= Customers::where('customer_id', $invoice->customer_id)->first();
        // $adm= UserDetails::where('user_id', Auth::user()->id)->first();


        // $pdf = PDF::loadView('pdfs.collections.receipts.cheque', [
        //     'is_duplicate' => 0,
        //     'payment' => $payment,
        //     'invoice' => $invoice,
        //     'customer' => $customer,
        //     'adm' => $adm
        // ])->setPaper('a4', 'portrait');

        // $folder = public_path('uploads/adm/collections/receipts/original');
        // if (!File::exists($folder)) {
        //     File::makeDirectory($folder, 0755, true);
        // }

        // $pdf_name = 'receipt_'.$payment->id.'_'.time().'.pdf';
        // $filePath = $folder.'/'.$pdf_name;
        // $pdf->save($filePath);

        // $payment->pdf_path = 'uploads/adm/collections/receipts/original/'.$pdf_name;
        // $payment->save();

        // Mail::to($customer->email)->send(new SendReceiptMail($payment, $filePath));

        ActivitLogService::log('collection', "Cheque collection added. Invoice: $id, Cheque No: {$request->cheque_number}, Amount: {$request->cheque_amount}");

        return response()->json([
            'status' => "success",
            'message' =>  "Cheque collection added. Amount: {$request->cheque_amount}",
            'amount' => $request->cheque_amount,
            'discount' => $request->discount,
            'payment_batch_id' => $payment_batch->id,
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'status' => "fail",
            'message' => 'Request failed',
            'error' => $e->getMessage()
        ], 500);
    }    
    }
}

public function add_card_payment($id,Request $request)
    { 
    if($request->isMethod('post')){
        
        try {
        $request->validate([
            'card_amount'   => 'required',
            'card_transfer_date'   => 'required',
            'card_screenshot'   => 'required',
        ]);
        $invoice = Invoices::with('customer')->find($id);
        $discount =  ($request->card_amount * ($request->card_discount ?? 0)) / 100;
        $final_payment = $request->card_amount-$discount;

        $image_name = time() . '-' . Str::uuid()->toString() .'.' . $request->card_screenshot->extension();
        $request->card_screenshot->move(public_path('uploads/adm/collections/card_screenshots/'), $image_name);

        if($request->payment_batch_id == ''){
            $payment_batch = new InvoicePaymentBatches();
            if(Auth::user()->user_role == 6 ){
                $payment_batch->adm_id = Auth::user()->id;
            }
            else {
                $customer = $invoice->customer;

                $adm_number = !empty($customer->secondary_adm) ? $customer->secondary_adm : $customer->adm;

                $adm = UserDetails::where('adm_number', $adm_number)->first();

                $payment_batch->adm_id = $adm ? $adm->user_id : null; 
            }
            $payment_batch->save();  
        }
        else{
            $payment_batch =  InvoicePaymentBatches::where('id', $request->payment_batch_id)->first();
        }
        $uniqid = uniqid();
       
        $payment = new InvoicePayments();
        $payment->uniqid = $uniqid;
        $payment->invoice_id = $id;
        $payment->batch_id = $payment_batch->id;
        $payment->type = 'card';
        $payment->is_bulk = 0;
        $payment->amount = $request->card_amount;
        $payment->discount = $request->card_discount;
        $payment->card_transfer_date = $request->card_transfer_date;
        $payment->final_payment = $final_payment;
        $payment->card_image = $image_name;
        if(Auth::user()->user_role == 6 ){
            $payment->adm_id = Auth::user()->id;
        }
        else {
            $customer = $invoice->customer;

            $adm_number = !empty($customer->secondary_adm) ? $customer->secondary_adm : $customer->adm;

            $adm = UserDetails::where('adm_number', $adm_number)->first();

            $payment->adm_id = $adm ? $adm->user_id : null; 
        }
        $payment->status = 'pending';
        $payment->save();
        
        $invoice->paid_amount = $invoice->paid_amount + $request->card_amount;
        $invoice->update();


        // $invoice= Invoices::where('id', $payment->invoice_id)->first();
        // $customer= Customers::where('customer_id', $invoice->customer_id)->first();
        // $adm= UserDetails::where('user_id', Auth::user()->id)->first();


        // $pdf = PDF::loadView('pdfs.collections.receipts.card', [
        //     'is_duplicate' => 0,
        //     'payment' => $payment,
        //     'invoice' => $invoice,
        //     'customer' => $customer,
        //     'adm' => $adm
        // ])->setPaper('a4', 'portrait');

        // $folder = public_path('uploads/adm/collections/receipts/original');
        // if (!File::exists($folder)) {
        //     File::makeDirectory($folder, 0755, true);
        // }

        // $pdf_name = 'receipt_'.$payment->id.'_'.time().'.pdf';
        // $filePath = $folder.'/'.$pdf_name;
        // $pdf->save($filePath);

        // $payment->pdf_path = 'uploads/adm/collections/receipts/original/'.$pdf_name;
        // $payment->save();

        // Mail::to($customer->email)->send(new SendReceiptMail($payment, $filePath));

        ActivitLogService::log('collection', "Card collection added. Invoice: $id, Amount: {$request->card_amount}");

        return response()->json([
            'status' => "success",
            'message' =>  "Card collection added. Amount: {$request->card_amount}",
            'amount' => $request->card_amount,
            'discount' => $request->card_discount,
            'payment_batch_id' => $payment_batch->id,
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'status' => "fail",
            'message' => 'Request failed',
            'error' => $e->getMessage()
        ], 500);
    }    
    }
}

public function save_invoice($id,Request $request)
    { 
    if($request->isMethod('post')){
        
        try {
        $request->validate([
            'adm_signature'   => 'required',
            'payment_batch_id'   => 'required',

        ]);

        $admSignature = $request->adm_signature;
        $admSignaturePath = null;

        if (strpos($admSignature, 'data:image/png;base64') === 0) {
            $admSignature = str_replace('data:image/png;base64,', '', $admSignature);
            $admSignature = str_replace(' ', '+', $admSignature);
            $admSignatureName = 'adm_signature_' . Str::random(10) . '.png';
            $admSignaturePath = public_path('uploads/adm/collections/signatures/adm/' . $admSignatureName);
            File::ensureDirectoryExists(public_path('uploads/adm/collections/signatures/adm'));
            File::put($admSignaturePath, base64_decode($admSignature));
        }

        $customerSignaturePath = null;
        
        if ($request->customer_signature && strpos($request->customer_signature, 'data:image/png;base64') === 0) {
            $customerSignature = str_replace('data:image/png;base64,', '', $request->customer_signature);
            $customerSignature = str_replace(' ', '+', $customerSignature);
            $customerSignatureName = 'customer_signature_' . Str::random(10) . '.png';
            $customerSignaturePath = public_path('uploads/adm/collections/signatures/customer/' . $customerSignatureName);
            File::ensureDirectoryExists(public_path('uploads/adm/collections/signatures/customer'));
            File::put($customerSignaturePath, base64_decode($customerSignature));
        }

        $batch = InvoicePaymentBatches::where('id', $request->payment_batch_id)->first();
        $batch->adm_signature = $admSignatureName ?? '';
        $batch->temp_receipt =  $request->temp_receipt;
        $batch->customer_signature = $customerSignatureName ?? '';
        $batch->reason_for_temp = $request->reason_for_temp;
        $batch->update();
        $is_temp = 0;
        if( $request->temp_receipt == 1){
        $is_temp = 1;    
        }
        $batch = InvoicePaymentBatches::with([
            'payments.invoice.customer',
            'payments.adm.userDetails'
        ])->findOrFail($request->payment_batch_id);

        $receiptPaths = [];
        
        foreach ($batch->payments as $payment) {

            $uniqid = $payment->uniqid;

            $pdf = PDF::loadView('pdfs.collections.receipts.pdf', [
                'payment' => $payment,
                'batch'   => $batch,
                'is_duplicate'   => 0,
                'is_temp'   => $is_temp,
            ])
            ->setPaper('a4', 'portrait')
            ->setOption('margin-bottom', 50);

            $folder = public_path('uploads/adm/collections/receipts/original/');
            File::ensureDirectoryExists($folder);

            $pdfName = 'receipt_' . $uniqid . '_' . time() . '.pdf';
            $filePath = $folder . $pdfName;

            $pdf->save($filePath);

            $payment->pdf_path = 'uploads/adm/collections/receipts/original/' . $pdfName;
            $payment->save();

            $receiptPaths[] = $filePath;

            $customerEmail = $payment->invoice->customer->email ?? null;

            if (!empty($customerEmail) && !empty($payment->pdf_path)) {
                try {
                    Mail::to($customerEmail)->send(
                        new SendReceiptMail($payment, $filePath, 0)
                    );
                } catch (\Exception $e) {
                    Log::error(
                        'Email sending failed for Payment ID '
                        . $payment->id . ': ' . $e->getMessage()
                    );
                }
            }

            $toNumber = preg_replace(
                '/^0/',
                '94',
                $payment->invoice->customer->mobile_number ?? ''
            );
 
            if (empty($toNumber)) {
                continue;
            }

            $smsMessage  = "Thank you for your payment.\n";
            $smsMessage .= "Receipt No: " . $payment->uniqid . "\n";
            $smsMessage .= "Amount: LKR " . number_format($payment->final_payment, 2) . "\n";
            $smsMessage .= "Payment Method: " . ucfirst($payment->type) . "\n";
            $smsMessage .= "ADM No: "
                . ($payment->adm->userDetails->adm_number ?? '-') . "\n";

            if (strtolower($payment->type) === 'cheque') {
                $smsMessage .= "Cheque No: " . ($payment->cheque_number ?? '-') . "\n";
                $smsMessage .= "Deposit Date: " . ($payment->cheque_date ?? '-') . "\n";
                $smsMessage .= "Bank: " . ($payment->bank_name ?? '-') . "\n";
                $smsMessage .= "Branch: " . ($payment->branch_name ?? '-') . "\n";
            }

            $smsMessage .= "\nInvoice:\n";
            $smsMessage .= $payment->invoice->invoice_or_cheque_no
                . " - LKR " . number_format($payment->invoice->amount, 2) . "\n";

            $smsMessage .= "\nView Receipt:\n"
                . url('/receipt/view/original/' . $payment->uniqid.' .');

            try {
                $this->smsService->sendInstantSms(
                    [(string) $toNumber],
                    $smsMessage,
                    "Receipt"
                );

                Log::info(
                    'SMS sent to ' . $toNumber
                    . ' (Payment ID: ' . $payment->id . ')'
                );

            } catch (\Exception $e) {
                Log::error(
                    'SMS sending failed for Payment ID '
                    . $payment->id . ': ' . $e->getMessage()
                );
            }

            if($payment->type == 'fund-transfer'){

                
                $deposit = new Deposits();
                $deposit->type = 'fund-transfer';
                $deposit->date_time = $payment->transfer_date;
                $deposit->amount = $payment->final_payment;
                $deposit->reciepts = [
                    ['reciept_id' => (string)$payment->id]
                ];
               if(Auth::user()->user_role == 6 ){
                    $deposit->adm_id = Auth::user()->id;
                }
                else {
                    $customer = $payment->invoice->customer;

                    $adm_number = !empty($customer->secondary_adm) ? $customer->secondary_adm : $customer->adm;

                    $adm = UserDetails::where('adm_number', $adm_number)->first();

                    $deposit->adm_id = $adm ? $adm->user_id : null; 
                }
                $deposit->status = 'pending';
                $deposit->attachment_path = $payment->screenshot;
                $deposit->save();
            }
        }
        return response()->json([
            'status' => "success",
            'message' => 'Collection Details Saved',
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
public function resend_receipt($id)
{
    $payment = InvoicePayments::with([
        'invoice.customer',
        'adm.userDetails',
        'batch'
    ])->findOrFail($id);

    $customer = $payment->invoice->customer;

    $folderPath = public_path('uploads/adm/collections/receipts/duplicates');
    File::ensureDirectoryExists($folderPath);

    if (!empty($payment->duplicate_pdf) && File::exists(public_path($payment->duplicate_pdf))) {

        $filePath = public_path($payment->duplicate_pdf);

    } else {

        $pdfName = 'duplicate_receipt_' . $payment->uniqid . '_' . time() . '.pdf';
        $filePath = $folderPath . '/' . $pdfName;

        $pdf = PDF::loadView('pdfs.collections.receipts.pdf', [
            'payment'      => $payment,
            'batch'        => $payment->batch,
            'is_duplicate' => 1,
            'is_temp' => 0,
        ])
        ->setPaper('a4', 'portrait')
        ->setOption('margin-bottom', 50);

        $pdf->save($filePath);

        $payment->duplicate_pdf = 'uploads/adm/collections/receipts/duplicates/' . $pdfName;
        $payment->save();
    }

    if (!empty($customer->email)) {
        try {
            Mail::to($customer->email)->send(
                new SendReceiptMail($payment, $filePath, 1)
            );
        } catch (\Exception $e) {
            Log::error(
                'Duplicate receipt email failed for Payment ID '
                . $payment->id . ': ' . $e->getMessage()
            );
        }
    }

     $toNumber = preg_replace(
        '/^0/',
        '94',
        $customer->mobile_number ?? ''
    );

    if (!empty($toNumber)) {

        $smsMessage  = "You have reqeusted a copy of a reciept \n";
        $smsMessage .= "Receipt No: " . $payment->uniqid . "\n";
        $smsMessage .= "Amount: LKR " . number_format($payment->final_payment, 2) . "\n";
        $smsMessage .= "Payment Method: " . ucfirst($payment->type) . "\n";
        $smsMessage .= "ADM No: "
            . ($payment->adm->userDetails->adm_number ?? '-') . "\n";

        if (strtolower($payment->type) === 'cheque') {
            $smsMessage .= "Cheque No: " . ($payment->cheque_number ?? '-') . "\n";
            $smsMessage .= "Deposit Date: " . ($payment->cheque_date ?? '-') . "\n";
            $smsMessage .= "Bank: " . ($payment->bank_name ?? '-') . "\n";
            $smsMessage .= "Branch: " . ($payment->branch_name ?? '-') . "\n";
        }

        $smsMessage .= "\nInvoice:\n";
        $smsMessage .= $payment->invoice->invoice_or_cheque_no
            . " - LKR " . number_format($payment->invoice->amount, 2) . "\n";

        $smsMessage .= "\nView Receipt:\n"
            . url('/receipt/view/duplicate/' . $payment->uniqid.' .');

        try {
            $this->smsService->sendInstantSms(
                [(string) $toNumber],
                $smsMessage,
                "Receipt"
            );

            Log::info(
                'Resend SMS sent to ' . $toNumber
                . ' (Payment ID: ' . $payment->id . ')'
            );

        } catch (\Exception $e) {
            Log::error(
                'Resend SMS failed for Payment ID '
                . $payment->id . ': ' . $e->getMessage()
            );
        }
    }


    return back()->with(
        'success',
        'Duplicate receipt resent successfully to the customer.'
    );
}

    public function search_bulk_payment(Request $request)
    {
        $selected_customers = $request->input('selected_customers');

        if (!is_array($selected_customers) || empty($selected_customers)) {
            return response()->json('<tr><td colspan="3">No customers selected.</td></tr>');
        }
    
        $invoices = Invoices::whereIn('customer_id', $selected_customers)->where(function ($q) {
            $q->whereColumn('amount', '>', 'paid_amount')
            ->orWhereNull('paid_amount');
        })
        ->get();

        $invoice_data = '';
        foreach ($invoices as $invoice) {
            $customer = Customers::where('customer_id', $invoice->customer_id)->first();
        
            $invoice_data .= '<tr>
                                <td>
                                    <input 
                                        class="form-check-input form-check-input-table ms-0" 
                                        type="checkbox"
                                        name="invoices[]"
                                        value="' . $invoice->id . '"
                                    >
                                </td>
                                <td style="line-height: 25px;">' . $customer->name . '</td>
                                <td style="line-height: 25px;">' . $invoice->invoice_or_cheque_no . '</td>
                              </tr>';
        }
        

        return response()->json($invoice_data);
    }
    

    public function bulk_payment_submit(Request $request)
    {
        $invoiceIds = $request->query('invoices');
    
        if (empty($invoiceIds)) {
            return redirect()->back('bulk-payment')->with('fail', 'No invoices selected.');
        }
    
        $invoices = Invoices::whereIn('id', $invoiceIds)->get();
    
        $grouped = $invoices->groupBy('customer_id');

        $groupedWithCustomers = $grouped->map(function ($invoices, $customerId) {
            $customer = Customers::where('customer_id', $customerId)->first();
            return [
                'customer' => $customer,
                'invoices' => $invoices
            ];
        });
        $banks = Bank::all();
        return view('adm::collection.bulk_payment_submit', ['grouped_data' => $groupedWithCustomers,'banks' => $banks]);
    }
    
    public function add_bulk_cash_payments(Request $request)
    { 
    if($request->isMethod('post')){
        
        try {
        
        if ($request->payment_batch_id == '') {
            $payment_batch = new InvoicePaymentBatches();
            if(Auth::user()->user_role == 6 ){
                $payment_batch->adm_id = Auth::user()->id;
            }
            else {
                $last_payment = end($request->payments);
                $last_invoice = Invoices::with('customer')->find($last_payment['invoice_id']);
                $last_customer = $last_invoice->customer;
                $adm_number = !empty($last_customer->secondary_adm) ? $last_customer->secondary_adm : $last_customer->adm;
                $adm = UserDetails::where('adm_number', $adm_number)->first();
                $payment_batch->adm_id = $adm ? $adm->user_id : null; 
            }

            $payment_batch->save();
        } else {
            $payment_batch = InvoicePaymentBatches::find($request->payment_batch_id);
        }
        $final_collection_total = 0;
        foreach ($request->payments as $payment) {
            $discount =  ($payment['amount'] * ($payment['discount'] ?? 0)) / 100;
            $final_payment = $payment['amount']-$discount;
            $invoice = Invoices::with('customer')->where('id', $payment['invoice_id'])->first();

            $uniqid = uniqid();
            $payment_data = new InvoicePayments();
            $payment_data->uniqid = $uniqid;
            $payment_data->invoice_id =  $payment['invoice_id'];
            $payment_data->batch_id = $payment_batch->id;
            $payment_data->type = 'cash';
            $payment_data->is_bulk = 1;
            $payment_data->amount = $payment['amount'];
            $payment_data->discount = $payment['discount'];
            $payment_data->final_payment = $final_payment;
            if(Auth::user()->user_role == 6 ){
                $payment_data->adm_id = Auth::user()->id;
            }
            else {
                $customer = $invoice->customer;

                $adm_number = !empty($customer->secondary_adm) ? $customer->secondary_adm : $customer->adm;

                $adm = UserDetails::where('adm_number', $adm_number)->first();

                $payment_data->adm_id = $adm ? $adm->user_id : null; 
            }
            $payment_data->status = 'pending';
            $payment_data->save();

            $invoice->paid_amount = $invoice->paid_amount + $payment['amount'];
            $invoice->update();

            $final_collection_total =  $final_collection_total+$payment['amount'];
            // $invoice= Invoices::where('id', $payment_data->invoice_id)->first();
            // $customer= Customers::where('customer_id', $invoice->customer_id)->first();
            // $adm= UserDetails::where('user_id', Auth::user()->id)->first();
            
            // $pdf = PDF::loadView('pdfs.collections.receipts.cash', [
            //     'is_duplicate' => 0,
            //     'payment' => $payment_data,
            //     'invoice' => $invoice,
            //     'customer' => $customer,
            //     'adm' => $adm
            // ])->setPaper('a4', 'portrait');

            // $folder = public_path('uploads/adm/collections/receipts/original');
            // if (!File::exists($folder)) {
            //     File::makeDirectory($folder, 0755, true);
            // }

            // $pdf_name = 'receipt_'.$payment_data->id.'_'.time().'.pdf';
            // $filePath = $folder.'/'.$pdf_name;
            // $pdf->save($filePath);

            // $payment_data->pdf_path = 'uploads/adm/collections/receipts/original/'.$pdf_name;
            // $payment_data->save();

            // Mail::to($customer->email)->send(new SendReceiptMail($payment_data, $filePath));

            ActivitLogService::log('collection', "Cash collection added. Invoice: {$payment['invoice_id']}, Amount: {$payment['amount']}");
        }
     
        return response()->json([
            'status' => "success",
            'message' => "Cash collections added successfully, Amount: {$final_collection_total}",
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

//  public function add_bulk_fund_transfer(Request $request)
//     { 
//     if($request->isMethod('post')){
        
//         try {
//          $payments = $request->payments;

//         if($request->payment_batch_id == ''){
//             $payment_batch = new InvoicePaymentBatches();
//             $payment_batch->save();  
//         }
//         else{
//             $payment_batch =  InvoicePaymentBatches::where('id', $request->payment_batch_id)->first();
//         }

//         $screenshot_name = null;
//         if ($request->hasFile('screenshot')) {
//             $file = $request->file('screenshot');
//             $screenshot_name = time() . '-' . Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
//             $file->move(public_path('uploads/adm/collections/fund_transfer_reciepts/'), $screenshot_name);
//         }
//         foreach ($payments as $key => $data) {
//             $discount =  ($data['amount'] * ($data['discount'] ?? 0)) / 100;
//             $final_payment = $data['amount']-$discount;


//             $payment_data = new InvoicePayments();
//             $payment_data->invoice_id = $data['invoice_id'];
//             $payment_data->batch_id = $payment_batch->id;
//             $payment_data->type = 'fund-transfer';
//             $payment_data->is_bulk = 1;
//             $payment_data->amount =$data['amount'];
//             $payment_data->discount = $data['discount'];
//             $payment_data->final_payment = $final_payment;
//             $payment_data->transfer_date = $request->transfer_date;
//             $payment_data->transfer_reference_number = $request->transfer_reference_number;
//             $payment_data->screenshot = $screenshot_name;
//             $payment_data->save();

//             $invoice = Invoices::where('id', $data['invoice_id'])->first();
//             $invoice->paid_amount = $invoice->paid_amount + $data['amount'];
//             $invoice->update();

//             $pdf_name ='#'.$payment_data->id.'.pdf';
//             $payment_data = InvoicePayments::where('id', $payment_data->id)->first();
//             $invoice= Invoices::where('id', $payment_data->invoice_id)->first();
//             $customer= Customers::where('customer_id', $invoice->customer_id)->first();
//             $adm= UserDetails::where('adm_number', $customer->adm)->first();

//             $pdf = PDF::loadView('pdfs.collections.receipts.cash', ['is_duplicate' => 0,'payment' => $payment_data,'invoice' => $invoice,'customer' => $customer,'adm' => $adm]);
//             $pdfContent = $pdf->setPaper('a4', 'portrait')->output();

//             Mail::to($customer->email)->send(new SendReceiptMail($pdfContent, $pdf_name, $customer));
//         }

//         return response()->json([
//             'status' => "success",
//             'message' => 'Payments added successfully',
//             'payment_batch_id' => $payment_batch->id,
//         ], 201);
        
//     }
//     catch (\Exception $e) {
//         return response()->json([
//             'status' => "fail",
//             'message' => 'Request failed',
//             'error' => $e->getMessage()
//         ], 500);
//     }    
//     }
// }
public function add_bulk_fund_transfer(Request $request)
{
    if ($request->isMethod('post')) {
        try {
            $payments = $request->input('payments', []);

           if ($request->payment_batch_id == '') {
            $payment_batch = new InvoicePaymentBatches();
            if(Auth::user()->user_role == 6 ){
                $payment_batch->adm_id = Auth::user()->id;
            }
            else {
                $last_payment = end($request->payments);
                $last_invoice = Invoices::with('customer')->find($last_payment['invoice_id']);
                $last_customer = $last_invoice->customer;
                $adm_number = !empty($last_customer->secondary_adm) ? $last_customer->secondary_adm : $last_customer->adm;
                $adm = UserDetails::where('adm_number', $adm_number)->first();
                $payment_batch->adm_id = $adm ? $adm->user_id : null; 
            }

            $payment_batch->save();
            } else {
                $payment_batch = InvoicePaymentBatches::find($request->payment_batch_id);
            }

            $screenshot_name = null;
            if ($request->hasFile('screenshot')) {
                $file = $request->file('screenshot');
                $screenshot_name = time() . '-' . Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/adm/collections/fund_transfer_reciepts/'), $screenshot_name);
            }
            $final_collection_total = 0;
            foreach ($payments as $data) {
                $amount = $data['amount'];
                $discountValue = $data['discount'] ?? 0;

                // if discount is % convert, otherwise keep as flat
                $discount = is_numeric($discountValue) && $discountValue < 100
                    ? ($amount * $discountValue / 100)
                    : $discountValue;

                $final_payment = $amount - $discount;
                $uniqid = uniqid();
                $invoice = Invoices::with('customer')->where('id', $data['invoice_id'])->first();
                $payment_data = new InvoicePayments();
                $payment_data->invoice_id = $data['invoice_id'];
                $payment_data->uniqid = $uniqid;
                $payment_data->batch_id = $payment_batch->id;
                $payment_data->type = 'fund-transfer';
                $payment_data->is_bulk = 1;
                $payment_data->amount = $amount;
                $payment_data->discount = $discountValue;
                $payment_data->final_payment = $final_payment;
                $payment_data->transfer_date = $request->transfer_date;
                $payment_data->transfer_reference_number = $request->transfer_reference_number;
                $payment_data->screenshot = $screenshot_name;
                 if(Auth::user()->user_role == 6 ){
                $payment_data->adm_id = Auth::user()->id;
                }
                else {
                    $customer = $invoice->customer;

                    $adm_number = !empty($customer->secondary_adm) ? $customer->secondary_adm : $customer->adm;

                    $adm = UserDetails::where('adm_number', $adm_number)->first();

                    $payment_data->adm_id = $adm ? $adm->user_id : null; 
                }
                $payment_data->status = 'pending';
                $payment_data->save();

                // update invoice
                $invoice->paid_amount += $amount;
                $invoice->save();

                $final_collection_total = $final_collection_total + $amount;
                // generate receipt + email
                // $customer = Customers::where('customer_id', $invoice->customer_id)->first();
                // $adm= UserDetails::where('user_id', Auth::user()->id)->first();

                // $pdf = PDF::loadView('pdfs.collections.receipts.fund-transfer', [
                // 'is_duplicate' => 0,
                // 'payment' => $payment_data,
                // 'invoice' => $invoice,
                // 'customer' => $customer,
                // 'adm' => $adm
                // ])->setPaper('a4', 'portrait');

                // $folder = public_path('uploads/adm/collections/receipts/original');
                // if (!File::exists($folder)) {
                //     File::makeDirectory($folder, 0755, true);
                // }

                // $pdf_name = 'receipt_'.$payment_data->id.'_'.time().'.pdf';
                // $filePath = $folder.'/'.$pdf_name;
                // $pdf->save($filePath);

                // $payment_data->pdf_path = 'uploads/adm/collections/receipts/original/'.$pdf_name;
                // $payment_data->save();

                // Mail::to($customer->email)->send(new SendReceiptMail($payment_data, $filePath));

                 ActivitLogService::log('collection', "Fund transfer collection added. Invoice: {$data['invoice_id']}, Amount: {$amount}");
            }

            return response()->json([
                'status' => "success",
                'message' => "Fund transfer collections added successfully, Amount: {$final_collection_total}",
                'payment_batch_id' => $payment_batch->id,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => "fail",
                'message' => 'Request failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

// public function add_bulk_cheque_payment(Request $request)
//     { 
//     if($request->isMethod('post')){
        
//         try {
//          $payments = $request->payments;

//         if($request->payment_batch_id == ''){
//             $payment_batch = new InvoicePaymentBatches();
//             $payment_batch->save();  
//         }
//         else{
//             $payment_batch =  InvoicePaymentBatches::where('id', $request->payment_batch_id)->first();
//         }

//         $image_name = null;
//         if ($request->hasFile('cheque_image')) {
//             $file = $request->file('cheque_image');
//             $image_name = time() . '-' . Str::uuid()->toString() .'.' . $file->getClientOriginalExtension();
//             $file->move(public_path('uploads/adm/collections/cheque_images/'), $image_name);
//         }
//         foreach ($payments as $key => $data) {
//             $discount =  ($data['amount'] * ($data['discount'] ?? 0)) / 100;
//             $final_payment = $data['amount']-$discount;

//             $payment_data = new InvoicePayments();
//             $payment_data->invoice_id = $data['invoice_id'];
//             $payment_data->batch_id = $payment_batch->id;
//             $payment_data->type = 'cheque';
//             $payment_data->is_bulk = 1;
//             $payment_data->amount = $data['amount'];
//             $payment_data->discount = $data['discount'];
//             $payment_data->cheque_amount = $request->cheque_amount;
//             $payment_data->final_payment = $final_payment;
//             $payment_data->cheque_number = $request->cheque_number;
//             $payment_data->cheque_date = $request->cheque_date;
//             $payment_data->cheque_image = $image_name;
//             $payment_data->bank_name = $request->bank_name;
//             $payment_data->branch_name = $request->branch_name;
//             $payment_data->post_dated = $request->post_dated;
//             $payment_data->save();

//             $invoice = Invoices::where('id', $data['invoice_id'])->first();
//             $invoice->paid_amount = $invoice->paid_amount + $data['amount'];
//             $invoice->update();

//             $pdf_name ='#'.$payment_data->id.'.pdf';
//             $payment_data = InvoicePayments::where('id', $payment_data->id)->first();
//             $invoice= Invoices::where('id', $payment_data->invoice_id)->first();
//             $customer= Customers::where('customer_id', $invoice->customer_id)->first();
//             $adm= UserDetails::where('adm_number', $customer->adm)->first();

//             $pdf = PDF::loadView('pdfs.collections.receipts.cash', ['is_duplicate' => 0,'payment' => $payment_data,'invoice' => $invoice,'customer' => $customer,'adm' => $adm]);
//             $pdfContent = $pdf->setPaper('a4', 'portrait')->output();

//             Mail::to($customer->email)->send(new SendReceiptMail($pdfContent, $pdf_name, $customer));
//         }

//         return response()->json([
//             'status' => "success",
//             'message' => 'Payments added successfully',
//             'payment_batch_id' => $payment_batch->id,
//         ], 201);
        
//     }
//     catch (\Exception $e) {
//         return response()->json([
//             'status' => "fail",
//             'message' => 'Request failed',
//             'error' => $e->getMessage()
//         ], 500);
//     }    
//     }
// }
public function add_bulk_cheque_payment(Request $request)
{
    if ($request->isMethod('post')) {
        try {
            $payments = $request->payments;

            if ($request->payment_batch_id == '') {
                $payment_batch = new InvoicePaymentBatches();
                if(Auth::user()->user_role == 6 ){
                    $payment_batch->adm_id = Auth::user()->id;
                }
                else {
                    $last_payment = end($request->payments);
                    $last_invoice = Invoices::with('customer')->find($last_payment['invoice_id']);
                    $last_customer = $last_invoice->customer;
                    $adm_number = !empty($last_customer->secondary_adm) ? $last_customer->secondary_adm : $last_customer->adm;
                    $adm = UserDetails::where('adm_number', $adm_number)->first();
                    $payment_batch->adm_id = $adm ? $adm->user_id : null; 
                }

                $payment_batch->save();
            } else {
                $payment_batch = InvoicePaymentBatches::find($request->payment_batch_id);
            }

            $image_name = null;
            if ($request->hasFile('cheque_image')) {
                $file = $request->file('cheque_image');
                $image_name = time() . '-' . Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/adm/collections/cheque_images/'), $image_name);
            }
            $final_collection_total = 0;
            foreach ($payments as $data) {
                $discount = ($data['amount'] * ($data['discount'] ?? 0)) / 100;
                $final_payment = $data['amount'] - $discount;
                $uniqid = uniqid();
                $invoice = Invoices::with('customer')->where('id', $data['invoice_id'])->first();

                $payment_data = new InvoicePayments();
                $payment_data->invoice_id = $data['invoice_id'];
                $payment_data->uniqid = $uniqid;
                $payment_data->batch_id = $payment_batch->id;
                $payment_data->type = 'cheque';
                $payment_data->is_bulk = 1;
                $payment_data->amount = $data['amount'];
                $payment_data->discount = $data['discount'] ?? 0;
                $payment_data->cheque_amount = $request->cheque_amount;
                $payment_data->final_payment = $final_payment;
                $payment_data->cheque_number = $request->cheque_number;
                $payment_data->cheque_date = $request->cheque_date;
                $payment_data->cheque_image = $image_name;
                $payment_data->bank_name = $request->bank_name;
                $payment_data->branch_name = $request->branch_name;
                $payment_data->post_dated = $request->post_dated == 1 ? 1 : 0;
                if(Auth::user()->user_role == 6 ){
                    $payment_data->adm_id = Auth::user()->id;
                }
                else {
                    $customer = $invoice->customer;

                    $adm_number = !empty($customer->secondary_adm) ? $customer->secondary_adm : $customer->adm;

                    $adm = UserDetails::where('adm_number', $adm_number)->first();

                    $payment_data->adm_id = $adm ? $adm->user_id : null; 
                }
                $payment_data->status = 'pending';
                $payment_data->save();

                $invoice->paid_amount += $data['amount'];
                $invoice->save();
                $final_collection_total = $final_collection_total + $data['amount'];
                // Send receipt
                // $customer = Customers::where('customer_id', $invoice->customer_id)->first();
                // $adm= UserDetails::where('user_id', Auth::user()->id)->first();


                // $pdf = PDF::loadView('pdfs.collections.receipts.cheque', [
                // 'is_duplicate' => 0,
                // 'payment' => $payment_data,
                // 'invoice' => $invoice,
                // 'customer' => $customer,
                // 'adm' => $adm
                // ])->setPaper('a4', 'portrait');

                // $folder = public_path('uploads/adm/collections/receipts/original');
                // if (!File::exists($folder)) {
                //     File::makeDirectory($folder, 0755, true);
                // }

                // $pdf_name = 'receipt_'.$payment_data->id.'_'.time().'.pdf';
                // $filePath = $folder.'/'.$pdf_name;
                // $pdf->save($filePath);

                // $payment_data->pdf_path = 'uploads/adm/collections/receipts/original/'.$pdf_name;
                // $payment_data->save();

                // Mail::to($customer->email)->send(new SendReceiptMail($payment_data, $filePath));

                ActivitLogService::log('collection', "Cheque collection added. Invoice: {$data['invoice_id']}, Cheque No: {$request->cheque_number}, Amount: {$request->cheque_amount}");

            }

            return response()->json([
                'status' => "success",
                'message' => "Cheque collections added successfully, Amount: {$final_collection_total}",
                'payment_batch_id' => $payment_batch->id,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => "fail",
                'message' => 'Request failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
public function add_bulk_card_payment(Request $request)
{
    if ($request->isMethod('post')) {
        try {
            $payments = $request->payments;

            if ($request->payment_batch_id == '') {
                $payment_batch = new InvoicePaymentBatches();
                if(Auth::user()->user_role == 6 ){
                    $payment_batch->adm_id = Auth::user()->id;
                }
                else {
                    $last_payment = end($request->payments);
                    $last_invoice = Invoices::with('customer')->find($last_payment['invoice_id']);
                    $last_customer = $last_invoice->customer;
                    $adm_number = !empty($last_customer->secondary_adm) ? $last_customer->secondary_adm : $last_customer->adm;
                    $adm = UserDetails::where('adm_number', $adm_number)->first();
                    $payment_batch->adm_id = $adm ? $adm->user_id : null; 
                }

                $payment_batch->save();
            } else {
                $payment_batch = InvoicePaymentBatches::find($request->payment_batch_id);
            }

            // Handle screenshots (multiple uploads)
           $screenshot_name = null;
            if ($request->hasFile('card_screenshot')) {
                $file = $request->file('card_screenshot');
                $screenshot_name = time() . '-' . Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/adm/collections/card_screenshots/'), $screenshot_name);
            }
            $final_collection_total = 0;
            foreach ($payments as $data) {
                $discount = ($data['amount'] * ($data['discount'] ?? 0)) / 100;
                $final_payment = $data['amount'] - $discount;
                $uniqid = uniqid();
                $invoice = Invoices::with('customer')->where('id', $data['invoice_id'])->first();
                $payment_data = new InvoicePayments();
                $payment_data->invoice_id = $data['invoice_id'];
                $payment_data->uniqid = $uniqid;
                $payment_data->batch_id = $payment_batch->id;
                $payment_data->type = 'card';
                $payment_data->is_bulk = 1;
                $payment_data->amount = $data['amount'];
                $payment_data->discount = $data['discount'] ?? 0;
                $payment_data->final_payment = $final_payment;
                $payment_data->card_transfer_date = $request->card_transfer_date;
                $payment_data->card_image = $screenshot_name;
                 if(Auth::user()->user_role == 6 ){
                    $payment_data->adm_id = Auth::user()->id;
                }
                else {
                    $customer = $invoice->customer;

                    $adm_number = !empty($customer->secondary_adm) ? $customer->secondary_adm : $customer->adm;

                    $adm = UserDetails::where('adm_number', $adm_number)->first();

                    $payment_data->adm_id = $adm ? $adm->user_id : null; 
                }
                $payment_data->status = 'pending';
                $payment_data->save();


                $invoice->paid_amount += $data['amount'];
                $invoice->save();
                $final_collection_total = $final_collection_total + $data['amount'];
                // Send receipt
                // $customer = Customers::where('customer_id', $invoice->customer_id)->first();
                // $adm= UserDetails::where('user_id', Auth::user()->id)->first();

                // $pdf = PDF::loadView('pdfs.collections.receipts.card', [
                // 'is_duplicate' => 0,
                // 'payment' => $payment_data,
                // 'invoice' => $invoice,
                // 'customer' => $customer,
                // 'adm' => $adm
                // ])->setPaper('a4', 'portrait');

                // $folder = public_path('uploads/adm/collections/receipts/original');
                // if (!File::exists($folder)) {
                //     File::makeDirectory($folder, 0755, true);
                // }

                // $pdf_name = 'receipt_'.$payment_data->id.'_'.time().'.pdf';
                // $filePath = $folder.'/'.$pdf_name;
                // $pdf->save($filePath);

                // $payment_data->pdf_path = 'uploads/adm/collections/receipts/original/'.$pdf_name;
                // $payment_data->save();

                // Mail::to($customer->email)->send(new SendReceiptMail($payment_data, $filePath));

                 ActivitLogService::log('collection', "Card collection added. Invoice: {$data['invoice_id']}, Amount: {$data['amount']}");
            }

            return response()->json([
                'status' => "success",
                'message' => "Card collections added successfully, Amount: {$final_collection_total}",
                'payment_batch_id' => $payment_batch->id,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => "fail",
                'message' => 'Request failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

public function save_bulk_payment(Request $request)
    { 
    if($request->isMethod('post')){
        
        try {
        $request->validate([
            'adm_signature'   => 'required',
            'payment_batch_id'   => 'required',

        ]);

        $admSignature = $request->adm_signature;
        $admSignaturePath = null;

        if (strpos($admSignature, 'data:image/png;base64') === 0) {
            $admSignature = str_replace('data:image/png;base64,', '', $admSignature);
            $admSignature = str_replace(' ', '+', $admSignature);
            $admSignatureName = 'adm_signature_' . Str::random(10) . '.png';
            $admSignaturePath = public_path('uploads/adm/collections/signatures/adm/' . $admSignatureName);
            File::ensureDirectoryExists(public_path('uploads/adm/collections/signatures/adm'));
            File::put($admSignaturePath, base64_decode($admSignature));
        }

        $customerSignaturePath = null;
        
        if ($request->customer_signature && strpos($request->customer_signature, 'data:image/png;base64') === 0) {
            $customerSignature = str_replace('data:image/png;base64,', '', $request->customer_signature);
            $customerSignature = str_replace(' ', '+', $customerSignature);
            $customerSignatureName = 'customer_signature_' . Str::random(10) . '.png';
            $customerSignaturePath = public_path('uploads/adm/collections/signatures/customer/' . $customerSignatureName);
            File::ensureDirectoryExists(public_path('uploads/adm/collections/signatures/customer'));
            File::put($customerSignaturePath, base64_decode($customerSignature));
        }

        $batch = InvoicePaymentBatches::where('id', $request->payment_batch_id)->first();
        $batch->adm_signature = $admSignatureName ?? '';
        $batch->temp_receipt =  $request->temp_receipt;
        $batch->customer_signature = $customerSignatureName ?? '';
        $batch->reason_for_temp = $request->reason_for_temp;
        $batch->update();
        $is_temp = 0;
        if( $request->temp_receipt == 1){
        $is_temp = 1;    
        }
        $batch = InvoicePaymentBatches::with([
            'payments.invoice.customer',
            'payments.adm.userDetails'
        ])->findOrFail($request->payment_batch_id);

        $receiptPaths = [];
        
        foreach ($batch->payments as $payment) {

            $uniqid = $payment->uniqid;

            $pdf = PDF::loadView('pdfs.collections.receipts.pdf', [
                'payment' => $payment,
                'batch'   => $batch,
                'is_duplicate'   => 0,
                'is_temp'   => $is_temp,
            ])
            ->setPaper('a4', 'portrait')
            ->setOption('margin-bottom', 50);

            $folder = public_path('uploads/adm/collections/receipts/original/');
            File::ensureDirectoryExists($folder);

            $pdfName = 'receipt_' . $uniqid . '_' . time() . '.pdf';
            $filePath = $folder . $pdfName;

            $pdf->save($filePath);

            $payment->pdf_path = 'uploads/adm/collections/receipts/original/' . $pdfName;
            $payment->save();

            $receiptPaths[] = $filePath;

            $customerEmail = $payment->invoice->customer->email ?? null;

            if (!empty($customerEmail) && !empty($payment->pdf_path)) {
                try {
                    Mail::to($customerEmail)->send(
                        new SendReceiptMail($payment, $filePath, 0)
                    );
                } catch (\Exception $e) {
                    Log::error(
                        'Email sending failed for Payment ID '
                        . $payment->id . ': ' . $e->getMessage()
                    );
                }
            }

            $toNumber = preg_replace(
                '/^0/',
                '94',
                $payment->invoice->customer->mobile_number ?? ''
            );

            if (empty($toNumber)) {
                continue;
            }

            $smsMessage  = "Thank you for your payment.\n";
            $smsMessage .= "Receipt No: " . $payment->uniqid . "\n";
            $smsMessage .= "Amount: LKR " . number_format($payment->final_payment, 2) . "\n";
            $smsMessage .= "Payment Method: " . ucfirst($payment->type) . "\n";
            $smsMessage .= "ADM No: "
                . ($payment->adm->userDetails->adm_number ?? '-') . "\n";

            if (strtolower($payment->type) === 'cheque') {
                $smsMessage .= "Cheque No: " . ($payment->cheque_number ?? '-') . "\n";
                $smsMessage .= "Deposit Date: " . ($payment->cheque_date ?? '-') . "\n";
                $smsMessage .= "Bank: " . ($payment->bank_name ?? '-') . "\n";
                $smsMessage .= "Branch: " . ($payment->branch_name ?? '-') . "\n";
            }

            $smsMessage .= "\nInvoice:\n";
            $smsMessage .= $payment->invoice->invoice_or_cheque_no
                . " - LKR " . number_format($payment->invoice->amount, 2) . "\n";

            $smsMessage .= "\nView Receipt:\n"
                . url('/receipt/view/original/' . $payment->uniqid.' .');

            try {
                $this->smsService->sendInstantSms(
                    [(string) $toNumber],
                    $smsMessage,
                    "Receipt"
                );

                Log::info(
                    'SMS sent to ' . $toNumber
                    . ' (Payment ID: ' . $payment->id . ')'
                );

            } catch (\Exception $e) {
                Log::error(
                    'SMS sending failed for Payment ID '
                    . $payment->id . ': ' . $e->getMessage()
                );
            }

            if($payment->type == 'fund-transfer'){

                
                $deposit = new Deposits();
                $deposit->type = 'fund-transfer';
                $deposit->date_time = $payment->transfer_date;
                $deposit->amount = $payment->final_payment;
                $deposit->reciepts = [
                    ['reciept_id' => (string)$payment->id]
                ];
               if(Auth::user()->user_role == 6 ){
                    $deposit->adm_id = Auth::user()->id;
                }
                else {
                    $customer = $payment->invoice->customer;

                    $adm_number = !empty($customer->secondary_adm) ? $customer->secondary_adm : $customer->adm;

                    $adm = UserDetails::where('adm_number', $adm_number)->first();

                    $deposit->adm_id = $adm ? $adm->user_id : null; 
                }
                $deposit->status = 'pending';
                $deposit->attachment_path = $payment->screenshot;
                $deposit->save();
            }
        }
        return response()->json([
            'status' => "success",
            'message' => 'Collection Details Saved',
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

public function receipts()
{
    $receipts = InvoicePayments::where('adm_id', Auth::id())
    ->with(['invoice.customer'])
    ->paginate(15);

    return view('adm::collection.receipts', [
        'receipts' => $receipts,
    ]);
}

public function temporary_receipts()
{
     $collections = InvoicePaymentBatches::with(['payments', 'admDetails'])
            ->where('temp_receipt', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->through(function ($batch) {
                return [
                    'collection_id' => $batch->id,
                    'collection_date' => $batch->created_at->format('Y-m-d'),
                    'total_collected_amount' => $batch->payments->sum('final_payment'),
                ];
            });

    return view('adm::collection.temporary_receipts', [
        'collections' => $collections,
    ]);
}
public function view_temp_receipt($id,Request $request)
    {
        if($request->isMethod('get')){
        $batch = InvoicePaymentBatches::with([
            'payments.invoice.customer',
        ])->findOrFail($id);

        $payments = $batch->payments->map(function ($payment) {
            return [
                'receipt_no' => $payment->id, // Receipt number = id from invoice_payments
                 'customer_name'   => $payment->invoice?->customer?->name ?? 'N/A',
                'invoice_no' => $payment->invoice_id,
                'status' => $payment->status ?? 'N/A',
                'payment_method' => $payment->type ?? 'N/A',
                'amount' => number_format($payment->final_payment ?? 0, 2),
            ];
        });

        return view('adm::collection.view_temp_receipt', [
            'batch' => $batch,
            'payments' => $payments,
        ]);
    }
    if ($request->isMethod('post')) {
        $request->validate([
            'customer_signature' => 'required',
            'batch_id'           => 'required',
        ]);

        try {
            $batch = InvoicePaymentBatches::where('id', $request->batch_id)
                ->where('temp_receipt', 1)
                ->with('payments.invoice.customer', 'payments.adm.userDetails')
                ->firstOrFail();

            // Save customer signature
            if (strpos($request->customer_signature, 'data:image/png;base64') === 0) {
                $signature = str_replace('data:image/png;base64,', '', $request->customer_signature);
                $signature = str_replace(' ', '+', $signature);

                $signatureName = 'customer_signature_' . Str::random(10) . '.png';

                $folder = public_path('uploads/adm/collections/signatures/customer');
                File::ensureDirectoryExists($folder);

                File::put($folder . '/' . $signatureName, base64_decode($signature));

                $batch->temp_receipt = 0;
                $batch->customer_signature = $signatureName;
                $batch->save();
            }

            foreach ($batch->payments as $payment) {
                $customer = $payment->invoice->customer;

                $folderPath = public_path('uploads/adm/collections/receipts/originals');
                File::ensureDirectoryExists($folderPath);

                $pdfName = 'receipt_' . $payment->uniqid . '_' . time() . '.pdf';
                $filePath = $folderPath . '/' . $pdfName;

                $pdf = PDF::loadView('pdfs.collections.receipts.pdf', [
                    'payment'      => $payment,
                    'batch'        => $batch,
                    'is_duplicate' => 0,
                    'is_temp'      => 0,
                ])
                ->setPaper('a4', 'portrait')
                ->setOption('margin-bottom', 50);

                $pdf->save($filePath);

                $payment->pdf = 'uploads/adm/collections/receipts/originals/' . $pdfName;
                $payment->save();

                if (!empty($customer->email)) {
                    try {
                        Mail::to($customer->email)->send(new SendReceiptMail($payment, $filePath, 0));
                    } catch (\Exception $e) {
                        Log::error(
                            'Original receipt email failed for Payment ID '
                            . $payment->id . ': ' . $e->getMessage()
                        );
                    }
                }

      
                $toNumber = preg_replace('/^0/', '94', $customer->mobile_number ?? '');
                if (!empty($toNumber)) {
                    $smsMessage  = "Your receipt has been generated and sent.\n";
                    $smsMessage .= "Receipt No: " . $payment->uniqid . "\n";
                    $smsMessage .= "Amount: LKR " . number_format($payment->final_payment, 2) . "\n";
                    $smsMessage .= "Payment Method: " . ucfirst($payment->type) . "\n";
                    $smsMessage .= "ADM No: " . ($payment->adm->userDetails->adm_number ?? '-') . "\n";

                    if (strtolower($payment->type) === 'cheque') {
                        $smsMessage .= "Cheque No: " . ($payment->cheque_number ?? '-') . "\n";
                        $smsMessage .= "Deposit Date: " . ($payment->cheque_date ?? '-') . "\n";
                        $smsMessage .= "Bank: " . ($payment->bank_name ?? '-') . "\n";
                        $smsMessage .= "Branch: " . ($payment->branch_name ?? '-') . "\n";
                    }

                    $smsMessage .= "\nInvoice:\n";
                    $smsMessage .= $payment->invoice->invoice_or_cheque_no
                        . " - LKR " . number_format($payment->invoice->amount, 2) . "\n";

                    $smsMessage .= "\nView Receipt:\n" . url('/receipt/view/original/' . $payment->uniqid);

                    try {
                        $this->smsService->sendInstantSms([(string) $toNumber], $smsMessage, "Receipt");
                        Log::info(
                            'Original receipt SMS sent to ' . $toNumber
                            . ' (Payment ID: ' . $payment->id . ')'
                        );
                    } catch (\Exception $e) {
                        Log::error(
                            'Original receipt SMS failed for Payment ID '
                            . $payment->id . ': ' . $e->getMessage()
                        );
                    }
                }
            }

            return redirect('adm/temporary-receipts')->with('success', 'Customer signature saved and original receipts sent successfully.');

        } catch (\Exception $e) {
            Log::error('Error in view_temp_receipt: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to save customer signature or send receipts.');
        }
    }

    }

    function logout()
    {
     Auth::logout();
     return redirect('/');
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
    public function get_branches(Request $request)
{
    $bankId = $request->bank_id;
    $branches = Branch::where('BankID', $bankId)->get(['BranchCode', 'BranchName']);
    return response()->json($branches);
}
}
