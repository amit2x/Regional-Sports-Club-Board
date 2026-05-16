<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;

class RegionSeeder extends Seeder
{
    public function run()
    {
        $regions = [
            [
                'name' => 'Northern Region',
                'code' => 'NR',
                'headquarters' => 'New Delhi',
                'description' => 'Northern Region covers airports in North India',
                'status' => 'active',
            ],
            [
                'name' => 'Southern Region',
                'code' => 'SR',
                'headquarters' => 'Chennai',
                'description' => 'Southern Region covers airports in South India',
                'status' => 'active',
            ],
            [
                'name' => 'Eastern Region',
                'code' => 'ER',
                'headquarters' => 'Kolkata',
                'description' => 'Eastern Region covers airports in East India',
                'status' => 'active',
            ],
            [
                'name' => 'Western Region',
                'code' => 'WR',
                'headquarters' => 'Mumbai',
                'description' => 'Western Region covers airports in West India',
                'status' => 'active',
            ],
            [
                'name' => 'North-Eastern Region',
                'code' => 'NER',
                'headquarters' => 'Guwahati',
                'description' => 'North-Eastern Region covers airports in North-East India',
                'status' => 'active',
            ],
        ];

        foreach ($regions as $region) {
            Region::create($region);
        }
    }
}
