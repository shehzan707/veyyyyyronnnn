<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admin_products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('category')->constrained('categories')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('admin_products', function (Blueprint $table) {
            $table->dropForeignIdFor('Category');
        });
    }
};
