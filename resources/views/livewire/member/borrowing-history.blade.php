<div class="bg-slate-800 p-6 rounded-xl border border-slate-700">
    <div class="mb-4">
        <h2 class="text-xl font-bold text-white">Riwayat Peminjaman Buku Kamu</h2>
        <p class="text-sm text-slate-400">Daftar buku yang sedang kamu pinjam atau yang telah kamu kembalikan.</p>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 p-3 bg-emerald-950/50 border border-emerald-500 text-emerald-400 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto rounded-lg border border-slate-700">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-700/50 text-slate-300 text-xs font-semibold uppercase border-b border-slate-700">
                    <th class="p-4">Judul Buku</th>
                    <th class="p-4 text-center">Tgl Pinjam</th>
                    <th class="p-4 text-center">Jatuh Tempo</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-center">Opsi Perpanjangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700 text-sm text-slate-300">
                @forelse($histories as $item)
                    <tr class="hover:bg-slate-700/20 transition">
                        <td class="p-4 font-medium text-white">{{ $item->book->title }}</td>
                        <td class="p-4 text-center font-mono text-xs">{{ $item->borrow_date ?? '-' }}</td>
                        <td class="p-4 text-center font-mono text-xs text-amber-400 font-semibold">{{ $item->due_date ?? '-' }}</td>
                        <td class="p-4 text-center">
                            <span class="px-2 py-0.5 text-xs rounded font-medium {{ $item->status === 'Diterima' ? 'bg-indigo-500/20 text-indigo-400' : ($item->status === 'Pending' ? 'bg-amber-500/20 text-amber-400' : 'bg-rose-500/20 text-rose-400') }}">
                                {{ $item->status === 'Diterima' ? 'Sedang Dibawa' : $item->status }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            @if($item->status === 'Diterima' && $item->extension_status === null)
                                <button wire:click="requestExtension({{ $item->id }})" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-xs font-medium transition shadow-sm">
                                    Ajukan Tambah 7 Hari
                                </button>
                            @elseif($item->extension_status === 'pending_extension')
                                <span class="text-amber-400 text-xs italic">Menunggu ACC Pustakawan...</span>
                            @elseif($item->extension_status === 'approved_extension')
                                <span class="text-emerald-400 text-xs font-semibold">✓ Diperpanjang 7 Hari</span>
                            @else
                                <span class="text-slate-500 text-xs">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-8 text-center text-slate-500">Kamu belum pernah meminjam buku.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>