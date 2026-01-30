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
        Schema::table('media_files', function (Blueprint $table) {
            // Add section field (default, men, women, accessories, footwear)
            $table->string('section')->default('default')->after('media_type');
            
            // Add banner link/URL field
            $table->string('banner_link')->nullable()->after('section');
            
            // Add enabled/disabled toggle
            $table->boolean('is_enabled')->default(true)->after('banner_link');
            
            // Add display order
            $table->integer('display_order')->default(0)->after('is_enabled');
            
            // Create index for section and enabled for faster queries
            $table->index(['section', 'is_enabled']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media_files', function (Blueprint $table) {
            $table->dropIndex(['section', 'is_enabled']);
            $table->dropColumn(['section', 'banner_link', 'is_enabled', 'display_order']);
        });
    }
};
