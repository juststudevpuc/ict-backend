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
        Schema::create('students', function (Blueprint $collection) {
            $collection->id();
            $collection->string("full_name");
            $collection->string("gender");
            $collection->string("phone");

            $collection->string("email")->nullable();
            $collection->date("date_of_birth")->nullable();
            $collection->date("address")->nullable();
            $collection->boolean("status")->default(true);
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
