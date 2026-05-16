<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FreshSeedCommand extends Command
{
    protected $signature = 'rscb:fresh';
    protected $description = 'Fresh migrate and seed the database for RSCB';

    public function handle()
    {
        $this->info('Starting fresh database setup...');

        // Run fresh migration
        $this->call('migrate:fresh', ['--force' => true]);

        // Clear caches
        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('view:clear');

        // Create storage link
        $this->call('storage:link');

        // Run seeders
        $this->call('db:seed', ['--force' => true]);

        // Create placeholder images
        $this->call('rscb:placeholders');

        $this->info('Database has been fresh seeded successfully!');
        $this->info('');
        $this->info('Login Credentials:');
        $this->info('  Super Admin: RSCB001 / Admin@1234');
        $this->info('  Regional Secretary: RSCB002 / Secretary@1234');
        $this->info('  Airport Secretary: DEL001 / Airport@1234');
        $this->info('  Employee: DEL0001 / Employee@1234');
    }
}
