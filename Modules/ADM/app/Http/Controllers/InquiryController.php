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
        $inquiries = Inquiries::with(['customerData', 'invoiceData'])->paginate(15);
        return view('adm::inquiries.index',['inquiries' => $inquiries]);
    }

    public function create_reminder(Request $request)
    { if($request->isMethod('get')){
        $users = User::with('userDetails')->get();
        return view('adm::notifications_and_reminders.create_reminder',['users' => $users]);
    }
    if($request->isMethod('post')){

        $request->validate([
            'send_from'   => 'required',
            'reminder_title'   => 'required',
            'reminder_type'   => 'required',
            'reminder_date'   => 'required',
            'reason'   => 'required',
           ]);

           $reminder = new Reminders();
           $reminder->sent_user_id =Auth::user()->id;
           $reminder->send_from = $request->send_from;
           $reminder->reminder_title = $request->reminder_title;
           $reminder->reminder_type = $request->reminder_type;
           $reminder->send_to = $request->send_to;
           $reminder->reminder_date = $request->reminder_date;
           $reminder->reason = $request->reason;
           $reminder->save();

        return back()->with('success', 'Reminder Successfully Added');

    }

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
