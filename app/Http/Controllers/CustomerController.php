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
use App\Services\ActivitLogService;

use File;
use Mail;
use Image;
use PDF; 

class CustomerController extends Controller
{
 public function customers(Request $request)
{
    // Active tab (default = customer-list)
    $activeTab = $request->input('active_tab', 'customer-list');

    // === CUSTOMERS TAB FILTERS ===
    $search = $request->input('search');
    $selectedAdms = $request->input('adm', []);
    if (is_string($selectedAdms)) {
        $selectedAdms = array_filter(explode(',', $selectedAdms));
    }

    // === TEMPORARY CUSTOMERS TAB FILTERS ===
    $tempSearch = $request->input('temp_search');
    $tempSelectedAdms = $request->input('temp_adm', []);
    if (is_string($tempSelectedAdms)) {
        $tempSelectedAdms = array_filter(explode(',', $tempSelectedAdms));
    }

    // --- BASE QUERIES ---
    $customersQuery = Customers::where('is_temp', 0)->with('admDetails','invoices');
    $tempCustomersQuery = Customers::where('is_temp', 1)->with('admDetails');

    // --- APPLY FILTERS TO CUSTOMERS ---
    if (!empty($selectedAdms)) {
        $customersQuery->whereIn('adm', $selectedAdms);
    }
    if (!empty($search)) {
        $customersQuery->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%")
              ->orWhere('mobile_number', 'LIKE', "%{$search}%")
              ->orWhere('customer_id', 'LIKE', "%{$search}%");
        });
    }

    // --- APPLY FILTERS TO TEMP CUSTOMERS ---
    if (!empty($tempSelectedAdms)) {
        $tempCustomersQuery->whereIn('adm', $tempSelectedAdms);
    }
    if (!empty($tempSearch)) {
        $tempCustomersQuery->where(function ($q) use ($tempSearch) {
            $q->where('name', 'LIKE', "%{$tempSearch}%")
              ->orWhere('email', 'LIKE', "%{$tempSearch}%")
              ->orWhere('mobile_number', 'LIKE', "%{$tempSearch}%")
              ->orWhere('customer_id', 'LIKE', "%{$tempSearch}%");
        });
    }

    // --- PAGINATION (with separate query page names) ---
    $customers = $customersQuery->paginate(15, ['*'], 'customers_page')
        ->appends($request->except('customers_page'));
    $temp_customers = $tempCustomersQuery->paginate(15, ['*'], 'temp_page')
        ->appends($request->except('temp_page'));

    // --- ADM list for filters (ADM = users with user_role 6) ---
    $adms = User::where('user_role', 6)->with('userDetails')->get();

    // --- RETURN VIEW ---
    return view('customer.customers', [
        'customers' => $customers,
        'temp_customers' => $temp_customers,
        'adms' => $adms,
        'selectedAdms' => $selectedAdms,
        'tempSelectedAdms' => $tempSelectedAdms,
        'search' => $search,
        'tempSearch' => $tempSearch,
        'activeTab' => $activeTab,
    ]);
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
           $customer->secondary_address = $request->secondary_address;
           $customer->mobile_number = $request->mobile_number;
           $customer->secondary_mobile_number = $request->secondary_mobile_number;
           $customer->email = $request->email;
           $customer->whatsapp_number = $request->whatsapp_number;
           $customer->adm = $request->adm;
           $customer->secondary_adm = $request->secondary_adm;
           $customer->contact_person = $request->contact_person;
           $customer->status = 'active';
           $customer->save();

            ActivitLogService::log('customer',  'new customer added - '.$request->name);

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
        $customer->customer_id = $request->customer_id;
        $customer->name = $request->name;
        $customer->address = $request->address;
        $customer->secondary_address = $request->secondary_address;
        $customer->mobile_number = $request->mobile_number;
        $customer->secondary_mobile_number = $request->secondary_mobile_number;
        $customer->email = $request->email;
        $customer->whatsapp_number = $request->whatsapp_number;
        $customer->adm = $request->adm;
        $customer->secondary_adm = $request->secondary_adm;
        $customer->contact_person = $request->contact_person;
        $customer->update();

        ActivitLogService::log('customer',  'customer details updated - '.$request->name);

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

        ActivitLogService::log('import',  'data imported from file - '. $fileName);
        
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
        
        ActivitLogService::log('import',  'data imported from file - '. $fileName);

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
                    $invoice->type = 'return-cheque';
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
    
    public function view_customer($id,Request $request)
    {
    if($request->isMethod('get')){
    $customer_details = Customers::where('id',$id)->with('admDetails','secondaryAdm','invoices')->first();
    return view('customer.view_customer', ['customer_details' => $customer_details]);
    }
   

    }
}
