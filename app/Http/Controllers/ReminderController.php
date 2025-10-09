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
            'user_level'  => 'required',
            'send_to'        => 'required',           
            'reminder_date'  => 'required|date',
            'reason'         => 'required|string',
        ]);

        $reminder = new Reminders();
        $reminder->sent_user_id    = Auth::id();
        $reminder->send_from       = $request->input('send_from');
        $reminder->reminder_title  = $request->input('reminder_title');
        $reminder->user_level      = $request->input('user_level');
        $reminder->send_to         = $request->input('send_to');
        $reminder->reminder_date   = $request->input('reminder_date');
        $reminder->reason          = $request->input('reason');
        $reminder->save();

        return redirect()->back()->with('toast', 'Reminder added successfully!');
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
}
