<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Airport;
use App\Models\Region;

class AirportSeeder extends Seeder
{
    public function run()
    {
        $airports = [
            // Northern Region
            [
                'name' => 'Indira Gandhi International Airport',
                'code' => 'DEL',
                'city' => 'New Delhi',
                'state' => 'Delhi',
                'region_code' => 'NR',
                'contact_person' => 'Airport Director DEL',
                'contact_number' => '011-XXXXXXXX',
                'email' => 'del@rscb.gov.in',
            ],
            [
                'name' => 'Chandigarh International Airport',
                'code' => 'IXC',
                'city' => 'Chandigarh',
                'state' => 'Chandigarh',
                'region_code' => 'NR',
                'contact_person' => 'Airport Director IXC',
                'contact_number' => '0172-XXXXXXXX',
                'email' => 'ixc@rscb.gov.in',
            ],
            [
                'name' => 'Jaipur International Airport',
                'code' => 'JAI',
                'city' => 'Jaipur',
                'state' => 'Rajasthan',
                'region_code' => 'NR',
                'contact_person' => 'Airport Director JAI',
                'contact_number' => '0141-XXXXXXXX',
                'email' => 'jai@rscb.gov.in',
            ],
            // Southern Region
            [
                'name' => 'Chennai International Airport',
                'code' => 'MAA',
                'city' => 'Chennai',
                'state' => 'Tamil Nadu',
                'region_code' => 'SR',
                'contact_person' => 'Airport Director MAA',
                'contact_number' => '044-XXXXXXXX',
                'email' => 'maa@rscb.gov.in',
            ],
            [
                'name' => 'Kempegowda International Airport',
                'code' => 'BLR',
                'city' => 'Bengaluru',
                'state' => 'Karnataka',
                'region_code' => 'SR',
                'contact_person' => 'Airport Director BLR',
                'contact_number' => '080-XXXXXXXX',
                'email' => 'blr@rscb.gov.in',
            ],
            // Eastern Region
            [
                'name' => 'Netaji Subhas Chandra Bose International Airport',
                'code' => 'CCU',
                'city' => 'Kolkata',
                'state' => 'West Bengal',
                'region_code' => 'ER',
                'contact_person' => 'Airport Director CCU',
                'contact_number' => '033-XXXXXXXX',
                'email' => 'ccu@rscb.gov.in',
            ],
            // Western Region
            [
                'name' => 'Chhatrapati Shivaji Maharaj International Airport',
                'code' => 'BOM',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'region_code' => 'WR',
                'contact_person' => 'Airport Director BOM',
                'contact_number' => '022-XXXXXXXX',
                'email' => 'bom@rscb.gov.in',
            ],
            // North-Eastern Region
            [
                'name' => 'Lokpriya Gopinath Bordoloi International Airport',
                'code' => 'GAU',
                'city' => 'Guwahati',
                'state' => 'Assam',
                'region_code' => 'NER',
                'contact_person' => 'Airport Director GAU',
                'contact_number' => '0361-XXXXXXXX',
                'email' => 'gau@rscb.gov.in',
            ],
        ];

        foreach ($airports as $airport) {
            $region = Region::where('code', $airport['region_code'])->first();
            if ($region) {
                Airport::create([
                    'name' => $airport['name'],
                    'code' => $airport['code'],
                    'city' => $airport['city'],
                    'state' => $airport['state'],
                    'region_id' => $region->id,
                    'contact_person' => $airport['contact_person'],
                    'contact_number' => $airport['contact_number'],
                    'email' => $airport['email'],
                    'status' => 'active',
                ]);
            }
        }
    }
}
