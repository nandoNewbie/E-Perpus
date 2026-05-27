<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen" x-data="{ activeFaq: null }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="text-center pb-6">
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight sm:text-4xl">Pusat Bantuan & FAQ</h1>
                <p class="text-base text-gray-500 mt-2">Punya pertanyaan seputar e-Perpus? Temukan jawaban instan di bawah ini.</p>
            </div>

            <div class="space-y-4">

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition duration-200">
                    <button @click="activeFaq === 1 ? activeFaq = null : activeFaq = 1" class="w-full flex items-center justify-between p-6 text-left focus:outline-none">
                        <span class="font-bold text-gray-800 text-base">1. Bagaimana cara meminjam buku di e-Perpus?</span>
                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="{ 'transform rotate-180 text-indigo-600': activeFaq === 1 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="activeFaq === 1" x-collapse class="border-t border-gray-50 bg-gray-50/50 p-6 text-sm text-gray-600 leading-relaxed">
                        Caranya sangat mudah! Cari buku yang kamu inginkan di menu <span class="font-semibold text-indigo-600">Daftar Buku</span>, klik tombol <span class="font-semibold">Lihat Detail</span>, lalu tekan <span class="font-semibold">Pinjam Buku Ini</span>. Setelah kamu melakukan konfirmasi pada pop-up, status peminjamanmu akan tercatat sebagai <span class="italic font-medium">Pending</span>. Kamu tinggal datang ke ruang perpustakaan fisik untuk mengambil bukunya setelah disetujui oleh pustakawan.
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition duration-200">
                    <button @click="activeFaq === 2 ? activeFaq = null : activeFaq = 2" class="w-full flex items-center justify-between p-6 text-left focus:outline-none">
                        <span class="font-bold text-gray-800 text-base">2. Bagaimana cara mengembalikan buku?</span>
                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="{ 'transform rotate-180 text-indigo-600': activeFaq === 2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="activeFaq === 2" x-collapse class="border-t border-gray-50 bg-gray-50/50 p-6 text-sm text-gray-600 leading-relaxed">
                        Bawa fisik buku yang kamu pinjam ke meja pustakawan di perpustakaan sekolah. Pustakawan akan memeriksa kondisi buku dan melakukan pemindaian/verifikasi di sistem admin. Setelah divalidasi, status di halaman <span class="font-semibold text-indigo-600">Riwayat Pinjam</span> milikmu otomatis akan berubah menjadi <span class="text-emerald-600 font-bold">Sudah Dikembalikan</span> dan kuota peminjamanmu akan kembali kosong.
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition duration-200">
                    <button @click="activeFaq === 3 ? activeFaq = null : activeFaq = 3" class="w-full flex items-center justify-between p-6 text-left focus:outline-none">
                        <span class="font-bold text-gray-800 text-base">3. Bagaimana cara mendapatkan akun e-Perpus?</span>
                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="{ 'transform rotate-180 text-indigo-600': activeFaq === 3 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="activeFaq === 3" x-collapse class="border-t border-gray-50 bg-gray-50/50 p-6 text-sm text-gray-600 leading-relaxed">
                        Kamu bisa mendaftarkan akunmu secara mandiri melalui tombol <span class="font-semibold">Register</span> di halaman awal aplikasi menggunakan NISN atau email aktif sekolah. Pastikan mengisi data dengan benar agar pustakawan dapat memverifikasi identitasmu saat proses peminjaman buku fisik.
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition duration-200">
                    <button @click="activeFaq === 4 ? activeFaq = null : activeFaq = 4" class="w-full flex items-center justify-between p-6 text-left focus:outline-none">
                        <span class="font-bold text-gray-800 text-base">4. Saya lupa password, apa yang harus saya lakukan?</span>
                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="{ 'transform rotate-180 text-indigo-600': activeFaq === 4 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="activeFaq === 4" x-collapse class="border-t border-gray-50 bg-gray-50/50 p-6 text-sm text-gray-600 leading-relaxed">
                        Jika kamu menggunakan email aktif saat registrasi, kamu bisa menekan tombol <span class="italic">Forgot your password?</span> di halaman login untuk mereset password secara otomatis via email. Namun, jika akunmu belum terikat email aktif, silakan hubungi petugas pustakawan di perpustakaan sekolah untuk melakukan reset password manual dari panel admin.
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition duration-200">
                    <button @click="activeFaq === 5 ? activeFaq = null : activeFaq = 5" class="w-full flex items-center justify-between p-6 text-left focus:outline-none">
                        <span class="font-bold text-gray-800 text-base">5. Mengapa permintaan pinjam saya ditolak (Rejected)?</span>
                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="{ 'transform rotate-180 text-indigo-600': activeFaq === 5 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="activeFaq === 5" x-collapse class="border-t border-gray-50 bg-gray-50/50 p-6 text-sm text-gray-600 leading-relaxed">
                        Permintaan pinjam bisa ditolak oleh pustakawan karena beberapa alasan, di antaranya:
                        <ul class="list-disc list-inside mt-2 space-y-1 pl-2 text-rose-700 font-medium">
                            <li>Kamu masih memiliki tanggungan buku lain yang sudah melewati batas waktu pengembalian (terkena denda/tunggakan).</li>
                            <li>Kondisi fisik buku asli di perpustakaan sedang rusak/hilang sehingga tidak layak edar.</li>
                            <li>Terdapat ketidakcocokan data profil siswa.</li>
                        </ul>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition duration-200">
                    <button @click="activeFaq === 6 ? activeFaq = null : activeFaq = 6" class="w-full flex items-center justify-between p-6 text-left focus:outline-none">
                        <span class="font-bold text-gray-800 text-base">6. Berapa lama durasi maksimal peminjaman satu buku?</span>
                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="{ 'transform rotate-180 text-indigo-600': activeFaq === 6 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="activeFaq === 6" x-collapse class="border-t border-gray-50 bg-gray-50/50 p-6 text-sm text-gray-600 leading-relaxed">
                        Durasi maksimal peminjaman buku standar adalah <span class="font-bold text-indigo-600">7 Hari (1 Minggu)</span> sejak permintaan disetujui oleh pustakawan. Jika kamu butuh waktu lebih lama, kamu wajib membawa buku tersebut ke perpustakaan untuk mengajukan perpanjangan durasi pinjam ke petugas.
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition duration-200">
                    <button @click="activeFaq === 7 ? activeFaq = null : activeFaq = 7" class="w-full flex items-center justify-between p-6 text-left focus:outline-none">
                        <span class="font-bold text-gray-800 text-base">7. Berapa banyak buku yang bisa saya pinjam sekaligus?</span>
                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="{ 'transform rotate-180 text-indigo-600': activeFaq === 7 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="activeFaq === 7" x-collapse class="border-t border-gray-50 bg-gray-50/50 p-6 text-sm text-gray-600 leading-relaxed">
                        Setiap siswa dibatasi maksimal meminjam atau mengajukan <span class="font-bold text-indigo-600">3 judul buku</span> dalam satu waktu bersamaan. Pembatasan ini diterapkan agar distribusi koleksi buku merata dan mencegah monopoli buku oleh satu orang pengguna.
                    </div>
                </div>

            </div>

            <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-6 text-center">
                <p class="text-sm text-indigo-900 font-medium">Masih punya pertanyaan lain yang belum terjawab?</p>
                <p class="text-xs text-indigo-500 mt-1">Silakan langsung kunjungi meja loket Pustakawan di gedung perpustakaan sekolah pada jam kerja.</p>
            </div>

        </div>
    </div>
</x-app-layout>