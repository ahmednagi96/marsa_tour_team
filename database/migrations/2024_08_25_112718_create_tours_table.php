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
            $table->decimal('price',12,2)->default(0.00);
            $table->boolean('is_active')->default(1);
            $table->boolean('is_favourite')->default(0);
            $table->json('photos')->nullable();
            $table->string('video')->nullable();
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
