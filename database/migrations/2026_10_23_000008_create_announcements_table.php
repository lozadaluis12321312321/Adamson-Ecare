<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title');
            $table->string('category'); // Urgent Advisory, Clinic Schedule Update, Health Advisory, Vaccination Drive, General Announcement
            $table->string('priority')->default('medium'); // low, medium, high, urgent
            $table->text('content');
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->timestamps();

            $table->index(['is_published', 'priority']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('announcements');
    }
};
