<x-app-layout>
<div class="p-6 max-w-7xl mx-auto space-y-12 animate-fade-in">

    <div class="text-center max-w-2xl mx-auto space-y-2">
        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold tracking-wider uppercase rounded-full">
            Informasi Institusi
        </span>
        <h1 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tight">Profil Perpustakaan</h1>
        <p class="text-sm md:text-base">"Membaca Hari Ini, Menginspirasi Masa Depan."</p>
        <div class="w-16 h-1 bg-blue-600 mx-auto mt-4 rounded-full"></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center bg-white p-6 md:p-8 rounded-3xl border border-gray-100 shadow-sm">
        <div class="lg:col-span-5 rounded-2xl overflow-hidden shadow-md h-72">
            <img src="{{ asset('images/FotoProfilSekolah.png') }}" alt="Gedung Perpustakaan" class="w-full h-full object-cover">
        </div>
        <div class="lg:col-span-7 space-y-4">
            <h3 class="text-2xl font-bold text-gray-800 tracking-tight">Sekilas Tentang Kami</h3>
            <p class="text-gray-600 text-sm leading-relaxed text-justify">
                Perpustakaan SMP Negeri 13 Malang hadir sebagai jantung akademik sekolah yang berkomitmen untuk mendukung seluruh kegiatan belajar mengajar. Tidak hanya sekadar tempat penyimpanan koleksi cetak, perpustakaan kini bertransformasi menjadi pusat layanan informasi yang adaptif, inovatif, dan nyaman guna melahirkan generasi yang kritis, kreatif, dan berbasis literasi digital.
            </p>
            <div class="pt-2">
                <span class="inline-flex items-center text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100">
                    📍 Lokasi: Area Perpustakaan SMP Negeri 13 Malang
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm space-y-6 flex flex-col justify-between group hover:shadow-md hover:border-blue-100 transition duration-300">
            <div class="space-y-5">
                <div class="flex items-center space-x-3">
                    <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-extrabold text-gray-800 tracking-tight">Visi Perpustakaan</h3>
                </div>
                
                <div class="p-4 bg-gradient-to-r from-blue-50/50 to-transparent border-l-4 border-blue-600 rounded-r-xl">
                    <p class="text-gray-700 text-sm md:text-base font-medium leading-relaxed italic">
                        "Mewujudkan Perpustakaan Sekolah sebagai Pusat Sumber Belajar Unggul / Prima untuk meningkatkan Mutu Pendidikan."
                    </p>
                </div>
            </div>
            
            <div class="border-t border-gray-100 pt-4 mt-4 text-xs font-medium text-gray-400">
                E-Library Resmi SMPN 13 Malang
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm space-y-6 flex flex-col justify-between group hover:shadow-md hover:border-indigo-100 transition duration-300">
            <div class="space-y-5">
                <div class="flex items-center space-x-3">
                    <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-extrabold text-gray-800 tracking-tight">Misi Perpustakaan</h3>
                </div>

                <ul class="space-y-3.5">
                    @foreach([
                        "Mewujudkan Perpustakaan sebagai tempat belajar yang menarik dan inovatif.",
                        "Meningkatkan layanan penyediaan informasi yang luas dan up to date.",
                        "Meningkatkan layanan prima untuk warga sekolah.",
                        "Menyediakan sumber bacaan terbaru dan bermutu."
                    ] as $index => $misi)
                    <li class="flex items-start space-x-3 text-gray-600 text-sm md:text-[14px] leading-relaxed">
                        <span class="flex-shrink-0 flex items-center justify-center w-5 h-5 bg-indigo-50 text-indigo-600 font-bold text-xs rounded-full mt-0.5 group-hover:bg-indigo-100 transition duration-300">
                            {{ $index + 1 }}
                        </span>
                        <span class="font-medium text-gray-600">{{ $misi }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="border-t border-gray-100 pt-4 mt-4 text-xs font-medium text-gray-400">
                Fokus Mutu Pelayanan Literasi
            </div>
        </div>
    </div>

    <div class="bg-gray-50 rounded-3xl p-8 border border-gray-200/60 shadow-inner space-y-6">
        <h3 class="text-2xl font-extrabold text-gray-800 text-center lg:text-left">Tujuan Perpustakaan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach([
                "Mendukung kegiatan pembelajaran di SMP Negeri 13 Malang.",
                "Menyediakan sumber informasi yang relevan dan mudah diakses.",
                "Menumbuhkan minat baca dan budaya literasi peserta didik.",
                "Meningkatkan kemampuan peserta didik dalam mencari dan memanfaatkan informasi.",
                "Menjadi pusat kegiatan literasi sekolah.",
                "Membantu peserta didik mengembangkan potensi akademik maupun nonakademik."
            ] as $tujuan)
            <div class="flex items-start space-x-3 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <div class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg mt-0.5">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-gray-700 text-sm font-medium leading-relaxed">{{ $tujuan }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm space-y-8">
        <div class="text-center max-w-md mx-auto">
            <h3 class="text-2xl font-black text-gray-900 tracking-tight">Struktur Organisasi</h3>
            <p class="text-gray-400 text-xs mt-0.5">Struktur Tata Pamong Manajemen Perpustakaan</p>
            <div class="w-10 h-0.5 bg-blue-600 mx-auto mt-2 rounded-full"></div>
        </div>

        <div class="flex flex-col space-y-6 max-w-4xl mx-auto text-center text-xs font-semibold">
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 w-full sm:w-80 mx-auto">
                <span class="text-[10px] font-bold text-gray-400 block tracking-widest uppercase mb-1">Penanggung Jawab / Kepala Sekolah</span>
                <span class="text-gray-800 font-bold text-base">Sadimin, M.Pd</span>
            </div>

            <div class="h-6 w-0.5 bg-gray-200 mx-auto"></div>

            <div class="bg-blue-50/70 p-4 rounded-xl border border-blue-100 w-full sm:w-80 mx-auto shadow-sm shadow-blue-50">
                <span class="text-[10px] font-bold text-blue-500 block tracking-widest uppercase mb-1">Kepala Perpustakaan</span>
                <span class="text-blue-900 font-bold text-base">Lailatul Fitriah, S.S</span>
            </div>

            <div class="h-6 w-0.5 bg-gray-200 mx-auto"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-2">
                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm text-center group hover:border-indigo-100 transition">
                    <span class="text-[10px] text-indigo-500 block font-bold uppercase tracking-wider mb-1">Co. Layanan Sirkulasi</span>
                    <span class="text-gray-700 text-sm font-bold">Miftahul Jannah, S.Pd</span>
                </div>
                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm text-center group hover:border-emerald-100 transition">
                    <span class="text-[10px] text-emerald-500 block font-bold uppercase tracking-wider mb-1">Co. Pengolahan Koleksi</span>
                    <span class="text-gray-700 text-sm font-bold">Laila Meilina, S.Pd</span>
                </div>
                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm text-center group hover:border-amber-100 transition">
                    <span class="text-[10px] text-amber-500 block font-bold uppercase tracking-wider mb-1">Co. Literasi & Promosi</span>
                    <span class="text-gray-700 text-sm font-bold">Mila Irawati, S.Pd</span>
                </div>
                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm text-center group hover:border-rose-100 transition">
                    <span class="text-[10px] text-rose-500 block font-bold uppercase tracking-wider mb-1">Koordinator IT</span>
                    <span class="text-gray-700 text-sm font-bold">Aldi</span>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>