<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feedback;

class FeedbackSeeder extends Seeder
{
    public function run()
    {
        $feedbacks = [
            [
                'name' => 'Rajesh Kumar',
                'email' => 'rajesh.kumar@example.com',
                'rating' => 5,
                'feedback' => 'Excellent platform for managing sports events. The registration process is very smooth and user-friendly.',
                'category' => 'appreciation',
                'status' => 'reviewed',
            ],
            [
                'name' => 'Priya Sharma',
                'email' => 'priya.sharma@example.com',
                'rating' => 4,
                'feedback' => 'Great initiative by RSCB. Would like to see more indoor sports events added to the calendar.',
                'category' => 'suggestion',
                'status' => 'reviewed',
            ],
            [
                'name' => 'Amit Patel',
                'email' => 'amit.patel@example.com',
                'rating' => 4,
                'feedback' => 'The certificate generation feature is very useful. QR verification adds authenticity.',
                'category' => 'appreciation',
                'status' => 'pending',
            ],
            [
                'name' => 'Sunita Reddy',
                'email' => 'sunita.reddy@example.com',
                'rating' => 3,
                'feedback' => 'Facing some issues with document upload. The file size limit could be increased for medical certificates.',
                'category' => 'technical',
                'status' => 'pending',
            ],
            [
                'name' => 'Mohammad Khan',
                'email' => 'mohammad.khan@example.com',
                'rating' => 5,
                'feedback' => 'Very well organized events. The portal makes it easy to track registrations and results.',
                'category' => 'appreciation',
                'status' => 'resolved',
            ],
            [
                'name' => 'Deepika Verma',
                'email' => 'deepika.verma@example.com',
                'rating' => 2,
                'feedback' => 'The mobile experience needs improvement. Some buttons are not properly visible on smaller screens.',
                'category' => 'complaint',
                'status' => 'pending',
            ],
            [
                'name' => 'Suresh Nair',
                'email' => 'suresh.nair@example.com',
                'rating' => 4,
                'feedback' => 'Good platform overall. The eligibility check feature helps avoid wasting time on events I cannot participate in.',
                'category' => 'general',
                'status' => 'pending',
            ],
        ];

        foreach ($feedbacks as $feedbackData) {
            Feedback::create($feedbackData);
        }
    }
}
