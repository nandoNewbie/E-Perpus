<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use HasFactory;
    //
    protected $fillable = [
        'title', 'author', 'publisher', 'published_place', 
        'published_year', 'edition', 'stock', 'language', 
        'isbn', 'description', 'cover', 'category', 'ddc'
    ];

    // Relasi untuk mengecek apakah buku sedang dipinjam
    public function borrowings()
    {
        // Sesuaikan 'Borrowing' dengan nama model transaksi peminjamanmu
        return $this->hasMany(Borrowing::class)->where('status', 'dipinjam'); 
    }
    /**
     * URL cover buku, atau null bila file gambarnya tidak ada.
     *
     * File hasil upload disimpan di storage/app/public dan sengaja tidak
     * ikut masuk Git, sehingga pada clone baru folder covers/ kosong
     * meskipun kolom `cover` di database terisi. Dengan mengecek keberadaan
     * file lebih dulu, tampilan jatuh ke placeholder "No Cover" yang sudah
     * ada, bukan menampilkan gambar rusak.
     */
    public function getCoverUrlAttribute(): ?string
    {
        if (! $this->cover || ! Storage::disk('public')->exists($this->cover)) {
            return null;
        }

        return asset('storage/' . $this->cover);
    }
}
