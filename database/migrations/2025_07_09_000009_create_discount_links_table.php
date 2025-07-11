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
        Schema::create('discount_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('discountable_id');
            $table->string('discountable_type');
            $table->decimal('discount_amount', 10, 2)->nullable(); // Chỉ dùng cho Order nếu cần
            $table->timestamps();

            $table->unique(['discount_id', 'discountable_type', 'discountable_id'], 'discount_links_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_links');
    }
};
