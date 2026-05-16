<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee');
    }

    /**
     * Display all notifications
     */
    public function index()
    {
        $user = auth()->guard('employee')->user();

        $notifications = $user->notifications()
            ->latest()
            ->paginate(20);

        $unreadCount = $user->unreadNotifications()->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = DatabaseNotification::findOrFail($id);

        // Check ownership
        if ($notification->notifiable_id !== auth()->guard('employee')->id()) {
            abort(403);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read.'
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        auth()->guard('employee')->user()
            ->unreadNotifications
            ->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read.'
        ]);
    }

    /**
     * Delete notification
     */
    public function delete($id)
    {
        $notification = DatabaseNotification::findOrFail($id);

        if ($notification->notifiable_id !== auth()->guard('employee')->id()) {
            abort(403);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted.'
        ]);
    }

    /**
     * Get unread notifications count (AJAX)
     */
    public function unreadCount()
    {
        $count = auth()->guard('employee')->user()
            ->unreadNotifications()
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get latest notifications (AJAX)
     */
    public function latest()
    {
        $notifications = auth()->guard('employee')->user()
            ->notifications()
            ->latest()
            ->take(5)
            ->get()
            ->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'message' => $notification->data['message'] ?? 'New notification',
                    'type' => $notification->data['type'] ?? 'general',
                    'time' => $notification->created_at->diffForHumans(),
                    'read' => !is_null($notification->read_at),
                ];
            });

        return response()->json($notifications);
    }

    /**
     * Handle notification click (redirect to relevant page)
     */
    public function handleClick($id)
    {
        $notification = DatabaseNotification::findOrFail($id);

        if ($notification->notifiable_id !== auth()->guard('employee')->id()) {
            abort(403);
        }

        $notification->markAsRead();

        $data = $notification->data;
        $redirectUrl = '/';

        // Determine redirect based on notification type
        switch ($data['type'] ?? '') {
            case 'event_registration':
                $redirectUrl = route('employee.registrations.show', $data['registration_id'] ?? 0);
                break;
            case 'announcement':
                $redirectUrl = route('announcements.details', $data['announcement_id'] ?? 0);
                break;
            case 'event_reminder':
                $redirectUrl = route('events.details', $data['event_code'] ?? '');
                break;
            case 'document_verified':
                $redirectUrl = route('employee.registrations.show', $data['registration_id'] ?? 0);
                break;
            default:
                $redirectUrl = route('employee.dashboard');
        }

        return redirect($redirectUrl);
    }
}
