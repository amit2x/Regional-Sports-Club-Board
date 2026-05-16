<?php

namespace App\Services;

use App\Models\Employee;
use App\Notifications\CustomNotification;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Send notification to specific employees
     */
    public function sendToEmployees($employeeIds, $message, $type = 'general', $data = [])
    {
        $employees = Employee::whereIn('id', (array) $employeeIds)->get();

        Notification::send($employees, new CustomNotification($message, $type, $data));
    }

    /**
     * Send notification to all employees in a region
     */
    public function sendToRegion($regionId, $message, $type = 'general', $data = [])
    {
        $employees = Employee::where('region_id', $regionId)
            ->where('employment_status', 'active')
            ->get();

        Notification::send($employees, new CustomNotification($message, $type, $data));
    }

    /**
     * Send notification to all employees in an airport
     */
    public function sendToAirport($airportId, $message, $type = 'general', $data = [])
    {
        $employees = Employee::where('airport_id', $airportId)
            ->where('employment_status', 'active')
            ->get();

        Notification::send($employees, new CustomNotification($message, $type, $data));
    }

    /**
     * Send notification to employees with specific role
     */
    public function sendToRole($roleName, $message, $type = 'general', $data = [])
    {
        $employees = Employee::role($roleName)
            ->where('employment_status', 'active')
            ->get();

        Notification::send($employees, new CustomNotification($message, $type, $data));
    }

    /**
     * Send bulk notification
     */
    public function sendBulk($message, $type = 'general', $data = [])
    {
        Employee::where('employment_status', 'active')
            ->chunk(100, function($employees) use ($message, $type, $data) {
                Notification::send($employees, new CustomNotification($message, $type, $data));
            });
    }
}
