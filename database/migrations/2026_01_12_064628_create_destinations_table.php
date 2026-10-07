<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('province_id')->constrained()->onDelete('cascade'); //* Link to Province table

            //* Basic Info + small container (before get into detail page)
            $table->string('place_name');    //* e.g., "Angkor Wat"
            $table->string('slug')->unique(); //* e.g., "angkor-wat"
            $table->text('short_description');
            $table->string('thumbnail')->nullable();
            $table->boolean('is_recommended')->default(false); //* For "Recommended for you"
            $table->decimal('rating', 3, 1)->default(0.0);

            //* Detail Page Info
            $table->longText('place_description')->nullable();
            $table->longText('place_history')->nullable();
            $table->longText('place_information')->nullable();

            //* Highlight (JSON)
            $table->json('highlights')->nullable();

            //* Stores array of image paths for the carousel (JSON)
            $table->json('gallery_images')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
