<?php

namespace App\Services;

use App\Models\Notifications;
use Illuminate\Support\Facades\Auth;

class SystemNotificationService
{
    /**
     * Log an activity.
     *
     * @param string $activity_type - e.g., 'permission', 'user', 'invoice'
     * @param string $changes - description of what was changed
     * @param int|null $user_id - optional, defaults to the currently logged-in user
     * @return void
     */
    public static function log(string $notification_type, int $changed_soruce_id, string $notification, ?int $to_user = null): void
    {
        try {
            $notification = new ActivtiyLog();
            $notification->user_id = $user_id ?? (Auth::check() ? Auth::user()->id : null);
            $notification->notification_type = $notification_type;
            $notification->changed_soruce_id = $changed_soruce_id;
            $notification->notification = $notification;
            $notification->is_seen = 0;
            $notification->save();
        } catch (\Exception $e) {
            // Optional: write to Laravel log if saving fails
            \Log::error('Activity log failed: ' . $e->getMessage());
        }
    }
}
