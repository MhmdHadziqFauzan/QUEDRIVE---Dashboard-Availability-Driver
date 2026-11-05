-- Pilihan Depo di List Antrian.
-- Sumber: DB1 / db_utilisasi / mst_depo ; geo_code (kode depo, unik)
SELECT geo_code
FROM mst_depo
WHERE geo_code IS NOT NULL
  AND TRIM(geo_code) <> ''
ORDER BY geo_code
