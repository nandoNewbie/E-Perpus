<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel session bawaan Laravel.
     *
     * Tabel ini sempat dihapus dari migrasi create_users_table, sehingga
     * aplikasi terpaksa memakai SESSION_DRIVER=file. Di hosting berbasis
     * container (Railway, Render, Cloud Run) filesystem bersifat ephemeral,
     * jadi semua user otomatis ter-logout setiap kali deploy ulang.
     * Dengan tabel ini, SESSION_DRIVER=database bisa dipakai dan session
     * tetap aman saat deploy.
     */
    public function up(): void
    {
        if (Schema::hasTable('sessions')) {
            return;
        }

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
