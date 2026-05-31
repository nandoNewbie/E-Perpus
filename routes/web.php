<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\AdminAuthController;
use Illuminate\Support\Facades\Auth;
use App\Imports\BooksImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WelcomeController;
use App\Livewire\Admin\Dashboard;

Route::get('/', WelcomeController::class)->name('welcome');

// Login User Biasa (Siswa/Guru)
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Login Admin/Pustakawan
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);


Route::get('/import-buku', function () {
    Excel::import(new BooksImport, storage_path('app/data_buku.xlsx'));
    return "Data Excel sukses masuk database beneran!";
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Rute dashboard untuk non-admin (siswa/guru)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rute khusus admin/pustakawan
    Route::middleware(['role:pustakawan'])->prefix('admin')->group(function () {
        Route::get('/dashboard', Dashboard::class)->name('admin.dashboard');
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

// Rute Dashboard Admin (DIBUNGKUS middleware)
Route::middleware(['admin.auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('admin.dashboard');
});


require __DIR__.'/auth.php';
