<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContactQuery;

class ContactQuerySeeder extends Seeder
{
    public function run()
    {
        $queries = [
            [
                'name' => 'Vikram Singh',
                'email' => 'vikram.singh@example.com',
                'subject' => 'Registration Issue',
                'message' => 'I am unable to register for the upcoming cricket tournament. The system shows an error message after submitting the form. My employee ID is DEL1234. Please help resolve this issue.',
                'status' => 'pending',
            ],
            [
                'name' => 'Anita Desai',
                'email' => 'anita.desai@example.com',
                'subject' => 'Certificate Not Generated',
                'message' => 'I participated in the Athletics Meet held last month but have not received my participation certificate yet. Registration number: REG-2024-ABC123.',
                'status' => 'pending',
            ],
            [
                'name' => 'Rahul Gupta',
                'email' => 'rahul.gupta@example.com',
                'subject' => 'Event Suggestion',
                'message' => 'I would like to suggest adding Kabaddi to the list of sports events. Many employees from our region are interested in this sport.',
                'status' => 'read',
            ],
            [
                'name' => 'Meera Joshi',
                'email' => 'meera.joshi@example.com',
                'subject' => 'Password Reset Issue',
                'message' => 'I have requested password reset multiple times but have not received the reset link on my email. Please assist.',
                'status' => 'read',
            ],
            [
                'name' => 'Karthik Menon',
                'email' => 'karthik.menon@example.com',
                'subject' => 'Venue Change Request',
                'message' => 'The volleyball court at our airport is under renovation. Can the upcoming tournament venue be shifted to the nearby sports complex?',
                'status' => 'pending',
            ],
            [
                'name' => 'Pooja Agarwal',
                'email' => 'pooja.agarwal@example.com',
                'subject' => 'Document Verification',
                'message' => 'My documents have been pending verification for over two weeks. Registration number: REG-2024-XYZ789. Please expedite the verification process.',
                'status' => 'pending',
            ],
            [
                'name' => 'Ravi Shankar',
                'email' => 'ravi.shankar@example.com',
                'subject' => 'Team Registration Query',
                'message' => 'Can we register a mixed-gender team for the table tennis doubles event? The rules are not clear on this aspect.',
                'status' => 'responded',
                'response' => 'Yes, mixed-gender teams are allowed for table tennis doubles. Both players must be from the same airport/region.',
                'responded_by' => 1,
                'responded_at' => now()->subDays(2),
            ],
            [
                'name' => 'Lakshmi Narayan',
                'email' => 'lakshmi.narayan@example.com',
                'subject' => 'Event Schedule Conflict',
                'message' => 'The Chess Championship and Badminton Tournament are scheduled on the same dates. I want to participate in both. Is it possible to reschedule one of them?',
                'status' => 'pending',
            ],
        ];

        foreach ($queries as $queryData) {
            ContactQuery::create($queryData);
        }
    }
}
