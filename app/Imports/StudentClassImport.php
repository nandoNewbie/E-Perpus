<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Exception; // Tambahkan ini untuk melempar pesan error

class StudentClassImport implements ToCollection, WithHeadingRow
{
    private array $whitelistKelas = [
        '7A', '7B', '7C', '7D', '7E', '7F', '7G', '7H', '7I',
        '8A', '8B', '8C', '8D', '8E', '8F', '8G', '8H', '8I',
        '9A', '9B', '9C', '9D', '9E', '9F', '9G', '9H', '9I',
    ];

    public function collection(Collection $rows)
    {
        // === TAHAP 1: VALIDASI SEMUA BARIS (WHITELIST FILTER) ===
        // Kita hitung nomor baris riil di Excel (baris 1 adalah header, data mulai dari baris 2)
        $barisKe = 2; 

        foreach ($rows as $row) {
            // FIX: Ambil data dan pastikan tipenya string dengan aman
            $nomorInduk = trim((string) ($row['nomor_induk'] ?? ''));
            $kelasBaru  = trim((string) ($row['kelas_baru'] ?? ''));

            // FIX UTAMA: Jika baris ini tidak punya nomor induk ATAU kelas baru kosong, 
            // anggap ini baris kosong/lewat dan langsung skip ke baris berikutnya (Jangan dilempar error)
            if (empty($nomorInduk) || empty($kelasBaru)) {
                $barisKe++;
                continue;
            }

            // Bersihkan input kelas baru untuk pengecekan whitelist
            $newClass = strtoupper(str_replace(' ', '', $kelasBaru));

            // Jika baris ada isinya tapi TIDAK SESUAI whitelist, baru STOP TOTAL
            if (!in_array($newClass, $this->whitelistKelas)) {
                throw new Exception(
                    "Gagal melakukan Bulk Update! Format kelas '{$kelasBaru}' pada Baris ke-{$barisKe} tidak valid atau tidak terdaftar dalam SOP sekolah (7A - 9I). Hubungkan kembali dengan format yang benar."
                );
            }

            $barisKe++;
        }


        // === TAHAP 2: EKSEKUSI UPDATE DATABASE (Hanya jalan jika Tahap 1 Lolos 100%) ===
        foreach ($rows as $row) {
            if (empty($row['nomor_induk']) || empty($row['kelas_baru'])) {
                continue;
            }

            $identityNumber = trim((string) $row['nomor_induk']);
            $newClass = strtoupper(str_replace(' ', '', trim((string) $row['kelas_baru'])));

            $user = User::where('identity_number', $identityNumber)->first();

            if ($user) {
                $user->update(['class' => $newClass]);
            }
        }
    }
}