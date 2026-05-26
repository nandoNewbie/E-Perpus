<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="border-b border-gray-200 pb-5">
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Riwayat Peminjaman Buku</h1>
                <p class="text-sm text-gray-500 mt-1">Pantau status pengajuan, batas waktu pengembalian, dan daftar buku yang kamu baca.</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                
                @if($borrowings->isEmpty())
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <h3 class="text-base font-bold text-gray-900 mb-1">Belum Ada Riwayat</h3>
                        <p class="text-sm text-gray-500 mb-4">Kamu belum pernah mengajukan peminjaman buku apa pun.</p>
                        <a href="{{ route('books.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl transition shadow-sm">
                            Jelajahi Katalog Buku
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-400 uppercase bg-gray-50/70 border-b border-gray-100 font-bold">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Buku</th>
                                    <th scope="col" class="px-6 py-4">Tanggal Request</th>
                                    <th scope="col" class="px-6 py-4">Batas Pengembalian</th>
                                    <th scope="col" class="px-6 py-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($borrowings as $borrow)
                                    <tr class="hover:bg-gray-50/50 transition duration-150">
                                        <td class="px-6 py-4 font-medium text-gray-900 max-w-sm">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-gray-800 line-clamp-1">{{ $borrow->book->title ?? 'Buku Telah Dihapus' }}</span>
                                                <span class="text-xs text-gray-400 mt-0.5">Kode DDC: {{ $borrow->book->ddc ?? '-' }}</span>
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                            {{ \Carbon\Carbon::parse($borrow->borrow_date)->translatedFormat('d F Y') }}
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                            @if($borrow->status == 'pending' || $borrow->status == 'rejected')
                                                <span class="text-gray-300">-</span>
                                            @else
                                                {{ \Carbon\Carbon::parse($borrow->due_date)->translatedFormat('d F Y') }}
                                            @endif
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($borrow->status == 'Pending')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-700 border border-amber-100 animate-pulse">
                                                    ⏳ Menunggu Approval
                                                </span>
                                            @elseif($borrow->status == 'Diterima')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                    📖 Sedang Dipinjam
                                                </span>
                                            @elseif($borrow->status == 'Dikembalikan')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                    ✅ Sudah Dikembalikan
                                                </span>
                                            @elseif($borrow->status == 'Ditolak')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                                    ❌ Permintaan Ditolak
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>