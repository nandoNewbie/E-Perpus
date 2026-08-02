<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\Borrowing;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ReturnLog;

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

public function show(int $id)
{
    // Ambil data buku berdasarkan ID, jika tidak ada tampilkan 404
    $book = Book::findOrFail($id);
    return view('books.show', compact('book'));
}

public function pinjam(Request $request, int $id)
{
    $user = Auth::user();
    $book = Book::findOrFail($id);

    // =========================================================================
    // 0. Cek apakah pengguna memiliki denda yang BELUM LUNAS di return_logs
    // =========================================================================
    $hasUnpaidFine = ReturnLog::where('fine_status', 'Belum Lunas')
        ->whereHas('borrowing', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->exists();

    if ($hasUnpaidFine) {
        return redirect()->back()->with('error', 'Gagal mengajukan peminjaman! Kamu masih memiliki denda keterlambatan yang BELUM LUNAS. Silakan selesaikan pembayaran denda ke Pustakawan terlebih dahulu.');
    }

    // 1. Cek Kategori Referensi
    if (strtolower($book->category) === 'referensi') {
        return redirect()->back()->with('error', 'Gagal! Buku dengan kategori referensi hanya boleh dibaca di perpustakaan.');
    }

    // 2. Cek Stok Buku
    if ($book->stock <= 0) {
        return redirect()->back()->with('error', 'Maaf, stok buku ini sudah habis!');
    }

    // 3. Cek apakah buku yang sama sedang dipinjam / diajukan
    $alreadyBorrowed = Borrowing::where('user_id', $user->id)
        ->where('book_id', $id)
        ->whereIn('status', ['Pending', 'Diterima'])
        ->exists();

    if ($alreadyBorrowed) {
        return redirect()->back()->with('error', 'Kamu sudah meminta atau sedang meminjam buku ini!');
    }

    // 4. Hitung jumlah peminjaman aktif pengguna saat ini
    $activeCount = Borrowing::where('user_id', $user->id)
        ->whereIn('status', ['Pending', 'Diterima'])
        ->count();

    // 5. Tentukan batas maksimal limit secara dinamis berdasarkan role akun
    $maxBuku = 3; // Nilai default cadangan jika role tidak terdefinisi

    if ($user->role === 'siswa') {
        $maxBuku = 2;
    } elseif ($user->role === 'guru') {
        $maxBuku = 3;
    }

    // 6. Bandingkan jumlah peminjaman aktif dengan limit dinamis
    if ($activeCount >= $maxBuku) {
        return redirect()->back()->with('error', "Gagal! Batas maksimal peminjaman untuk " . ucfirst($user->role) . " adalah {$maxBuku} buku.");
    }   

    // 7. Jalankan transaksi database (Booking stok & Buat Request)
    DB::transaction(function () use ($user, $book) {
        $book->decrement('stock');

        Borrowing::create([
            'user_id'     => $user->id,
            'book_id'     => $book->id,
            'status'      => 'Pending',
            'borrow_date' => Carbon::now()->toDateString(),
            'due_date'    => Carbon::now()->addDays(7)->toDateString(),
        ]);
    });

    return redirect()->route('books.riwayat')->with('success', 'Request peminjaman berhasil dikirim! Stok telah di-booking.');
}

public function riwayat()
{
    return view('books.riwayat');
}
}
