<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-8 sm:p-12 flex flex-col md:flex-row items-center justify-between gap-8 bg-gradient-to-r from-indigo-50 via-white to-white">
                    
                    <div class="space-y-4 max-w-2xl text-center md:text-left">
                        <div class="flex justify-center md:justify-start mb-2">
                            <img src="{{ asset('images/logo-sekolah.png') }}" class="h-12 w-auto" alt="Logo Sekolah" onerror="this.style.display='none'">
                        </div>

                        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                            Selamat datang, <span class="text-indigo-600">{{ Auth::user()->name }}</span>! 👋
                        </h1>
                        
                        <p class="text-lg text-gray-600 leading-relaxed">
                            E-Library SMP N 14 Malang merupakan platform digital yang menyediakan berbagai koleksi buku untuk mendukung kegiatan belajar siswa dan guru. Melalui sistem ini, pengguna dapat dengan mudah mencari buku, mengetahui ketersediaan, serta melakukan peminjaman tanpa harus datang langsung ke perpustakaan.
                            Platform ini dirancang untuk mempermudah akses informasi, meningkatkan efisiensi layanan perpustakaan, serta  mendorong budaya literasi di lingkungan sekolah. 
                            {{-- <span class="font-semibold text-gray-800">e-Library</span> resmi kami. --}}
                        </p>

                        <div class="pt-2 flex flex-wrap justify-center md:justify-start gap-4">
                            <a href="{{ route('books.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                Cari Buku Bacaan
                                <svg class="ms-2 -me-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('books.riwayat') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                Lihat Pinjaman Saya
                            </a>
                        </div>
                    </div>

                    <div class="w-full md:w-1/3 flex justify-center">
                        <div class="relative w-64 h-64 bg-indigo-100 rounded-2xl flex items-center justify-center shadow-inner overflow-hidden">
                            <svg class="w-40 h-40 text-indigo-500 opacity-80 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>

                </div>
            </div>

        <div class="mt-12 bg-gradient-to-br from-amber-100 via-orange-50 to-yellow-50 rounded-3xl p-6 sm:p-10 border-2 border-dashed border-amber-300 shadow-sm relative overflow-hidden group">
            
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-yellow-200 rounded-full opacity-40 blur-2xl group-hover:scale-125 transition-transform duration-500"></div>
            <div class="absolute left-1/3 top-2 w-12 h-12 bg-amber-200 rounded-full opacity-30 blur-xl"></div>

            <div class="relative flex flex-col md:flex-row items-center justify-between gap-6">
                
                <div class="flex-shrink-0 bg-white p-4 rounded-2xl shadow-md rotate-[-3deg] group-hover:rotate-[6deg] transition-transform duration-300 border border-amber-200">
                    <svg class="w-16 h-16 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8a2 2 0 100-4 2 2 0 000 4z" class="animate-spin" style="transform-origin: 12px 6px;"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11l2 2m0-2l-2 2m6-2l2 2m0-2l-2 2"></path>
                    </svg>
                </div>

                <div class="flex-1 text-center md:text-left space-y-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-200 text-amber-800 uppercase tracking-wider animate-bounce">
                        🚨 Mode Panik: Aktif
                    </span>
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight">
                        Mulai Bingung & Tersesat di Perpus Digital?
                    </h2>
                    <p class="text-gray-700 leading-relaxed max-w-xl">
                        Niatnya mau pinjam buku sejarah, malah nyasar ke tombol logout? Atau lupa cara mulangin buku biar gak diuber denda? Tenang, tarik napas dulu. Kami sudah merangkum semua jawaban konyol hingga serius di halaman bantuan.
                    </p>
                </div>

                <div class="flex-shrink-0 w-full md:w-auto">
                    <a href="{{ url('#faq') }}" class="block text-center px-8 py-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold text-lg rounded-2xl shadow-lg hover:from-amber-600 hover:to-orange-600 hover:shadow-xl hover:-translate-y-1 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-orange-300">
                        Pencet Ini, Tolong! 🧠⚡
                    </a>
                    <p class="text-center text-xs text-amber-700 mt-2 font-medium italic">
                        *Garansi 99% langsung paham, 1%-nya hoki.
                    </p>
                </div>

            </div>
        </div>
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                </div>
        </div>
    </div>
</x-app-layout>