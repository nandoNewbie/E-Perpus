<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentClassImport implements ToCollection, WithHeadingRow
{
    /**
     * Kita ubah dari ToModel menjadi ToCollection agar bisa mengontrol query dengan fleksibel
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // 1. Lewati jika kolom nomor_induk atau kelas_baru kosong
            if (empty($row['nomor_induk']) || empty($row['kelas_baru'])) {
                continue;
            }

            // 2. Hilangkan kemungkinan format aneh Excel (mengubah ke string bersih)
            $identityNumber = trim((string)$row['nomor_induk']);
            $newClass = trim((string)$row['kelas_baru']);

            // 3. Cari siswa di database yang identity_number-nya cocok
            $user = User::where('identity_number', $identityNumber)->first();

            // 4. Jika siswa ditemukan, update kelasnya saja
            if ($user) {
                $user->update([
                    'class' => $newClass
                ]);
            }
            // Jika tidak ditemukan, abaikan saja (tidak akan memaksakan INSERT baru)
        }
    }
}