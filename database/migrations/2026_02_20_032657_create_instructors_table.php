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
        Schema::create('instructors', function (Blueprint $collection) {
            $collection->id(); // This creates the MongoDB _id

            $collection->string('first_name');
            $collection->string('last_name');
            $collection->string('email')->nullable(); // Ensures no duplicate emails
            $collection->string('phone')->nullable();
            $collection->string('specialization')->nullable();
            // $collection->string('profile_picture')->nullable();

            // ✅ You were right! In MongoDB, we store relationships as arrays of IDs
            // This allows Mr. Sok to teach multiple courses without a pivot table.
            $collection->array('course_ids')->nullable();

            $collection->boolean('status')->default(true);
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructors');
    }
};
