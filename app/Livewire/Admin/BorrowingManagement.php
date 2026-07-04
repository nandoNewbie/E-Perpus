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

    // Pisahkan logika expired ke method sendiri
    private function processExpiredRequests(): void
    {
        $expiredRequests = Borrowing::where('status', Borrowing::STATUS_PENDING)
            ->where('created_at', '<=', Carbon::now()->subHours(24))
            ->get();

        foreach ($expiredRequests as $request) {
            $book = Book::find($request->book_id);

            if ($book) {
                $book->increment('stock');
            }

            $request->update(['status' => Borrowing::STATUS_EXPIRED]);
        }
    }

    public function render()
    {
        // Proses expired dulu sebelum query tampilan
        $this->processExpiredRequests();

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
            // ✅ Ganti FIELD() dengan orderBy yang kompatibel SQLite & MySQL
            ->orderByRaw("FIELD(extension_status, 'pending_extension') DESC")
            ->latest()
            ->paginate(10);

        return view('livewire.admin.borrowing-management', [
            'borrowings' => $borrowings,
        ]);
    }

    public function acceptBorrow($id)
    {
        $borrowing = Borrowing::findOrFail($id);
        $borrowing->update([
            'status'      => Borrowing::STATUS_DITERIMA,
            'borrow_date' => Carbon::now()->toDateString(),
            'due_date'    => Carbon::now()->addDays(7)->toDateString(),
        ]);
        session()->flash('success', 'Peminjaman buku berhasil disetujui!');
    }

    public function rejectBorrow($id)
    {
        $borrowing = Borrowing::findOrFail($id);
        $book      = Book::findOrFail($borrowing->book_id);

        $book->increment('stock');

        $borrowing->update(['status' => Borrowing::STATUS_DITOLAK]);
        session()->flash('success', 'Peminjaman buku telah ditolak. Stok buku dikembalikan.');
    }

    public function acceptExtension($id)
    {
        $borrowing = Borrowing::findOrFail($id);

        $newDueDate = Carbon::parse($borrowing->due_date)
            ->addDays(7)
            ->toDateString();

        $borrowing->update([
            'due_date'         => $newDueDate,
            'extension_status' => 'approved_extension',
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