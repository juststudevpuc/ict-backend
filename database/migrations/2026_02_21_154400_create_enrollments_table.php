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
        Schema::create('enrollments', function (Blueprint $collection) {
            $collection->id();
            $collection->objectId("student_id");
            $collection->index("student_id");

            $collection->objectId("course_id");
            $collection->index("course_id");

            $collection->objectId("schedule_id");
            $collection->index("schedule_id");

            $collection->string("enrollment_date");

            $collection->double("total_price");
            $collection->double('discount')->default(0);
            $collection->double('paid_amount')->default(0);
            $collection->string('payment_status')->default('Unpaid');

            $collection->boolean('status')->default(true);
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
