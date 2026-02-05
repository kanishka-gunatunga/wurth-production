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
use App\Models\RolePermissions;
use App\Models\Customers;
use File;
use Mail;
use Image;
use PDF;

class DashboardController extends Controller
{
    

    public function dashboard()
    {
        if(Auth::user()->user_role == 5){
            // Get current month and year
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
            $formattedMonth = str_pad($currentMonth, 2, '0', STR_PAD_LEFT);
            $adms = UserDetails::where('supervisor', Auth::user()->id)->with('admTargets', function($query) use ($formattedMonth, $currentYear) {
                $query->where('year_and_month', $currentYear . '-' . $formattedMonth);
            })
            ->orWhere('second_supervisor', Auth::user()->id)
            ->get(); // Fetch both columns
           
            $totalTarget = $adms->sum(fn($adm) => $adm->admTargets->sum('target'));

            $latestAdms = $adms
            ->filter(fn($adm) =>
                $adm->created_at->year == $currentYear &&
                $adm->created_at->month == $currentMonth
            )
            ->sortByDesc('created_at')
            ->take(5);

            $admIds = $adms->pluck('user_id');      // Collection of user_ids
            $admnos = $adms->pluck('adm_number'); 

            $admCount= $admIds->count();

            $collections = InvoicePayments::with(['adm.userDetails','invoice.customer'])
            ->whereIn('adm_id', $admIds)
            ->where('status', '!=', 'voided')
            ->get();

            $currentMonthCollections = $collections->filter(function($payment) use ($currentMonth, $currentYear) {
                return $payment->created_at->year == $currentYear 
                    && $payment->created_at->month == $currentMonth;
            });

            $customers = Customers::whereIn('adm', $admnos)
            ->orWhereIn('secondary_adm', $admnos)
            ->orderBy('created_at', 'desc')
            ->get();

            $recentCustomers = $customers->take(5);

            return view('dashboard.team_leader_dashboard', compact(
                'admCount',
                'collections',
                'currentMonthCollections',
                'customers',
                'adms',
                'latestAdms',
                'recentCustomers',
                'totalTarget'
            ));
        }
        if(Auth::user()->user_role == 4){
            // Get current month and year
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;

            // 1. Get Team Leaders under the ASM
            $teamLeadersDetails = UserDetails::where('supervisor', Auth::user()->id)
                ->orWhere('second_supervisor', Auth::user()->id)
                ->get();
            
            $tlIds = $teamLeadersDetails->pluck('user_id');
            $tlCount = $tlIds->count();
            $formattedMonth = str_pad($currentMonth, 2, '0', STR_PAD_LEFT);
            // 2. Get ADMs under those Team Leaders
            $adms = UserDetails::whereIn('supervisor', $tlIds)->with('admTargets', function($query) use ($formattedMonth, $currentYear) {
                $query->where('year_and_month', $currentYear . '-' . $formattedMonth);
            })
                ->orWhereIn('second_supervisor', $tlIds)
                ->get();
            $totalTarget = $adms->sum(fn($adm) => $adm->admTargets->sum('target'));
            $admIds = $adms->pluck('user_id');      // Collection of user_ids
            $admnos = $adms->pluck('adm_number'); 

            $admCount = $admIds->count();

            // 3. Recently assigned users (ADMs or Team Leaders)
            $combinedUsers = $teamLeadersDetails->merge($adms);

            $latestUsers = $combinedUsers
                ->filter(fn($user) =>
                    $user->created_at->year == $currentYear &&
                    $user->created_at->month == $currentMonth
                )
                ->sortByDesc('created_at')
                ->take(5);

            $collections = InvoicePayments::with(['adm.userDetails','invoice.customer'])
                ->whereIn('adm_id', $admIds)
                ->where('status', '!=', 'voided')
                ->get();

            $currentMonthCollections = $collections->filter(function($payment) use ($currentMonth, $currentYear) {
                return $payment->created_at->year == $currentYear 
                    && $payment->created_at->month == $currentMonth;
            });

            $customers = Customers::whereIn('adm', $admnos)
                ->orWhereIn('secondary_adm', $admnos)
                ->orderBy('created_at', 'desc')
                ->get();

            $recentCustomers = $customers->take(5);

            return view('dashboard.area_sales_dashboard', compact(
                'tlCount',
                'admCount',
                'collections',
                'currentMonthCollections',
                'customers',
                'adms',
                'latestUsers',
                'recentCustomers',
                'totalTarget'
            ));
        }
        if(Auth::user()->user_role == 3){
            // Get current month and year
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
            $formattedMonth = str_pad($currentMonth, 2, '0', STR_PAD_LEFT);
            // 1. Get ASMs under the RSM
            $asmDetails = UserDetails::where('supervisor', Auth::user()->id)
                ->orWhere('second_supervisor', Auth::user()->id)
                ->get();
            
            $asmIds = $asmDetails->pluck('user_id');
            $asmCount = $asmIds->count();

            // 2. Get Team Leaders under those ASMs
            $teamLeadersDetails = UserDetails::whereIn('supervisor', $asmIds)
                ->orWhereIn('second_supervisor', $asmIds)
                ->get();
            
            $tlIds = $teamLeadersDetails->pluck('user_id');
            $tlCount = $tlIds->count();

            // 3. Get ADMs under those Team Leaders
            $adms = UserDetails::whereIn('supervisor', $tlIds)->with('admTargets', function($query) use ($formattedMonth, $currentYear) {
                $query->where('year_and_month', $currentYear . '-' . $formattedMonth);
            })
                ->orWhereIn('second_supervisor', $tlIds)
                ->get();
            $totalTarget = $adms->sum(fn($adm) => $adm->admTargets->sum('target'));
            $admIds = $adms->pluck('user_id');      // Collection of user_ids
            $admnos = $adms->pluck('adm_number'); 

            $admCount = $admIds->count();

            // 4. Recently assigned users (ASMs, Team Leaders, or ADMs)
            $combinedUsers = $asmDetails->merge($teamLeadersDetails)->merge($adms);

            $latestUsers = $combinedUsers
                ->filter(fn($user) =>
                    $user->created_at->year == $currentYear &&
                    $user->created_at->month == $currentMonth
                )
                ->sortByDesc('created_at')
                ->take(5);

            $collections = InvoicePayments::with(['adm.userDetails','invoice.customer'])
                ->whereIn('adm_id', $admIds)
                ->where('status', '!=', 'voided')
                ->get();

            $currentMonthCollections = $collections->filter(function($payment) use ($currentMonth, $currentYear) {
                return $payment->created_at->year == $currentYear 
                    && $payment->created_at->month == $currentMonth;
            });

            $customers = Customers::whereIn('adm', $admnos)
                ->orWhereIn('secondary_adm', $admnos)
                ->orderBy('created_at', 'desc')
                ->get();

            $recentCustomers = $customers->take(5);

            return view('dashboard.regional_sales_dashboard', compact(
                'asmCount',
                'tlCount',
                'admCount',
                'collections',
                'currentMonthCollections',
                'customers',
                'adms',
                'latestUsers',
                'recentCustomers',
                'totalTarget'
            ));
        }
         if(Auth::user()->user_role == 2){
            // Get current month and year
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
            $formattedMonth = str_pad($currentMonth, 2, '0', STR_PAD_LEFT);
            // Get HOD's division
            $hodDivision = Auth::user()->userDetails->division;

            // 1. Get RSMs in HOD's division
            $rsmDetails = UserDetails::where('division', $hodDivision)
                ->whereHas('user', function($q){
                    $q->where('user_role', 3);
                })
                ->get();
            $rsmIds = $rsmDetails->pluck('user_id');
            $rsmCount = $rsmIds->count();

            // 2. Get ASMs under those RSMs
            $asmDetails = UserDetails::whereIn('supervisor', $rsmIds)
                ->orWhereIn('second_supervisor', $rsmIds)
                ->get();
            $asmIds = $asmDetails->pluck('user_id');
            $asmCount = $asmIds->count();

            // 3. Get Team Leaders under those ASMs
            $teamLeadersDetails = UserDetails::whereIn('supervisor', $asmIds)
                ->orWhereIn('second_supervisor', $asmIds)
                ->get();
            $tlIds = $teamLeadersDetails->pluck('user_id');
            $tlCount = $tlIds->count();

            // 4. Get ADMs under those Team Leaders
            $adms = UserDetails::whereIn('supervisor', $tlIds)->with('admTargets', function($query) use ($formattedMonth, $currentYear) {
                $query->where('year_and_month', $currentYear . '-' . $formattedMonth);
            })
                ->orWhereIn('second_supervisor', $tlIds)    
                ->get();
            $totalTarget = $adms->sum(fn($adm) => $adm->admTargets->sum('target'));
            $admIds = $adms->pluck('user_id');      // Collection of user_ids
            $admnos = $adms->pluck('adm_number'); 
            $admCount = $admIds->count();

            // 5. Recently assigned users (RSMs, ASMs, Team Leaders, or ADMs)
            $combinedUsers = $rsmDetails->merge($asmDetails)->merge($teamLeadersDetails)->merge($adms);

            $latestUsers = $combinedUsers
                ->filter(fn($user) =>
                    $user->created_at->year == $currentYear &&
                    $user->created_at->month == $currentMonth
                )
                ->sortByDesc('created_at')
                ->take(5);

            $collections = InvoicePayments::with(['adm.userDetails','invoice.customer'])
                ->whereIn('adm_id', $admIds)
                ->where('status', '!=', 'voided')
                ->get();

            $currentMonthCollections = $collections->filter(function($payment) use ($currentMonth, $currentYear) {
                return $payment->created_at->year == $currentYear 
                    && $payment->created_at->month == $currentMonth;
            });

            $customers = Customers::whereIn('adm', $admnos)
                ->orWhereIn('secondary_adm', $admnos)
                ->orderBy('created_at', 'desc')
                ->get();

            $recentCustomers = $customers->take(5);

            return view('dashboard.head_of_division', compact(
                'rsmCount',
                'asmCount',
                'tlCount',
                'admCount',
                'collections',
                'currentMonthCollections',
                'customers',
                'adms',
                'latestUsers',
                'recentCustomers',
                'totalTarget'
            ));
        }
        else{
            // Get current month and year
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
           
            // Sum deposits for the current month with status 'approved'
            $currentMonthDeposits = Deposits::whereYear('date_time', $currentYear)
                ->whereMonth('date_time', $currentMonth)
                ->where('status', 'approved')
                ->sum('amount');

            $onHandCollections = InvoicePayments::where('status', 'pending')
                ->whereIn('type', ['cash', 'cheque'])
                ->sum('final_payment');

            $monthCollections = InvoicePayments::where('status', 'approved')
                ->sum('final_payment');

            $monthChequeCollections = InvoicePayments::where('status', 'approved')
                ->where('type', 'cheque')
                ->sum('final_payment');

            $monthCashOnHand = InvoicePayments::where('status', 'pending')
                ->where('type', 'cash')
                ->sum('final_payment');

            $locked_users = User::where('is_locked', 1)->with('userDetails')->take(10)->get();
            $logs = ActivtiyLog::with('userData.userDetails')->orderBy('id', 'DESC')->take(10)->get();

            return view('dashboard.dashboard', compact(
                'currentMonthDeposits',
                'onHandCollections',
                'monthCollections',
                'monthChequeCollections',
                'monthCashOnHand',
                'locked_users',
                'logs'
            ));
        }
       
    }

    
}
