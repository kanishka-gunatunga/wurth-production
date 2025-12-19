<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Upload;
use App\Http\Controllers\ReturnChequeController;
use App\Http\Controllers\CreditNoteController;
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
    } else {
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
