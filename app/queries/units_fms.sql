-- Master Unit / pilihan Nopol di List Antrian.
-- Sumber: FMS (PostgreSQL) / fl.fl_mst_fleet_otm ; unit aktif (active_flag) pada pool yang boleh diakses (ownership_name).
-- Rantai data: aset -> OTM -> FMS (transkrip: "ngikutin Asep"). Tipe unit dari aset (unit_type_du), kosong bila belum diisi.
-- :pools = daftar pool dipisah koma, mis. 'POOL NAGRAK TRAILER'. Satu baris per nopol (data terbaru).
SELECT DISTINCT ON (o.power_unit_num)
       o.power_unit_num       AS nopol,
       o.unit_type_du         AS tipe_unit,
       o.ownership_name       AS pool,
       o.availability_status  AS status_fms
FROM fl.fl_mst_fleet_otm o
WHERE o.active_flag
  AND o.ownership_name = ANY (string_to_array(:pools, ','))
ORDER BY o.power_unit_num, o.updated_date DESC NULLS LAST
