<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Expense;
use App\Events\ExpenseNotificationEvent;
use Illuminate\Support\Facades\Mail;
use App\Mail\ExpenseNotificationMail;
use Illuminate\Support\Facades\Log;


class NotificationService
{
    public function create($userId, $message, $type = 'info', $expenseId = null)
    {
        try {

            $notification = Notification::create([
                'user_id' => $userId,
                'message' => $message,
                'type' => $type,
                'expense_id' => $expenseId
            ]);

            // Sirf Broadcast - Email job mein handle ho jayega
            try {
                broadcast(new ExpenseNotificationEvent([
                    'message' => $message,
                    'type' => $type,
                    'expense_id' => $expenseId,
                    'notification_id' => $notification->id,
                    'time' => now()->format('h:i A'),
                    'created_at' => now()->toDateTimeString()
                ], $userId));
            } catch (\Exception $e) {
                Log::error('Broadcast failed: ' . $e->getMessage());
            }

            return $notification;
        } catch (\Exception $e) {
            Log::error('Notification creation failed: ' . $e->getMessage());
            throw $e;
        }
    }

   

    public function getUnread($userId)
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->latest()
            ->get();
    }

    public function getAll($userId)
    {
        return Notification::where('user_id', $userId)
            ->latest()
            ->limit(50)
            ->get();
    }

    public function markAsRead($notificationId, $userId)
    {
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->first();

        if ($notification) {
            $notification->markAsRead();
            return true;
        }
        return false;
    }

    public function markAllAsRead($userId)
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}
