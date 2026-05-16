<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gallery;
use App\Models\Employee;

class GallerySeeder extends Seeder
{
    public function run()
    {
        $admin = Employee::where('employee_id', 'RSCB001')->first();

        $galleryItems = [
            [
                'title' => 'Cricket Tournament Opening Ceremony',
                'category' => 'Cricket',
                'description' => 'Opening ceremony of the Inter-Airport Cricket Tournament',
                'is_featured' => true,
            ],
            [
                'title' => 'Football Championship Final',
                'category' => 'Football',
                'description' => 'Final match of the Regional Football Championship',
                'is_featured' => true,
            ],
            [
                'title' => 'Badminton Singles Match',
                'category' => 'Badminton',
                'description' => 'Intense rally during the Badminton Singles semi-finals',
                'is_featured' => true,
            ],
            [
                'title' => 'Table Tennis Tournament',
                'category' => 'Table Tennis',
                'description' => 'Players in action at the Table Tennis Open',
                'is_featured' => false,
            ],
            [
                'title' => 'Athletics Meet - 100m Sprint',
                'category' => 'Athletics',
                'description' => '100m sprint event at the Annual Athletics Meet',
                'is_featured' => true,
            ],
            [
                'title' => 'Chess Championship',
                'category' => 'Chess',
                'description' => 'Concentration and strategy at the Chess Championship',
                'is_featured' => false,
            ],
            [
                'title' => 'Volleyball League Action',
                'category' => 'Volleyball',
                'description' => 'Exciting moment from the Volleyball League',
                'is_featured' => false,
            ],
            [
                'title' => 'Basketball Tournament Highlights',
                'category' => 'Basketball',
                'description' => 'Best moments from the Basketball Tournament',
                'is_featured' => true,
            ],
            [
                'title' => 'Marathon Run Start',
                'category' => 'Marathon',
                'description' => 'Participants at the starting line of the Marathon',
                'is_featured' => false,
            ],
            [
                'title' => 'Prize Distribution Ceremony',
                'category' => 'Ceremony',
                'description' => 'Winners receiving prizes at the Annual Sports Day',
                'is_featured' => true,
            ],
            [
                'title' => 'Sports Day Group Photo',
                'category' => 'Ceremony',
                'description' => 'Group photograph of all participants on Sports Day',
                'is_featured' => false,
            ],
            [
                'title' => 'Yoga Session',
                'category' => 'Yoga',
                'description' => 'Employees participating in the morning yoga session',
                'is_featured' => false,
            ],
        ];

        foreach ($galleryItems as $item) {
            Gallery::create([
                'title' => $item['title'],
                'image_path' => 'gallery/placeholder.jpg',
                'thumbnail_path' => 'gallery/thumbnails/placeholder.jpg',
                'category' => $item['category'],
                'description' => $item['description'],
                'is_featured' => $item['is_featured'],
                'uploaded_by' => $admin ? $admin->id : 1,
            ]);
        }
    }
}
