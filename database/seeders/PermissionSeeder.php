<?php
// database/seeders/PermissionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions with guard name
        $guardName = 'employee';

        $permissions = [
            // Dashboard Permissions
            'view_super_admin_dashboard',
            'view_regional_dashboard',
            'view_airport_dashboard',
            'view_employee_dashboard',

            // Region Management
            'view_regions',
            'create_regions',
            'edit_regions',
            'delete_regions',

            // Airport Management
            'view_airports',
            'create_airports',
            'edit_airports',
            'delete_airports',
            'manage_regional_airports',

            // Employee Management
            'view_employees',
            'create_employees',
            'edit_employees',
            'delete_employees',
            'import_employees',
            'export_employees',
            'view_employee_details',

            // Event Management
            'view_events',
            'create_events',
            'edit_events',
            'delete_events',
            'publish_events',
            'manage_regional_events',
            'manage_airport_events',
            'view_event_participants',

            // Dynamic Forms
            'manage_form_templates',
            'create_form_fields',
            'edit_form_fields',
            'delete_form_fields',

            // Registration Management
            'view_registrations',
            'approve_registrations',
            'reject_registrations',
            'view_registration_details',

            // Document Management
            'view_documents',
            'verify_documents',
            'upload_documents',
            'download_documents',

            // Reports & Analytics
            'view_reports',
            'generate_reports',
            'export_reports',
            'view_analytics',

            // User Management
            'manage_roles',
            'manage_permissions',
            'assign_roles',

            // System Settings
            'manage_settings',
            'view_audit_logs',
            'manage_notifications',

            // Certificate Management
            'generate_certificates',
            'verify_certificates',
            'manage_certificates',

            // Gallery & Media
            'manage_gallery',
            'upload_media',

            // Announcements
            'manage_announcements',
            'publish_announcements',
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => $guardName,
            ]);
        }

        // Create Roles and Assign Permissions with guard name

        // 1. Super Admin Role
        $superAdmin = Role::create([
            'name' => 'super_admin',
            'guard_name' => $guardName,
        ]);
        $superAdmin->givePermissionTo(Permission::all());

        // 2. Regional Sports Secretary Role
        $regionalSecretary = Role::create([
            'name' => 'regional_sports_secretary',
            'guard_name' => $guardName,
        ]);
        $regionalSecretary->givePermissionTo([
            'view_regional_dashboard',
            'view_airports',
            'manage_regional_airports',
            'view_employees',
            'view_employee_details',
            'view_events',
            'create_events',
            'edit_events',
            'manage_regional_events',
            'publish_events',
            'view_event_participants',
            'manage_form_templates',
            'view_registrations',
            'approve_registrations',
            'reject_registrations',
            'view_registration_details',
            'view_documents',
            'verify_documents',
            'download_documents',
            'view_reports',
            'generate_reports',
            'export_reports',
            'view_analytics',
            'generate_certificates',
            'manage_certificates',
            'manage_announcements',
            'publish_announcements',
            'manage_gallery',
            'upload_media',
            'manage_notifications',
        ]);

        // 3. Airport Sports Secretary Role
        $airportSecretary = Role::create([
            'name' => 'airport_sports_secretary',
            'guard_name' => $guardName,
        ]);
        $airportSecretary->givePermissionTo([
            'view_airport_dashboard',
            'view_employees',
            'view_employee_details',
            'view_events',
            'create_events',
            'edit_events',
            'manage_airport_events',
            'view_event_participants',
            'manage_form_templates',
            'view_registrations',
            'approve_registrations',
            'view_registration_details',
            'view_documents',
            'verify_documents',
            'download_documents',
            'view_reports',
            'generate_reports',
            'manage_gallery',
            'upload_media',
            'manage_announcements',
        ]);

        // 4. Employee/User Role
        $employeeRole = Role::create([
            'name' => 'employee',
            'guard_name' => $guardName,
        ]);
        $employeeRole->givePermissionTo([
            'view_employee_dashboard',
            'view_events',
            'view_registrations',
            'upload_documents',
            'view_documents',
            'download_documents',
            'view_employee_details',
            'generate_certificates',
        ]);

        echo "Roles and permissions created successfully!\n";
    }
}
