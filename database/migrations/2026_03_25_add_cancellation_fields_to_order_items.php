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
        Schema::table('order_items', function (Blueprint $table) {
            // Cancellation tracking fields
            $table->string('item_status')->default('Placed')->after('size');
            $table->string('cancel_reason')->nullable()->after('item_status');
            $table->timestamp('cancelled_at')->nullable()->after('cancel_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['item_status', 'cancel_reason', 'cancelled_at']);
        });
    }
};
