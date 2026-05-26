<?php

namespace App\Imports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Gunakan ini jika baris pertama Excel adalah Judul Kolom

class BooksImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // JIKA KOLOM JUDUL BUKU KOSONG, LANGSUNG ABAIKAN (TIDAK USAH DI-INSERT)
        if (empty($row['judul_buku'])) {
            return null;
        }
        return new Book([
            // 'kolom_database' => $row['nama_header_di_excel_kamu']
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
            'category'        => 'Umum',
            'ddc'             => $row['ddc'],
            'cover'           => null, 
        ]);
    }
}
