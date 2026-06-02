<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Borrowing;
use App\Models\Book;
use App\Models\ReturnLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin-layout')]
class ReturnManagement extends Component
{
    public $search = '';
    public $selectedBorrowingId = null;
    
    // Variabel Detail Form Pengembalian
    public $selectedBorrowing = null;
    public $returnDate;
    public $lateDays = 0;
    public $fineAmount = 0;
    public $fineStatus = null;

    public function mount()
    {
        // Set tanggal pengembalian default hari ini (waktu server local)
        $this->returnDate = Carbon::now()->toDateString();
    }

    public function updatedSearch()
    {
        // Reset pilihan jika admin mengetik ulang pencarian
        $this->selectedBorrowingId = null;
        $this->selectedBorrowing = null;
    }

    public function selectBorrowing($id)
    {
        $this->selectedBorrowingId = $id;
        $this->selectedBorrowing = Borrowing::with(['user', 'book'])->findOrFail($id);
        $this->search = $this->selectedBorrowing->user->name . ' - ' . $this->selectedBorrowing->book->title;
        
        $this->calculateFine();
    }

    public function calculateFine()
    {
        if (!$this->selectedBorrowing) return;

        $dueDate = Carbon::parse($this->selectedBorrowing->due_date);
        $returnDate = Carbon::parse($this->returnDate);

        // Jika tanggal pengembalian melewati jatuh tempo
        if ($returnDate->gt($dueDate)) {
            $this->lateDays = $returnDate->diffInDays($dueDate);
            $this->fineAmount = $this->lateDays * 500;
            $this->fineStatus = 'Lunas';
        } else {
            $this->lateDays = 0;
            $this->fineAmount = 0;
            $this->fineStatus = 'none';
        }
    }

    // Dipicu jika admin merubah tanggal pengembalian secara manual di form
    public function updatedReturnDate()
    {
        $this->calculateFine();
    }

    public function processReturn()
    {
        if (!$this->selectedBorrowingId) {
            session()->flash('error', 'Silakan pilih data peminjaman terlebih dahulu.');
            return;
        }

        $borrowing = Borrowing::findOrFail($this->selectedBorrowingId);
        $book = Book::findOrFail($borrowing->book_id);

        DB::transaction(function () use ($borrowing, $book) {
            // 1. Tambah stok buku kembali 1
            $book->increment('stock');

            // 2. Ubah status peminjaman menjadi dikembalikan
            $borrowing->update([
                'status' => 'Dikembalikan'
            ]);

            // 3. Catat transaksi ke dalam return_logs
            ReturnLog::create([
                'borrowing_id'  => $borrowing->id,
                'return_date'   => $this->returnDate,
                'late_days'     => $this->lateDays,
                'fine_amount'   => $this->fineAmount,
                'fine_status'   => $this->fineStatus,
                'pustakawan_name' => Auth::user()->name, 
            ]);
        });

        session()->flash('success', 'Buku berhasil dikembalikan! Stok buku telah ditambahkan.');
        
        // Reset Form setelah sukses transaksi
        $this->reset(['search', 'selectedBorrowingId', 'selectedBorrowing', 'lateDays', 'fineAmount', 'fineStatus']);
        $this->returnDate = Carbon::now()->toDateString();
    }

    public function render()
    {
        // Mencari peminjaman aktif (status 'Diterima') berdasarkan nama user/siswa
        $searchResults = [];
        if (strlen($this->search) > 1 && !$this->selectedBorrowingId) {
            $searchResults = Borrowing::with(['user', 'book'])
                ->where('status', 'Diterima')
                ->whereHas('user', function($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->take(5)
                ->get();
        }

        $returnLogs = ReturnLog::with(['borrowing.user', 'borrowing.book'])
            ->latest()
            ->paginate(10); 

        return view('livewire.admin.return-management', [
            'searchResults' => $searchResults,
            'returnLogs' => $returnLogs
        ]);
    }
}