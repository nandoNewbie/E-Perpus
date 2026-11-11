# E-Perpus SMP N Galas Malang 📚✨

[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.3%2B-777BB4.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)

**E-Perpus SMP N Galas Malang** adalah sistem informasi manajemen perpustakaan berbasis web yang dirancang khusus untuk mempermudah pengelolaan sirkulasi buku, manajemen anggota, serta proses peminjaman dan pengembalian secara efisien, responsif, dan *real-time*.

> 📘 **Akan meng-hosting aplikasi ini?** Baca [**DEPLOYMENT.md**](DEPLOYMENT.md) — berisi panduan lengkap cPanel/VPS, konfigurasi produksi, daftar masalah yang sudah diketahui, dan checklist go-live.

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
| **Backend Framework** | Laravel 12.x | Core system & routing |
| **Runtime Environment**| PHP 8.3+ | Diuji pada PHP 8.4 |
| **Database** | MySQL 8 / MariaDB 10.4+ | Penyimpanan data relasional |
| **Reactivity Layer** | Livewire 3 & Alpine.js | Proses asinkronous & komponen interaktif |
| **CSS Framework** | Tailwind CSS | Desain antarmuka responsif |
| **Library Pendukung** | Maatwebsite/Laravel-Excel | Handler import data massal |

---

## 🌿 Kondisi Branch

Repositori ini memiliki tiga branch, dan **belum ada satu branch pun yang memuat seluruh perbaikan**:

| Branch | Isi | Penyimpanan cover buku |
| :--- | :--- | :--- |
| `main` | Versi dasar | Cloudflare R2 (cloud) |
| `demo-ngrok` | Logika aplikasi paling lengkap (blokir pinjam saat denda belum lunas, perbaikan hitung denda, `payFine()`) | Disk lokal server |
| `debug` | `main` + migrasi tabel `sessions` & `password_reset_tokens`, serta dukungan PWA | Cloudflare R2 (cloud) |

Untuk produksi, disarankan menggabungkan `demo-ngrok` dengan `debug`. Penjelasan lengkap ada di [DEPLOYMENT.md bagian 2](DEPLOYMENT.md).

---

## ⚙️ Panduan Instalasi Lokal

Ikuti langkah-langkah di bawah ini untuk memasang dan menjalankan proyek di lingkungan *development* kamu.

### 0. Prasyarat

* **PHP 8.3** atau lebih baru, dengan extension berikut aktif:
  `gd`, `zip`, `pdo_mysql`, `mbstring`, `openssl`, `curl`, `fileinfo`, `bcmath`, `tokenizer`, `xml`, `ctype`
* **Composer 2.x**
* **Node.js 18+** dan **npm**
* **MySQL 8** atau **MariaDB 10.4+** (tersedia di XAMPP / Laragon)

### 1. Persiapan Repositori & Dependensi

Mulai dengan mengkloning proyek dan memasang pustaka yang diperlukan:

```bash
# Clone repositori ini
git clone https://github.com/nandoNewbie/E-Perpus.git

# Masuk ke direktori proyek
cd E-Perpus

# Install dependensi PHP (Composer)
composer install

# Install dependensi Frontend (NPM)
npm install
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
DB_DATABASE=db_eperpus
DB_USERNAME=root
DB_PASSWORD=

APP_TIMEZONE=Asia/Jakarta

# Branch main & demo-ngrok belum memiliki tabel `sessions`,
# sehingga wajib memakai driver file. Pada branch `debug`
# nilai ini boleh diisi `database`.
SESSION_DRIVER=file
```

### 3. Inisialisasi Aplikasi

```bash
# Generate app key
php artisan key:generate

# Jalankan migrasi tabel
php artisan migrate

# Buat symlink agar file upload (cover buku) bisa diakses publik
php artisan storage:link
```

> ⚠️ **Jangan jalankan `php artisan db:seed`.** Seeder bawaan hanya membuat satu user dummy (`test@example.com`) dengan role `siswa` yang tidak dipakai aplikasi ini.

### 4. Jalankan Aplikasi

Buka **dua terminal** terpisah:

```bash
# Terminal 1 — server Laravel
php artisan serve
```

```bash
# Terminal 2 — build asset & watch perubahan
npm run dev
```

Buka browser kamu dan akses alamat: [http://127.0.0.1:8000](http://127.0.0.1:8000)

> Untuk produksi, cukup jalankan `npm run build` sekali — tidak perlu `npm run dev`.

---

## 🔑 Akun & Login

| Peran | Halaman login | Keterangan |
| :--- | :--- | :--- |
| **Pustakawan / Admin** | `/admin/login` | Email: `adminpustakawan@gmail.com` |
| **Siswa & Guru** | `/login` | Password default: `password123` |

* **Pendaftaran publik sengaja ditutup.** Akun siswa dibuat terpusat oleh Admin, atau melalui import massal dari Excel.
* Password akun Admin tidak dicantumkan di repositori ini. Silakan minta langsung kepada pembuat aplikasi, lalu segera ganti.
* Jika database masih kosong, belum ada akun apa pun — buat akun pustakawan pertama secara manual lewat database atau `php artisan tinker`.

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
memory_limit = 256M
```

🔒 **Setelah mengubah `php.ini`:**

1. Restart service Apache/Nginx pada control panel XAMPP/Laragon kamu.
2. Jalankan perintah `php artisan config:clear` di terminal proyek agar konfigurasi baru dimuat ulang secara bersih.

---

## 🌐 Hosting & Produksi

Panduan hosting ke cPanel, VPS, atau layanan lain — termasuk konfigurasi `.env` produksi, pemindahan database, penanganan cover buku, daftar masalah yang sudah diketahui, dan checklist go-live — tersedia lengkap di:

📄 **[DEPLOYMENT.md](DEPLOYMENT.md)**

---

Dibuat dengan 💻 dan ☕ untuk kemajuan literasi SMP N Galas Malang.
