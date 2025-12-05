<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Upload;
use App\Http\Controllers\ReturnChequeController;

class UploadController extends Controller
{
    public function index()
    {
        $uploads = Upload::latest()->take(6)->get();
        return view('finance::uploads.upload', compact('uploads'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file_type' => 'required|string',
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        // ✅ Get only the original file name (without saving the file)
        $fileName = $request->file('file')->getClientOriginalName();

        // ✅ Save just the name in database
        $upload = \App\Models\Upload::create([
            'file_type' => $request->file_type,
            'file_name' => $fileName,
        ]);

        // ✅ Handle Return Cheque file (if applicable)
        if ($request->file_type === 'return_cheque') {
            $returnChequeController = new \App\Http\Controllers\ReturnChequeController();
            $response = $returnChequeController->importReturnCheques($request);
            return $response;
        }

        return response()->json(['message' => 'File name saved successfully']);
    }
}
