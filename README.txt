====================================================================
               PANDUAN LENGKAP & INFORMASI APLIKASI
                 SIRAB - Sistem RAB Bangunan
====================================================================

Kepada Yth. Bapak/Ibu Dosen Penguji,
Berikut adalah panduan lengkap serta informasi teknis terkait 
aplikasi Sistem Informasi RAB (Rencana Anggaran Biaya).

Aplikasi ini dibangun untuk mengelola proyek, konsultan, konsumen, 
serta administrasi dari pihak Pekerjaan Umum (PU).

--------------------------------------------------------------------
A. TEKNOLOGI YANG DIGUNAKAN (TECH STACK)
--------------------------------------------------------------------
- Framework Backend : Laravel 10 / 11
- Bahasa Pemrograman: PHP 8.2
- Database Server   : MySQL
- Asset Bundler     : Vite (Node.js)
- UI / Frontend     : Blade Templating dengan Bootstrap/Tailwind

--------------------------------------------------------------------
B. PERSYARATAN SISTEM (REQUIREMENTS)
--------------------------------------------------------------------
Pastikan komputer/laptop telah terinstall:
- XAMPP (dengan PHP minimal versi 8.2 dan MySQL)
- Composer
- Node.js (versi 18+ atau 20 LTS)

--------------------------------------------------------------------
C. PANDUAN INSTALASI & PERSIAPAN DATABASE
--------------------------------------------------------------------
1. Nyalakan modul Apache dan MySQL pada aplikasi XAMPP.
2. Buka browser dan akses: http://localhost/phpmyadmin
3. Buat database baru dengan nama persis: db_sirab_app
4. Pilih menu "Import", lalu masukkan file backup database berikut
   yang sudah disertakan di dalam folder flashdisk:
   => database_sirab_kampus.sql
5. Klik "Go" atau "Import" untuk menyelesaikan pengisian data.

*Catatan Tambahan (Opsional):
Jika file .env belum ada, silahkan salin file .env.example menjadi 
.env, kemudian jalankan perintah "php artisan key:generate".
Namun untuk versi pengumpulan ini, file .env sudah dikonfigurasi.

--------------------------------------------------------------------
D. CARA MENJALANKAN APLIKASI
--------------------------------------------------------------------
Aplikasi ini bersifat plug-and-play untuk pengujian lokal:
1. Buka Terminal / Command Prompt (CMD).
2. Arahkan direktori (cd) ke dalam folder aplikasi ini.
3. Ketikkan perintah berikut untuk menyalakan server:
   => php artisan serve
4. Buka browser dan akses alamat berikut:
   => http://127.0.0.1:8000

--------------------------------------------------------------------
E. DAFTAR AKUN AKSES (LOGIN)
--------------------------------------------------------------------
Aplikasi ini memiliki 3 hak akses utama (Role). Silakan gunakan
kredensial berikut untuk melakukan pengujian sistem:

1. ADMIN PU (Pengelola Dinas)
   - Email    : admin@pu.com
   - Password : password

2. KONSULTAN (Pembuat RAB & Pelaksana)
   - Email    : konsultan@konsultan.com
   - Password : konsultan

3. KONSUMEN (Masyarakat / Pemilik Proyek)
   - Email    : konsumen@konsumen.com
   - Password : password

--------------------------------------------------------------------
F. STRUKTUR & DATA SEEDER
--------------------------------------------------------------------
Di dalam sistem ini terdapat beberapa data seeder default:
- RolePermissionSeeder : Mengatur peran admin_pu, konsultan, konsumen
- UserSeeder           : Membuat akun user default di atas
- MasterDataSeeder     : Mengisi master data awal sistem
- KategoriPekerjaanSeeder
- DummyTransactionSeeder

Semua data di atas sudah otomatis terisi di dalam file SQL yang
Bapak/Ibu import.

--------------------------------------------------------------------
G. INFORMASI PENGEMBANG
--------------------------------------------------------------------
Pengembang  : Andre Ryan Baskoro
GitHub      : https://github.com/andreryanbaskoro
Repositori  : https://github.com/andreryanbaskoro/sirab-app.git

Demikian panduan ini dibuat. Terima kasih atas perhatian dan waktu 
Bapak/Ibu Dosen Penguji.

Hormat saya,
Andre Ryan Baskoro
====================================================================
