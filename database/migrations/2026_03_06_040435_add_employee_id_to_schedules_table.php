<?php

use Illuminate\Database\Migrations\Migration;
use MongoDB\Laravel\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Notice we use Schema::table() here, not Schema::create()
        Schema::table('schedules', function (Blueprint $collection) {
            $collection->objectId('employee_id')->nullable();
            $collection->index('employee_id');
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $collection) {
            // This allows you to rollback if you make a mistake
            $collection->dropIndex('employee_id');
            $collection->dropColumn('employee_id');
        });
    }
};
