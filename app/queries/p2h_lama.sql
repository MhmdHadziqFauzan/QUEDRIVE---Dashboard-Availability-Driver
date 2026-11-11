-- Status P2H terakhir (seluruh riwayat) untuk unit Master Unit yang TIDAK punya P2H dalam 30 hari terakhir (p2h_terakhir).
-- Sumber: DB3 / puninar_p2h / public.check_records. vehicle_license tidak ber-index, jadi query ini membaca seluruh tabel (±5 detik):
-- dipanggil hanya untuk sisa nopol tersebut dan hasilnya di-cache 24 jam di PHP (status yang lebih tua dari 30 hari tidak
-- berubah; P2H baru selalu tertangkap p2h_terakhir).
-- :plates = nopol tanpa tanda baca dipisah koma, mis. 'B1234ABC,L5678XY'
SELECT DISTINCT ON (UPPER(REGEXP_REPLACE(vehicle_license, '[^A-Za-z0-9]', '', 'g')))
       vehicle_license,
       status,
       created_at
FROM public.check_records
WHERE UPPER(REGEXP_REPLACE(vehicle_license, '[^A-Za-z0-9]', '', 'g')) = ANY (string_to_array(CAST(:plates AS text), ','))
ORDER BY UPPER(REGEXP_REPLACE(vehicle_license, '[^A-Za-z0-9]', '', 'g')), created_at DESC
