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
        Schema::create('bookings', function (Blueprint $table) {

            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('tour_id')->constrained();
            $table->foreignId('tour_availability_id')->constrained(); // ربط مباشر باليوم المختار

            $table->date('travel_date');
            $table->integer('adults_count');
            $table->integer('children_count');

            $table->decimal('adult_price', 10, 2); // السعر اللي اتحجز بيه فعلياً
            $table->decimal('child_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->string('status')->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
