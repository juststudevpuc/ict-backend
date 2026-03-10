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
        Schema::create('employees', function (Blueprint $collection) {
            $collection->id();
            $collection->string("full_name");
            $collection->string("gender");
            $collection->string("phone");
            $collection->string("address");
            $collection->string("birthday");
            $collection->string("email")->unique();
            $collection->string("position");
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
