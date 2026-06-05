<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Borrowing;
use App\Models\Book;
use Carbon\Carbon;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin-layout')]
class BorrowingManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';

    protected $updatesQueryString = ['search', 'filterStatus'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $borrowings = Borrowing::with(['user', 'book'])
            ->when($this->search, function($query) {
                $query->whereHas('user', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('class', 'like', '%' . $this->search . '%');
                })->orWhereHas('book', function($q) {
                    $q->where('title', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus, function($query) {
                if ($this->filterStatus === 'pending_extension') {
                    $query->where('extension_status', 'pending_extension');
                } else {
                    $query->where('status', $this->filterStatus);
                }
            })
            ->orderByRaw("FIELD(extension_status, 'pending_extension') DESC")
            ->latest()
            ->paginate(10);

        // 1. AMBIL DATA REQUEST YANG SUDAH LEWAT 24 JAM
        $expiredRequests = Borrowing::where('status', 'pending')
            ->where('created_at', '<=', Carbon::now()->subHours(24))
            ->get();

        // 2. KEMBALIKAN STOK BUKU & UBAH STATUS JADI EXPIRED
        foreach ($expiredRequests as $request) {
            // Ambil data buku terkait
            $book = Book::find($request->book_id);
            
            if ($book) {
                // Kembalikan stok buku yang sempat tertahan karena di-booking
                $book->increment('stock'); 
            }

            // Ubah status transaksi tersebut menjadi expired
            $request->update([
                'status' => 'expired'
            ]);
        }

        // 3. QUERY NORMAL TAMPILAN HALAMAN (Kode kamu yang sudah ada)
        // Sekarang, data yang ditarik ke bawah sudah bersih dari status pending yang basi
        $borrowings = Borrowing::query()
            ->when($this->search, function($query) {
                $query->where('invoice_number', 'like', '%' . $this->search . '%');
                // ... atau filter pencarian kamu yang lain ...
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.borrowing-management', [
            'borrowings' => $borrowings
        ]);                                                     
    }

    public function acceptBorrow($id)
    {
        $borrowing = Borrowing::findOrFail($id);
        $borrowing->update([
            'status'      => 'Diterima',
            'borrow_date' => Carbon::now()->toDateString(),
            'due_date'    => Carbon::now()->addDays(7)->toDateString(),
        ]);
        session()->flash('success', 'Peminjaman buku berhasil disetujui!');
    }

    public function rejectBorrow($id)
    {
        $borrowing = Borrowing::findOrFail($id);
        $book      = Book::findOrFail($borrowing->book_id);

        // Kembalikan stok karena peminjaman dibatalkan/ditolak
        $book->increment('stock');

        $borrowing->update(['status' => 'Ditolak']);
        session()->flash('success', 'Peminjaman buku telah ditolak. Stok buku dikembalikan.');
    }

    public function acceptExtension($id)
    {
        $borrowing = Borrowing::findOrFail($id);
        
        // Tambahkan durasi jatuh tempo 7 hari dari tanggal jatuh tempo lama
        $currentDueDate = Carbon::parse($borrowing->due_date);
        $newDueDate     = $currentDueDate->addDays(7)->toDateString();

        $borrowing->update([
            'due_date'         => $newDueDate,
            'extension_status' => 'approved_extension'
        ]);

        session()->flash('success', 'Masa pinjam buku berhasil diperpanjang 7 hari!');
    }

    public function rejectExtension($id)
    {
        $borrowing = Borrowing::findOrFail($id);
        $borrowing->update(['extension_status' => null]);
        session()->flash('success', 'Permintaan perpanjangan waktu ditolak.');
    }
}