/* =====================================================================
   QUEDRIVE – adapter database untuk tampilan HTML user (Quedrive - Transport Trailer).
   Dimuat SESUDAH script tampilan (lihat scripts/build_dashboard.php). Tampilan & aturan hitung tetap kode dari HTML user;
   file ini hanya:
     1. Penyimpanan: /api/store (database quedrive: qd_antrian, qd_setting, qd_history, qd_catatan) menggantikan
        penyimpanan artifact / browser.
     2. Data sumber dari database lewat API PHP, mengisi struktur yang sama dengan hasil upload file di HTML:
          Attendance + Monitoring  <- Quickapps trx_dms_absent (kode absen, jam = updated_at)        /api/drivers
          DOTS Report + GPS        <- Quickapps trx_gps_tracking + trx_shipment_preparation          /api/shipments
          bd_export (FMS BD)       <- FMS service order -> hand over                                  /api/units
          P2H                      <- puninar_p2h check_records                                       /api/units
          Klinik                   <- Quickapps trx_dms_absent.status_klinik (absen hari itu)         /api/drivers
          Download Detail SJ       <- odongnew_db (SJ mulai 01-Nov-2026)                              /api/sj
          Master Driver / Unit     <- Quickapps mst_driver (scope role) / FMS fl_mst_fleet_otm        /api/drivers, /api/units
     3. Aturan yang disepakati: akses data per role (dari API), Master Driver versi PHP (+ Catatan, urut kolom,
        Last Location), Master Unit FMS (+ Pool, Catatan maks 12 karakter, Status FMS BD), Depo dropdown,
        driver nonaktif tidak ditampilkan di antrian, halaman Upload = status sumber data (tanpa upload file).
   Fungsi tampilan yang diganti ditulis ulang di bawah (assignment ke fungsi global), bukan mengubah kode user,
   supaya update HTML berikutnya cukup dibangun ulang dengan scripts/build_dashboard.php.
   ===================================================================== */

/* ---------- utilitas ---------- */
const QD = { ok: false, err: '', at: 0, drivers: [], drvById: new Map(), units: [], ship: null, sj: null, sjErr: '', depo: [], days: new Map(), notes: { driver: {}, unit: {} }, loading: null };
const QD_REFRESH_MS = 60000;   // backend meng-cache 2 menit
const qdIsoSerial = s => { const m = /^(\d{4})-(\d{2})-(\d{2})/.exec(s || ''); return m ? ymd(+m[1], +m[2], +m[3]) : null; };
const qdIsoKey = s => { const m = /^(\d{4})-(\d{2})-(\d{2})[ T](\d{2}):(\d{2})/.exec(s || ''); return m ? ymd(+m[1], +m[2], +m[3]) * 1440 + (+m[4]) * 60 + (+m[5]) : null; };
const qdKeyIso = s => dateKey(s);   // serial -> YYYY-MM-DD
const qdTipe = id => { const m = /^([A-Za-z]+)-/.exec(id || ''); return m ? m[1].toUpperCase() : ''; };
async function qdGet(path) {
  const r = await fetch(path, { cache: 'no-store' });
  if (!r.ok) { let m = r.status + ' ' + r.statusText; try { m = (await r.json()).detail || m; } catch (e) {} throw new Error(m); }
  return r.json();
}

/* =====================================================================
   1. PENYIMPANAN: /api/store (database quedrive)
   ===================================================================== */
const QD_POLL_MS = 3000;
async function qdStorePost(body) {
  const r = await fetch('/api/store', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(body) });
  if (!r.ok) {
    let m = r.status + ' ' + r.statusText; try { m = (await r.json()).detail || m; } catch (e) {}
    const e = new Error(m); e.status = r.status; e.code = r.status === 409 ? 'invalid_argument' : 'server'; throw e;   // 409 = dokumen belum ada (upsert lalu set)
  }
  return r.json();
}
// antarmuka sama dengan db artifact (doc / collection, get / set / update / delete / onSnapshot); perubahan browser lain diambil tiap 3 detik
async function qdServerDb() {
  if (location.protocol === 'file:') return null;
  let first = null;
  for (let i = 0; i < 3 && !first; i++) {   // kegagalan sesaat (mis. server baru dinyalakan) dicoba ulang
    if (i) await new Promise(res => setTimeout(res, 2000 * i));
    try { first = await qdGet('/api/store?since=0'); } catch (e) {}
  }
  if (!first) return null;
  const store = {}, ver = {}, subs = new Set();
  let since = Math.max(0, (first.ver || 0) - 5e6);
  const clone = o => JSON.parse(JSON.stringify(o));
  const notify = () => setTimeout(() => subs.forEach(f => f()), 0);
  const apply = docs => { for (const d of docs) { if ((ver[d.path] || 0) >= d.ver) continue; if (d.deleted) delete store[d.path]; else store[d.path] = d.data; ver[d.path] = d.ver; } if (docs.length) notify(); };
  apply(first.docs);
  const poll = async () => {
    try { const r = await qdGet('/api/store?since=' + since); apply(r.docs); since = Math.max(since, (r.ver || 0) - 5e6); } catch (e) {}
    setTimeout(poll, QD_POLL_MS);
  };
  setTimeout(poll, QD_POLL_MS);
  const has = p => Object.prototype.hasOwnProperty.call(store, p);
  const mkSnap = p => { const ex = has(p); return { id: p.split('/').pop(), exists: ex, data: () => ex ? clone(store[p]) : undefined, metadata: {} }; };
  const colPaths = c => Object.keys(store).filter(p => p.startsWith(c + '/') && p.indexOf('/', c.length + 1) < 0).sort();
  const qSnap = c => { const docs = colPaths(c).map(mkSnap); return { docs, size: docs.length, empty: !docs.length }; };
  const sub = (sigFn, snapFn, next) => { let last = null; const f = () => { const g = sigFn(); if (g === last) return; last = g; next(snapFn()); }; subs.add(f); setTimeout(f, 0); return () => subs.delete(f); };
  const write = async (op, path, data) => { const r = await qdStorePost({ op, path, data }); apply([r.doc]); };
  const docRef = p => ({ id: p.split('/').pop(), path: p,
    get: async () => mkSnap(p),
    set: d => write('set', p, d),
    update: d => write('update', p, d),
    delete: () => write('delete', p),
    acquire: async o => qdStorePost({ op: 'acquire', path: p, holder: o && o.holder, ttlMs: o && o.ttlMs }),
    onSnapshot: next => sub(() => p + ':' + (ver[p] || 0) + has(p), () => mkSnap(p), next) });
  const colRef = c => ({ path: c, doc: id => docRef(c + '/' + id), get: async () => qSnap(c),
    onSnapshot: next => sub(() => colPaths(c).map(p => p + ':' + (ver[p] || 0)).join('|'), () => qSnap(c), next) });
  return { doc: docRef, collection: colRef };
}
// database quedrive tidak bisa dihubungi: data hanya di memori halaman, mode baca saja
function qdMemoryDb() {
  const store = {}, ver = {}, subs = new Set(), clone = o => JSON.parse(JSON.stringify(o));
  const has = p => Object.prototype.hasOwnProperty.call(store, p);
  const mkSnap = p => { const ex = has(p); return { id: p.split('/').pop(), exists: ex, data: () => ex ? clone(store[p]) : undefined, metadata: {} }; };
  const colPaths = c => Object.keys(store).filter(p => p.startsWith(c + '/') && p.indexOf('/', c.length + 1) < 0).sort();
  const sub = (sigFn, snapFn, next) => { let last = null; const f = () => { const g = sigFn(); if (g === last) return; last = g; next(snapFn()); }; subs.add(f); setTimeout(f, 0); return () => subs.delete(f); };
  const ro = async () => { const e = new Error('database QUEDRIVE tidak terhubung'); e.code = 'server'; throw e; };
  const docRef = p => ({ id: p.split('/').pop(), path: p, get: async () => mkSnap(p), set: ro, update: ro, delete: ro, acquire: async () => ({ acquired: false }),
    onSnapshot: next => sub(() => p + ':' + (ver[p] || 0) + has(p), () => mkSnap(p), next) });
  const colRef = c => ({ path: c, doc: id => docRef(c + '/' + id), get: async () => ({ docs: colPaths(c).map(mkSnap), size: 0, empty: true }),
    onSnapshot: next => sub(() => colPaths(c).join('|'), () => ({ docs: colPaths(c).map(mkSnap), size: 0, empty: true }), next) });
  return { doc: docRef, collection: colRef };
}
initDb = async function () {
  const srv = await qdServerDb();
  if (srv) { DB = srv; MODE = 'server'; CAN_WRITE = true; }
  else { DB = qdMemoryDb(); MODE = 'local'; CAN_WRITE = false; }
};
dbWrite = async function (fn) {
  if (!CAN_WRITE) { toast(MODE === 'local' ? 'Database QUEDRIVE tidak terhubung: perubahan tidak bisa disimpan. Muat ulang halaman setelah server berjalan.' : 'Mode baca saja: akun ini tidak bisa mengubah data.'); return false; }
  try { await fn(); return true; } catch (e) { handleWriteError(e); return false; }
};
handleWriteError = function (e) {
  if (e && e.status === 422) toast('Tidak bisa disimpan: ' + e.message);
  else toast('Gagal menyimpan ke database QUEDRIVE' + (e && e.message ? ' (' + e.message + ')' : '') + '. Coba lagi.');
};

