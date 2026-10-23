<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('appointment_schedules', function (Blueprint $table) {
            $table->id();
            $table->date('schedule_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('max_slots')->default(4);
            $table->boolean('is_active')->default(true);
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->unique(['schedule_date', 'start_time']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointment_schedules');
    }
};
