-- DB2 puninar_dms (PostgreSQL): Status Driver Master Driver. nik = driver_id di DB1 (DM-/CD-/DOS-)
-- is_active TRUE = Aktif; nik yang muncul lebih dari sekali digabung (aktif bila salah satunya aktif)
SELECT nik, bool_or(is_active) AS is_active
FROM public.v_driver_career_utilisasi
WHERE nik IS NOT NULL
GROUP BY nik
