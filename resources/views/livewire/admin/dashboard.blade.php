<div>
    <div class="mb-8">
        <h2 class="text-2xl font-black text-white tracking-tight">Ringkasan Perpustakaan</h2>
        <p class="text-sm text-gray-500 mt-1">Selamat datang kembali, Pustakawan! Berikut adalah statistik per hari ini.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Buku</p>
                <h3 class="text-3xl font-black text-gray-800 mt-2">{{ $totalBuku }}</h3>
            </div>
            <div class="p-4 bg-indigo-50 text-indigo-600 rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Buku Dipinjam</p>
                <h3 class="text-3xl font-black text-gray-800 mt-2">{{ $bukuDipinjam }}</h3>
            </div>
            <div class="p-4 bg-amber-50 text-amber-600 rounded-xl group-hover:bg-amber-600 group-hover:text-white transition duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Anggota</p>
                <h3 class="text-3xl font-black text-gray-800 mt-2">{{ $totalAnggota }}</h3>
            </div>
            <div class="p-4 bg-emerald-50 text-emerald-600 rounded-xl group-hover:bg-emerald-600 group-hover:text-white transition duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Keterlambatan</p>
                <h3 class="text-3xl font-black text-red-600 mt-2">{{ $totalKeterlambatan }}</h3>
            </div>
            <div class="p-4 bg-red-50 text-red-600 rounded-xl group-hover:bg-red-600 group-hover:text-white transition duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

    </div>

    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <div class="mb-4">
            <h4 class="text-sm font-bold text-gray-800">Tren Peminjaman Buku</h4>
            <p class="text-xs text-gray-400">Grafik total peminjaman buku oleh siswa sepanjang tahun 2026.</p>
        </div>
        
        <div class="w-full h-80 relative">
            <canvas id="borrowingChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:navigated', () => {
        const ctx = document.getElementById('borrowingChart');
        if (!ctx) return;

        // Reset canvas jika grafik sempat terbuat sebelumnya (agar aman di SPA Livewire)
        if (window.myChart) {
            window.myChart.destroy();
        }

        window.myChart = new Chart(ctx, {
            type: 'line', // Jenis grafik garis
            data: {
                labels: @json($dataGrafik['labels']),
                datasets: [{
                    label: 'Jumlah Buku Dipinjam',
                    data: @json($dataGrafik['jumlah']),
                    borderColor: '#4f46e5', // Warna garis ungu indigo-600
                    backgroundColor: 'rgba(79, 70, 229, 0.05)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3, // Membuat garis sedikit melengkung (smooth)
                    pointBackgroundColor: '#4f46e5',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 10 // Lompatan angka vertikal
                        },
                        grid: {
                            color: '#f3f4f6'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false // Sembunyikan label kotak atas bawaan chart.js agar lebih minimalis
                    }
                }
            }
        });
    });
</script>
@endpush