-- Status FMS BD (data breakdown bengkel) - query dari user, rentang tanggal jadi parameter.
-- Sumber: FMS (PostgreSQL) / ws.ws_th_service_order -> ws_th_receive -> ws_th_work_order / ws_th_hand_over, aset fl.fl_mst_fleet_all.
-- Unit dikatakan breakdown bila SO-nya belum punya handover_time (handover_time = null). Aturan per nopol dihitung di PHP (fms_bd).
-- Rentang: SO yang punya aktivitas (SO, receive, mulai/selesai WO, hand over, hapus) di antara :mulai dan :sampai.
WITH p AS (SELECT CAST(:mulai AS timestamp) AS mulai, CAST(:sampai AS timestamp) AS sampai)
SELECT DISTINCT ON (a.id, c.wo_number)
  e.license_number,
  a.id,
  CASE
    WHEN a.plan_prev_main_detail_id IS NULL THEN 'Other Services'
    ELSE 'Preventive Maintenance'
  END AS service_type,
  a.insert_time        AS service_order_date,
  b.insert_time        AS received_date,
  c.wo_number,
  c.start_time,
  c.complete_time,
  d.handover_time,
  a.delete_time,
  g.activity_repair_name,
  h.repair_brand_name  AS repair_group,
  i.description        AS rootcause_desc
FROM ws.ws_th_service_order a
CROSS JOIN p
LEFT JOIN ws.ws_th_receive       b ON a.id = b.service_order_id
LEFT JOIN ws.ws_th_work_order    c ON b.id = c.receive_id
LEFT JOIN ws.ws_th_hand_over     d ON b.id = d.receive_id
LEFT JOIN fl.fl_mst_fleet_all    e ON a.asset_id = e.asset_id
LEFT JOIN ws.ws_td_work_order    f ON c.id = f.work_order_id
LEFT JOIN ws.ws_md_repair_brand  g ON f.srv_category_detail_id = g.repair_brand_detail_id
LEFT JOIN ws.ws_mh_repair_brand  h ON g.repair_brand_id = h.repair_brand_id
LEFT JOIN gn.gn_md_lookup        i ON b.main_rootcause = i.lookup_lines_id
WHERE (
     a.insert_time   >= p.mulai AND a.insert_time   < p.sampai
  OR b.insert_time   >= p.mulai AND b.insert_time   < p.sampai
  OR c.start_time    >= p.mulai AND c.start_time    < p.sampai
  OR c.complete_time >= p.mulai AND c.complete_time < p.sampai
  OR d.handover_time >= p.mulai AND d.handover_time < p.sampai
  OR a.delete_time   >= p.mulai AND a.delete_time   < p.sampai
)
ORDER BY
  a.id            DESC,
  c.wo_number     NULLS LAST,
  d.handover_time DESC NULLS LAST,
  c.complete_time DESC NULLS LAST,
  c.start_time    DESC NULLS LAST,
  b.insert_time   DESC
