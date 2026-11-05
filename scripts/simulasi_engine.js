/* =====================================================================
   SIMULASI QUEDRIVE (disisipkan oleh scripts/buat_simulasi.php)
   - Data hari ini di-hardcode (snapshot database sumber); /api/* dijawab dari data ini.
   - Seperti HTML awal: kejadian acak berjalan sendiri tiap 3–8 detik (absen, order driver di antrian, pairing driver
     yang sudah absen, shipment jalan & selesai, cek klinik), tampil sebagai notifikasi, lalu dashboard dihitung ulang.
   ===================================================================== */
const SIM_DATA = /*DATA*/null;
const simPad = n => String(n).padStart(2, '0');
const simYmd = d => d.getFullYear() + '-' + simPad(d.getMonth() + 1) + '-' + simPad(d.getDate());
const simNow = () => { const d = new Date(); return simYmd(d) + 'T' + simPad(d.getHours()) + ':' + simPad(d.getMinutes()); };
const simPick = a => a[Math.floor(Math.random() * a.length)];
const SIM_RUN = ['Ready', 'On Progress'];

// tanggal snapshot digeser ke hari ini, agar aturan "hari ini" tetap berlaku bila file dibuka di hari lain
const SIM = (() => {
  const s = JSON.parse(JSON.stringify(SIM_DATA));
  const off = Math.round((Date.parse(simYmd(new Date())) - Date.parse(s.drivers.tanggal)) / 864e5);
  const geser = v => { if (!off || typeof v !== 'string' || !/^\d{4}-\d\d-\d\d/.test(v)) return v; const d = new Date(v.slice(0, 10) + 'T00:00:00'); d.setDate(d.getDate() + off); return simYmd(d) + v.slice(10); };
  s.drivers.tanggal = geser(s.drivers.tanggal);
  s.drivers.drivers.forEach(d => ['tender_date', 'closed_date', 'last_unloading_at'].forEach(k => { d[k] = geser(d[k]); }));
  s.units.forEach(u => { u.p2h_at = geser(u.p2h_at); });
  s.sj.forEach(x => { x.tender_date = geser(x.tender_date); });
  if (s.spk) s.spk.tanggal = geser(s.spk.tanggal);
  s.no = 0;
  return s;
})();
const simSnapLabel = () => SIM_DATA.diambil.slice(8, 10) + '/' + SIM_DATA.diambil.slice(5, 7) + '/' + SIM_DATA.diambil.slice(0, 4) + ' ' + SIM_DATA.diambil.slice(11, 16);

// pengganti backend: jawaban /api/* dari data simulasi
async function simApi(path) {
  const p = path.split('?')[0], c = o => JSON.parse(JSON.stringify(o));
  if (p === '/api/drivers') return c({ ...SIM.drivers, jumlah: SIM.drivers.drivers.length });
  if (p === '/api/units') return c(SIM.units);
  if (p === '/api/depo') return c(SIM.depo);
  if (p === '/api/spk-spo') return c(SIM.spk);
  if (p === '/api/sj') return c(SIM.sj);
  throw new Error('tidak tersedia di simulasi');
}

