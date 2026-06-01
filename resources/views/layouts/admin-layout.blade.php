<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pustakawan Dashboard - Library MS</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @livewireStyles
</head>
<body class="bg-gray-50 font-sans antialiased" x-data="{ sidebarOpen: false }">

    <div class="flex min-h-screen">
        
        <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 flex flex-col justify-between shrink-0 shadow-xl border-r border-slate-800 transition-transform duration-300 md:translate-x-0 md:static"
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div>
                <div class="p-6 border-b border-slate-800 flex items-center gap-3">
                    <div class="bg-white text-white p-2 rounded-xl shadow-md shadow-indigo-500/30">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                            <img src="{{ asset('images/logo-sekolah.png') }}" class="h-9 w-auto" alt="Logo Sekolah" onerror="this.style.display='none'">
                        </a>
                    </div>
                    <div>
                        <h1 class="font-black text-white text-sm tracking-wide leading-none uppercase">SMPN GALAS</h1>
                        <p class="text-[11px] text-slate-500 font-medium mt-1">Library MS <span class="text-slate-600">/</span> Manajemen Perpus</p>
                    </div>
                </div>

                <nav class="p-4 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition {{ Request::is('admin/dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.books') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition hover:bg-slate-800 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        Manajemen Buku
                    </a>
                    <a href="{{ route('admin.users') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition hover:bg-slate-800 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Manajemen Anggota
                    </a>
                    <a href="#" @click="sidebarOpen = false" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition hover:bg-slate-800 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Peminjaman Buku
                    </a>
                </nav>
            </div>

            <div class="p-4 border-t border-slate-800 bg-slate-950/40 flex items-center justify-between">
                <div class="truncate mr-2">
                    <p class="text-xs font-bold text-white truncate">{{ session('admin_name', 'Nama Pustakawan') }}</p>
                    <p class="text-[10px] text-slate-500 truncate mt-0.5">{{ session('admin_email', 'admin@school.sch.id') }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" id="logout-form" class="hidden">
                    @csrf
                </form>
                <a href="{{ route('logout') }}" 
                    class="text-slate-500 hover:text-red-400 transition p-1.5 rounded-lg hover:bg-slate-800" title="Keluar" 
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </a>
            </div>
        </aside>

        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/50 md:hidden"></div>

        <main class="flex-1 overflow-y-auto max-h-screen w-full">
            <div class="sticky top-0 bg-white border-b px-4 py-3 flex items-center md:hidden">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-slate-600 hover:bg-gray-100 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                </button>
                <span class="ml-3 font-bold text-slate-800">SMPN GALAS</span>
            </div>

            <div class="p-8">
                {{ $slot }}
            </div>
        </main>

    </div>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireScripts
    @stack('scripts')
</body>
</html>