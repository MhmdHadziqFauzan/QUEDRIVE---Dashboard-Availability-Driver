-- Tipe truk per shipment (rincian 20 FT / 40 FT kartu SPK / SPO).
-- Sumber: DB1 / db_utilisasi / trx_gps_tracking ; equipment_group. Daftar shipment disisipkan di PHP (IN (...)).
SELECT shipment_number,
       MAX(equipment_group) AS equipment_group
FROM trx_gps_tracking
WHERE shipment_number IN (%s)
GROUP BY shipment_number
