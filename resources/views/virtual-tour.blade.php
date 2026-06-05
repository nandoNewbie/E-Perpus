<x-app-layout>
    <div class="py-12 min-h-screen">
        <div class="flex-grow space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-800 p-6 rounded-xl border max-w-5xl mx-auto border-slate-700">
                <div class="space-y-1">
                    <h2 class="text-xl font-bold text-white inline-flex items-center gap-2">
                        <span>🌐</span> Jelajahi Virtual Tour Perpustakaan
                    </h2>
                    <p class="text-xs text-slate-400">Gunakan mouse atau usap layar HP kamu untuk melihat sekeliling ruangan 360° secara digital.</p>
                </div>
                <div>
                    <span class="px-3 py-1 text-xs rounded-full bg-indigo-500/15 text-indigo-400 border border-indigo-500/30 font-medium">
                        Mode Interaktif
                    </span>
                </div>
            </div>

            <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden p-2 shadow-lg max-w-5xl mx-auto sm:px-4 lg:px-6 space-y-6">
                {{-- 16:9 aspect ratio wrapper --}}
                <div class="relative w-full" style="padding-bottom: 56.25%;">
                    {{-- Loading placeholder --}}
                    <div class="absolute inset-0 flex items-center justify-center bg-slate-900 text-slate-400 animate-pulse rounded-lg z-0">
                        <span>Memuat Virtual Tour 360°...</span>
                    </div>
                    {{-- Iframe fills the ratio box --}}
                    <iframe 
                        src="https://tour.panoee.net/6834b47078c1e1dde01fae31/6834b6663a317743ab1d59cb" 
                        class="absolute inset-0 w-full h-full border-0 rounded-lg z-10"
                        allow="gyroscope; accelerometer; fullscreen; vr"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>