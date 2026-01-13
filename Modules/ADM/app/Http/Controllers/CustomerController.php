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

use App\Models\User;
use App\Models\UserDetails;
use App\Models\Invoices;
use App\Models\Customers;

use File;
use Mail;
use Image;
use PDF;
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
     public function customers()
{
    if(Auth::user()->user_role == 6 ){
    $adm_no = UserDetails::where('user_id', Auth::user()->id)->value('adm_number');

    // Permanent customers (primary or secondary ADM)
    $customers = Customers::where('is_temp', 0)
        ->where(function($q) use ($adm_no) {
            $q->where('adm', $adm_no)
              ->orWhere('secondary_adm', $adm_no);
        })
        ->paginate(15);

    // Temporary customers (primary or secondary ADM)
    $temp_customers = Customers::where('is_temp', 1)
        ->where(function($q) use ($adm_no) {
            $q->where('adm', $adm_no)
              ->orWhere('secondary_adm', $adm_no);
        })
        ->paginate(15);

    // Counts for both
    $customers_count = Customers::where('is_temp', 0)
        ->where(function($q) use ($adm_no) {
            $q->where('adm', $adm_no)
              ->orWhere('secondary_adm', $adm_no);
        })
        ->count();

    $temp_customers_count = Customers::where('is_temp', 1)
        ->where(function($q) use ($adm_no) {
            $q->where('adm', $adm_no)
              ->orWhere('secondary_adm', $adm_no);
        })
        ->count();
    } else{
        $customers = Customers::where('is_temp', 0)->paginate(15);

        $temp_customers = Customers::where('is_temp', 1)->paginate(15);

        $customers_count = Customers::where('is_temp', 0)->count();

        $temp_customers_count = Customers::where('is_temp', 1)->count();
    }
    return view('adm::customer.customers', [
        'customers' => $customers,
        'temp_customers' => $temp_customers,
        'customers_count' => $customers_count,
        'temp_customers_count' => $temp_customers_count,
    ]);
}


    public function search_customers(Request $request)
{
    $query = $request->input('query');
    if(Auth::user()->user_role == 6 ){
    $adm_no = UserDetails::where('user_id', Auth::user()->id)->value('adm_number');

    $customers = Customers::where('is_temp', 0)
        ->where(function($q) use ($adm_no) {
            $q->where('adm', $adm_no)
              ->orWhere('secondary_adm', $adm_no);
        })
        ->where(function($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('customer_id', 'LIKE', "%{$query}%");
        })
        ->get();
    }
    else{
         $customers = Customers::where('is_temp', 0)
         ->where(function($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('customer_id', 'LIKE', "%{$query}%");
        })
        ->get();
    }
    $customer_data = '';
    foreach ($customers as $customer) {
        $customer_data .= '
            <tr>
                <td>' . $customer->customer_id . '</td>
                <td>' . $customer->name . '</td>
                <td>' . $customer->mobile_number . '</td>
            </tr>';
    }

    return response()->json($customer_data);
}

   public function search_temp_customers(Request $request)
{
    $query = $request->input('query');
     if(Auth::user()->user_role == 6 ){
    $adm_no = UserDetails::where('user_id', Auth::user()->id)->value('adm_number');

    $customers = Customers::where('is_temp', 1)
        ->where(function($q) use ($adm_no) {
            $q->where('adm', $adm_no)
              ->orWhere('secondary_adm', $adm_no);
        })
        ->where(function($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('customer_id', 'LIKE', "%{$query}%");
        })
        ->get();
     } else{
        $customers = Customers::where('is_temp', 1)
        ->where(function($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('customer_id', 'LIKE', "%{$query}%");
        })
        ->get();
     }
    $customer_data = '';
    foreach ($customers as $customer) {
        $customer_data .= '
            <tr>
                <td>' . $customer->customer_id . '</td>
                <td>' . $customer->name . '</td>
                <td>' . $customer->mobile_number . '</td>
            </tr>';
    }

    return response()->json($customer_data);
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

    public function update_customer_ajax(Request $request)
{
    $customer = Customers::where('id', $request->id)->first();

    if (!$customer) {
        return response()->json(['status' => false, 'message' => 'Customer not found']);
    }

    $customer->name = $request->name;
    $customer->mobile_number = $request->mobile_number;
    $customer->email = $request->email;
    $customer->address = $request->address;
    $customer->save();

    return response()->json([
        'status' => true,
        'customer' => $customer
    ]);
}

}
