<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

 public function up(): void
    {
     #   Schema::create('users', function (Blueprint $table) {
      #      $table->id();
       #     $table->string('name')->nullable();
        #    $table->string('email')->unique()->nullable();
         #   $table->timestamp('email_verified_at')->nullable();
          #  $table->string('password')->nullable();
          //  $table->string('country')->nullable();
         //   $table->string('country_code',99)->nullable();
         //   $table->string('phone',30)->unique();
         //   $table->string('photo')->nullable();
         //   $table->string('code')->nullable();
          //  $table->timestamp('expired_at')->nullable();
         //   $table->string('fcm_token',1024)->nullable();
         #   $table->rememberToken();
         #   $table->timestamps();
       # });
       Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('phone')->unique(); // Main identifier
        $table->timestamp('phone_verified_at')->nullable();
        $table->string('email')->nullable()->unique(); // Optional for social login
        $table->string('password')->nullable(); // Nullable for social users
        $table->string('provider_id')->nullable(); // For Socialite
        $table->string('provider_name')->nullable(); // google, facebook, etc.
        $table->rememberToken();
        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
