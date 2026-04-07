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
        Schema::create('contactus_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contactus_id')->constrained('contactus')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('locale',10);
            $table->string('name');
            $table->unique(['contactus_id','name','locale']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contactus_translations');
    }
};
