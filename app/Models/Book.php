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
        
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('s3');
        
        return $disk->url($this->cover);
    }
}
