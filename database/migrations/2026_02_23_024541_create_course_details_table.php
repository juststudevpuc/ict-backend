<?php

use Illuminate\Database\Migrations\Migration;
use MongoDB\Laravel\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('course_details', function (Blueprint $collection) {
            $collection->id();
            // 1. Foreign Keys (Saved as strings in MongoDB)
            $collection->objectId('course_id');
            $collection->objectId('schedule_id');

            // 2. Add Indexes for blazing-fast database queries!
            $collection->index('course_id');
            $collection->index('schedule_id');
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_details');
    }
};
