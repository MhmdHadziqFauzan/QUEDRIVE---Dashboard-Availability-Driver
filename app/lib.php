<?php
/* Backend QUEDRIVE (PHP): koneksi database sumber, cache, dan aturan List Driver.

   Dipakai oleh public/api/index.php (API), database/buat_db.php, dan scripts/cek_koneksi.php (tes koneksi).
   Hanya membaca database sumber (sesi read-only). Query ada di folder queries/.
   Hanya folder public/ yang jadi web root: app/ dan .env (berisi password) tidak bisa dibuka dari browser. */

const SHIP_LOOKBACK_DAYS = 7;   // Last Shipment dicari dari shipment dengan tender_date 7 hari terakhir
const CACHE_SECONDS = 30;       // data sumber diambil ulang paling cepat tiap 30 detik

// view PostgreSQL di DB2 (puninar_dms): endpoint /api/dms/<nama> -> file queries/<sql>.sql
const DMS_DB = 'DB2';
const DMS_VIEWS = ['driver-career' => 'dms_driver_career'];   // public.v_driver_career_utilisasi
const DMS_LIMIT_DEFAULT = 100;
const DMS_LIMIT_MAX = 1000;

// DB3 (puninar_p2h): Status P2H unit = pemeriksaan terbaru dalam P2H_LOOKBACK_DAYS hari terakhir
const P2H_DB = 'DB3';
const P2H_LOOKBACK_DAYS = 30;

// DB4 (odongnew_db): Surat Jalan untuk SJ Outstanding & Aging SJ
const SJ_DB = 'DB4';
// SJ ditampilkan mulai tender November 2026 (keputusan 06-Nov-2026); SJ dengan tender sebelum ini tidak dihitung
const SJ_MULAI = '2026-11-01';

// Tipe Absensi dari trx_dms_absent.initial (huruf besar/kecil diabaikan); selain ini = Belum Absen (termasuk O, ADJ)
const TIPE_ABSEN = ['X' => 'Pool', 'OR' => 'On Road', 'A' => 'Tidak Hadir', 'I' => 'Izin', 'S' => 'Sakit'];
// shipment_status di trx_gps_tracking -> label Last Shipment (cancel diabaikan)
const SHIP_LABEL = ['onprogress' => 'On Progress', 'ready' => 'Ready', 'closed' => 'Closed', 'forceclosed' => 'Closed'];

// Akses data (hasil meeting 05-Nov-2026): driver project Exim Trailer, LOBP, ESA; SPK/SPO POOL NAGRAK TRAILER.
// Driver Exim Trailer yang membawa shipment POOL CILEGON TRAILER ikut karena project-nya Exim Trailler.
// ESA: shipment selalu dengan pihak ketiga, jadi wajar absensinya selalu OR dan tidak ada di trx_gps_tracking.
// Akses diambil dari role user di database QUEDRIVE (qd_user -> qd_role_akses), lihat akses().
// Konstanta di bawah hanya cadangan bila database QUEDRIVE tidak bisa dibaca.
const AKSES_PROJECT = ['Exim Trailler', 'LOBP', 'ESA'];   // mst_driver.project_name (ejaan sesuai database)
const AKSES_POOL = ['POOL NAGRAK TRAILER'];               // trx_shipment_preparation.ownership_shipment
const LOGIN_DEFAULT = 'admin_exim';   // belum ada halaman login: user = QD_LOGIN_USER di .env, bila kosong ini

date_default_timezone_set('Asia/Jakarta');

