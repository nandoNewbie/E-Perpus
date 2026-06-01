<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class UsersImport implements ToModel, WithHeadingRow, WithUpserts
{
    public function model(array $row)
    {
        return new User([
            'name'     => trim($row['nama']),
            'class'    => trim($row['kelas']),
            'identity_number' => trim($row['nomor_induk']),
            // Berikan password default (misal: password123) jika ini anggota baru
            'password' => Hash::make('password123'), 
            'role'     => trim($row['role'] ?? 'siswa'),
        ]);
    }

    /**
     * Tentukan kolom yang menjadi acuan unik untuk Upsert
     */
    public function uniqueBy()
    {
        return 'identity_number'; 
    }
}
