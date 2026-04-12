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
        Schema::create('tour_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->onDelete('cascade');
            
            $table->date('date')->index(); 
            
            $table->integer('capacity')->default(20);
            $table->integer('booked')->default(0);  
            
            $table->decimal('adult_price', 10, 2); 
            $table->decimal('child_price', 10, 2)->nullable(); 
            
          #  $table->string('badge')->nullable(); 
            
            $table->boolean('is_active')->default(true)->index();
            
            $table->unique(['tour_id', 'date']); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_availabilities');
    }
};