// isi .env (KEY=VALUE, baris "#" = komentar); variabel environment server tetap didahulukan
function env_all(): array {
    static $env = null;
    if ($env !== null) return $env;
    $env = [];
    $f = __DIR__ . '/../.env';
    if (is_file($f)) {
        foreach (file($f, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if (!preg_match('/^\s*([A-Za-z_][A-Za-z0-9_]*)\s*=\s*(.*)$/', $line, $m)) continue;
            $v = trim($m[2]);
            if (preg_match('/^(["\'])(.*)\1$/', $v, $q)) $v = $q[2];
            else $v = trim(preg_replace('/\s+#.*$/', '', $v));   // komentar di ujung baris
            $env[$m[1]] = $v;
        }
    }
    foreach (getenv() as $k => $v) if (preg_match('/^(DB\d+|QD)_/', $k)) $env[$k] = $v;
    return $env;
}

function env(string $key, string $default = ''): string {
    return trim(env_all()[$key] ?? $default);
}

// prefix database yang terdaftar di .env: DB1, DB2, ...
function db_prefixes(): array {
    $p = [];
    foreach (array_keys(env_all()) as $k) if (preg_match('/^(DB\d+)_HOST$/', $k, $m)) $p[] = $m[1];
    sort($p);
    return $p;
}

function connect(string $prefix = 'DB1', bool $withDb = true): PDO {
    $type = strtolower(env("{$prefix}_TYPE", 'mysql'));
    $host = env("{$prefix}_HOST");
    $name = $withDb ? env("{$prefix}_NAME") : '';
    $opt = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_TIMEOUT => 10];
    if ($type === 'mysql') {
        $port = env("{$prefix}_PORT") ?: '3306';
        $pdo = new PDO("mysql:host=$host;port=$port" . ($name !== '' ? ";dbname=$name" : '') . ';charset=utf8mb4',
                       env("{$prefix}_USER"), env("{$prefix}_PASSWORD"), $opt);
        $pdo->exec('SET SESSION TRANSACTION READ ONLY');
        try { $pdo->exec('SET SESSION max_statement_time = 90'); } catch (PDOException $e) {}   // batas 90 detik (MariaDB)
    } elseif ($type === 'postgres') {
        $port = env("{$prefix}_PORT") ?: '5432';
        $pdo = new PDO("pgsql:host=$host;port=$port;dbname=" . ($name !== '' ? $name : 'postgres') . ';connect_timeout=10',
                       env("{$prefix}_USER"), env("{$prefix}_PASSWORD"), $opt);
        $pdo->exec('SET SESSION CHARACTERISTICS AS TRANSACTION READ ONLY');
        $pdo->exec("SET statement_timeout = '90s'");
    } else {
        throw new RuntimeException("{$prefix}_TYPE tidak dikenal: $type (pakai mysql atau postgres)");
    }
    return $pdo;
}

// user yang sedang "login" (sementara dari .env sampai menu login dibuat)
function login_user(): string {
    return env('QD_LOGIN_USER') ?: LOGIN_DEFAULT;
}

