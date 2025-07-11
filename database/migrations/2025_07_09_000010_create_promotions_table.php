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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('code')->nullable()->unique();
            $table->text('description')->nullable();

            $table->enum('type', ['percentage', 'fixed', 'free_shipping', 'gift', 'custom'])->default('percentage');
            $table->decimal('value', 15, 2)->nullable();

            $table->integer('usage_limit')->nullable();
            $table->integer('used')->default(0);

            $table->boolean('is_active')->default(true);
            $table->boolean('is_public')->default(true);

            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
