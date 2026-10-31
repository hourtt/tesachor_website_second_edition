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
        Schema::table('provinces', function (Blueprint $table) {
            $table->unique('name');
        });

        Schema::table('destinations', function (Blueprint $table) {
            $table->unique('place_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('provinces', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });

        Schema::table('destinations', function (Blueprint $table) {
            $table->dropUnique(['place_name']);
        });
    }
};