/* ---------- catatan driver / unit: tabel qd_catatan (dokumen catatan/driver & catatan/unit) ---------- */
// format tampilan user: S.notes = { drivers: {ID: [[serial, teks], ...]}, units: {NOPOL: [...]} }
function qdApplyNotes() {
  const conv = items => { const o = {}; for (const [k, arr] of Object.entries(items || {})) o[k] = (arr || []).map(([iso, t]) => [qdIsoSerial(iso), t]).filter(e => e[0] != null).sort((a, b) => a[0] - b[0]); return o; };
  S.notes = { drivers: conv(QD.notes.driver), units: conv(QD.notes.unit) };
}
const QD_NOTE_MAX = { drivers: 255, units: 12 };   // catatan unit maks 12 karakter (keputusan meeting)
saveNote = async function (kind, key, text) {
  text = String(text || '').replace(/\s+/g, ' ').trim();
  const max = QD_NOTE_MAX[kind] || 255;
  if (text.length > max) { toast(`Catatan ${kind === 'units' ? 'unit' : 'driver'} maksimal ${max} karakter.`); text = text.slice(0, max); }
  if (text === noteOf(kind, key, todaySerial())) return;
  const ok = await dbWrite(() => DB.doc('catatan/' + (kind === 'units' ? 'unit' : 'driver')).update({ [key]: text }));
  if (ok) toast(text ? 'Catatan disimpan, berlaku mulai hari ini.' : 'Catatan dihapus mulai hari ini.');
  else { renderMasterDriver(); renderMasterUnit(); }
};
// saran isian catatan = catatan yang sedang berlaku di master; catatan yang tidak dipakai lagi hilang dari saran
function qdNoteSuggest(kind, keys) {
  const el = document.getElementById('qd-cat-' + kind); if (!el) return;
  const seen = new Map(), t = todaySerial();
  for (const k of keys) { const v = noteOf(kind, k, t); if (v && !seen.has(v.toUpperCase())) seen.set(v.toUpperCase(), v); }
  el.innerHTML = [...seen.values()].sort((a, b) => a.localeCompare(b)).map(v => `<option value="${esc(v)}"></option>`).join('');
}

/* =====================================================================
   2. DATA SUMBER DARI DATABASE -> struktur data tampilan user
   ===================================================================== */
// absensi per tanggal (Quickapps trx_dms_absent): "file" Attendance (kode) dan Monitoring (jam) buatan
function qdBuildAtt() {
  const days = [...QD.days.keys()].sort((a, b) => a - b), ids = new Map();
  for (const d of days) for (const x of QD.days.get(d)) if (!ids.has(normId(x.id))) ids.set(normId(x.id), x);
  const grid = [], mon = [];
  for (const [k, x] of ids) {
    grid.push([x.id, x.nama, days.map(d => { const r = QD.days.get(d).find(y => y.id === x.id); return r && r.initial ? r.initial : ''; })]);
    mon.push([x.id, x.nama, days.map(d => { const r = QD.days.get(d).find(y => y.id === x.id); return r && r.initial && r.jam ? `X@${r.jam};OR@${r.jam}` : ''; })]);
  }
  const mk = (kind, name, rows) => ({ id: kind, meta: { kind, name, at: 1, d0: days[0], d1: days[days.length - 1] }, days, rows, map: new Map(rows.map(r => [normId(r[0]), r])), idx: new Map(days.map((v, i) => [v, i])) });
  S.att = days.length ? [mk('grid', 'Quickapps trx_dms_absent (kode absen)', grid), mk('mon', 'Quickapps trx_dms_absent (jam = updated_at)', mon)] : [];
  // Status Klinik: status_klinik pada baris absen hari itu (tanpa jam pemeriksaan -> tidak kedaluwarsa 24 jam)
  const t = todaySerial(), today = QD.days.get(t) || [];
  S.klinikBy = new Map(today.filter(x => x.klinik).map(x => [normId(x.id), [{ st: x.klinik, key: -1, note: 'Status klinik dari absen hari ini (Quickapps); jam pemeriksaan belum tersedia', orig: '' }]]));
  S.loaded.att = true; S.loaded.klinik = true;
}
// shipment (Quickapps): baris DOTS [ship, d1, d2, tgl tender, menit tender, tgl execution, customer, order, rute, tipe truk, tipe shipment, FPT, nopol, proses]
// dan baris GPS [ship, nopol, driver, tgl shipment, status, mulai, jam status] per driver
function qdBuildShip() {
  const rows = (QD.ship && QD.ship.rows) || [];
  const dots = [], gps = [];
  for (const r of rows) {
    const [ship, d1, d2, tIso, exIso, cust, ord, route, truck, tipe, st, endIso, nopol, proses, , cancel] = r;
    const tk = qdIsoKey(tIso); if (tk == null) continue;
    const td = Math.floor(tk / 1440), tm = tk % 1440, end = qdIsoKey(endIso);
    if (!cancel) dots.push([ship, d1, d2, td, tm, qdIsoSerial(exIso), cust, ord, route, truck, tipe, '', nopol, proses]);   // FPT kosong: status FPT belum ada di database
    for (const d of [d1, d2]) if (d) gps.push([ship, nopol, d, td, cancel ? 'CANCEL' : st, tk, end]);
  }
  S.dotsRows = dots; S.dotsIds = ['db'];
  S.gpsRows = gps; S.gpsIds = ['db']; S.gpsBy = new Map();
  for (const r of gps) { const k = normId(r[2]); if (!S.gpsBy.has(k)) S.gpsBy.set(k, []); S.gpsBy.get(k).push({ ship: r[0], nopol: r[1], drv: r[2], day: r[3], status: String(r[4] || '').toUpperCase(), start: r[5], end: r[6] || null }); }
  for (const a of S.gpsBy.values()) a.sort((x, y) => x.day - y.day || x.start - y.start || (x.ship < y.ship ? -1 : 1));
  rebuildShip();
  S.loaded.dots = true; S.loaded.gps = true;
}
// master driver (aktif, scope role) & unit (FMS), P2H, Status FMS BD, SJ
function qdBuildMasters() {
  S.masterDB = QD.drivers.filter(x => x.aktif === true).map(x => ({ id: x.id, nama: nmUp(x.nama), status: qdTipe(x.id), aktif: null, nonaktif: null }));
  QD.drvById = new Map(QD.drivers.map(x => [x.id, x]));
  S.unitDB = QD.units.map(u => ({ nopol: u.nopol, type: u.tipe_unit || '', sub: u.sub_status || '', pool: u.pool || '', aktif: null, nonaktif: null, sumber: u.sumber }));
  // Status P2H = status TERAKHIR, tidak kedaluwarsa (keputusan user 11-Nov): kunci waktu -1 = selalu berlaku; jam inspeksi disimpan di kolom "asli" untuk tooltip
  S.p2hBy = buildBy(QD.units.filter(u => u.p2h).map(u => [normPlat(u.nopol), u.p2h, -1, '', u.p2h_at || '']));
  const bdOk = QD.units.some(u => u.fms_bd);
  S.bdRows = bdOk ? QD.units : []; S.bdBy = new Map();
  for (const u of QD.units) {
    const so = u.fms_bd_so; if (!so) continue;
    S.bdBy.set(normPlat(u.nopol), [{ nopol: u.nopol, so: qdIsoKey(so.so_date), ho: qdIsoKey(so.handover), del: false, status: u.fms_bd, pool: u.pool,
      prob: [so.tipe, so.repair ? 'Perbaikan: ' + so.repair : '', so.rootcause ? 'Root cause: ' + so.rootcause : '', so.received ? 'Diterima bengkel ' + so.received : 'Belum diterima bengkel'].filter(Boolean).join(' · '), id: so.wo || 'SO' }]);
  }
  S.sjRows = (QD.sj || []).map(x => [x.shipment_number, x.pool || '', x.customer || '', qdIsoSerial(x.tender_date), null, x.driver_id || '', '', x.nopol || '']);
  S.loaded.md = S.loaded.mu = S.loaded.p2h = S.loaded.bd = S.loaded.sj = true;
}
// muat data sumber untuk tanggal aplikasi (Tanggal tertentu di chip kanan atas ikut dipakai)
async function qdLoad() {
  if (QD.loading) return QD.loading;
  QD.loading = (async () => {
    const t = todaySerial(), tk = qdKeyIso(t);
    try {
      if (location.protocol === 'file:') throw new Error('halaman dibuka sebagai file, bukan lewat server');
      const [d, u, sh] = await Promise.all([qdGet('/api/drivers?tanggal=' + tk), qdGet('/api/units'), qdGet('/api/shipments?tanggal=' + tk)]);
      QD.drivers = d.drivers || []; QD.units = u || []; QD.ship = sh;
      QD.days.set(t, QD.drivers.map(x => ({ id: x.id, nama: x.nama, initial: x.initial, jam: x.jam, klinik: x.klinik })));
      QD.ok = true; QD.err = ''; QD.at = Date.now();
    } catch (e) { QD.ok = false; QD.err = e.message || String(e); }
    try { QD.depo = await qdGet('/api/depo'); } catch (e) {}
    try { QD.sj = await qdGet('/api/sj'); QD.sjErr = ''; } catch (e) { QD.sjErr = e.message || String(e); }
    if (QD.ok || QD.drivers.length) { qdBuildMasters(); qdBuildAtt(); qdBuildShip(); }
    S.loaded.api = true;
    renderAllData(); renderShareBar();
  })();
  try { await QD.loading; } finally { QD.loading = null; }
}
// absensi tanggal lain (RAW Data, History) dimuat bila diperlukan
async function qdEnsureDay(day) {
  if (day == null || QD.days.has(day)) return false;
  try {
    const d = await qdGet('/api/drivers?tanggal=' + qdKeyIso(day));
    QD.days.set(day, (d.drivers || []).map(x => ({ id: x.id, nama: x.nama, initial: x.initial, jam: x.jam, klinik: x.klinik })));
    qdBuildAtt(); return true;
  } catch (e) { return false; }
}

