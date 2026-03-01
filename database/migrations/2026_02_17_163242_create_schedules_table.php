<?php

use Illuminate\Database\Migrations\Migration;
use MongoDB\laravel\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $collection) {
            $collection->id();
            $collection->string('shift');
            $collection->date('start_date');
            $collection->string('days_of_week');
            $collection->time('start_time');
            $collection->time('end_time');
            $collection->boolean('status')->default(true);

            $collection->objectId('course_id');
            $collection->index('course_id');
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
