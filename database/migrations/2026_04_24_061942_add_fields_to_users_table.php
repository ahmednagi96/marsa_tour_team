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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->unique(); // Main identifier
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('provider_id')->nullable(); // For Socialite
            $table->string('provider_name')->nullable(); // google, facebook, etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(...['phone','phone_verified_at','provider_id','provider_name']);
        });
    }
};