/* ---------- langganan data (pengganti subscribeAll tampilan user: tidak ada koleksi upload) ---------- */
subscribeAll = function () {
  const onErr = e => { if (e && (e.code === 'revoked')) { CAN_WRITE = false; renderShareBar(); } };
  DB.doc('app/queue').onSnapshot(s => { S.queueItems = (s.exists && s.data().items) || {}; for (const k of Object.keys(S.pending)) if (S.queueItems[k]) delete S.pending[k]; S.loaded.queue = true; renderQueueSafe(); renderListDriver(); updateDashboard(); refreshOpenViews(); }, onErr);
  DB.doc('app/config').onSnapshot(s => { S.config = s.exists ? s.data() : {}; applySettings(); rebuildShip(); S.loaded.config = true; renderAllData(); afterLoad(); }, onErr);
  for (const j of ['driver', 'unit']) DB.doc('catatan/' + j).onSnapshot(s => { QD.notes[j] = (s.exists && s.data().items) || {}; qdApplyNotes(); renderMasterDriver(); renderMasterUnit(); renderQueueSafe(); renderListDriver(); refreshOpenViews(); }, onErr);
  qdLoad();
  setInterval(qdLoad, QD_REFRESH_MS);
};
// rollover / history hanya berjalan setelah data sumber pertama termuat (Status Order butuh shipment)
const _qdAfterLoad = afterLoad;
afterLoad = function () { if (!S.loaded.api) { setTimeout(afterLoad, 1000); return; } _qdAfterLoad(); };

/* =====================================================================
   3. ATURAN YANG DISEPAKATI
   ===================================================================== */
// driver nonaktif / di luar akses role tidak ditampilkan di antrian (dipakai patch antrian() di build)
function qdShowQueue(id) { return !S.masterDB.length || S.masterDB.some(d => d.id === id); }

// STATUS AVA OTOMATIS (aturan QUEDRIVE, keputusan user): AVA hanya bila Kode Absensi X (Pool) DAN shipment terakhir
// sudah closed / forceclosed (atau tidak punya shipment 15 hari terakhir). Selain itu NOT AVA. Driver NOT AVA berkode X / OR / N/A
// tetap bisa ditambahkan MANUAL ke antrian lewat konfirmasi, tanpa mengubah status AVA otomatis.
statusAntrianOf = function (id, day) {
  const ab = absensiOf(id, day), g = lastGps(id, day), res = { status: 'NOT AVA', ab, why: '', gps: g };
  if (ab.kode !== 'X') { res.why = ab.kode === 'N/A' ? 'Belum absen hari ini (Kode Absensi N/A)' : `Kode Absensi ${ab.kode === 'OR' ? 'OR (On Road)' : ab.kode}, bukan X (Pool)`; return res; }
  if (!g) { res.status = 'AVA'; res.why = 'Absen X (Pool) dan tidak ada shipment 15 hari terakhir'; return res; }
  if (closeSet().has(g.status)) { res.status = 'AVA'; res.closed = g.end || null; res.why = `Absen X (Pool) dan shipment terakhir ${g.ship} sudah ${g.status.toLowerCase()}` + (g.end ? ` (${soTxt(g.end)})` : ''); return res; }
  res.why = `Absen X (Pool), tetapi shipment terakhir ${g.ship} masih ${g.status.toLowerCase()}`; return res;
};
const QD_MANUAL_KODE = ['X', 'OR', 'N/A'];   // NOT AVA yang boleh ditambah manual (dengan konfirmasi); AIS & O tidak
addToAntrian = async function (id, force) {
  const d = driverListToday().find(x => x.id_driver === id);
  if (!d || (d.status_lokasi !== 'AVA' && !(force && QD_MANUAL_KODE.includes(d.kode))) || antriAktif().some(a => a.id === id)) return;
  const k = keyOf(id);
  const item = { id, nama: d.nama, jam_absen: d.jam_absen || '', absen_day: todaySerial(), antri_at: nowDate().getTime(), depo: '', nopol_unit: '', type_unit: '', keterangan: '', waktu_masuk: getCurrentDateTimeStr(), seq: Date.now() };
  if (d.status_lokasi !== 'AVA') item.manual_add = { at: Date.now(), kode: d.kode, why: qdWhyTxt(d.why) };   // jejak: masuk antrian manual (bukan AVA)
  S.pending[k] = { ...item, isNew: true };
  renderListDriver(); renderListAntrian();
  const ok = await dbWrite(() => upsert('app/queue', { items: { [k]: item } }));
  if (ok) { scheduleHistorySave(); setTimeout(() => { if (S.pending[k]) { delete S.pending[k]; renderQueueSafe(); renderListDriver(); } }, 5000); }
  else { delete S.pending[k]; renderListDriver(); renderListAntrian(); }
};
addAskNotAva = function (id) {
  const d = driverListToday().find(x => x.id_driver === id); if (!d || antriAktif().some(a => a.id === id)) return;
  if (d.kode === 'N/A') { askConfirm('Driver belum absen', `Driver <b>${esc(d.nama)}</b> (${esc(id)}) <b>belum absen</b> hari ini. Apakah yakin tetap masuk antrian (manual)? Tim Driver Management bisa mengingatkan driver untuk absen terlebih dahulu.`, 'Ya, masuk antrian', () => addToAntrian(id, true)); return; }
  const os = openShipsOf(id, todaySerial());
  askConfirm('Masuk antrian (manual)?', `Driver <b>${esc(d.nama)}</b> (${esc(id)}) <b>belum AVA</b>: ${esc(qdWhyTxt(d.why))}.` + (os.length ? ` Shipment yang belum selesai:${shipLines(os)}` : '') + '<br><br>Apakah yakin tetap masuk antrian? Status AVA otomatis tidak berubah.', 'Ya, masuk antrian', () => addToAntrian(id, true));
};

// cakupan absensi: dari database, bukan file
attCoverage = function () {
  const t = todaySerial(), d = QD.days.get(t);
  if (!S.loaded.api) return { ok: true, msg: '' };
  if (!QD.ok && !d) return { ok: false, msg: 'Data absensi belum bisa dibaca dari database: ' + esc(QD.err) + '. Semua driver N/A dan NOT AVA sampai database terbaca lagi.' };
  if (!d || !d.some(x => x.initial)) return { ok: false, msg: `Belum ada kode absensi tanggal ${fmtSerial(t)} di database (Quickapps trx_dms_absent). Semua driver N/A dan NOT AVA.` };
  return { ok: true, msg: '' };
};

