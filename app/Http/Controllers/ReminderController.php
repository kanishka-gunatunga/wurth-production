<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reminders;
use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{
    // Show the create form
    public function create()
    {
        $currentUserId = Auth::id();
        $currentUserRole = User::where('id', $currentUserId)->value('user_role');

        // 🎯 Get all users except same-level users
        $users = User::with('userDetails')
            ->where('user_role', '!=', $currentUserRole)
            ->orWhere('id', $currentUserId) // allow the current admin for name display
            ->get();

        // Only roles EXCEPT current user's level
        $roles = $users->pluck('user_role')
            ->unique()
            ->filter(fn($role) => $role != $currentUserRole)  // 🚫 remove same role
            ->sort()
            ->values();

        $name = UserDetails::where('user_id', Auth::id())->value('name');

        return view('reminders.create_reminder', compact('users', 'name', 'roles'));
    }

    public function getUsersByLevel($level)
    {
        $currentUserRole = Auth::user()->user_role;

        $users = User::with('userDetails')
            ->where('user_role', $level)
            ->where('user_role', '!=', $currentUserRole) // 🚫 block same level
            ->get();

        return response()->json($users);
    }

    // Store reminder
    public function store(Request $request)
    {
        $request->validate([
            'send_from'      => 'required|string|max:255',
            'reminder_title' => 'required|string|max:255',
            'user_level'     => 'required|integer',
            'reminder_date'  => 'required|date',
            'reason'         => 'required|string',
            'send_to'        => 'required|array',
            'send_to.*'      => 'integer',
        ]);

        $selectedLevel = (int)$request->input('user_level');
        $selectedUserIds = array_map('intval', $request->input('send_to'));

        $currentUserId = Auth::id();
        $currentUserRole = User::where('id', $currentUserId)->value('user_role');

        $usersQuery = User::query();

        /**
         * SPECIAL RULE 1:
         * Level 1 <-> Level 7 → only the target level users
         */
        if (
            ($currentUserRole == 1 && $selectedLevel == 7) ||
            ($currentUserRole == 7 && $selectedLevel == 1)
        ) {
            $usersQuery->where('user_role', $selectedLevel);
        }
        /**
         * SPECIAL RULE 2:
         * Level 1 or 7 → Level 8 → only Level 2 and Level 8 users
         */
        elseif (
            ($currentUserRole == 1 || $currentUserRole == 7) &&
            $selectedLevel == 8
        ) {
            $usersQuery->whereIn('user_role', [2, 8]);
        }
        /**
         * DEFAULT RULE:
         * Levels between current user and selected level
         */
        else {
            $minLevel = min($currentUserRole, $selectedLevel);
            $maxLevel = max($currentUserRole, $selectedLevel);

            $usersQuery->whereBetween('user_role', [$minLevel, $maxLevel])
                ->where('user_role', '!=', $currentUserRole);
        }

        $users = $usersQuery->get();

        foreach ($users as $user) {
            $reminder = new Reminders();
            $reminder->sent_user_id   = $currentUserId;
            $reminder->send_from      = $request->input('send_from');
            $reminder->reminder_title = $request->input('reminder_title');
            $reminder->user_level     = $selectedLevel;
            $reminder->send_to        = $user->id;
            $reminder->reminder_date  = $request->input('reminder_date');
            $reminder->reason         = $request->input('reason');

            // Mark selected dropdown users as direct
            $reminder->is_direct = in_array($user->id, $selectedUserIds) ? 1 : 0;

            $reminder->save();
        }

        return redirect()->back()->with('toast', 'Reminder sent successfully!');
    }

    public function index(Request $request)
    {
        $userId = Auth::id();

        $query = Reminders::query()
            ->where('send_to', $userId);

        // ------------------------------
        // SEARCH BY TITLE
        // ------------------------------
        if ($request->filled('q')) {
            $query->where('reminder_title', 'like', "%{$request->q}%");
        }

        // ------------------------------
        // FILTER: FROM (send_from column)
        // ------------------------------
        if ($request->filled('from_users')) {
            $query->whereIn('sent_user_id', $request->from_users);
        }

        // ------------------------------
        // FILTER: TO (send_to)
        // ------------------------------
        if ($request->filled('to_users')) {
            $query->whereIn('send_to', $request->to_users);
        }

        // ------------------------------
        // FILTER: DATE RANGE
        // ------------------------------
        if ($request->filled('date_range')) {
            $range = $request->date_range;

            if (str_contains($range, 'to')) {
                [$start, $end] = array_map('trim', explode('to', $range));
            } else {
                $start = $end = $range;
            }

            $query->whereBetween('reminder_date', [
                date('Y-m-d 00:00:00', strtotime($start)),
                date('Y-m-d 23:59:59', strtotime($end)),
            ]);
        }

        $reminders = $query->orderByDesc("reminder_date")
            ->paginate(10)
            ->withQueryString();

        // Unique user IDs for FROM dropdown (senders)
        $fromUserIds = Reminders::distinct()
            ->pluck('sent_user_id')
            ->toArray();

        $fromUsers = User::whereIn('id', $fromUserIds)
            ->with('userDetails')
            ->get();

        // Unique user IDs for TO dropdown (receivers)
        $toUserIds = Reminders::distinct()
            ->pluck('send_to')
            ->toArray();

        $toUsers = User::whereIn('id', $toUserIds)
            ->with('userDetails')
            ->get();

        return view('reminders.all_reminders', [
            'reminders' => $reminders,
            'filters'   => $request->all(),
            'fromUsers' => $fromUsers,
            'toUsers'   => $toUsers
        ]);
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
        $receiver = User::with('userDetails')->find($reminder->send_to);

        return view('reminders.payment_reminder_details', compact('reminder', 'sender', 'receiver'));
    }

    public function sentReminders()
    {
        $currentUserName = UserDetails::where('user_id', Auth::id())->value('name');

        $reminders = Reminders::where('send_from', $currentUserName)
            ->where('is_direct', 1)
            ->with(['recipient' => function ($query) {
                $query->with('userDetails');
            }])
            ->orderByDesc('reminder_date')
            ->get();

        $reminders = Reminders::where('send_from', $currentUserName)
            ->where('is_direct', 1)
            ->with(['recipient.userDetails'])
            ->orderByDesc('reminder_date')
            ->paginate(10);

        return view('reminders.reminders', compact('reminders'));
    }
}
