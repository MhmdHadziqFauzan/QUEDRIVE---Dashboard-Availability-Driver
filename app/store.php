<?php
/* Penyimpanan data yang diisi di dashboard ke tabel QUEDRIVE (lihat database/schema.sql):
     qd_antrian      List Antrian (1 baris per driver di antrian)      <- dokumen dashboard app/queue
     qd_setting      setting & info upload                              <- app/config
     qd_history      snapshot harian                                    <- history/<tanggal>
     qd_upload       file upload per bagian                             <- dots|sj|p2h|klinik/c<n>
     qd_catatan      catatan driver / unit, berlaku mulai tanggal diisi  <- catatan/driver, catatan/unit

   Browser memakai /api/store seperti penyimpanan dokumen:
     GET  /api/store?since=<mikrodetik>  -> dokumen yang berubah sejak waktu itu (0 = semua) + waktu server
     POST /api/store                     -> {op: set | update | delete | acquire, path, data}
   Koneksi: QD_HOST, QD_PORT, QD_NAME, QD_USER, QD_PASSWORD di .env (boleh tulis, tidak read-only). */

const UPLOAD_KINDS = ['dots', 'sj', 'p2h', 'klinik'];
// catatan: path dokumen -> [jenis di qd_catatan, panjang maksimal, pola kunci]
const CATATAN = ['driver' => ['DRIVER', 255, '/^[A-Z0-9][A-Z0-9._-]{0,39}$/'],   // ID driver, mis. DM-01234
                 'unit'   => ['UNIT', 12, '/^[A-Z0-9]{1,40}$/']];                 // nopol tanpa spasi/strip/titik (transkrip: maks 12 karakter)
// isi item antrian di dashboard -> kolom qd_antrian
const QUEUE_COLS = ['id' => 'driver_id', 'nama' => 'nama', 'jam_absen' => 'jam_absen', 'absen_day' => 'absen_day', 'waktu_masuk' => 'waktu_masuk',
                    'seq' => 'seq', 'nopol_unit' => 'nopol_unit', 'type_unit' => 'tipe_unit', 'depo' => 'depo', 'keterangan' => 'keterangan'];

function store_db(): PDO {
    static $pdo = null;
    if ($pdo) return $pdo;
    if (env('QD_NAME') === '') throw new RuntimeException('Database QUEDRIVE belum diatur (QD_NAME di .env)');
    $pdo = new PDO('mysql:host=' . (env('QD_HOST') ?: '127.0.0.1') . ';port=' . (env('QD_PORT') ?: '3306') . ';dbname=' . env('QD_NAME') . ';charset=utf8mb4',
                   env('QD_USER'), env('QD_PASSWORD'),
                   [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_TIMEOUT => 5]);
    $pdo->exec("SET time_zone = '+07:00'");
    return $pdo;
}

// "2026-11-03 09:40:01.123456" <-> mikrodetik (versi dokumen untuk browser)
function us_of(?string $dt): int {
    return $dt ? (int) (strtotime(substr($dt, 0, 19)) * 1000000 + (int) str_pad(substr($dt, 20, 6), 6, '0')) : 0;
}
function dt_of(int $us): string {
    return date('Y-m-d H:i:s', intdiv($us, 1000000)) . sprintf('.%06d', $us % 1000000);
}

function store_client(): string {
    return substr($_SERVER['REMOTE_ADDR'] ?? 'cli', 0, 64);
}

// gabung seperti di browser: objek digabung per kunci, selain itu (angka, teks, null, array) diganti
function store_merge($a, $b) {
    if (!is_object($a) || !is_object($b)) return $b;
    foreach (get_object_vars($b) as $k => $v) $a->$k = isset($a->$k) && is_object($a->$k) && is_object($v) ? store_merge($a->$k, $v) : $v;
    return $a;
}

function json_obj($v) {
    return json_decode(json_encode($v ?? new stdClass(), JSON_UNESCAPED_UNICODE));   // array kosong dari PHP tetap objek {}
}

/* ---------- baca ---------- */

// List Antrian versi dashboard: {items: {kunci: item}} dari baris AKTIF; ver = perubahan terakhir di qd_antrian
function queue_doc(PDO $db): array {
    $items = new stdClass();
    foreach ($db->query("SELECT * FROM qd_antrian WHERE status = 'AKTIF' ORDER BY id") as $r) $items->{$r['kunci']} = queue_item($r);
    $ver = us_of($db->query('SELECT MAX(diubah_pada) FROM qd_antrian')->fetchColumn() ?: null);
    return ['path' => 'app/queue', 'ver' => max($ver, 1), 'deleted' => false, 'data' => (object) ['items' => $items]];
}

