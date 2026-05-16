<?php
// database/seeders/EventRegistrationSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventRegistration;
use App\Models\Event;
use App\Models\Employee;
use Spatie\Permission\Models\Role;

class EventRegistrationSeeder extends Seeder
{
    public function run()
    {
        $events = Event::all();

        if ($events->count() === 0) {
            echo "No events found. Skipping registration seeding.\n";
            return;
        }

        $employees = Employee::where('employment_status', 'active')->get();

        if ($employees->count() === 0) {
            echo "No active employees found. Skipping registration seeding.\n";
            return;
        }

        // Get approver (regional sports secretary or admin)
        $approver = Employee::role('regional_sports_secretary')->first()
                    ?? Employee::role('super_admin')->first();

        $statuses = ['draft', 'pending', 'approved', 'rejected'];
        $statusWeights = [10, 30, 50, 10]; // Weighted distribution

        foreach ($events as $event) {
            // Generate random number of registrations per event
            $maxReg = min(20, $event->max_participants ?? 20);
            $numRegistrations = rand(5, $maxReg);

            // Get random employees for this event
            $eventEmployees = $employees->random(min($numRegistrations, $employees->count()));

            foreach ($eventEmployees as $employee) {
                // Skip if already registered
                if (EventRegistration::where('event_id', $event->id)
                    ->where('employee_id', $employee->id)
                    ->exists()) {
                    continue;
                }

                // Determine status based on weights
                $status = $this->getWeightedRandom($statuses, $statusWeights);

                $registrationData = [
                    'registration_number' => EventRegistration::generateRegistrationNumber(),
                    'event_id' => $event->id,
                    'employee_id' => $employee->id,
                    'status' => $status,
                    'form_data' => $this->generateFormData($event),
                    'documents_verified' => $status === 'approved' ? true : (rand(0, 1) === 1),
                    'created_at' => now()->subDays(rand(1, 60)),
                ];

                // Set approval/rejection details
                if ($status === 'approved' && $approver) {
                    $registrationData['approved_by'] = $approver->id;
                    $registrationData['approved_at'] = now()->subDays(rand(1, 30));
                } elseif ($status === 'rejected' && $approver) {
                    $registrationData['rejected_by'] = $approver->id;
                    $registrationData['rejected_at'] = now()->subDays(rand(1, 30));
                    $registrationData['rejection_reason'] = $this->getRejectionReason();
                }

                EventRegistration::create($registrationData);
            }
        }

        echo "Event registrations seeded successfully!\n";
    }

    private function generateFormData($event)
    {
        return [
            'full_name' => 'Participant Name',
            'age' => rand(25, 55),
            'blood_group' => ['A+', 'B+', 'O+', 'AB+'][array_rand(['A+', 'B+', 'O+', 'AB+'])],
            'tshirt_size' => ['S', 'M', 'L', 'XL'][array_rand(['S', 'M', 'L', 'XL'])],
            'emergency_contact_name' => 'Emergency Contact',
            'emergency_contact_number' => '98' . rand(10000000, 99999999),
            'previous_experience' => 'Some sports experience',
            'declaration' => true,
        ];
    }

    private function getRejectionReason()
    {
        $reasons = [
            'Incomplete documentation',
            'Does not meet eligibility criteria',
            'Registration after deadline',
            'Event capacity full',
            'Medical certificate not valid',
            'Department not eligible',
            'Age criteria not met',
            'Duplicate registration found',
        ];
        return $reasons[array_rand($reasons)];
    }

    private function getWeightedRandom($values, $weights)
    {
        $totalWeight = array_sum($weights);
        $random = rand(1, $totalWeight);

        foreach ($values as $index => $value) {
            $random -= $weights[$index];
            if ($random <= 0) {
                return $value;
            }
        }

        return $values[0];
    }
}
