@php use 

Illuminate\Support\Facades\Storage; 

@endphp

<div class="p-0.5 bg-slate-900 min-h-screen text-slate-100">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Manajemen Koleksi Buku</h1>
            <p class="text-slate-400 text-sm">Kelola data buku perpustakaan, stok, dan klasifikasi DDC.</p>
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <button wire:click="create" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Buku
            </button>
            <button wire:click="openImportModal" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                </svg>
                Import Excel
            </button>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-emerald-950/50 border border-emerald-500 text-emerald-400 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-rose-950/50 border border-rose-500 text-rose-400 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-slate-800 p-4 rounded-xl mb-6 flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <input wire:model.live="search" type="text" placeholder="Cari judul, penulis, atau ISBN..." class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-sm text-slate-100 placeholder-slate-400 focus:outline-none focus:border-indigo-500">
        </div>
        <div class="w-full md:w-48">
            <select wire:model.live="filterCategory" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-sm text-slate-100 focus:outline-none focus:border-indigo-500">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="bg-slate-800 rounded-xl overflow-hidden border border-slate-700">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-700/50 text-slate-300 text-xs font-semibold uppercase tracking-wider border-b border-slate-700">
                        <th class="p-4">Sampul</th>
                        <th class="p-4">Informasi Buku</th>
                        <th class="p-4">Kategori / DDC</th>
                        <th class="p-4 text-center">Stok</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700 text-sm text-slate-300">
                    @forelse($books as $book)
                        <tr class="hover:bg-slate-700/30 transition">
                            <td class="p-4 w-20">
                                @if($book->cover)
                                    <img src="{{ asset('storage/' . $book->cover) }}" class="w-12 h-16 object-cover rounded shadow">
                                @else
                                    <div class="w-12 h-16 bg-slate-700 rounded flex items-center justify-center text-[10px] text-slate-400 text-center p-1 border border-slate-600">No Cover</div>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="font-semibold text-white text-base">{{ $book->title }}</div>
                                <div class="text-slate-400 text-xs mt-0.5">Penulis: {{ $book->author }} | Penerbit: {{ $book->publisher }} ({{ $book->published_year }})</div>
                                <div class="text-slate-500 text-xs mt-1">ISBN: {{ $book->isbn ?? '-' }} | Bahasa: {{ $book->language }}</div>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 bg-slate-700 text-slate-300 text-xs rounded-md font-medium">{{ $book->category }}</span>
                                <div class="text-slate-500 text-xs mt-1.5 font-mono">DDC: {{ $book->ddc }}</div>
                            </td>
                            <td class="p-4 text-center font-semibold text-white">
                                {{ $book->stock }}
                            </td>
                            <td class="p-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="edit({{ $book->id }})" class="p-1.5 text-amber-400 hover:bg-amber-500/10 rounded transition" title="Ubah Data">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </button>

                                    <button onclick="confirm('Apakah Anda yakin ingin menghapus buku ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $book->id }})" class="p-1.5 text-rose-500 hover:bg-rose-500/10 rounded transition" title="Hapus Data">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-500">Data koleksi buku tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-slate-800/50 border-t border-slate-700">
            {{ $books->links() }}
        </div>
    </div>

    @if($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4 bg-black/60 backdrop-blur-sm">
            <div class="bg-slate-800 border border-slate-700 rounded-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl transition-all">
                <div class="p-6 border-b border-slate-700 flex justify-between items-center bg-slate-800 sticky top-0 z-10">
                    <h3 class="text-lg font-bold text-white">{{ $isEditMode ? 'Ubah Data Buku' : 'Tambah Koleksi Buku Baru' }}</h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}" class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Judul Buku *</label>
                            <input wire:model="title" type="text" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-indigo-500">
                            @error('title') <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Penulis *</label>
                            <input wire:model="author" type="text" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-indigo-500">
                            @error('author') <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Penerbit *</label>
                            <input wire:model="publisher" type="text" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-indigo-500">
                            @error('publisher') <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Tempat Terbit</label>
                            <input wire:model="published_place" type="text" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Tahun Terbit *</label>
                            <input wire:model="published_year" type="number" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-indigo-500">
                            @error('published_year') <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Edisi</label>
                            <input wire:model="edition" type="text" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Jumlah Stok *</label>
                            <input wire:model="stock" type="number" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-indigo-500">
                            @error('stock') <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Bahasa *</label>
                            <input wire:model="language" type="text" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-indigo-500">
                            @error('language') <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">ISBN</label>
                            <input wire:model="isbn" type="text" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Kategori *</label>
                            <input wire:model="category" type="text" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-indigo-500" placeholder="Contoh: Fiksi, Komputer, Sejarah">
                            @error('category') <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Kode DDC *</label>
                            <input wire:model="ddc" type="text" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-indigo-500" placeholder="Contoh: 005.1 atau 800">
                            @error('ddc') <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Deskripsi Ringkas</label>
                        <textarea wire:model="description" rows="3" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-indigo-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Sampul Gambar Buku (Opsional)</label>
                        <input wire:model="cover" type="file" accept="image/*" class="w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-slate-700 file:text-slate-200 hover:file:bg-slate-600">
                        @error('cover') <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        
                        <div class="mt-2 flex items-center gap-4">
                            @if ($cover)
                                <div>
                                    <span class="text-xs text-slate-400 block mb-1">Pratinjau:</span>
                                    <img src="{{ $cover->temporaryUrl() }}" class="w-16 h-20 object-cover rounded shadow border border-indigo-500">
                                </div>
                            @elseif ($existingCover)
                                <div>
                                    <span class="text-xs text-slate-400 block mb-1">Sampul Saat Ini:</span>
                                    <img src="{{ asset('storage/' . $existingCover) }}" class="w-16 h-20 object-cover rounded shadow border border-slate-600">
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="border-t border-slate-700 pt-4 flex justify-end gap-2 bg-slate-800 sticky bottom-0">
                        <button type="button" wire:click="closeModal" class="bg-slate-700 hover:bg-slate-600 text-slate-200 px-4 py-2 rounded-lg text-sm font-medium transition">Batal</button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-1">
                            <span wire:loading wire:target="store, update, cover" class="animate-spin inline-block w-3 h-3 border-2 border-white border-t-transparent rounded-full mr-1"></span>
                            {{ $isEditMode ? 'Simpan Perubahan' : 'Simpan Buku' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
    @if($isImportOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4 bg-black/60 backdrop-blur-sm">
            <div class="bg-slate-800 border border-slate-700 rounded-xl w-full max-w-md shadow-2xl transition-all">
                <div class="p-6 border-b border-slate-700 flex justify-between items-center bg-slate-800 rounded-t-xl">
                    <h3 class="text-lg font-bold text-white">Import Koleksi Buku via Excel</h3>
                    <button wire:click="closeImportModal" class="text-slate-400 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="importExcel" class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Pilih File Excel (.xlsx, .xls, .csv) *</label>
                        <input wire:model="fileExcel" type="file" accept=".xlsx, .xls, .csv" class="w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-slate-700 file:text-slate-200 hover:file:bg-slate-600">
                        
                        @error('fileExcel') 
                            <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="bg-slate-700/40 p-3 rounded-lg border border-slate-600/50">
                        <p class="text-xs text-slate-400 leading-relaxed">
                            💡 <strong class="text-slate-300">Informasi:</strong> Sistem menggunakan fitur <span class="text-emerald-400 font-semibold">Upsert</span>. Jika Judul Buku atau Nama Penulis sudah terdaftar di database, data buku lama otomatis akan diperbarui dengan data terbaru dari Excel alih-alih membuat data ganda.
                        </p>
                    </div>

                    <div class="border-t border-slate-700 pt-4 flex justify-end gap-2">
                        <button type="button" wire:click="closeImportModal" class="bg-slate-700 hover:bg-slate-600 text-slate-200 px-4 py-2 rounded-lg text-sm font-medium transition">Batal</button>
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-1">
                            <span wire:loading wire:target="importExcel, fileExcel" class="animate-spin inline-block w-3 h-3 border-2 border-white border-t-transparent rounded-full mr-1"></span>
                            Mulai Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
