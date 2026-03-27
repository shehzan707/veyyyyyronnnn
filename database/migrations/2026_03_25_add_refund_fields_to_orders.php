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
        Schema::table('orders', function (Blueprint $table) {
            // Refund tracking fields
            $table->string('refund_status')->default('Pending')->after('order_status');
            $table->decimal('refund_amount', 10, 2)->default(0)->after('refund_status');
            $table->timestamp('refund_initiated_at')->nullable()->after('refund_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['refund_status', 'refund_amount', 'refund_initiated_at']);
        });
    }
};