// kartu SPK (aturan lama): shipment SPK onroad hari ini yang tidak cancel DAN dibawa driver aktif dalam scope
// (Exim Trailler / LOBP / ESA, driver utama atau kedua). Status FPT diabaikan (keputusan user 11-Nov).
dayTotals = function (day) {
  const act = new Set(activeDrivers().map(d => d.id)), seen = new Set(), best = new Map();
  const mk = () => ({ n: 0, t20: 0, t40: 0, oth: 0, types: {} }), sp = { SPK: mk(), SPO: mk() };
  const addType = (o, tp) => { const k = String(tp || '').trim() || '(kosong)'; o.types[k] = (o.types[k] || 0) + 1; };
  for (const r of S.dotsRows) {
    const [ship, d1, d2, td, tm, ex, , , , tp, ts] = r;
    if (onDay(td, tm, ex) !== day) continue;
    const g = truckGroup(tp);
    if (!seen.has(ship) && (act.has(d1) || act.has(d2))) { seen.add(ship); const o = sp[ts]; if (o) { o.n++; o[grpKey(g)]++; if (g === 'o') addType(o, tp); } }
    const key = td * 1440 + tm;
    for (const d of [d1, d2]) if (d && act.has(d)) { const c = best.get(d); if (!c || key >= c.key) best.set(d, { key, g, tp }); }
  }
  const or = mk(); or.n = best.size; best.forEach(v => { or[grpKey(v.g)]++; if (v.g === 'o') addType(or, v.tp); });
  return { sp, or, best, hasType: true, hasTS: true, hasFPT: true };
};
{ const k = $('#count-spk'); if (k) k.closest('.kpi').title = 'SPK sudah tender: jumlah shipment SPK yang onroad hari ini (tanggal terbesar antara tanggal eksekusi dan tanggal tender), tidak cancel, dan dibawa driver aktif Exim Trailler / LOBP / ESA (driver utama atau kedua). Dihitung per shipment unik. Rincian 20 FT / 40 FT dari tipe truk. Sumber: shipment Quickapps.'; }

// Depo: dropdown dari Quickapps mst_depo (dipakai patch renderListAntrian di build)
function qdDepoCell(d) {
  const list = QD.depo || [], cur = d.depo || '', opts = (cur && !list.includes(cur) ? [cur] : []).concat(list);
  return `<select class="cell-in" data-id="${esc(d.id)}" data-key="${esc(qkey(d))}" data-f="depo" title="${esc(cur)}"${d.retired ? ' disabled' : ''}><option value="">Pilih depo...</option>${opts.map(v => `<option value="${esc(v)}"${v === cur ? ' selected' : ''}>${esc(v)}</option>`).join('')}</select>`;
}
// List Antrian tidak digambar ulang selama dropdown / isian di dalamnya sedang dipakai
const _qdRenderQueueSafe = renderQueueSafe;
renderQueueSafe = function () {
  const ae = document.activeElement;
  if (ae && ae.tagName === 'SELECT' && $('#tbAntrian').contains(ae)) { queuePending = true; return; }
  _qdRenderQueueSafe();
};

// sel Status P2H (Master Unit & List Antrian): status terakhir apa adanya + jam inspeksi terakhir di tooltip
p2hCell = function (nopol, day) {
  if (!String(nopol || '').trim()) return '';
  const x = p2hRec(nopol, day);
  if (!x) return naPill('Tidak ada riwayat P2H untuk unit ini di puninar_p2h.');
  const k = qdIsoKey(x.r.orig), ago = k == null ? null : Math.floor((keyAt() - k) / 1440);
  const tip = k == null ? 'Status P2H terakhir' : `P2H terakhir ${soTxt(k)}` + (ago > 0 ? ` (${ago} hari lalu)` : ' (hari ini)');
  return `<span class="pill ${p2hClass(x.r.st)}" title="${esc(tip)}">${esc(x.r.st)}</span>`;
};

/* ---------- tombol Filter (pop up) di Master Driver & Master Unit, seperti versi PHP ----------
   Pilihan diambil dari nilai yang ada di data; tidak dicentang dalam satu kelompok = semua. */
function qdPopFilter(id, title, inputSel, fields, getList, rerender) {
  const sel = Object.fromEntries(fields.map(f => [f[0], new Set()]));
  const lab = v => (v === '' || v === '-' ? 'Tidak ada data' : v);
  $(inputSel).insertAdjacentHTML('afterend', `<div class="df-wrap" id="${id}Wrap"><button class="btn qd-fbtn" id="${id}Btn" type="button" aria-haspopup="dialog" aria-expanded="false" aria-controls="${id}Panel"><svg class="ic" viewBox="0 0 24 24"><path d="M3 5h18l-7 8v6l-4 2v-8z"/></svg>Filter<span class="df-n" id="${id}Count" hidden>0</span></button>` +
    `<div class="df-panel" id="${id}Panel" role="dialog" aria-label="${title}" hidden><div class="df-h"><b>${title}</b><button class="modal-x" data-x type="button" aria-label="Tutup">&times;</button></div><div id="${id}Body"></div>` +
    `<div class="df-act"><button class="btn" data-reset type="button">Reset</button><button class="btn primary" data-x type="button">Selesai</button></div></div></div>`);
  const wrap = $('#' + id + 'Wrap'), panel = $('#' + id + 'Panel'), btn = $('#' + id + 'Btn');
  const active = () => fields.reduce((n, [k]) => n + sel[k].size, 0);
  const badge = () => { const n = active(), c = $('#' + id + 'Count'); c.textContent = n; c.hidden = !n; btn.classList.toggle('on', n > 0); };
  const body = () => {
    const list = getList();
    $('#' + id + 'Body').innerHTML = fields.map(([k, l, get, order]) => {
      const vals = [...new Set(list.map(get).concat([...sel[k]]))];
      const rank = v => { const i = (order || []).indexOf(v); return i < 0 ? 99 : i; };
      vals.sort((a, b) => rank(a) - rank(b) || (a === '-' || a === '') - (b === '-' || b === '') || String(a).localeCompare(String(b)));
      return `<div class="df-g" role="group" aria-label="${l}"><div class="df-l">${l}</div><div class="df-opts">` +
        vals.map(v => `<label class="df-o"><input type="checkbox" data-k="${k}" value="${esc(v)}"${sel[k].has(v) ? ' checked' : ''}>${esc(lab(v))}</label>`).join('') + '</div></div>';
    }).join('');
  };
  const toggle = open => { if (open == null) open = panel.hidden; if (open) body(); panel.hidden = !open; btn.setAttribute('aria-expanded', open); };
  btn.addEventListener('click', () => toggle());
  panel.addEventListener('click', e => { if (e.target.closest('[data-x]')) toggle(false); if (e.target.closest('[data-reset]')) { fields.forEach(([k]) => sel[k].clear()); body(); badge(); rerender(); } });
  panel.addEventListener('change', e => { const c = e.target; if (!c.dataset.k) return; sel[c.dataset.k][c.checked ? 'add' : 'delete'](c.value); badge(); rerender(); });
  document.addEventListener('click', e => { if (!panel.hidden && !wrap.contains(e.target)) toggle(false); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && !panel.hidden) { toggle(false); btn.focus(); } });
  return { ok: x => fields.every(([k, , get]) => !sel[k].size || sel[k].has(get(x))), active };
}
const QD_MDF = qdPopFilter('mdf', 'Filter Master Driver', '#searchMD', [
  ['proj', 'Project', d => qdProject((QD.drvById.get(d.id) || {}).project), ['Exim Trailer', 'LOBP', 'ESA']],
  ['tipe', 'Tipe Driver', d => qdTipe(d.id), ['DM', 'DOS']],
  ['klinik', 'Status Klinik', d => klinikOf(d.id) || '-', ['FIT', 'UNFIT']],
  ['ship', 'Last Shipment Status', d => (QD.drvById.get(d.id) || {}).last_shipment || '-', ['On Progress', 'Ready', 'Closed']]
], () => activeDrivers(), () => renderMasterDriver());
const QD_MUF = qdPopFilter('muf', 'Filter Master Unit', '#searchMU', [
  ['tipe', 'Tipe Unit', u => u.type || '-'],
  ['sub', 'Sub Status', u => u.sub || '-'],
  ['p2h', 'Status P2H', u => p2hOf(u.nopol) || '-', ['READY', 'BREAKDOWN', 'PREVENTIVE']],
  ['bd', 'Status FMS BD', u => unitStatus(u.nopol) || '-']
], () => activeUnits(), () => renderMasterUnit());

