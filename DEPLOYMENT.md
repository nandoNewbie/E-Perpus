# Panduan Hosting & Serah Terima — E-Perpus SMP N Galas Malang

Dokumen ini ditujukan untuk **pihak sekolah, guru IT, atau developer** yang akan
melanjutkan dan meng-hosting aplikasi ini. Isinya mencakup kebutuhan server,
langkah instalasi (cPanel maupun VPS), konfigurasi, serta daftar masalah yang
sudah diketahui beserta solusinya.

Aplikasi dibangun dengan **Laravel 12** + **Livewire 3** + **Tailwind CSS**,
dengan database **MySQL/MariaDB**.

---

## 0. Ringkasan Cepat

| Hal | Nilai |
| :--- | :--- |
| Framework | Laravel 12.62 |
| PHP minimal | **8.3** (sudah diuji di 8.4) |
| Database | MySQL 8 / MariaDB 10.4+ |
| Document root | folder **`public/`** (WAJIB) |
| Butuh cron job? | **Tidak** |
| Butuh queue worker? | **Tidak** |
| Butuh Redis? | Tidak |
| Ukuran database | sangat kecil (< 1 MB) |

> **Catatan penting:** aplikasi ini **tidak** memerlukan cron job maupun queue
> worker. Tidak ada scheduled task (`routes/console.php` kosong) dan tidak ada
> job antrian (`ShouldQueue`). Cukup web server + PHP + MySQL.

---

## 1. Kebutuhan Server (Requirements)

### 1.1 PHP

Minimal **PHP 8.3**. Dependensi pada `composer.lock` dikunci ke platform PHP
8.3.32, sehingga versi di bawahnya akan gagal dipasang.

Extension PHP yang wajib aktif:

```
gd          zip         pdo_mysql    mbstring
openssl     curl        fileinfo     bcmath
tokenizer   xml         ctype        json
```

`gd` dan `zip` ditulis eksplisit di `composer.json`. `zip` dipakai fitur import
Excel, `gd` untuk pemrosesan gambar.

### 1.2 Software lain

* **Composer 2.x** — untuk memasang dependensi PHP.
* **Node.js 18+ dan npm** — **hanya** untuk build asset CSS/JS. Tidak perlu ada
  di server produksi bila asset di-build di komputer lokal (lihat bagian 3.6).
* **MySQL 8** atau **MariaDB 10.4+**.
* **Apache** dengan `mod_rewrite` (file `public/.htaccess` sudah tersedia), atau
  **Nginx** dengan konfigurasi Laravel standar.

### 1.3 Pengaturan `php.ini`

Fitur import data buku dari Excel mendukung file sampai 10MB, sehingga batas
default PHP perlu dinaikkan:

```ini
upload_max_filesize = 20M
post_max_size       = 25M
max_execution_time  = 600
memory_limit        = 256M
```

Di cPanel, pengaturan ini ada di **MultiPHP INI Editor** atau **Select PHP
Version → Options**. Setelah diubah, jalankan `php artisan config:clear`.

---

## 2. PENTING — Kondisi Branch Saat Serah Terima

Repository ini memiliki **tiga branch**, dan **tidak ada satu branch pun yang
berisi semua perbaikan**. Harap dibaca sebelum memilih branch untuk di-deploy.

| Branch | Isi | Cover buku disimpan di |
| :--- | :--- | :--- |
| `main` | Versi dasar | Cloudflare R2 (cloud) |
| `demo-ngrok` | **Logika aplikasi paling lengkap** | Disk lokal server |
| `debug` | `main` + perbaikan session & PWA | Cloudflare R2 (cloud) |

### Yang hanya ada di `demo-ngrok`

* **Blokir peminjaman** bagi siswa yang masih punya denda berstatus `Belum Lunas`.
* **Perbaikan bug perhitungan denda** — jumlah hari terlambat sebelumnya bisa
  bernilai negatif; diperbaiki dengan normalisasi tanggal dan `abs()`.
* **Fitur `payFine()`** untuk menandai denda menjadi `Lunas`.
* Cover buku dibaca dari disk lokal, bukan cloud.

### Yang hanya ada di `debug`

* **Migrasi tabel `password_reset_tokens`** — tanpa ini fitur "Lupa Password"
  **error HTTP 500**.
* **Migrasi tabel `sessions`** — memungkinkan `SESSION_DRIVER=database`.
* **Dukungan PWA** — aplikasi bisa dipasang ke home screen HP (ikon, manifest,
  service worker).

### Rekomendasi

Untuk produksi, sebaiknya **gabungkan `demo-ngrok` dengan `debug`**:

```bash
git checkout demo-ngrok
git merge debug
```

