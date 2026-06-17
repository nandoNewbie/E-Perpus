<?php

namespace App\Livewire\Member;

use Livewire\Component;
use App\Models\Borrowing;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BorrowingHistory extends Component
{
    public function render()
    {
        $histories = Borrowing::with('book')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('livewire.member.borrowing-history', [
            'histories' => $histories
        ]);
    }

    public function requestExtension($id)
    {
        $borrowing = Borrowing::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($borrowing->status === 'Diterima' && $borrowing->extension_status === null) {
            
            // ─── LOGIKA VALIDASI JATUH TEMPO (H-1) ───
            $hariIni = Carbon::now()->startOfDay();
            $jatuhTempo = Carbon::parse($borrowing->due_date)->startOfDay();

            // Hitung selisih hari (false agar menghasilkan nilai minus jika sudah lewat)
            $selisihHari = $hariIni->diffInDays($jatuhTempo, false);

            if ($selisihHari > 1) {
                // Kasus: Belum waktunya perpanjang (misal masih kurang 3 hari lagi)
                session()->flash('error', "Gagal! Perpanjangan hanya dapat diajukan 1 hari sebelum jatuh tempo.");
                return;
            } elseif ($selisihHari < 0) {
                // Kasus: Sudah terlambat / melewati tanggal jatuh tempo
                session()->flash('error', "Gagal! Masa pinjam sudah melewati jatuh tempo. Silakan kembalikan ke perpustakaan.");
                return;
            }
            // ─────────────────────────────────────────

            // Jika lolos validasi (selisihHari == 1 atau selisihHari == 0)
            $borrowing->update([
                'extension_status' => 'pending_extension'
            ]);
            
            session()->flash('success', 'Pengajuan perpanjangan 7 hari telah dikirim ke pustakawan!');
        }
    }
}