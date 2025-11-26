<?php

namespace Modules\Finance\Http\Controllers;

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
use App\Services\ActivitLogService;

use App\Models\User;
use App\Models\UserDetails;
use App\Models\Customers;
use App\Models\Invoices;

use File;
use Mail;
use Image;
use PDF;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function dashboard()
    {
        // ✅ Recent Cash Deposits
        $recentCashDeposits = \App\Models\Deposits::where('type', 'cash')
            ->orderByDesc('date_time')
            ->take(5)
            ->get()
            ->map(function ($deposit) {
                $admDetails = \App\Models\UserDetails::where('user_id', $deposit->adm_id)->first();

                return [
                    'date' => $deposit->date_time
                        ? date('d M Y', strtotime($deposit->date_time))
                        : 'N/A',
                    'adm_name' => $admDetails->name ?? 'N/A',
                    'adm_number' => $admDetails->adm_number ?? 'N/A',
                    'amount' => number_format($deposit->amount ?? 0, 2),
                ];
            });

        // ✅ Recent Cheque Deposits (5 latest)
        $recentChequeDeposits = \App\Models\Deposits::where('type', 'cheque')
            ->orderByDesc('date_time')
            ->take(5)
            ->get()
            ->map(function ($deposit) {
                $admDetails = \App\Models\UserDetails::where('user_id', $deposit->adm_id)->first();

                return [
                    'date' => $deposit->date_time
                        ? date('d M Y', strtotime($deposit->date_time))
                        : 'N/A',
                    'adm_name' => $admDetails->name ?? 'N/A',
                    'adm_number' => $admDetails->adm_number ?? 'N/A',
                    'amount' => number_format($deposit->amount ?? 0, 2),
                    'payment_slip' => $deposit->id, // ID column from deposits table
                ];
            });

        return view('finance::dashboard.index', compact('recentCashDeposits', 'recentChequeDeposits'));
    }


    function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function settings(Request $request)
    {
        if ($request->isMethod('get')) {
            $user = User::where('id', Auth::user()->id)->with('userDetails')->first();
            return view('finance::settings.settings', ['user' => $user]);
        }
        if ($request->isMethod('post')) {
            $request->validate([
                'name'   => 'required',
                'user_role'   => 'required',
                'phone_number'   => 'required',
                'email'   => 'required | email',
            ]);

            if (!$request->password == null || !$request->password_confirmation == null || !$request->current_password == null) {

                $request->validate([
                    "password" => "required | confirmed | min:6",
                    "current_password" => "required",
                ]);
                if (Hash::check($request->input('current_password'), User::where('id', $id)->value('password'))) {
                    if (User::where("id", "=", $id)->where("email", "=", $request->email)->exists()) {
                        $email = $request->email;
                    } elseif (User::where("email", "=", $request->email)->exists()) {
                        return back()->with('fail', 'This email is already in use');
                    } else {
                        $email = $request->email;
                    }

                    $userDetails =  UserDetails::where('user_id', '=', $id)->first();
                    $userDetails->name = $request->name;
                    $userDetails->phone_number = $request->phone_number;
                    $userDetails->update();

                    $user = User::find($id);
                    $user->email = $email;
                    $user->password = Hash::make($request->input('password'));
                    $user->update();

                    ActivitLogService::log('user management',  $request->name . ' - user details updated');

                    return back()->with('success', 'User Details Successfully  Updated');
                } else {
                    return back()->with('fail', 'Current password is incorrect.');
                }
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
                $userDetails->update();
                $user = User::find($id);
                $user->email = $email;
                $user->update();

                ActivitLogService::log('user management',  $request->name . ' - user details updated');

                return back()->with('success', 'User Details Successfully  Updated');
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('finance::create');
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
        return view('finance::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('finance::edit');
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
