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
        $table->string('title');
        $table->string('author');
        $table->string('publisher');
        $table->year('published_year');
        $table->integer('stock');
        $table->string('ddc_code', 3); // Kode DDC 3 digit (000-900)
        $table->enum('category_type', ['Komputer', 'Filsafat', 'Agama', 'Sosial', 'Bahasa', 'Sains', 'Teknologi', 'Seni', 'Sastra', 'Geografi']); // Klasifikasi Besar DDC
        $table->string('location_image'); // Menyimpan nama file gambar denah 3D
        $table->text('description')->nullable(); // Sinopsis/Deskripsi, boleh kosong
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
