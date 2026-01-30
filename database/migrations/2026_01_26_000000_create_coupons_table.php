<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['percent', 'fixed']);
            $table->decimal('value', 8, 2);
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
        });
    }
    public function down() {
        Schema::dropIfExists('coupons');
    }
};
