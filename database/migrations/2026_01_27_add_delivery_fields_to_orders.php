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
            $table->string('address_type')->nullable()->after('pincode'); // home or office
            $table->string('delivery_time')->nullable()->after('address_type'); // 9-5, 10-6, 9-1, 2-6
            $table->boolean('open_saturday')->default(false)->after('delivery_time');
            $table->boolean('open_sunday')->default(false)->after('open_saturday');
            $table->json('payment_details')->nullable()->after('payment_status'); // Store payment details as JSON
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('address_type');
            $table->dropColumn('delivery_time');
            $table->dropColumn('open_saturday');
            $table->dropColumn('open_sunday');
            $table->dropColumn('payment_details');
        });
    }
};
