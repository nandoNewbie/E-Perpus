<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-gray-200 pb-5">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Katalog Buku Perpustakaan</h1>
                    <p class="text-sm text-gray-500 mt-1">Temukan dan jelajahi ratusan koleksi buku bacaan siap pinjam.</p>
                </div>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500">
                    ← Kembali ke Dashboard
                </a>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-indigo-200 shadow-sm">
                <form action="{{ route('books.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center justify-between">
                    
                    <div class="relative w-full md:flex-1">
                        <span class="absolute inset-y-0 left-0 flex items-center ps-4 pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik judul buku, penulis, atau ISBN..." class="w-full py-3 ps-11 pe-4 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all bg-gray-50/50">
                    </div>

                    <div class="w-full md:w-64">
                        <select name="category" onchange="this.form.submit()" class="w-full py-3 px-4 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50/50">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full md:w-auto flex gap-2">
                        <button type="submit" class="w-full md:w-auto px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition duration-150 shadow-sm">
                            Cari
                        </button>
                        @if(request('search') || request('category'))
                            <a href="{{ route('books.index') }}" class="px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-xl transition text-center">
                                Reset
                            </a>
                        @endif
                    </div>

                </form>
            </div>

            <div x-data="{ limit: 10, total: {{ $books->count() }} }" class="space-y-8 p-3">
                
                @if($books->isEmpty())
                    <div class="bg-white rounded-2xl border border-indigo-200 p-12 text-center shadow-sm">
                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Buku Tidak Ditemukan</h3>
                        <p class="text-sm text-gray-500">Coba cari dengan kata kunci lain atau reset filter Kategori.</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                        @foreach($books as $index => $book)
                            <div x-show="{{ $index }} < limit" 
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                class="bg-white rounded-2xl border border-indigo-200 shadow-sm overflow-hidden hover:shadow-md transition-all duration-200 flex flex-col h-full group relative">
                                
                                <div class="w-full h-48 bg-gradient-to-br from-gray-50 to-indigo-50/30 flex items-center justify-center border-b border-indigo-200 shrink-0 relative">
                                    @if($book->cover)
                                        <img src="{{ $book->cover_url }}" class="w-full h-full object-cover rounded shadow">
                                    @else
                                        <svg class="w-10 h-10 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    @endif
                                    
                                    <span class="absolute top-3 left-3 text-[10px] font-bold tracking-wider px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded-md border border-indigo-100 shadow-sm capitalize">
                                        {{ $book->category ?? 'Umum' }}
                                    </span>
                                </div>

                                <div class="p-4 flex flex-col flex-grow justify-between">
                                    <div class="mb-3">
                                        <h3 class="font-bold text-gray-800 text-sm line-clamp-2 mb-1 group-hover:text-indigo-600 transition-colors" title="{{ $book->title }}">
                                            {{ $book->title }}
                                        </h3>
                                        <p class="text-xs text-gray-400 line-clamp-1 mb-2">Oleh: {{ $book->author ?? 'Anonim' }}</p>
                                        
                                        <div class="flex items-center gap-1 text-[11px] text-gray-500">
                                            <span>Stok:</span>
                                            <span class="font-bold {{ $book->stock > 0 ? 'text-emerald-600' : 'text-red-500' }}">
                                                {{ $book->stock ?? 0 }} eks
                                            </span>
                                        </div>
                                    </div>

                                    <div>
                                        <a href="{{ route('books.show', $book->id) }}" class="block text-center w-full py-2 bg-gray-50 hover:bg-indigo-600 border border-gray-200 hover:border-indigo-600 text-gray-600 hover:text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-sm">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="text-center pt-4" x-show="limit < total">
                        <button @click="limit += 10" class="inline-flex items-center px-6 py-3 border border-gray-200 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 shadow-sm transition hover:border-gray-300">
                            Tampilkan Lebih Banyak
                            <svg class="ms-2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>