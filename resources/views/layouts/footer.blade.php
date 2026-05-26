<footer class="bg-gray-900 text-gray-300 mt-20 border-t border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            
            <div class="space-y-4">
                <div class="flex items-center gap-2 text-white">
                    <img src="{{ asset('images/logo-sekolah.png') }}" class="h-10 w-auto" alt="Logo Sekolah" onerror="this.style.display='none'">
                    <span class="font-bold text-xl tracking-tight">e-library</span>
                </div>
                <p class="text-sm text-gray-400 leading-relaxed">
                    Platform perpustakaan digital resmi sekolah. Membantu siswa dan guru mengakses ribuan literatur, buku bacaan, dan referensi akademik secara efisien dan modern.
                </p>
                <div class="pt-2 text-xs text-gray-500">
                    &copy; {{ date('Y') }} Perpus Digital Sekolah. All rights reserved.
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Navigasi</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('dashboard') }}" class="hover:text-indigo-400 transition">Beranda</a></li>
                        <li><a href="{{ route('books.index') }}" class="hover:text-indigo-400 transition">Daftar Buku</a></li>
                        <li><a href="{{ route('books.riwayat') }}" class="hover:text-indigo-400 transition">Riwayat</a></li>
                        <li><a href="{{ url('#faq') }}" class="hover:text-indigo-400 transition">FaQ</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Kontak Kami</h3>
                    <ul class="space-y-2.5 text-sm text-gray-400">
                        <li>📞 (0341) 552864</li>
                        <li>✉️ smpn13malang@gmail.com / info@smpngalasmalang.sch.id</li>
                        <li>📍 Jl. Sunan Ampel 2, RT.9/RW.2, Dinoyo, Kec. Lowokwaru, Kota Malang, Jawa Timur 65144</li>
                    </ul>
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider">Lokasi Sekolah</h3>
                <div class="w-full h-44 rounded-2xl overflow-hidden shadow-md border border-gray-800 group relative">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.484730668693!2d112.60483811063227!3d-7.948755692042533!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78826e24f620b1%3A0x289964547020361c!2sSMP%20Negeri%2013%20Malang!5e0!3m2!1sid!2sid!4v1779694910644!5m2!1sid!2sid" 
                        class="w-full h-full border-0 grayscale opacity-80 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-300" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

        </div>
    </div>
</footer>