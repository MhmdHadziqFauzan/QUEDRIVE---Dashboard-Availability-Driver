<?php
/* Router untuk server bawaan PHP (pengganti Nginx saat di laptop), dijalankan dari folder project:
       php -S 127.0.0.1:8000 -t public server/router.php
   /api/* -> public/api/index.php, selain itu ditangani server bawaan dari folder public/ (halaman utama: public/index.php). */
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
if (str_starts_with($path, '/api/')) {
    require __DIR__ . '/../public/api/index.php';
    return true;
}
return false;
