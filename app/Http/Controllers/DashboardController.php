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
use App\Models\Reminders;
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
            $adms = UserDetails::where('supervisor', Auth::user()->id)
            ->whereHas('user', function($q){
                $q->where('status', 'active');
            })
            ->with('admTargets', function($query) use ($formattedMonth, $currentYear) {
                $query->where('year_and_month', $currentYear . '-' . $formattedMonth);
            })
            ->orWhere('second_supervisor', Auth::user()->id)
            ->get(); // Fetch both columns
           
            $totalTarget = $adms->sum(fn($adm) => $adm->admTargets->sum('target'));
            
            // Fetch reminders
            $reminders = $this->fetchReminders();

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
                'totalTarget',
                'reminders'
            ));
        }
        if(Auth::user()->user_role == 4){
            // Get current month and year
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;

            // 1. Get Team Leaders under the ASM
            $teamLeadersDetails = UserDetails::where('supervisor', Auth::user()->id)
                ->whereHas('user', function($q){
                    $q->where('status', 'active');
                })
                ->orWhere('second_supervisor', Auth::user()->id)
                ->get();
            
            $tlIds = $teamLeadersDetails->pluck('user_id');
            $tlCount = $tlIds->count();
            $formattedMonth = str_pad($currentMonth, 2, '0', STR_PAD_LEFT);
            // 2. Get ADMs under those Team Leaders
            $adms = UserDetails::whereIn('supervisor', $tlIds)
            ->whereHas('user', function($q){
                $q->where('status', 'active');
            })
            ->with('admTargets', function($query) use ($formattedMonth, $currentYear) {
                $query->where('year_and_month', $currentYear . '-' . $formattedMonth);
            })
                ->orWhereIn('second_supervisor', $tlIds)
                ->get();
            $totalTarget = $adms->sum(fn($adm) => $adm->admTargets->sum('target'));
            $admIds = $adms->pluck('user_id');      // Collection of user_ids
            $admnos = $adms->pluck('adm_number'); 

            $admCount = $admIds->count();
            
             // Fetch reminders
            $reminders = $this->fetchReminders();

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
                'totalTarget',
                'reminders'
            ));
        }
        if(Auth::user()->user_role == 3){
            // Get current month and year
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
            $formattedMonth = str_pad($currentMonth, 2, '0', STR_PAD_LEFT);
            // 1. Get ASMs under the RSM
            $asmDetails = UserDetails::where('supervisor', Auth::user()->id)
                ->whereHas('user', function($q){
                    $q->where('status', 'active');
                })
                ->orWhere('second_supervisor', Auth::user()->id)
                ->get();
            
            $asmIds = $asmDetails->pluck('user_id');
            $asmCount = $asmIds->count();

            // 2. Get Team Leaders under those ASMs
            $teamLeadersDetails = UserDetails::whereIn('supervisor', $asmIds)
                ->whereHas('user', function($q){
                    $q->where('status', 'active');
                })
                ->orWhereIn('second_supervisor', $asmIds)
                ->get();
            
            $tlIds = $teamLeadersDetails->pluck('user_id');
            $tlCount = $tlIds->count();

            // 3. Get ADMs under those Team Leaders
            $adms = UserDetails::whereIn('supervisor', $tlIds)
            ->whereHas('user', function($q){
                $q->where('status', 'active');
            })
            ->with('admTargets', function($query) use ($formattedMonth, $currentYear) {
                $query->where('year_and_month', $currentYear . '-' . $formattedMonth);
            })
                ->orWhereIn('second_supervisor', $tlIds)
                ->get();
            $totalTarget = $adms->sum(fn($adm) => $adm->admTargets->sum('target'));
            $admIds = $adms->pluck('user_id');      // Collection of user_ids
            $admnos = $adms->pluck('adm_number'); 

            $admCount = $admIds->count();

             // Fetch reminders
            $reminders = $this->fetchReminders();

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
                'totalTarget',
                'reminders'
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
                    $q->where('user_role', 3)->where('status', 'active');
                })
                ->get();
            $rsmIds = $rsmDetails->pluck('user_id');
            $rsmCount = $rsmIds->count();

            // 2. Get ASMs under those RSMs
            $asmDetails = UserDetails::whereIn('supervisor', $rsmIds)
                ->whereHas('user', function($q){
                    $q->where('status', 'active');
                })
                ->orWhereIn('second_supervisor', $rsmIds)
                ->get();
            $asmIds = $asmDetails->pluck('user_id');
            $asmCount = $asmIds->count();

            // 3. Get Team Leaders under those ASMs
            $teamLeadersDetails = UserDetails::whereIn('supervisor', $asmIds)
                ->whereHas('user', function($q){
                    $q->where('status', 'active');
                })
                ->orWhereIn('second_supervisor', $asmIds)
                ->get();
            $tlIds = $teamLeadersDetails->pluck('user_id');
            $tlCount = $tlIds->count();

            // 4. Get ADMs under those Team Leaders
            $adms = UserDetails::whereIn('supervisor', $tlIds)
            ->whereHas('user', function($q){
                $q->where('status', 'active');
            })
            ->with('admTargets', function($query) use ($formattedMonth, $currentYear) {
                $query->where('year_and_month', $currentYear . '-' . $formattedMonth);
            })
                ->orWhereIn('second_supervisor', $tlIds)    
                ->get();
            $totalTarget = $adms->sum(fn($adm) => $adm->admTargets->sum('target'));
            $admIds = $adms->pluck('user_id');      // Collection of user_ids
            $admnos = $adms->pluck('adm_number'); 
            $admCount = $admIds->count();

             // Fetch reminders
            $reminders = $this->fetchReminders();

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
                'totalTarget',
                'reminders'
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


    private function fetchReminders()
    {
        $currentUser = Auth::user();
        $currentUserId = $currentUser->id;
        $currentUserRole = $currentUser->user_role;
        $currentUserDivision = $currentUser->userDetails->division ?? null;
        $isGlobalUser = in_array($currentUserRole, [1, 7]);

        // Start Query with Joins for Filtering
        $query = Reminders::select('reminders.*')
            ->leftJoin('users as sender', 'reminders.sent_user_id', '=', 'sender.id')
            ->leftJoin('user_details as sender_details', 'sender.id', '=', 'sender_details.user_id');

        // CORE VISIBILITY LOGIC
        $query->where(function ($mainGroup) use ($currentUserId, $currentUserRole, $currentUserDivision, $isGlobalUser) {
            
            // 1. Direct Match: User ID is in send_to
            $mainGroup->whereJsonContains('reminders.send_to', (string)$currentUserId)
            
            // 2. Role Match: send_to is empty but user_level matched
            ->orWhere(function ($roleQ) use ($currentUserRole, $currentUserDivision, $isGlobalUser) {
                $roleQ->where('reminders.user_level', $currentUserRole)
                      ->whereNull('reminders.send_to');

                // Division matching (does not apply to roles 1 and 7)
                if (!$isGlobalUser) {
                    $roleQ->where(function ($divCheck) use ($currentUserDivision) {
                        // If division is set on reminder, user must match it
                        $divCheck->where('reminders.division', $currentUserDivision)
                        // If division is NOT set, match with sender division
                        ->orWhere(function ($implicit) use ($currentUserDivision) {
                            $implicit->whereNull('reminders.division')
                                     ->where(function ($s) use ($currentUserDivision) {
                                         $s->where('sender_details.division', $currentUserDivision)
                                           ->orWhereIn('sender.user_role', [1, 7]);
                                     });
                        });
                    });
                }
            })

            // 3. Hierarchy / In-Between Visibility
            // Logic: A user sees a reminder if they are in the hierarchy path between Sender and Target
            ->orWhere(function ($flowQ) use ($currentUserId, $currentUserRole, $currentUserDivision, $isGlobalUser) {
                // Recovery Manager (8) Exception: If sender or target is role 8, skip hierarchy flow
                $flowQ->where('sender.user_role', '!=', 8)
                      ->where('reminders.user_level', '!=', 8);

                // Hierarchy Rank: 1=SysAdmin, 7=Finance, 2=HOD, 3=RSM, 4=ASM, 5=TL, 6=ADM, 8=Recovery
                $fieldList = "1,7,2,3,4,5,6,8";
                
                // --- RANK CHECK (Grouped) ---
                $flowQ->where(function($rankGrp) use ($currentUserRole, $fieldList) {
                    $rankGrp->where(function($rankCheck) use ($currentUserRole, $fieldList) {
                        $rankCheck->whereRaw("FIELD(?, $fieldList) > FIELD(sender.user_role, $fieldList)", [$currentUserRole])
                                  ->whereRaw("FIELD(?, $fieldList) < IFNULL(FIELD(reminders.user_level, $fieldList), 9)", [$currentUserRole]);
                    })
                    ->orWhere(function($rankCheckRev) use ($currentUserRole, $fieldList) {
                        $rankCheckRev->whereRaw("FIELD(?, $fieldList) < FIELD(sender.user_role, $fieldList)", [$currentUserRole])
                                     ->whereRaw("FIELD(?, $fieldList) > IFNULL(FIELD(reminders.user_level, $fieldList), 0)", [$currentUserRole]);
                    });
                });

                // --- CONNECTION CHECK (Strict Path vs Division Match) ---
                $flowQ->where(function ($connQ) use ($currentUserId, $currentUserDivision, $isGlobalUser) {
                    // Global users (1 and 7) see all flow between sender and target
                    if ($isGlobalUser) {
                        $connQ->whereRaw('1=1');
                        return;
                    }

                    // Case A: Role Broadcast (send_to is empty)
                    // Match current user division with either reminder division or sender division
                    $connQ->where(function($roleProc) use ($currentUserDivision) {
                        $roleProc->whereNull('reminders.send_to')
                                 ->where(function($divCheck) use ($currentUserDivision) {
                                     $divCheck->where('reminders.division', $currentUserDivision)
                                              ->orWhere('sender_details.division', $currentUserDivision);
                                 });
                    })
                    // Case B: Specific Recipient (send_to has IDs)
                    // Strict supervisor check: User must be in the direct supervisor chain of lower-ranked party
                    ->orWhere(function($specProc) use ($currentUserId) {
                        $specProc->whereNotNull('reminders.send_to')
                        ->where(function($strictQ) use ($currentUserId) {
                             // --- SENDER SIDE HIERARCHY ---
                             $strictQ->where(function($sChain) use ($currentUserId) {
                                 // L1: Direct supervisor
                                 $sChain->where('sender_details.supervisor', $currentUserId)
                                        ->orWhere('sender_details.second_supervisor', $currentUserId)
                                        // L2: Supervisor of supervisor
                                        ->orWhereExists(function($l2) use ($currentUserId) {
                                            $l2->from('user_details as u2')
                                               ->whereColumn('u2.user_id', 'sender_details.supervisor')
                                               ->where(fn($q) => $q->where('u2.supervisor', $currentUserId)->orWhere('u2.second_supervisor', $currentUserId));
                                        })
                                        // L3: Supervisor of Level 2 supervisor
                                        ->orWhereExists(function($l3) use ($currentUserId) {
                                            $l3->from('user_details as u2_3')
                                               ->whereColumn('u2_3.user_id', 'sender_details.supervisor')
                                               ->whereExists(function($l2_3) use ($currentUserId) {
                                                   $l2_3->from('user_details as u3')
                                                        ->whereColumn('u3.user_id', 'u2_3.supervisor')
                                                        ->where(fn($q) => $q->where('u3.supervisor', $currentUserId)->orWhere('u3.second_supervisor', $currentUserId));
                                               });
                                        })
                                        // L4: Supervisor of Level 3 supervisor
                                        ->orWhereExists(function($l4) use ($currentUserId) {
                                            $l4->from('user_details as u2_4')
                                               ->whereColumn('u2_4.user_id', 'sender_details.supervisor')
                                               ->whereExists(function($l3_4) use ($currentUserId) {
                                                   $l3_4->from('user_details as u3_4')
                                                        ->whereColumn('u3_4.user_id', 'u2_4.supervisor')
                                                        ->whereExists(function($l2_4) use ($currentUserId) {
                                                            $l2_4->from('user_details as u4')
                                                                 ->whereColumn('u4.user_id', 'u3_4.supervisor')
                                                                 ->where(fn($q) => $q->where('u4.supervisor', $currentUserId)->orWhere('u4.second_supervisor', $currentUserId));
                                                        });
                                               });
                                        });
                             })
                             // --- RECIPIENT SIDE HIERARCHY ---
                             ->orWhereExists(function ($sub) use ($currentUserId) {
                                 $sub->from('user_details as rd')
                                     ->whereRaw("JSON_CONTAINS(reminders.send_to, JSON_QUOTE(CAST(rd.user_id AS CHAR)))")
                                     ->where(function($rChain) use ($currentUserId) {
                                         // L1: Direct supervisor
                                         $rChain->where('rd.supervisor', $currentUserId)
                                                ->orWhere('rd.second_supervisor', $currentUserId)
                                                // L2
                                                ->orWhereExists(function($rl2) use ($currentUserId) {
                                                    $rl2->from('user_details as rd2')->whereColumn('rd2.user_id', 'rd.supervisor')
                                                        ->where(fn($q) => $q->where('rd2.supervisor', $currentUserId)->orWhere('rd2.second_supervisor', $currentUserId));
                                                })
                                                // L3
                                                ->orWhereExists(function($rl3) use ($currentUserId) {
                                                    $rl3->from('user_details as rd2_3')->whereColumn('rd2_3.user_id', 'rd.supervisor')
                                                         ->whereExists(function($rl3_in) use ($currentUserId) {
                                                             $rl3_in->from('user_details as rd3')->whereColumn('rd3.user_id', 'rd2_3.supervisor')
                                                                    ->where(fn($q) => $q->where('rd3.supervisor', $currentUserId)->orWhere('rd3.second_supervisor', $currentUserId));
                                                         });
                                                })
                                                // L4
                                                ->orWhereExists(function($rl4) use ($currentUserId) {
                                                    $rl4->from('user_details as rd2_4')->whereColumn('rd2_4.user_id', 'rd.supervisor')
                                                        ->whereExists(function($rl3_4) use ($currentUserId) {
                                                            $rl3_4->from('user_details as rd3_4')->whereColumn('rd3_4.user_id', 'rd2_4.supervisor')
                                                                ->whereExists(function($rl2_4) use ($currentUserId) {
                                                                    $rl2_4->from('user_details as rd4')->whereColumn('rd4.user_id', 'rd3_4.supervisor')
                                                                        ->where(fn($q) => $q->where('rd4.supervisor', $currentUserId)->orWhere('rd4.second_supervisor', $currentUserId));
                                                                });
                                                        });
                                                });
                                     });
                             });
                        });
                    });
                });
            });
        });

        // Default: Show TODAY's reminders + Future? or just All relevant pending ones?
        // User request: "like ReminderController index function"
        // ReminderController index shows ALL (paginated) relevant reminders.
        // Dashboard usually implies "Active" or "Recent" or "Today".
        // Let's grab recent 5-10 or Today's. 
        // Reminder Controller logic effectively lists a history/calendar.
        // For a dashboard notification tab, we probably want "Upcoming" or "Recent".
        // Let's stick to Recent (Order by Date Desc) and take 10.
        // And maybe filter only pending/unread if we had that flag (we have is_read but that's per reminder not per user).
        
        return $query->orderByDesc("reminders.reminder_date")
            ->take(10)
            ->get();
    }

}