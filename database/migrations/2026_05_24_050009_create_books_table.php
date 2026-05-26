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
    Schema::create('books', function (Blueprint $table) {
        $table->id();
        $table->string('title');            // Judul Buku
        $table->string('author');           // Penulis
        $table->string('publisher')->nullable();        // Penerbit
        $table->string('published_place')->nullable();  // Tempat terbit
        $table->year('published_year')->nullable();     // Tahun terbit
        $table->string('edition')->nullable(); // Edisi / Cetakan
        $table->integer('stock')->default(0);  // JML (Stok)
        $table->string('language')->nullable();         // Bahasa
        $table->string('isbn')->nullable(); // ISBN/ISSN
        $table->text('description')->nullable(); // Deskripsi
        $table->string('cover')->nullable(); // Sampul Buku (Kosong dulu)
        $table->string('category');         // Kategori untuk filter
        $table->string('ddc')->nullable(); // Kode DDC untuk klasifikasi
        $table->timestamps();
    });

}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
