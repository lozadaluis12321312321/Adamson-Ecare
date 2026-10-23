<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('health_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category'); // General Wellness, Mental Well-Being, Dental Health, Hygiene and Prevention, Healthy Lifestyle, Preventive Healthcare
            $table->text('summary');
            $table->longText('content');
            $table->string('author_name')->default('AdU Health Services Office');
            $table->string('image_url')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['category', 'is_published']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('health_articles');
    }
};
