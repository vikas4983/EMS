<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ExpenseNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $notificationMessage;
    public $notificationType;
    public $expenseId;
    public $appName;
    public $expenseData;
    public $recipientUser;

    public function __construct($message, $type = 'info', $expenseId = null, $expense = null, $user = null)
    {
        $this->notificationMessage = $message;
        $this->notificationType = $type;
        $this->expenseId = $expenseId;
        $this->expenseData = $expense;
        $this->recipientUser = $user;
        $this->appName = config('app.name', 'Expense Management System');
    }

    public function build()
    {
        $subject = match ($this->notificationType) {
            'success' => 'Expense Approved',
            'error' => 'Expense Rejected',
            'warning' => 'Expense Action Required',
            default => 'Expense Notification',
        };

        $mail = $this->subject($subject . ' - ' . $this->appName)
            ->view('emails.expense-notification')
            ->with([
                'notificationMessage' => $this->notificationMessage,
                'type' => $this->notificationType,
                'expenseId' => $this->expenseId,
                'expense' => $this->expenseData,
                'user' => $this->recipientUser,
                'appName' => $this->appName,
            ]);

        if ($this->expenseData && $this->expenseData->receipts) {
            foreach ($this->expenseData->receipts as $receipt) {
                $filePath = storage_path('app/public/' . $receipt->file_path);

                if (file_exists($filePath)) {
                    $mail->attach($filePath, [
                        'as' => $receipt->file_name,
                        'mime' => $this->getMimeType($filePath),
                    ]);
                }
            }
        }

        return $mail;
    }

    /**
     * Get MIME type of file
     */
    private function getMimeType($filePath)
    {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        return match (strtolower($extension)) {
            'pdf' => 'application/pdf',
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            default => 'application/octet-stream',
        };
    }
}
