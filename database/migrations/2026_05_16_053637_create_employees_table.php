<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique();
            $table->string('name');
            $table->string('pan_number')->unique();
            $table->string('designation');
            $table->string('department');
            $table->foreignId('airport_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('region_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->date('date_of_birth');
            $table->string('email')->unique();
            $table->string('mobile')->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('sports_category')->nullable();
            $table->string('blood_group')->nullable();
            $table->text('medical_conditions')->nullable();
            $table->enum('employment_status', ['active', 'inactive', 'retired', 'transferred'])->default('active');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('force_password_change')->default(true);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
