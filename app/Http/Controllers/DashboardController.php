<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\Borrowing;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function katalog(Request $request)
{
    // 1. Mengambil query pencarian dan filter dari URL
    $search = $request->input('search');
    $ddcFilter = $request->input('ddc');

    $query = Book::query();

    // 2. Logic Pencarian
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('title', 'LIKE', "%{$search}%")
            ->orWhere('author', 'LIKE', "%{$search}%")
            ->orWhere('isbn', 'LIKE', "%{$search}%");
        });
    }

    // 3. Logic Filter DDC
    if ($ddcFilter) {
        $query->where('ddc', $ddcFilter);
    }

    // 4. Ambil data buku terbaru & list DDC unik
    $books = $query->latest()->get();
    $categories = Book::pluck('ddc')->unique()->filter()->values();

    // 5. Lempar ke file view baru bernama 'books.index'
    return view('books.index', compact('books', 'categories'));
}

public function show(int $id)
{
    // Ambil data buku berdasarkan ID, jika tidak ada tampilkan 404
    $book = Book::findOrFail($id);
    return view('books.show', compact('book'));
}

public function pinjam(Request $request, int $id)
{
    $user = Auth::user(); // Ambil data user yang sedang login
    $book = Book::findOrFail($id);

    // 1. VALIDASI: Cek apakah stok buku masih ada
    if ($book->stock <= 0) {
        return redirect()->back()->with('error', 'Maaf, stok buku ini sudah habis!');
    }

    // 2. VALIDASI: Cek apakah user sudah meminjam/request buku yang SAMA dan belum dikembalikan
    $alreadyBorrowed = Borrowing::where('user_id', $user->id)
        ->where('book_id', $id)
        ->whereIn('status', ['Pending', 'Diterima'])
        ->exists();

    if ($alreadyBorrowed) {
        return redirect()->back()->with('error', 'Kamu sudah meminta atau sedang meminjam buku ini!');
    }

    // 3. VALIDASI: Cek batas maksimal pinjam (Maksal 3 jenis buku aktif)
    $activeBorrowingCount = Borrowing::where('user_id', $user->id)
        ->whereIn('status', ['Pending', 'Diterima'])
        ->count();

    if ($activeBorrowingCount >= 3) {
        return redirect()->back()->with('error', 'Gagal! Kamu sudah mencapai batas maksimal peminjaman (Maks 3 buku).');
    }

    // 4. PROSES: Gunakan Database Transaction agar aman (Stok berkurang & History tercipta bersamaan)
    DB::transaction(function () use ($user, $book) {
        // Kurangi stok buku
        $book->decrement('stock');

        // Buat data di tabel borrowings
        Borrowing::create([
            'user_id'     => $user->id,
            'book_id'     => $book->id,
            'status'      => 'Pending',
            'borrow_date' => Carbon::now(),
            'due_date'    => Carbon::now()->addDays(7), // Batas waktu pinjam default 7 hari
        ]);
    });

    return redirect()->back()->with('success', 'Request peminjaman berhasil dikirim! Menunggu persetujuan pustakawan.');
}

public function riwayat()
{
    $user = Auth::user();

    // Kita gunakan eager loading (with('book')) agar bisa memanggil data judul buku nanti
    $borrowings = Borrowing::with('book')
        ->where('user_id', $user->id)
        ->latest()
        ->get();

    return view('books.riwayat', compact('borrowings'));
}
}
