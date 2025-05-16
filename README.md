# Neo Telemetri - Sistem Informasi Manajemen Absensi
<div align="center">
  <img src="/assets/images/NEO.png" alt="Neo Telemetri Logo" width="120"/>
  <h1>Neo Telemetri</h1>
  <p>Sistem Informasi Manajemen Absensi Digital</p>
</div>

## 📋 Tentang Neo Telemetri

Neo Telemetri adalah sistem informasi manajemen absensi digital yang dirancang untuk memudahkan pengelolaan kehadiran dalam berbagai kegiatan organisasi. Sistem ini menyediakan solusi terintegrasi untuk:

- 📍 Manajemen presensi kegiatan
- 📅 Pengelolaan jadwal rapat
- 🔄 Pengaturan jadwal piket
- 📊 Pelaporan kehadiran real-time

## ✨ Fitur Utama

- **Absensi Digital**
  - Sistem QR Code untuk presensi
  - Pencatatan lokasi kehadiran
  - Verifikasi kehadiran real-time

- **Manajemen Rapat**
  - Penjadwalan rapat
  - Pencatatan kehadiran peserta
  - Pelaporan hasil rapat

- **Sistem Piket**
  - Penjadwalan otomatis
  - Notifikasi jadwal
  - Rotasi jadwal piket

## 🚀 Teknologi

- **Backend**: Laravel 12.0
- **Frontend**: TailwindCSS
- **Database**: MySQL
- **Authentication**: Google OAuth
- **Deployment**: Docker + FrankenPHP

## 🛠️ Instalasi

1. Clone repositori
```bash
git clone https://github.com/username/neo-telemetri.git
````

2. Install dependensi

```bash
composer install
npm install
```

3. Salin file environment

```bash
cp .env.example .env
```

4. Generate key aplikasi

```bash
php artisan key:generate
```

5. Jalankan migrasi database

```bash
php artisan migrate
```

6. Compile assets

```bash
npm run build
```

7. Jalankan server

```bash
php artisan serve
```

## 📝 Lisensi

Hak Cipta © 2024 Neo Telemetri. Dilindungi Undang-Undang.

## 👥 Tim Pengembang

-   Tim Neo Telemetri
-   Kontributor Komunitas

## 📞 Kontak

Untuk informasi lebih lanjut, silakan hubungi tim pengembang melalui:

-   Email: neotelemetri@gmail.com
-   Website: www.neotelemetri.id
