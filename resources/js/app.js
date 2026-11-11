// Alpine sengaja TIDAK di-import di file ini.
//
// Livewire 3 sudah menyertakan Alpine secara bawaan. Meng-import Alpine lagi
// di sini membuat dua instance berjalan bersamaan, sehingga muncul warning
// "Detected multiple instances of Alpine running" di console browser dan
// komponen x-data bisa berperilaku tidak konsisten.
//
// Seluruh direktif Alpine (x-data, x-show, x-on, dsb.) tetap berfungsi normal
// memakai Alpine bawaan Livewire — tidak ada yang perlu diubah di Blade.
//
// Jika suatu saat perlu mendaftarkan komponen, store, atau plugin Alpine,
// gunakan event `alpine:init` dan JANGAN meng-import Alpine kembali:
//
//     document.addEventListener('alpine:init', () => {
//         Alpine.data('namaKomponen', () => ({
//             open: false,
//             toggle() { this.open = !this.open; },
//         }));
//     });
