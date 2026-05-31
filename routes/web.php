<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Imports\BooksImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WelcomeController;
use App\Livewire\Admin\Dashboard as AdminDashboard;

Route::get('/', WelcomeController::class)->name('welcome');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/import-buku', function () {
    Excel::import(new BooksImport, storage_path('app/data_buku.xlsx'));
    return "Data Excel sukses masuk database beneran!";
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', function () {
        // Jika yang login Pustakawan, arahkan ke rute khusus pustakawan
        if (Auth::user()->role === 'pustakawan') {
            return redirect()->route('pustakawan.dashboard');
        }
        // Jika siswa/guru, Panggil fungsi 'index' di DashboardController
        return app(DashboardController::class)->index();
    })->name('dashboard');

    // 2. KAMAR KHUSUS PUSTAKAWAN (DIKUNCI SATPAM ROLE)
    Route::middleware(['role:pustakawan'])->prefix('pustakawan')->group(function () {
        Route::get('/dashboard', function () {
            return view('pustakawan.dashboard'); // Nanti kita buat file view-nya
        })->name('pustakawan.dashboard');
        
        // Nanti rute manajemen buku, verifikasi denda dll akan ditulis di bawah sini
    });

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute katalog buku khusus siswa
Route::get('/daftar-buku', [DashboardController::class, 'katalog'])->name('books.index');

Route::middleware(['auth'])->group(function () {
    // Rute detail buku
    Route::get('/daftar-buku/{id}', [DashboardController::class, 'show'])->name('books.show');
    
    // Rute aksi pencatatan pinjam buku
    Route::post('/daftar-buku/{id}/pinjam', [DashboardController::class, 'pinjam'])->name('books.pinjam');

    Route::get('/riwayat-peminjaman', [DashboardController::class, 'riwayat'])->name('books.riwayat');
    Route::view('/faq', 'faq')->name('faq');
});

Route::middleware(['admin.auth'])->prefix('admin')->group(function () {
    // URL: /admin/dashboard
    Route::get('/dashboard', AdminDashboard::class)->name('admin.dashboard');
});



require __DIR__.'/auth.php';
