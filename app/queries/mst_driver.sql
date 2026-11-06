-- Daftar driver (patokan List Driver & Master Driver): hanya project yang boleh diakses, awalan DM- dan DOS-.
-- Sumber: DB1 / db_utilisasi / mst_driver ; dm_number = driver_id di trx_dms_absent
-- Aktif/nonaktif dari DB2 (puninar_dms, is_active), disaring di PHP; status di sini hanya cadangan bila DB2 gagal
-- (ditulis tidak seragam: active / Inactive / inactive).
SELECT dm_number,
       name,
       project_name,
       LOWER(status) AS status
FROM mst_driver
WHERE FIND_IN_SET(project_name, :projects)   -- daftar project dipisah koma, mis. 'Exim Trailler,LOBP,ESA'
  AND (dm_number LIKE 'DM-%' OR dm_number LIKE 'DOS-%')
ORDER BY dm_number
