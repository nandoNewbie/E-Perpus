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
    public function getCoverUrlAttribute(): ?string
    {
        if (!$this->cover) return null;
        
        $publicUrl = 'https://pub-eda10e471fff43c8b6b3e22b66478998.r2.dev';
        
        return $publicUrl . '/' . $this->cover;
    }
}
