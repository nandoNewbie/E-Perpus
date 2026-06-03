<div class="p-0.5 bg-slate-900 min-h-screen text-slate-100">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">Sirkulasi Pengembalian Buku</h1>
        <p class="text-slate-400 text-sm">Proses pengembalian buku siswa, hitung keterlambatan, dan kelola administrasi denda.</p>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-emerald-950/50 border border-emerald-500 text-emerald-400 rounded-lg text-sm">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-rose-950/50 border border-rose-500 text-rose-400 rounded-lg text-sm">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 bg-slate-800 p-5 rounded-xl border border-slate-700 h-fit">
            <h2 class="text-base font-bold text-white mb-4">Cari Peminjam Aktif</h2>
            <div class="relative">
                <input wire:model.live="search" type="text" placeholder="Ketik nama siswa..." class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2.5 text-sm text-slate-100 placeholder-slate-400 focus:outline-none focus:border-indigo-500">
                
                @if(!empty($searchResults))
                    <div class="absolute z-10 w-full mt-1 bg-slate-700 border border-slate-600 rounded-lg shadow-xl overflow-hidden max-h-60 overflow-y-auto">
                        @foreach($searchResults as $result)
                            <button wire:click="selectBorrowing({{ $result->id }})" class="w-full text-left px-4 py-2.5 text-sm hover:bg-slate-600 border-b border-slate-600/50 last:border-0 transition flex flex-col">
                                <span class="font-semibold text-white">{{ $result->user->name }} ({{ $result->user->class }})</span>
                                <span class="text-xs text-indigo-300 mt-0.5">📖 {{ $result->book->title }}</span>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
            
            @if(!$selectedBorrowingId && strlen($search) > 1 && empty($searchResults))
                <p class="text-xs text-rose-400 mt-2">Siswa tersebut tidak memiliki pinjaman buku yang aktif.</p>
            @endif
        </div>

        <div class="lg:col-span-2 bg-slate-800 p-6 rounded-xl border border-slate-700">
            <h2 class="text-base font-bold text-white mb-4">Detail & Kalkulasi Pengembalian</h2>

            @if($selectedBorrowing)
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-slate-900/50 rounded-lg border border-slate-700/60 text-sm">
                        <div>
                            <span class="text-slate-400 block text-xs uppercase font-semibold">Nama Siswa / Kelas</span>
                            <span class="text-white font-medium block mt-0.5">{{ $selectedBorrowing->user->name }}</span>
                            <span class="text-slate-400 text-xs">{{ $selectedBorrowing->user->class }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-xs uppercase font-semibold">Buku Yang Dipinjam</span>
                            <span class="text-indigo-300 font-medium block mt-0.5">{{ $selectedBorrowing->book->title }}</span>
                        </div>
                        <div class="pt-2 border-t border-slate-700/40">
                            <span class="text-slate-400 block text-xs uppercase font-semibold">Tanggal Pinjam</span>
                            <span class="text-slate-300 font-mono text-xs mt-0.5">{{ \Carbon\Carbon::parse($selectedBorrowing->borrow_date)->translatedFormat('d F Y') }}</span>
                        </div>
                        <div class="pt-2 border-t border-slate-700/40">
                            <span class="text-slate-400 block text-xs uppercase font-semibold">Batas Jatuh Tempo</span>
                            <span class="text-amber-400 font-bold font-mono text-xs mt-0.5">{{ \Carbon\Carbon::parse($selectedBorrowing->due_date)->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase mb-1.5">Tanggal Pengembalian</label>
                            <input wire:model.live="returnDate" type="date" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase mb-1.5">Total Keterlambatan</label>
                            <div class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-3 py-2 text-sm font-medium text-slate-300">
                                <span class="{{ $lateDays > 0 ? 'text-rose-400 font-bold' : '' }}">{{ $lateDays }} Hari</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 rounded-lg {{ $fineAmount > 0 ? 'bg-rose-950/20 border border-rose-500/30' : 'bg-slate-900/40 border border-slate-700' }} flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <span class="text-xs text-slate-400 uppercase font-semibold block">Total Denda Perpustakaan</span>
                            <span class="text-2xl font-black {{ $fineAmount > 0 ? 'text-rose-400' : 'text-emerald-400' }} mt-0.5">
                                Rp. {{ number_format($fineAmount, 0, ',', '.') }}
                            </span>
                        </div>
                        
                        @if($fineAmount > 0)
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-400 uppercase mb-1">Status Pembayaran Denda</label>
                                <select wire:model="fineStatus" class="bg-slate-700 border border-slate-600 rounded-lg px-2.5 py-1.5 text-xs text-white focus:outline-none">
                                    <option value="Lunas">Lunas (Bayar Cash)</option>
                                    <option value="Belum Lunas">Belum Lunas</option>
                                </select>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-end pt-2">
                        <button wire:click="processReturn" wire:loading.attr="disabled" class="bg-indigo-600 hover:bg-indigo-700 disabled:bg-slate-600 text-white font-bold px-6 py-2.5 rounded-lg text-sm shadow transition duration-150 flex items-center gap-2">
                            <span wire:loading.remove>✓ Proses Pengembalian</span>
                            <span wire:loading class="animate-spin inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full"></span>
                            <span wire:loading>Memproses...</span>
                        </button>
                    </div>
                </div>
            @else
                <div class="py-16 text-center text-slate-500 border border-dashed border-slate-700 rounded-xl">
                    <svg class="mx-auto h-10 w-10 text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <p class="text-sm">Pilih nama siswa di panel kiri untuk memuat kalkulator denda transaksi.</p>
                </div>
            @endif
        </div>
    </div>
    <div class="mt-8 bg-slate-800 p-6 rounded-xl border border-slate-700">
        <div class="mb-4">
            <h2 class="text-base font-bold text-white">Riwayat Pengembalian Terbaru</h2>
            <p class="text-xs text-slate-400 mt-0.5">Daftar 10 transaksi pengembalian buku terakhir yang diproses oleh Pustakawan.</p>
        </div>

        <div class="overflow-x-auto rounded-lg border border-slate-700/60">
            <table class="w-full text-sm text-left text-slate-300">
                <thead class="text-xs text-slate-400 uppercase bg-slate-900/60 font-bold border-b border-slate-700">
                    <tr>
                        <th scope="col" class="px-6 py-3.5">Peminjam / Kelas</th>
                        <th scope="col" class="px-6 py-3.5">Buku</th>
                        <th scope="col" class="px-6 py-3.5">Tgl Kembali</th>
                        <th scope="col" class="px-6 py-3.5">Terlambat</th>
                        <th scope="col" class="px-6 py-3.5">Denda</th>
                        <th scope="col" class="px-6 py-3.5">Status Denda</th>
                        <th scope="col" class="px-6 py-3.5">Petugas (Pustakawan)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50 bg-slate-800/40">
                    @forelse($returnLogs as $log)
                        <tr class="hover:bg-slate-700/30 transition">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-semibold text-white">{{ $log->borrowing->user->name ?? 'User Dihapus' }}</span>
                                    <span class="text-xs text-slate-400 mt-0.5">{{ $log->borrowing->user->class ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-200 max-w-xs truncate">
                                {{ $log->borrowing->book->title ?? 'Buku Dihapus' }}
                            </td>
                            <td class="px-6 py-4 font-mono text-xs text-slate-400">
                                {{ \Carbon\Carbon::parse($log->return_date)->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($log->late_days > 0)
                                    <span class="text-rose-400 font-bold font-mono">{{ $log->late_days }} Hari</span>
                                @else
                                    <span class="text-slate-500 font-mono">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-mono text-xs">
                                @if($log->fine_amount > 0)
                                    <span class="text-rose-400 font-semibold text-xs">Rp. {{ number_format($log->fine_amount, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-emerald-400 font-medium">Rp. 0</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($log->fine_status == 'Lunas')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-950/80 text-emerald-400 border border-emerald-800/60">
                                        Lunas
                                    </span>
                                @elseif($log->fine_status == 'Belum Lunas')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-rose-950/80 text-rose-400 border border-rose-800/60 animate-pulse">
                                        Belum Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-700/60 text-slate-400">
                                        Tepat Waktu
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-indigo-300 font-medium">
                                👤 {{ $log->pustakawan_name }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-slate-500">
                                Belum ada riwayat pengembalian buku yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>