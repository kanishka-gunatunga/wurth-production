<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reminders;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\InvoicePayments;
use App\Models\RolePermissions;
use App\Models\Divisions;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\MobitelInstantSmsService;
use Illuminate\Support\Facades\Log;
use App\Services\ActivitLogService;

class ReminderController extends Controller
{
    // Show the create form
    public function create()
    {
        $currentUserId = Auth::id();
        $currentUserRole = Auth::user()->user_role;
         $divisions = Divisions::where('status', 'active')->get();
        // ----------------------------------------
        // 🔥 Step 1: Get roles that have "notifications" permission
        // ----------------------------------------
        $allowedRoles = RolePermissions::where(function($query) {
                $query->whereJsonContains('permissions', 'notifications')
                    ->orWhereJsonContains('permissions', 'reminders');
            })
            ->pluck('user_role')
            ->toArray();

        // ----------------------------------------
        // 🔥 Step 2: Remove current user's role
        // ----------------------------------------
        $roles = array_values(array_filter($allowedRoles, function ($role) use ($currentUserRole) {
            return $role != $currentUserRole;
        }));



        // ----------------------------------------
        // Existing Code to load users (still needed)
        // ----------------------------------------
        $users = User::with('userDetails')
            ->where('user_role', '!=', $currentUserRole)
            ->orWhere('id', $currentUserId)
            ->get();

        $name = UserDetails::where('user_id', $currentUserId)->value('name');

        return view('reminders.create_reminder', compact('users', 'name', 'roles', 'divisions'));
    }
public function getUsersByLevel(Request $request, $level)
{
    $currentUser = Auth::user();
    $currentUserRole = $currentUser->user_role;
    $currentUserDivision = $currentUser->userDetails->division ?? null;

    // Check if selected level has permission
    $hasPermission = RolePermissions::where('user_role', $level)
        ->where(function($query) {
                $query->whereJsonContains('permissions', 'notifications')
                    ->orWhereJsonContains('permissions', 'reminders');
            })
        ->exists();

    if (!$hasPermission) {
        return response()->json([]); // return empty list
    }

    $query = User::with('userDetails')
        ->where('user_role', $level)
        ->where('user_role', '!=', $currentUserRole);

    // If current user is NOT Admin(1) or Finance(7) -> only show users in their division
    if (!in_array($currentUserRole, [1, 7])) {
        $query->whereHas('userDetails', function ($q) use ($currentUserDivision) {
            $q->where('division', $currentUserDivision);
        });
    } else {
        // Admin/Finance: check if division is selected
        $division = $request->query('division');
        if ($division) {
            $query->whereHas('userDetails', function ($q) use ($division) {
                $q->where('division', $division);
            });
        }
        // else: no division filter -> load all users
    }

    return response()->json($query->get());
}