// isi baris = JSON lengkap (kolom data) bila ada; baris lama (sebelum kolom data) disusun dari kolom biasa
function queue_item(array $r): object {
    if (!empty($r['data']) && is_object($d = json_decode($r['data']))) return $d;
    $it = new stdClass();
    foreach (QUEUE_COLS as $k => $c) $it->$k = in_array($k, ['absen_day', 'seq'], true) ? ($r[$c] === null ? null : (int) $r[$c]) : $r[$c];
    return $it;
}

// kolom laporan qd_antrian dari isi baris (selain kolom QUEUE_COLS)
function queue_extra(object $it): array {
    $ms = (int) ($it->antri_at ?? 0) ?: (int) ($it->seq ?? 0);
    $m = isset($it->manual_ok) && is_object($it->manual_ok) ? $it->manual_ok : null;
    return ['waktu_antri' => $ms > 0 ? date('Y-m-d H:i:s', intdiv($ms, 1000)) : null,
            'putaran' => max(1, (int) ($it->round ?? 1)), 'selesai' => empty($it->retired) ? 0 : 1,
            'manual_sudah' => $m ? 1 : 0, 'manual_remarks' => $m ? mb_substr((string) ($m->remark ?? ''), 0, 300) : null,
            'jam_diedit' => empty($it->antri_edit) ? 0 : 1, 'data' => json_encode($it, JSON_UNESCAPED_UNICODE)];
}

// catatan satu jenis: {items: {kunci: [[berlaku_mulai, catatan], ...]}} urut tanggal; browser memilih yang berlaku per tanggal
function catatan_doc(PDO $db, string $path): array {
    $st = $db->prepare('SELECT kunci, berlaku_mulai, catatan, diubah_pada FROM qd_catatan WHERE jenis = ? ORDER BY kunci, berlaku_mulai');
    $st->execute([CATATAN[$path][0]]);
    $items = new stdClass(); $ver = 1;
    foreach ($st as $r) { $items->{$r['kunci']}[] = [$r['berlaku_mulai'], $r['catatan']]; $ver = max($ver, us_of($r['diubah_pada'])); }
    return ['path' => "catatan/$path", 'ver' => $ver, 'deleted' => false, 'data' => (object) ['items' => $items]];
}

function row_doc(string $path, ?string $data, int $deleted, string $diubah): array {
    return ['path' => $path, 'ver' => us_of($diubah), 'deleted' => (bool) $deleted, 'data' => $deleted || $data === null ? null : json_decode($data)];
}

// dokumen yang berubah sejak $since (mikrodetik, 0 = semua yang masih ada)
function store_changes(int $since): array {
    $db = store_db();
    $now = us_of($db->query('SELECT NOW(6)')->fetchColumn());
    $w = $since > 0 ? 'diubah_pada >= ?' : '1 = 1';
    $arg = $since > 0 ? [dt_of($since)] : [];
    $q = function (string $sql) use ($db, $arg) { $st = $db->prepare($sql); $st->execute($arg); return $st->fetchAll(); };
    $docs = [];
    if ($since === 0 || $q("SELECT 1 FROM qd_antrian WHERE $w LIMIT 1")) $docs[] = queue_doc($db);
    foreach ($q("SELECT kunci, nilai, diubah_pada FROM qd_setting WHERE $w") as $r) $docs[] = row_doc('app/' . $r['kunci'], $r['nilai'], 0, $r['diubah_pada']);
    foreach ($q("SELECT tanggal, data, dihapus, diubah_pada FROM qd_history WHERE $w" . ($since ? '' : ' AND dihapus = 0')) as $r)
        $docs[] = row_doc('history/' . $r['tanggal'], $r['data'], (int) $r['dihapus'], $r['diubah_pada']);
    foreach ($q("SELECT jenis, bagian, data, dihapus, diubah_pada FROM qd_upload WHERE $w" . ($since ? '' : ' AND dihapus = 0')) as $r)
        $docs[] = row_doc($r['jenis'] . '/' . $r['bagian'], $r['data'], (int) $r['dihapus'], $r['diubah_pada']);
    foreach (CATATAN as $p => [$jenis]) {
        if ($since === 0 || $q("SELECT 1 FROM qd_catatan WHERE $w AND jenis = '$jenis' LIMIT 1")) $docs[] = catatan_doc($db, $p);
    }
    return ['ver' => $now, 'docs' => $docs];
}

