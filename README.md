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

'''
git clone [https://github.com/USERNAME_KAMU/NAMA_REPOSITORI.git](https://github.com/USERNAME_KAMU/NAMA_REPOSITORI.git)
cd NAMA_REPOSITORI


2. Install Dependensi Composer & NPM
Bash
composer install
npm install && npm run dev

4. Konfigurasi Environment File
Salin file .env.example menjadi .env:

Bash
cp .env.example .env
Buka file .env, sesuaikan pengaturan database dan pastikan konfigurasi zona waktu Indonesia Barat (WIB) sudah terpasang:

Cuplikan kode
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_kamu
DB_USERNAME=root
DB_PASSWORD=

APP_TIMEZONE=Asia/Jakarta
4. Generate Application Key
Bash
php artisan key:generate

5. Jalankan Migrasi & Seeder Database
Bash
php artisan migrate --seed
💡 Setelah seeder berhasil dijalankan, kamu bisa login di /admin/login pertama kali menggunakan akun bawaan:

Email: test@example.com

Password: password

6. Jalankan Server Lokal
Bash
php artisan serve
Aplikasi sekarang dapat diakses melalui browser di alamat http://127.0.0.1:8000.

⚠️ Catatan Penting untuk Server (Konfigurasi php.ini)
Karena aplikasi ini mendukung fitur Import Massal Excel dengan kapasitas file besar (hingga 10MB), pastikan server PHP lokal kamu (XAMPP / Laragon) tidak mengalami timeout atau pembatasan ukuran file.

Silakan buka dan ubah pengaturan file php.ini milikmu menjadi seperti ini:

Ini, TOML
; Tingkatkan batas waktu eksekusi agar import data banyak tidak timeout
max_execution_time = 600

; Tingkatkan batas ukuran upload file agar mendukung file Excel 10MB
upload_max_filesize = 20M
post_max_size = 25M
Jangan lupa untuk me-restart Apache/Nginx setelah mengubah file php.ini, lalu jalankan perintah php artisan config:clear pada terminal proyek.

Dibuat dengan 💻 dan ☕ untuk kemajuan literasi SMP N Galas Malang.
