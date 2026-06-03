# E-Perpus SMP N Galas Malang 📚✨

**E-Perpus SMP N Galas Malang** adalah sistem informasi manajemen perpustakaan berbasis web yang dirancang khusus untuk mempermudah pengelolaan sirkulasi buku, manajemen anggota, serta proses peminjaman dan pengembalian secara efisien, responsif, dan *real-time*.

---

## 🚀 Fitur Utama

* **👥 Sistem Multi-Role (RBAC)**
    * **Admin/Pustakawan:** Memiliki akses penuh untuk mengelola data master buku, mengimpor data anggota, memvalidasi sirkulasi, dan mengatur sistem denda.
    * **Siswa/Member:** Dapat menjelajahi katalog buku, melihat rekomendasi, mengajukan peminjaman, serta mengubah password akun secara mandiri.
* **📖 Manajemen Buku & Anggota Kontrol Penuh**
    * Fitur *Import/Upsert Massal* data buku langsung dari file Excel/CSV hingga kapasitas 10MB untuk efisiensi input data awal.
    * Registrasi publik ditutup demi keamanan; semua data anggota diinput secara terpusat oleh Admin dengan sistem password default (`password123`).
* **⚡ Alur Sirkulasi Real-Time (Powered by Livewire)**
    * Proses pengajuan peminjaman dan pengembalian buku diproses secara instan tanpa perlu memuat ulang halaman (*zero reload*).
    * **Sistem Proteksi Buku Referensi:** Buku dengan kategori 'Referensi' terkunci secara otomatis oleh sistem (Hanya Baca di Tempat) dan tombol pinjam dihilangkan demi mematuhi kebijakan perpustakaan.
* **💰 Kalkulator Denda Otomatis**
    * Sistem menghitung keterlambatan secara otomatis berdasarkan tanggal jatuh tempo yang presisi menggunakan zona waktu **WIB (Asia/Jakarta)**.
    * Pelacakan log sirkulasi denda menggunakan status dinamis: `none` (tepat waktu), `Belum Lunas`, dan `Lunas`.
* **🎯 Sistem Rekomendasi Buku Pintar**
    * Halaman beranda menampilkan dashboard rekomendasi otomatis yang memisahkan antara **Buku Terbaru** yang baru saja di-arsipkan dan **Buku Populer** berdasarkan statistik yang paling sering dipinjam oleh siswa.
* **🔒 Keamanan Akun**
    * Dilengkapi fitur interaktif *Show/Hide Password* menggunakan Alpine.js pada form pembaruan password untuk memudahkan siswa menjaga keamanan akun mereka.

---

## 🛠️ Tech Stack

Aplikasi ini dibangun menggunakan ekosistem modern Laravel bergaya TALL Stack:

* **Framework Utama:** Laravel 11
* **Runtime:** PHP 8.4
* **Database:** MySQL
* **Frontend & Reactivity:** Livewire 3, Alpine.js, Tailwind CSS
* **Library Pendukung:** Maatwebsite/Laravel-Excel (Untuk kebutuhan import data massal)

---

## ⚙️ Panduan Instalasi Lokal

Ikuti langkah-langkah berikut untuk menjalankan proyek E-Perpus di komputer lokal kamu:

### 1. Clone Repositori
```bash
git clone [https://github.com/USERNAME_KAMU/NAMA_REPOSITORI.git](https://github.com/USERNAME_KAMU/NAMA_REPOSITORI.git)
cd NAMA_REPOSITORI