/* ---------- tulis ---------- */

function store_write(array $req): array {
    $op = $req['op'] ?? ''; $path = (string) ($req['path'] ?? '');
    // dulu dipakai sebagai kunci antar browser; proses yang memakainya aman dijalankan lebih dari sekali
    if ($op === 'acquire') return ['acquired' => true];
    if (!in_array($op, ['set', 'update', 'delete'], true)) throw new InvalidArgumentException('op tidak dikenal');
    $data = json_obj($req['data'] ?? null);
    $db = store_db();
    $db->beginTransaction();
    try {
        $ts = $db->query('SELECT NOW(6)')->fetchColumn();   // satu cap waktu untuk seluruh penyimpanan = versi dokumen
        if ($path === 'app/queue') $doc = queue_write($db, $ts, $op, $data);
        elseif (preg_match('#^app/([a-z]+)$#', $path, $m)) $doc = setting_write($db, $ts, $m[1], $op, $data);
        elseif (preg_match('#^history/(\d{4}-\d{2}-\d{2})$#', $path, $m)) $doc = row_write($db, $ts, 'qd_history', ['tanggal' => $m[1]], $path, $op, $data);
        elseif (preg_match('#^catatan/(driver|unit)$#', $path, $m)) $doc = catatan_write($db, $ts, $m[1], $op, $data);
        elseif (preg_match('#^(' . implode('|', UPLOAD_KINDS) . ')/(c\d+)$#', $path, $m)) $doc = row_write($db, $ts, 'qd_upload', ['jenis' => $m[1], 'bagian' => $m[2]], $path, $op, $data);
        else throw new InvalidArgumentException('path tidak dikenal: ' . $path);
        if ($doc === null) { $db->rollBack(); return ['missing' => true]; }
        $db->commit();
    } catch (Throwable $e) {
        if ($db->inTransaction()) $db->rollBack();
        throw $e;
    }
    return ['doc' => $doc];
}

