# 🌱 Sapu‑App

**Website manajemen distribusi pemupukan** yang dibangun menggunakan [Laravel](https://laravel.com/).

---

## 📌 Deskripsi  
Aplikasi ini dirancang untuk membantu petani dan pihak terkait dalam mengelola produk pemupukan:  
- Memantau pengajuan pupuk 
- Melacak stok pupuk 
- Mengelola penerimaan pupuk ke petni

---

## 🚀 Fitur Utama  
- CRUD (Create, Read, Update, Delete) produk pupuk
- Pemantauan pengajuan pupuk  
- Pelacakan stok dan status distribusi  
- Autentikasi pengguna (login/register)  
- Dashboard ringkasan statistik penggunaan dan distribusi
- Pembayaran pupuk oline
- Pemantauan penyaluran pupuk

---

## ⚙️ Teknologi  
- **Backend**: Laravel (PHP)  
- **Frontend**: Blade + Tailwind CSS + JavaScript (Vite)  
- **Database**: MySQL   

---

## 🛠️ Persiapan & Instalasi  

Ikuti langkah-langkah berikut untuk menjalankan proyek ini secara lokal:

1. **Clone repository:**
   ```bash
   git clone https://github.com/puj16/Sapu-App.git
   cd Sapu-App
2. **Install dependency Laravel via Composer:**
   ```bash
   composer install
3. **Install dependency frontend (Vite) via NPM:**
   ```bash
   npm install
4. **Salin file konfigurasi lingkungan dan buat kunci aplikasi:**
   ```bash
   bash
   Copy
   Edit
   cp .env.example .env
   php artisan key:generate
5. **Edit file .env untuk sesuaikan konfigurasi database dan lainnya. Contoh konfigurasi:**
   ```bash
   env
   Copy
   Edit
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sapuapp_db
   DB_USERNAME=root
   DB_PASSWORD=
6. **Buat database baru di MySQL sesuai `.env`, lalu jalankan migrasi:**
   ```bash
   php artisan migrate
7. **Akses aplikasi di browser:**
   ```bash
   http://127.0.0.1:8000


