<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE borrowings MODIFY COLUMN status ENUM('Pending', 'Diterima', 'Ditolak', 'Dikembalikan', 'Expired') DEFAULT 'Pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE borrowings MODIFY COLUMN status ENUM('Pending', 'Diterima', 'Ditolak', 'Dikembalikan') DEFAULT 'Pending'");
    }
};