function setting_write(PDO $db, string $ts, string $kunci, string $op, object $data): ?array {
    $st = $db->prepare('SELECT nilai FROM qd_setting WHERE kunci = ? FOR UPDATE');
    $st->execute([$kunci]);
    $cur = $st->fetchColumn();
    if ($op === 'update' && $cur === false) return null;
    $new = $op === 'set' ? $data : ($op === 'update' ? store_merge(json_decode($cur), $data) : new stdClass());
    $db->prepare('INSERT INTO qd_setting (kunci, nilai, diubah_pada, diubah_oleh) VALUES (?, ?, ?, ?)
                  ON DUPLICATE KEY UPDATE nilai = VALUES(nilai), diubah_pada = VALUES(diubah_pada), diubah_oleh = VALUES(diubah_oleh)')
       ->execute([$kunci, json_encode($new, JSON_UNESCAPED_UNICODE), $ts, store_client()]);
    return row_doc('app/' . $kunci, json_encode($new), 0, $ts);
}

// catatan: hanya op update, data = {kunci: teks}. Berlaku mulai hari ini (tanggal server); teks kosong = hapus mulai hari ini.
// Mengisi lagi pada hari yang sama menimpa isian hari itu.
function catatan_write(PDO $db, string $ts, string $path, string $op, object $data): array {
    [$jenis, $maks, $pola] = CATATAN[$path];
    if ($op !== 'update') throw new InvalidArgumentException('catatan hanya bisa diubah per baris (op update)');
    $st = $db->prepare('INSERT INTO qd_catatan (jenis, kunci, berlaku_mulai, catatan, diubah_pada, diubah_oleh) VALUES (?, ?, CURDATE(), ?, ?, ?)
                        ON DUPLICATE KEY UPDATE catatan = VALUES(catatan), diubah_pada = VALUES(diubah_pada), diubah_oleh = VALUES(diubah_oleh)');
    foreach (get_object_vars($data) as $kunci => $teks) {
        $kunci = strtoupper(trim((string) $kunci));
        $teks = trim(preg_replace('/\s+/u', ' ', is_scalar($teks) ? (string) $teks : ''));
        if (!preg_match($pola, $kunci)) throw new InvalidArgumentException("kunci catatan tidak valid: $kunci");
        if (mb_strlen($teks) > $maks) throw new InvalidArgumentException("catatan $path maksimal $maks karakter");
        $st->execute([$jenis, $kunci, $teks, $ts, store_client()]);
    }
    return catatan_doc($db, $path);
}

// qd_history / qd_upload: 1 baris per dokumen, hapus = tandai dihapus (agar browser lain ikut menghapus)
function row_write(PDO $db, string $ts, string $table, array $key, string $path, string $op, object $data): ?array {
    $where = implode(' AND ', array_map(fn($k) => "$k = ?", array_keys($key)));
    $st = $db->prepare("SELECT data, dihapus FROM $table WHERE $where FOR UPDATE");
    $st->execute(array_values($key));
    $cur = $st->fetch();
    $old = $cur && !$cur['dihapus'] ? json_decode($cur['data']) : null;
    if ($op === 'update' && $old === null) return null;
    $new = $op === 'set' ? $data : ($op === 'update' ? store_merge($old, $data) : null);
    $cols = array_keys($key);
    $db->prepare("INSERT INTO $table (" . implode(', ', $cols) . ', data, dihapus, diubah_pada) VALUES (' . str_repeat('?, ', count($cols)) . '?, ?, ?)
                  ON DUPLICATE KEY UPDATE data = VALUES(data), dihapus = VALUES(dihapus), diubah_pada = VALUES(diubah_pada)')
       ->execute([...array_values($key), $new === null ? null : json_encode($new, JSON_UNESCAPED_UNICODE), $new === null ? 1 : 0, $ts]);
    return row_doc($path, $new === null ? null : json_encode($new), $new === null ? 1 : 0, $ts);
}

// List Antrian: bandingkan isi lama dan baru, lalu tambah baris, ubah baris, atau tandai KELUAR
function queue_write(PDO $db, string $ts, string $op, object $data): array {
    $rows = [];
    foreach ($db->query("SELECT * FROM qd_antrian WHERE status = 'AKTIF' FOR UPDATE")->fetchAll() as $r) $rows[$r['kunci']] = $r;
    $old = array_map('queue_item', $rows);
    $items = fn($d) => isset($d->items) && is_object($d->items) ? get_object_vars($d->items) : [];
    if ($op === 'delete') $new = [];
    elseif ($op === 'set') $new = array_filter($items($data), 'is_object');
    else {   // update: null = keluar, objek = gabung ke item lama (atau item baru bila ada ID driver)
        $new = array_map(fn($it) => clone $it, $old);
        foreach ($items($data) as $k => $v) {
            if (!is_object($v)) unset($new[$k]);
            elseif (isset($new[$k])) $new[$k] = store_merge($new[$k], $v);
            elseif (!empty($v->id)) $new[$k] = $v;
        }
    }
    $by = store_client();
    $val = fn(object $it, string $k) => isset($it->$k) && (is_scalar($it->$k)) ? (string) $it->$k : null;

    foreach ($old as $k => $it) if (!isset($new[$k])) {
        $db->prepare("UPDATE qd_antrian SET status = 'KELUAR', waktu_keluar = NOW(), diubah_pada = ?, diubah_oleh = ? WHERE id = ?")->execute([$ts, $by, $rows[$k]['id']]);
    }
    foreach ($new as $k => $it) {
        if (strlen((string) $k) > 64) throw new InvalidArgumentException('kunci antrian terlalu panjang');
        $cols = []; foreach (QUEUE_COLS as $f => $c) $cols[$c] = $val($it, $f);
        $cols += queue_extra($it);
        if (!isset($old[$k])) {
            $ms = (int) ($it->antri_at ?? 0) ?: (int) ($it->seq ?? 0);
            $t = $ms > 0 ? intdiv($ms, 1000) : time();
            $cols['waktu_antri'] = $cols['waktu_antri'] ?? date('Y-m-d H:i:s', $t);
            $db->prepare('INSERT INTO qd_antrian (kunci, tanggal, ' . implode(', ', array_keys($cols)) . ', status, diubah_pada, diubah_oleh)
                          VALUES (?, ?, ' . str_repeat('?, ', count($cols)) . "'AKTIF', ?, ?)")
               ->execute([$k, date('Y-m-d', $t), ...array_values($cols), $ts, $by]);
            continue;
        }
        // simpan bila isi baris berubah (dibandingkan JSON lengkap, termasuk field baru seperti putaran / manual)
        if (json_encode($old[$k]) === json_encode($it) && $rows[$k]['data'] !== null) continue;
        $ubah = $cols;
        $db->prepare('UPDATE qd_antrian SET ' . implode(', ', array_map(fn($c) => "$c = ?", array_keys($ubah))) . ', diubah_pada = ?, diubah_oleh = ? WHERE id = ?')
           ->execute([...array_values($ubah), $ts, $by, $rows[$k]['id']]);
    }
    return queue_doc($db);
}
