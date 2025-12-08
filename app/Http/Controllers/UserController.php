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
use App\Services\ActivitLogService;
use App\Models\Deposits;
use App\Models\InvoicePayments;
use App\Models\ActivtiyLog;
use File;
use Mail;
use Image;
use PDF;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('user.login');
        }

        if ($request->isMethod('post')) {

            $request->validate([
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            // Find user by email
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return back()->with('fail', 'Invalid login details');
            }

            // Check if account is locked
            if ($user->is_locked) {
                return back()->with('fail', 'Your account is locked due to multiple failed login attempts. Please contact admin.');
            }

            // Check password manually (to control attempt tracking)
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => 'active'])) {

                // Reset failed attempts on success
                $user->failed_attempts = 0;
                $user->save();

                // Redirect based on role
                switch ($user->user_role) {
                    case 6:
                        return redirect('adm');
                    case 7:
                        return redirect('finance');
                    default:
                        return redirect('dashboard');
                }
            } else {
                // Increment failed attempts
                $user->failed_attempts += 1;

                // Lock account if 3 failed attempts reached
                if ($user->failed_attempts >= 3) {
                    $user->is_locked = true;
                }

                $user->save();

                return back()->with('fail', 'Wrong login details. (' . $user->failed_attempts . '/3 attempts)');
            }
        }
    }

    public function dashboard()
    {
        // Get current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Sum deposits for the current month with status 'accepted'
        $currentMonthDeposits = Deposits::whereYear('date_time', $currentYear)
            ->whereMonth('date_time', $currentMonth)
            ->where('status', 'accepted')
            ->sum('amount');

        $onHandCollections = InvoicePayments::where('status', 'pending')
            ->whereIn('type', ['cash', 'cheque'])
            ->sum('final_payment');

        $monthCollections = InvoicePayments::where('status', 'accepted')
            ->sum('final_payment');

        $monthChequeCollections = InvoicePayments::where('status', 'accepted')
            ->where('type', 'cheque')
            ->sum('final_payment');

        $monthCashOnHand = InvoicePayments::where('status', 'pending')
            ->where('type', 'cash')
            ->sum('final_payment');

        $locked_users = User::where('is_locked', 1)->with('userDetails')->take(10)->get();
        $logs = ActivtiyLog::with('userData.userDetails')->orderBy('id', 'DESC')->take(10)->get();

        return view('dashboard', compact(
            'currentMonthDeposits',
            'onHandCollections',
            'monthCollections',
            'monthChequeCollections',
            'monthCashOnHand',
            'locked_users',
            'logs'
        ));
    }

    function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function forgot_password(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('user.forgot_password');
        }
        if ($request->isMethod('post')) {
            $request->validate([
                'email'   => 'required',
            ]);

            if (User::where("user_role", 1)->where("email", $request->email)->exists()) {
                $login_details = User::where('email', $request->email)->first();
            } else {
                return back()->with('fail', 'Please enter a valid email address');
            }
            $OTP = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

            $user = User::where('email', $request->email)->first();
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

    public function enter_otp(Request $request)
    {

        if ($request->isMethod('get')) {
            return view('user.enter_otp');
        }
        if ($request->isMethod('post')) {

            $user_otp = $request->no_1 . $request->no_2 . $request->no_3 . $request->no_4;

            if ($user_otp == User::where('email', session('otp_email'))->value('otp')) {
                return redirect('reset-password');
            } else {
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
            $user = User::where('email', session('otp_email'))->first();
            $user->password = Hash::make($request->password);
            $user->otp = null;
            $user->update();

            $request->session()->forget(['otp_email']);

            return redirect('/')->with('success', 'Password successfully updated');
        }
    }

    public function user_managment(Request $request)
    {
        // Get the roles input
        $selectedRoles = $request->input('roles', []);
        $search = $request->input('search');

        // If it's a string like "1,2,3", convert it to array
        if (is_string($selectedRoles)) {
            $selectedRoles = array_filter(explode(',', $selectedRoles));
        }

        $selectedDivisions = $request->input('division', []);

        $query = User::with('userDetails');

        if (!empty($selectedRoles)) {
            $query->whereIn('user_role', $selectedRoles);
        }

        if (!empty($selectedDivisions)) {
            $query->whereHas('userDetails', function ($q) use ($selectedDivisions) {
                $q->whereIn('division', $selectedDivisions);
            });
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'LIKE', "%{$search}%")
                    ->orWhereHas('userDetails', function ($sub) use ($search) {
                        $sub->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('adm_number', 'LIKE', "%{$search}%");
                    });
            });
        }

        $users = $query->paginate(15)->appends($request->query());

        $divisions = Divisions::get();

        return view('user.user_managment', compact(
            'users',
            'divisions',
            'selectedRoles',
            'selectedDivisions',
            'search'
        ));
    }





    public function add_new_user(Request $request)
    {
        if ($request->isMethod('get')) {
            $divisions = Divisions::where('status', 'active')->get();
            return view('user.add_new_user', ['divisions' => $divisions]);
        }
        if ($request->isMethod('post')) {
            $request->validate([
                'name'   => 'required',
                'user_role'   => 'required',
                'phone_number'   => 'required',
                'adm_number'   => 'nullable|unique:user_details',
                'email'   => 'required | email | unique:users',
                "password" => "required | confirmed | min:6",
            ]);

            if ($request->user_role == 2) {
                $existingHead = UserDetails::where('division', $request->division)
                    ->whereHas('user', function ($q) {
                        $q->where('user_role', 2);
                    })
                    ->first();

                if ($existingHead) {
                    return back()
                        ->withErrors(['user_role' => 'This division already has a Head of Division.'])
                        ->withInput();
                }
            }


            DB::beginTransaction();
            $user = User::create([
                "email" => $request->email,
                "password" => Hash::make($request->password),
                "user_role" => $request->user_role,
                "status" => 'active'
            ]);

            $userDetails = new UserDetails();
            $userDetails->user_id = $user->id;
            $userDetails->name = $request->name;
            $userDetails->phone_number = $request->phone_number;
            $userDetails->adm_number = $request->adm_number;
            $userDetails->supervisor = $request->supervisor;
            $userDetails->second_supervisor = $request->second_supervisor;
            $userDetails->division = $request->division;
            $userDetails->save();
            DB::commit();

            ActivitLogService::log('user management', $request->name . ' - user added');

            return back()->with('success', 'User Successfully Added');
        }
    }

    public function deactivate_user($id)
    {
        $user = User::with('userDetails')->find($id);
        $user->status = "inactive";
        $user->update();

        ActivitLogService::log('user management',  $user->userDetails->name . ' - user deactivated');

        return back()->with('success', 'User Deactivated');
    }

    public function activate_user($id)
    {
        $user = User::with('userDetails')->find($id);
        $user->status = "active";
        $user->update();

        ActivitLogService::log('user management',  $user->userDetails->name . ' - user activated');

        return back()->with('success', 'User Activated');
    }

    public function edit_user($id, Request $request)
    {
        if ($request->isMethod('get')) {
            $login_details = User::where('id', $id)->first();
            $other_details = UserDetails::where('user_id', $id)->first();
            $divisions = Divisions::where('status', 'active')->get();
            $role = $login_details->user_role;
            if ($role == '1' || $role == '2' || $role == '7' || $role == '8') {
                $supervisors = [];
            }
            if ($role == '3') {
                $supervisors = User::where('user_role', '2')->with('userDetails')->get();
            }
            if ($role == '4') {
                $supervisors = User::where('user_role', '3')->with('userDetails')->get();
            }
            if ($role == '5') {
                $supervisors = User::where('user_role', '4')->with('userDetails')->get();
            }
            if ($role == '6') {
                $supervisors = User::where('user_role', '5')->with('userDetails')->get();
            }

            return view('user.edit_user', ['login_details' => $login_details, 'other_details' => $other_details, 'divisions' => $divisions, 'supervisors' => $supervisors]);
        }
        if ($request->isMethod('post')) {
            $request->validate([
                'name'   => 'required',
                'user_role'   => 'required',
                'phone_number'   => 'required',
                'email'   => 'required | email',
            ]);

            if (!$request->password == null || !$request->password_confirmation == null) {

                $request->validate([
                    "password" => "required | confirmed | min:6",
                ]);

                if (User::where("id", "=", $id)->where("email", "=", $request->email)->exists()) {
                    $email = $request->email;
                } elseif (User::where("email", "=", $request->email)->exists()) {
                    return back()->with('fail', 'This email is already in use');
                } else {
                    $email = $request->email;
                }

                if (UserDetails::where("user_id", "=", $id)->where("adm_number", "=", $request->adm_number)->exists()) {
                    $adm_number = $request->adm_number;
                } elseif (UserDetails::where("adm_number", "=", $request->adm_number)->exists()) {
                    return back()->with('fail', 'This adm number is already in use');
                } else {
                    $adm_number = $adm_number;
                }

                $userDetails =  UserDetails::where('user_id', '=', $id)->first();
                $userDetails->name = $request->name;
                $userDetails->phone_number = $request->phone_number;
                $userDetails->adm_number = $adm_number;
                $userDetails->supervisor = $request->supervisor;
                $userDetails->division = $request->division;
                $userDetails->second_supervisor = $request->second_supervisor;
                $userDetails->update();

                $user = User::find($id);
                $user->email = $email;
                $user->password = Hash::make($request->input('password'));
                $user->update();

                ActivitLogService::log('user management',  $request->name . ' - user details updated');

                return back()->with('success', 'User Details Successfully  Updated');
            } else {
                if (User::where("id", "=", $id)->where("email", "=", $request->email)->exists()) {
                    $email = $request->email;
                } elseif (User::where("email", "=", $request->email)->exists()) {
                    return back()->with('fail', 'This email is already in use');
                } else {
                    $email = $request->email;
                }

                if (UserDetails::where("user_id", "=", $id)->where("adm_number", "=", $request->adm_number)->exists()) {
                    $adm_number = $request->adm_number;
                } elseif (UserDetails::where("adm_number", "=", $request->adm_number)->exists()) {
                    return back()->with('fail', 'This adm number is already in use');
                } else {
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

                ActivitLogService::log('user management',  $request->name . ' - user details updated');

                return back()->with('success', 'User Details Successfully  Updated');
            }
        }
    }

    public function get_supervisors($role)
    {

        if ($role == '3') {
            $supervisors = User::where('user_role', '2')->with('userDetails')->get();
        }
        if ($role == '4') {
            $supervisors = User::where('user_role', '3')->with('userDetails')->get();
        }
        if ($role == '5') {
            $supervisors = User::where('user_role', '4')->with('userDetails')->get();
        }
        if ($role == '6') {
            $supervisors = User::where('user_role', '5')->with('userDetails')->get();
        }
        return response()->json($supervisors);
    }

    public function locked_users(Request $request)
    {
        $query = User::where('is_locked', 1)->with('userDetails');

        // Check if there's a search term
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $locked_users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('user.locked_users', compact('locked_users'));
    }

    public function unlock_user($id)
    {
        $user = User::with('userDetails')->find($id);
        $user->is_locked = 0;
        $user->failed_attempts = 0;
        $user->save();


        ActivitLogService::log('user management',  $user->userDetails->name . ' - user unblocked');
    }

    public function settings(Request $request)
    {
        if ($request->isMethod('get')) {
            $user = User::where('id', Auth::user()->id)
                ->with([
                    'userDetails',
                    'userDetails.division',
                    'userDetails.supervisor.userDetails'
                ])->first();
            return view('user.settings', ['user' => $user]);
        }

        if ($request->isMethod('post')) {
            // Check which form was submitted based on the active tab
            $activeTab = $request->input('active_tab', 'customer-list');

            if ($activeTab === 'customer-list') {
                // Handle User Profile update
                return $this->updateProfile($request);
            } elseif ($activeTab === 'temporary') {
                // Handle Password Reset
                return $this->updatePassword($request);
            }

            return back()->with('fail', 'Invalid request.');
        }
    }

    private function updateProfile(Request $request)
    {
        // Get current user ID
        $id = Auth::user()->id;

        $request->validate([
            'name'   => 'required',
            'phone_number'   => 'required',
            'email'   => 'required | email',
        ]);

        // Update user without changing password
        $user = User::find($id);

        // Check if email is already taken by another user
        if ($user->email != $request->email && User::where("email", "=", $request->email)->exists()) {
            return back()->with('fail', 'This email is already in use');
        }

        $user->email = $request->email;
        $user->update();

        // Update user details
        $userDetails = UserDetails::where('user_id', '=', $id)->first();

        if ($userDetails) {
            $userDetails->name = $request->name;
            $userDetails->phone_number = $request->phone_number;

            // Update ADM number if provided and unique
            if (!empty($request->adm_number)) {
                if (
                    $userDetails->adm_number != $request->adm_number &&
                    UserDetails::where("adm_number", "=", $request->adm_number)->exists()
                ) {
                    return back()->with('fail', 'This ADM number is already in use');
                }
                $userDetails->adm_number = $request->adm_number;
            }

            // Update division if provided
            if (!empty($request->division)) {
                $userDetails->division = $request->division;
            }

            // Update supervisor if provided
            if (!empty($request->supervisor)) {
                $userDetails->supervisor = $request->supervisor;
            }

            $userDetails->update();
        }

        ActivitLogService::log('user settings', $request->name . ' - user profile updated');

        return back()->with('success', 'Profile successfully updated')
            ->with('active_tab', 'customer-list');
    }

    private function updatePassword(Request $request)
    {
        // Get current user ID
        $id = Auth::user()->id;

        $request->validate([
            "current_password" => "required",
            "password" => "required | confirmed | min:6",
        ]);

        // Verify current password
        $user = Auth::user();
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->with('fail', 'Current password is incorrect.')
                ->with('active_tab', 'temporary');
        }

        // Update password
        $user->password = Hash::make($request->input('password'));
        $user->update();

        ActivitLogService::log('user settings', 'Password changed for user ID: ' . $id);

        return back()->with('success', 'Password successfully updated')
            ->with('active_tab', 'temporary');
    }

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        $userDetails = UserDetails::where('user_id', Auth::id())->first();

        // Delete old picture if exists
        if ($userDetails->profile_picture && file_exists(public_path('db_files/user_profile_images/' . $userDetails->profile_picture))) {
            unlink(public_path('db_files/user_profile_images/' . $userDetails->profile_picture));
        }

        $image = $request->file('profile_picture');
        $filename = time() . '_' . Auth::id() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('db_files/user_profile_images'), $filename);

        $userDetails->profile_picture = $filename;
        $userDetails->save();

        return back()->with('success', 'Profile picture updated');
    }

    public function deleteProfilePicture(Request $request)
    {
        $userDetails = UserDetails::where('user_id', Auth::id())->first();

        if ($userDetails->profile_picture && file_exists(public_path('db_files/user_profile_images/' . $userDetails->profile_picture))) {
            unlink(public_path('db_files/user_profile_images/' . $userDetails->profile_picture));
        }

        $userDetails->profile_picture = null;
        $userDetails->save();

        return response()->json(['success' => true]);
    }
}
