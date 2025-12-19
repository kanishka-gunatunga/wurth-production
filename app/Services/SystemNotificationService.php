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
     * @param int $changes - description of what was changed
     * @param string $activity_type - e.g., 'permission', 'user', 'invoice'
     * @param int|null $user_id - optional, defaults to the currently logged-in user
     * @return void
     */
    public static function log(string $notification_type, int $changed_soruce_id, string $notification, ?int $to_user = null): void
    {
        try {
            $notification_data = new Notifications();
            $notification_data->user_id =Auth::check() ? Auth::user()->id : null;
            $notification_data->notification_type = $notification_type;
            $notification_data->changed_soruce_id = $changed_soruce_id;
            $notification_data->notification = $notification;
            $notification_data->is_seen = 0;
            $notification_data->to_user = $to_user;
            $notification_data->save();
        } catch (\Exception $e) {
            // Optional: write to Laravel log if saving fails
            \Log::error('Notification log failed: ' . $e->getMessage());
        }
    }
}
