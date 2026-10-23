<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('health_inquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('category'); // General Health, Clinic Services & Hours, Vaccination, Medical Records, Pharmacy
            $table->string('subject');
            $table->text('message');
            $table->string('status')->default('submitted'); // submitted, in_progress, awaiting_user_response, resolved, closed
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('health_inquiries');
    }
};
