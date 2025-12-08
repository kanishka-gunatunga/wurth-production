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
use App\Models\User;
use App\Models\UserDetails;
use App\Models\Invoices;
use App\Models\Customers;
use App\Models\Reminders;
use App\Models\Inquiries;

use File;
use Mail;
use Image;
use PDF;
class InquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function inquiries()
    {
    //    $inquiries = Inquiries::with(['invoice', 'customer', 'admin'])
    //     ->where('adm_id', Auth::id())
    //     ->paginate(15);
        $inquiries = Inquiries::with(['customer', 'invoice'])->paginate(15);
        return view('adm::inquiries.index',['inquiries' => $inquiries]);
    }

    public function create_inquiry(Request $request)
    { if($request->isMethod('get')){
        $user = User::where('id',Auth::user()->id)->with('userDetails')->first();
        $customers = Customers::get();
        return view('adm::inquiries.create_inquiry',['user' => $user,'customers' => $customers]);
    }
    if($request->isMethod('post')){

        $request->validate([
            'inquiry_type'   => 'required',
            'adm_number'   => 'required',
            'name'   => 'required',
            'customer'   => 'required',
            'invoice'   => 'required',
            'reason'   => 'required',
           ]);

           $attachment_name = null;
            if(!$request->attachment == null){
                $attachment_name = time() . '-' . Str::uuid()->toString() .'.' . $request->attachment->extension();
                $request->attachment->move(public_path('uploads/adm/inquiry/attachments/'), $attachment_name);
            }
            

           $inquiry = new Inquiries();
           $inquiry->adm_id =Auth::user()->id;
           $inquiry->type = $request->inquiry_type;
           $inquiry->customer = $request->customer;
           $inquiry->invoice_number = $request->invoice;
           $inquiry->reason = $request->reason;
           $inquiry->attachement = $attachment_name;
           $inquiry->status = 'pending';
           $inquiry->save();

        return back()->with('success', 'Inquiry Successfully Added');

    }

    }
    public function get_customer_invoices($customer_id)
    {
        $invoices = Invoices::where('customer_id', $customer_id)->get();

        // Return as JSON
        return response()->json($invoices);
    }
    public function search_inquiries(Request $request)
     {
         $query = $request->input('query');
     
         $adm_no = UserDetails::where('user_id', Auth::user()->id)->value('adm_number');
         $customers = Customers::where('adm', $adm_no)->pluck('customer_id');
     
         $inquiries = Inquiries::with(['customer', 'invoice'])->whereIn('customer', $customers)
             ->where(function ($q) use ($query) {
                 $q->where('invoice_number', 'LIKE', "%{$query}%")
                   ->orWhereHas('customer', function ($q) use ($query) {
                       $q->where('name', 'LIKE', "%{$query}%");
                   });
             })
             ->get();
             
         $html = '';
         foreach ($inquiries as $inquiry) {
                // ... same row building as Option A ...
                $html .= '<tr>';
                $html .= '<td>' . e($inquiry->type ?? 'N/A') . '</td>';
                $html .= '<td>' . e($inquiry->customer->name ?? 'N/A') . '</td>';
                $html .= '<td>' . e($inquiry->invoice->invoice_or_cheque_no ?? 'N/A') . '</td>';
                $html .= '<td>';
                if ($inquiry->status === 'pending') {
                    $html .= '<span class="badge bg-warning">Pending</span>';
                } elseif ($inquiry->status === 'approved') {
                    $html .= '<span class="badge bg-success">Approved</span>';
                } elseif ($inquiry->status === 'rejected') {
                    $html .= '<span class="badge bg-danger">Rejected</span>';
                } else {
                    $html .= '<span class="badge bg-secondary">Unknown</span>';
                }
                $html .= '</td>';
                $html .= '</tr>';
            }

     
         return response()->json($html);
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
