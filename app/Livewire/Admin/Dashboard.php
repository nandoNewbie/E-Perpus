<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;

#[Layout('layouts.admin-layout')]
class Dashboard extends Component
{
#[Layout('layouts.admin-layout')]
public function render()
{
    // Mengambil data peminjaman yang dikelompokkan per bulan untuk tahun ini (2026)
    $peminjamanPerBulan = Borrowing::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
        ->whereYear('created_at', date('Y'))
        ->groupBy('bulan')
        ->pluck('total', 'bulan')
        ->toArray();

    // Menyiapkan array 12 bulan dengan nilai default 0
    $dataBulanan = [];
    for ($i = 1; $i <= 12; $i++) {
        $dataBulanan[] = $peminjamanPerBulan[$i] ?? 0;
    }

    return view('livewire.admin.dashboard', [
        'totalBuku' => Book::sum('stock'),
        'bukuDipinjam' => Borrowing::where('status', 'Diterima')->count(),
        'totalAnggota' => User::where('role', 'siswa')->count(),
        'totalKeterlambatan' => Borrowing::where('status', 'Diterima')
                                            ->where('due_date', '<', date('Y-m-d'))
                                            ->count(),
        'dataGrafik' => [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            'jumlah' => $dataBulanan // Data riil dari database
        ]
    ]);
}
}