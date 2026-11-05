<?php
/* Membuat simulasi/QUEDRIVE - Transport Trailer.html: dashboard yang berdiri sendiri (tanpa server & database).
   Isinya data hari ini (snapshot dari database sumber, di-hardcode) + simulasi kejadian (absen, order/pairing,
   shipment jalan & selesai, status klinik) seperti HTML awal. Antrian & isian hanya di memori halaman (hilang saat refresh).

   Pemakaian (dari folder project):  php scripts/buat_simulasi.php
   Jalankan ulang untuk memperbarui snapshot ke data terbaru. */
if (PHP_SAPI !== 'cli') exit("Jalankan dari command line: php scripts/buat_simulasi.php\n");
require __DIR__ . '/../app/lib.php';

$root = dirname(__DIR__);
$tanggal = date('Y-m-d');
echo "Mengambil data $tanggal ...\n";

// nomor HP yang tertulis di nama driver tidak ikut ke file simulasi
$bersih = fn(?string $n) => trim(preg_replace('/[\s\-\/]*\(?\+?\d[\d\s\-]{8,}\d\)?/', '', (string) $n), " -/");
$drivers = build_drivers($tanggal);
foreach ($drivers['drivers'] as &$d) $d['nama'] = $bersih($d['nama']);
unset($d);
$sj = build_sj();
$data = [
    'diambil' => date('Y-m-d\TH:i'),
    'drivers' => $drivers,
    'units' => build_units(),
    'depo' => build_depo(),
    'spk' => build_spk_spo($tanggal),
    'sj' => $sj,
];
echo count($drivers['drivers']), " driver, ", count($data['units']), " unit, ", count($sj), " SJ, ", count($data['depo']), " depo\n";

// tampilan dashboard (PHP tanpa kode PHP) + SheetJS ditanam + data + mesin simulasi
$html = file_get_contents("$root/app/views/dashboard.php");
$lib = file_get_contents("$root/public/assets/js/xlsx.full.min.js");
$json = str_replace('</', '<\/', json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
$sim = str_replace('/*DATA*/null', $json, file_get_contents(__DIR__ . '/simulasi_engine.js'));

$ganti = [
    '<script src="/assets/js/xlsx.full.min.js"></script>' => "<script>$lib</script>\n<script>\n$sim\n</script>",
    '<title>QUEDRIVE – ' => '<title>SIMULASI · QUEDRIVE – ',
    // API -> data hardcode
    "async function apiGet(path) {\n" => "async function apiGet(path) {\n  return simApi(path);   // SIMULASI: data hardcode, tanpa server\n",
    "if (location.protocol === 'file:') throw new Error('halaman dibuka sebagai file, bukan lewat backend');" => '',
    "if (location.protocol === 'file:') throw new Error('halaman dibuka sebagai file');" => '',
    // penyimpanan: memori halaman, boleh diubah
    "  if (location.protocol === 'file:') return null;\n  let first;" => "  return null;   // SIMULASI: tanpa database QUEDRIVE\n  let first;",
    "DB = makeMemoryDb(); MODE = 'local'; CAN_WRITE = false;" => "DB = makeMemoryDb(); MODE = 'local';",
    "  b.innerHTML = `Data sumber diperbarui \${esc(fmtTs(API.at))}. ` + (MODE === 'server'" =>
        "  b.innerHTML = `<b>SIMULASI</b>: data \${esc(simSnapLabel())} (hardcode) + kejadian acak. Antrian &amp; isian hanya di halaman ini, hilang saat refresh.`; return;\n  b.innerHTML = `Data sumber diperbarui \${esc(fmtTs(API.at))}. ` + (MODE === 'server'",
];
foreach ($ganti as $a => $b) {
    $n = substr_count($html, $a);
    if ($n !== 1) exit("Gagal: teks tidak ditemukan tepat 1x ($n): " . substr($a, 0, 60) . "\n");
    $html = str_replace($a, $b, $html);
}

@mkdir("$root/simulasi", 0775, true);
$out = "$root/simulasi/QUEDRIVE - Transport Trailer.html";
file_put_contents($out, $html);
printf("Selesai: %s (%.1f MB)\n", $out, filesize($out) / 1048576);
