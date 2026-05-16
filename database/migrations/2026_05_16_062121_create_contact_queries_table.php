<?php
// database/migrations/2024_01_03_000002_create_contact_queries_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contact_queries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('subject');
            $table->text('message');
            $table->string('ip_address')->nullable();
            $table->enum('status', ['pending', 'read', 'responded'])->default('pending');
            $table->foreignId('responded_by')->nullable()->constrained('employees')->onDelete('set null');
            $table->text('response')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contact_queries');
    }
};
