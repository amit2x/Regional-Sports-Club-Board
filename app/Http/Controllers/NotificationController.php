<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        // Support both web guard (admin/users) and employee guard
        $this->middleware(function ($request, $next) {
            if (!Auth::guard('web')->check() && !Auth::guard('employee')->check()) {
                return redirect()->route('login');
            }
            return $next($request);
        });
    }

    /**
     * Get the authenticated user regardless of guard
     */
    private function getAuthUser()
    {
        if (Auth::guard('employee')->check()) {
            return Auth::guard('employee')->user();
        }
        return Auth::user();
    }

    /**
     * Get the guard name for the authenticated user
     */
    private function getGuardName(): string
    {
        if (Auth::guard('employee')->check()) {
            return 'employee';
        }
        return 'web';
    }

    /**
     * Check if user is an employee (not admin)
     */
    private function isEmployee(): bool
    {
        return Auth::guard('employee')->check();
    }

    /**
     * Check if user has admin roles
     */
    private function isAdmin(): bool
    {
        if ($this->isEmployee()) {
            return false;
        }

        $user = Auth::user();
        return $user && ($user->isAdmin() || $user->hasRole(['admin', 'super-admin']));
    }

    /**
     * Get the appropriate view path based on user role
     */
    private function getViewPath(string $view): string
    {
        if ($this->isEmployee()) {
            return 'employee.' . $view;
        }

        if ($this->isAdmin()) {
            return 'admin.' . $view;
        }

        return $view;
    }

    /**
     * Display all notifications
     */
    public function index()
    {
        $user = $this->getAuthUser();

        if (!$user) {
            return redirect()->route('login');
        }

        $notifications = $user->notifications()
            ->latest()
            ->paginate(20);

        $unreadCount = $user->unreadNotifications()->count();

        // Return view based on user role
        $view = $this->getViewPath('notifications.index');

        return view($view, compact('notifications', 'unreadCount'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = DatabaseNotification::findOrFail($id);
        $user = $this->getAuthUser();

        // Check ownership
        if ($notification->notifiable_id !== $user->id ||
            $notification->notifiable_type !== get_class($user)) {
            abort(403, 'Unauthorized access to notification.');
        }

        $notification->markAsRead();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read.'
            ]);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = $this->getAuthUser();

        $user->unreadNotifications->markAsRead();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read.'
            ]);
        }

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete notification
     */
    public function delete($id)
    {
        $notification = DatabaseNotification::findOrFail($id);
        $user = $this->getAuthUser();

        if ($notification->notifiable_id !== $user->id ||
            $notification->notifiable_type !== get_class($user)) {
            abort(403);
        }

        $notification->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted.'
            ]);
        }

        return back()->with('success', 'Notification deleted.');
    }

    /**
     * Get unread notifications count (AJAX)
     */
    public function unreadCount()
    {
        $user = $this->getAuthUser();

        $count = $user->unreadNotifications()->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get latest notifications (AJAX)
     */
    public function latest()
    {
        $user = $this->getAuthUser();

        $notifications = $user->notifications()
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'message' => $notification->data['message'] ?? 'New notification',
                    'title' => $notification->data['title'] ?? 'Notification',
                    'type' => $notification->data['type'] ?? 'general',
                    'incident_id' => $notification->data['incident_id'] ?? null,
                    'icon' => $this->getNotificationIcon($notification->data['type'] ?? 'general'),
                    'color' => $this->getNotificationColor($notification->data['type'] ?? 'general'),
                    'time' => $notification->created_at->diffForHumans(),
                    'read' => !is_null($notification->read_at),
                    'url' => $notification->data['url'] ?? '#',
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
        $user = $this->getAuthUser();

        if ($notification->notifiable_id !== $user->id ||
            $notification->notifiable_type !== get_class($user)) {
            abort(403);
        }

        $notification->markAsRead();

        $data = $notification->data;
        $redirectUrl = $this->getDefaultRoute();

        // Determine redirect based on notification type and user role
        switch ($data['type'] ?? '') {
            case 'new_incident':
            case 'incident_assigned':
            case 'incident_escalated':
            case 'incident_resolved':
            case 'incident_closed':
            case 'incident_reopened':
            case 'new_comment':
            case 'mentioned':
                if (isset($data['incident_id'])) {
                    $redirectUrl = route('incidents.show', $data['incident_id']);
                }
                break;

            case 'event_registration':
                if (isset($data['registration_id'])) {
                    $redirectUrl = $this->isEmployee()
                        ? route('employee.registrations.show', $data['registration_id'])
                        : route('admin.registrations.show', $data['registration_id']);
                }
                break;

            case 'announcement':
                if (isset($data['announcement_id'])) {
                    $redirectUrl = route('announcements.details', $data['announcement_id']);
                }
                break;

            case 'event_reminder':
                if (isset($data['event_code'])) {
                    $redirectUrl = route('events.details', $data['event_code']);
                }
                break;

            case 'document_verified':
            case 'document_rejected':
                if (isset($data['registration_id'])) {
                    $redirectUrl = $this->isEmployee()
                        ? route('employee.registrations.show', $data['registration_id'])
                        : route('admin.registrations.show', $data['registration_id']);
                }
                break;

            case 'status_update':
                if (isset($data['url'])) {
                    $redirectUrl = $data['url'];
                }
                break;

            default:
                $redirectUrl = $data['url'] ?? $this->getDefaultRoute();
        }

        return redirect($redirectUrl);
    }

    /**
     * Get default route based on user role
     */
    private function getDefaultRoute(): string
    {
        if ($this->isEmployee()) {
            return route('employee.dashboard');
        }

        if ($this->isAdmin()) {
            return route('admin.dashboard');
        }

        return route('dashboard');
    }

    /**
     * Get notification icon based on type
     */
    private function getNotificationIcon(string $type): string
    {
        return match ($type) {
            'new_incident' => 'fa-exclamation-triangle',
            'incident_assigned' => 'fa-user-plus',
            'incident_escalated' => 'fa-arrow-up',
            'incident_resolved' => 'fa-check-circle',
            'incident_closed' => 'fa-lock',
            'incident_reopened' => 'fa-redo',
            'new_comment' => 'fa-comment',
            'mentioned' => 'fa-at',
            'event_registration' => 'fa-calendar-check',
            'event_reminder' => 'fa-calendar-alt',
            'announcement' => 'fa-bullhorn',
            'document_verified' => 'fa-check-double',
            'document_rejected' => 'fa-times-circle',
            'status_update' => 'fa-info-circle',
            default => 'fa-bell',
        };
    }

    /**
     * Get notification color based on type
     */
    private function getNotificationColor(string $type): string
    {
        return match ($type) {
            'new_incident', 'incident_escalated' => '#EF4444',
            'incident_assigned' => '#3B82F6',
            'incident_resolved', 'document_verified' => '#10B981',
            'incident_closed' => '#6B7280',
            'incident_reopened', 'event_reminder' => '#F59E0B',
            'new_comment', 'mentioned' => '#8B5CF6',
            'event_registration' => '#EC4899',
            'announcement' => '#14B8A6',
            'document_rejected' => '#DC2626',
            'status_update' => '#6366F1',
            default => '#6B7280',
        };
    }
}
