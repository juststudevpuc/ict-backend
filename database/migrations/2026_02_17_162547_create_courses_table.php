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
        Schema::create('courses', function (Blueprint $collection) {
            $collection->id();
            $collection->string('title'); // For "Data Analytics"
            $collection->integer('duration_hours'); // For "48"
            $collection->text('description')->nullable();
            $collection->boolean('status')->default(true);
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
