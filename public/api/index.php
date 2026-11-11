<?php
/* API QUEDRIVE: /api/health, /api/drivers?tanggal=YYYY-MM-DD, /api/units,
   /api/sj, /api/depo, /api/shipments?tanggal=YYYY-MM-DD, /api/spk-spo?tanggal=YYYY-MM-DD, /api/dms/<view>?is_active=true|false&limit=N&offset=N (lihat DMS_VIEWS di lib.php),
   /api/store (penyimpanan dashboard di database QUEDRIVE, lihat app/store.php)
   Nginx meneruskan semua /api/* ke file ini (lihat server/nginx-quedrive.conf). */
require __DIR__ . '/../../app/lib.php';
require __DIR__ . '/../../app/store.php';

set_time_limit(120);
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function reply($data, int $code = 200): void {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

// pesan error dengan format {"detail": "..."} (dibaca oleh dashboard)
function fail(int $code, string $msg): void {
    reply(['detail' => $msg], $code);
}

$path = rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');

// angka bulat dari query string, di luar batas -> 422
function int_param(string $key, int $default, int $min, int $max): int {
    $v = $_GET[$key] ?? '';
    if ($v === '') return $default;
    if (!ctype_digit($v) || (int) $v < $min || (int) $v > $max) fail(422, "$key harus angka $min-$max");
    return (int) $v;
}

// tanggal dari query string (kosong = hari ini), format salah -> 422
function date_param(): string {
    $t = $_GET['tanggal'] ?? '';
    if ($t === '') return date('Y-m-d');
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $t) || !checkdate((int) substr($t, 5, 2), (int) substr($t, 8, 2), (int) substr($t, 0, 4)))
        fail(422, 'Format tanggal harus YYYY-MM-DD');
    return $t;
}

try {
    if (preg_match('#^/api/dms/([a-z0-9-]+)$#', $path, $m)) {
        $q = DMS_VIEWS[$m[1]] ?? null;
        if ($q === null) fail(404, 'View DMS tidak dikenal: ' . $m[1]);
        $a = strtolower($_GET['is_active'] ?? '');
        if (!in_array($a, ['', 'true', 'false', '1', '0'], true)) fail(422, 'is_active harus true atau false');
        $aktif = $a === '' ? null : in_array($a, ['true', '1'], true);
        $limit = int_param('limit', DMS_LIMIT_DEFAULT, 1, DMS_LIMIT_MAX);
        $offset = int_param('offset', 0, 0, 1000000);
        $rows = cached("dms_{$m[1]}_{$a}_{$limit}_$offset", fn() => dms_rows($q, $aktif, $limit, $offset));
        reply(['view' => $m[1], 'is_active' => $aktif, 'limit' => $limit, 'offset' => $offset, 'count' => count($rows), 'rows' => $rows]);
    }

    switch ($path) {
        case '/api/health':
            $res = [];
            foreach (db_prefixes() as $p) {
                $t = microtime(true);
                try {
                    connect($p)->query('SELECT 1');
                    $res[$p] = ['ok' => true, 'ms' => (int) round((microtime(true) - $t) * 1000)];
                } catch (Throwable $e) {
                    $res[$p] = ['ok' => false, 'error' => get_class($e) . ': ' . $e->getMessage()];
                }
            }
            $t = microtime(true);
            try {
                store_db()->query('SELECT 1');
                $res['QD'] = ['ok' => true, 'ms' => (int) round((microtime(true) - $t) * 1000)];
            } catch (Throwable $e) {
                $res['QD'] = ['ok' => false, 'error' => get_class($e) . ': ' . $e->getMessage()];
            }
            reply($res);

        case '/api/drivers':
            $t = date_param();
            reply(cached("drivers_" . login_user() . "_$t", fn() => build_drivers($t)));

        case '/api/shipments':
            $t = date_param();
            reply(cached("shipments_" . login_user() . "_$t", fn() => build_shipments($t)));

        case '/api/units':
            reply(cached('units_' . login_user(), 'build_units'));

        case '/api/spk-spo':
            $t = date_param();
            reply(cached("spk_spo_" . login_user() . "_$t", fn() => build_spk_spo($t)));

        case '/api/depo':
            reply(cached('depo', 'build_depo'));

        case '/api/sj':
            reply(cached('sj', 'build_sj'));

        case '/api/store':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $req = json_decode((string) file_get_contents('php://input'));   // objek: {} tetap {} (bukan [])
                if (!is_object($req)) fail(422, 'Body harus JSON');
                $res = store_write((array) $req);
                if (!empty($res['missing'])) fail(409, 'dokumen belum ada');
                reply($res);
            }
            reply(store_changes(int_param('since', 0, 0, PHP_INT_MAX)));

        default:
            fail(404, 'Not Found');
    }
} catch (InvalidArgumentException $e) {
    fail(422, $e->getMessage());
} catch (PDOException | RuntimeException $e) {
    fail(503, 'Database tidak bisa dibaca: ' . $e->getMessage());
}
