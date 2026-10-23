<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('teleconsultation_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('preferred_date');
            $table->string('preferred_time');
            $table->string('category'); // General Health, Mental Well-Being, Nutritional Advice, etc.
            $table->text('description');
            $table->string('preferred_contact_method'); // Video Meeting, Phone Call, In-App
            $table->string('contact_details');
            $table->string('status')->default('pending'); // pending, approved, scheduled, completed, rejected, cancelled
            $table->string('meeting_link')->nullable();
            $table->string('assigned_personnel')->nullable();
            $table->text('admin_notes')->nullable();
            $table->dateTime('scheduled_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('teleconsultation_requests');
    }
};
