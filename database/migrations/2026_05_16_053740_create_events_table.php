<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('event_name');
            $table->string('event_code')->unique();
            $table->text('description')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('venue');
            $table->foreignId('region_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('airport_id')->nullable()->constrained()->onDelete('set null');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->dateTime('registration_last_date');
            $table->enum('event_type', ['regional', 'airport', 'inter_airport', 'annual_meet']);
            $table->enum('participation_type', ['team', 'individual', 'both']);
            $table->integer('max_participants')->nullable();
            $table->enum('gender_eligibility', ['male', 'female', 'other', 'all']);
            $table->integer('min_age')->nullable();
            $table->integer('max_age')->nullable();
            $table->json('department_eligibility')->nullable();
            $table->text('rules_regulations')->nullable();
            $table->json('required_documents')->nullable();
            $table->enum('status', ['draft', 'published', 'cancelled', 'completed'])->default('draft');
            $table->boolean('is_published')->default(false);
            $table->foreignId('created_by')->constrained('employees')->onDelete('cascade');
            $table->foreignId('form_template_id')->nullable()->constrained('form_templates')->onDelete('set null');
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
};
