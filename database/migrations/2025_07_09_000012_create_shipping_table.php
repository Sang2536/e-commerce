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
        Schema::create('shipping', function (Blueprint $table) {
            $table->id();

            $table->foreignId('shipping_method_id')->constrained()->onDelete('set null')->nullable();

            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->string('address');
            $table->string('district')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->default('Việt Nam');

            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->string('tracking_code')->nullable(); // Mã vận đơn
            $table->string('status')->default('pending'); // pending, shipping, delivered, failed

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping');
    }
};
