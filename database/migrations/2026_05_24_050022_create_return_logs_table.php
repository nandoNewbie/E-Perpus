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
    Schema::create('return_logs', function (Blueprint $table) {
        $table->id();
        // Menghubungkan ke transaksi peminjaman terkait
        $table->foreignId('borrowing_id')->constrained('borrowings')->onDelete('cascade');
        
        $table->date('return_date'); // Tanggal aktual pengembalian fisik
        $table->integer('late_days')->default(0); // Hitungan hari keterlambatan
        $table->integer('fine_amount')->default(0); // Hitungan total denda (late_days * 500)
        $table->enum('fine_status', ['none', 'unpaid', 'paid'])->default('none');
        
        // Menghubungkan ke tabel users untuk mencatat pustakawan yang memverifikasi
        $table->foreignId('pustakawan_id')->constrained('users')->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_logs');
    }
};
