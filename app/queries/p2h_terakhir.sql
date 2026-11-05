-- Status P2H per unit: pemeriksaan terbaru (created_at) per nopol.
-- Sumber: DB3 / puninar_p2h / public.check_records ; vehicle_license, status (READY / BREAKDOWN / PREVENTIVE), created_at
-- Dibatasi created_at (ber-index) agar ringan.
SELECT DISTINCT ON (vehicle_license)
       vehicle_license,
       status,
       created_at
FROM public.check_records
WHERE created_at >= CAST(:sejak AS timestamp)
  AND vehicle_license IS NOT NULL
ORDER BY vehicle_license, created_at DESC
