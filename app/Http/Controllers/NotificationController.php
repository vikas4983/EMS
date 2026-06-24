<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $userId = auth()->id();

        return response()->json([
            'notifications' => $this->notificationService->getAll($userId),
            'unread_count' => $this->notificationService->getUnread($userId)->count()
        ]);
    }

    public function markAsRead($id)
    {
        $this->notificationService->markAsRead($id, auth()->id());
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        $this->notificationService->markAllAsRead(auth()->id());
        return response()->json(['success' => true]);
    }
}