Konflik yang mungkin muncul hanya pada `app/Models/Book.php` (bagian cover).
Pilih versi `demo-ngrok` bila cover disimpan di disk lokal.

---

## 3. Instalasi di cPanel (Shared Hosting)

### 3.1 Upload kode

Letakkan kode **di luar** `public_html`, misalnya `/home/namauser/eperpus`.
Bisa lewat Git (menu *Git Version Control*) atau upload ZIP lalu extract.

### 3.2 Arahkan document root ke `public/`

Laravel **wajib** diakses lewat folder `public/`. Ada dua cara:

**Cara A (disarankan)** — ubah document root domain menjadi
`/home/namauser/eperpus/public` lewat menu **Domains** di cPanel.

**Cara B** — bila hosting tidak mengizinkan ubah document root: salin seluruh
isi folder `public/` ke `public_html`, lalu edit `public_html/index.php` dan
sesuaikan dua baris path berikut agar menunjuk ke folder aplikasi:

```php
require __DIR__.'/../eperpus/vendor/autoload.php';
$app = require_once __DIR__.'/../eperpus/bootstrap/app.php';
```

> **Jangan** meletakkan seluruh isi project di `public_html`. File `.env` yang
> berisi password database akan bisa diakses publik.

### 3.3 Pasang dependensi

Lewat **Terminal** cPanel:

```bash
cd ~/eperpus
composer install --no-dev --optimize-autoloader
```

Bila hosting tidak menyediakan Terminal/Composer, jalankan perintah di atas di
komputer lokal lalu upload folder `vendor/` hasilnya ke server.

### 3.4 Buat database

Lewat menu **MySQL Databases**: buat database, buat user, lalu beri user
tersebut **ALL PRIVILEGES** pada database itu. Catat nama database, username,
dan password untuk dipakai di `.env`.

### 3.5 Konfigurasi `.env`

Salin `.env.example` menjadi `.env`, sesuaikan isinya (lihat bagian 5), lalu:

```bash
php artisan key:generate
```

### 3.6 Build asset CSS/JS

Shared hosting umumnya tidak punya Node.js. Build di komputer lokal:

```bash
npm install
npm run build
```

Lalu upload folder **`public/build/`** hasil build ke server. Folder ini masuk
`.gitignore` sehingga tidak ikut saat clone — **wajib di-upload manual**.

### 3.7 Migrasi database

```bash
php artisan migrate --force
```

> **JANGAN** menjalankan `php artisan db:seed` di produksi. Seeder hanya membuat
> user dummy `test@example.com` yang tidak diperlukan.

### 3.8 Symlink storage

```bash
php artisan storage:link
```

Bila hosting memblokir symlink, buat symlink manual lewat File Manager, atau
sebagai alternatif salin isi `storage/app/public` ke `public/storage`.

### 3.9 Hak akses folder

Folder berikut harus bisa ditulis oleh web server (`755`, atau `775` bila perlu):

```
storage/
storage/logs/
storage/framework/
bootstrap/cache/
```

### 3.10 Optimasi akhir

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

> Setiap kali `.env` diubah, jalankan ulang `php artisan config:cache`, atau
> perubahan tidak akan terbaca.

---

## 4. Instalasi di VPS / Cloud

Langkahnya sama dengan bagian 3, dengan catatan tambahan:

* Arahkan `root` Nginx atau `DocumentRoot` Apache ke folder `public/`.
* Pastikan `mod_rewrite` (Apache) aktif, atau pakai konfigurasi `try_files`
  standar Laravel untuk Nginx.
