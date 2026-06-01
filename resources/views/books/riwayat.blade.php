<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="border-b border-gray-200 pb-5">
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Riwayat Peminjaman Buku</h1>
                <p class="text-sm text-gray-500 mt-1">Pantau status pengajuan, batas waktu pengembalian, dan daftar buku yang kamu baca.</p>
            </div>

            @livewire('member.borrowing-history')

        </div>
    </div>
</x-app-layout>