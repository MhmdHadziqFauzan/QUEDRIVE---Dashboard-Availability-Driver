<?php
/* Halaman dashboard QUEDRIVE. Tampilannya (HTML + JavaScript) ada di app/views/dashboard.php;
   library Excel (SheetJS) terpisah di public/assets/js/xlsx.full.min.js.
   no-cache: browser selalu mengambil versi terbaru setelah update. */
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-cache');
require __DIR__ . '/../app/views/dashboard.php';
