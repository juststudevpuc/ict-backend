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
        Schema::table('users', function (Blueprint $collection) {
            // 🛠️ Add the new 'role' column. Default everyone to 'student'.
            $collection->string('role')->default('student');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $collection) {
            // 🛠️ Drop the column if you ever need to rollback this migration
            $collection->dropColumn('role');
        });
    }
};
