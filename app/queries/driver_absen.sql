-- List Driver & Master Driver: absensi semua driver pada satu tanggal.
-- Sumber: DB1 / db_utilisasi / trx_dms_absent
-- Setiap driver sudah punya 1 baris per hari sejak ±00:04 (initial kosong); updated_at berubah saat driver absen.
SELECT driver_id,
       driver_name,
       initial,
       status_klinik,   -- FIT / UNFIT / NULL (belum ada data klinik)
       updated_at
FROM trx_dms_absent
WHERE period_absent = :tanggal
  -- AND driver_id LIKE 'DM-%'   -- filter awalan DM- dinonaktifkan sementara (30-Oct-2026): ambil semua driver
ORDER BY driver_id;
