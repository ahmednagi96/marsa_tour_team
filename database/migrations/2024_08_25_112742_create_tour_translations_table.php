<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */public function up(): void
{
    Schema::create('tour_translations', function (Blueprint $table) {
        $table->id();
        
        // ربط الجدول مع الفلترة السريعة
        $table->foreignId('tour_id')->constrained('tours')->cascadeOnDelete();
        
        // تقليل طول الـ locale وتحسين الفلترة
        $table->string('locale', 5)->index(); 
        
        // إضافة index للبحث بالاسم والمدينة والدولة
        $table->string('name')->index(); 
        $table->string('country')->nullable()->index();
        $table->string('city')->nullable()->index();
        
        $table->string('street')->nullable();
        $table->text('description')->nullable();

        // الـ JSON بدون index (لأن البحث جواه مكلف جداً)
        $table->json('services')->nullable();
        $table->json('additional_data')->nullable();

        // ضمان عدم تكرار اللغة لنفس الجولة (مهم جداً للـ Translatable package)
        $table->unique(['tour_id', 'locale']);
        
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_translations');
    }
};
