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
        $users = User::with('userDetails')->get();

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
        $users = User::where('user_role', '<=', $selectedLevel)->get();

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
            ->where('send_to', $userId); // show reminders sent to this user

        // optional search by title (GET ?q=..)
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where('reminder_title', 'like', "%{$q}%");
        }

        // order by reminder_date (or created_at) and paginate
        $reminders = $query->orderByDesc('reminder_date')
            ->paginate(10)            // adjust per page
            ->withQueryString();      // keep `q` during pages

        // pass to view
        return view('reminders.all_reminders', compact('reminders'));
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
}
