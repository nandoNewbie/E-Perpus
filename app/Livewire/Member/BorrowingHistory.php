<?php

namespace App\Livewire\Member;

use Livewire\Component;
use App\Models\Borrowing;
use Illuminate\Support\Facades\Auth;

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
            $borrowing->update([
                'extension_status' => 'pending_extension'
            ]);
            session()->flash('success', 'Pengajuan perpanjangan 7 hari telah dikirim ke pustakawan!');
        }
    }
}