/* ---------- Master Driver (versi PHP: Project, Status Klinik, Last Shipment, Last Unloading, Last Location) ---------- */
const QD_TRACK_URL = 'https://app.puninar.com/3/public/live-tracking?licenseNumber=';
const QD_SHIP_LABEL = { ONPROGRESS: 'On Progress', READY: 'Ready', CLOSED: 'Closed', FORCECLOSED: 'Closed' };
// pil Last Shipment: satu gaya untuk Master Driver dan detail ▾ List Driver (Closed hijau, Ready biru, On Progress oranye)
const QD_SH_CLS = { Closed: 'sh-cl', Ready: 'sh-rd', 'On Progress': 'sh-op' };
const qdShipTag = st => `<span class="pill sh ${QD_SH_CLS[st] || 'sh-op'}">${esc(st)}</span>`;
const qdShipPill = st => st ? qdShipTag(st) : '<span class="note" title="Tidak ada shipment 7 hari terakhir">-</span>';
function qdLastLoc(x) {
  if (x && x.tracking_nopol) {
    const b64 = btoa(x.tracking_nopol).replace(/\+/g, '%2B').replace(/\//g, '%2F');
    return `<a class="btn trk" href="${QD_TRACK_URL}${b64}" target="_blank" rel="noopener" title="Live tracking ${esc(x.tracking_nopol)} (${esc(x.last_shipment || '')})">Lokasi</a>`;
  }
  const why = !x ? 'Data driver belum termuat' : x.nopol_driver_lain ? `Nopol ${x.nopol || ''} sekarang dipakai shipment driver lain (${x.nopol_driver_lain})`
    : x.last_shipment === 'Closed' ? 'Shipment terakhir sudah Closed' : x.last_shipment ? 'Nopol shipment tidak ada' : 'Tidak ada shipment 7 hari terakhir';
  return `<span class="note" title="${esc(why)}">-</span>`;
}
const qdProject = p => (p === 'Exim Trailler' ? 'Exim Trailer' : p || '-');
const qdMdCols = t => [
  { label: 'ID Driver', get: d => d.id }, { label: 'Nama Driver', cls: 'l', get: d => d.nama }, { label: 'Catatan', cls: 'l', get: d => noteDriver(d.id, t) },
  { label: 'Project', get: d => qdProject((QD.drvById.get(d.id) || {}).project) }, { label: 'Status Klinik', get: d => klinikOf(d.id) || 'N/A' },
  { label: 'Last Shipment Status', get: d => (QD.drvById.get(d.id) || {}).last_shipment || '-' },
  { label: 'Last Unloading', cls: 'l', get: d => (QD.drvById.get(d.id) || {}).last_unloading || '-' },
  { label: 'Last Location', get: d => ((QD.drvById.get(d.id) || {}).tracking_nopol ? 'Lokasi' : '-') }
];
renderMasterDriver = function () {
  const t = todaySerial(), cols = qdMdCols(t);
  if (inEditing($('#v-masterDriver'))) { pendingViews.add('masterDriver'); return; }
  const list = msearch(activeDrivers(), cols, MSEARCH.md).filter(QD_MDF.ok);
  SFREG.md = { names: cols.map(c => c.label), matrix: () => sfMatrix(list, cols), render: renderMasterDriver };
  $('#mdHead').innerHTML = '<th>No</th>' + cols.map((c, i) => sfTh('md', i, c.label, c.cls)).join('');
  const view = sfApply('md', list, cols);
  qdNoteSuggest('drivers', activeDrivers().map(d => d.id));
  $('#tbMD').innerHTML = view.length ? view.map((d, i) => {
    const x = QD.drvById.get(d.id) || {}, note = noteDriver(d.id, t);
    return `<tr><td class="num">${i + 1}</td><td>${esc(d.id)}</td><td class="l">${esc(d.nama)}</td>` +
      `<td class="l"><input type="text" class="cell-in note-in" data-note="drivers" data-k="${esc(d.id)}" value="${esc(note)}" title="${esc(note)}" list="qd-cat-drivers" placeholder="Tulis catatan..."${CAN_WRITE ? '' : ' disabled'}></td>` +
      `<td>${esc(qdProject(x.project))}</td><td>${klinikCell(d.id)}</td><td>${qdShipPill(x.last_shipment)}</td>` +
      (x.last_unloading ? `<td class="l" title="Tiba ${esc(x.last_unloading_at || '')}">${esc(x.last_unloading)}</td>` : '<td class="note" title="Tidak ada stop bongkar dalam 7 hari terakhir">-</td>') +
      `<td>${qdLastLoc(x)}</td></tr>`;
  }).join('') : `<tr><td colspan="9" class="empty-row">${!S.loaded.api ? 'Memuat data driver dari database…' : !QD.ok && !S.masterDB.length ? 'Data driver tidak bisa dimuat: ' + esc(QD.err) : 'Tidak ada driver yang cocok dengan pencarian / filter'}</td></tr>`;
  const per = {}; activeDrivers().forEach(d => { const p = qdProject((QD.drvById.get(d.id) || {}).project); per[p] = (per[p] || 0) + 1; });
  $('#cntMD').textContent = activeDrivers().length + ' driver aktif (' + Object.entries(per).map(([p, n]) => p + ' ' + n).join(', ') + ') · sumber Quickapps mst_driver' + (view.length === activeDrivers().length ? '' : ` · menampilkan ${view.length}`);
  $('#resetMD').hidden = !(sfActive('md') || MSEARCH.md.trim());
};

/* ---------- Master Unit (FMS: Pool, Catatan maks 12 karakter, Status P2H, Status FMS BD) ---------- */
const qdMuCols = t => [
  { label: 'Nopol Unit', cls: 'l', get: u => u.nopol }, { label: 'Tipe Unit', get: u => u.type || '-' }, { label: 'Catatan', cls: 'l', get: u => noteUnit(u.nopol, t) },
  { label: 'Pool', get: u => u.pool || '-' }, { label: 'Sub Status', get: u => u.sub || '-' },
  { label: 'Status P2H', get: u => p2hOf(u.nopol) || 'N/A' }, { label: 'Status FMS BD', get: u => unitStatus(u.nopol) }
];
renderMasterUnit = function () {
  const t = todaySerial(), cols = qdMuCols(t);
  if (inEditing($('#v-masterUnit'))) { pendingViews.add('masterUnit'); return; }
  const list = msearch(activeUnits(), cols, MSEARCH.mu).filter(QD_MUF.ok);
  SFREG.mu = { names: cols.map(c => c.label), matrix: () => sfMatrix(list, cols), render: renderMasterUnit };
  $('#muHead').innerHTML = '<th>No</th>' + cols.map((c, i) => sfTh('mu', i, c.label, c.cls)).join('');
  const view = sfApply('mu', list, cols);
  qdNoteSuggest('units', activeUnits().map(u => normPlat(u.nopol)));
  $('#tbMU').innerHTML = view.length ? view.map((u, i) => {
    const note = noteUnit(u.nopol, t);
    return `<tr><td class="num">${i + 1}</td><td class="l">${esc(u.nopol)}</td><td>${esc(u.type || '-')}</td>` +
      `<td class="l"><input type="text" class="cell-in note-in" data-note="units" data-k="${esc(normPlat(u.nopol))}" value="${esc(note)}" title="${esc(note || 'Maksimal 12 karakter')}" maxlength="12" list="qd-cat-units" placeholder="Tulis catatan..."${CAN_WRITE ? '' : ' disabled'}></td>` +
      `<td>${esc(u.pool || '-')}</td><td>${esc(u.sub || '-')}</td><td class="wrap">${p2hCell(u.nopol)}</td><td class="wrap">${unitCell(u.nopol)}</td></tr>`;
  }).join('') : `<tr><td colspan="8" class="empty-row">${!S.loaded.api ? 'Memuat data unit dari database…' : !QD.ok && !S.unitDB.length ? 'Data unit tidak bisa dimuat: ' + esc(QD.err) : 'Tidak ada unit yang cocok dengan pencarian / filter'}</td></tr>`;
  const src = (S.unitDB[0] || {}).sumber === 'Quickapps' ? 'cadangan Quickapps (FMS tidak terbaca)' : 'FMS fl_mst_fleet_otm';
  $('#cntMU').textContent = activeUnits().length + ' unit aktif Pool Nagrak Trailer · sumber ' + src + ' · Status P2H puninar_p2h · Status FMS BD service order FMS 30 hari' + (view.length === activeUnits().length ? '' : ` · menampilkan ${view.length}`);
  $('#resetMU').hidden = !(sfActive('mu') || MSEARCH.mu.trim());
  refreshUnitList();
};
// render Master yang tertunda karena sedang mengetik catatan
const _qdRefreshOpenViews = refreshOpenViews;
refreshOpenViews = function (force) {
  for (const v of ['masterDriver', 'masterUnit']) if (pendingViews.has(v) && !inEditing($('#v-' + v))) { pendingViews.delete(v); v === 'masterDriver' ? renderMasterDriver() : renderMasterUnit(); }
  _qdRefreshOpenViews(force);
};
document.addEventListener('focusout', () => setTimeout(() => refreshOpenViews(), 60));

/* ---------- halaman Upload: status sumber data (file upload tidak dipakai lagi) ---------- */
renderData = function () {
  const row = (n, title, ok, info, src) => `<div class="slot"><div class="slot-h"><span class="n">${n}</span>${title}</div><div class="${ok ? 'ok' : 'miss'}">${ok ? '✓ ' + info : info}</div><div class="fn">${src}</div></div>`;
  const nShip = (QD.ship && QD.ship.rows || []).length, t = todaySerial(), abs = QD.days.get(t) || [];
  $('#v-data').innerHTML = `<div class="page-h"><h2>Sumber Data</h2><p>Semua data diambil langsung dari database (tidak perlu upload file). Diperbarui otomatis tiap menit${QD.at ? '; terakhir ' + esc(fmtTs(QD.at)) : ''}.${QD.ok ? '' : ' <b style="color:var(--danger)">Gagal membaca: ' + esc(QD.err) + '</b>'}</p></div>
  <div class="panel"><h3>Data yang dipakai aplikasi</h3><div class="slots">
    ${row(1, 'Master Driver', S.masterDB.length > 0, S.masterDB.length + ' driver aktif', 'Quickapps mst_driver (project sesuai akses role) · aktif dari status driver')}
    ${row(2, 'Master Unit', S.unitDB.length > 0, S.unitDB.length + ' unit aktif', 'FMS fl.fl_mst_fleet_otm (aktif, Pool Nagrak Trailer) · Sub Status dari Quickapps MST_UNIT_TAHUN')}
    ${row(3, 'Kode Absensi & jam Absen MTA', abs.some(x => x.initial), abs.filter(x => x.initial).length + ' driver berkode absen hari ini', 'Quickapps trx_dms_absent (initial; jam = updated_at sampai data MTA tersedia)')}
    ${row(4, 'Shipment (pengganti DOTS & GPS Tracking)', nShip > 0, nShip + ' shipment, tender sejak ' + esc(QD.ship ? QD.ship.sejak : '-'), 'Quickapps trx_gps_tracking + trx_shipment_preparation')}
    ${row(5, 'Status P2H', S.p2hBy.size > 0, S.p2hBy.size + ' unit punya P2H', 'puninar_p2h check_records (status P2H terakhir, tanpa batas umur)')}
    ${row(6, 'Status Klinik', S.klinikBy.size > 0, S.klinikBy.size + ' driver punya status hari ini', 'Quickapps trx_dms_absent.status_klinik (absen hari ini; jam pemeriksaan belum tersedia)')}
    ${row(7, 'Status FMS BD', S.bdRows.length > 0, [...S.bdBy.values()].filter(a => a[0].status === 'BD').length + ' unit BD', 'FMS service order → receive → work order / hand over (SO 30 hari terakhir)')}
    ${row(8, 'SJ Outstanding', !QD.sjErr && QD.sj !== null, (QD.sj || []).length + ' SJ belum kembali (semua pool)', 'odongnew_db so.so_trx_document (tender mulai 01-Nov-2026)' + (QD.sjErr ? ' · gagal: ' + esc(QD.sjErr) : ''))}
    ${row(9, 'Penyimpanan aplikasi', MODE === 'server', MODE === 'server' ? 'Database QUEDRIVE' : 'Tidak terhubung (baca saja)', 'qd_antrian, qd_history, qd_setting, qd_catatan')}
  </div></div>`;
};

/* ---------- bar status kiri bawah ---------- */
renderShareBar = function () {
  const b = $('#shareBar');
  if (!DBREADY || !S.loaded.api) { b.className = 'sharebar loc'; b.textContent = 'Memuat data dari database…'; return; }
  if (!QD.ok) { b.className = 'sharebar ro'; b.innerHTML = `Data sumber tidak terbaca: ${esc(QD.err)}.` + (QD.at ? ` Menampilkan data ${esc(fmtTs(QD.at))}.` : ''); return; }
  if (MODE !== 'server') { b.className = 'sharebar ro'; b.innerHTML = '<b>Database QUEDRIVE tidak terhubung</b>: antrian dan catatan tidak bisa diubah. Muat ulang halaman setelah server berjalan.'; return; }
  b.className = 'sharebar srv'; b.textContent = `Data sumber diperbarui ${fmtTs(QD.at)}. Antrian & catatan tersimpan di database QUEDRIVE (dipakai bersama).`;
};

/* ---------- RAW Data & tanggal aplikasi: absensi tanggal lain dimuat dari database ---------- */
const _qdRenderRaw = renderRaw;
renderRaw = function () { _qdRenderRaw(); const d = RAWSEL.day; if (d != null && !QD.days.has(d)) qdEnsureDay(d).then(ok => { if (ok && curView === 'raw') _qdRenderRaw(); }); };
const _qdSetAsOf = setAsOf;
setAsOf = function (v) { _qdSetAsOf(v); qdLoad(); };

/* ---------- Asumsi: catatan sumber data versi database ---------- */
const _qdRenderAsumsi = renderAsumsi;
renderAsumsi = function () {
  _qdRenderAsumsi();
  const h = $('#v-asumsi .page-h');
  if (h) h.insertAdjacentHTML('beforeend', '<div class="alert-note">Di QUEDRIVE versi server, data tidak lagi diupload: semua sumber dibaca langsung dari database (lihat menu <b>Upload</b> → Sumber Data). Aturan hitung di halaman ini tetap berlaku; penjelasan sumber data per file akan disesuaikan.</div>');
};

/* ---------- tampilan: sembunyikan kontrol upload master & filter aktif/nonaktif (master hanya data aktif dari database) ---------- */
document.head.insertAdjacentHTML('beforeend', `<style>
  #btnTplDriver,#btnTplUnit,label.btn:has(#fileMD),label.btn:has(#fileMU),#v-masterDriver tr.flt,#v-masterUnit tr.flt{display:none!important}
  /* baris filter disembunyikan -> judul kolom menempel di paling atas (tadinya top:34px sehingga ada celah berisi baris data saat digulir) */
  #v-masterDriver table.t thead tr.flt + tr th,#v-masterUnit table.t thead tr.flt + tr th{top:0}
  .toolbar.mtool{display:flex;gap:8px;align-items:center}
  .toolbar.mtool input[type=search]{flex:1;min-width:0}
  .pill.sh{border:1px solid;font-weight:700}
  .pill.sh-cl{background:rgba(22,163,74,.16);color:#15803D;border-color:#16A34A}
  .pill.sh-rd{background:rgba(47,111,222,.16);color:#2459B8;border-color:#2F6FDE}
  .pill.sh-op{background:rgba(217,130,0,.18);color:#B45309;border-color:#D98200}
  :root[data-theme=dark] .pill.sh-cl{color:#4ADE80}:root[data-theme=dark] .pill.sh-rd{color:#8DB4FF}:root[data-theme=dark] .pill.sh-op{color:#FBBF24}
  @media (prefers-color-scheme: dark){:root:not([data-theme=light]) .pill.sh-cl{color:#4ADE80}:root:not([data-theme=light]) .pill.sh-rd{color:#8DB4FF}:root:not([data-theme=light]) .pill.sh-op{color:#FBBF24}}
  .qd-fbtn.on{border-color:var(--accent);color:var(--accent);background:var(--accent-bg)}
  #v-masterUnit .cell-in.note-in{min-width:110px;max-width:130px}
</style>`);
document.body.insertAdjacentHTML('beforeend', '<datalist id="qd-cat-drivers"></datalist><datalist id="qd-cat-units"></datalist>');

/* =====================================================================
   4. LIST DRIVER: desain versi PHP (permintaan user) dengan logika tampilan HTML user
      Kolom: No | ID Driver | Nama | Jam | Status | Aksi (+ tombol detail ▾); toolbar: cari, Semua/AVA/NOT AVA, Filter.
      Logika tetap: urutan X -> OR yang sudah AVA (jam closed) -> OR lain -> AIS/O -> N/A; + hijau untuk AVA,
      + abu-abu (dengan konfirmasi) untuk OR yang belum AVA dan N/A; catatan driver di bawah nama.
   ===================================================================== */
document.head.insertAdjacentHTML('beforeend', `<style>
  .act2{display:inline-flex;align-items:center;gap:1px}
  .ibtn.dd svg.ic{transition:transform .15s}
  .ibtn.dd.open{color:var(--accent);background:var(--accent-bg)}
  .ibtn.dd.open svg.ic{transform:rotate(180deg)}
  table.t.fit.lt tr.dd-open td{border-bottom-color:transparent;background:var(--accent-bg)}
  table.t.fit.lt tr.dd-row td{white-space:normal;text-align:left;padding:2px 8px 8px 30px;background:var(--accent-bg)}
  .dd-list{display:flex;flex-direction:column;gap:4px;background:var(--surface);border:1px solid var(--border);border-radius:8px;padding:7px 9px}
  .dd-it{display:grid;grid-template-columns:14px 82px minmax(0,1fr);align-items:start;gap:6px;font-size:10.5px;line-height:18px}
  .dd-it input{margin:3px 0 0;width:12px;height:12px;accent-color:var(--accent2);pointer-events:none}
  .dd-k{font-weight:700;color:var(--muted)}
  .dd-v{display:flex;align-items:center;gap:0 5px;flex-wrap:wrap;min-width:0;min-height:18px}
  .dd-v .pill{line-height:14px}
  .dd-s{white-space:nowrap}
  .c-WR{background:var(--warn,#E0A100);color:#fff}
  .df-wrap{position:relative;flex-shrink:0}
  #btnDF.on{border-color:var(--accent);color:var(--accent);background:var(--accent-bg)}
  #fltStatusLD{padding:6px 8px;font-weight:600}
  .df-n{min-width:16px;padding:0 5px;border-radius:999px;background:var(--accent);color:#fff;font-size:10px;line-height:16px;text-align:center}
  .df-n[hidden],.df-panel[hidden]{display:none}
  .df-panel{position:absolute;z-index:40;top:calc(100% + 6px);right:0;width:min(300px,86vw);background:var(--surface);border:1px solid var(--border);border-radius:10px;box-shadow:0 12px 32px rgba(0,0,0,.18);padding:10px 12px}
  .df-h{display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;font-size:12.5px}
  .df-h .modal-x{position:static;width:26px;height:26px;font-size:16px}
  .df-g{border-top:1px solid var(--border);padding:7px 0 8px}
  .df-l{font-size:10.5px;font-weight:700;color:var(--muted);letter-spacing:.03em;text-transform:uppercase;margin-bottom:5px}
  .df-opts{display:flex;flex-wrap:wrap;gap:6px}
  .df-o{display:inline-flex;align-items:center;gap:5px;padding:3px 9px;border:1px solid var(--border);border-radius:999px;font-size:11.5px;cursor:pointer;background:var(--surface2)}
  .df-o:has(input:checked){border-color:var(--accent);background:var(--accent-bg);color:var(--accent);font-weight:600}
  .df-o input{margin:0;accent-color:var(--accent)}
  .df-act{display:flex;justify-content:flex-end;gap:6px;border-top:1px solid var(--border);padding-top:8px}
</style>`);
(function qdListDriverPanel() {
  const panel = $('#tbDriver').closest('.panel');
  panel.innerHTML = `<h3><svg class="ic" viewBox="0 0 24 24"><circle cx="12" cy="7" r="3.4"/><path d="M6.8 21.5v-5.6c0-2.6 2.3-4.6 5.2-4.6s5.2 2 5.2 4.6v5.6"/></svg><span class="tt" title="AVA = Kode Absensi X (Pool) dan shipment terakhir sudah closed / forceclosed (atau tidak ada shipment 15 hari terakhir). Urutan: AVA di atas, lalu X yang shipment terakhirnya belum closed, OR, Off, Belum absen; absen tertua di atas. AVA langsung bisa dimasukkan ke antrian (tombol + hijau); X yang belum closed, OR, dan Belum absen bertanda + abu-abu dan butuh konfirmasi (status AVA otomatis tidak berubah). AIS tidak ditampilkan, cukup dihitung di kartu atas."><span>List Driver</span><i class="tti" aria-hidden="true">i</i></span><span class="hd-info" id="ldInfo"></span><span class="grow"><span class="badge-n" id="badge-total-absen">0 Driver</span></span></h3>
    <p class="desc">Driver yang sudah absen hari ini (AVA) di atas dan bisa dimasukkan ke antrian.</p>
    <div class="alert-note" id="attWarn" hidden></div>
    <div class="toolbar"><input type="search" id="searchDriver" placeholder="Cari ID, Nama, atau Catatan..." aria-label="Cari driver"><select id="fltStatusLD" aria-label="Filter status driver"><option value="all">Semua</option><option value="AVA">AVA</option><option value="NOT AVA">NOT AVA</option></select><div class="df-wrap">
      <button class="btn" id="btnDF" type="button" aria-haspopup="dialog" aria-expanded="false" aria-controls="dfPanel"><svg class="ic" viewBox="0 0 24 24"><path d="M3 5h18l-7 8v6l-4 2v-8z"/></svg>Filter<span class="df-n" id="dfCount" hidden>0</span></button>
      <div class="df-panel" id="dfPanel" role="dialog" aria-label="Filter List Driver" hidden><div class="df-h"><b>Filter List Driver</b><button class="modal-x" id="dfClose" type="button" aria-label="Tutup">&times;</button></div><div id="dfBody"></div><div class="df-act"><button class="btn" id="dfReset" type="button">Reset</button><button class="btn primary" id="dfDone" type="button">Terapkan</button></div></div>
    </div></div>
    <div class="tbl-wrap nox">
      <table class="t fit lt">
        <colgroup><col style="width:22px"><col style="width:58px"><col><col style="width:38px"><col style="width:54px"><col style="width:50px"></colgroup>
        <thead><tr><th>No</th><th class="l">ID Driver</th><th class="l">Nama</th><th>Jam</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody id="tbDriver"></tbody>
      </table>
    </div>`;
})();
// filter (pop up): pilihan kosong dalam satu kelompok = semua
const QD_DF_FIELDS = [
  ['proj', 'Project', [['Exim Trailer', 'Exim Trailer'], ['LOBP', 'LOBP'], ['ESA', 'ESA']], d => qdProject((QD.drvById.get(d.id_driver) || {}).project)],
  ['drv', 'Tipe Driver', [['DM', 'DM'], ['DOS', 'DOS']], d => qdTipe(d.id_driver)],
  ['kode', 'Kode Absensi', [['X', 'Pool (X)'], ['OR', 'On Road (OR)'], ['O', 'Off'], ['N/A', 'Belum absen']], d => d.kode],
  ['ship', 'Last Shipment', [['Ready', 'Ready'], ['On Progress', 'On Progress'], ['Closed', 'Closed'], ['-', 'Tidak ada']], d => (QD.drvById.get(d.id_driver) || {}).last_shipment || '-']
];
const QD_DF = Object.fromEntries(QD_DF_FIELDS.map(f => [f[0], new Set()]));
let QD_DF_STATUS = 'all';
const qdDfOk = d => (QD_DF_STATUS === 'all' || d.status_lokasi === QD_DF_STATUS) && QD_DF_FIELDS.every(([k, , , get]) => !QD_DF[k].size || QD_DF[k].has(get(d)));
const qdDfActive = () => QD_DF_FIELDS.reduce((n, [k]) => n + QD_DF[k].size, 0);
function qdRenderDF() {
  $('#dfBody').innerHTML = QD_DF_FIELDS.map(([k, l, opts]) => `<div class="df-g" role="group" aria-label="${l}"><div class="df-l">${l}</div><div class="df-opts">` +
    opts.map(([v, t]) => `<label class="df-o"><input type="checkbox" data-k="${k}" value="${esc(v)}"${QD_DF[k].has(v) ? ' checked' : ''}>${esc(t)}</label>`).join('') + '</div></div>').join('');
  const n = qdDfActive(); $('#dfCount').textContent = n; $('#dfCount').hidden = !n; $('#btnDF').classList.toggle('on', n > 0);
}
function qdToggleDF(open) { const p = $('#dfPanel'); if (open == null) open = p.hidden; p.hidden = !open; $('#btnDF').setAttribute('aria-expanded', open); }
const QD_DD_OPEN = new Set();
const QD_IC_DD = '<svg class="ic" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>';
const qdApiDT = s => { const k = qdIsoKey(s); return k == null ? '-' : fmtDM(Math.floor(k / 1440)) + ' ' + pad(Math.floor((k % 1440) / 60)) + ':' + pad(k % 60); };
const qdShipPill2 = qdShipTag;
// teks alasan dari tampilan user menyebut file upload; di versi database sumbernya Quickapps
const qdWhyTxt = s => String(s || '').replace(/dari file Attendance/g, 'dari absensi Quickapps').replace(/dari Monitoring Attendance/g, '(jam update absensi Quickapps)').replace(/di GPS Tracking/g, 'di data shipment Quickapps');
// label Kode Absensi seperti versi PHP: X = Pool, OR = On Road, O = Off, A = Tidak Hadir, I = Izin, S = Sakit
const QD_TIPE = { X: ['Pool', 'c-OR'], OR: ['On Road', 'c-AIS'], O: ['Off', 'c-O'], A: ['Tidak Hadir', 'c-NG'], I: ['Izin', 'c-WR'], S: ['Sakit', 'c-WR'] };
function qdKodePill(d) {
  const raw = String(d.absensi_raw || '').toUpperCase(), key = raw === 'ADJ' ? d.kode : (QD_TIPE[raw] ? raw : d.kode);
  const t = QD_TIPE[key];
  return t ? `<span class="pill ${t[1]}" title="${esc(qdWhyTxt(d.kode_why))}">${t[0]}${raw === 'ADJ' ? ' (ADJ)' : ''}</span>` : '<span class="note">Belum absen</span>';
}
// detail ▾: hanya Kode Absensi dan Last Shipment (penjelasan hanya untuk Last Shipment)
function qdDriverDetail(d) {
  const x = QD.drvById.get(d.id_driver) || {};
  const it = (ok, k, v) => `<div class="dd-it"><input type="checkbox" tabindex="-1" aria-hidden="true"${ok ? ' checked' : ''}><span class="dd-k">${k}</span><span class="dd-v">${v}</span></div>`;
  const loc = x.last_shipment
    ? `${qdShipPill2(x.last_shipment)}<span class="note"><span class="dd-s">${esc(x.shipment_number || '-')}</span> · <span class="dd-s">${esc(x.nopol || '-')}</span> · <span class="dd-s">tender ${qdApiDT(x.tender_date)}</span>${x.closed_date ? ` · <span class="dd-s">closed ${qdApiDT(x.closed_date)}</span>` : ''}</span>`
    : '<span class="note">Tidak ada shipment 7 hari terakhir</span>';
  return `<div class="dd-list">${it(d.kode === 'X', 'Kode Absensi', qdKodePill(d))}${it(x.last_shipment === 'Closed', 'Last Shipment', loc)}</div>`;
}
renderListDriver = function () {
  const q = $('#searchDriver').value.trim().toLowerCase(), t = todaySerial();
  const inQueue = new Set(antriAktif().map(a => a.id));
  // AIS (alpha / izin / sakit) tidak ditampilkan di List Driver: cukup dihitung di kartu atas (Kode Absensi, AIS + OFF)
  const all = driverListToday().filter(d => d.kode !== 'AIS' && !inQueue.has(d.id_driver)), nAva = all.filter(d => d.status_lokasi === 'AVA').length;
  // hitungan di sebelah judul (seperti HTML user): seluruh List Driver, tidak terpengaruh cari / filter
  $('#ldInfo').innerHTML = `<span class="pill c-OR" title="Driver yang bisa langsung masuk antrian">AVA</span> <b>${nAva}</b> · <span class="pill c-O" title="Belum bisa langsung masuk antrian">NOT AVA</span> <b>${all.length - nAva}</b>`;
  const rows = all.filter(d => (!q || d.id_driver.toLowerCase().includes(q) || d.nama.toLowerCase().includes(q) || noteDriver(d.id_driver, t).toLowerCase().includes(q)) && qdDfOk(d));
  // urutan: AVA (X + shipment terakhir closed) di atas, lalu X belum closed, OR, Off, N/A; dalam kelompok absen tertua di atas
  const grp = d => (d.status_lokasi === 'AVA' ? 0 : ({ X: 1, OR: 2, O: 3 })[d.kode] ?? 4);
  const byTime = (x, y) => (x && y ? (x < y ? -1 : x > y ? 1 : 0) : x ? -1 : y ? 1 : 0);
  rows.sort((a, b) => (grp(a) - grp(b)) || byTime(a.jam_absen, b.jam_absen) || (a.id_driver < b.id_driver ? -1 : 1));
  $('#badge-total-absen').textContent = rows.length + ' Driver';
  const cov = attCoverage(), w = $('#attWarn'); if (w) { w.hidden = cov.ok; w.innerHTML = cov.ok ? '' : cov.msg; }
  $('#tbDriver').innerHTML = rows.length ? rows.map((d, i) => {
    const ava = d.status_lokasi === 'AVA', open = QD_DD_OPEN.has(d.id_driver), warn = !ava && QD_MANUAL_KODE.includes(d.kode);
    const act = ava ? `<button class="ibtn add" data-act="add" data-id="${esc(d.id_driver)}" title="Masukan ke antrian" aria-label="Masukan ke antrian">${IC_ADD}</button>`
      : warn ? `<button class="ibtn add muted" data-act="add" data-warn="1" data-id="${esc(d.id_driver)}" title="${esc('Belum AVA: ' + qdWhyTxt(d.why) + '. Masih bisa masuk antrian manual dengan konfirmasi')}" aria-label="Masukan ke antrian (ada konfirmasi)">${IC_ADD}</button>`
      : `<span class="na-ic" title="${esc(qdWhyTxt(d.why))}">${IC_BAN}</span>`;
    const dd = `<button class="ibtn dd${open ? ' open' : ''}" data-act="dd" data-id="${esc(d.id_driver)}" title="Detail absen & shipment" aria-label="Detail absen" aria-expanded="${open}">${QD_IC_DD}</button>`;
    return `<tr${open ? ' class="dd-open"' : ''}><td class="num">${i + 1}</td><td class="l">${esc(d.id_driver)}</td><td class="l nm" title="${esc(d.nama)}">${esc(d.nama)}${noteHtml(noteDriver(d.id_driver, t))}</td>` +
      `<td class="mono" title="${esc(qdWhyTxt(d.kode_why))}">${d.jam_absen ? esc(d.jam_absen) : '-'}</td><td><span class="pill ${ava ? 'c-OR' : 'c-O'}" title="${esc(qdWhyTxt(d.why))}">${esc(d.status_lokasi)}</span></td><td><span class="act2">${act}${dd}</span></td></tr>` +
      (open ? `<tr class="dd-row"><td colspan="6">${qdDriverDetail(d)}</td></tr>` : '');
  }).join('') : `<tr><td colspan="6" class="empty-row">${!S.loaded.api ? 'Memuat data driver dari database…' : !QD.ok && !S.masterDB.length ? 'Data driver tidak bisa dimuat: ' + esc(QD.err) : qdDfActive() || QD_DF_STATUS !== 'all' || q ? 'Tidak ada driver yang cocok dengan filter' : 'Tidak ada driver'}</td></tr>`;
};
// event panel List Driver (elemen baru, jadi event dipasang ulang di sini)
$('#searchDriver').addEventListener('input', renderListDriver);
$('#fltStatusLD').addEventListener('change', e => { QD_DF_STATUS = e.target.value; renderListDriver(); });
$('#btnDF').addEventListener('click', () => qdToggleDF());
$('#dfClose').addEventListener('click', () => qdToggleDF(false));
$('#dfDone').addEventListener('click', () => qdToggleDF(false));
$('#dfReset').addEventListener('click', () => { Object.values(QD_DF).forEach(x => x.clear()); qdRenderDF(); renderListDriver(); });
$('#dfBody').addEventListener('change', e => { const c = e.target; if (!c.dataset.k) return; QD_DF[c.dataset.k][c.checked ? 'add' : 'delete'](c.value); qdRenderDF(); renderListDriver(); });
document.addEventListener('click', e => { if (!$('#dfPanel').hidden && !e.target.closest('.df-wrap')) qdToggleDF(false); });
document.addEventListener('keydown', e => { if (e.key === 'Escape' && !$('#dfPanel').hidden) { qdToggleDF(false); $('#btnDF').focus(); } });
$('#tbDriver').addEventListener('click', e => {
  const dd = e.target.closest('[data-act="dd"]');
  if (dd) { const id = dd.dataset.id; QD_DD_OPEN.has(id) ? QD_DD_OPEN.delete(id) : QD_DD_OPEN.add(id); renderListDriver(); return; }
  const b = e.target.closest('[data-act="add"]'); if (b) { if (b.dataset.warn) addAskNotAva(b.dataset.id); else addToAntrian(b.dataset.id); }
});
qdRenderDF();
