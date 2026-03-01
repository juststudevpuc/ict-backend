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
        Schema::table('schedules', function (Blueprint $collection) {
            // Add the new descriptive fields
            $collection->string('group_name');
            $collection->string('room')->nullable();
            $collection->date('end_date');

            // Add the instructor reference and index it for fast searching
            $collection->objectId('instructor_id')->nullable();
            $collection->index('instructor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $collection) {
            // Drop the columns if we ever need to rollback this migration
            $collection->dropColumn([
                'group_name',
                'room',
                'end_date',
                'instructor_id'
            ]);
        });
    }
};
