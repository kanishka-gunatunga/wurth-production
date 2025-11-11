<?php

namespace App\Services;

use App\Models\ActivtiyLog;
use Illuminate\Support\Facades\Auth;

class ActivitLogService
{
    /**
     * Log an activity.
     *
     * @param string $activity_type - e.g., 'permission', 'user', 'invoice'
     * @param string $changes - description of what was changed
     * @param int|null $user_id - optional, defaults to the currently logged-in user
     * @return void
     */
    public static function log(string $activity_type, string $changes, ?int $user_id = null): void
    {
        try {
            $log = new ActivtiyLog();
            $log->user_id = $user_id ?? (Auth::check() ? Auth::user()->id : null);
            $log->activity_type = $activity_type;
            $log->changes = $changes;
            $log->save();
        } catch (\Exception $e) {
            // Optional: write to Laravel log if saving fails
            \Log::error('Activity log failed: ' . $e->getMessage());
        }
    }
}
