-- Master Unit / pilihan Nopol di List Antrian.
-- Sumber: DB1 / db_utilisasi / mst_lookup_lines { lookup_code_header = MST_UNIT_TAHUN && status = ACTIVE }
SELECT name         AS nopol,
       attribute_v3 AS tipe_unit,
       attribute_v4 AS pool,
       attribute_v2 AS customer
FROM mst_lookup_lines
WHERE lookup_code_header = 'MST_UNIT_TAHUN'
  AND status = 'ACTIVE'
ORDER BY name;