// Hak akses data user login: ['user', 'role', 'project' => [...], 'pool' => [...]] dari qd_user + qd_role_akses.
// Bila database QUEDRIVE tidak bisa dibaca / user tidak ada: cadangan AKSES_PROJECT & AKSES_POOL.
function akses(): array {
    static $a = null;
    if ($a) return $a;
    $a = ['user' => login_user(), 'role' => null, 'project' => AKSES_PROJECT, 'pool' => AKSES_POOL, 'sumber' => 'cadangan'];
    try {
        if (!function_exists('store_db')) require_once __DIR__ . '/store.php';
        $st = store_db()->prepare('SELECT u.role_kode, ra.jenis, ra.nilai FROM qd_user u JOIN qd_role r ON r.kode = u.role_kode AND r.aktif = 1
                                   LEFT JOIN qd_role_akses ra ON ra.role_kode = u.role_kode
                                   WHERE u.username = ? AND u.aktif = 1 ORDER BY ra.jenis, ra.nilai');
        $st->execute([$a['user']]);
        $rows = $st->fetchAll();
        if ($rows) {
            $p = []; $pool = [];
            foreach ($rows as $r) {
                if ($r['jenis'] === 'PROJECT') $p[] = $r['nilai'];
                if ($r['jenis'] === 'POOL') $pool[] = $r['nilai'];
            }
            // urutan project mengikuti AKSES_PROJECT bila ada (Exim, LOBP, ESA), sisanya di belakang
            usort($p, fn($x, $y) => (array_search($x, AKSES_PROJECT) === false ? 99 : array_search($x, AKSES_PROJECT)) <=> (array_search($y, AKSES_PROJECT) === false ? 99 : array_search($y, AKSES_PROJECT)));
            $a = ['user' => $a['user'], 'role' => $rows[0]['role_kode'], 'project' => $p, 'pool' => $pool, 'sumber' => 'database'];
        }
    } catch (Throwable $e) {
        error_log('QUEDRIVE akses: ' . $e->getMessage());
    }
    return $a;
}

// komentar "--" dibuang dulu agar teks seperti "00:04" tidak dibaca PDO sebagai parameter
function sql(string $name): string {
    return preg_replace('/--[^\n]*/', '', file_get_contents(__DIR__ . "/queries/$name.sql"));
}

function fetch_rows(string $name, array $params = [], string $prefix = 'DB1'): array {
    $st = connect($prefix)->prepare(sql($name));
    $st->execute($params);
    return $st->fetchAll();
}

// cache file di storage/cache (PHP-FPM tidak menyimpan memori antar request)
function cached(string $key, callable $fn) {
    $dir = __DIR__ . '/../storage/cache';
    if (!is_dir($dir)) @mkdir($dir, 0775, true);
    $f = $dir . '/' . preg_replace('/[^A-Za-z0-9_-]/', '_', $key) . '.json';
    if (is_file($f) && time() - filemtime($f) < CACHE_SECONDS) {
        $hit = json_decode((string) @file_get_contents($f), true);
        if ($hit !== null) return $hit;
    }
    $val = $fn();
    @file_put_contents($f, json_encode($val, JSON_UNESCAPED_UNICODE), LOCK_EX);
    return $val;
}

function norm_tipe(?string $v): string {
    return preg_replace('/(\d+)\s*FT/', '$1FT', strtoupper(trim($v ?? '')));   // "40 FT" -> "40FT"
}

// "2026-10-30 01:16:05" -> "2026-10-30 01:16"
function iso_min(?string $v): ?string {
    return $v ? substr($v, 0, 16) : null;
}

// Per driver (primary_driver_num): shipment yang waktunya paling dekat ke sekarang.
// Waktu shipment = closed_date bila Closed, selain itu tender_date (update_at di tabel ini selalu kosong).
// baris stop trx_gps_tracking dengan tender_date SHIP_LOOKBACK_DAYS hari terakhir (dipakai Last Shipment & Last Unloading)
function ship_rows(string $tanggal): array {
    $sejak = date('Y-m-d 00:00:00', strtotime("$tanggal -" . SHIP_LOOKBACK_DAYS . ' days'));
    return fetch_rows('last_shipment', ['sejak' => $sejak]);
}

// driver yang ikut di satu baris shipment: primary dan secondary ("-" / kosong = tidak ada).
// Driver yang ada di dalam truk ikut memiliki shipment tersebut.
function ship_drivers(array $r): array {
    return array_values(array_unique(array_filter([$r['primary_driver_num'] ?? '', $r['secondary_driver_num'] ?? ''], fn($d) => $d !== '' && $d !== '-')));
}

function last_shipments(array $rows): array {
    $ships = [];
    foreach ($rows as $r) {   // 1 baris per stop -> 1 entri per shipment
        $no = (string) $r['shipment_number'];
        if (!isset($ships[$no]) || ($r['tender_date'] ?? '') > ($ships[$no]['tender_date'] ?? '')) $ships[$no] = $r;
    }
    $best = [];
    foreach ($ships as $s) {
        $label = SHIP_LABEL[strtolower($s['shipment_status'] ?? '')] ?? null;
        if (!$label) continue;
        $waktu = ($label === 'Closed' ? $s['closed_date'] : $s['tender_date']) ?? '';
        foreach (ship_drivers($s) as $d) {
            if (isset($best[$d]) && $waktu <= $best[$d]['waktu']) continue;
            $best[$d] = ['waktu' => $waktu, 'item' => [
                'status' => $label, 'shipment_number' => $s['shipment_number'], 'tender_date' => $s['tender_date'], 'closed_date' => $s['closed_date'],
                'nopol' => $s['license_number'], 'tipe_unit' => norm_tipe($s['equipment_group']), 'customer' => $s['customer_name']]];
        }
    }
    return array_map(fn($v) => $v['item'], $best);
}


// Per driver (primary_driver_num): stop bongkar (stop_type = 'D') terbaru menurut final_ata_pta -> location_name.
// final_ata_pta berisi waktu rencana bila belum tiba, jadi stop yang waktunya belum lewat diabaikan.
function last_unloadings(array $rows, string $tanggal): array {
    $batas = $tanggal === date('Y-m-d') ? date('Y-m-d H:i:s') : "$tanggal 23:59:59";
    $best = [];
    foreach ($rows as $r) {
        $t = $r['final_ata_pta'] ?? '';
        if (strtoupper($r['stop_type'] ?? '') !== 'D' || $t === '' || $t > $batas) continue;
        foreach (ship_drivers($r) as $d) {
            if (isset($best[$d]) && $t <= $best[$d]['waktu']) continue;
            $best[$d] = ['lokasi' => $r['location_name'], 'waktu' => $t];
        }
    }
    return $best;
}

// nopol -> driver (primary & secondary) pada shipment terbaru (tender_date) yang memakai nopol itu
function nopol_owner(array $rows): array {
    $best = [];
    foreach ($rows as $r) {
        $n = $r['license_number'];
        if (!$n || (isset($best[$n]) && ($r['tender_date'] ?? '') <= $best[$n]['t'])) continue;
        $best[$n] = ['t' => $r['tender_date'] ?? '', 'd' => ship_drivers($r)];
    }
    return array_map(fn($v) => $v['d'], $best);
}

function build_drivers(string $tanggal): array {
    $absen = fetch_rows('driver_absen', ['tanggal' => $tanggal]);
    // +-00:00-00:04 baris absen hari baru belum dibuat: pakai daftar driver kemarin, semua Belum Absen
    if (!$absen && $tanggal === date('Y-m-d')) {
        $absen = array_map(fn($a) => ['initial' => null, 'updated_at' => null] + $a,
                           fetch_rows('driver_absen', ['tanggal' => date('Y-m-d', strtotime('-1 day'))]));
    }
    $absen = array_column($absen, null, 'driver_id');
    $rows = ship_rows($tanggal);
    $ship = last_shipments($rows);
    $unload = last_unloadings($rows, $tanggal);
    $owner = nopol_owner($rows);
    $out = [];
    // patokan daftar driver = mst_driver (project yang boleh diakses, DM/DOS), aktif MAUPUN nonaktif (Master Driver menampilkan semua);
    // List Driver / antrian di dashboard hanya memakai yang 'aktif' = true. Absen hari ini ditempelkan,
    // driver yang belum punya baris absen tampil Belum Absen. Nama dari mst_driver.
    foreach (fetch_rows('mst_driver', ['projects' => implode(',', akses()['project'])]) as $m) {
        $a = ($absen[$m['dm_number']] ?? [])
           + ['driver_id' => $m['dm_number'], 'initial' => null, 'updated_at' => null, 'status_klinik' => null];
        $a['driver_name'] = $m['name'];
        $ini = strtoupper(trim($a['initial'] ?? ''));
        $s = $ship[$a['driver_id']] ?? null;
        $last = $s['status'] ?? null;
        $jalan = in_array($last, ['On Progress', 'Ready'], true) && ($s['nopol'] ?? '') !== '';
        $pemegang = $jalan ? ($owner[$s['nopol']] ?? []) : [];
        $out[] = [
            'id' => $a['driver_id'], 'nama' => $a['driver_name'], 'project' => $m['project_name'], 'initial' => $ini !== '' ? $ini : null,
            'jam' => $ini !== '' && $a['updated_at'] ? substr($a['updated_at'], 11, 5) : null,
            'tipe_absen' => TIPE_ABSEN[$ini] ?? 'Belum Absen',
            'last_shipment' => $last, 'shipment_number' => $s['shipment_number'] ?? null, 'tender_date' => iso_min($s['tender_date'] ?? null),
            'closed_date' => iso_min($s['closed_date'] ?? null),
            'nopol' => $s['nopol'] ?? null, 'tipe_unit' => $s['tipe_unit'] ?? null,
            'last_unloading' => $unload[$a['driver_id']]['lokasi'] ?? null,
            'last_unloading_at' => iso_min($unload[$a['driver_id']]['waktu'] ?? null),
            // Last Location (live tracking): hanya bila shipment terakhir On Progress / Ready dan nopolnya
            // masih dipegang driver ini (shipment terbaru dengan nopol tsb milik driver ini); selain itu null
            'tracking_nopol' => $jalan && in_array($a['driver_id'], $pemegang, true) ? $s['nopol'] : null,
            'nopol_driver_lain' => $jalan && $pemegang && !in_array($a['driver_id'], $pemegang, true) ? implode(' / ', $pemegang) : null,
            // aktif dari mst_driver.status (active / inactive); driver nonaktif tidak bisa / tidak tetap di antrian
            'aktif' => $m['status'] === 'active',
            // Status Klinik dari baris absen tanggal ini (FIT / UNFIT); null = belum ada data klinik
            'klinik' => ($k = strtoupper(trim($a['status_klinik'] ?? ''))) !== '' ? $k : null,
            // AVA: hadir (X) dan Last Shipment Closed, atau hadir (X) tanpa shipment 7 hari terakhir
            'status' => $ini === 'X' && ($last === 'Closed' || $last === null) ? 'AVA' : 'NOT AVA',
        ];
    }
    return ['tanggal' => $tanggal, 'diambil' => date('Y-m-d\TH:i:s'), 'jumlah' => count($out), 'akses' => akses(), 'drivers' => $out];
}

// nopol tanpa spasi/strip/titik, huruf besar (B-9028-TEJ = B 9028 TEJ)
function norm_plat(?string $v): string {
    return strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $v ?? ''));
}

