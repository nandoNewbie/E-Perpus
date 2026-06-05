<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Kita pertahankan kapitalisasi aslimu, dan kita tambahkan 'Expired' di dalamnya
        DB::statement("ALTER TABLE borrowings MODIFY COLUMN status ENUM('Pending', 'Diterima', 'Ditolak', 'Dikembalikan', 'Expired') DEFAULT 'Pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke struktur awal jika di-rollback
        DB::statement("ALTER TABLE borrowings MODIFY COLUMN status ENUM('Pending', 'Diterima', 'Ditolak', 'Dikembalikan') DEFAULT 'Pending'");
    }
};