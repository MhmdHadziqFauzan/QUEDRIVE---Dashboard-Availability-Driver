# QUEDRIVE

Dashboard antrian & ketersediaan driver Puninar Transport Trailer. Backend PHP membaca 4 database sumber (read-only) dan menyimpan isian dashboard ke database QUEDRIVE (MySQL).

## Struktur folder

```
.env                     koneksi database (password) - jangan dibagikan
app/
  lib.php                koneksi database sumber, cache, aturan List Driver / Master / SJ / SPK
  store.php              simpan antrian, setting, history, upload ke database QUEDRIVE
  queries/*.sql          query ke database sumber
  views/dashboard.php    tampilan dashboard (HTML + JavaScript)
public/                  web root (hanya folder ini yang bisa dibuka dari browser)
  index.php              halaman dashboard (memuat app/views/dashboard.php)
  assets/js/             library Excel SheetJS (xlsx.full.min.js)
  api/index.php          API /api/*
database/
  schema.sql             tabel QUEDRIVE (qd_*)
  buat_db.php            membuat database + tabel QUEDRIVE
scripts/cek_koneksi.php  tes koneksi & struktur tabel database sumber
server/
  router.php             router untuk server bawaan PHP (di laptop)
  nginx-quedrive.conf    contoh konfigurasi Nginx (di server)
storage/                 cache API & hasil cek koneksi (dibuat otomatis)
```

## Menjalankan di laptop

Dari folder project:

```
php -S 127.0.0.1:8000 -t public server/router.php
```

Lalu buka http://127.0.0.1:8000

Pertama kali (atau setelah schema.sql berubah): `php database/buat_db.php`
