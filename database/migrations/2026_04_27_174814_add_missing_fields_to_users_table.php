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
        // الحقول اللي كانت ناقصة في الـ Migration الأصلي بتاعك
        if (!Schema::hasColumn('users', 'country_id')) {
            $table->foreignId("country_id")->nullable()->constrained("countries");
        }
       
        if (!Schema::hasColumn('users', 'avatar')) {
            $table->string('avatar')->nullable()->after('phone');
        }
        if (!Schema::hasColumn('users', 'fcm_token')) {
            $table->text('fcm_token')->nullable()->after('photo');
        }
        if (!Schema::hasColumn('users', 'phone_verified_at')) {
            $table->timestamp('phone_verified_at')->nullable()->after('phone');
        }
        // حالات الحساب
       // $table->boolean('is_active')->default(true);
        //$table->timestamp('last_login_at')->nullable();
        // $table->softDeletes();
        
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
