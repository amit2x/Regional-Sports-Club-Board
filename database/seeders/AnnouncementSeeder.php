<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\Employee;

class AnnouncementSeeder extends Seeder
{
    public function run()
    {
        $admin = Employee::where('employee_id', 'RSCB001')->first();

        $announcements = [
            [
                'title' => 'Annual Sports Calendar 2024 Released',
                'content' => '<p>The Annual Sports Calendar for 2024 has been released. All employees are requested to check the events section for upcoming tournaments and registration deadlines.</p><p>Key highlights:</p><ul><li>Inter-Airport Cricket Tournament - March 2024</li><li>Regional Athletics Meet - April 2024</li><li>Annual Sports Day - December 2024</li></ul>',
                'priority' => 'high',
                'valid_from' => now(),
                'valid_until' => now()->addMonths(6),
            ],
            [
                'title' => 'Registration Open: Inter-Region Football Championship',
                'content' => '<p>Registration is now open for the Inter-Region Football Championship 2024. All interested teams must register before 15th March 2024.</p><p>Eligibility: All active employees</p><p>Team Size: 11 players + 5 substitutes</p>',
                'priority' => 'urgent',
                'valid_from' => now()->subDays(5),
                'valid_until' => now()->addMonths(2),
            ],
            [
                'title' => 'New Sports Facilities at Delhi Airport',
                'content' => '<p>We are pleased to announce the inauguration of new sports facilities at Delhi Airport. The facilities include an indoor badminton court, table tennis room, and a modern gymnasium.</p>',
                'priority' => 'medium',
                'valid_from' => now()->subDays(10),
                'valid_until' => now()->addMonths(3),
            ],
            [
                'title' => 'Medical Fitness Camp for Athletes',
                'content' => '<p>A medical fitness camp will be organized for all registered athletes on 20th February 2024. Attendance is mandatory for all event participants.</p><p>Venue: Medical Center, Terminal 2</p><p>Time: 9:00 AM - 5:00 PM</p>',
                'priority' => 'high',
                'valid_from' => now()->subDays(2),
                'valid_until' => now()->addWeeks(3),
            ],
            [
                'title' => 'Congratulations to Cricket Tournament Winners',
                'content' => '<p>Congratulations to the Delhi Airport team for winning the Inter-Airport Cricket Tournament 2023. The team showed excellent sportsmanship and team spirit throughout the tournament.</p>',
                'priority' => 'low',
                'valid_from' => now()->subDays(15),
                'valid_until' => now()->addWeeks(2),
            ],
            [
                'title' => 'New Online Registration System',
                'content' => '<p>RSCB is pleased to announce the launch of the new online registration system. Employees can now register for events, upload documents, and track their application status online.</p>',
                'priority' => 'medium',
                'valid_from' => now()->subDays(30),
                'valid_until' => now()->addMonths(4),
            ],
            [
                'title' => 'Sports Kit Distribution Schedule',
                'content' => '<p>Sports kits for registered participants will be distributed from 1st March to 5th March 2024 at respective airport sports offices. Please carry your employee ID for collection.</p>',
                'priority' => 'high',
                'valid_from' => now()->addDays(10),
                'valid_until' => now()->addMonths(1),
            ],
            [
                'title' => 'Annual Sports Day Celebration',
                'content' => '<p>The Annual Sports Day will be celebrated on 15th December 2024 at the Main Stadium. All employees and their families are invited to attend. Various sports events, cultural programs, and prize distribution ceremonies will be organized.</p>',
                'priority' => 'medium',
                'valid_from' => now()->addMonths(8),
                'valid_until' => now()->addMonths(9),
            ],
        ];

        foreach ($announcements as $announcementData) {
            Announcement::create([
                'title' => $announcementData['title'],
                'content' => $announcementData['content'],
                'priority' => $announcementData['priority'],
                'status' => 'published',
                'valid_from' => $announcementData['valid_from'],
                'valid_until' => $announcementData['valid_until'],
                'published_at' => $announcementData['valid_from'],
                'created_by' => $admin ? $admin->id : 1,
                'views_count' => rand(50, 500),
            ]);
        }

        // Create some draft announcements
        for ($i = 0; $i < 3; $i++) {
            Announcement::create([
                'title' => 'Draft Announcement ' . ($i + 1),
                'content' => '<p>This is a draft announcement pending review.</p>',
                'priority' => 'low',
                'status' => 'draft',
                'valid_from' => now()->addDays(rand(5, 15)),
                'valid_until' => now()->addMonths(1),
                'created_by' => $admin ? $admin->id : 1,
            ]);
        }
    }
}
