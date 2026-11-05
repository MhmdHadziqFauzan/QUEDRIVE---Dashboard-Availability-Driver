-- DB2 puninar_dms (PostgreSQL): view karir/utilisasi driver, schema public
-- :aktif = 'true' / 'false' / NULL (semua) -> kolom is_active (TRUE = driver aktif)
-- Kolom dipilih satu per satu: nomor HP (phone) sengaja tidak dikirim lewat API.
SELECT user_id, nik, name, driver_type, user_position, project_name, is_active,
       active_career_transition, category_name, type_name, start_date, end_date, date_inactive
FROM public.v_driver_career_utilisasi
WHERE is_active IS NOT DISTINCT FROM COALESCE(CAST(:aktif AS boolean), is_active)
ORDER BY nik, user_id
LIMIT :lim OFFSET :off
