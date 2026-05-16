<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Region;
use App\Models\Airport;
use App\Models\Employee;
use App\Models\FormTemplate;

class EventSeeder extends Seeder
{
    public function run()
    {
        $admin = Employee::where('employee_id', 'RSCB001')->first();
        $regions = Region::all();
        $airports = Airport::all();
        $templates = FormTemplate::all();

        $eventTypes = ['regional', 'airport', 'inter_airport', 'annual_meet'];
        $participationTypes = ['individual', 'team', 'both'];
        $statuses = ['draft', 'published', 'completed', 'cancelled'];

        $sportsEvents = [
            [
                'name' => 'Annual Cricket Tournament',
                'type' => 'team',
                'venue' => 'Main Sports Complex',
            ],
            [
                'name' => 'Inter-Airport Football Championship',
                'type' => 'team',
                'venue' => 'Football Stadium',
            ],
            [
                'name' => 'Regional Badminton Singles',
                'type' => 'individual',
                'venue' => 'Indoor Sports Hall',
            ],
            [
                'name' => 'Table Tennis Open',
                'type' => 'individual',
                'venue' => 'Recreation Center',
            ],
            [
                'name' => 'Chess Championship',
                'type' => 'individual',
                'venue' => 'Conference Hall',
            ],
            [
                'name' => 'Athletics Meet 2024',
                'type' => 'both',
                'venue' => 'Athletic Track',
            ],
            [
                'name' => 'Volleyball League',
                'type' => 'team',
                'venue' => 'Volleyball Court',
            ],
            [
                'name' => 'Basketball Tournament',
                'type' => 'team',
                'venue' => 'Basketball Court',
            ],
            [
                'name' => 'Swimming Competition',
                'type' => 'individual',
                'venue' => 'Swimming Pool Complex',
            ],
            [
                'name' => 'Annual Sports Meet 2024',
                'type' => 'both',
                'venue' => 'Main Stadium',
            ],
            [
                'name' => 'Marathon Run',
                'type' => 'individual',
                'venue' => 'Airport Premises',
            ],
            [
                'name' => 'Carrom Tournament',
                'type' => 'individual',
                'venue' => 'Recreation Room',
            ],
            [
                'name' => 'Tennis Open Championship',
                'type' => 'both',
                'venue' => 'Tennis Court',
            ],
            [
                'name' => 'Kabaddi League',
                'type' => 'team',
                'venue' => 'Outdoor Ground',
            ],
            [
                'name' => 'Yoga and Fitness Camp',
                'type' => 'individual',
                'venue' => 'Yoga Hall',
            ],
        ];

        foreach ($sportsEvents as $index => $eventData) {
            $region = $regions->random();
            $airport = $airports->random();
            $template = $templates->random();

            $startDate = now()->addDays(rand(15, 90));
            $endDate = (clone $startDate)->addDays(rand(1, 5));
            $registrationLastDate = (clone $startDate)->subDays(rand(5, 10));

            $isPublished = $index < 10; // First 10 events are published

            Event::create([
                'event_name' => $eventData['name'],
                'event_code' => 'EVT-' . date('Y') . '-' . sprintf('%04d', $index + 1),
                'description' => 'This is the ' . $eventData['name'] . ' organized by Regional Sports Control Board. All eligible employees are encouraged to participate and showcase their sports talent.',
                'banner_image' => null,
                'venue' => $eventData['venue'],
                'region_id' => $region->id,
                'airport_id' => $airport->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'registration_last_date' => $registrationLastDate,
                'event_type' => $eventTypes[array_rand($eventTypes)],
                'participation_type' => $eventData['type'],
                'max_participants' => $eventData['type'] === 'individual' ? rand(50, 200) : rand(10, 30),
                'gender_eligibility' => ['male', 'female', 'all'][array_rand(['male', 'female', 'all'])],
                'min_age' => 18,
                'max_age' => 60,
                'department_eligibility' => null,
                'rules_regulations' => "1. All participants must be active employees.\n2. Participants must wear appropriate sports attire.\n3. Fair play and sportsmanship are mandatory.\n4. Decision of officials will be final.\n5. Participants must report 30 minutes before the event.",
                'required_documents' => ['medical_certificate', 'id_card', 'undertaking_form'],
                'status' => $isPublished ? 'published' : 'draft',
                'is_published' => $isPublished,
                'created_by' => $admin ? $admin->id : 1,
                'form_template_id' => $template->id,
            ]);
        }

        // Create some completed events
        for ($i = 0; $i < 5; $i++) {
            $region = $regions->random();
            $airport = $airports->random();
            $template = $templates->random();

            $startDate = now()->subDays(rand(30, 180));
            $endDate = (clone $startDate)->addDays(rand(1, 5));

            Event::create([
                'event_name' => 'Completed Sports Event ' . ($i + 1),
                'event_code' => 'EVT-' . date('Y') . '-C' . sprintf('%04d', $i + 1),
                'description' => 'This event has been completed successfully.',
                'venue' => 'Sports Complex',
                'region_id' => $region->id,
                'airport_id' => $airport->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'registration_last_date' => (clone $startDate)->subDays(10),
                'event_type' => 'regional',
                'participation_type' => 'individual',
                'max_participants' => 100,
                'gender_eligibility' => 'all',
                'status' => 'completed',
                'is_published' => true,
                'created_by' => $admin ? $admin->id : 1,
                'form_template_id' => $template->id,
            ]);
        }
    }
}