// norm_plat(nopol) -> ['status', 'waktu']; null bila DB3 tidak bisa dibaca (Master Unit tetap jalan)
function p2h_status(): ?array {
    try {
        $sejak = date('Y-m-d 00:00:00', strtotime('-' . P2H_LOOKBACK_DAYS . ' days'));
        $out = [];
        foreach (fetch_rows('p2h_terakhir', ['sejak' => $sejak], P2H_DB) as $r) {
            $k = norm_plat($r['vehicle_license']);
            if (!isset($out[$k]) || $r['created_at'] > $out[$k]['waktu']) $out[$k] = ['status' => $r['status'], 'waktu' => $r['created_at']];
        }
        return $out;
    } catch (Throwable $e) {
        error_log('QUEDRIVE DB3: ' . $e->getMessage());
        return null;
    }
}

// Master Unit: hanya unit di pool yang diizinkan role login (akses()['pool'], mis. POOL NAGRAK TRAILER).
// mst_lookup_lines.attribute_v4 memakai garis bawah (POOL_NAGRAK_TRAILER), jadi dibandingkan setelah '_' -> ' '.
function build_units(): array {
    $p2h = p2h_status() ?? [];
    $pools = array_map('strtoupper', akses()['pool']);
    $rows = array_filter(fetch_rows('units'), fn($r) => in_array(strtoupper(str_replace('_', ' ', (string)$r['pool'])), $pools, true));
    return array_values(array_map(function ($r) use ($p2h) {
        $p = $p2h[norm_plat($r['nopol'])] ?? null;
        return ['nopol' => $r['nopol'], 'tipe_unit' => norm_tipe($r['tipe_unit']), 'pool' => $r['pool'], 'customer' => $r['customer'],
                'p2h' => $p['status'] ?? null, 'p2h_at' => iso_min($p['waktu'] ?? null)];
    }, $rows));
}

