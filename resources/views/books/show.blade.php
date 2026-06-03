<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen" x-data="{ openModal: false }">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-sm font-medium shadow-sm">
                    ✨ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center gap-3 text-sm font-medium shadow-sm">
                    🚨 {{ session('error') }}
                </div>
            @endif

            <div class="mb-6">
                <a href="{{ route('books.index') }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-500 transition">
                    ← Kembali ke Katalog Buku
                </a>
            </div>

            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden grid grid-cols-1 md:grid-cols-12 gap-8 p-6 sm:p-10">
                
                <div class="md:col-span-4 flex flex-col items-center">
                    <div class="w-full aspect-[3/4] max-w-[260px] bg-gradient-to-br from-gray-100 to-indigo-50/50 border border-gray-200 rounded-2xl shadow-inner flex flex-col items-center justify-center p-6 relative group">
                        @if($book->cover)
                            <img src="{{ asset('storage/' . $book->cover) }}" class="w-full h-full object-cover rounded shadow">
                        @else
                            <svg class="w-10 h-10 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            <span class="absolute top-4 left-4 text-[11px] font-bold tracking-wider px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg border border-indigo-100">
                                {{ $book->category ?? 'Umum' }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="md:col-span-8 flex flex-col justify-between space-y-6">
                    <div class="space-y-4">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 leading-tight">{{ $book->title }}</h1>
                            <p class="text-indigo-600 font-medium text-sm mt-1">Oleh: {{ $book->author ?? 'Anonim' }}</p>
                        </div>

                        <div class="border-t border-gray-100 pt-4">
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                                <div class="bg-gray-50/60 p-3 rounded-xl border border-gray-50">
                                    <dt class="text-xs text-gray-400 font-medium uppercase tracking-wider">Penerbit</dt>
                                    <dd class="text-gray-800 font-semibold mt-0.5">{{ $book->publisher ?? '-' }}</dd>
                                </div>
                                <div class="bg-gray-50/60 p-3 rounded-xl border border-gray-50">
                                    <dt class="text-xs text-gray-400 font-medium uppercase tracking-wider">Tahun / Tempat Terbit</dt>
                                    <dd class="text-gray-800 font-semibold mt-0.5">{{ $book->published_year ?? '-' }} / {{ $book->published_place ?? '-' }}</dd>
                                </div>
                                <div class="bg-gray-50/60 p-3 rounded-xl border border-gray-50">
                                    <dt class="text-xs text-gray-400 font-medium uppercase tracking-wider">Nomor ISBN / ISSN</dt>
                                    <dd class="text-gray-800 font-mono font-semibold mt-0.5">{{ $book->isbn ?? '-' }}</dd>
                                </div>
                                <div class="bg-gray-50/60 p-3 rounded-xl border border-gray-50">
                                    <dt class="text-xs text-gray-400 font-medium uppercase tracking-wider">Edisi / Cetakan</dt>
                                    <dd class="text-gray-800 font-semibold mt-0.5">Edisi {{ $book->edition ?? '1' }}</dd>
                                </div>
                                <div class="bg-gray-50/60 p-3 rounded-xl border border-gray-50">
                                    <dt class="text-xs text-gray-400 font-medium uppercase tracking-wider">Bahasa</dt>
                                    <dd class="text-gray-800 font-semibold mt-0.5">{{ $book->language ?? 'Indonesia' }}</dd>
                                </div>
                                <div class="bg-gray-50/60 p-3 rounded-xl border border-gray-50">
                                    <dt class="text-xs text-gray-400 font-medium uppercase tracking-wider">Ketersediaan Stok</dt>
                                    <dd class="mt-0.5">
                                        <span class="inline-flex px-2.5 py-0.5 rounded-md text-xs font-bold {{ $book->stock > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }}">
                                            {{ $book->stock ?? 0 }} Eksemplar Tersedia
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div class="pt-2">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Deskripsi / Catatan</h3>
                            <p class="text-gray-600 text-sm leading-relaxed bg-gray-50/30 p-3 rounded-xl border border-gray-100/70">{{ $book->description ?? 'Tidak ada deskripsi tambahan untuk koleksi buku ini.' }}</p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        @if($book->stock > 0 && strtolower($book->category ?? '') !== 'referensi')
                            <button @click="openModal = true" class="w-full md:w-auto text-center px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-md hover:shadow-lg transition-all duration-150 focus:outline-none">
                                Pinjam Buku Ini 📚✨
                            </button>
                        @elseif(strtolower($book->category ?? '') === 'referensi')
                            <button disabled class="w-full md:w-auto text-center px-8 py-3.5 bg-amber-50 text-amber-700 border border-amber-200 font-bold text-sm rounded-xl cursor-not-allowed">
                                🔒 Buku Referensi Hanya Bisa Dibaca di Perpustakaan!
                            </button>
                        @else
                            <button disabled class="w-full md:w-auto text-center px-8 py-3.5 bg-gray-200 text-gray-400 font-bold text-sm rounded-xl cursor-not-allowed">
                                Stok Habis, Tidak Bisa Dipinjam
                            </button>
                        @endif
                    </div>
                </div>

            </div>

        </div>

        <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-500/75" @click="openModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:min-h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 border border-gray-100">
                    <div class="sm:flex sm:items-start">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-indigo-50 rounded-full sm:mx-0 sm:h-10 sm:w-10 text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0114 0z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ms-4 sm:text-left">
                            <h3 class="text-lg font-bold leading-6 text-gray-900">Konfirmasi Peminjaman Buku</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Apakah kamu yakin ingin meminjam buku <span class="font-bold text-gray-800">"{{ $book->title }}"</span>?
                                </p>
                                <ul class="mt-3 text-xs text-indigo-600 bg-indigo-50/50 p-3 rounded-xl space-y-1.5 list-disc list-inside">
                                    <li>Stok buku otomatis dikunci untukmu.</li>
                                    <li>Harap tunggu approval status <span class="font-bold">Pending</span> dari Pustakawan sekolah.</li>
                                    <li>Maksimal pengembalian adalah 7 hari sejak disetujui.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-2">
                        <form action="{{ route('books.pinjam', $book->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="w-full inline-flex justify-center px-4 py-2.5 text-sm font-bold text-white bg-indigo-600 border border-transparent rounded-xl shadow-sm hover:bg-indigo-700 focus:outline-none transition">
                                Ya, Kirim Request 🚀
                            </button>
                        </form>
                        <button type="button" @click="openModal = false" class="mt-3 w-full inline-flex justify-center px-4 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50 focus:outline-none sm:mt-0 transition">
                            Batalkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </div>
</x-app-layout>