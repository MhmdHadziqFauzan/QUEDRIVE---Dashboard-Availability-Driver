<?php
/* Membuat database QUEDRIVE (QD_NAME di .env) beserta tabelnya dari schema.sql. Aman dijalankan ulang.
   Pemakaian (dari folder project):  php database/buat_db.php */
if (PHP_SAPI !== 'cli') exit("Jalankan dari command line: php database/buat_db.php\n");
require __DIR__ . '/../app/lib.php';

$name = env('QD_NAME');
if ($name === '' || !preg_match('/^[A-Za-z0-9_]+$/', $name)) exit("QD_NAME di .env belum diisi / tidak valid\n");
$pdo = new PDO('mysql:host=' . (env('QD_HOST') ?: '127.0.0.1') . ';port=' . (env('QD_PORT') ?: '3306') . ';charset=utf8mb4',
               env('QD_USER'), env('QD_PASSWORD'), [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$pdo->exec("CREATE DATABASE IF NOT EXISTS `$name` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
$pdo->exec("USE `$name`");
foreach (array_filter(array_map('trim', explode(';', preg_replace('/--[^\n]*/', '', file_get_contents(__DIR__ . '/schema.sql'))))) as $q) $pdo->exec($q);
echo "Database $name siap. Tabel: ", implode(', ', $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN)), PHP_EOL;
