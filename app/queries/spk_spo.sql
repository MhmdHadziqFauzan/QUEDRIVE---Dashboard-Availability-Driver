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
WHERE ownership_shipment = :pool
  AND (tender_date >= :sejak
       OR date_execution >= :sejak2)
