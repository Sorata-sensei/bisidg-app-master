<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom baru.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // tambahkan setelah kolom no_telepon biar rapih
            $table->string('no_telepon_orangtua', 20)->nullable()->after('no_telepon');
            
            // tambahkan setelah kolom is_counseling
            $table->boolean('is_edited')->default(false)->after('is_counseling');
        });
    }

    /**
     * Rollback kolom jika dibatalkan.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['no_telepon_orangtua', 'is_edited']);
        });
    }
};