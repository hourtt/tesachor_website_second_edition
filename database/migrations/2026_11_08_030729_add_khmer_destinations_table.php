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
        Schema::table('destinations', function (Blueprint $table) {
            //* Basic Info
            $table->string('place_name_in_khmer')->after('place_name')->index()->nullable();
            $table->text('short_description_in_khmer')->after('short_description')->nullable();
            
            //* Detail page info
            $table->longText('place_description_in_khmer')->after('place_description')->nullable();
            $table->longText('place_history_in_khmer')->after('place_history')->nullable();
            $table->longText('place_information_in_khmer')->after('place_information')->nullable();
            $table->json('highlights_in_khmer')->after('highlights')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            //
            $table->dropColumn([
                'place_name_in_khmer',
                'short_description_in_khmer',
                'place_description_in_khmer',
                'place_history_in_khmer',
                'place_information_in_khmer',
                'highlights_in_khmer',
            ]);
        });
    }
};
