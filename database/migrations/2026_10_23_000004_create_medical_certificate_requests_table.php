<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medical_certificate_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('certificate_type'); // Fit to Return, Excuse Slip Validation, Sports/PE, OJT/Internship, etc.
            $table->date('consultation_date')->nullable();
            $table->string('purpose_of_request');
            $table->text('additional_information')->nullable();
            $table->string('supporting_document_path')->nullable();
            $table->string('supporting_document_name')->nullable();
            $table->string('status')->default('submitted'); // submitted, under_review, additional_info_required, approved, ready_for_release, released, rejected
            $table->string('certificate_number')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('medical_certificate_requests');
    }
};
