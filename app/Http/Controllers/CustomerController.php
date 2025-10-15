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
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;


use App\Models\User;
use App\Models\UserDetails;
use App\Models\Divisions;
use App\Models\Customers;
use App\Models\ImportedReports;
use App\Models\Invoices;

use File;
use Mail;
use Image;
use PDF;

class CustomerController extends Controller
{
   
    public function customers()
    {
        $customers = Customers::where('is_temp', 0)->paginate(15);
        $temp_customers = Customers::where('is_temp', 1)->paginate(15);
        return view('customer.customers',['customers' => $customers,'temp_customers' => $temp_customers]);
    }

    public function add_new_customer(Request $request)
    { if($request->isMethod('get')){
        $adms = User::where('user_role', '6')->with('userDetails')->get();
        return view('customer.add_new_customer',['adms' => $adms]);
    }
    if($request->isMethod('post')){
        
        $request->validate([
            'customer_id'   => 'required|unique:customers',
            'name'   => 'required',
            'address'   => 'required',
            'mobile_number'   => 'required',
            'email'   => 'required',
            'whatsapp_number'   => 'required',
            'adm'   => 'required',
            'avilable_time'   => 'required',
        ]);

           $customer = new Customers();
           $customer->is_temp = 0;
           $customer->customer_id = $request->customer_id;
           $customer->name = $request->name;
           $customer->address = $request->address;
           $customer->mobile_number = $request->mobile_number;
           $customer->email = $request->email;
           $customer->whatsapp_number = $request->whatsapp_number;
           $customer->adm = $request->adm;
           $customer->avilable_time = $request->avilable_time;
           $customer->status = 'active';
           $customer->save();

        return back()->with('success', 'Customer Successfully Added');

    }

    }

    public function deactivate_customer($id){
        $customer = Customers::find($id);
        $customer->status = "inactive";
        $customer->update();
        return back()->with('success', 'Customer Deactivated');

    }

    public function activate_customer($id){
        $customer = Customers::find($id);
        $customer->status = "active";
        $customer->update();
        return back()->with('success', 'Customer Activated');

    }

    public function edit_customer($id,Request $request)
    {
    if($request->isMethod('get')){
    $customer_details = Customers::where('id',$id)->first();
    $adms = User::where('user_role', '6')->with('userDetails')->get();
    return view('customer.edit_customer', ['customer_details' => $customer_details,'adms' => $adms]);
    }
    if($request->isMethod('post')){

        $request->validate([
            'customer_id'   => 'required',
            'name'   => 'required',
            'address'   => 'required',
            'mobile_number'   => 'required',
            'email'   => 'required',
            'whatsapp_number'   => 'required',
            'adm'   => 'required',
            'avilable_time'   => 'required',
        ]);
        
        if(Customers::where("id", "=", $id)->where("customer_id", "=", $request->customer_id)->exists()){
            $customer_id = $request->customer_id;
        }
        elseif(Customers::where("customer_id", "=", $request->customer_id)->exists()){
         return back()->with('fail', 'This customer ID is already in use');
        }
        else{
            $customer_id = $request->customer_id;
        }
        
        $customer =  Customers::where('id', '=', $id)->first();
        $customer->is_temp = 0;
        $customer->customer_id = $customer_id;
        $customer->name = $request->name;
        $customer->address = $request->address;
        $customer->mobile_number = $request->mobile_number;
        $customer->email = $request->email;
        $customer->whatsapp_number = $request->whatsapp_number;
        $customer->adm = $request->adm;
        $customer->avilable_time = $request->avilable_time;
        $customer->update();

        return back()->with('success', 'Customer Details Successfully  Updated');
    }

    }