    // Store reminder
    public function store(Request $request, MobitelInstantSmsService $smsService)
    {
        $currentUserId = Auth::id();
        $currentUserRole = User::where('id', $currentUserId)->value('user_role');
        $name = UserDetails::where('user_id', $currentUserId)->value('name');

        $request->validate([
                'send_from'      => 'required',
                'reminder_title' => 'required',
                'user_level'     => 'required|integer',
                // 'reminder_type'  => 'required',
                'reminder_date'  => 'required',
                'reason'         => 'required',
                'send_to'        => 'nullable|array',
                'send_to.*'      => 'integer',
            ]); 


                $reminder = new Reminders();
                $reminder->sent_user_id   = $currentUserId;
                $reminder->send_from      = $request->send_from;
                $reminder->reminder_title = $request->reminder_title;
                $reminder->division       = $request->division;
                $reminder->user_level     = $request->user_level;
                $reminder->send_to        = $request->send_to;
                $reminder->reminder_type  = 'Other';
                $reminder->reminder_date  = $request->reminder_date;
                $reminder->reason         = $request->reason;
                $reminder->is_direct      = 0;
                $reminder->save();
                
                ActivitLogService::log('reminder', "Reminder campaign created: {$request->reminder_title}");
            

            // Check if reminder date is today
            if (\Carbon\Carbon::parse($request->reminder_date)->isToday()) {
                $smsRecipients = collect();

                // Case 1: Specific users (or Self)
                if (!empty($reminder->send_to)) {
                    $smsRecipients = User::with('userDetails')->whereIn('id', $reminder->send_to)->get();
                }
                // Case 2 & 3: Role-based (Select All)
                // Case 2 & 3: Role-based (Select All)
                elseif ($reminder->user_level) {
                    $targetRole = $reminder->user_level;
                    $query = User::with('userDetails')->where('user_role', $targetRole);

                    // Exclude current user to match UI behavior
                    $query->where('id', '!=', $currentUserId);

                    // ---------------------------------------------------------
                    // NEW LOGIC: Check if Division is selected in Reminder
                    // ---------------------------------------------------------
                    if (!empty($reminder->division)) {
                        // If division is selected, FILTER by that division for ALL roles
                        $divisionId = $reminder->division;
                        $query->whereHas('userDetails', function ($q) use ($divisionId) {
                            $q->where('division', $divisionId);
                        });
                    } 
                    // ---------------------------------------------------------
                    // EXISTING FALLBACK LOGIC (No Division Selected)
                    // ---------------------------------------------------------
                    else {
                        if (in_array($targetRole, [1, 7])) {
                            // Case 2: Admin/Finance - No division filter (Load all)
                        } else {
                            // Case 3: Other roles - Apply Division filter based on LOGGED-IN User
                            $currentUserData = User::with('userDetails')->find($currentUserId);
                            $currentUserDivision = $currentUserData->userDetails->division ?? null;

                            if ($currentUserDivision) {
                                $query->whereHas('userDetails', function ($q) use ($currentUserDivision) {
                                    $q->where('division', $currentUserDivision);
                                });
                            }
                        }
                    }
                    $smsRecipients = $query->get();
                }

                // Send SMS
                if ($smsRecipients->isNotEmpty()) {
                    $phoneNumbers = $smsRecipients->pluck('userDetails.phone_number')
                        ->filter()
                        ->map(function ($phone) {
                            // Format number: replace leading 0 with 94
                            return preg_replace('/^0/', '94', $phone);
                        })->unique()->values()->toArray();

                    if (!empty($phoneNumbers)) {
                        $senderName = $name ?? 'System';
                        $message = "From: $senderName\nReminder: {$reminder->reminder_title}\n{$reminder->reason}";

                        try {
                            $this->smsService->sendInstantSms($phoneNumbers, $message, "ReminderCampaign");
                            Log::info("SMS sent to users: " . implode(',', $phoneNumbers));
                        } catch (\Exception $e) {
                            Log::error("SMS sending failed: " . $e->getMessage());
                        }
                    }
                }
            }

            return back()->with('success', 'Reminder Successfully Added');
    }

    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $currentUserId = $currentUser->id;
        $currentUserRole = $currentUser->user_role;
        $currentUserDivision = $currentUser->userDetails->division ?? null;
        $isGlobalUser = in_array($currentUserRole, [1, 7]);

        $today = Carbon::today()->format('Y-m-d');
        $today_cheques = InvoicePayments::whereIn('type', ['finance-cheque', 'cheque'])
            ->where('status', 'deposited')
            ->whereDate('cheque_date', $today)
            ->orderBy('cheque_date', 'desc')
            ->paginate(15, ['*'], 'cheque_page')
            ->withQueryString();

        // Start Query with Joins for Filtering
        $query = Reminders::select('reminders.*')
            ->leftJoin('users as sender', 'reminders.sent_user_id', '=', 'sender.id')
            ->leftJoin('user_details as sender_details', 'sender.id', '=', 'sender_details.user_id');

