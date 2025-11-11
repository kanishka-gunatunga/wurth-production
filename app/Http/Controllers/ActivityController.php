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
use App\Models\ActivtiyLog;

use File;
use Mail;
use Image;
use PDF;

class ActivityController extends Controller
{

public function activity_log(Request $request)
{
    $query = ActivtiyLog::with('userData.userDetails')->orderBy('id', 'DESC');

    // 🔍 Text Search
    if ($search = $request->input('search')) {
        $query->where(function ($q) use ($search) {
            $q->whereHas('userData.userDetails', function ($subQuery) use ($search) {
                $subQuery->where('adm_number', 'like', "%{$search}%")
                         ->orWhere('name', 'like', "%{$search}%");
            })
            ->orWhere('activity_type', 'like', "%{$search}%")
            ->orWhere('changes', 'like', "%{$search}%");
        });
    }

    // 🧩 Role Filter (multi-select)
    $selectedRoles = [];
    if ($request->filled('roles')) {
        $selectedRoles = array_map('intval', explode(',', $request->roles));
        $query->whereHas('userData', function ($q) use ($selectedRoles) {
            $q->whereIn('user_role', $selectedRoles);
        });
    }

    // 📅 Date Periods (multi-select)
    if ($request->filled('date_period')) {
        $datePeriods = (array) $request->input('date_period');
        $query->where(function ($q) use ($datePeriods) {
            foreach ($datePeriods as $period) {
                switch ($period) {
                    case 'today':
                        $q->orWhereDate('created_at', now());
                        break;
                    case 'yesterday':
                        $q->orWhereDate('created_at', now()->subDay());
                        break;
                    case 'this_week':
                        $q->orWhereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                        break;
                    case 'this_month':
                        $q->orWhereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
                        break;
                    case 'this_year':
                        $q->orWhereBetween('created_at', [now()->startOfYear(), now()->endOfYear()]);
                        break;
                }
            }
        });
    }

    // ⏰ Time Periods (multi-select)
    // ⏰ Time Periods (multi-select with your custom time slots)
    if ($request->filled('time_period')) {
        $timeRanges = (array) $request->input('time_period');
        $query->where(function ($q) use ($timeRanges) {
            foreach ($timeRanges as $range) {
                switch ($range) {
                    case '9am-1pm':
                        $q->orWhereBetween(DB::raw('TIME(created_at)'), ['09:00:00', '13:00:00']);
                        break;

                    case '1pm-5pm':
                        $q->orWhereBetween(DB::raw('TIME(created_at)'), ['13:00:00', '17:00:00']);
                        break;

                    case '5pm-9pm':
                        $q->orWhereBetween(DB::raw('TIME(created_at)'), ['17:00:00', '21:00:00']);
                        break;

                    case '9pm-1am':
                        // crosses midnight
                        $q->orWhereTime('created_at', '>=', '21:00:00')
                        ->orWhereTime('created_at', '<', '01:00:00');
                        break;

                    case '1am-5am':
                        $q->orWhereBetween(DB::raw('TIME(created_at)'), ['01:00:00', '05:00:00']);
                        break;

                    case '5am-9am':
                        $q->orWhereBetween(DB::raw('TIME(created_at)'), ['05:00:00', '09:00:00']);
                        break;
                }
            }
        });
    }


    // 📄 Pagination with query persistence
    $logs = $query->paginate(15)->appends($request->query());

    return view('activity.activity_log', compact('logs', 'selectedRoles'));
}



}
