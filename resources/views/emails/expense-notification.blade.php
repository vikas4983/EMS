<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Expense Notification</title>
</head>

<body>
    <div
        style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <h2 style="color: #2c3e50; border-bottom: 2px solid #4A90D9; padding-bottom: 10px;">
            {{ $appName }}
        </h2>

        <p><strong>Notification Type:</strong>
            @if ($type == 'success')
                <span
                    style="display: inline-block; padding: 3px 12px; background: #27ae60; color: white; border-radius: 4px; font-weight: bold;">APPROVED</span>
            @elseif($type == 'error')
                <span
                    style="display: inline-block; padding: 3px 12px; background: #e74c3c; color: white; border-radius: 4px; font-weight: bold;">REJECTED</span>
            @elseif($type == 'warning')
                <span
                    style="display: inline-block; padding: 3px 12px; background: #f39c12; color: white; border-radius: 4px; font-weight: bold;">ACTION
                    REQUIRED</span>
            @else
                <span
                    style="display: inline-block; padding: 3px 12px; background: #3498db; color: white; border-radius: 4px; font-weight: bold;">INFORMATION</span>
            @endif
        </p>

        <div
            style="background: #f8f9fa; padding: 15px 20px; border-left: 4px solid #3498db; margin: 15px 0; border-radius: 3px;">
            <p style="margin: 0; font-size: 15px; color: #333; line-height: 1.6;">
                {{ $notificationMessage }}
            </p>
        </div>

        @if ($expenseId)
            <div
                style="background: #fafafa; padding: 15px 20px; margin: 15px 0; border: 1px solid #eee; border-radius: 4px;">
                <h4 style="margin: 0 0 12px 0; color: #2c3e50; font-size: 14px;">Expense Details</h4>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 6px 0; font-weight: bold; color: #555; width: 30%;">Expense ID</td>
                        <td style="padding: 6px 0; color: #333;">#{{ $expenseId }}</td>
                    </tr>
                    @if ($expense)
                        <tr>
                            <td style="padding: 6px 0; font-weight: bold; color: #555;">Title</td>
                            <td style="padding: 6px 0; color: #333;">{{ $expense->title ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 6px 0; font-weight: bold; color: #555;">Amount</td>
                            <td style="padding: 6px 0; color: #333;">Rs. {{ number_format($expense->amount ?? 0, 2) }}
                            </td>
                        </tr>
                        @if (isset($expense->category))
                            <tr>
                                <td style="padding: 6px 0; font-weight: bold; color: #555;">Category</td>
                                <td style="padding: 6px 0; color: #333;">{{ $expense->category->name ?? 'N/A' }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td style="padding: 6px 0; font-weight: bold; color: #555;">Status</td>
                            <td style="padding: 6px 0;">
                                @if (isset($expense->status) && $expense->status == 'approved')
                                    <span style="color: #27ae60; font-weight: bold;">Approved</span>
                                @elseif(isset($expense->status) && $expense->status == 'rejected')
                                    <span style="color: #e74c3c; font-weight: bold;">Rejected</span>
                                @else
                                    <span style="color: #f39c12; font-weight: bold;">Pending</span>
                                @endif
                            </td>
                        </tr>
                        @if (isset($expense->user) && $expense->user)
                            <tr>
                                <td style="padding: 6px 0; font-weight: bold; color: #555;">Submitted By</td>
                                <td style="padding: 6px 0; color: #333;">{{ $expense->user->name ?? 'N/A' }}</td>
                            </tr>
                        @endif
                        @if (isset($expense->expense_date))
                            <tr>
                                <td style="padding: 6px 0; font-weight: bold; color: #555;">Expense Date</td>
                                <td style="padding: 6px 0; color: #333;">
                                    {{ date('d M Y', strtotime($expense->expense_date)) }}</td>
                            </tr>
                        @endif
                        @if (isset($expense->manager_comment) && $expense->manager_comment)
                            <tr>
                                <td style="padding: 6px 0; font-weight: bold; color: #555;">Manager Comment</td>
                                <td style="padding: 6px 0; color: #333;">{{ $expense->manager_comment }}</td>
                            </tr>
                        @endif
                    @endif
                </table>
            </div>
        @endif

        @if ($expense && $expense->receipts && count($expense->receipts) > 0)
            <div
                style="background: #f0f8ff; padding: 15px 20px; margin: 15px 0; border: 1px solid #b8d4e3; border-radius: 4px;">
                <h4 style="margin: 0 0 12px 0; color: #2c3e50; font-size: 14px;">📎 Receipts Attached</h4>
                <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: #333;">
                    @foreach ($expense->receipts as $receipt)
                        <li style="margin-bottom: 5px;">
                            <span style="font-weight: 500;">{{ $receipt->file_name }}</span>
                            <span style="color: #888; font-size: 11px;">
                                ({{ number_format(Storage::disk('public')->size($receipt->file_path) / 1024, 2) }} KB)
                            </span>
                        </li>
                    @endforeach
                </ul>
                <p style="margin: 8px 0 0 0; font-size: 12px; color: #666;">
                    <em>Receipt files are attached to this email.</em>
                </p>
            </div>
        @endif

        <div
            style="background: #f8f9fa; padding: 12px 20px; margin: 15px 0; border: 1px solid #eee; border-radius: 4px;">
            <p style="margin: 0 0 8px 0; font-weight: bold; color: #555; font-size: 13px;">Quick Actions</p>
            <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: #666;">
                <li>Login to your account to view all expenses</li>
                <li>Check your dashboard for real-time updates</li>
                @if ($expenseId)
                    <li>View expense details: {{ url('/expenses/' . $expenseId) }}</li>
                @endif
            </ul>
        </div>

        <div
            style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #ddd; font-size: 12px; color: #888; text-align: center;">
            <p style="margin: 4px 0;">This is an automated notification from {{ $appName }}.</p>
            <p style="margin: 4px 0;">Please do not reply to this email.</p>
            <p style="margin: 4px 0; color: #aaa;">&copy; {{ date('Y') }} {{ $appName }}. All rights
                reserved.</p>
        </div>
    </div>
</body>

</html>
