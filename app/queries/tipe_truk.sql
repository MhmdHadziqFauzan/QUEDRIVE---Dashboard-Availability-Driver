-- Tipe truk & driver per shipment (kartu SPK / SPO).
-- Sumber: DB1 / db_utilisasi / trx_gps_tracking ; equipment_group, primary_driver_num, secondary_driver_num.
-- Daftar shipment disisipkan di PHP (IN (...)). Shipment hanya dihitung bila dibawa driver dalam scope project.
SELECT DISTINCT shipment_number,
       equipment_group,
       primary_driver_num,
       secondary_driver_num
FROM trx_gps_tracking
WHERE shipment_number IN (%s)
