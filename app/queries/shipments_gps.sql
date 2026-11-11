-- Shipment untuk dashboard (pengganti file DOTS Report + GPS Tracking Report di tampilan HTML user).
-- Sumber: Quickapps / db_utilisasi / trx_gps_tracking (1 baris per stop). Dikelompokkan per shipment di PHP (build_shipments).
-- Disaring di PHP: milik pool yang boleh diakses (ownership_shipment) ATAU dibawa driver project yang boleh diakses
-- (primary / secondary), sehingga driver Exim yang membawa shipment pool lain (mis. Cilegon) ikut.
SELECT g.shipment_number,
       g.license_number,
       g.equipment_group,
       g.ownership_shipment,
       g.customer_name,
       g.primary_driver_num,
       g.secondary_driver_num,
       g.tender_date,
       g.shipment_status,
       g.closed_date,
       g.final_ata_pta,
       g.final_atd_ptd
FROM trx_gps_tracking g
WHERE g.tender_date >= :sejak
  AND g.tender_date < :sampai   -- ber-index (idx_tender); saring pool / driver dilakukan di PHP agar index tetap terpakai
