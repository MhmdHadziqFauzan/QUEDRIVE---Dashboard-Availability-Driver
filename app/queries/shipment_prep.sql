-- Detail shipment (tipe SPK / SPO, nomor order, rute, tanggal eksekusi, status, cancel) untuk shipment dari shipments_gps.
-- Sumber: Quickapps / db_utilisasi / trx_shipment_preparation. Daftar shipment disisipkan di PHP (IN (...)).
SELECT shipment_id,
       shipment_type,
       order_number,
       customer_name,
       loading,
       unloading,
       tender_date,
       date_execution,
       status,
       cancel_date
FROM trx_shipment_preparation
WHERE shipment_id IN (%s)
