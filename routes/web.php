<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Livewire\Admin\UserManagement;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WelcomeController;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\BookManagement;
use App\Livewire\Admin\BorrowingManagement;
use App\Livewire\Admin\ReturnManagement;
use App\Http\Controllers\VirtualTourController;

// Welcome
Route::get('/', WelcomeController::class)->name('welcome');

// Auth routes (siswa/guru)
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Auth routes (admin)
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);

// Siswa/Guru routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/daftar-buku', [DashboardController::class, 'katalog'])->name('books.index');
    Route::get('/daftar-buku/{id}', [DashboardController::class, 'show'])->name('books.show');
    Route::post('/daftar-buku/{id}/pinjam', [DashboardController::class, 'pinjam'])->name('books.pinjam');
    Route::get('/riwayat-peminjaman', [DashboardController::class, 'riwayat'])->name('books.riwayat');
    Route::view('/faq', 'faq')->name('faq');
    Route::get('/virtual-tour', [VirtualTourController::class, 'index'])->name('virtual-tour');
    Route::get('/profil-perpustakaan', [ProfileController::class, 'index'])->name('profile-perpustakaan');
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['admin.auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');       // /admin/dashboard
    Route::get('/buku', BookManagement::class)->name('books');           // /admin/buku
    Route::get('/users', UserManagement::class)->name('users');          // /admin/users  ✅ fixed
    Route::get('/peminjaman', BorrowingManagement::class)->name('borrowings'); // /admin/peminjaman
    Route::get('/pengembalian', ReturnManagement::class)->name('returns');     // /admin/pengembalian
});

Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

require __DIR__.'/auth.php';