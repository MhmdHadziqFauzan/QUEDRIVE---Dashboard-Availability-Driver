-- Kartu Shipment SPK / SPO di Dashboard Antrian.
-- Sumber: DB1 / db_utilisasi / trx_shipment_preparation ; shipment_id, shipment_type (SPK / SPO), tender_date, date_execution
-- Diambil yang tender atau eksekusinya dekat tanggal yang diminta; tanggal onroad dihitung di PHP.
-- Hanya pool yang boleh diakses (ownership_shipment, mis. POOL NAGRAK TRAILER).
SELECT shipment_id,
       shipment_type,
       status,
       tender_date,
       date_execution,
       cancel_date
FROM trx_shipment_preparation
WHERE FIND_IN_SET(ownership_shipment, :pools)   -- pool sesuai akses role user (dipisah koma)
  AND tender_date >= :batas                       -- batas bawah ber-index (31 hari) agar tidak membaca seluruh tabel
  AND (tender_date >= :sejak
       OR date_execution >= :sejak2)
