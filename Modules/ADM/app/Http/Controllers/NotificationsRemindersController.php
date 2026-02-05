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

use App\Services\MobitelInstantSmsService;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\RolePermissions;
use App\Models\Invoices;
use App\Models\Customers;
use App\Models\Reminders;
use App\Models\Notifications;

use File;
use Mail;
use Image;
use PDF;
use App\Services\ActivitLogService;

class NotificationsRemindersController extends Controller
{
    protected $smsService;

    public function __construct(MobitelInstantSmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Display a listing of the resource.
     */

    public function notifications_and_reminders()
    {
        $currentUserId = Auth::id();

        // Get reminders for today:
        // 1. Directly sent to the user (send_to contains ID)
        // 2. Sent to the user's level (user_level match) AND send_to is NULL (Broadcast), 
        //    filtered by sender logic (Admin/Finance OR Same Division)
        
        $currentUser = Auth::user();
        $currentUserRole = $currentUser->user_role;
        $currentUserDivision = $currentUser->userDetails->division ?? null;

        $reminders = Reminders::with(['user', 'user.userDetails'])
            ->whereDate('reminder_date', \Carbon\Carbon::today())
            ->where(function ($query) use ($currentUserId, $currentUserRole, $currentUserDivision) {
                // Direct match
                $query->whereJsonContains('send_to', (string) $currentUserId)
                
                // OR Broadcast to Role
                ->orWhere(function ($q) use ($currentUserRole, $currentUserDivision) {
                    $q->where('user_level', $currentUserRole)
                      ->whereNull('send_to') // Assuming NULL means "All in this level"
                      ->where(function ($subQ) use ($currentUserDivision) {
                            // 1. Reminder has a specific target division
                            $subQ->where(function ($divQ) use ($currentUserDivision) {
                                $divQ->whereNotNull('division')
                                     ->where('division', $currentUserDivision);
                            })
                            // 2. OR Reminder has NO division (Legacy/Implicit behavior)
                            ->orWhere(function ($noDivQ) use ($currentUserDivision) {
                                $noDivQ->whereNull('division')
                                       ->where(function ($senderQuery) use ($currentUserDivision) {
                                          // Allow if sender is Admin(1) or Finance(7)
                                          $senderQuery->whereHas('user', function ($u) {
                                              $u->whereIn('user_role', [1, 7]);
                                          })
                                          // OR if sender is in same division
                                          ->orWhereHas('user.userDetails', function ($ud) use ($currentUserDivision) {
                                              $ud->where('division', $currentUserDivision);
                                          });
                                       });
                            });
                      });
                });
            })
            ->orderBy('id', 'desc')
            ->get();
        
        $notifications = Notifications::where('to_user', $currentUserId)
            ->orderBy('id', 'desc')
            ->get();   
        return view('adm::notifications_and_reminders.notifications_and_reminders', [
            'reminders' => $reminders,
            'notifications' => $notifications
        ]);
    }

    public function create_reminder(Request $request)
    {
        $currentUserId = Auth::id();
        $currentUserRole = User::where('id', $currentUserId)->value('user_role');
        $name = UserDetails::where('user_id', $currentUserId)->value('name');

        if ($request->isMethod('get')) {
            // Only roles except current user's level
            $allowedRoles = RolePermissions::whereJsonContains('permissions', 'notifications')
                ->pluck('user_role')
                ->toArray();

            $roles = collect($allowedRoles)
                ->filter(fn($role) => $role != $currentUserRole)
                ->sort()
                ->values();
            return view('adm::notifications_and_reminders.create_reminder', compact('roles', 'name'));
        }

       

        if ($request->isMethod('post')) {
            $request->validate([
                'send_from'      => 'required',
                'reminder_title' => 'required',
                'user_level'     => 'nullable|integer|required_if:reminder_type,Other',
                'reminder_type'  => 'required',
                'reminder_date'  => 'required',
                'reason'         => 'required',
                'send_to'        => 'nullable|array',
                'send_to.*'      => 'integer',
            ]); 

            if ($request->reminder_type === 'Self') {
                $reminder = new Reminders();
                $reminder->sent_user_id   = $currentUserId;
                $reminder->send_from      = $request->send_from;
                $reminder->reminder_title = $request->reminder_title;
                $reminder->user_level     = $currentUserRole;
                $reminder->send_to        = [(string) $currentUserId];
                $reminder->reminder_type  = 'Self';
                $reminder->reminder_date  = $request->reminder_date;
                $reminder->reason         = $request->reason;
                $reminder->is_direct      = 1;
                $reminder->save();

                ActivitLogService::log('reminder', "Self-reminder created: {$request->reminder_title}");

            } else {
                $reminder = new Reminders();
                $reminder->sent_user_id   = $currentUserId;
                $reminder->send_from      = $request->send_from;
                $reminder->reminder_title = $request->reminder_title;
                $reminder->user_level     = $request->user_level;
                $reminder->send_to        = $request->send_to;
                $reminder->reminder_type  = $request->reminder_type;
                $reminder->reminder_date  = $request->reminder_date;
                $reminder->reason         = $request->reason;
                $reminder->is_direct      = 0;
                $reminder->save();
                
                ActivitLogService::log('reminder', "Reminder campaign created: {$request->reminder_title}");
            }

            // Check if reminder date is today
            if (\Carbon\Carbon::parse($request->reminder_date)->isToday()) {
                $smsRecipients = collect();

                // Case 1: Specific users (or Self)
                if (!empty($reminder->send_to)) {
                    $smsRecipients = User::with('userDetails')->whereIn('id', $reminder->send_to)->get();
                }
                // Case 2 & 3: Role-based (Select All)
                elseif ($reminder->user_level) {
                    $targetRole = $reminder->user_level;
                    $query = User::with('userDetails')->where('user_role', $targetRole);

                    // Exclude current user to match UI behavior
                    $query->where('id', '!=', $currentUserId);

                    if (in_array($targetRole, [1, 7])) {
                        // Case 2: Admin/Finance - No division filter (Load all)
                    } else {
                        // Case 3: Other roles - Apply Division filter
                        $currentUserData = User::with('userDetails')->find($currentUserId);
                        $currentUserDivision = $currentUserData->userDetails->division ?? null;

                        if ($currentUserDivision) {
                            $query->whereHas('userDetails', function ($q) use ($currentUserDivision) {
                                $q->where('division', $currentUserDivision);
                            });
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
    }

    // method to fetch users by level
    public function getUsersByLevel($level)
    {
        $currentUser = Auth::user();
        $currentUserRole = $currentUser->user_role;
        $currentUserDivision = $currentUser->userDetails->division ?? null;

        // Check if selected role has notifications permission
        $hasPermission = RolePermissions::where('user_role', $level)
            ->whereJsonContains('permissions', 'notifications')
            ->exists();

        if (! $hasPermission) {
            return response()->json([]); // no permission → return empty
        }

        $usersQuery = User::with('userDetails')
            ->where('user_role', $level)
            ->where('user_role', '!=', $currentUserRole);

        // Filter by division if user has one AND the target level is NOT Admin (1) or Finance (7)
        // Admin and Finance users are global and should be visible to everyone regardless of division
        if ($currentUserDivision && !in_array($level, [1, 7])) {
            $usersQuery->whereHas('userDetails', function ($query) use ($currentUserDivision) {
                $query->where('division', $currentUserDivision);
            });
        }

        $users = $usersQuery->get();

        return response()->json($users);
    }

    public function reminder_details($id)
    {
  
        // Fetch the reminder only if it belongs to the logged user
        $reminder = Reminders::where('id', $id)
            ->firstOrFail();

        // Mark as read if not already
        if (!$reminder->is_read) {
            $reminder->is_read = 1;
            $reminder->save();
        }

        // Get sender and receiver names
        $senderName = UserDetails::where('user_id', $reminder->sent_user_id)->value('name') ?? 'Unknown';
        $receiverName = UserDetails::where('user_id', $reminder->send_to)->value('name') ?? 'Unknown';

        return view('adm::notifications_and_reminders.payment_reminder_details', compact('reminder', 'senderName', 'receiverName'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin::create');
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
}
