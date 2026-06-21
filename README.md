# 🏗️ SIRAB APP

Sistem Informasi RAB (Rencana Anggaran Biaya) berbasis Laravel untuk pengelolaan proyek, tukang, konsumen, dan administrasi PU.

---

## 📌 Tech Stack

![Laravel](https://img.shields.io/badge/Laravel-10%2F11-red?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2-blue?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-Database-orange?style=for-the-badge&logo=mysql)
![Node.js](https://img.shields.io/badge/Node.js-Vite-green?style=for-the-badge&logo=node.js)
![License](https://img.shields.io/badge/License-Private-lightgrey?style=for-the-badge)

---

## ⚙️ Requirements

Pastikan software berikut sudah terinstall:

- XAMPP (PHP **8.2.12** + MySQL)
- Composer
- Node.js (18+ / 20 LTS)
- Git

---

## 📥 Installation

### 1. Clone Repository

```bash
git clone https://github.com/andreryanbaskoro/nama-project.git
cd nama-project
````

---

### 2. Install Dependencies

```bash
composer install
npm install
```

---

### 3. Setup Environment

```bash
copy .env.example .env
```

Edit konfigurasi database:

```env
DB_DATABASE=sirab_app
DB_USERNAME=root
DB_PASSWORD=
```

---

### 4. Generate App Key

```bash
php artisan key:generate
```

---

## 🧠 Database Setup

Jalankan migration + seeder:

```bash
php artisan migrate:fresh --seed
```

---

## 🌱 Seeder Information

Project ini hanya menggunakan 2 seeder utama:

### 📌 RolePermissionSeeder

Membuat role:

* admin_pu
* kepala_tukang
* konsumen

### 📌 UserSeeder

Membuat user default:

* Admin PU
* 1 Kepala Tukang
* 3 Konsumen

---

## 🔗 Storage Setup

```bash
php artisan storage:link
```

---

## 🚀 Run Project

```bash
php artisan serve
```

Akses aplikasi:

```
http://127.0.0.1:8000
```

---

## 👤 Default Accounts

### 🛠 Admin PU

```
Email    : admin@pu.com
Password : password
```

### 👷 Kepala Tukang

```
Email    : tukang@tukang.com
Password : password
```

### 👤 Konsumen

```
Email    : konsumen@konsumen.com
Password : password
```

---

## ⚡ Quick Start (One Command)

```bash
git clone https://github.com/andreryanbaskoro/nama-project.git
cd nama-project

composer install
npm install

copy .env.example .env
php artisan key:generate

php artisan migrate:fresh --seed
php artisan storage:link

php artisan serve
```

---

## 📁 Project Structure (Simplified)

```
app/
database/
 └── seeders/
     ├── RolePermissionSeeder.php
     └── UserSeeder.php
resources/
routes/
```

---

## ⚠️ Notes

* Seeder lain tidak digunakan
* Database akan reset saat `migrate:fresh`
* Pastikan PHP sesuai versi (8.2+)

---

## 👨‍💻 Developer

**Andreryan Baskoro**

GitHub: [https://github.com/andreryanbaskoro](https://github.com/andreryanbaskoro)

```

---

Tinggal bilang aja 👍
```