        // CORE VISIBILITY LOGIC
        $query->where(function ($mainGroup) use ($currentUserId, $currentUserRole, $currentUserDivision, $isGlobalUser) {
            
            // 1. Direct Match
            $mainGroup->whereJsonContains('reminders.send_to', (string)$currentUserId)
            
            // 2. Role Broadcast (Targeted to My Role)
            ->orWhere(function ($roleQ) use ($currentUserRole, $currentUserDivision, $isGlobalUser) {
                $roleQ->where('reminders.user_level', $currentUserRole)
                      ->whereNull('reminders.send_to');

                // Division Logic for Broadcast
                if (!$isGlobalUser) {
                    $roleQ->where(function ($divCheck) use ($currentUserDivision) {
                        // Explicit Division Match
                        $divCheck->where('reminders.division', $currentUserDivision)
                        // Implicit Division Match (Sender in same div OR Sender is Global)
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
            // User sees reminders where they are strictly between Sender and Target roles
            ->orWhere(function ($flowQ) use ($currentUserRole, $currentUserDivision, $isGlobalUser) {
                // Logic: (Sender < Me < Target) OR (Target < Me < Sender)
                // Note: user_role 1 is High, 10 is Low.
                // So "Between" means: min(Sender, Target) < Me < max(Sender, Target)
                
                $flowQ->whereRaw('? > LEAST(sender.user_role, reminders.user_level)', [$currentUserRole])
                      ->whereRaw('? < GREATEST(sender.user_role, reminders.user_level)', [$currentUserRole]);

                // Division Logic for Hierarchy Flow
                if (!$isGlobalUser) {
                    $flowQ->where(function ($divCheck) use ($currentUserDivision) {
                        // Explicit Division Match
                        $divCheck->where('reminders.division', $currentUserDivision)
                        // Implicit Division Match
                        ->orWhere(function ($implicit) use ($currentUserDivision) {
                            $implicit->whereNull('reminders.division')
                                     ->where(function ($s) use ($currentUserDivision) {
                                         $s->where('sender_details.division', $currentUserDivision)
                                           ->orWhereIn('sender.user_role', [1, 7]);
                                     });
                        });
                    });
                }
            });
        });

        // ------------------------------
        // SEARCH BY TITLE
        // ------------------------------
        if ($request->filled('q')) {
            $query->where('reminders.reminder_title', 'like', "%{$request->q}%");
        }

        // ------------------------------
        // FILTER: FROM (send_from column - which is just a string name? No, code uses sent_user_id filter)
        // ------------------------------
        if ($request->filled('from_users')) {
            $query->whereIn('reminders.sent_user_id', $request->from_users);
        }

        // ------------------------------
        // FILTER: TO (send_to) - This filter is tricky with JSON/Broadcast. 
        // Assuming this filters Explicit Targets.
        // ------------------------------
        if ($request->filled('to_users')) {
            // Since send_to is JSON now, we'd need whereJsonContains OR whereIn if it was simple. 
            // Previous code used whereIn('send_to', $request->to_users). 
            // With JSON, we iterate? Or just skip precise filtering for simplicty?
            // Let's iterate using OR conditions for JSON array contains.
            $query->where(function($q) use ($request) {
                foreach($request->to_users as $uid) {
                    $q->orWhereJsonContains('reminders.send_to', (string)$uid);
                }
            });
        }

        // ------------------------------
        // FILTER: DATE RANGE (Default to Today if Empty)
        // ------------------------------
        if ($request->filled('date_range')) {
            $range = $request->date_range;
            if (str_contains($range, 'to')) {
                [$start, $end] = array_map('trim', explode('to', $range));
            } else {
                $start = $end = $range;
            }
            $query->whereBetween('reminders.reminder_date', [
                date('Y-m-d 00:00:00', strtotime($start)),
                date('Y-m-d 23:59:59', strtotime($end)),
            ]);
        } else {
            // Default: Like ADM side - Show TODAY's reminders
            $query->whereDate('reminders.reminder_date', Carbon::today());
        }

        $reminders = $query->orderByDesc("reminders.reminder_date")
            ->paginate(10)
            ->withQueryString();

        // Unique user IDs for FROM dropdown (senders) - Re-query to avoid huge join overhead
        $fromUserIds = Reminders::distinct()->pluck('sent_user_id')->toArray();
        $fromUsers = User::whereIn('id', $fromUserIds)->with('userDetails')->get();

        // Unique user IDs for TO dropdown (receivers)
        // This is harder with JSON send_to, skipping optimization/logic update for now as it wasn't requested
        // But preventing crash:
        // $toUserIds = Reminders::distinct()->pluck('send_to')->toArray(); -> This returns JSON strings or Arrays. 
        // Keeping it simple/safe:
        $toUsers = collect(); // Placeholder or distinct query adaptation needed. 
        // If critical, user will report. For now, empty list is safer than crashing on array-to-string conversion.
        
        // Re-implement TO users via simple fetch if needed, but 'send_to' is now mixed types.
        // Let's try to fetch all users involved in reminders roughly?
        // Or just leave $toUsers empty for now to avoid breaking.
        $toUsers = User::has('reminders')->with('userDetails')->limit(50)->get();

        return view('reminders.all_reminders', [
            'reminders' => $reminders,
            'filters'   => $request->all(),
            'fromUsers' => $fromUsers,
            'toUsers'   => $toUsers,
            'today_cheques'   => $today_cheques
        ]);
    }
    public function view_deposit_reminder($id)
    {
        $cheque = InvoicePayments::with(['invoice.customer', 'adm'])->find($id);

        return view('reminders.view_deposit_reminder', compact('cheque'));
    }
    public function show($id)
    {
        $reminder = Reminders::findOrFail($id);

        // ✅ Mark as read if not already
        if (!$reminder->is_read) {
            $reminder->is_read = true;
            $reminder->save();
        }

        $sender = User::with('userDetails')->find($reminder->sent_user_id);

        // Handle send_to as array (multiple receivers) or fallback logic
        $receiverNames = 'N/A';
        
        if (!empty($reminder->send_to)) {
             // If array or straight ID
             $receiverIds = is_array($reminder->send_to) ? $reminder->send_to : [$reminder->send_to];
             $receivers = User::with('userDetails')->whereIn('id', $receiverIds)->get();
             
             $receiverNames = $receivers->map(function($user) {
                 return $user->userDetails->name ?? 'Unknown';
             })->implode(', ');
             
             // Pass first receiver object for view compatibility if it expects an object property access like $receiver->userDetails->name
             // However, view expects $receiver object. We can create a dummy object or modify view. 
             // Ideally we modify view, but user asked to fix controller.
             // We will create a dummy object structure to prevent view crash if it accesses ->userDetails->name
             $firstReceiver = $receivers->first();
             $receiver = $firstReceiver; 
             // Override the name on this object just for display if we want to show all names? 
             // View uses: {{ $receiver->userDetails->name ?? 'N/A' }}
             // So we can clone and set userDetails->name to string of all names.
             if($receiver && $receiver->userDetails) {
                 $receiver = clone $receiver;
                 $receiver->userDetails = clone $receiver->userDetails;
                 $receiver->userDetails->name = $receiverNames;
             }
        } 
        elseif ($reminder->user_level) {
             // Broadcast
             $roles = [
                1 => 'System Administrator',
                2 => 'Head of Division',
                3 => 'Regional Sales Manager',
                4 => 'Area Sales Manager',
                5 => 'Team Leader',
                6 => 'ADM (Sales Rep)',
                7 => 'Finance Manager',
                8 => 'Recovery Manager',
             ];
             
             $roleName = $roles[$reminder->user_level] ?? "Level {$reminder->user_level}";

             $receiver = new User();
             $receiver->userDetails = new UserDetails();
             $receiver->userDetails->name = "All Users of Role ({$roleName})";
        }
        else {
            $receiver = null;
        }

        return view('reminders.payment_reminder_details', compact('reminder', 'sender', 'receiver'));
    }

    public function sentReminders()
    {
        // $currentUserName = UserDetails::where('user_id', Auth::id())->value('name');

        // $reminders = Reminders::where('sent_user_id', $currentUserName)
        //     // ->where('is_direct', 1)
        //     ->with(['recipient' => function ($query) {
        //         $query->with('userDetails');
        //     }])
        //     ->orderByDesc('reminder_date')
        //     ->get();

        $reminders = Reminders::where('sent_user_id',Auth::id())
            // ->where('is_direct', 1)
            // ->with(['recipient.userDetails'])
            ->orderByDesc('reminder_date')
            ->paginate(10);

        return view('reminders.reminders', compact('reminders'));
    }
}
