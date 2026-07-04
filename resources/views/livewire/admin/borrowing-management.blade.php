<div class="p-0.5 bg-slate-900 min-h-screen text-slate-100">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">Manajemen Peminjaman Buku</h1>
        <p class="text-slate-400 text-sm">Validasi pengajuan peminjaman baru dan perpanjangan jatuh tempo siswa.</p>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-emerald-950/50 border border-emerald-500 text-emerald-400 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <div class="bg-slate-800 p-4 rounded-xl mb-6 flex flex-col md:flex-row gap-4">
        <input wire:model.live="search" type="text" placeholder="Cari nama siswa, kelas, atau judul buku..." class="flex-1 bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-sm text-slate-100 placeholder-slate-400 focus:outline-none focus:border-indigo-500">
        <select wire:model.live="filterStatus" class="bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-sm text-slate-100 focus:outline-none focus:border-indigo-500">
            <option value="">Semua Status</option>
            <option value="Pending">Pengajuan Baru (Pending)</option>
            <option value="pending_extension">Minta Perpanjang</option>
            <option value="Diterima">Sedang Dipinjam</option>
            <option value="Ditolak">Ditolak</option>
            <option value="Dikembalikan">Dikembalikan</option>
            <option value="Expired">Terlambat</option>
        </select>
    </div>

    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
    <div class="w-full overflow-x-auto min-w-full align-middle">
        <table class="w-full text-left border-collapse min-w-[800px]"> 
            <thead>
                <tr class="bg-slate-700/50 text-slate-300 text-xs font-semibold uppercase tracking-wider border-b border-slate-700">
                    <th class="p-4 whitespace-nowrap">Peminjam / Kelas</th>
                    <th class="p-4 whitespace-nowrap">Judul Buku</th>
                    <th class="p-4 text-center whitespace-nowrap">Tgl Pinjam</th>
                    <th class="p-4 text-center whitespace-nowrap">Jatuh Tempo</th>
                    <th class="p-4 text-center whitespace-nowrap">Status / Info</th>
                    <th class="p-4 text-center whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700 text-sm text-slate-300">
                @forelse($borrowings as $item)
                    <tr class="hover:bg-slate-700/30 transition">
                        <td class="p-4 whitespace-nowrap">
                            <div class="font-semibold text-white">{{ $item->user->name }}</div>
                            <div class="text-xs text-slate-400">{{ $item->user->class }}</div>
                        </td>
                        <td class="p-4 text-indigo-300 font-medium max-w-xs truncate">{{ $item->book->title }}</td>
                        <td class="p-4 text-center text-xs font-mono whitespace-nowrap">{{ $item->borrow_date ?? '-' }}</td>
                        <td class="p-4 text-center text-xs font-mono text-amber-400 font-bold whitespace-nowrap">{{ $item->due_date ?? '-' }}</td>
                        <td class="p-4 text-center whitespace-nowrap">
                            <span class="px-2 py-0.5 text-xs rounded font-medium {{ $item->status === 'Diterima' ? 'bg-indigo-500/20 text-indigo-400' : ($item->status === 'Pending' ? 'bg-amber-500/20 text-amber-400' : 'bg-rose-500/20 text-rose-400') }}">
                                {{ $item->status === 'Diterima' ? 'Sedang Dipinjam' : $item->status }}
                            </span>
                            @if($item->extension_status === 'pending_extension')
                                <div class="mt-1"><span class="px-2 py-0.5 text-[11px] rounded bg-purple-500/20 text-purple-400 border border-purple-500/30 animate-pulse font-bold">Minta Perpanjang</span></div>
                            @elseif($item->extension_status === 'approved_extension')
                                <div class="mt-1"><span class="px-2 py-0.5 text-[11px] rounded bg-emerald-500/20 text-emerald-400">Time Extended</span></div>
                            @endif
                        </td>
                        <td class="p-4 text-center whitespace-nowrap">
                            <div class="flex justify-center gap-1">
                                @if($item->extension_status === 'pending_extension')
                                    <button wire:click="acceptExtension({{ $item->id }})" class="bg-purple-600 hover:bg-purple-700 text-white px-2 py-1 rounded text-xs font-semibold shadow-sm">ACC Perpanjang</button>
                                    <button wire:click="rejectExtension({{ $item->id }})" class="bg-slate-600 hover:bg-slate-500 text-white px-2 py-1 rounded text-xs font-semibold shadow-sm">Tolak</button>
                                @elseif($item->status === 'Pending')
                                    <button wire:click="acceptBorrow({{ $item->id }})" class="bg-emerald-600 hover:bg-emerald-700 text-white px-2 py-1 rounded text-xs font-semibold shadow-sm">ACC</button>
                                    <button wire:click="rejectBorrow({{ $item->id }})" class="bg-rose-600 hover:bg-rose-700 text-white px-2 py-1 rounded text-xs font-semibold shadow-sm">Reject</button>
                                @elseif($item->status === 'Diterima')
                                    <span class="text-indigo-400 text-xs py-1">Aktif</span>
                                @else
                                    <span class="text-slate-500 text-xs py-1">Selesai</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-8 text-center text-slate-500">Tidak ada log data peminjaman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 bg-slate-800/50 border-t border-slate-700">
        {{ $borrowings->links() }}
    </div>
</div>
</div>