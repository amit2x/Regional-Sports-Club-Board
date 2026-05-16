<?php

namespace Database\Seeders;

use Database\Seeders\FormTemplateSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();


        // Truncate tables in correct order
        DB::table('activity_log')->truncate();
        DB::table('event_registrations')->truncate();
        DB::table('registration_documents')->truncate();
        DB::table('employee_documents')->truncate();
        DB::table('participation_certificates')->truncate();
        DB::table('events')->truncate();
        DB::table('form_templates')->truncate();
        DB::table('announcements')->truncate();
        DB::table('galleries')->truncate();
        DB::table('feedback')->truncate();
        DB::table('contact_queries')->truncate();
        DB::table('winners')->truncate();
        DB::table('employees')->truncate();
        DB::table('airports')->truncate();
        DB::table('regions')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();

        // Enable foreign key checks
        Schema::enableForeignKeyConstraints();

        // Clear cache before seeding
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Seed in order - Permissions FIRST
        $this->call([
            PermissionSeeder::class,     // Create roles and permissions first
            RegionSeeder::class,         // Create regions
            AirportSeeder::class,        // Create airports (depends on regions)
        ]);

        // Run seeders in order
        $this->call([
            EmployeeSeeder::class,
            FormTemplateSeeder::class,
            EventSeeder::class,
            EventRegistrationSeeder::class,
            AnnouncementSeeder::class,
            GallerySeeder::class,
            WinnerSeeder::class,
            FeedbackSeeder::class,
            ContactQuerySeeder::class,
        ]);

             echo "\n========================================\n";
        echo "  Database seeding completed successfully!\n";
        echo "========================================\n\n";
        echo "Default Login Credentials:\n";
        echo "  Super Admin: RSCB001 / Admin@1234\n";
        echo "  Regional Secretary: RSCB002 / Secretary@1234\n";
        echo "  Airport Secretary: DEL001 / Airport@1234\n";
        echo "  Employee: DEL0001 / Employee@1234\n";
        echo "  (Any employee can login with Employee ID and password 'Employee@1234')\n\n";
        $this->command->info('Database seeded successfully!');
    }
}
