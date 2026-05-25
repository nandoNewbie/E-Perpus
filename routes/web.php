<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', function () {
        // Jika yang login Pustakawan, arahkan ke rute khusus pustakawan
        if (Auth::user()->role === 'pustakawan') {
            return redirect()->route('pustakawan.dashboard');
        }
        // Jika siswa/guru, biarkan melihat halaman dashboard utama
        return view('dashboard');
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

require __DIR__.'/auth.php';