    public function import_customers(Request $request)
    { if($request->isMethod('get')){
        $reports = ImportedReports::take(8)->get();
        return view('customer.import_customers', ['reports' => $reports]);
    }
    if($request->isMethod('post')){
        
        $request->validate([
            'customers'   => 'required|mimes:xls,xlsx,csv',
        ]);

        $fileName =  $request->customers->getClientOriginalName();
        $request->customers->move(public_path('imports'), $fileName);

        $sales_file = new ImportedReports();
        $sales_file->name = $fileName;
        $sales_file->date = date("Y-m-d");
        $sales_file->save();
        
        $file = public_path('imports/' . $fileName);
        $data = Excel::toArray([], $file);
        $rows = $data[0];
        $header = array_shift($rows);
        foreach ($rows as $row) {
            $record = array_combine($header, $row);

            if(!Customers::where("customer_id", $record['customer_number'])->exists()){
                $customer = new Customers();
                $customer->is_temp = 1;
                $customer->customer_id = $record['customer_number'] ?? null;
                $customer->name = $record['customer_name'];
                $customer->adm = $record['adm_number'] ?? null;
                $customer->status = 'active';
                $customer->save();
            }
           
        }

        return back()->with('success', 'Customers Successfully Added');

    }

    }

    public function import(Request $request)
    { if($request->isMethod('get')){
        $reports = ImportedReports::take(8)->get();
        return view('customer.import', ['reports' => $reports]);
    }
    if($request->isMethod('post')){
        
        $request->validate([
            'report'   => 'required|mimes:xls,xlsx,csv',
        ]);

        $fileName =  $request->report->getClientOriginalName();
        $request->report->move(public_path('imports'), $fileName);

        $sales_file = new ImportedReports();
        $sales_file->type = $request->report_type;
        $sales_file->name = $fileName;
        $sales_file->date = date("Y-m-d");
        $sales_file->save();
        
        $file = public_path('imports/' . $fileName);
        $data = Excel::toArray([], $file);
        $rows = $data[0];
        $header = array_shift($rows);

        if($request->report_type == "sales"){
            foreach ($rows as $row) {
                $record = array_combine($header, $row);
    
                if(!Customers::where("customer_id", $record['customer_number'])->exists()){
                    $customer = new Customers();
                    $customer->is_temp = 1;
                    $customer->customer_id = $record['customer_number'] ?? null;
                    $customer->name = $record['customer_name'];
                    $customer->adm = $record['adm_number'] ?? null;
                    $customer->status = 'active';
                    $customer->save();
                }
    
                if(!Invoices::where("invoice_or_cheque_no", $record['invoice_or_cheque_no'])->exists()){
                    $invoice = new Invoices();
                    $invoice->type = 'invoice';
                    $invoice->invoice_or_cheque_no = $record['invoice_or_cheque_no'] ?? null;
                    $invoice->customer_id = $record['customer_number'] ?? null;
                    $invoice->invoice_date = $record['invoice_date'];
                    $invoice->amount = $record['amount'] ?? null;
                    $invoice->save();
                }
               
               
            }
        }
        if($request->report_type == "return-cheques"){
            foreach ($rows as $row) {
                $record = array_combine($header, $row);
    
                if(!Customers::where("customer_id", $record['customer_number'])->exists()){
                    $customer = new Customers();
                    $customer->is_temp = 1;
                    $customer->customer_id = $record['customer_number'] ?? null;
                    $customer->name = $record['customer_name'];
                    $customer->adm = $record['adm_number'] ?? null;
                    $customer->status = 'active';
                    $customer->save();
                }
    
                if(!Invoices::where("invoice_or_cheque_no", $record['invoice_or_cheque_no'])->exists()){
                    $invoice = new Invoices();
                    $invoice->type = 'cheque';
                    $invoice->invoice_or_cheque_no = $record['invoice_or_cheque_no'] ?? null;
                    $invoice->customer_id = $record['customer_number'] ?? null;
                    $invoice->invoice_date = $record['invoice_date'];
                    $invoice->amount = $record['amount'] ?? null;
                    $invoice->save();
                }
               
               
            }
        }

        return back()->with('success', 'Reports Successfully Added');

    }

    }
}