* Jalankan `chown -R www-data:www-data storage bootstrap/cache`.
* Pasang sertifikat HTTPS (Let's Encrypt / Certbot). **HTTPS wajib** bila ingin
  fitur PWA (pasang ke home screen HP) berfungsi.
* Bila memakai proxy, load balancer, atau Cloudflare: `trustProxies(at: '*')`
  sudah diaktifkan di `bootstrap/app.php`, sehingga URL otomatis memakai
  `https://` tanpa konfigurasi tambahan.

---

## 5. Konfigurasi `.env`

```env
APP_NAME="E-Perpus SMP N Galas Malang"
APP_ENV=production
APP_KEY=                      # diisi otomatis oleh php artisan key:generate
APP_DEBUG=false               # WAJIB false di produksi
APP_URL=https://domain-sekolah.sch.id
APP_TIMEZONE=Asia/Jakarta     # WAJIB, dipakai perhitungan denda
APP_LOCALE=id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=user_database
DB_PASSWORD=password_database

SESSION_DRIVER=database       # lihat catatan di bawah
SESSION_LIFETIME=120

CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local

MAIL_MAILER=log               # ganti ke smtp bila butuh kirim email
```

### Catatan `SESSION_DRIVER`

* Pakai **`database`** hanya bila branch yang di-deploy sudah memiliki migrasi
  tabel `sessions` (branch `debug`, lihat bagian 2).
* Bila memakai `main` atau `demo-ngrok` tanpa migrasi tersebut, **wajib**
  memakai `SESSION_DRIVER=file`, kalau tidak aplikasi akan error.

### Catatan `APP_DEBUG`

`APP_DEBUG=true` di produksi akan menampilkan isi `.env` (termasuk password
database) pada halaman error. **Selalu set `false`.**

---

## 6. Database

### 6.1 Memindahkan data

File `db_eperpus.sql` di repository ini **kosong (0 byte)** dan hanya
placeholder. Data asli harus di-export terpisah dari komputer pembuat:

```bash
mysqldump -u root -p db_eperpus > db_eperpus_backup.sql
```

Lalu import di server lewat **phpMyAdmin**, atau:

```bash
mysql -u user_database -p nama_database < db_eperpus_backup.sql
```

> **Jangan commit file dump berisi data siswa ke GitHub.** Database memuat data
> pribadi ratusan siswa (nama, NIS, kelas). Simpan backup di tempat tertutup.

### 6.2 Isi data saat serah terima

| Tabel | Jumlah baris |
| :--- | ---: |
| `users` | 868 |
| `books` | 329 |
| `borrowings` | 13 |
| `return_logs` | 9 |

Rincian user: 862 siswa, 5 guru, 1 pustakawan.

---

## 7. Cover Buku

### 7.1 Kondisi saat ini

Dari 329 buku, hanya **10 buku** yang memiliki cover, dan **file gambarnya saat
ini tidak tersedia** — baik di repository maupun di cloud storage.

Penyebabnya: file hasil upload disimpan di `storage/app/public/` yang memang
sengaja **tidak ikut di-commit** ke Git (perilaku standar Laravel). Saat
repository di-clone, file gambar tidak ikut terbawa; database hanya menyimpan
nama filenya saja.

> Ini bukan bug. Folder upload memang tidak seharusnya masuk Git. Konsekuensinya,
> **setiap clone baru selalu tanpa cover** sampai filenya disalin manual atau
> di-upload ulang. Karena itu folder `storage/app/public/` wajib ikut di-backup
> (lihat bagian 11).

Pada branch **`demo-ngrok`** (penyimpanan disk lokal, opsi A di bawah), kondisi
ini sudah ditangani: `Book::getCoverUrlAttribute()` mengecek keberadaan file
terlebih dahulu, sehingga tampilan otomatis memakai placeholder "No Cover"
alih-alih ikon gambar rusak.

Pada branch `main` / `debug` yang memakai Cloudflare R2, pengecekan serupa belum
ada — mengecek keberadaan objek di cloud berarti satu HTTP request per buku,
yang terlalu mahal. Bila nanti tetap memakai R2, gunakan atribut `onerror` pada
tag `<img>` sebagai penggantinya.

### 7.2 Dua cara penyimpanan cover

Tergantung branch yang dipakai:

**A. Disk lokal server** (branch `demo-ngrok`) — **disarankan**. Paling
sederhana, tanpa layanan pihak ketiga dan tanpa biaya.

* Upload disimpan ke `storage/app/public/covers/`.
* Wajib menjalankan `php artisan storage:link`.
* Pastikan folder `storage/` bisa ditulis.

**B. Cloudflare R2** (branch `main` / `debug`) — penyimpanan cloud.

* Perlu mengisi kredensial di `.env`: `AWS_ACCESS_KEY_ID`,
  `AWS_SECRET_ACCESS_KEY`, `AWS_BUCKET`, `AWS_ENDPOINT`.
* URL publik saat ini masih *hardcoded* di `app/Models/Book.php`
  (`https://storage.perpusgalas.online/`). Domain tersebut milik pembuat
  aplikasi dan **kemungkinan tidak diperpanjang** — perlu diganti.
* Catatan: domain bawaan Cloudflare `*.r2.dev` **diblokir sebagian ISP di
  Indonesia** (mis. MyRepublic), sehingga custom domain memang diperlukan.

### 7.3 Cara memulihkan cover

**Bila file aslinya masih ada** (di komputer tempat dulu meng-upload) — ini cara
tercepat. Salin seluruh isi folder `covers/` dari komputer lama ke server:

```
storage/app/public/covers/
```

Nama file tidak perlu dicocokkan satu per satu: Laravel menyimpan cover dengan
nama acak yang sama persis dengan yang tercatat di database, jadi cukup salin
satu folder utuh dan cover langsung muncul kembali.

Pastikan symlink sudah dibuat:

```bash
php artisan storage:link
```

**Bila file aslinya sudah hilang** — upload ulang lewat menu admin
**Manajemen Buku → Edit**. Hanya 10 buku, jadi relatif cepat.

### 7.4 Mengecek cover mana yang hilang

```bash
php artisan tinker --execute="
foreach (App\Models\Book::whereNotNull('cover')->where('cover','<>','')->get() as \$b) {
    echo (\$b->cover_url ? '[ada]    ' : '[HILANG] ') . \$b->title . PHP_EOL;
}"
```

---

## 8. Akun & Login

| Peran | Cara masuk | Keterangan |
| :--- | :--- | :--- |
| Pustakawan / Admin | `/admin/login` | email: `adminpustakawan@gmail.com` |
| Siswa & Guru | `/login` | password default: `password123` |

* **Pendaftaran publik sengaja ditutup** demi keamanan. Akun siswa dibuat
  terpusat oleh admin, atau lewat import massal dari Excel.
* Password admin **tidak dicantumkan di dokumen ini** — mintalah langsung
  kepada pembuat aplikasi, dan **segera ganti** setelah serah terima.
* Siswa dapat mengganti password sendiri lewat halaman profil.

---

## 9. Masalah yang Sudah Diketahui (Known Issues)

| # | Masalah | Dampak | Solusi |
| :-- | :--- | :--- | :--- |
| 1 | Tabel `password_reset_tokens` tidak ada di `main` & `demo-ngrok` | Fitur "Lupa Password" **error 500** | Merge branch `debug` |
| 2 | Tabel `sessions` tidak ada di `main` & `demo-ngrok` | Harus memakai `SESSION_DRIVER=file`; user ter-logout tiap deploy ulang pada hosting berbasis container | Merge branch `debug` |
| 3 | File cover buku hilang | 10 buku tampil tanpa gambar | Lihat bagian 7 |
| 4 | `db_eperpus.sql` kosong | Tidak ada data awal | Lihat bagian 6.1 |
| 5 | `README.md` sudah tidak akurat | Menyebut Laravel 11 (asli 12) dan akun `test@example.com` yang tidak ada | Pakai dokumen ini sebagai acuan |
| 6 | Seeder membuat user dummy | Data sampah di produksi | Jangan jalankan `db:seed` |

---

## 10. Yang TIDAK Dibutuhkan

Agar tidak membuang waktu saat setup, berikut hal yang **tidak** diperlukan:

* Cron job / task scheduler — tidak ada scheduled task.
* Queue worker (`php artisan queue:work`) — tidak ada job antrian.
* Redis / Memcached — cache memakai database.
* Node.js di server produksi — cukup build asset di komputer lokal.
* WebSocket / Pusher — Livewire memakai HTTP biasa.

---

## 11. Perawatan (Maintenance)

### Backup

Minimal **mingguan**, backup dua hal:

1. Database (`mysqldump`, atau fitur Backup di cPanel).
2. Folder `storage/app/public/` (berisi cover buku hasil upload).

### Setelah update kode

```bash
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Bila ada perubahan tampilan, build ulang asset dan upload `public/build/`.

### Melihat error

Log tersimpan di `storage/logs/laravel.log`. Bila aplikasi menampilkan halaman
error 500 tanpa keterangan, file inilah yang pertama diperiksa.

---

## 12. Checklist Go-Live

- [ ] PHP 8.3+ dengan semua extension terpasang
- [ ] Document root mengarah ke folder `public/`
- [ ] `composer install --no-dev --optimize-autoloader` selesai
- [ ] `.env` terisi benar, `APP_DEBUG=false`, `APP_ENV=production`
- [ ] `php artisan key:generate` sudah dijalankan
- [ ] Database dibuat dan data berhasil di-import
- [ ] `php artisan migrate --force` selesai
- [ ] `php artisan storage:link` berhasil
- [ ] Folder `storage/` dan `bootstrap/cache/` bisa ditulis
- [ ] Folder `public/build/` sudah di-upload
- [ ] Pengaturan `php.ini` untuk import Excel sudah dinaikkan
- [ ] HTTPS aktif
- [ ] Password akun admin sudah diganti
- [ ] Uji coba: login admin, login siswa, pinjam buku, kembalikan buku, import Excel
- [ ] Backup pertama sudah dibuat

---

*Dokumen ini dibuat saat serah terima aplikasi dan menjelaskan kondisi apa
adanya, termasuk kekurangan yang belum sempat diperbaiki.*
