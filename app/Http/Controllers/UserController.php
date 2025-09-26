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

use App\Models\User;
use App\Models\UserDetails;
use App\Models\Divisions;

use File;
use Mail;
use Image;
use PDF;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if($request->isMethod('get')){
            return view('user.login');
         }
         if($request->isMethod('post')){

            $request->validate([
                'email'   => 'required',
                'password'  => 'required'
               ]);

               $user_data = array(
                'email'  => $request->get('email'),
                'status'  => "active",
                // 'user_role'  => 1,
                'password' => $request->get('password')
               );

               if(Auth::attempt($user_data))
               {
                if(Auth::user()->user_role == 6){
                    return redirect('adm');
                }
                elseif(Auth::user()->user_role == 7){
                    return redirect('finance');
                }
                else{
                    return redirect('dashboard');
                }
                
               }
               else
               {
                return back()->with('fail', 'Wrong Login Details');
               }
         }
    }
    public function dashboard()
    {
        return view('dashboard');
    }
    function logout()
    {
     Auth::logout();
     return redirect('/');
    }
    public function forgot_password(Request $request){
        if($request->isMethod('get')){
            return view('user.forgot_password');
         }
         if($request->isMethod('post')){
            $request->validate([
                'email'   => 'required',
               ]);

            if (User::where("user_role", 1)->where("email", $request->email)->exists()) {
                $login_details = User::where('email', $request->email)->first();
            } else {
                return back()->with('fail', 'Please enter a valid email address');
            }
            $OTP = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

            $user = User::where('email',$request->email)->first();
            $user->otp = $OTP;
            $user->update();

            $details  = [
                'otp' => $OTP,
            ];

            Mail::to($request->email)->send(new \App\Mail\ForgotPassword($details));

            session(['otp_email' => $request->email]);

            return redirect('enter-otp');
         }
    }
    public function enter_otp(Request $request){
        if($request->isMethod('get')){
            return view('user.enter_otp');
         } 
         if($request->isMethod('post')){

            $user_otp = $request->no_1.$request->no_2.$request->no_3.$request->no_4;

             if($user_otp == User::where('email',session('otp_email'))->value('otp')){
                return redirect('reset-password');
             }
             else{
                return back()->with('fail', 'You have entered the wrong OTP. Please try again');
             }
         }
    }
    public function reset_password(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('user.reset_password');
        }
        if ($request->isMethod('post')) {

            $request->validate([
                "password" => "required | min:6 | confirmed",
            ]);
            $user = User::where('email',session('otp_email'))->first();
            $user->password = Hash::make($request->password);
            $user->otp = null;
            $user->update();

            $request->session()->forget(['otp_email']);

            return redirect('/')->with('success', 'Password successfully updated');
        }
    }
    public function user_managment()
    {
        $users = User::with('userDetails')->paginate(15);
        return view('user.user_managment',['users' => $users]);
    }


    public function add_new_user(Request $request)
    { if($request->isMethod('get')){
        $divisions = Divisions::where('status', 'active')->get();
        return view('user.add_new_user',['divisions' => $divisions]);
    }
    if($request->isMethod('post')){
    $request->validate([
            'name'   => 'required',
            'user_role'   => 'required',
            'phone_number'   => 'required',
            'adm_number'   => 'nullable|unique:user_details',
            'email'   => 'required | email | unique:users',
            "password" => "required | confirmed | min:6",
           ]);

           DB::beginTransaction();
           $user = User::create([
              "email" => $request->email,
              "password" => Hash::make($request->password),
              "user_role" => $request->user_role,
              "status" => 'active'
           ]);

           $userDetails = new UserDetails();
           $userDetails->user_id =$user->id;
           $userDetails->name = $request->name;
           $userDetails->phone_number = $request->phone_number;
           $userDetails->adm_number = $request->adm_number;
           $userDetails->supervisor = $request->supervisor;
           $userDetails->division = $request->division;
           $userDetails->save();
           DB::commit();

        return back()->with('success', 'User Successfully Added');

    }

    }

    public function deactivate_user($id){
        $user = User::find($id);
        $user->status = "inactive";
        $user->update();
        return back()->with('success', 'User Deactivated');

    }

    public function activate_user($id){
        $user = User::find($id);
        $user->status = "active";
        $user->update();
        return back()->with('success', 'User Activated');

    }

    public function edit_user($id,Request $request)
    {
    if($request->isMethod('get')){
    $login_details = User::where('id',$id)->first();
    $other_details = UserDetails::where('user_id',$id)->first();
    $divisions = Divisions::where('status', 'active')->get();
    $role = $login_details->user_role;
    if($role == '1' || $role == '2' || $role == '7'){
        $supervisors = []; 
    }
    if($role == '3'){
        $supervisors = User::where('user_role', '2')->with('userDetails')->get(); 
    }
    if($role == '4'){
        $supervisors = User::where('user_role', '3')->with('userDetails')->get(); 
    }
    if($role == '5'){
        $supervisors = User::where('user_role', '4')->with('userDetails')->get(); 
    }
    if($role == '6'){
        $supervisors = User::where('user_role', '5')->with('userDetails')->get(); 
    }

    return view('user.edit_user', ['login_details' => $login_details,'other_details' => $other_details,'divisions' => $divisions,'supervisors' => $supervisors]);
    }
    if($request->isMethod('post')){
    $request->validate([
        'name'   => 'required',
        'user_role'   => 'required',
        'phone_number'   => 'required',
        'email'   => 'required | email',
    ]);

    if(!$request->password == null || !$request->password_confirmation == null){
            
        $request->validate([
            "password" => "required | confirmed | min:6",
        ]);

            if(User::where("id", "=", $id)->where("email", "=", $request->email)->exists()){
                $email = $request->email;
            }
            elseif(User::where("email", "=", $request->email)->exists()){
             return back()->with('fail', 'This email is already in use');
            }
            else{
             $email = $request->email;
            }

            if(UserDetails::where("user_id", "=", $id)->where("adm_number", "=", $request->adm_number)->exists()){
                $adm_number = $request->adm_number;
            }
            elseif(UserDetails::where("adm_number", "=", $request->adm_number)->exists()){
             return back()->with('fail', 'This adm number is already in use');
            }
            else{
             $adm_number = $adm_number;
            }

            $userDetails =  UserDetails::where('user_id', '=', $id)->first();
            $userDetails->name = $request->name;
            $userDetails->phone_number = $request->phone_number;
            $userDetails->adm_number = $adm_number;
            $userDetails->supervisor = $request->supervisor;
            $userDetails->division = $request->division;
            $userDetails->update();

            $user = User::find($id);
            $user->email = $email;
            $user->password = Hash::make($request->input('password'));
            $user->update();

            return back()->with('success', 'User Details Successfully  Updated');

    }
    else{
        if(User::where("id", "=", $id)->where("email", "=", $request->email)->exists()){
            $email = $request->email;
        }
        elseif(User::where("email", "=", $request->email)->exists()){
         return back()->with('fail', 'This email is already in use');
        }
        else{
         $email = $request->email;
        }

        if(UserDetails::where("user_id", "=", $id)->where("adm_number", "=", $request->adm_number)->exists()){
            $adm_number = $request->adm_number;
        }
        elseif(UserDetails::where("adm_number", "=", $request->adm_number)->exists()){
         return back()->with('fail', 'This adm number is already in use');
        }
        else{
         $adm_number = $adm_number;
        }

        $userDetails =  UserDetails::where('user_id', '=', $id)->first();;
        $userDetails->name = $request->name;
        $userDetails->phone_number = $request->phone_number;
        $userDetails->adm_number = $adm_number;
        $userDetails->supervisor = $request->supervisor;
        $userDetails->division = $request->division;
        $userDetails->update();
        $user = User::find($id);
        $user->email = $email;
        $user->update();
        return back()->with('success', 'User Details Successfully  Updated');
    }


    }

    }

public function get_supervisors($role)
{

    if($role == '3'){
        $supervisors = User::where('user_role', '2')->with('userDetails')->get(); 
    }
    if($role == '4'){
        $supervisors = User::where('user_role', '3')->with('userDetails')->get(); 
    }
    if($role == '5'){
        $supervisors = User::where('user_role', '4')->with('userDetails')->get(); 
    }
    if($role == '6'){
        $supervisors = User::where('user_role', '5')->with('userDetails')->get(); 
    }
    return response()->json($supervisors);
}
}