// ---- kejadian ----
const simDrv = () => SIM.drivers.drivers;
const simAva = d => { d.status = d.initial === 'X' && (d.last_shipment === 'Closed' || d.last_shipment == null) ? 'AVA' : 'NOT AVA'; };
// driver dipairing ke shipment baru (Ready, tender sekarang) dengan unit yang sedang tidak jalan
function simPair(d) {
  const dipakai = new Set(simDrv().filter(x => SIM_RUN.includes(x.last_shipment)).map(x => normPlat(x.nopol)));
  const u = simPick(SIM.units.filter(x => !dipakai.has(normPlat(x.nopol)))); if (!u) return null;
  const ship = 'SIM' + simYmd(new Date()).replace(/-/g, '') + '-' + String(++SIM.no).padStart(4, '0');
  Object.assign(d, { last_shipment: 'Ready', shipment_number: ship, tender_date: simNow(), closed_date: null, nopol: u.nopol, tipe_unit: u.tipe_unit, tracking_nopol: u.nopol, nopol_driver_lain: null, sim: true });
  simAva(d);
  if (SIM.spk) { const k = grpKey(truckGroup(u.tipe_unit)); SIM.spk.SPK.n++; SIM.spk.SPK[k]++; }
  return `${d.nama} dipairing ke ${ship} · ${u.nopol}`;
}
const SIM_EVENTS = [
  { w: 4, run() {   // driver absen (seperti simulasi PEVO di HTML awal)
    const c = simDrv().filter(d => !d.initial); if (!c.length) return null;
    const d = simPick(c); d.initial = Math.random() < 0.7 ? 'X' : 'OR'; d.tipe_absen = d.initial === 'X' ? 'Pool' : 'On Road';
    d.jam = simNow().slice(11); simAva(d);
    return `Absen: ${d.nama} · ${d.tipe_absen}${d.status === 'AVA' ? ' · AVA' : ''}`;
  } },
  { w: 4, run() {   // driver di antrian (≥ 20 detik) dapat order -> Status Order SUDAH, nopol terisi otomatis
    const q = antrian().filter(a => a.seq && Date.now() - a.seq > 20000 && !orderOf(a)); if (!q.length) return null;
    const d = simDrv().find(x => x.id === simPick(q).id); if (!d) return null;
    const m = simPair(d); return m && 'Order: ' + m;
  } },
  { w: 3, run() {   // driver yang sudah absen (tidak di antrian) dipairing ke shipment -> NOT AVA, On Road bertambah
    const inQ = new Set(antrian().map(a => a.id));
    const c = simDrv().filter(d => d.initial && !inQ.has(d.id) && !SIM_RUN.includes(d.last_shipment)); if (!c.length) return null;
    const m = simPair(simPick(c)); return m && 'Pairing: ' + m;
  } },
  { w: 2, run() {   // shipment mulai jalan
    const c = simDrv().filter(d => d.last_shipment === 'Ready'); if (!c.length) return null;
    const d = simPick(c); d.last_shipment = 'On Progress';
    return `Berangkat: ${d.nama} · ${d.shipment_number} On Progress`;
  } },
  { w: 2, run() {   // shipment selesai (hanya shipment dari data asli, agar order simulasi tetap SUDAH)
    const c = simDrv().filter(d => d.last_shipment === 'On Progress' && !d.sim); if (!c.length) return null;
    const d = simPick(c); Object.assign(d, { last_shipment: 'Closed', closed_date: simNow(), tracking_nopol: null }); simAva(d);
    return `Selesai: ${d.nama} · ${d.shipment_number} Closed${d.status === 'AVA' ? ' · sekarang AVA' : ''}`;
  } },
  { w: 3, run() {   // hasil cek klinik masuk
    const c = simDrv().filter(d => d.initial && !d.klinik); if (!c.length) return null;
    const d = simPick(c); d.klinik = Math.random() < 0.75 ? 'FIT' : 'UNFIT';
    return `Cek klinik: ${d.nama} · ${d.klinik}`;
  } },
];
function simStep() {
  const pool = SIM_EVENTS.slice();
  while (pool.length) {
    let r = Math.random() * pool.reduce((n, e) => n + e.w, 0), i = 0;
    while ((r -= pool[i].w) > 0) i++;
    const msg = pool[i].run();
    if (msg) { toast('[SIMULASI] ' + msg); loadApi(); return; }
    pool.splice(i, 1);   // kejadian ini belum mungkin terjadi: coba yang lain
  }
}
// kejadian berjalan sendiri dengan jeda acak 3–8 detik
function simLoop() { setTimeout(() => { simStep(); simLoop(); }, 3000 + Math.random() * 5000); }
window.addEventListener('load', simLoop);
