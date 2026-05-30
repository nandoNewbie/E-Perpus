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
    $categoryFilter = $request->input('category');

    $query = Book::query();

    // 2. Logic Pencarian
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('title', 'LIKE', "%{$search}%")
            ->orWhere('author', 'LIKE', "%{$search}%");
        });
    }

    // 3. Logic Filter Kategori
    if ($categoryFilter) {
        $query->where('category', $categoryFilter);
    }

    // 4. Ambil data buku terbaru & list Kategori unik untuk di dropdown filter
    $books = $query->latest()->get();
    $categories = Book::select('category')
                        ->whereNotNull('category')
                        ->groupBy('category')
                        ->pluck('category');

    // 5. Pagination (10 buku per halaman) dengan mempertahankan query string agar pencarian & filter tetap aktif saat pindah halaman
    $books = $query->latest()->get();

    // 6. Lempar ke file view baru bernama 'books.index'
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

public function index()
{
    // 1. Ambil 5 buku terbaru berdasarkan tanggal dibuat (created_at)
    $latestBooks = Book::latest()->take(5)->get();

    // 2. Ambil 5 buku terpopuler berdasarkan jumlah peminjaman terbanyak di tabel borrowings
    // Grouping berdasarkan book_id, lalu hitung totalnya
    $popularBooks = Book::select('books.*', DB::raw('COUNT(borrowings.id) as total_borrowed'))
        ->leftJoin('borrowings', 'books.id', '=', 'borrowings.book_id')
        ->groupBy('books.id', 'books.title', 'books.author', 'books.publisher', 'books.published_year', 'books.published_place', 'books.isbn', 'books.edition', 'books.language', 'books.category', 'books.ddc', 'books.stock', 'books.cover', 'books.description', 'books.created_at', 'books.updated_at') // Sebutkan kolom secara spesifik agar aman di mode SQL strict
        ->orderBy('total_borrowed', 'desc')
        ->take(5)
        ->get();

    // Kirim kedua data tersebut ke view dashboard bawaan kamu
    return view('dashboard', compact('latestBooks', 'popularBooks'));
}
}
