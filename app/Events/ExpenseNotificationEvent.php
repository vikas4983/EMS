<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExpenseNotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;
    public $userId;

    public function __construct($notification, $userId)
    {
        $this->notification = $notification;
        $this->userId = $userId;
    }

    public function broadcastOn()
    {
        return new Channel('user.' . $this->userId);
    }

    public function broadcastAs()
    {
        return 'expense.notification';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->notification['notification_id'] ?? null,
            'message' => $this->notification['message'],
            'type' => $this->notification['type'],
            'expense_id' => $this->notification['expense_id'] ?? null,
            'time' => now()->format('h:i A'),
            'created_at' => now()->toDateTimeString(),
            'read' => false
        ];
    }
}
