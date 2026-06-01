<?php

namespace App\Imports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class BooksImport implements ToModel, WithHeadingRow, WithUpserts
{
    public function model(array $row)
    {
        // JIKA KOLOM JUDUL BUKU KOSONG, LANGSUNG ABAIKAN (TIDAK USAH DI-INSERT)
        if (empty($row['judul_buku'])) {
            return null;
        }

        return new Book([
            'title'           => $row['judul_buku'],
            'author'          => $row['penulis'],
            'publisher'       => $row['penerbit'],
            'published_place' => $row['tempat_terbit'],
            'published_year'  => $row['tahun_terbit'],
            'edition'         => $row['edisi_cetakan'],
            'stock'           => $row['jml'],
            'language'        => $row['bahasa'],
            'isbn'            => $row['isbn_issn'],
            'description'     => $row['deskripsi'],
            'category'        => $row['kategori'] ?? 'Umum',
            'ddc'             => $row['ddc'],
            'cover'           => null, 
        ]);
    }
    public function uniqueBy()
    {
        return ['title', 'author']; // Gunakan kolom 'title' dan 'author' sebagai acuan untuk update jika data sudah ada
    }
}
