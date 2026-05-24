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
    Schema::create('borrowings', function (Blueprint $table) {
        $table->id();
        // Menghubungkan ke tabel users (siapa yang meminjam)
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        // Menghubungkan ke tabel books (buku apa yang dipinjam)
        $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
        
        $table->enum('status', ['Pending', 'Diterima', 'Ditolak', 'Dikembalikan'])->default('Pending');
        $table->date('borrow_date')->nullable(); // Diisi otomatis saat admin klik 'Diterima'
        $table->date('due_date')->nullable(); // Otomatis set 7 hari setelah borrow_date
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
