<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;

class Dashboard extends Component
{
    protected $layout = 'layouts.admin-layout';

    public function render()
    {
        $totalBuku = Book::sum('stock'); 
        $bukuDipinjam = Borrowing::count('id');
        $totalAnggota = User::where('role', 'siswa')->count(); 
        $totalKeterlambatan = 0; // Set dummy dulu

        // 2. Siapkan data untuk grafik Chart.js
        $dataGrafik = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            'jumlah' => [12, 19, 3, 5, 2, 3, 15, 20, 30, 45, 35, 60]
        ];

        // HANYA ADA SATU RETURN DI PALING BAWAH METHOD
        return view('livewire.admin.dashboard', [
            'totalBuku' => $totalBuku,
            'bukuDipinjam' => $bukuDipinjam,
            'totalAnggota' => $totalAnggota,
            'totalKeterlambatan' => $totalKeterlambatan,
            'dataGrafik' => $dataGrafik
        ])->layout('layouts.admin-layout');
    }
}