// Kartu Shipment SPK / SPO: shipment unik dengan tanggal onroad = $tanggal
// (onroad = tanggal terbesar antara tender_date dan date_execution).
// Hanya shipment yang dibawa driver dalam scope (mst_driver project sesuai akses role user, primary atau secondary di
// trx_gps_tracking): SPO vendor dan shipment yang belum ada drivernya tidak dihitung (user: "kami tidak punya SPO").
// Rincian per scope project (Exim Trailer / LOBP / ESA) menurut project driver yang membawa.
function build_spk_spo(string $tanggal): array {
    $sejak = date('Y-m-d 00:00:00', strtotime("$tanggal -" . SHIP_LOOKBACK_DAYS . ' days'));
    $ship = [];
    foreach (fetch_rows('spk_spo', ['pools' => implode(',', akses()['pool']), 'sejak' => $sejak, 'sejak2' => "$tanggal 00:00:00"]) as $r) {
        $tipe = strtoupper(trim($r['shipment_type'] ?? ''));
        // seperti aturan DOTS: shipment tanpa tanggal tender dan yang dibatalkan tidak dihitung
        if (!in_array($tipe, ['SPK', 'SPO'], true) || !$r['tender_date'] || $r['cancel_date'] || stripos($r['status'] ?? '', 'cancel shipment approved') !== false) continue;
        if (max(substr($r['tender_date'] ?? '', 0, 10), substr($r['date_execution'] ?? '', 0, 10)) !== $tanggal) continue;
        $ship[$r['shipment_id']] = $tipe;
    }
    // dm_number -> project (Exim Trailler / LOBP / ESA); shipment masuk project driver utama, bila di luar scope pakai driver kedua
    $scope = akses()['project'];
    $proj = array_column(fetch_rows('mst_driver', ['projects' => implode(',', $scope)]), 'project_name', 'dm_number');
    $milik = [];
    foreach (array_chunk(array_keys($ship), 500) as $ch) {
        $st = connect()->prepare(sprintf(sql('tipe_truk'), implode(',', array_fill(0, count($ch), '?'))));
        $st->execute($ch);
        foreach ($st->fetchAll() as $r) {
            $p = $proj[$r['primary_driver_num'] ?? ''] ?? $proj[$r['secondary_driver_num'] ?? ''] ?? null;
            if ($p && !isset($milik[$r['shipment_number']])) $milik[$r['shipment_number']] = $p;
        }
    }
    // rincian per scope (urutan project di akses role), menggantikan rincian 20/40 FT
    $out = ['tanggal' => $tanggal, 'scope' => $scope];
    foreach (['SPK', 'SPO'] as $k) $out[$k] = ['n' => 0, 'per' => array_fill_keys($scope, 0)];
    foreach ($ship as $id => $tipe) {
        if (!isset($milik[$id])) continue;
        $out[$tipe]['n']++; $out[$tipe]['per'][$milik[$id]]++;
    }
    return $out;
}

