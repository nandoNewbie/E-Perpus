<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('identity_number')->unique()->nullable(); // Untuk NIS/NIP Anggota
            $table->string('class')->nullable(); // Untuk Kelas Siswa (Guru & Pustakawan dikosongkan)
            $table->string('email')->unique()->nullable(); // Pustakawan wajib, Anggota boleh kosong
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['siswa', 'guru', 'pustakawan'])->default('siswa'); // Pembagian 3 Role
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};