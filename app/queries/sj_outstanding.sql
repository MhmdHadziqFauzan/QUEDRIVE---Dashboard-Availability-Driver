-- SJ Outstanding (Surat Jalan belum kembali).
-- Sumber: DB4 / odongnew_db / so.so_trx_document
-- Belum kembali = perjalanan selesai (TRIP_ENDED) tetapi dokumen belum diterima admin mana pun (last_status_id kosong,
-- tidak ada riwayat di so_trx_document_history). Baris yang dihapus diabaikan.
SELECT shipment_number,
       driver_id,
       customer_name,
       tender_date,
       power_unit
FROM so.so_trx_document
WHERE deleted_at IS NULL
  AND trip_status = 'TRIP_ENDED'
  AND last_status_id IS NULL
