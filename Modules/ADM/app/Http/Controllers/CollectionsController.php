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
class CollectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
     public function collections()
     {
         $adm_no = UserDetails::where('user_id', Auth::user()->id)->value('adm_number');
         $customers = Customers::where('adm', $adm_no)->pluck('customer_id'); 
         $invoices = Invoices::whereIn('customer_id', $customers)->paginate(15);
         $all_invoices = Invoices::whereIn('customer_id', $customers)->get();
         $all_customers = Customers::where('adm', $adm_no)->get(); 

         return view('adm::collection.collections', ['invoices' => $invoices,'all_invoices' => $all_invoices,'customers' => $all_customers]);
     }

      public function bulk_payment()
     {
         $adm_no = UserDetails::where('user_id', Auth::user()->id)->value('adm_number');
         $customers = Customers::where('adm', $adm_no)->pluck('customer_id'); 
         $invoices = Invoices::whereIn('customer_id', $customers)->paginate(15);
         $all_invoices = Invoices::whereIn('customer_id', $customers)->get();
         $all_customers = Customers::where('adm', $adm_no)->get(); 

         return view('adm::collection.bulk_payment', ['invoices' => $invoices,'all_invoices' => $all_invoices,'customers' => $all_customers]);
     }
     
    
     public function search_invoices(Request $request)
     {
         $query = $request->input('query');
     
         $adm_no = UserDetails::where('user_id', Auth::user()->id)->value('adm_number');
         $customers = Customers::where('adm', $adm_no)->pluck('customer_id');
     
         $invoices = Invoices::whereIn('customer_id', $customers)
             ->where(function ($q) use ($query) {
                 $q->where('invoice_or_cheque_no', 'LIKE', "%{$query}%")
                   ->orWhereHas('customer', function ($q) use ($query) {
                       $q->where('name', 'LIKE', "%{$query}%");
                   });
             })
             ->get();
             
         $invoice_data = '';
         foreach ($invoices as $invoice) {
             $customer_name = Customers::where('customer_id', $invoice->customer_id)->value('name');
             $invoice_data .= '<tr>
                                 <td><a href="' . url('adm/view-invoice/' . $invoice->id) . '">' . $invoice->invoice_or_cheque_no . '</a></td>
                                 <td>' . $customer_name . '</td>
                                 <td>' . $invoice->invoice_date . '</td>
                               </tr>';
         }
     
         return response()->json($invoice_data);
     }
    public function view_invoice($id,Request $request)
    {
    if($request->isMethod('get')){
        $invoice_details = Invoices::where('id',$id)->first();
        $customer_details = Customers::where('customer_id', $invoice_details->customer_id)->first();
        $payments  = InvoicePayments::where('invoice_id',$id)->get();
        return view('adm::collection.view_invoice', ['invoice_details' => $invoice_details,'customer_details' => $customer_details,'payments' => $payments]);
    }
    }    

    public function add_cash_payment($id,Request $request)
    { 
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

        $discount =  ($request->amount * ($request->discount ?? 0)) / 100;
        $final_payment = $request->amount-$discount;

        $screenshot_name = time() . '-' . Str::uuid()->toString() .'.' . $request->screenshot->extension();
        $request->screenshot->move(public_path('uploads/adm/collections/fund_transfer_reciepts/'), $screenshot_name);

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
        $payment->type = 'fund-transfer';
        $payment->is_bulk = 0;
        $payment->amount = $request->amount;
        $payment->discount = $request->discount;
        $payment->final_payment = $final_payment;
        $payment->transfer_date = $request->transfer_date;
        $payment->transfer_reference_number = $request->transfer_reference_number;
        $payment->screenshot = $screenshot_name;
        $payment->save();
        
        $invoice =  Invoices::where('id', $id)->first();
        $invoice->paid_amount = $invoice->paid_amount + $request->amount;
        $invoice->update();

        $invoice= Invoices::where('id', $payment->invoice_id)->first();
        $customer= Customers::where('customer_id', $invoice->customer_id)->first();
        $adm= UserDetails::where('adm_number', $customer->adm)->first();

        $pdf = PDF::loadView('pdfs.collections.receipts.fund-transfer', [
            'is_duplicate' => 0,
            'payment' => $payment,
            'invoice' => $invoice,
            'customer' => $customer,
            'adm' => $adm
        ])->setPaper('a4', 'portrait');

        $folder = public_path('uploads/adm/collections/receipts/original');
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
            'amount' => $request->amount,
            'discount' => $request->discount,
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

        $discount =  ($request->cheque_amount * ($request->discount ?? 0)) / 100;
        $final_payment = $request->cheque_amount-$discount;

        $image_name = time() . '-' . Str::uuid()->toString() .'.' . $request->cheque_image->extension();
        $request->cheque_image->move(public_path('uploads/adm/collections/cheque_images/'), $image_name);

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
        $payment->save();
        
       
        $invoice =  Invoices::where('id', $id)->first();
        $invoice->paid_amount = $invoice->paid_amount + $request->cheque_amount;
        $invoice->update();


        $invoice= Invoices::where('id', $payment->invoice_id)->first();
        $customer= Customers::where('customer_id', $invoice->customer_id)->first();
        $adm= UserDetails::where('adm_number', $customer->adm)->first();


        $pdf = PDF::loadView('pdfs.collections.receipts.cheque', [
            'is_duplicate' => 0,
            'payment' => $payment,
            'invoice' => $invoice,
            'customer' => $customer,
            'adm' => $adm
        ])->setPaper('a4', 'portrait');

        $folder = public_path('uploads/adm/collections/receipts/original');
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
            'amount' => $request->cheque_amount,
            'discount' => $request->discount,
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

public function add_card_payment($id,Request $request)
    { 
    if($request->isMethod('post')){
        
        try {
        $request->validate([
            'card_amount'   => 'required',
            'card_transfer_date'   => 'required',
            'card_screenshot'   => 'required',
        ]);

        $discount =  ($request->card_amount * ($request->card_discount ?? 0)) / 100;
        $final_payment = $request->card_amount-$discount;

        $image_name = time() . '-' . Str::uuid()->toString() .'.' . $request->card_screenshot->extension();
        $request->card_screenshot->move(public_path('uploads/adm/collections/card_screenshots/'), $image_name);

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
        $payment->type = 'card';
        $payment->is_bulk = 0;
        $payment->amount = $request->card_amount;
        $payment->discount = $request->card_discount;
        $payment->card_transfer_date = $request->card_transfer_date;
        $payment->final_payment = $final_payment;
        $payment->card_image = $image_name;
        $payment->save();
        
       
        $invoice =  Invoices::where('id', $id)->first();
        $invoice->paid_amount = $invoice->paid_amount + $request->card_amount;
        $invoice->update();


        $invoice= Invoices::where('id', $payment->invoice_id)->first();
        $customer= Customers::where('customer_id', $invoice->customer_id)->first();
        $adm= UserDetails::where('adm_number', $customer->adm)->first();


        $pdf = PDF::loadView('pdfs.collections.receipts.card', [
            'is_duplicate' => 0,
            'payment' => $payment,
            'invoice' => $invoice,
            'customer' => $customer,
            'adm' => $adm
        ])->setPaper('a4', 'portrait');

        $folder = public_path('uploads/adm/collections/receipts/original');
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
            'amount' => $request->card_amount,
            'discount' => $request->card_discount,
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
        

        return response()->json([
            'status' => "success",
            'message' => 'Payment Details Saved',
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


    public function search_bulk_payment(Request $request)
    {
        $selected_customers = $request->input('selected_customers');

        if (!is_array($selected_customers) || empty($selected_customers)) {
            return response()->json('<tr><td colspan="3">No customers selected.</td></tr>');
        }
    
        $invoices = Invoices::whereIn('customer_id', $selected_customers)->get();

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
    
        return view('adm::collection.bulk_payment_submit', ['grouped_data' => $groupedWithCustomers]);
    }
    
    public function add_bulk_cash_payments(Request $request)
    { 
    if($request->isMethod('post')){
        
        try {
        
        if($request->payment_batch_id == ''){
            $payment_batch = new InvoicePaymentBatches();
            $payment_batch->save();  
        }
        else{
            $payment_batch =  InvoicePaymentBatches::where('id', $request->payment_batch_id)->first();
        }
        foreach ($request->payments as $payment) {
            $discount =  ($payment['amount'] * ($payment['discount'] ?? 0)) / 100;
            $final_payment = $payment['amount']-$discount;

            $payment_data = new InvoicePayments();
            $payment_data->invoice_id =  $payment['invoice_id'];
            $payment_data->batch_id = $payment_batch->id;
            $payment_data->type = 'cash';
            $payment_data->is_bulk = 1;
            $payment_data->amount = $payment['amount'];
            $payment_data->discount = $payment['discount'];
            $payment_data->final_payment = $final_payment;
            $payment_data->save();

            $invoice = Invoices::where('id', $payment['invoice_id'])->first();
            $invoice->paid_amount = $invoice->paid_amount + $payment['amount'];
            $invoice->update();

            $invoice= Invoices::where('id', $payment_data->invoice_id)->first();
            $customer= Customers::where('customer_id', $invoice->customer_id)->first();
            $adm= UserDetails::where('adm_number', $customer->adm)->first();
            
            $pdf = PDF::loadView('pdfs.collections.receipts.cash', [
                'is_duplicate' => 0,
                'payment' => $payment_data,
                'invoice' => $invoice,
                'customer' => $customer,
                'adm' => $adm
            ])->setPaper('a4', 'portrait');

            $folder = public_path('uploads/adm/collections/receipts/original');
            if (!File::exists($folder)) {
                File::makeDirectory($folder, 0755, true);
            }

            $pdf_name = 'receipt_'.$payment_data->id.'_'.time().'.pdf';
            $filePath = $folder.'/'.$pdf_name;
            $pdf->save($filePath);

            $payment_data->pdf_path = 'uploads/adm/collections/receipts/original/'.$pdf_name;
            $payment_data->save();

            Mail::to($customer->email)->send(new SendReceiptMail($payment_data, $filePath));


        }

        return response()->json([
            'status' => "success",
            'message' => 'Payments added successfully',
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

            if (empty($request->payment_batch_id)) {
                $payment_batch = new InvoicePaymentBatches();
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

            foreach ($payments as $data) {
                $amount = $data['amount'];
                $discountValue = $data['discount'] ?? 0;

                // if discount is % convert, otherwise keep as flat
                $discount = is_numeric($discountValue) && $discountValue < 100
                    ? ($amount * $discountValue / 100)
                    : $discountValue;

                $final_payment = $amount - $discount;

                $payment_data = new InvoicePayments();
                $payment_data->invoice_id = $data['invoice_id'];
                $payment_data->batch_id = $payment_batch->id;
                $payment_data->type = 'fund-transfer';
                $payment_data->is_bulk = 1;
                $payment_data->amount = $amount;
                $payment_data->discount = $discountValue;
                $payment_data->final_payment = $final_payment;
                $payment_data->transfer_date = $request->transfer_date;
                $payment_data->transfer_reference_number = $request->transfer_reference_number;
                $payment_data->screenshot = $screenshot_name;
                $payment_data->save();

                // update invoice
                $invoice = Invoices::find($data['invoice_id']);
                $invoice->paid_amount += $amount;
                $invoice->save();

                // generate receipt + email
                $customer = Customers::where('customer_id', $invoice->customer_id)->first();
                $adm = UserDetails::where('adm_number', $customer->adm)->first();

                $pdf = PDF::loadView('pdfs.collections.receipts.fund-transfer', [
                'is_duplicate' => 0,
                'payment' => $payment_data,
                'invoice' => $invoice,
                'customer' => $customer,
                'adm' => $adm
                ])->setPaper('a4', 'portrait');

                $folder = public_path('uploads/adm/collections/receipts/original');
                if (!File::exists($folder)) {
                    File::makeDirectory($folder, 0755, true);
                }

                $pdf_name = 'receipt_'.$payment_data->id.'_'.time().'.pdf';
                $filePath = $folder.'/'.$pdf_name;
                $pdf->save($filePath);

                $payment_data->pdf_path = 'uploads/adm/collections/receipts/original/'.$pdf_name;
                $payment_data->save();

                Mail::to($customer->email)->send(new SendReceiptMail($payment_data, $filePath));
            }

            return response()->json([
                'status' => "success",
                'message' => 'Payments added successfully',
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

            if (empty($request->payment_batch_id)) {
                $payment_batch = new InvoicePaymentBatches();
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

            foreach ($payments as $data) {
                $discount = ($data['amount'] * ($data['discount'] ?? 0)) / 100;
                $final_payment = $data['amount'] - $discount;

                $payment_data = new InvoicePayments();
                $payment_data->invoice_id = $data['invoice_id'];
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
                $payment_data->save();

                $invoice = Invoices::find($data['invoice_id']);
                $invoice->paid_amount += $data['amount'];
                $invoice->save();

                // Send receipt
                $customer = Customers::where('customer_id', $invoice->customer_id)->first();
                $adm = UserDetails::where('adm_number', $customer->adm)->first();


                $pdf = PDF::loadView('pdfs.collections.receipts.cheque', [
                'is_duplicate' => 0,
                'payment' => $payment_data,
                'invoice' => $invoice,
                'customer' => $customer,
                'adm' => $adm
                ])->setPaper('a4', 'portrait');

                $folder = public_path('uploads/adm/collections/receipts/original');
                if (!File::exists($folder)) {
                    File::makeDirectory($folder, 0755, true);
                }

                $pdf_name = 'receipt_'.$payment_data->id.'_'.time().'.pdf';
                $filePath = $folder.'/'.$pdf_name;
                $pdf->save($filePath);

                $payment_data->pdf_path = 'uploads/adm/collections/receipts/original/'.$pdf_name;
                $payment_data->save();

                Mail::to($customer->email)->send(new SendReceiptMail($payment_data, $filePath));
            }

            return response()->json([
                'status' => "success",
                'message' => 'Payments added successfully',
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

            if (empty($request->payment_batch_id)) {
                $payment_batch = new InvoicePaymentBatches();
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

            foreach ($payments as $data) {
                $discount = ($data['amount'] * ($data['discount'] ?? 0)) / 100;
                $final_payment = $data['amount'] - $discount;

                $payment_data = new InvoicePayments();
                $payment_data->invoice_id = $data['invoice_id'];
                $payment_data->batch_id = $payment_batch->id;
                $payment_data->type = 'card';
                $payment_data->is_bulk = 1;
                $payment_data->amount = $data['amount'];
                $payment_data->discount = $data['discount'] ?? 0;
                $payment_data->final_payment = $final_payment;
                $payment_data->card_transfer_date = $request->card_transfer_date;
                $payment_data->card_image = $screenshot_name;
                $payment_data->save();

                $invoice = Invoices::find($data['invoice_id']);
                $invoice->paid_amount += $data['amount'];
                $invoice->save();

                // Send receipt
                $customer = Customers::where('customer_id', $invoice->customer_id)->first();
                $adm = UserDetails::where('adm_number', $customer->adm)->first();

                $pdf = PDF::loadView('pdfs.collections.receipts.card', [
                'is_duplicate' => 0,
                'payment' => $payment_data,
                'invoice' => $invoice,
                'customer' => $customer,
                'adm' => $adm
                ])->setPaper('a4', 'portrait');

                $folder = public_path('uploads/adm/collections/receipts/original');
                if (!File::exists($folder)) {
                    File::makeDirectory($folder, 0755, true);
                }

                $pdf_name = 'receipt_'.$payment_data->id.'_'.time().'.pdf';
                $filePath = $folder.'/'.$pdf_name;
                $pdf->save($filePath);

                $payment_data->pdf_path = 'uploads/adm/collections/receipts/original/'.$pdf_name;
                $payment_data->save();

                Mail::to($customer->email)->send(new SendReceiptMail($payment_data, $filePath));
            }

            return response()->json([
                'status' => "success",
                'message' => 'Card Payments added successfully',
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
        

        return response()->json([
            'status' => "success",
            'message' => 'Payment Details Saved',
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
    $adm_no = UserDetails::where('user_id', Auth::id())->value('adm_number');

    $receipts = InvoicePayments::whereHas('invoice.customer', function ($query) use ($adm_no) {
        $query->where('adm', $adm_no);
    })
    ->with(['invoice.customer'])
    ->paginate(15);

    return view('adm::collection.receipts', [
        'receipts' => $receipts,
    ]);
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
}
