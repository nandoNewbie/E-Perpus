# E-Perpus SMP N Galas Malang 📚✨

[![Laravel Version](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.4-777BB4.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)

**E-Perpus SMP N Galas Malang** adalah sistem informasi manajemen perpustakaan berbasis web yang dirancang khusus untuk mempermudah pengelolaan sirkulasi buku, manajemen anggota, serta proses peminjaman dan pengembalian secara efisien, responsif, dan *real-time*.

---

## 🚀 Fitur Utama

* **👥 Sistem Multi-Role (RBAC)**
  * **Admin/Pustakawan:** Akses penuh untuk mengelola data master buku, mengimpor data anggota, memvalidasi sirkulasi, dan mengatur denda siswa.
  * **Siswa/Member:** Menjelajahi katalog, melihat rekomendasi pintar, mengajukan peminjaman, dan mengubah password akun secara mandiri.
* **📖 Manajemen Buku & Anggota Terpusat**
  * *Import/Upsert Massal* data buku langsung dari file Excel/CSV hingga kapasitas 10MB untuk efisiensi input data awal.
  * Jalur registrasi publik ditutup demi keamanan; akun siswa dibuat terpusat oleh Admin dengan password default `password123`.
* **⚡ Sirkulasi Real-Time (Powered by Livewire)**
  * Transaksi peminjaman dan pengembalian diproses instan tanpa muat ulang halaman (*zero reload*).
  * **Proteksi Buku Referensi:** Buku berkategori 'Referensi' otomatis terkunci oleh sistem (Hanya Baca di Tempat) dan tombol pinjam dihilangkan.
* **💰 Kalkulator Denda Otomatis**
  * Menghitung keterlambatan otomatis berdasarkan tanggal jatuh tempo yang presisi menggunakan zona waktu **WIB (Asia/Jakarta)**.
  * Pelacakan log sirkulasi denda menggunakan status dinamis: `none` (tepat waktu), `Belum Lunas`, dan `Lunas`.
* **🎯 Sistem Rekomendasi Buku Pintar**
  * Dashboard beranda memisahkan secara dinamis antara **Buku Terbaru** (arsip terbaru) dan **Buku Populer** (paling sering dipinjam).
* **🔒 Keamanan Akun**
  * Fitur interaktif *Show/Hide Password* menggunakan Alpine.js pada form pembaruan password untuk memudahkan siswa memantau ketikan mereka.

---

## 🛠️ Tech Stack

Aplikasi ini dibangun menggunakan ekosistem komponen modern berikut:

| Komponen | Teknologi | Keterangan |
| :--- | :--- | :--- |
| **Backend Framework** | Laravel 11.x | Core system & routing |
| **Runtime Environment**| PHP 8.4 | Versi PHP terbaru |
| **Database** | MySQL | Penyimpanan data relasional |
| **Reactivity Layer** | Livewire 3 & Alpine.js | Proses asinkronous & komponen interaktif |
| **CSS Framework** | Tailwind CSS | Desain antarmuka responsif |
| **Library Pendukung** | Maatwebsite/Laravel-Excel | Handler import data massal |

---

## ⚙️ Panduan Instalasi Lokal

Ikuti langkah-langkah di bawah ini untuk memasang dan menjalankan proyek di lingkungan *development* kamu:

### 1. Persiapan Repositori & Dependensi
Mulai dengan mengkloning proyek dan memasang pustaka yang diperlukan:
```bash
# Clone repositori ini
git clone [https://github.com/USERNAME_KAMU/NAMA_REPOSITORI.git](https://github.com/USERNAME_KAMU/NAMA_REPOSITORI.git)

# Masuk ke direktori proyek
cd NAMA_REPOSITORI

# Install dependensi PHP (Composer)
composer install

# Install dependensi Frontend (NPM)
npm install && npm run build

```

### 2. Konfigurasi Environment (`.env`)

Salin file template environment bawaan Laravel:

```bash
cp .env.example .env

```

Buka file `.env` yang baru dibuat, sesuaikan kredensial database lokal kamu, dan pastikan konfigurasi zona waktu Indonesia Barat (WIB) sudah aktif:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_kamu
DB_USERNAME=root
DB_PASSWORD=

APP_TIMEZONE=Asia/Jakarta

```

### 3. Inisialisasi Aplikasi

Generate kunci enkripsi aplikasi serta jalankan migrasi tabel beserta datanya:

```bash
# Generate app key
php artisan key:generate

# Jalankan migrasi tabel dan seeder awal
php artisan migrate --seed

```

> 💡 **Informasi Login Awal**
> Setelah proses database seeding selesai, kamu bisa login pada route `/admin/login` menggunakan akun pengujian berikut:
> * **Email:** `test@example.com`
> * **Password:** `password`
> 
> 

### 4. Jalankan Aplikasi

Nyalakan server lokal Laravel untuk menguji aplikasi di browser:

```bash
php artisan serve

```

Buka browser kamu dan akses alamat: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## ⚠️ Catatan Penting Server (`php.ini`)

> **PENTING:** Karena sistem ini mendukung fitur **Import Massal Excel hingga 10MB**, pastikan konfigurasi server PHP lokal kamu (XAMPP / Laragon) disesuaikan agar tidak terkena kendala pembatasan file atau batas waktu eksekusi (*timeout*).

Silakan buka berkas `php.ini` server kamu dan perbarui baris konfigurasi berikut:

```ini
; Tingkatkan batas waktu eksekusi (dalam detik) agar import data tidak timeout
max_execution_time = 600

; Sesuaikan batas upload file untuk mendukung file Excel kapasitas besar
upload_max_filesize = 20M
post_max_size = 25M

```

> 🔒 **Setelah Mengubah php.ini:** > 1. Restart service Apache/Nginx pada control panel XAMPP/Laragon kamu.
> 2. Jalankan perintah `php artisan config:clear` di terminal proyek agar konfigurasi baru dimuat ulang secara bersih.

---

Dibuat dengan 💻 dan ☕ untuk kemajuan literasi SMP N Galas Malang.

```

```
