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


use File;
use Mail;
use Image;
use PDF;

class NotificationsRemindersController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function notifications_and_reminders()
    {
        $currentUserId = Auth::id();

        // Get all reminders sent to the logged-in user
        $reminders = Reminders::where('send_to', $currentUserId)
            ->orderBy('reminder_date', 'desc')
            ->get();

        return view('adm::notifications_and_reminders.notifications_and_reminders', [
            'reminders' => $reminders
        ]);
    }

    public function create_reminder(Request $request)
    {
        $currentUserId = Auth::id();
        $currentUserRole = User::where('id', $currentUserId)->value('user_role');
        $name = UserDetails::where('user_id', $currentUserId)->value('name');

        if ($request->isMethod('get')) {
            // Only roles except current user's level
            $roles = User::pluck('user_role')->unique()->filter(fn($role) => $role != $currentUserRole)->sort()->values();
            return view('adm::notifications_and_reminders.create_reminder', compact('roles', 'name'));
        }

        if ($request->reminder_type === 'Self') {
            $reminder = new Reminders();
            $reminder->sent_user_id   = $currentUserId;
            $reminder->send_from      = $request->send_from;
            $reminder->reminder_title = $request->reminder_title;
            $reminder->user_level     = $currentUserRole;
            $reminder->send_to        = $currentUserId;
            $reminder->reminder_type  = 'Self';
            $reminder->reminder_date  = $request->reminder_date;
            $reminder->reason         = $request->reason;
            $reminder->is_direct      = 1;
            $reminder->save();

            return back()->with('success', 'Reminder Successfully Added');
        }

        if ($request->isMethod('post')) {
            $request->validate([
                'send_from'      => 'required',
                'reminder_title' => 'required',
                'user_level'     => 'required|integer',
                'reminder_type'  => 'required',
                'reminder_date'  => 'required',
                'reason'         => 'required',
                'send_to'        => 'required|array',
                'send_to.*'      => 'integer',
            ]);

            $selectedLevel = (int)$request->user_level;
            $selectedUserIds = array_map('intval', $request->send_to);

            $usersQuery = User::query();

            // Special Rules
            if (($currentUserRole == 1 && $selectedLevel == 7) || ($currentUserRole == 7 && $selectedLevel == 1)) {
                $usersQuery->where('user_role', $selectedLevel);
            } elseif (($currentUserRole == 1 || $currentUserRole == 7) && $selectedLevel == 8) {
                $usersQuery->whereIn('user_role', [2, 8]);
            } else {
                $minLevel = min($currentUserRole, $selectedLevel);
                $maxLevel = max($currentUserRole, $selectedLevel);
                $usersQuery->whereBetween('user_role', [$minLevel, $maxLevel])
                    ->where('user_role', '!=', $currentUserRole);
            }

            $users = $usersQuery->get();

            foreach ($users as $user) {
                $reminder = new Reminders();
                $reminder->sent_user_id   = $currentUserId;
                $reminder->send_from      = $request->send_from;
                $reminder->reminder_title = $request->reminder_title;
                $reminder->user_level     = $selectedLevel;
                $reminder->send_to        = $user->id;
                $reminder->reminder_type  = $request->reminder_type;
                $reminder->reminder_date  = $request->reminder_date;
                $reminder->reason         = $request->reason;
                $reminder->is_direct      = in_array($user->id, $selectedUserIds) ? 1 : 0;
                $reminder->save();
            }

            return back()->with('success', 'Reminder Successfully Added');
        }
    }

    // method to fetch users by level
    public function getUsersByLevel($level)
    {
        $currentUserRole = Auth::user()->user_role;
        $users = User::with('userDetails')
            ->where('user_role', $level)
            ->where('user_role', '!=', $currentUserRole)
            ->get();

        return response()->json($users);
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
