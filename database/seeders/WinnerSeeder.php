<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Winner;
use App\Models\Event;
use App\Models\Employee;
use App\Models\EventRegistration;

class WinnerSeeder extends Seeder
{
    public function run()
    {
        $completedEvents = Event::where('status', 'completed')->get();
        $positions = ['1st', '2nd', '3rd'];
        $prizes = ['Gold Medal & Trophy', 'Silver Medal & Certificate', 'Bronze Medal & Certificate'];

        foreach ($completedEvents as $event) {
            // Get approved registrations for this event
            $registrations = EventRegistration::where('event_id', $event->id)
                ->where('status', 'approved')
                ->with('employee')
                ->get();

            if ($registrations->count() >= 3) {
                $winners = $registrations->random(3);

                foreach ($positions as $index => $position) {
                    if (isset($winners[$index])) {
                        Winner::create([
                            'event_id' => $event->id,
                            'employee_id' => $winners[$index]->employee_id,
                            'position' => $position,
                            'category' => $event->participation_type === 'individual' ? 'Individual' : 'Team',
                            'achievement' => $this->getAchievement($event, $position),
                            'prize' => $prizes[$index],
                            'remarks' => 'Congratulations on your outstanding performance!',
                        ]);
                    }
                }
            }
        }
    }

    private function getAchievement($event, $position)
    {
        $achievements = [
            '1st' => [
                'Gold medalist with record-breaking performance',
                'First place with exceptional skill and determination',
                'Outstanding performance securing first position',
            ],
            '2nd' => [
                'Silver medalist with excellent performance',
                'Second place with remarkable sportsmanship',
                'Runner-up with impressive skills',
            ],
            '3rd' => [
                'Bronze medalist with commendable performance',
                'Third place with great competitive spirit',
                'Secured third position with consistent performance',
            ],
        ];

        return $achievements[$position][array_rand($achievements[$position])];
    }
}
