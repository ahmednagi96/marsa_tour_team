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
        Schema::create('social_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_id')->constrained('socials')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('locale',10);
            $table->string('title');
            $table->unique(['social_id','title','locale']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_translations');
    }
};
