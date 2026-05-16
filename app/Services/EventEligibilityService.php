<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Event;

class EventEligibilityService
{
    /**
     * Check if employee is eligible for an event
     */
    public function isEligible(Employee $employee, Event $event): bool
    {
        // Check employment status
        if ($employee->employment_status !== 'active') {
            return false;
        }

        // Check gender eligibility
        if ($event->gender_eligibility !== 'all' &&
            $event->gender_eligibility !== $employee->gender) {
            return false;
        }

        // Check age criteria
        if ($event->min_age && $employee->age < $event->min_age) {
            return false;
        }
        if ($event->max_age && $employee->age > $event->max_age) {
            return false;
        }

        // Check department eligibility
        if ($event->department_eligibility && !empty($event->department_eligibility)) {
            if (!in_array($employee->department, $event->department_eligibility)) {
                return false;
            }
        }

        // Check region/airport eligibility
        switch ($event->event_type) {
            case 'regional':
                if ($event->region_id && $employee->region_id !== $event->region_id) {
                    return false;
                }
                break;

            case 'airport':
                if ($event->airport_id && $employee->airport_id !== $event->airport_id) {
                    return false;
                }
                break;

            case 'inter_airport':
                // More flexible - might be from same region
                if ($event->region_id && $employee->region_id !== $event->region_id) {
                    return false;
                }
                break;

            case 'annual_meet':
                // Open to all
                break;
        }

        // Check if already registered
        $existingRegistration = $event->registrations()
            ->where('employee_id', $employee->id)
            ->where('status', 'approved')
            ->exists();

        if ($existingRegistration) {
            return false;
        }

        // Check max participants limit
        if ($event->max_participants) {
            $approvedCount = $event->registrations()
                ->where('status', 'approved')
                ->count();

            if ($approvedCount >= $event->max_participants) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get all eligible employees for an event
     */
    public function getEligibleEmployees(Event $event)
    {
        $query = Employee::where('employment_status', 'active');

        // Gender filter
        if ($event->gender_eligibility !== 'all') {
            $query->where('gender', $event->gender_eligibility);
        }

        // Age filter
        if ($event->min_age) {
            $query->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) >= ?', [$event->min_age]);
        }
        if ($event->max_age) {
            $query->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) <= ?', [$event->max_age]);
        }

        // Department filter
        if ($event->department_eligibility && !empty($event->department_eligibility)) {
            $query->whereIn('department', $event->department_eligibility);
        }

        // Region/Airport filter based on event type
        switch ($event->event_type) {
            case 'regional':
                if ($event->region_id) {
                    $query->where('region_id', $event->region_id);
                }
                break;
            case 'airport':
                if ($event->airport_id) {
                    $query->where('airport_id', $event->airport_id);
                }
                break;
            case 'inter_airport':
                if ($event->region_id) {
                    $query->where('region_id', $event->region_id);
                }
                break;
        }

        // Exclude already approved registrations
        $excludeIds = $event->registrations()
            ->where('status', 'approved')
            ->pluck('employee_id');
        $query->whereNotIn('id', $excludeIds);

        return $query->get();
    }

    /**
     * Get eligibility reasons for an employee
     */
    public function getEligibilityDetails(Employee $employee, Event $event): array
    {
        $reasons = [];
        $eligible = true;

        if ($employee->employment_status !== 'active') {
            $reasons[] = 'Employee is not active';
            $eligible = false;
        }

        if ($event->gender_eligibility !== 'all' &&
            $event->gender_eligibility !== $employee->gender) {
            $reasons[] = "Event is for {$event->gender_eligibility} participants only";
            $eligible = false;
        }

        if ($event->min_age && $employee->age < $event->min_age) {
            $reasons[] = "Minimum age requirement is {$event->min_age} years";
            $eligible = false;
        }

        if ($event->max_age && $employee->age > $event->max_age) {
            $reasons[] = "Maximum age limit is {$event->max_age} years";
            $eligible = false;
        }

        if ($event->department_eligibility && !empty($event->department_eligibility)) {
            if (!in_array($employee->department, $event->department_eligibility)) {
                $reasons[] = "Your department is not eligible for this event";
                $eligible = false;
            }
        }

        return [
            'eligible' => $eligible,
            'reasons' => $reasons
        ];
    }
}
