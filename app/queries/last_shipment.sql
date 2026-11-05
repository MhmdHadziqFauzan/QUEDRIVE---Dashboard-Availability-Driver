-- Last Shipment: shipment driver dalam beberapa hari terakhir (dikelompokkan per shipment di PHP).
-- Sumber: DB1 / db_utilisasi / trx_gps_tracking  (1 baris per stop, bukan per shipment)
-- Dibatasi tender_date (ber-index) agar ringan; cancel diabaikan. Driver = primary_driver_num dan secondary_driver_num.
-- Juga dipakai Last Unloading: stop_type = 'D' terbaru (final_ata_pta) -> location_name.
SELECT shipment_number,
       primary_driver_num,
       secondary_driver_num,
       shipment_status,
       tender_date,
       closed_date,
       license_number,
       equipment_group,
       customer_name,
       stop_type,
       location_name,
       final_ata_pta
FROM trx_gps_tracking
WHERE tender_date >= :sejak
  AND shipment_status <> 'cancel';
