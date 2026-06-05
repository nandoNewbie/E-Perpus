<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;


class StudentClassExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * 1. Ambil data siswa saja (Asumsi di database kamu ada kolom role, atau sesuaikan filtermu)
     */
    public function query()
    {
        // Mengambil user yang rolenya 'siswa' dan kelasnya bukan 'Alumni'
        return User::query()->where('role', 'siswa')->where('class', '!=', 'Alumni');
    }

    /**
     * 2. Atur Header (Baris Pertama) di file Excel nanti
     */
    public function headings(): array
    {
        return [
            'nomor_induk',
            'nama_siswa',
            'kelas_lama',
            'kelas_baru', // Kolom kosong ini sengaja dibuat untuk diisi manual oleh Admin nanti
        ];
    }

    /**
     * 3. Masukkan data ke masing-masing kolom di atas
     */
    public function map($user): array
    {
        return [
            $user->identity_number,
            $user->name,
            $user->class,
            '', // Kosongkan kolom kelas baru, agar Admin tinggal mengetik di sini
        ];
    }
}