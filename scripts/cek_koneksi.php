<?php
/* Tes koneksi ke satu database QUEDRIVE dan cek struktur tabel yang dibutuhkan.

   Pemakaian (dari folder project):
       php scripts/cek_koneksi.php                    # DB1, tanpa contoh isi data
       php scripts/cek_koneksi.php --db=DB1 --sample=5
       php -d extension=pdo_pgsql scripts/cek_koneksi.php --db=DB2   # PostgreSQL, bila pdo_pgsql belum aktif di php.ini

   Hanya menjalankan SELECT (sesi diset read-only). Hasil juga disimpan ke storage/hasil_cek_<DB>.txt.
   --sample menampilkan isi baris: periksa/samarkan data pribadi sebelum hasilnya dibagikan. */
if (PHP_SAPI !== 'cli') exit("Jalankan dari command line: php scripts/cek_koneksi.php\n");
require __DIR__ . '/../app/lib.php';

// tabel yang dipakai dashboard (sumber tiap kolom: lihat komentar di app/queries/*.sql)
const TARGET = [
    'DB1' => ['trx_dms_absent', 'trx_gps_tracking', 'mst_lookup_lines'],
    'DB2' => ['v_driver_career_utilisasi'],   // VIEW di schema public
    'DB3' => ['check_records'],
    'DB4' => ['so.so_trx_document'],          // PostgreSQL: "schema.tabel" (tanpa titik = schema public)
];

$out = [];
function say(string $s = ''): void { global $out; echo $s, PHP_EOL; $out[] = $s; }

function q(PDO $c, string $sql, array $args = []): array {
    $st = $c->prepare($sql);
    $st->execute($args);
    $rows = $st->fetchAll(PDO::FETCH_NUM);
    $cols = [];
    for ($i = 0; $i < $st->columnCount(); $i++) $cols[] = $st->getColumnMeta($i)['name'] ?? "kolom$i";
    return [$cols, $rows];
}

function table(array $cols, array $rows): void {
    if (!$rows) { say('  (kosong)'); return; }
    $cells = array_map(fn($r) => array_map(fn($v) => mb_substr($v === null ? '' : (string) $v, 0, 40), $r), $rows);
    $w = [];
    foreach ($cols as $i => $c) $w[$i] = max(mb_strlen($c), ...array_map(fn($r) => mb_strlen($r[$i]), $cells));
    $pad = fn($s, $n) => $s . str_repeat(' ', $n - mb_strlen($s));
    say('  ' . implode(' | ', array_map(fn($c, $i) => $pad($c, $w[$i]), $cols, array_keys($cols))));
    say('  ' . implode('-+-', array_map(fn($x) => str_repeat('-', $x), $w)));
    foreach ($cells as $r) say('  ' . implode(' | ', array_map(fn($v, $i) => $pad($v, $w[$i]), $r, array_keys($r))));
}

function save(string $p, int $code): int {
    global $out;
    $f = __DIR__ . "/../storage/hasil_cek_$p.txt";
    file_put_contents($f, implode("\n", $out) . "\n");
    echo PHP_EOL, "Hasil disimpan di $f", PHP_EOL;
    return $code;
}

function main(): int {
    $a = getopt('', ['db::', 'sample::']);
    $p = strtoupper($a['db'] ?? 'DB1');
    $sample = (int) ($a['sample'] ?? 0);
    $type = strtolower(env("{$p}_TYPE", 'mysql'));
    $host = env("{$p}_HOST"); $name = env("{$p}_NAME"); $user = env("{$p}_USER");
    $port = env("{$p}_PORT") ?: ($type === 'mysql' ? '3306' : '5432');
    if ($host === '' || $user === '') { echo "{$p}_HOST / {$p}_USER belum diisi di .env", PHP_EOL; return 1; }
    if (env("{$p}_PASSWORD") === '') { echo "{$p}_PASSWORD belum diisi di .env", PHP_EOL; return 1; }

    say("== $p: $type $user@$host:$port / " . ($name !== '' ? $name : '(tanpa database)'));
    try {
        $c = connect($p);
    } catch (Throwable $e) {
        say('GAGAL KONEKSI: ' . get_class($e) . ': ' . $e->getMessage());
        say('Cek: TYPE (mysql/postgres), PORT, user/password, nama database sama seperti di DBeaver, dan ekstensi PHP pdo_mysql / pdo_pgsql aktif.');
        return save($p, 1);
    }
    say('KONEKSI BERHASIL');
    say('Versi: ' . q($c, 'SELECT version()')[1][0][0]);

    if ($name === '') {
        $sql = $type === 'mysql' ? 'SHOW DATABASES' : 'SELECT datname FROM pg_database WHERE NOT datistemplate ORDER BY 1';
        say(PHP_EOL . "Database yang tersedia (isi salah satu ke {$p}_NAME):");
        foreach (q($c, $sql)[1] as $r) say('  - ' . $r[0]);
        return save($p, 0);
    }

    // daftar tabel + perkiraan jumlah baris (tanpa COUNT(*) agar ringan untuk tabel besar)
    $rows = $type === 'mysql'
        ? q($c, 'SELECT table_name, table_rows FROM information_schema.tables WHERE table_schema = DATABASE() ORDER BY table_name')[1]
        : q($c, "SELECT c.relname, c.reltuples::bigint FROM pg_class c JOIN pg_namespace n ON n.oid = c.relnamespace "
              . "WHERE c.relkind IN ('r','v','m','p') AND n.nspname NOT IN ('pg_catalog','information_schema') ORDER BY 1")[1];
    $have = [];
    foreach ($rows as $r) $have[strtolower($r[0])] = $r[1];
    say(PHP_EOL . 'Jumlah tabel/view: ' . count($rows));

    foreach (TARGET[$p] ?? [] as $target) {
        say(PHP_EOL . "--- $target ---");
        [$sch, $t] = str_contains($target, '.') ? explode('.', $target, 2) : ['public', $target];
        if (!array_key_exists(strtolower($t), $have)) {
            $kata = substr($t, strrpos($t, '_') + 1);
            $mirip = array_slice(array_filter(array_keys($have), fn($x) => str_contains($x, $kata)), 0, 10);
            say('  TIDAK DITEMUKAN.' . ($mirip ? ' Mirip: ' . implode(', ', $mirip) : ''));
            continue;
        }
        say('  perkiraan baris: ' . $have[strtolower($t)]);
        [$c2, $r2] = $type === 'mysql'
            ? q($c, 'SELECT column_name, column_type, is_nullable, column_key FROM information_schema.columns '
                  . 'WHERE table_schema = DATABASE() AND table_name = ? ORDER BY ordinal_position', [$t])
            : q($c, 'SELECT column_name, data_type, is_nullable FROM information_schema.columns '
                  . 'WHERE table_schema = ? AND table_name = ? ORDER BY ordinal_position', [$sch, $t]);
        table($c2, $r2);
        if ($sample > 0) {
            say("  contoh $sample baris:");
            $ident = $type === 'mysql' ? "`$t`" : "\"$sch\".\"$t\"";
            table(...q($c, "SELECT * FROM $ident LIMIT $sample"));
        }
    }
    return save($p, 0);
}

exit(main());
