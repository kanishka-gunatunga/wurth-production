<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Upload;
use App\Http\Controllers\ReturnChequeController; 
use App\Http\Controllers\CreditNoteController;
use App\Http\Controllers\ExtraPaymentController;
use App\Http\Controllers\AdmTargetsController;
class UploadController extends Controller
{
    public function index()
    {
        $uploads = Upload::latest()->take(6)->get();
        return view('uploads.upload', compact('uploads'));
    }

    public function store(Request $request)
{
  
    $request->validate([
        'file_type' => 'required|string',
        'file' => 'required|mimes:xlsx,xls,csv|max:10240',
    ]); 
    $file = $request->file('file');
    $fileName = $file->getClientOriginalName();


    if ($request->file_type === 'return_cheque') {
        $returnChequeController = new \App\Http\Controllers\ReturnChequeController();
        $response = $returnChequeController->importReturnCheques($request);
    } elseif ($request->file_type === 'customer') {
        $customerController = new \App\Http\Controllers\CustomerController();
        $response = $customerController->importCustomers($request);
    } elseif ($request->file_type === 'invoice') {
        $invoiceController = new \App\Http\Controllers\InvoiceController();
        $response = $invoiceController->importInvoices($request);
    }elseif ($request->file_type === 'credit-note') {
        $creditNoteController = new \App\Http\Controllers\CreditNoteController();
        $response = $creditNoteController->importCreditNotes($request);
    }
    elseif ($request->file_type === 'extra-payment') {
        $extraPaymentController = new \App\Http\Controllers\ExtraPaymentController();
        $response = $extraPaymentController->importExtraPayments($request);
        return back()->with('success', 'File Uploaded Successfully');
    }
     elseif ($request->file_type === 'adm-targets') {
        $admTargetsController = new \App\Http\Controllers\AdmTargetsController();
        $response = $admTargetsController->importAdmTargets($request);
        return back()->with('success', 'File Uploaded Successfully');
    }
     else {
        $response = back()->with('success', 'File uploaded successfully');
    }

    $file->move(public_path('imports'), $fileName);

    Upload::create([
        'file_type' => $request->file_type,
        'file_name' => $fileName,
    ]);

    return $response;
}


}
