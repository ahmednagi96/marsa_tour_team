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
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trips')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedInteger('duration')->comment('by days');
            $table->decimal('price', 10, 2)->index();
            $table->enum('discount_type', ['fixed', 'percentage'])->nullable();
            $table->decimal('discount_value', 10, 2)->nullable(); // القيمة سواء كانت 10% أو 500 ج
            $table->decimal('sale_price', 10, 2)->nullable()->index(); // السعر النهائي بعد الخصم (للسرعة)
            $table->timestamp('sale_start')->nullable()->index();
            $table->timestamp('sale_end')->nullable()->index();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_favourite')->default(0);
            $table->json('photos')->nullable();
            $table->string('video')->nullable();
            $table->index(['is_active', 'is_featured']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
