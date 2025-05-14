<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $userRole = Auth::user()->role;
        $notifications = Notification::whereJsonContains('notified_roles', $userRole)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notifications);
    }

    public function getUnreadCount()
    {
        $userRole = Auth::user()->role;
        $count = Notification::whereJsonContains('notified_roles', $userRole)
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        $userRole = Auth::user()->role;
        Notification::whereJsonContains('notified_roles', $userRole)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
} 