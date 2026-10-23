<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dental_appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('appointment_schedule_id')->nullable()->constrained('appointment_schedules')->onDelete('set null');
            $table->date('appointment_date');
            $table->string('appointment_time');
            $table->string('reason_for_visit');
            $table->text('specific_concerns')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rescheduled, rejected, cancelled, completed
            $table->date('rescheduled_date')->nullable();
            $table->string('rescheduled_time')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['appointment_date', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('dental_appointments');
    }
};
