<?php
/* Membangun app/views/dashboard.php dari file HTML tampilan user (Quedrive - Transport Trailer - *.html).
   Tampilan & aturan hitung = kode HTML user apa adanya; sumber data & penyimpanan = adapter
   public/assets/js/quedrive-adapter.js (database lewat API PHP). Bila user mengirim HTML baru, cukup jalankan ulang:

       php scripts/build_dashboard.php "simulasi/Update/<file HTML baru>.html"

   Yang dilakukan:
     1. SheetJS yang ditanam di HTML diganti file lokal /assets/js/xlsx.full.min.js.
     2. Data bawaan HTML (EMB_DRV, EMB_UNIT = daftar driver & nopol, SEED = snapshot data) DIBUANG: berisi data pribadi
        dan tidak dipakai (master & data dari database). Repo publik: jangan commit file HTML user.
     3. Patch kecil (gagal = berhenti, berarti kode HTML berubah dan patch perlu disesuaikan):
          - antrian(): driver nonaktif / di luar akses role tidak ditampilkan (qdShowQueue)
          - kolom Depo di List Antrian: dropdown daftar depo database (qdDepoCell)
     4. Adapter dimuat sesudah script tampilan. */
if (PHP_SAPI !== 'cli') exit("Jalankan dari command line\n");
$src = $argv[1] ?? '';
$out = $argv[2] ?? __DIR__ . '/../app/views/dashboard.php';
if ($src === '' || !is_file($src)) exit("Pemakaian: php scripts/build_dashboard.php <file HTML user> [output]\n");
$h = file_get_contents($src);
$fail = function (string $m) { fwrite(STDERR, "GAGAL: $m\n"); exit(1); };

// 1. SheetJS tertanam -> file lokal
$a = strpos($h, '<script>/* SheetJS');
if ($a === false) $fail('blok SheetJS tertanam tidak ditemukan');
$b = strpos($h, '</script>', $a);
$h = substr($h, 0, $a) . '<script src="/assets/js/xlsx.full.min.js"></script>' . substr($h, $b + 9);

// 2. data bawaan (data pribadi) dibuang
$data = ['EMB_DRV' => '[]', 'EMB_UNIT' => '[]', 'SEED' => 'null'];
foreach ($data as $name => $empty) {
    $n = 0;
    $h = preg_replace('/^const ' . $name . ' = .*;[ \t]*\r?$/m', "const $name = $empty;   // data bawaan HTML user dibuang (build): data dari database", $h, 1, $n);
    if ($n !== 1) $fail("baris const $name tidak ditemukan");
}

// 3. patch
$patches = [
    'antrian: sembunyikan driver nonaktif / di luar akses role' =>
        ['.filter(([k, x]) => x && x.id)', '.filter(([k, x]) => x && x.id && qdShowQueue(x.id))'],
    'kolom Depo: dropdown dari database' =>
        ['<input type="text" class="cell-in" data-id="${esc(d.id)}" data-key="${esc(qkey(d))}" data-f="depo" value="${esc(d.depo)}" title="${esc(d.depo)}" placeholder="Tulis depo..."${d.retired ? \' disabled\' : \'\'}>', '${qdDepoCell(d)}'],
];
foreach ($patches as $what => [$old, $new]) {
    $c = substr_count($h, $old);
    if ($c !== 1) $fail("patch '$what': teks asli ditemukan $c kali (harus 1)");
    $h = str_replace($old, $new, $h);
}

// 4. adapter + penanda file hasil build
$c = substr_count($h, '</body>');
if ($c !== 1) $fail('</body> ditemukan ' . $c . ' kali');
if (strpos($h, '<?') !== false) $fail('HTML memuat "<?" (bentrok dengan PHP)');
// ?v= waktu ubah adapter: browser selalu memuat adapter terbaru tanpa Ctrl+F5
$h = str_replace('</body>', "<script src=\"/assets/js/quedrive-adapter.js?v=<?= @filemtime(__DIR__ . '/../../public/assets/js/quedrive-adapter.js') ?>\"></script>\n</body>", $h);
$h = preg_replace('/^<!DOCTYPE html>\r?\n/i', "<!DOCTYPE html>\n<!-- DIBUAT OTOMATIS oleh scripts/build_dashboard.php dari HTML user: " . basename($src) . ".\n     Jangan edit langsung: ubah public/assets/js/quedrive-adapter.js atau patch di scripts/build_dashboard.php, lalu build ulang. -->\n", $h, 1);

// pemeriksaan akhir: tidak ada sisa data bawaan
foreach (['const EMB_DRV = [[', 'const EMB_UNIT = [[', '"app/absen"', '"app/master_driver":{'] as $bad)
    if (strpos($h, $bad) !== false) $fail("masih ada data bawaan: $bad");
file_put_contents($out, $h);
printf("dashboard dibuat: %s (%s KB) dari %s\n", realpath($out) ?: $out, number_format(strlen($h) / 1024, 0), basename($src));
