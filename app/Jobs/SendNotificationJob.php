<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Expense;
use App\Mail\ExpenseNotificationMail;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $message;
    protected $type;
    protected $expenseId;

    public function __construct($userId, $message, $type = 'info', $expenseId = null)
    {
        $this->userId = $userId;
        $this->message = $message;
        $this->type = $type;
        $this->expenseId = $expenseId;
    }

    public function handle(NotificationService $notificationService)
    {
        // 1. Notification Service - Database + Broadcast
        $notificationService->create(
            $this->userId,
            $this->message,
            $this->type,
            $this->expenseId
        );

        // 2. Email bhi yahi se bhejo
        $this->sendEmail();
    }

    protected function sendEmail()
    {
        try {
            $user = User::find($this->userId);
            if ($user && $user->email) {
                $expense = null;
                if ($this->expenseId) {
                    $expense = Expense::with(['user', 'category', 'receipts'])->find($this->expenseId);
                }

                $mail = new ExpenseNotificationMail(
                    $this->message,
                    $this->type,
                    $this->expenseId,
                    $expense,
                    $user
                );

                Mail::to($user->email)->send($mail);
                Log::info('Email sent to: ' . $user->email);
            }
        } catch (\Exception $e) {
            Log::error('Email failed: ' . $e->getMessage());
        }
    }
}
