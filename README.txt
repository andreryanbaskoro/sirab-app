====================================================================
               PANDUAN INSTALASI & PENGGUNAAN APLIKASI
                 SIRAB - Sistem RAB Bangunan
====================================================================

Kepada Yth. Bapak/Ibu Dosen Penguji,
Berikut adalah panduan singkat untuk menjalankan aplikasi SIRAB 
pada komputer/laptop lokal (XAMPP).

--------------------------------------------------------------------
A. PERSIAPAN DATABASE
--------------------------------------------------------------------
1. Pastikan XAMPP (Apache & MySQL) sudah berjalan.
2. Buka browser dan akses: http://localhost/phpmyadmin
3. Buat database baru dengan nama: db_sirab_app
4. Pilih menu "Import", kemudian masukkan file database yang 
   telah disediakan di dalam folder ini, yaitu:
   => database_sirab_kampus.sql
5. Klik "Go" atau "Import" untuk menyelesaikan proses.

--------------------------------------------------------------------
B. MENJALANKAN APLIKASI
--------------------------------------------------------------------
Aplikasi ini sudah dikonfigurasi dan siap dijalankan (plug-and-play). 
Anda tidak perlu menjalankan 'composer install' kecuali terjadi error.

1. Buka Terminal / Command Prompt (CMD).
2. Arahkan direktori (cd) ke dalam folder aplikasi ini.
3. Ketikkan perintah berikut untuk menyalakan server lokal:
   => php artisan serve
4. Buka browser dan akses URL berikut:
   => http://127.0.0.1:8000

--------------------------------------------------------------------
C. AKUN AKSES (LOGIN)
--------------------------------------------------------------------
Berikut adalah data akun default yang dapat Bapak/Ibu gunakan 
untuk masuk ke dalam sistem dengan berbagai peran:

1. Akun Admin PU (Dinas)
   - Email    : admin@pu.com
   - Password : password

2. Akun Konsultan (Pembuat RAB)
   - Email    : konsultan@konsultan.com
   - Password : konsultan

3. Akun Konsumen (Masyarakat)
   - Email    : konsumen@konsumen.com
   - Password : password

--------------------------------------------------------------------
D. INFORMASI TAMBAHAN
--------------------------------------------------------------------
- Aplikasi ini dibangun menggunakan Framework Laravel.
- File konfigurasi (.env) sudah disesuaikan secara default untuk
  kebutuhan pengujian lokal.

Demikian panduan ini dibuat. Terima kasih atas perhatian 
dan waktu Bapak/Ibu.

Hormat saya,
Mahasiswa
