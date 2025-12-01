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
        // Get all users so admin can pick who to send to
        $currentUserId = Auth::id();
        $currentUserRole = User::where('id', $currentUserId)->value('user_role');

        $users = User::with('userDetails')
            ->where(function ($query) use ($currentUserId, $currentUserRole) {
                $query->where('id', $currentUserId) // include yourself
                    ->orWhere('user_role', '!=', $currentUserRole); // exclude same level users
            })
            ->get();

        // Get current admin name (if you store name in user_details)
        $name = UserDetails::where('user_id', Auth::id())->value('name');

        return view('reminders.create_reminder', compact('users', 'name'));
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
            'send_to'        => 'required|integer', // validate the user
        ]);

        $selectedLevel = (int)$request->input('user_level');
        $selectedUserId = (int)$request->input('send_to');

        // Get all users within the selected level range
        $currentUserId = Auth::id();
        $currentUserRole = User::where('id', $currentUserId)->value('user_role');

        // Get users to send reminder
        $users = User::where('user_role', '<=', $selectedLevel)
            ->where(function ($query) use ($currentUserId, $selectedUserId, $currentUserRole, $selectedLevel) {
                if ($selectedLevel == $currentUserRole) {
                    // If selected level is same as current user level, only include:
                    // 1) the currently logged-in user
                    // 2) users of other levels
                    $query->where('id', $currentUserId)
                        ->orWhere('user_role', '!=', $currentUserRole);
                } else {
                    // Otherwise, include all users up to the selected level
                    $query->where('user_role', '<=', $selectedLevel);
                }
            })
            ->get();

        foreach ($users as $user) {
            $reminder = new Reminders();
            $reminder->sent_user_id   = Auth::id();
            $reminder->send_from      = $request->input('send_from');
            $reminder->reminder_title = $request->input('reminder_title');
            $reminder->user_level     = $selectedLevel;
            $reminder->send_to        = $user->id;
            $reminder->reminder_date  = $request->input('reminder_date');
            $reminder->reason         = $request->input('reason');

            // ✅ Mark the one that matches the selected user as direct
            $reminder->is_direct = ($user->id === $selectedUserId) ? 1 : 0;

            $reminder->save();
        }

        return redirect()->back()->with('toast', 'Reminder sent to all users up to level ' . $selectedLevel . '!');
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
