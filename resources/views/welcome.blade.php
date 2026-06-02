<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        @endif
    </head>
    <body class="bg-gray-50 text-gray-900 antialiased font-sans selection:bg-indigo-500 selection:text-white">
        
        <header class="w-full bg-white/80 backdrop-blur-md border-b border-gray-100 fixed top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center shrink-0">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <img src="{{ asset('images/logo-sekolah.png') }}" class="h-9 w-auto" alt="Logo Sekolah" onerror="this.style.display='none'">
                        <span class="font-bold text-lg text-gray-800">| e-library</span>
                    </a>
                </div>

                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-100 transition duration-150">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 bg-gray-900 hover:bg-indigo-600 text-white text-xs font-bold rounded-xl shadow-sm transition duration-150">
                            Masuk
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-xs font-bold rounded-xl shadow-sm transition duration-150">
                                Daftar Akun
                            </a>
                        @endif
                    @endauth
                </nav>
            </div>
        </header>

        <main class="min-h-screen flex items-center justify-center pt-16 relative overflow-hidden bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:16px_16px]">
            <div class="max-w-4xl mx-auto px-4 py-12 text-center space-y-8 relative z-10">
                
                {{-- <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-full border border-indigo-100/50">
                    🚀 Platform Perpustakaan Digital Sekolah Modern
                </span> --}}

                <h1 class="text-4xl sm:text-6xl font-black text-gray-900 tracking-tight leading-tight sm:leading-none">
                    Membuka Jendela Dunia <br class="hidden sm:inline" />
                    Dalam Satu <span class="text-indigo-600">Genggaman</span>
                </h1>

                <p class="max-w-xl mx-auto text-sm sm:text-base text-gray-500 leading-relaxed">
                    Kelola data buku, pinjam literatur favorit, serta pantau riwayat bacaanmu secara kilat, transparan, dan terintegrasi penuh.
                </p>

                <div class="flex items-center justify-center gap-4 pt-2">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200 transition duration-150">
                            Masuk ke Dashboard Utama →
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200 transition duration-150">
                            Mulai Membaca Sekarang
                        </a>
                    @endauth
                </div>

                <div class="pt-8 max-w-xl mx-auto">
                    <div class="grid grid-cols-2 gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        
                        <div class="flex items-center gap-3.5 text-left border-r border-gray-100 pr-2">
                            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-xl sm:text-2xl font-black text-gray-900 leading-none">{{ number_format($totalBooks) }}</h3>
                                <p class="text-xs font-medium text-gray-400 mt-1">Koleksi Buku</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3.5 text-left pl-2">
                            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-1.5 leading-none">
                                    <h3 class="text-xl sm:text-2xl font-black text-gray-900">{{ number_format($activeUsersToday) }}</h3>
                                    <span class="flex h-1.5 w-1.5 relative">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                                    </span>
                                </div>
                                <p class="text-xs font-medium text-gray-400 mt-1">Siswa Aktif Hari Ini</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-center gap-4 pt-2">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200 transition duration-150">
                            Masuk ke Dashboard Utama →
                        </a>
                    @else
                        <a href="{{ route('admin.login') }}" class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200 transition duration-150">
                            Masuk sebagai Admin
                        </a>
                    @endauth
                </div>
            </div>
        </main>

        <footer class="w-full py-6 border-t border-gray-100 bg-white text-center text-xs text-gray-400 fixed bottom-0 left-0 right-0 z-40">
            &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }} Digital. All Rights Reserved.
        </footer>

    </body>
</html>