// nama depo untuk dropdown Depo di List Antrian; awalan project tidak ditampilkan (PL/EXIM.DEPO-BCC -> DEPO-BCC)
function depo_name(string $geo): string {
    return preg_replace('#^PL/[^.]*\.#', '', trim($geo));
}
function build_depo(): array {
    return array_values(array_unique(array_map(fn($r) => depo_name($r['geo_code']), fetch_rows('depo'))));
}

// SJ belum kembali; pool diambil dari Master Unit lewat nopol (power_unit), karena tabel SJ tidak punya kolom pool
function build_sj(): array {
    $pool = [];
    foreach (fetch_rows('units') as $u) $pool[norm_plat($u['nopol'])] = $u['pool'];
    $out = [];
    foreach (fetch_rows('sj_outstanding', ['mulai' => SJ_MULAI], SJ_DB) as $r) {
        $p = $pool[norm_plat($r['power_unit'])] ?? null;
        $out[] = ['shipment_number' => $r['shipment_number'], 'driver_id' => $r['driver_id'], 'customer' => $r['customer_name'],
                  'tender_date' => iso_min($r['tender_date']), 'nopol' => $r['power_unit'],
                  'pool' => $p !== null ? str_replace('_', ' ', $p) : null];   // POOL_NAGRAK_TRAILER -> POOL NAGRAK TRAILER
    }
    return $out;
}

// baris view DMS; $aktif null = semua, true/false = filter is_active
function dms_rows(string $query, ?bool $aktif, int $limit, int $offset): array {
    return fetch_rows($query, ['aktif' => $aktif === null ? null : ($aktif ? 'true' : 'false'), 'lim' => $limit, 'off' => $offset], DMS_DB);
}
