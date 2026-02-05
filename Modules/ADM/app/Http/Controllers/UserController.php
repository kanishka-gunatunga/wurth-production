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
use App\Models\Customers;
use App\Models\Invoices;

use File;
use Mail;
use Image;
use PDF;
use App\Services\ActivitLogService;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function dashboard()
    {
        return view('adm::dashboard.index');
    }
    
    public function my_profile(Request $request)
    {
    if($request->isMethod('get')){
    $login_details = User::where('id',Auth::user()->id)->first();
    $other_details = UserDetails::where('user_id',Auth::user()->id)->first();

    $customers = Customers::where('adm', $other_details->adm_number)->pluck('customer_id'); 
    $all_invoices = Invoices::whereIn('customer_id', $customers)->get();

    return view('adm::user.my_profile', ['login_details' => $login_details,'other_details' => $other_details,'customers' => $customers,'all_invoices' => $all_invoices]);
    }

    }
    public function edit_profile(Request $request)
    {
    if($request->isMethod('get')){
        $login_details = User::where('id',Auth::user()->id)->first();
        $other_details = UserDetails::where('user_id',Auth::user()->id)->first();
    
        return view('adm::user.edit_profile', ['login_details' => $login_details,'other_details' => $other_details]);
    }
    if($request->isMethod('post')){
    $request->validate([
        'name'   => 'required',
        'phone_number'   => 'required',
        'email'   => 'required | email',
    ]);

    if($request->profile_picture == null){
        $imag_name =  UserDetails::where('user_id',  Auth::user()->id)->value('profile_picture');;
    }
    else{
        $imag_name = time().'-dp-.'.$request->profile_picture->extension();
        $request->profile_picture->move(public_path('db_files/user_profile_images/'), $imag_name);
    }

    if(!$request->current_password == null || !$request->password == null || !$request->password_confirmation == null){
            
            $request->validate([
                "password" => "required | confirmed | min:6",
            ]);
            if (Hash::check($request->input('current_password'), User::where('id', Auth::user()->id)->value('password'))) {
            if(User::where("id", "=", Auth::user()->id)->where("email", "=", $request->email)->exists()){
                $email = $request->email;
            }
            elseif(User::where("email", "=", $request->email)->exists()){
             return back()->with('fail', 'This email is already in use');
            }
            else{
             $email = $request->email;
            }


            $userDetails =  UserDetails::where('user_id', '=', Auth::user()->id)->first();
            $userDetails->name = $request->name;
            $userDetails->phone_number = $request->phone_number;
            $userDetails->profile_picture = $imag_name;
            $userDetails->update();
           
            $user = User::find(Auth::user()->id);
            $user->email = $email;
            $user->password = Hash::make($request->input('password'));
            $user->update();

            $user->update();
            
            ActivitLogService::log('user_profile', "Profile updated (with password) for user: " . Auth::user()->name);

            return back()->with('success', 'Profile Updated');
        }
        else{
            return back()->with('fail', 'Current password is incorrect.');
        }
    }
    else{
        if(User::where("id", "=", Auth::user()->id)->where("email", "=", $request->email)->exists()){
            $email = $request->email;
        }
        elseif(User::where("email", "=", $request->email)->exists()){
         return back()->with('fail', 'This email is already in use');
        }
        else{
         $email = $request->email;
        }

        $userDetails =  UserDetails::where('user_id', '=', Auth::user()->id)->first();
        $userDetails->name = $request->name;
        $userDetails->phone_number = $request->phone_number;
        $userDetails->profile_picture = $imag_name;
        $userDetails->update();
        $user = User::find(Auth::user()->id);
        $user->email = $email;
        $user->update();

        ActivitLogService::log('user_profile', "Profile updated (without password) for user: " . Auth::user()->name);

        return back()->with('success', 'Profile Updated');
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
}
