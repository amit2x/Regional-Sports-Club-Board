<?php
// database/seeders/EmployeeSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Airport;
use App\Models\Region;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        // Ensure roles exist
        $this->ensureRolesExist();

        $delhiAirport = Airport::where('code', 'DEL')->first();
        $northernRegion = Region::where('code', 'NR')->first();

        // Fallback if no airports/regions exist
        if (!$delhiAirport) {
            $delhiAirport = Airport::first();
        }
        if (!$northernRegion) {
            $northernRegion = Region::first();
        }

        // Super Admin
        $superAdmin = Employee::create([
            'employee_id' => 'RSCB001',
            'name' => 'Super Admin',
            'pan_number' => 'ABCDE1234F',
            'designation' => 'Director Sports',
            'department' => 'Sports Administration',
            'airport_id' => $delhiAirport ? $delhiAirport->id : null,
            'region_id' => $northernRegion ? $northernRegion->id : null,
            'gender' => 'male',
            'date_of_birth' => '1980-01-01',
            'email' => 'admin@rscb.gov.in',
            'mobile' => '9812345670',
            'sports_category' => 'Administration',
            'blood_group' => 'O+',
            'employment_status' => 'active',
            'password' => Hash::make('Admin@1234'),
            'force_password_change' => false,
        ]);

        // Assign role with error handling
        try {
            $superAdmin->assignRole('super_admin');
        } catch (\Exception $e) {
            echo "Warning: Could not assign super_admin role. " . $e->getMessage() . "\n";
        }

        // Regional Sports Secretary - Northern Region
        $regionalSec = Employee::create([
            'employee_id' => 'RSCB002',
            'name' => 'Regional Sports Secretary NR',
            'pan_number' => 'BCDEF2345G',
            'designation' => 'Regional Sports Secretary',
            'department' => 'Sports Administration',
            'airport_id' => $delhiAirport ? $delhiAirport->id : null,
            'region_id' => $northernRegion ? $northernRegion->id : null,
            'gender' => 'male',
            'date_of_birth' => '1982-05-15',
            'email' => 'regional.nr@rscb.gov.in',
            'mobile' => '9812345671',
            'sports_category' => 'Administration',
            'blood_group' => 'A+',
            'employment_status' => 'active',
            'password' => Hash::make('Secretary@1234'),
            'force_password_change' => false,
        ]);

        try {
            $regionalSec->assignRole('regional_sports_secretary');
        } catch (\Exception $e) {
            echo "Warning: Could not assign regional_sports_secretary role. " . $e->getMessage() . "\n";
        }

        // Airport Sports Secretary - Delhi Airport
        $airportSec = Employee::create([
            'employee_id' => 'DEL001',
            'name' => 'Airport Sports Secretary DEL',
            'pan_number' => 'CDEFG3456H',
            'designation' => 'Airport Sports Secretary',
            'department' => 'HR & Sports',
            'airport_id' => $delhiAirport ? $delhiAirport->id : null,
            'region_id' => $northernRegion ? $northernRegion->id : null,
            'gender' => 'female',
            'date_of_birth' => '1985-08-20',
            'email' => 'sports.del@rscb.gov.in',
            'mobile' => '9812345672',
            'sports_category' => 'Administration',
            'blood_group' => 'B+',
            'employment_status' => 'active',
            'password' => Hash::make('Airport@1234'),
            'force_password_change' => false,
        ]);

        try {
            $airportSec->assignRole('airport_sports_secretary');
        } catch (\Exception $e) {
            echo "Warning: Could not assign airport_sports_secretary role. " . $e->getMessage() . "\n";
        }

        // Sample Employees
        $departments = ['Operations', 'Security', 'Engineering', 'Fire Services', 'HR', 'Finance', 'IT', 'Commercial'];
        $designations = ['Officer', 'Senior Officer', 'Assistant Manager', 'Deputy Manager', 'Manager', 'Senior Manager'];
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $sportsCategories = ['Cricket', 'Football', 'Badminton', 'Table Tennis', 'Chess', 'Athletics', 'Volleyball', 'Basketball'];

        $airports = Airport::all();

        if ($airports->count() === 0) {
            echo "No airports found. Skipping employee creation.\n";
            return;
        }

        for ($i = 1; $i <= 50; $i++) {
            $airport = $airports->random();
            $department = $departments[array_rand($departments)];
            $designation = $designations[array_rand($designations)];

            $employee = Employee::create([
                'employee_id' => $airport->code . sprintf('%04d', $i),
                'name' => 'Employee ' . $i . ' ' . $airport->code,
                'pan_number' => $this->generatePAN(),
                'designation' => $designation,
                'department' => $department,
                'airport_id' => $airport->id,
                'region_id' => $airport->region_id,
                'gender' => rand(0, 1) ? 'male' : 'female',
                'date_of_birth' => now()->subYears(rand(25, 55))->subDays(rand(0, 365)),
                'email' => 'employee' . $i . '@rscb.gov.in',
                'mobile' => '98123' . sprintf('%05d', $i),
                'sports_category' => $sportsCategories[array_rand($sportsCategories)],
                'blood_group' => $bloodGroups[array_rand($bloodGroups)],
                'medical_conditions' => rand(0, 1) ? null : 'None',
                'employment_status' => 'active',
                'password' => Hash::make('Employee@1234'),
                'force_password_change' => true,
            ]);

            try {
                $employee->assignRole('employee');
            } catch (\Exception $e) {
                // Skip role assignment if fails
            }
        }

        echo "Employees seeded successfully!\n";
    }

    /**
     * Ensure roles exist before seeding employees
     */
    private function ensureRolesExist()
    {
        $roles = ['super_admin', 'regional_sports_secretary', 'airport_sports_secretary', 'employee'];

        foreach ($roles as $roleName) {
            if (!Role::where('name', $roleName)->where('guard_name', 'employee')->exists()) {
                echo "Creating role: {$roleName}\n";
                Role::create([
                    'name' => $roleName,
                    'guard_name' => 'employee',
                ]);
            }
        }
    }

    /**
     * Generate a random PAN number
     */
    private function generatePAN()
    {
        $letters = range('A', 'Z');
        $pan = '';
        for ($i = 0; $i < 5; $i++) {
            $pan .= $letters[array_rand($letters)];
        }
        $pan .= sprintf('%04d', rand(0, 9999));
        $pan .= $letters[array_rand($letters)];
        return $pan;
    }
}
