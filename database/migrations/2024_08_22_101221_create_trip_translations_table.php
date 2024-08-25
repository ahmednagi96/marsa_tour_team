<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trip_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trips')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('locale',10);
            $table->string('name');
            $table->text('description')->nullable();
            $table->unique(['trip_id','name','locale']);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('trip_translations');
    }
};
