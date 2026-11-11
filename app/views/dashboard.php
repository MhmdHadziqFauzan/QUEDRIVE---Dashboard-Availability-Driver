<!DOCTYPE html>
<!-- DIBUAT OTOMATIS oleh scripts/build_dashboard.php dari HTML user: Quedrive - Transport Trailer - 9Nov26.html.
     Jangan edit langsung: ubah public/assets/js/quedrive-adapter.js atau patch di scripts/build_dashboard.php, lalu build ulang. -->
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>QUEDRIVE – Queue Driver Availability &amp; Status Monitor</title>
<script src="/assets/js/xlsx.full.min.js"></script>
<style>
:root{
  --bg:#EEF1F5; --surface:#FFFFFF; --surface2:#F5F7FA; --border:#DCE1E8; --text:#1B2430; --muted:#697586;
  --accent:#1F5FBF; --accent-bg:#E7EEFA; --accent2:#2E8B57; --danger:#C62828; --warn:#D98200;
  --s-or:#43A35A; --s-o:#AEB6C1; --s-ais:#8FA8DA; --s-ink:#1B2430; color-scheme:light;
}
:root[data-theme="dark"]{
  --bg:#0F1621; --surface:#172130; --surface2:#1E2A3B; --border:#2B3A50; --text:#E4EAF2; --muted:#93A1B5;
  --accent:#5B93E6; --accent-bg:#1D2E48; --accent2:#4CC37A; --danger:#FF6B6B; --warn:#F2B34A;
  --s-or:#2F8446; --s-o:#56606E; --s-ais:#4F679A; --s-ink:#F5F7FA; color-scheme:dark;
}
@media (prefers-color-scheme: dark){:root:not([data-theme="light"]){
  --bg:#0F1621; --surface:#172130; --surface2:#1E2A3B; --border:#2B3A50; --text:#E4EAF2; --muted:#93A1B5;
  --accent:#5B93E6; --accent-bg:#1D2E48; --accent2:#4CC37A; --danger:#FF6B6B; --warn:#F2B34A;
  --s-or:#2F8446; --s-o:#56606E; --s-ais:#4F679A; --s-ink:#F5F7FA; color-scheme:dark;
}}
*{box-sizing:border-box}
html,body{margin:0;height:100%}
:root{box-sizing:border-box;padding-top:env(safe-area-inset-top,0px);padding-bottom:env(safe-area-inset-bottom,0px)}
html{scroll-padding-top:env(safe-area-inset-top,0px)}
body{background:var(--bg);color:var(--text);font-family:"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;font-size:13px;line-height:1.45}
button,input,select{font-family:inherit;font-size:inherit;color:inherit}
button:focus-visible,input:focus-visible,select:focus-visible{outline:2px solid var(--accent);outline-offset:2px}
svg.ic{width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0}
.app-shell{display:flex;min-height:100vh}
/* sidebar */
.sidebar{width:232px;flex-shrink:0;background:var(--surface);border-right:1px solid var(--border);display:flex;flex-direction:column;padding:18px 14px;position:sticky;top:0;height:100vh;overflow-y:auto}
.sidebar-brand{display:flex;align-items:center;gap:10px;padding:2px 6px 16px;margin-bottom:14px;border-bottom:1px solid var(--border)}
.brand-icon{width:34px;height:34px;border-radius:9px;background:var(--accent-bg);color:var(--accent);display:flex;align-items:center;justify-content:center}
.brand-row{display:flex;align-items:center;gap:10px;padding:2px 2px 0}
.brand-icon svg.ic{width:22px;height:22px}
.sidebar-title{font-size:15px;font-weight:800;line-height:1.2;letter-spacing:.02em}
.sidebar-subtitle{font-size:11px;color:var(--muted)}
.logo-box{display:flex;align-items:center;justify-content:flex-start;padding:2px 2px}
.logo-box img{width:100%;max-width:170px;height:auto;display:block}
.side-label{font-size:10px;font-weight:700;color:var(--muted);letter-spacing:.04em;padding:0 8px;margin:10px 0 6px}
.side-nav{display:flex;flex-direction:column;gap:3px}
.side-btn{display:flex;align-items:center;gap:10px;width:100%;padding:9px 12px;border-radius:8px;border:none;background:transparent;color:var(--muted);font-size:13px;font-weight:600;text-align:left;cursor:pointer}
.side-btn:hover{background:var(--surface2);color:var(--text)}
.side-btn.active{background:var(--accent);color:#fff}
.sidebar-footer{margin-top:auto;padding:14px 8px 0;border-top:1px solid var(--border);font-size:10px;color:var(--muted);text-align:center;line-height:1.7}
/* header */
.app-content{flex:1;min-width:0;display:flex;flex-direction:column}
header{position:sticky;top:env(safe-area-inset-top,0px);z-index:20;background:var(--surface);border-bottom:1px solid var(--border);padding:12px 22px;display:flex;align-items:center;gap:14px;flex-wrap:wrap}
.hdr-title{font-size:15px;font-weight:800;letter-spacing:.01em}
.hdr-sub{font-size:11px;color:var(--muted)}
.hdr-actions{margin-left:auto;display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.btn{display:inline-flex;align-items:center;gap:6px;padding:7px 12px;border-radius:7px;border:1px solid var(--border);background:var(--surface2);cursor:pointer;font-weight:600;font-size:12px}
.btn:hover{border-color:var(--muted)}
.btn.primary{background:var(--accent);border-color:var(--accent);color:#fff}
.btn.primary:hover{filter:brightness(1.08)}
.btn.danger{color:var(--danger)}
.btn.icon{padding:7px}
.clock{display:flex;align-items:center;gap:6px;background:var(--accent-bg);border:1px solid var(--accent);border-radius:7px;padding:6px 10px;font-size:12px;font-weight:700;color:var(--accent);font-variant-numeric:tabular-nums}
/* content */
main{padding:20px 16px 40px}
.view{display:none}.view.visible{display:block}
.page-h{display:flex;align-items:baseline;gap:12px;margin:0 0 14px;flex-wrap:wrap}
.page-h h2{font-size:17px;margin:0}
.page-h p{margin:0;color:var(--muted);font-size:12px}
.page-h .grow{margin-left:auto;display:flex;gap:8px;align-items:center}
.panel{background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:16px 18px;margin-bottom:16px}
.panel h3{font-size:13px;margin:0 0 4px;display:flex;align-items:center;gap:8px}
.panel h3 .grow{margin-left:auto;display:flex;align-items:center;gap:8px}
.panel .desc{font-size:11.5px;color:var(--muted);margin:0 0 12px}
.dash-grid{display:grid;grid-template-columns:minmax(392px,404px) minmax(0,1fr);gap:16px;align-items:start}
.dash-grid>*{min-width:0}
.dash-grid .panel{margin-bottom:0}
@media(max-width:1559px){.dash-grid{grid-template-columns:minmax(0,1fr)}.dash-grid .panel{margin-bottom:16px}}
/* tabel muat tanpa scroll samping */
.tbl-wrap.nox{overflow-x:hidden}
table.t.fit{table-layout:fixed}
table.t.fit th,table.t.fit td{padding:6px 4px;overflow:hidden;text-overflow:ellipsis}
table.t.fit th{white-space:normal;line-height:1.25;vertical-align:bottom}
table.t.fit td.wrap{white-space:normal;line-height:1.25}
table.t.fit .cell-in{min-width:0}
table.t.fit .pill{min-width:0;padding:1px 6px}
/* KPI */
.kpi-group{display:grid;grid-template-columns:170px 1fr;gap:0;background:var(--surface);border:1px solid var(--border);border-radius:12px;margin-bottom:16px;overflow:hidden}
.kpi-lead{padding:16px 18px;border-right:1px solid var(--border);display:flex;flex-direction:column;justify-content:center;background:var(--surface2)}
.kpi-lead .t{font-size:12px;font-weight:700}
.kpi-lead .big{font-size:40px;font-weight:800;line-height:1.05;color:var(--accent);margin:4px 0 2px}
.kpi-lead .s{font-size:11px;color:var(--muted)}
.kpi-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(110px,1fr))}
.kpi{padding:14px 14px;border-right:1px solid var(--border)}
.kpi:last-child{border-right:none}
.kpi .l{font-size:11px;color:var(--muted);font-weight:600;display:flex;align-items:center;gap:6px;line-height:1.35}
.kpi .v{font-size:24px;font-weight:800;line-height:1.1;margin-top:6px}
.kpi .dot{width:9px;height:9px;border-radius:2px;display:inline-block}
@media(max-width:800px){.kpi-group{grid-template-columns:1fr}.kpi-lead{border-right:none;border-bottom:1px solid var(--border)}}
/* tables */
.tbl-wrap{overflow:auto;max-height:64vh;border:1px solid var(--border);border-radius:9px}
table.t{border-collapse:separate;border-spacing:0;width:100%;font-size:12px}
table.t th,table.t td{padding:6px 8px;border-bottom:1px solid var(--border);white-space:nowrap;text-align:center}
table.t tbody tr:last-child td{border-bottom:none}
table.t thead th{position:sticky;top:0;background:var(--surface2);font-weight:700;font-size:11px;color:var(--muted);z-index:2}
table.t tbody tr:hover td{background:var(--accent-bg)}
table.t td.l,table.t th.l{text-align:left}
table.t .num{font-variant-numeric:tabular-nums}
table.t .mono{font-family:ui-monospace,Consolas,monospace;font-size:11px}
table.t td.nm{overflow:hidden;text-overflow:ellipsis}
table.t td.empty-row{padding:46px 12px;color:var(--muted)}
@keyframes newrow{from{background:var(--accent-bg)}to{background:transparent}}
table.t tr.new-row td{animation:newrow 2s ease-out}
.pill{display:inline-block;min-width:34px;padding:1px 7px;border-radius:5px;font-weight:700;font-size:11px;color:var(--s-ink)}
.c-OR{background:var(--s-or);color:#fff}.c-O{background:var(--s-o)}.c-AIS{background:var(--s-ais)}
.badge-n{font-size:11px;font-weight:700;background:var(--accent-bg);color:var(--accent);border-radius:9px;padding:1px 9px}
.toolbar{display:flex;gap:8px;align-items:center;flex-wrap:wrap;margin-bottom:10px}
.toolbar input[type=search]{background:var(--surface2);border:1px solid var(--border);border-radius:6px;padding:6px 9px;min-width:200px;flex:1}
.cell-in{width:100%;min-width:110px;background:transparent;border:none;border-bottom:1px dashed var(--border);padding:3px 4px;border-radius:0}
.cell-in:focus{outline:none;border-bottom-color:var(--accent);background:var(--surface2)}
.cell-in[readonly]{color:var(--muted);cursor:not-allowed;background:var(--surface2)}
.ibtn{border:none;background:transparent;cursor:pointer;padding:4px;border-radius:6px;color:var(--muted);display:inline-flex}
.ibtn:hover{background:var(--accent-bg)}
.ibtn.add{color:var(--accent2)}
.ibtn.add svg.ic{width:18px;height:18px}
.ibtn.del:hover{color:var(--danger)}
.na-ic{color:var(--muted);opacity:.5;display:inline-flex}
/* upload */
.drop{border:2px dashed var(--border);border-radius:12px;padding:26px;text-align:center;background:var(--surface2);margin-bottom:14px}
.drop b{font-size:14px;display:block;margin-bottom:6px}
.drop p{margin:0 0 14px;color:var(--muted);font-size:12px;line-height:1.55}
.info-line{font-size:12px;color:var(--muted);min-height:1.4em}
.info-line b{color:var(--text)}
/* asumsi */
.asm{max-width:940px}
.asm table{width:100%;border-collapse:collapse;font-size:12px}
.asm td{padding:7px 8px;border-top:1px solid var(--border);vertical-align:top}
.asm tr:first-child td{border-top:none}
.asm td:first-child{font-weight:700;color:var(--accent);width:210px}
.asm td:last-child{color:var(--text);line-height:1.55}
.asm .step{font-weight:800;color:var(--accent);margin-right:6px}
.asm .warn td:first-child{color:var(--warn)}
.toast{position:fixed;bottom:18px;right:18px;background:var(--text);color:var(--surface);padding:10px 14px;border-radius:8px;font-size:12px;opacity:0;transition:opacity .2s;pointer-events:none;z-index:99;max-width:420px}
.toast.show{opacity:1}
@media(max-width:800px){
  .logo-box img{max-width:120px}
  .app-shell{flex-direction:column}
  .sidebar{width:100%;height:auto;position:relative;flex-direction:row;flex-wrap:wrap;align-items:center;padding:10px}
  .sidebar-brand{border-bottom:none;margin:0;padding:2px 6px}
  .side-label,.sidebar-footer{display:none}
  .side-nav{flex-direction:row;flex-wrap:wrap}
  header{position:relative}
}
@media (prefers-reduced-motion: reduce){*{transition:none!important;animation:none!important}}
/* ---- tambahan v3 ---- */
.sharebar{display:flex;align-items:center;gap:10px;flex-wrap:wrap;padding:7px 22px;font-size:12px;border-bottom:1px solid var(--border)}
.sharebar.srv{background:#E8F5EC;color:#1E5631}
.sharebar.loc{background:#FFF6D6;color:#6B4E00}
.sharebar.ro{background:#FDECEC;color:#8E1B1B}
:root[data-theme="dark"] .sharebar.srv{background:#173524;color:#BFE8CC}
:root[data-theme="dark"] .sharebar.loc{background:#3B3110;color:#FFE6A0}
:root[data-theme="dark"] .sharebar.ro{background:#3A1F24;color:#FFC9C9}
.modal{position:fixed;inset:0;background:rgba(10,16,26,.5);display:flex;align-items:flex-start;justify-content:center;padding:12vh 16px;z-index:90}
.modal[hidden]{display:none}
.modal-box{background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:18px 20px;width:min(480px,100%);box-shadow:0 18px 50px rgba(0,0,0,.25)}
.modal-box h3{margin:0 0 6px;font-size:15px}
.modal-box p{margin:0;font-size:13px;line-height:1.5}
.slots{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:12px}
.slot{border:1px solid var(--border);border-radius:10px;padding:12px 14px;background:var(--surface);display:flex;flex-direction:column;gap:6px}
.slot-h{display:flex;align-items:center;gap:8px;font-weight:700}
.slot-h .n{width:22px;height:22px;border-radius:50%;background:var(--accent-bg);color:var(--accent);display:flex;align-items:center;justify-content:center;font-size:11px}
.slot .fn{font-size:11.5px;color:var(--muted);word-break:break-word}
.slot .ok{color:var(--accent2);font-weight:700;font-size:11.5px}
.slot .miss{color:var(--danger);font-weight:700;font-size:11.5px}
.slot-actions{display:flex;gap:6px;margin-top:auto;flex-wrap:wrap}
.drop{border:2px dashed var(--border);border-radius:12px;padding:22px;text-align:center;background:var(--surface2);cursor:pointer;margin-bottom:16px}
.drop.over{border-color:var(--accent);background:var(--accent-bg)}
.drop b{font-size:14px}
.note{font-size:11px;color:var(--muted)}
.set-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;max-width:900px}
@media(max-width:700px){.set-grid{grid-template-columns:1fr}}
.set-grid label{display:flex;flex-direction:column;gap:4px;font-size:11.5px;font-weight:700;color:var(--muted)}
.set-grid input,.set-grid select{background:#FFFBD6;border:1px solid #E1CD5A;border-radius:6px;padding:7px 9px;font-weight:700;color:#1B2430}
:root[data-theme="dark"] .set-grid input,:root[data-theme="dark"] .set-grid select{background:#3B3514;border-color:#7C6D21;color:#FFF3B0}
.set-grid small{font-weight:400;color:var(--muted)}
.hist-bar{display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-bottom:14px}
.hist-bar input[type=date],.hist-bar select{background:var(--accent-bg);border:1px solid var(--accent);border-radius:7px;padding:6px 8px;font-weight:700;color:var(--text)}
.hist-bar .grow{margin-left:auto}
.sj-wrap{max-width:980px}
.sj-card{border:1px solid var(--border);border-radius:9px;overflow:hidden;background:var(--surface)}
.sj-title{background:#FF0000;color:#111;font-weight:800;text-align:center;padding:7px 10px;letter-spacing:.03em;font-size:14px;border-bottom:1px solid #B00000}
.sj-scroll{max-height:68vh;overflow:auto}
table.sj{width:100%;border-collapse:collapse;font-size:13px}
table.sj th{background:#FF0000;color:#111;font-weight:800;padding:7px 10px;border:1px solid #B00000;position:sticky;top:0;z-index:2;text-transform:uppercase}
table.sj td{padding:6px 10px;border:1px solid var(--border)}
table.sj td.c{text-align:center}
table.sj td.age-red{background:#FF0000;color:#111;font-weight:800;text-align:center}
table.sj td.age{text-align:center;font-weight:700}
table.sj tbody tr:hover td:not(.age-red){background:var(--accent-bg)}
.ro-note{opacity:.55;pointer-events:none}
.empty{padding:50px 20px;text-align:center;color:var(--muted)}
.empty b{display:block;color:var(--text);font-size:15px;margin-bottom:6px}
.modal-box.wide{width:min(980px,100%);max-height:84vh;overflow:auto;position:relative}
.modal-x{position:absolute;top:10px;right:12px;width:30px;height:30px;border-radius:8px;border:1px solid var(--border);background:var(--surface2);cursor:pointer;font-size:18px;line-height:1}
.modal-box.wide h3{padding-right:40px}
.or-link{display:inline-flex;flex-direction:column;align-items:center;border:none;background:transparent;cursor:pointer;padding:2px 4px;border-radius:7px;color:inherit}
.or-link:hover{background:var(--accent-bg)}
.or-link:hover .pill{outline:2px solid var(--accent)}
.or-link .note{text-decoration:underline dotted;text-underline-offset:2px}
table.t.wrapall th,table.t.wrapall td{white-space:normal;line-height:1.35}
table.t.wrapall td{vertical-align:top}
.age{display:inline-block;min-width:34px;padding:1px 9px;border-radius:999px;font-weight:700;font-size:12px;background:var(--surface2);border:1px solid var(--border)}
.age.red{background:#E53935;border-color:#E53935;color:#fff}
.alert-note{background:#FFF6D6;border:1px solid #E1CD5A;color:#6B4E00;border-radius:8px;padding:8px 10px;font-size:12px;margin-bottom:10px}
:root[data-theme="dark"] .alert-note{background:#3B3110;border-color:#7C6D21;color:#FFE6A0}
.kpi .sub{font-size:11px;color:var(--muted);margin-top:4px}
.kpi .sub b{color:var(--text)}
.kpi.wide{grid-column:span 2}
.kpi .trio{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}
.kpi .sub.split{display:flex;gap:10px;flex-wrap:wrap;min-height:1.2em}
.kpi .sub.split b{color:var(--text)}
@media(max-width:560px){.kpi.wide{grid-column:span 1}.kpi .trio{grid-template-columns:1fr}}
.kpi.quadbox{grid-column:span 2;padding:0}
.kpi .quad{display:grid;grid-template-columns:repeat(2,minmax(0,1fr))}
.kpi .qc{padding:14px}
.kpi .qc:nth-child(odd){border-right:1px solid var(--border)}
.kpi .qc:nth-child(-n+2){border-bottom:1px solid var(--border)}
@media(max-width:560px){.kpi.quadbox{grid-column:span 1}}
/* tabel antrian: teks tidak dipotong (kecuali kolom isian bebas Depo & Keterangan) */
table.t.auto{table-layout:auto;width:100%;font-size:11.5px}
table.t.auto th,table.t.auto td{padding:6px 5px}
table.t.auto th{white-space:normal;line-height:1.25;vertical-align:bottom}
table.t.auto td.nm{white-space:normal;overflow:visible;text-overflow:clip;min-width:84px;line-height:1.3}
table.t.auto .cell-in{min-width:84px;width:100%}
table.t.auto td.wrap{white-space:normal;line-height:1.3}
table.t.auto td.ft .cell-in{min-width:56px}
table.t.auto td.ft2 .cell-in{min-width:64px}
.type-in{white-space:nowrap}
.type-in.ph{color:var(--muted)}
/* daftar driver (kiri): nama panjang turun ke baris berikutnya, bukan dipotong */
table.t.fit td.nm{white-space:normal;overflow:visible;text-overflow:clip;line-height:1.3}
table.t.fit.lt{font-size:11.5px}
table.t.fit.lt th{font-size:10.5px}
table.t.fit.lt th,table.t.fit.lt td{padding:6px 3px}
table.t.fit.lt td.nm{white-space:normal;word-break:normal;overflow-wrap:break-word}
.c-NG{background:var(--danger);color:#fff}
/* filter customer (pilihan ganda) */
.sjbar{align-items:flex-start;gap:10px}
.msel{position:relative}
.msel-btn b{font-weight:700;max-width:260px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;display:inline-block;vertical-align:bottom}
.msel-panel{position:absolute;z-index:40;top:calc(100% + 6px);left:0;width:min(460px,92vw);background:var(--surface);border:1px solid var(--border);border-radius:10px;box-shadow:0 12px 32px rgba(0,0,0,.18);padding:10px}
.msel-panel[hidden]{display:none}
.msel-panel input[type=search]{width:100%;min-width:0;flex:none;background:var(--surface2);border:1px solid var(--border);border-radius:6px;padding:6px 9px}
.msel-actions{display:flex;gap:6px;margin:8px 0 6px}
.msel-list{max-height:290px;overflow:auto;border-top:1px solid var(--border);padding-top:4px}
.msel-item{display:flex;align-items:flex-start;gap:8px;padding:5px 6px;border-radius:6px;cursor:pointer;font-size:12px;line-height:1.35}
.msel-item:hover{background:var(--accent-bg)}
.msel-item span{flex:1}
.msel-item em{font-style:normal;color:var(--muted);font-size:11px}
.msel-item[hidden]{display:none}
/* kolom nopol ringkas */
table.t.auto td.np{width:96px}
table.t.auto td.np .cell-in{width:88px;min-width:84px}
table.t.auto th,table.t.auto td{padding:6px 4px}
.dash-grid .panel{padding:14px}
.toolbar select{background:var(--surface2);border:1px solid var(--border);border-radius:6px;padding:4px 6px}
/* header tabel: rata tengah (kiri-kanan & atas-bawah), tidak terpotong */
table.t th,table.t th.l,table.t.fit th,table.t.auto th{text-align:center;vertical-align:middle}
table.t.fit th,table.t.auto th{overflow:visible;text-overflow:clip}
/* baris filter di atas header kolom */
table.t thead tr.flt th{top:0;z-index:3;height:34px;box-sizing:border-box;padding:4px 3px;background:var(--surface);border-bottom:1px solid var(--border)}
table.t thead tr.flt + tr th{top:34px}
table.t thead tr.flt select{appearance:none;-webkit-appearance:none;width:100%;min-width:54px;font-size:10px;line-height:1.2;padding:3px 11px 3px 3px;border:1px solid var(--border);border-radius:5px;color:var(--text);text-overflow:clip;cursor:pointer;
  background:var(--surface2) url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='8' height='5' viewBox='0 0 8 5'><path d='M0 0l4 5 4-5z' fill='%23697586'/></svg>") no-repeat right 3px center/8px 5px}
table.t thead tr.flt select:focus-visible{outline:2px solid var(--accent);outline-offset:1px}
/* tanggal-jam: font lebih kecil, tanggal & jam tidak dipecah */
table.t.auto td.dt{font-size:10px;line-height:1.35}
.nb{white-space:nowrap}
/* list driver: font lebih kecil agar "NOT AVA" dan nama driver tidak terpotong / tidak sampai 5 baris */
table.t.fit.lt{font-size:10.5px}
table.t.fit.lt th{font-size:10px}
table.t.fit.lt td.mono{font-size:10px}
table.t.fit.lt td.nm{font-size:10px;line-height:1.25}
table.t.fit.lt .pill{font-size:9px;padding:1px 3px;min-width:0}
table.t.fit.lt td{overflow:visible;text-overflow:clip}
/* status data di kiri bawah (footer sidebar) */
.sidebar-footer{padding:10px 0 0;text-align:left;line-height:1.4}
.sidebar-footer .sharebar{display:block;padding:8px 9px;border:1px solid transparent;border-bottom-width:1px;border-radius:8px;font-size:10px;line-height:1.4}
.sidebar-footer .sharebar.srv{border-color:#BFE0C8}
.sidebar-footer .sharebar.loc{border-color:#EAD98B}
.sidebar-footer .sharebar.ro{border-color:#F0B8B8}
:root[data-theme="dark"] .sidebar-footer .sharebar.srv{border-color:#2B5A3E}
:root[data-theme="dark"] .sidebar-footer .sharebar.loc{border-color:#6B5A1D}
:root[data-theme="dark"] .sidebar-footer .sharebar.ro{border-color:#6A3038}
.sidebar-footer .sharebar .btn{margin-top:6px;padding:4px 7px;font-size:10px}
@media(max-width:800px){.sidebar-footer{display:block;width:100%;border-top:none;padding:6px 6px 0}}
/* ringkasan KPI lebih rendah agar List Driver & List Antrian naik */
header{padding:8px 22px}
main{padding-top:12px}
.page-h{margin-bottom:8px}
.page-h h2{font-size:16px}
.kpi-group{margin-bottom:10px}
.kpi-lead{padding:8px 14px}
.kpi-lead .t{font-size:11px}
.kpi-lead .big{font-size:28px;margin:1px 0}
.kpi-lead .s{font-size:10px;line-height:1.3}
.kpi{padding:8px 12px}
.kpi .l{font-size:10.5px;line-height:1.25}
.kpi .v{font-size:20px;margin-top:2px}
.kpi .sub{font-size:10px;margin-top:1px;line-height:1.3}
.kpi .sub.split{min-height:0;gap:8px}
.kpi .qc{padding:7px 12px}
.kpi-row .kpi:not(.quadbox){display:flex;flex-direction:column;justify-content:center}
.dash-grid .panel{padding:12px}
.dash-grid .panel .desc{margin-bottom:8px}

/* logo & header ala APEX */
.lg{fill:none;stroke-linecap:round;stroke-linejoin:round;stroke-width:1.8;--lg-bg:var(--surface)}
.lg .t{stroke:var(--warn);color:var(--warn)}
.lg .p{stroke:var(--accent);color:var(--accent)}
.sidebar-brand{padding:4px 0 10px;margin-bottom:6px}
.sidebar-brand .brand-row{gap:8px;padding:0}
.brand-icon{width:44px;height:44px;background:none;border-radius:0;flex-shrink:0}
.brand-icon svg.lg{width:44px;height:44px}
.sidebar-title{font-size:22px;font-weight:800;letter-spacing:.05em;line-height:1.05;white-space:nowrap}
.sidebar-subtitle{font-size:8px;letter-spacing:.17em;text-transform:uppercase;margin-top:3px;white-space:nowrap}
header{border-left:4px solid var(--accent)}
.hdr-title{padding-left:2px}
.hdr-actions{gap:10px}
.hdr-logo{height:38px;width:auto;display:block;margin-left:6px}
/* mode gelap (night): logo Puninar diberi halo putih tipis seperti DUIT (blur drop-shadow = standar deviasi, jadi cukup 0,75px) */
:root[data-theme="dark"] .hdr-logo{filter:drop-shadow(0 0 .75px #fff) drop-shadow(0 0 .75px #fff) drop-shadow(0 0 .75px #fff)}
@media(max-width:800px){.hdr-logo{height:30px}}
/* tombol Day/Night + tulisan kecil di kiri bawah (seperti APEX) */
.theme-pill{display:flex;align-items:center;justify-content:center;gap:8px;margin:10px auto 8px;padding:7px 18px;border:1px solid var(--accent);background:var(--accent-bg);color:var(--accent);border-radius:999px;font-size:12.5px;cursor:pointer;min-width:104px}
.theme-pill b{font-weight:700}
.theme-pill:hover{filter:brightness(.97)}
.theme-ic{display:inline-flex}
.theme-ic svg{width:17px;height:17px}
.side-credit{text-align:center;font-size:10.5px;color:var(--muted);line-height:1.5;padding-bottom:6px}
.side-credit .sc-name{letter-spacing:.06em}

/* status absensi */
.c-ONR{background:var(--accent);color:#fff}
.c-NA{background:transparent;border:1px dashed var(--border);color:var(--muted)}

.raw-bar{display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-bottom:10px}
.raw-bar label{display:flex;align-items:center;gap:6px;font-size:12px;color:var(--muted);font-weight:600}
.raw-bar select,.raw-bar input[type=date]{background:var(--surface2);border:1px solid var(--border);border-radius:6px;padding:5px 7px;color:var(--text)}
.raw-bar input[type=search]{background:var(--surface2);border:1px solid var(--border);border-radius:6px;padding:6px 9px;min-width:200px}
#rawInfo{margin-bottom:8px}
#rawInfo .btn{padding:2px 9px;margin-left:6px}

.c-S{background:rgba(217,130,0,.2);color:var(--warn);border:1px solid var(--warn)}
table.t.fit.lt td.dt{font-size:9.5px;line-height:1.3;white-space:normal}

/* menu kiri auto-hide + List Driver & List Antrian selalu kiri-kanan */
.app-shell{display:block;position:relative}
.app-content{margin-left:0;width:100%;min-width:0}
.sidebar{position:fixed;left:0;top:0;bottom:0;width:232px;height:100vh;z-index:60;transform:translateX(-102%);transition:transform .18s ease,box-shadow .18s ease;flex-direction:column;flex-wrap:nowrap;align-items:stretch;padding:18px 14px;overflow-y:auto}
.app-shell.side-open .sidebar{transform:translateX(0);box-shadow:8px 0 28px rgba(0,0,0,.22)}
.side-hot{position:fixed;left:0;top:0;bottom:0;width:12px;z-index:59}
.side-hot::after{content:"";position:absolute;left:0;top:0;bottom:0;width:3px;background:var(--accent);opacity:.3;transition:opacity .15s}
.side-hot:hover::after{opacity:.8}
.app-shell.side-open .side-hot{display:none}
.hdr-menu{width:34px;height:34px;display:inline-flex;align-items:center;justify-content:center;border:1px solid var(--border);border-radius:8px;background:var(--surface);color:var(--text);cursor:pointer;margin-right:10px;flex-shrink:0}
.hdr-menu:hover{background:var(--surface2)}
.hdr-menu svg{width:18px;height:18px;fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round}
header{display:flex;align-items:center}
main{padding-left:14px;padding-right:14px}
.dash-grid{grid-template-columns:minmax(392px,404px) minmax(0,1fr)}
@media(max-width:1559px){.dash-grid{grid-template-columns:minmax(380px,400px) minmax(0,1fr)}.dash-grid .panel{margin-bottom:0}}
@media(max-width:1240px){.dash-grid{grid-template-columns:minmax(340px,32%) minmax(0,1fr)}}
@media(max-width:900px){.dash-grid{grid-template-columns:minmax(0,1fr)}.dash-grid .panel{margin-bottom:16px}}
@media(max-width:800px){
  .sidebar{width:232px;height:100vh;position:fixed;flex-direction:column;flex-wrap:nowrap;align-items:stretch;padding:18px 14px}
  .side-label{display:block}
  .sidebar-footer{display:block;width:auto;border-top:1px solid var(--border);padding:10px 0 0}
  .side-nav{flex-direction:column;flex-wrap:nowrap}
  .sidebar-brand{padding:4px 0 10px;margin-bottom:6px}
  header{position:relative}
}

/* pilihan tanggal aplikasi (kanan atas) */
.clock-wrap{position:relative}
button.clock{cursor:pointer;font-family:inherit}
button.clock:hover{filter:brightness(.97)}
button.clock.sim{background:rgba(217,130,0,.16);border-color:var(--warn);color:var(--warn)}
.sim-badge{font-size:9px;letter-spacing:.06em;background:var(--warn);color:#fff;border-radius:4px;padding:1px 5px}
.asof-pop{position:absolute;right:0;top:calc(100% + 8px);z-index:80;width:300px;background:var(--surface);border:1px solid var(--border);border-radius:10px;box-shadow:0 10px 30px rgba(0,0,0,.22);padding:12px 14px;font-size:12.5px;color:var(--text)}
.asof-pop[hidden]{display:none}
.asof-title{font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px}
.asof-opt{display:flex;align-items:baseline;gap:6px;padding:5px 2px;cursor:pointer}
.asof-opt small{color:var(--muted);font-size:11px;margin-left:2px}
.asof-pop input[type=date]{width:100%;margin:6px 0 8px;background:var(--surface2);border:1px solid var(--border);border-radius:6px;padding:6px 8px;color:var(--text)}
.asof-pop input[type=date]:disabled{opacity:.5}
.asof-note{font-size:10.5px;line-height:1.45;color:var(--muted);border-top:1px solid var(--border);padding-top:8px}

.cell-in.dup{border-color:var(--danger)!important;box-shadow:0 0 0 1px var(--danger);background:rgba(217,48,37,.08)}

/* RAW Data: urut & filter kolom */
th.rawh{white-space:nowrap}
th.rawh .sh-lbl{cursor:pointer;user-select:none}
th.rawh .sh-lbl:hover{color:var(--accent)}
.sh-arrow{display:inline-block;min-width:12px;font-size:12px;line-height:1;color:var(--accent);margin-left:3px;vertical-align:-1px}
.sh-f{border:1px solid transparent;background:none;color:var(--accent);cursor:pointer;font-size:11px;line-height:1;padding:2px 3px;border-radius:4px;margin-left:2px}
.sh-f:hover{background:var(--surface2);color:var(--text)}
.sh-f.on{background:var(--accent);color:#fff}
.sh-pop.fx{position:fixed;z-index:90;background:var(--surface);border:1px solid var(--border);border-radius:10px;box-shadow:0 10px 30px rgba(0,0,0,.25);padding:10px;display:flex;flex-direction:column;gap:6px;font-size:12px;color:var(--text)}
.sh-head{font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em}
.sh-q{background:var(--surface2);border:1px solid var(--border);border-radius:6px;padding:5px 8px;color:var(--text);width:100%}
.sh-act{display:flex;gap:6px}
.sh-act button{flex:1;background:var(--surface2);border:1px solid var(--border);border-radius:6px;padding:4px 6px;font-size:11px;color:var(--text);cursor:pointer}
.sh-act button:hover{border-color:var(--accent)}
.msel-list{flex:1 1 auto;min-height:0;max-height:300px;overflow-y:auto;border:1px solid var(--border);border-radius:6px;background:var(--surface)}
.msel-row{display:flex;align-items:center;gap:7px;padding:4px 8px;cursor:pointer}
.msel-row:hover{background:var(--surface2)}
.msel-row span:first-of-type{flex:1;min-width:0;overflow-wrap:anywhere}
.msel-row .ct{color:var(--muted);font-size:10.5px}
.sh-note{font-size:10px;color:var(--muted)}
.sh-empty{padding:10px;color:var(--muted);font-size:11px}

/* kartu KPI: Kode Absensi Semua Driver */
@media(min-width:801px){.kpi-group{grid-template-columns:232px 1fr}}
.kpi-lead2{padding:0;gap:0;justify-content:stretch}
.kl-box{padding:7px 14px;display:flex;flex-direction:column;justify-content:center;flex:1}
.kl-box + .kl-box{border-top:1px solid var(--border)}
.kl-box .t{font-size:11px;font-weight:700}
.kl-line{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.kl-box .big{font-size:26px;margin:0}
.kl-box .s{font-size:10px;color:var(--muted);line-height:1.25;max-width:110px}
.kl-break{display:flex;flex-wrap:wrap;gap:3px 5px;flex:1;min-width:110px}
.ch{font-size:9.5px;font-weight:600;border-radius:4px;padding:1px 5px;background:var(--surface);border:1px solid var(--border);color:var(--muted);white-space:nowrap}
.ch b{color:var(--text);font-weight:800}
.ch-x{border-color:var(--s-or)}.ch-or{border-color:var(--accent)}.ch-ais{border-color:var(--s-ais)}.ch-s{border-color:var(--warn)}
.kpi-stack{display:flex;flex-direction:column;border-right:1px solid var(--border);min-width:0}
.kpi-stack .kpi{flex:1;border-right:none}
.kpi-stack .kpi + .kpi{border-top:1px solid var(--border)}
.kpi .v.duo{display:flex;gap:16px;align-items:baseline}
.kpi .v.duo small{font-size:9.5px;font-weight:600;color:var(--muted);margin-left:4px}

/* tata letak kartu: SPK sudah tender, Driver tender hari ini, dan Kode Absensi Semua Driver ditumpuk */
@media(min-width:801px){.kpi-group{grid-template-columns:170px 1fr}}
.kpi-group .kpi-row{grid-template-columns:minmax(0,1fr) minmax(0,1.45fr)}
.kpi-group .kpi-row > .kpi.quadbox{grid-column:1;grid-row:1}
.kpi-group .kpi-row > .kpi-stack{grid-column:2;grid-row:1;display:flex;flex-direction:column;min-width:0;border-left:1px solid var(--border)}
.kpi-group .kpi-stack > .kpi.kx{display:flex;flex-direction:row;align-items:center;justify-content:flex-start;text-align:left;gap:10px;padding:5px 12px;border-right:none;flex:1 1 auto;min-height:34px}
.kpi-group .kpi-stack > .kpi.kx + .kpi.kx{border-top:1px solid var(--border)}
.kx .l{flex:0 0 auto;min-width:128px;white-space:nowrap}
.kpi-stack .kx:last-child .l{min-width:168px}
.kx .v{font-size:20px;margin:0;min-width:36px;text-align:right}
.kx .sub,.kx .kl-break{margin:0;flex:1 1 auto;min-width:0}
.kx .kl-break{min-width:0;gap:3px 4px;flex-wrap:wrap}
.kx .ch{padding:1px 4px;font-size:9px}
.kx{gap:8px!important}
@media(max-width:800px){.kpi-group .kpi-row{grid-template-columns:1fr}.kpi-group .kpi-row > .kpi.quadbox,.kpi-group .kpi-row > .kpi-stack{grid-column:1;grid-row:auto}.kpi-group .kpi-row > .kpi-stack{border-left:none;border-top:1px solid var(--border)}}
.ch-na{border-style:dashed}.ch-off{border-color:var(--muted)}

/* List Driver & List Antrian: sama tinggi (ujung bawah sejajar) dan setinggi layar, supaya lebih banyak baris terlihat sekaligus */
@media(min-width:901px){
  .dash-grid{align-items:stretch}
  .dash-grid > .panel{display:flex;flex-direction:column;height:max(520px,calc(100vh - 28px));min-height:0}
  .dash-grid > .panel > .tbl-wrap{flex:1 1 auto;min-height:0;max-height:none}
}
.kpi .sub.split span.oth{cursor:help;border-bottom:1px dotted var(--muted)}
.sh-arrow.idle{color:var(--accent);opacity:.55}
th.rawh .sh-lbl:hover .sh-arrow.idle{opacity:1;color:var(--accent)}
/* Asumsi: judul dan isi berada di tengah halaman (tidak mepet kiri) */
#v-asumsi .page-h,#v-asumsi .asm{max-width:960px;margin-left:auto;margin-right:auto}
/* elemen ber-atribut hidden (mis. tombol Reset urut & filter) benar-benar tersembunyi walau kelasnya mengatur display */
[hidden]{display:none!important}

/* kartu Status All Driver (di bagian atas Dashboard) */
.kpi-flow{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);margin:0 0 12px;padding:8px 14px 10px}
.kf-head{display:flex;align-items:baseline;gap:10px;margin-bottom:6px}
.kf-title{font-size:12px;font-weight:800}
.kf-sub{font-size:11px;color:var(--muted)}
.kf-grid{display:grid;grid-template-columns:repeat(7,minmax(0,1fr));gap:0}
.kf{padding:2px 12px;border-left:1px solid var(--border);min-width:0;cursor:help}
.kf:first-child{border-left:none;padding-left:0}
.kf .l{display:flex;align-items:flex-start;gap:6px;font-size:11px;font-weight:600;color:var(--muted);line-height:1.25;min-height:28px}
.kf .l i.dot{display:inline-block;width:9px;height:9px;border-radius:2px;margin-top:3px;flex:0 0 auto}
.kf .v{font-size:22px;font-weight:800;line-height:1.15}
.kf .p{font-size:10px;color:var(--muted)}
.kf.na .l i.dot{background:transparent!important;border:1.5px dashed var(--muted)}
@media(max-width:1100px){.kf-grid{grid-template-columns:repeat(4,minmax(0,1fr));row-gap:8px}.kf:nth-child(4n+1){border-left:none;padding-left:0}}
@media(max-width:700px){.kf-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.kf:nth-child(odd){border-left:none;padding-left:0}.kf:nth-child(even){border-left:1px solid var(--border)}}
/* putaran antri */
tr.retired td{color:var(--muted)}
tr.retired td > *{opacity:.55}   /* putaran lama: isi diredupkan, latar (hijau SUDAH) tetap sama dengan baris aktif */
tr.retired .cell-in{background:transparent;border-color:transparent}
.rnd{font-size:11px;font-weight:600;color:var(--muted)}
.rnd.hi{background:#FFF3C4;border:1px solid #E0A100;border-radius:10px;padding:1px 7px;color:#6b4a00;font-weight:800}
td.act{white-space:nowrap}
.ibtn.rq{color:var(--muted);border:1px dashed var(--border);border-radius:7px;background:transparent;margin-right:4px}
.ibtn.rq.ready{color:#fff;background:var(--s-or);border:1px solid var(--s-or)}
.ibtn.rq .ic{stroke:currentColor}
.ibtn.add.muted{color:var(--muted);border:1px dashed var(--border);border-radius:6px;opacity:.85}
.ibtn.add.muted:hover{opacity:1}

/* tiga kartu (SPK sudah tender, Driver tender hari ini, Kode Absensi Semua Driver) kini di kanan kartu N/A pada Status All Driver */
.kpi-group .kpi-row{grid-template-columns:minmax(0,1fr)}
.kf-wrap{display:grid;grid-template-columns:minmax(0,1fr) minmax(350px,440px);gap:0 14px;align-items:stretch}
.kf-side{display:flex;flex-direction:column;min-width:0;border-left:1px solid var(--border)}
.kf-side > .kpi.kx{display:flex;flex-direction:row;align-items:center;justify-content:flex-start;text-align:left;gap:8px;padding:5px 12px;border:none;background:transparent;flex:1 1 auto;min-height:34px}
.kf-side > .kpi.kx + .kpi.kx{border-top:1px solid var(--border)}
.kf-side .kx:last-child .l{min-width:168px}
@media(max-width:1280px){.kf-wrap{grid-template-columns:minmax(0,1fr)}.kf-side{border-left:none;border-top:1px solid var(--border);margin-top:8px}}
.hd-info{font-size:11.5px;font-weight:500;color:var(--muted);margin-left:10px;display:inline-flex;gap:6px;align-items:center;flex-wrap:wrap}
.hd-info b{color:var(--text);font-weight:800}
.hd-info .pill{font-size:9.5px;padding:1px 7px}

/* kotak Belum / Sudah Diorder / % Belum / Tersedia (AVA): satu baris empat sel karena kolom tumpukan sudah pindah */
@media(min-width:801px){
  .kpi .quad{grid-template-columns:repeat(4,minmax(0,1fr))}
  .kpi .qc{border-right:none!important;border-bottom:none!important;border-left:1px solid var(--border);padding:8px 16px}
  .kpi .qc:first-child{border-left:none}
}

/* judul tabel Dashboard: keterangan di sampingnya turun ke baris berikutnya bila tidak muat, tidak memecah kata */
.dash-grid > .panel > h3{flex-wrap:wrap;row-gap:4px}
.hd-info,.badge-n{white-space:nowrap}

/* baris Status Order SUDAH diberi latar hijau lembut (Dashboard Antrian dan History) */
table.t tr.so-ok td{background:rgba(67,163,90,.14)}
table.t tr.so-ok:hover td{background:rgba(67,163,90,.24)}
:root[data-theme="dark"] table.t tr.so-ok td{background:rgba(67,163,90,.2)}
:root[data-theme="dark"] table.t tr.so-ok:hover td{background:rgba(67,163,90,.3)}
/* History Antrian: tabel muat di layar tanpa scroll ke kanan; kedua tabel terlihat dalam satu layar (scroll hanya di dalam tabel) */
:root{--hh:354px}
#v-history .tbl-wrap{max-height:max(150px,calc((100vh - var(--hh)) / 2))}
#v-history table.t{width:100%;table-layout:auto}
#v-history table.t th,#v-history table.t td{padding:5px 5px;font-size:10.5px;line-height:1.25;white-space:normal;word-break:normal;overflow-wrap:break-word}
#v-history table.t th{font-size:10px}
#v-history table.t td.dt{white-space:normal}
#v-history table.t .pill{font-size:9.5px;padding:1px 6px;white-space:nowrap}
#v-history .hd-info{margin-left:12px}
#v-history .panel{padding-top:10px;padding-bottom:10px}
#v-history .panel .desc{margin:2px 0 6px}

/* rincian 20 FT / 40 FT / N/A di kanan angka kartu Status All Driver */
.kf .vrow{display:flex;align-items:center;flex-wrap:nowrap;gap:8px}
.kf .vrow .v{flex:0 0 auto}
.kf-split{display:flex;flex-wrap:wrap;gap:0 7px;font-size:9.5px;color:var(--muted);line-height:1.3;min-width:0;flex:1 1 0}
.kf-split b{color:var(--text);font-weight:800}
.kf-split span{white-space:nowrap}
.kf-split .np{cursor:help;white-space:normal}
.toolbar.mtool{margin:0 0 10px}
/* menu kiri: tidak ada scroll kiri-kanan; teks yang panjang turun baris */
.sidebar{overflow-x:hidden}
.sidebar .side-btn{white-space:normal;word-break:normal;overflow-wrap:break-word;min-width:0}
.sidebar-footer,.side-credit,.sidebar-footer .sharebar{min-width:0;overflow-wrap:anywhere}
.sidebar-title{font-size:21px}

/* kartu Kode Absensi Semua Driver: label, angka, dan lima chip dalam SATU baris */
.kf-wrap{grid-template-columns:minmax(0,1fr) minmax(430px,490px)}
.kf-side .kx:last-child{flex-wrap:nowrap;white-space:nowrap}
.kf-side .kx:last-child .l{min-width:0;flex:0 0 auto}
.kf-side .kx:last-child .kl-break{flex-wrap:nowrap;flex:0 1 auto;gap:3px}
.kf-side .kx:last-child .ch{padding:1px 5px;font-size:9.5px;white-space:nowrap}
/* latar hijau baris SUDAH lebih tegas */
table.t tr.so-ok td{background:rgba(67,163,90,.32)}
table.t tr.so-ok:hover td{background:rgba(67,163,90,.42)}
:root[data-theme="dark"] table.t tr.so-ok td{background:rgba(67,163,90,.36)}
:root[data-theme="dark"] table.t tr.so-ok:hover td{background:rgba(67,163,90,.46)}
/* catatan kecil di bawah nama / nopol, tombol edit jam antri, centang manual Status Order */
.catn{font-size:9.5px;font-weight:500;color:var(--muted);line-height:1.25;margin-top:1px;white-space:normal;word-break:break-word;font-style:italic}
.ibtn.ed{border:none;background:transparent;color:var(--muted);padding:0 2px;margin-left:2px;vertical-align:middle;cursor:pointer;opacity:.7}
.ibtn.ed:hover{opacity:1;color:var(--accent)}
.ibtn.ed .ic{width:12px;height:12px}
.or-wrap{display:inline-flex;align-items:center;gap:4px}
.ibtn.chk{border:1px solid var(--border);background:transparent;color:var(--muted);border-radius:6px;padding:2px 4px;cursor:pointer}
.ibtn.chk:hover{background:var(--s-or);border-color:var(--s-or);color:#fff}
.ibtn.chk .ic{width:12px;height:12px}
.cell-in.note-in{min-width:150px;width:100%}
.mo-fld{display:block;font-size:12px;font-weight:700;margin-top:10px}
.mo-fld textarea,.mo-fld input{display:block;width:100%;margin-top:4px;background:var(--surface2);border:1px solid var(--border);border-radius:6px;padding:7px 9px;font:inherit;font-weight:500}

/* judul tabel Dashboard: keterangan hanya muncul saat kursor didekatkan ke judul */
.tt{cursor:help;display:inline-flex;align-items:center}
.tti{display:inline-flex;align-items:center;justify-content:center;width:13px;height:13px;border:1px solid var(--muted);border-radius:50%;font-size:9px;font-style:normal;font-weight:700;color:var(--muted);margin-left:6px;line-height:1}
/* Status All Driver: angka utama sejajar (judul, angka, dan persentase masing-masing satu baris bersama); rincian 20 FT / 40 FT / N/A ke bawah */
.kf-grid{row-gap:0!important}
.kf{display:grid;grid-row:span 3;grid-template-rows:subgrid;align-content:start}
.kf .l{min-height:0}
.kf .vrow{align-items:flex-start}
.kf-split{flex-direction:column;flex-wrap:nowrap;gap:0;flex:0 1 auto}
.kf-split span,.kf-split .np{white-space:nowrap}
.kf .ps{padding-bottom:6px}
@supports not (grid-template-rows:subgrid){.kf{display:block}.kf .l{min-height:42px}}
/* kartu kanan: angka utama sejajar atas-bawah (kolom label selebar label terpanjang) */
.kf-side{display:grid;grid-template-columns:max-content max-content minmax(0,1fr);grid-auto-rows:minmax(34px,auto);align-content:stretch}
.kf-side > .kpi.kx{display:grid;grid-column:1/-1;grid-template-columns:subgrid;column-gap:10px;align-items:center}
@supports not (grid-template-columns:subgrid){.kf-side{display:flex}.kf-side > .kpi.kx{display:flex}.kf-side .kx .l{min-width:180px}}

/* angka rincian 20 FT / 40 FT / N/A sejajar dalam satu kolom */
.kf-split{display:grid;grid-template-columns:max-content max-content;column-gap:7px;justify-content:start}
.kf-split span{display:grid;grid-column:1/-1;grid-template-columns:subgrid;white-space:nowrap}
.kf-split b{text-align:right}
@supports not (grid-template-columns:subgrid){.kf-split{display:flex;flex-direction:column}.kf-split span{display:block}}
.kf-n{font-size:15px;font-weight:800;color:var(--text)}

/* judul tabel Dashboard satu baris: tombol Reset Filter dan lencana jumlah driver dibuat kecil */
.btn.sm{padding:2px 8px;font-size:10.5px;gap:4px;border-radius:6px;font-weight:600}
.dash-grid > .panel > h3{flex-wrap:wrap;gap:4px 6px}
.dash-grid > .panel > h3 .tt{white-space:nowrap}
.dash-grid > .panel > h3 .grow{gap:6px;flex:0 0 auto}
.dash-grid > .panel > h3 .badge-n{font-size:10.5px;padding:1px 8px}
.dash-grid > .panel > h3 .hd-info{margin-left:2px;gap:4px;min-width:0;flex-wrap:nowrap;font-size:11px}
.dash-grid > .panel > h3 .hd-info .pill{font-size:9px;padding:1px 6px}

/* kolom List Driver sedikit melebar di layar lebar supaya judul + keterangan + Reset Filter + lencana muat satu baris */
@media(min-width:901px){.dash-grid{grid-template-columns:clamp(392px,25.5vw,490px) minmax(0,1fr)}}
.btn.sm{padding:2px 7px}
.dash-grid > .panel > h3 .badge-n{padding:1px 7px}
.dash-grid > .panel > h3 .hd-info{gap:3px}
.dash-grid > .panel > h3 .hd-info .pill{padding:1px 5px}

/* Status Order: pil SUDAH / BELUM tepat di tengah sel; tombol centang menempel di kanan pil BELUM tanpa menggeser pil */
.or-wrap{display:inline-block;position:relative;vertical-align:middle}
.or-wrap .ibtn.chk{position:absolute;left:100%;top:50%;transform:translateY(-50%);margin-left:2px}
#tblAntrian th:nth-child(11),#tblAntrian td:nth-child(11){min-width:114px}
td.idc{width:1px;white-space:normal}   /* kolom ID Driver (List Driver) sempit: dipenggal DM- / 03668, sisa lebar untuk Nama */
table.lt th,table.lt td{padding-left:3px;padding-right:3px}   /* List Driver: bantalan sel dirapatkan supaya Nama lebih lebar */
table.lt .pill{padding-left:5px;padding-right:5px}

/* ikon menu di kiri judul tiap halaman (sama dengan ikon di menu kiri) */
.page-h h2{display:inline-flex;align-items:center;gap:8px}
.h2-ic{display:inline-flex;align-items:center;color:var(--accent)}
.h2-ic svg.ic{width:20px;height:20px;stroke-width:2.1}
</style>
</head>
<body>
<div class="app-shell">
  <div class="side-hot" aria-hidden="true"></div>
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-brand"><div class="brand-row"><div class="brand-icon" id="brandIcon" title="QUEDRIVE – Queue Driver Availability &amp; Status Monitor"><svg class="lg" viewBox="0 0 24 24"><g class="t" transform="translate(0.4 10.6) scale(.48)" stroke-width="3.7"><rect x="1.5" y="4.5" width="11.5" height="9.5" rx="1"/><path d="M5.5 7v4.5M9 7v4.5" stroke-width="2.04"/><path d="M1.5 16.5H13.5"/><path d="M16 16.5V9.2h3.6l2.9 3.1v4.2z"/><circle cx="3.9" cy="19.3" r="1.75" fill="currentColor" stroke="none"/><circle cx="8" cy="19.3" r="1.75" fill="currentColor" stroke="none"/><circle cx="17.5" cy="19.3" r="1.75" fill="currentColor" stroke="none"/><circle cx="21.3" cy="19.3" r="1.75" fill="currentColor" stroke="none"/></g><g class="p"><circle cx="17.2" cy="6.2" r="3.2"/><path d="M11.6 20.6v-3.4c0-3 2.4-4.9 5.6-4.9s5.6 1.9 5.6 4.9v3.4"/></g></svg></div><div><div class="sidebar-title">QUEDRIVE</div><div class="sidebar-subtitle">Transport Trailer</div></div></div></div>
    <div class="side-label">Monitoring</div>
    <nav class="side-nav">
      <button class="side-btn active" data-v="dashboard"><svg class="ic" viewBox="0 0 24 24"><path d="M4 4h6v8H4zM14 4h6v5h-6zM14 13h6v7h-6zM4 16h6v4H4z"/></svg>Dashboard Antrian</button>
      <button class="side-btn" data-v="sj"><svg class="ic" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M8 13h8M8 17h6"/></svg>SJ Outstanding</button>
    </nav>
    <div class="side-label">Data Master</div>
    <nav class="side-nav">
      <button class="side-btn" data-v="masterDriver"><svg class="ic" viewBox="0 0 24 24"><circle cx="12" cy="7" r="3.4"/><path d="M6.8 21.5v-5.6c0-2.6 2.3-4.6 5.2-4.6s5.2 2 5.2 4.6v5.6"/></svg>Master Driver</button>
      <button class="side-btn" data-v="masterUnit"><svg class="ic" viewBox="0 0 24 24"><rect x="1.5" y="4.5" width="11.5" height="9.5" rx="1"/><path d="M5.5 7v4.5M9 7v4.5" stroke-width="1.6"/><path d="M1.5 16.5H13.5"/><circle cx="3.9" cy="19.3" r="1.75" fill="currentColor" stroke="none"/><circle cx="8" cy="19.3" r="1.75" fill="currentColor" stroke="none"/><path d="M16 16.5V9.2h3.6l2.9 3.1v4.2z"/><circle cx="17.5" cy="19.3" r="1.75" fill="currentColor" stroke="none"/><circle cx="21.3" cy="19.3" r="1.75" fill="currentColor" stroke="none"/></svg>Master Unit</button>
    </nav>
    <div class="side-label">Data &amp; Info</div>
    <nav class="side-nav">
      <button class="side-btn" data-v="history"><svg class="ic" viewBox="0 0 24 24"><path d="M3 12a9 9 0 109-9 9 9 0 00-6.4 2.6L3 8"/><path d="M3 3v5h5M12 7v5l3 2"/></svg>History Antrian</button>
      <button class="side-btn" data-v="raw"><svg class="ic" viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 5v6c0 1.7 3.6 3 8 3s8-1.3 8-3V5M4 11v6c0 1.7 3.6 3 8 3s8-1.3 8-3v-6"/></svg>RAW Data</button>
      <button class="side-btn" data-v="asumsi"><svg class="ic" viewBox="0 0 24 24"><path d="M12 22a10 10 0 100-20 10 10 0 000 20zM12 16v-4M12 8h.01"/></svg>Asumsi</button>
      <button class="side-btn" data-v="data"><svg class="ic" viewBox="0 0 24 24"><path d="M12 16V4m0 0l-4 4m4-4l4 4M4 16v3a1 1 0 001 1h14a1 1 0 001-1v-3"/></svg>Upload</button>
    </nav>
    <div class="sidebar-footer"><div class="sharebar loc" id="shareBar">Memuat data…</div><button type="button" class="theme-pill" id="btnTheme" title="Ganti tema terang/gelap" aria-label="Ganti tema terang/gelap"><span class="theme-ic" id="themeIc"></span><b id="themeLbl">Night</b></button><div class="side-credit"><div class="sc-name">QUEDRIVE</div><div>© <span id="yr">2026</span> Transport Trailer</div></div></div>
  </aside>
  <div class="app-content">
    <header>
      <button type="button" class="hdr-menu" id="btnMenu" aria-label="Buka menu" aria-expanded="false" aria-controls="sidebar" title="Menu"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg></button>
      <div class="hdr-title">QUEDRIVE – QUEUE DRIVER AVAILABILITY &amp; STATUS MONITOR</div>
      <div class="hdr-actions">
        <div class="clock-wrap">
          <button type="button" class="clock" id="clockBtn" aria-haspopup="dialog" aria-expanded="false" aria-controls="asofPop" title="Klik untuk memilih tanggal: Saat Ini / Tanggal tertentu"><svg class="ic" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg><span id="clock">---, --</span><span class="sim-badge" id="clockDev" hidden>SIMULASI</span></button>
          <div class="asof-pop" id="asofPop" role="dialog" aria-label="Pilih tanggal aplikasi" hidden>
            <div class="asof-title">Tanggal aplikasi</div>
            <label class="asof-opt"><input type="radio" name="asofMode" value="now"> <b>Saat Ini</b><small>mengikuti tanggal komputer</small></label>
            <label class="asof-opt"><input type="radio" name="asofMode" value="date"> <b>Tanggal tertentu</b><small>untuk development</small></label>
            <input type="date" id="asofDate" aria-label="Tanggal tertentu">
            <div class="asof-note">Semua hitungan (Status Absensi, AVA, OR, aging SJ, master aktif) memakai tanggal ini. Berlaku hanya di browser ini. Selama Tanggal tertentu, pergantian tanggal otomatis dan penyimpanan History dimatikan; perubahan antrian tetap tersimpan ke data bersama.</div>
          </div>
        </div>
        <img class="hdr-logo" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAUsAAABdCAYAAAAsT0SsAABeKUlEQVR42u2deZxeVZH3v3XOXZ6n96Szko1ACBARQVBRUUYFhQyDoga3cR9xRURcxmWMGXcdZDAOMzCjiLuJ48woIrghLog7YAAJgez71uuz3HvPqfePe5/uTtLpdHcSYObt8nM/mKT7ec49S52qX1X9CiZkQiZkQiZkQiZkQiZkQiZkQiZkQiZkQiZkQiZkQiZkQiZkQiZkQiZkQiZkQiZkQiZkQiZkQiZkQiZkQiZkQiZkQiZkQiZk/HKzEj9Wx7b2Bkpj/R1VolH93OrDe+/bbhvb2PQGSktHObbiPUY9vmsew2s4pjldRctjeXyqlB+zc6cH34+rVo1+3x1M5NF8uauU8gtB3yP4P3BGWTmmVMVOzqiVmpm+Nybp8/wu+ex312TBSbjFC6nv/xlLVxAtu4TkcMcym9e9Qoj/KiBWQ+gVPIg6Mg+i+Z9RcE4QLaZPBXWKZAawNPc6tt4nZN6ReQNOaapP4Qlru8i613B5z1jHNZdXPLFKyxtCwiAcco48AP1D1s/jSVD44xb+/drRfPapXDxtK/ZyS+cMpSkzWCwBFiMJvb5ELI6ND0cLvvXPa9YcOPdn8e+TH+T7lxqmLghokwCRABGDUUW8w2uKU6XuQrS/jfjWVefs/gW331gbzfgWsSjaw+kXhUy6YOCVMQPvq2QqWJOQbdlO9i/whe0HHO6tNO/6Js9ptjy7HhPHMSEJCYonIEhCQpuSmvzlw2111vmEL5zwVjYON6bj+MjZjvWvzIgtlMQiNPaDJ/VKpgZVxdbr9Gw/mfOuv51X7Brteh/DCzqVGW8XZKYllCHvCUix3io1dq7cyYrbYLx7f2nbfDa9M6Tp2JSST+nLBuYMxKAyuLOcelIvhL85lVO+fcs49nFDJvPSp0RMfoMFDbGSf5/q0J/xA2s8jLIuflYK1eVJe4Tm7ojSxgC7wXF/l6Abn750Ze/LP4x9ntB/pPRV8KhpynPmlT4gT5/2MfQcqJ0bYk+CsFmI4xDRBHUJUQZn7Pi7i07/jTLrtmk87u4dvGGfA3EkFOVlnB9/l/TJQnixJTWQAYqAgPhiAxWXi0vzP/vGX4igBox3VDPo8IpXwavH+5Q9biO393tk2zG8/hdlZn8/5nGr7+OSvtGMLaFzhiW50CDNhq6BTWUAIdTBTSRkRJqwew4s+AKsqR/qs7uY1axsXWzRWUK3GEx+9LFEiAkoaUJtx+Q1T/5X+G19f0vuk/LzFsG9JCI+BroRRBTEYySfO68Wr0qsirKXDU+dc/uWJRthy+g259Vhwr8/t4y+MF8HUcGLgg7oTgyO2sMx675chwOUpcykv/uzzC+38JIgZCoGj1BH8RgCo6RiyEQwQDjDs7ZpB99GdTsiB+wtw5YTlOjimDj0VBVSbSjygLBYi0wg0ZBSsobvlc6Zx8dvX8+oLghPd4tSfn6JSXPswN/aQkkoIApqhez+2Vz1801cOa49fzbtUzax7fVK2GzITIjPDq6gYjxN1Nn15Af5y4+AcSrLpZHwpwURk19sMc6gCMVWGbwC1eynPPcVZwZ1OnisE5LEsZeU/oqnTRTX85Nlr/vNb5Yd/1/nc9ddt7By5yolOkUOT1c84spyEUujDfxgQevt896mBM8Jmdxk8E3gLFgEjIAKkRhi6+mf7UhOzeh5Scb3eubwiv/uYf2Xu/nlw0dqTMtfXZUZNxqNMYEjM8XiqSCqBEbzG11y5WiCfNc27jZBUSk2caxUnOIa50diWnHIVCWY6fCP62bNqz333HEMl3+2CX//GpaPqNQMzgiEAanxB26cfVyEkFAcQXjOOWvk9tsP/d4xsyRhTymCMEHU51YhFiMOjyOxKfDbzvcru1+wz+9eLtRPJDE9YC2mlGGzYjRiEFPoc83nJfGWUmDoPDHFv3QmF/7bVm6qHGp8e3ieVy60ig8YsN4UwSgoiqrFEyBxJ3PNluH9xqj+GRFSVCMQT4DiUDweKyGxKJkBgyH0hmaOJTiY19VNvytRyix9kcdjcutIAPF4EVQVbxRPSFOcYF65df3nvg5v/8to9mKMF8UGAVHkUfK9NqCPVUEFtUJkp/NSGY+yXMSS6EHuOqdER6unpg482HCIZaky5PUtXizN1tF2jNIxF4a3ug8tjwPuFpBAEasD52eoYvaqeD244nY09oLkl6ZC1Cw0YwjLEVaUpmmedHY/D59/N+G22bxx+TOk42b41GEpS3M0FeM1N++LI53DbVN62fP6MvNWWlpeGFCeKmgrGGsRsShmwCqr+zpdSZ26V3wQUOosMfdYQ+ubYubePIOXvmkedOyPWel4sIkbp3mDMYK1HkuG+MYDHsGp4Hz+CAYRk/tEIjgxeAxOBIdFTYAxAYEJCAQsFuMsGoRouUxHZwuz/9rS860ava/s5N2tI+MkqYIXCvfWYIvHqBRzJXgFr45UFVFuP2dUr72DO71SIsFKRCkIiYwlMPm2yF2kkHbY/bVhN69ltxfaAK+N+fDFvwiBWmINKWFpsopRD9bS8eaM+TNHM77TuFACwkxATT4Jms9BgCHEEqCIKKEzVP3wEyiJb6LJRTSLxRrwxhJYSxQarAVrDTGKkNCHxT6Q4REZ9hILKYnBGogwRMYQWkNohdDkCkYQrDeEHmo+ZnJnnXUfYcnsUWF9dTo1w3oP3uFdvvhGDYEKAZLPo0Kof+BFOp5zuY31pZDeJUqqHoelyRgG/2cxMvTPHiGhK/MkUZ2u86dPp3lchtKiS7C0uXy/qprcP1LwvvEIokO/WxA/9LEYtQi2OIeCGHAqKIbIQGyEVA1JFGA6ItoWgv90M5u+8Ey+cfJlh4HTH1VlefniHOe6eTXxkzip8y/8y2cz3PsiZs80NMeGQCBVJVGw5EalxWOK20YHcArBIFgBUwpomQblZRVefMOpLF0IcMHQy2vMN+1QM0IO8aD7PjrwyMCUDn1kH4QltwZtAKXpKfWlls2XPpkFbYcE1kE1x061Ydke+F14g7id7ByVx1AqxisFFiaICEZycKzxroap7Dgk9C1YaTyDW8sUjgID1oIiUx2b37lgFO/MwPgGj4fB6L7rlO+POpmOOLwCI9jnb/a3HhUdxYEpXkxMMVdDwP/GOtjCdc5HnNH17PaVZz91dMrSamEuae56NzDRwf2khxlqaOGEZqXlVEO58Jq8DrNvZfABg3qDdQm7nuO3L2k9zDARZsB7G+7M7LOn9zlkw/37sKcFQQhECMKAjlah89w13HbtTQs/duKKFUQrxmFUHVVl2ZA3L7yhvIWzPh/Q+byQaJKhJpbQGKyY/BaToRNQKMUhfx5UUgJExDQxq6WJOefs5O4bnshVZy2UXDHLOHCJ+1iZmFxBD1GAoKgoRhQ78BygKw9YuKGbTEQZ/J9BJL8ZcZaAMrPaAsJ3bOTUJady3jhu64Yyyh9FjQJtPM6N5rfbmSoGKwGBOXisT0dpvRxwqeyDuBlULKEPKGlEy8WWs04Z2zYdUEKy77cawCgjGAyBB6sMqNnDEY8jx6i1EWzQXJlJ4wIbsh4RincRLSbGfWA6n2m+eTXxDSNkEZT2OfBHXl69lFKF6DTLpOYcMjEomT3UyhrCQBBrCBfGLJoy/vkTlRy6GqIAxxqT3leR7yuNeIMRwYrBKVS8IcPC41Luv+oTl7z+eO57jFmWAAtYHXfxvY9C8FyDKwupCykboa658oiNpWQOtkRywISoBvjQ0OUtiY2ZfdJOVv3raZy/6PDuuoZl2LDadFgzU8nY9/EDT754+yuwoScpMoY4hNQaMglIJWZym6f0zh3MP3l8y7ePslZBvGHzqCzLNlokyF2ZYeY5xxsFYSeRHnoD729N73NEpLBaA0toY9qbutnx90/g6o5RHhBp4MYHGn+qBtGRcpgaYO+RTf0QZD9/ZP/10GL/WkphzKTT+vnpyxcvpP70vxpZQejg3jtisrZIq7lx2UyT0f+CfH+nDqwXAneoNzUEYjEmoNX2csdiWFJWHbt1ZgcxUTXjekUrDcs9f/afu0wUJ2AMWCMExiBWCCWkuRTSctoOqu/88CW3tDzmlCUsP6eJpktiQhsQqUUEHJ7AKogjU0i9kpiMPp+wty9l74OOrjWeSgVEM0qSYHEogjUe1IMXAjWEgaE0dzsd1y7irMmHpzBzy9XisSgWrybH4wYwlNyVNsUGClUIaTwQKHj1JC7D+RTvLUjjkTz7yOc/G+MRVeoY2o9J2HJpOy+fNIxdpGDEYjGIaTxS4KaDj0cQr0BGNqpduBP1DiMF5qOmYachIgSSkPo6iX8a55rhLfIe7wq1ABmCJ8wDPHLgcUMlv1RUQQ3znrGN3z1vwWWjwZD8EIv+gBNmPJ469qAKyGgOvInHixauX36rGMndBwo3wBhFm/zBlZkpXG9BtBhUgZvuvx5IiBdFxFNzIVFQpuWK5/CdzoYXNJzUqAIi+T7M/+sHHMs8wAWqHlXYOmqrbL7k0fjZTCo1M+UZOe4uYsAIiRxaTThvsRoSShvHv2gGK1tknNHlwrKUg02zouJwmpJ4h6pDxaHGoyqEIlgLRgSRgLLkqVoJDi859inFbjGA8YJVmwfGvGBCh/zVFr72tMeUspzHvFIf/R8MaLP72SEKxjm8S6m6fnbtrNK1vMwx50X8fNFln/rSs5ac9aVnLOKZC2H3OQlrP52weaMjVU8QKJlTUpdjnXUEj9D8xJ3MvOJIWAz7Wgha4IyN0EWrMbRJhpKiJGRu6COEgaXFBAQmGtZNH3ThC8tUAmIJaH6uYeesOzaMlPRr9rNchxu8mnnMSx8JeIVSLB6/H1Z0KAsYDKmJ6BDwfz91+a3thz8QM2J2vDdDZmc/IGW41R/ZjfTqQD2qfmCvjG6MEVNmruKLl/MoinLubEhnDCLoqm5UUMsg/u6pzSvz1kksXXEYid4iB5vuIX8pKf07HJXVjv7VKdUH6uy6N6FrVZ09e2p0JXV6Q0eIp6lwB60Mevd+P8jMFdBJMMnS9cLJvKJtLCM+qqlDGc97qiU+3SAppCpDXBfF4RFXZ88PLN0f2s6P1m4rfu+97218wqsA/gz8+Qw+e8MW7v5wChfFaDx0Sg2YkHZNqb1lDi/95ka+ee/hKcuhuJgrtIArgNOKS0lMRveXUuwmSIacFjEZZqGSnB7SOi+kvQFyDqtQdOD+81Kms83hz3nflawb78AHjSTcI3P0KjIedzHAqiezIZPmb+Irb4MlH4WVyXhfW1BfHwGRzMwQ21T2PZEyRsTMFc6fH8eLRzRbT/Olp/LPKz646h2rLznl4JaZ5Bb6kUUwF1wTp2t+c1GZSdYgeFQT8uB6PAbDyRKUM+xTWXnJOPeqDlFoMmx4pvFzIX1v3cB//mQATlhKaf4yamdz/tQa7aVtdP5Nha1/G1J6XEgTEOS1ILhGfrQOhdo8npAWa0ifFNN04h743aNuWd68mjil+grBOUjs/vgg1PqU7Z+3fPBN2/nR2kN93ns3vLPrA0tuvFJIrgFbF2IrRAJxERCqu5CpUqX+3sOzK40cGKjwRaDDITRZjzPTOW75W/Qb1+zkPz81+Hz7E9t0+pu28/DTPW3vrNHdNRgg8eRRRzfkMU7RTOlTi4pQfvafVi6NABrROju8BXmwECCCai+9RwDvGsXxrDEc1jmKw1YOoDuz4BL6Xhvzm9mHMU7NwYNUD3k85fBeN3/l3AXWfawXv4/teeDfNfZWqhFzSlv4yYdYNjZEWo4AhrlozQOhp/YcCEQGba1hLEuvh7CRszqbX9a+eXwpRPtfCiNZ9xWa9kmWn78shxN+yS07f8+3Nv7D5qu+tIgrLoLqrZ5sSJYI5GdscD2UDIfHUJKQtkkGPeYx4YYvXnwzQv2vAlSVgICSgCdFfIK4jPqPN59z0ifPv+00d/4C4sfz8kmzeP2pM3jbBVN4yeXzmH7s0M+7ZC7Vt62kbzNf+FydPSs9NlUSk5KpJ9IcR6xrTOfz5nDRmCZhEUsiRQu/wDEUd4JAIBSKCH1G4hWjjl1u2XCYjSyvwz39jotWVNjy0YQsS/Ga5S5CA6AWsGIQG1CyAS0CoYkITurgzhigYXVkJEWk2hxqrcSCAaGL1lGta1uBfTZ0xdBjrtSLHDdjurhx2MMziZkYrIIVj1XFFIDFyG5dncR5mgyILTGjdTLP/ND5w4Sz/wAFDiUFnCwD3rPHaYrTDKeeTLvYIyO54baAnYv8JtMIpZr9krAQNLQHV52tiIQgFi+Sv63u6+QPjdT6/Twt5wOwJaafe8XKi89bteLAAIkyTQQVJVNXWLJuPwuJAlce65msUZkZ0vk4R9VpgYk2EYZNRGFeSBA0sETv8d4PuRiKBPICjzZhyKQTZ3R/on3BGGry585FtEgPzI0Ir4rTfRWlI8fOrRggoH/EvfTGWVRu50m7mmm7Akg9mTq8egyeUCA0FPmXluYgphRYnAEt1dj7OLgsftSV5bQ1f+oAJuVArJJRxYPmiqZv63RO+ciKny2TG5/1ZXvvmre/dDv1WxKqP4TsW4bmtxoumHow38+y4JqUHbuFZm9yp8jkqSlGhNDWCJ47Tv/7iE3Jdp7X38nZtyi96wRxOaidHioKGmZoMP5bWjxI5lkfHv4bWNVC+cLU0WwkMUVAwox+wlUQ74kuXM3fPn1kvPboSMM99zKcLXhkxRJYQz2zlE2d0jvfesnSpsOwqMemLBctiar4cwSJLfsXgwlK4Dy1fuC3Pk+GlxHMbS/QXqfrCWvefv4RniU5eA7aCHIsv+4SdM2hvCXNYQeveMmoNc1mm3nUlWXKbWdYQmsxRlDxGBwWjyaG9H/+yHseeutbsZ386PkpXZ+MOOaEmPbIkjkLqaHtoHt3Ix97yJN8o8D7rMWrYMQQWENoLZPOHstYE2Y0nLQjmq7RxKa6IHt8vgNUD7nBPUJ93PCUR0XQsEw5OfzRGyQ3VqWFU82hbpmhinL0rnn+cwGtpo/6hz4+97pHnNhFhwZ9jvK35xhkYi3WGMqnrWHnqx6p9zzjvnMDh7vIUlJBgv3d7pTMOOp/aGbadR5XIce/D6L01VhKUZXNl7D8deERXY/Ckh3rUvRzpvFknYcAltThCmfce4eplDjbP+rK0lJuNoSSJzxZDyURQvXU6wHB9wF2fmlpi7Ln0pD25oDEBIiNKFuDkQp/PjjVmK6OS0y+WanXBOfzJFRVm5eFicefPrbRPkiRQH5IRSVjUAa7WNup6Il2oH58eBXXeARJa6T+YAs9tFpomGTxopYdLVMek8KVfQIJjdpcEbAesvYNPBgf+jOUnBhBh9hojWIUNwyW10jVx0CvGjpO/S0/fsWKpfuueT7XOiLE6B9B5epHgBl0oJhhf/dy8J0Frwb1FjSl/+1P5QPzipmKR7ta45EuftppCE/J3dt996/PeQCqSvr7gD/d5XFbyC9eOYjSUCHy4M+Yz5Yx4pZuRIA4x1JFPepHnbC+SKPVrPtroTxj3x2iw9uWqHqolZj18Bourz/qytKRdQgt6vCaYSXHFusoopvZogAnVXa1lWibJdR9XmdrG9aJCWk6eDWOLKy30rKuTlL3OJ/l2Aoep5BhSMaYjnIBQlhE6NyICxRirZAKnHrQn1m6ltJ0lk7LaHlbRMekADVBgcMcqHgb6SiqNfrX16nt986Z5LwPmdtXUx54YFNQj7CB1lFtstrgAfemsHvyz3ZeEQ0w1mBkW8vBqtv2omQKriDPEKMF8xAgHtGERBO69mZoluI0JUWpKzifHzrjI0qEiLeE71i+7OIh1sFNlGkJG07yYB384CFWGrmHIyu4BqXHcOZe8WkqinrB1uoHzyZIC5By+I8yHqzx+DRh124h8Yay8TjJ58VrRhmPSpmmIKLUsZ5171qyhBYpci9jQsmLNcTsezE2LlQjQiA5xLt+1Du8j/AsS2lqgJOQyAoZkGkeFHHq6O3uof/Xd/GdrYb6nXk+sHcBplFON0ThOBVSA+XpvfzqpDFeNya/SBpVNkYOvIycz8h8hiM7FGXq5AVtc+678pyQGR+xNJEz7dki5qD77JJcNxinJC6D7pQta8cy8kei3HFgZ5siD7CE2BzwzgJFw+Fuy3SEpI7b1lK6h6t2CH6zkvlH8r1y4Lsu0+gqLb2MNs6aXWbevBJnzS6zYEG8gPPbvjb/DbMcd11lmbRkJOAnt0CMQiSKxSP3xJTq/K8XLYylXMk10fYRR+1WIXY2d+91sGZeRCHMrduW6RuY/ZYbluYnxDNdhuK8eWqUPKocrHY/K3I/vWvApRndO6cx/zUJlZ6MRPI1znQ/61oMoXXo8+9a+fkF+xLX5mWr+wbcDhcCqC82RJliNS8EsTTCWj6v4trbzJP+svQ2Mkv2W3D9nsA41O//rjkTl/qAIBAqfzuTM5qO3Azn5Yy5srMEBNNm8va5k/m72ZP5u9nHc+acM3jl3Cdz/fwT+eiZs/ac8/GEzV+ytHcq9UN5UCoEEUQBVO9vZ/pDYxlZcLSVZMNayfnrCAIwzTRlfUAz7VKhD8H4fREjcYI9qHf1rPl5+oCSqFJuRHSHECyY7OipAecN5foG9r7ioeVv2D2TJAOPrDc+YFJXPxvO9vQ/K2LyDIvVnDj44BCMR6wncxnd9VYWfHsjK3r/96tKVcmtBwGvXayrtnHyZ6rseqKldebgz+VpWgEhjj6jlCSh/+8+uOztK+Fz9zzlou3m999VOzQKXkScH211WZTqNeCKgZ1nwEhGUunl4fszSjc4et8SEpZMQawx9KK0CCGTSnv54/vfIE/+O/htLc/nVaM5Gdk+/KV2nC8+iSXtEeWnWoJYiii3yauOyFOvxEN1w1aqO5Y9i9pC6msqZD2WlhYllQPh3NBDgiXUEpOeYggi+EPlSMxuXlSVr7MgmiHXeHbXIsgU1RqLZCsmEH4XAuWAqBQyrYjYp0A4wr4UATJIe2LqK1bz9V1jG9tR21KBDg1oGdTmG0yc0lzNFUVq8kwO0ZyjsHGRiosJRoSizucVbQ4/GazRAVIDJS83lO2HaT/qwZAwR2YCmrRG5TJP+A8hrR8Naf+YpfXjSnZtwJxXRLTPCAklIGYwnaQxGYP2gkfF47OMmvf03m3o2nSQG1FGg5OacR6m4aw1HcwllJFv6wYTzsDP62DhQa7klI7Ydn52jaH/m0LYR4FuFlXHxhCqokbwxhBFNba+fzqfaT79dKzLWd90aEBIh1zEjeDSqO0WPaiZNni3u4Mbcm7YHEQdMo9iIJY1BHoMz70upXuNIs4QqA6k4WhxNrwRJAowZ1V4/Dm5G94iuXsq7PuOuS7O8WQdowLae6YhmimI0zwzww1uFQG0EtH2A1hWA5jJzI1gChf1wGR/zSECYzDGUj6mlz0Lx2dBHojbF3wEklNQG8p0tDTTOb3M5GOamHxMQOvMgOYpIVGrJQoDmord5Ar+5uEw0sZ5FvEkmZD8YhJ/un3sivwoiVBOBWMa28jgJKcPdMZTrwFEhBgCm3PSgQfNcD5/khGrUO7jtNke2+zJimlPi9n3vob+6OYx8tbJwBncJ6FYDpywUAVjA2JyWkTE5NE7FVCL+JCQgACTM6gXR9QMPaYFKQTqSEno6xHa/rWZnXuGt2TMqID9AuuSucwYlfKoDwzFD69CKewb2TPszVEtIvd5FEe0uPTIMTUKgrJAFSMz3kZ6DFO+VGPvNod4n+chGkE1ow6UyXEylYjmc1t58Nmsg1rDidjHas2PRYCIBWNQ0050cOvdFoygDOZVDtipDcAz/1CjCul20kO54UMdqMZHZRifUks9ykzgD7xyq8Fck0F/hvVSZBA2Hi3aRlgoG+QD8PbpdTapJbb5+xnTeGRIuazi1FP3MO+Qa3zDWkpNHPsCIRCl6nPry9o8GJjjlo6kt4v47saZuZ1r9ziy30GlSBq3++2zNGdpRRXiOGbaYs45pzQ2RSkMEv8OZelyA5SMFIz7OZ+nLcg8rAZYDNYEWMlLnoWc43RoYKdBM2dcjjkE6iFL6b8/wH/6Hh7e8ZhRlsNMkDSIPh17q7nOT81+Vo1ozhXozAhu+FV3UK5xz3MtUWSIULQwqspWSSSk7efD9es5OpfCQOigeBqRXzMsyjEEclYlEMWmULkF1v/ivnGX/B0VdFZ0+Kj7gJTztMgGg7n6AYuU/RKNe/XMFuxvuG6dwnVKUsmtp1CHq8y0xEGFynv/68bzJllagsHLpXGI/vdIBy+72bHjzgONVY+SecGq0BQqTXOmsum1AT8Lck/pyEAN75+/2WRUnqkDLFIGRTXDoVjnyVDSnZ456+aeMDDIJKD190q6N49K+/0wywa3gQqgDv/sztuvCI/SCWukVBzc3RvJfCCIPKFLqCc1uu4N6Pzwibz0gfFBBI+YmMJFTn1U9IcRknCQ5mAoLZpx8QjpL59+2otnOPzrLKUoJ7+2hXuSoHSvg+ofGj+7Sg+/q9vYIhsNElqLDFOq2MBX85ujnmbs+m07s/9pK7fvemwdc0XwHpwh2jSsEq8N5ITminJQYe4rISWuenfeOGprOys8e1d5+uqFSzocbqWG0kl7mf+ygOYgJ0p/dJvrjVce4AW9HehnHd17h7948ixPgy9Z7Mtbed2xHqd6BI7mklVEIV84WSjNHvwu38BMnSLW42qC3N/L+3cP7VHjSf6iJNv8QGnusD60KHhDy8Iyv5h3dGbQM1IJ6aF+V8myjK7+Ktt+mhG+Y8MC96tbWFx/TCnLECM5ZqWF2dcwjTMsnQ6gB0KPsX5IUX1e6eJScxDM8hje2ulJ3xPTOsMQBLkZjipKQndaxl+3my8OBEnG3qRon9x0PfBfi4K+AWs5T38YpLgfrC0f7DFi8HjNu+SpV6w40rqj52dNNH3gIa49VOWBDtzlg/mJw20NdahuYNsoQa16oylbYRprEbiwYlD1GPGIsPuB3kN5DQeSYaoMsjbBElbll1b3jV2WaR9J6avmZX04yHyeEiSYnH9QhCDw6GtjzEla4Jb70UrIwPVzCBDPuMGR+P2JNMwQ8K4wu0b6rByzFB1oVzdATi3IQBM7kaE92R7gP+/IqH7DkXhFkKJbY86zqBgyFZyJmTStRu97LUHTcMCk0khsBzuKo7u4GZOx/nxLPAy5r7N5ylqalWm59UCI5SfbPea+wR5ATgcVbePANiCR5lKdDRcuXXpow8QNYK5aXMj5/h58zH7lOwM0s0JRktzgWVC8P7BTwb5f56iuyah8osrJV+xa8R9/Zs3ycXucR01ZNhNLXlcqagduNAM47edpDqAPU3IEovvopUZvmaZ9NsuSJUTH8cq5CV1/H9P+1wG2JBjNJ99IRkRK769ms+Dbhw8XHJBaNkRZ5nXiORen+iL3D4NKgIrFFsm8WfG4Bv5A3lcly+p0b1T6r2smfs86brh79FpcZCSWaD+ODBMtElN0sLWA5PmuBo9RHYEnsjy4iwfaUhiGtqBqUHo5v5JTBi6tDWfU7xLsTYqrO7xxOK+oz1nzrcnxqEAi4mkh5rg8+Gd0yJrIfjCIH80mHwCW5SDYiCmKlkcI8BRtb1UG9kkwBFduILVWGpmpDUIU4eTrE/p3uCIKLfnBV4uYIMffjCVuMtinWKK2kfN9Rfwo+JJ+9hoQ0r82g+la2sDQ8zJ5VQj7y0zavIAlU4/j4mnTOW/adC6e1s7JZUvrevCp7JvgLY21KBS+GCJviJ75tWXnj2j9b9iA2iHJV/uukBlyFQylQxcGI6T55dTIrdV92m0M12ZCVZF7tk/91pcqfHwrl/wv6e6og2a0TqXDdwMx1eaMcBgXK/CGyAOsuIPyR57296Vfr9x9ao3ut1nazhaiMoRFIy+rGeo9ld3NyEd/wid3H833SKl6JfAea0BMgPghfLKqeavW4mLMa1AVn3nqFU+yBeQXTXR+t53+u/7A9d38HxZBxA9U8QyRP1xfMbzsn2vsfUbMlLmStzVQyYmBvRaWqgxE04+uC655AqcfTQ+ewpVulBXJvnvcFHyKRpuYJjBIiLKNf1g/h0uvSej/mBKLxR3AFF6U+akeIczhjttfOR+iky2RgZrff20s6h22bRcP/iNEVSU0QowQSQrG4KbnuaN6iHVWUdLHhzxnBtyy/nDG7PMKn0ZKlnrSuuLTYi+IIw2FUpi3HXZ25F48WEfltAU7r+n4gF6evVZG1474UVeWg9W3TrrZ6RexIqpwc7MhPqD8yiCuEQ3/26c9c3YH694O5lkR7dMMYSRYQ96ZziveJ+ztttQ+spb/+u3Rf4tmyagI+Ac81UpKkhaYGuSKU/O8UfFg+i3BQ0LQ5YkesIRrIto3ruFT/6eV5ODFkUcyh6ud2cw3Huxkyb+WYRkEZY9zOcu1UyG0HNojPjLr6QeUpI7uCw0HS6eShoGNBWYc8O+TKX9jK3uWZGSnQglbZA0UrZRlELgZHgIaq/Sy+1kxpwQWMW4/ZWkxoiSBJbBKx1lCZGTIpU/Rm10hO9RQLCIBzc27+dUFwL8dppZQzduQOEVtja5/csh9QphBRpn2BQn9HwiY3iz0KCN6/iIBrTP6WHvWa4VvH+58HjVlmeAIEGPzBs0qRbJp7m79UuFa4JWlRv5Y0UsFyTva+D42ZACtzJxlKD/XEkwVxBiMF9TkPHw176j0JWz/xw5YcZiLNMrLPPNKXVpoflcfu9bXSXQQCTNaw2gZq4LVkDjtYGo14cJ0zThB5Ub7ntFYG3nzKZvBoggO7XK0EkjXgEWjDUu9wJaUQZq64WUPMB0tlNwBXq1YEIfHY/zwiOlDK3uxryozeVHRcsNYShSBTxmKSx583VQUteNW6vlgjeSv7MRgym2HhKeG6LOcDliKPRAUf1shPEDD3M01XXN520dr7PqqJS5nUOCXokJoxsLZbEZB+xHQdGGQt0d2+RgH0lRx4IRAg5xxCIZkGiheZQB3F7uf8h5GcxorNDkhfe41C7jh8jXDZ6LMnYvsuk/lwL3i97NSbU4NRE0dld/s4qaBnMiTeE5nnWlvAHOsxRg3YtTHIJRiy/ZXXsTTbv0udxxWwcdRjYbnvaTz4IFgvAwB/OcuQPLk1kYfk6E4hfpaUcZoKdmIZmuITJHDZxSTKaE4kl5BP9TN6m+t5/Zxm9izaJPRcs7k+aJGytQ2beWmDXv54ca9/HhD/vxwY5VbNu3h+5t3890t21i58z6u7Ru/ohwOWDuUC5Pi+NOo3qWJyRa8PfCikAZ4mweAFi0d/vpuih2Y+v4VSkWaigzhxD9g8ItYFX3s5j/2ZrR9PKNey7to0gicyKHbne7zjcI4PSwhJ/UswNU8c9mOdC81ghwD3pAOGqVaNKpVtrJz2AV71rzP32EJb1WCAtP3RZ00Mro2r41AycgyjyUzFHMaJDpIirsPlKCDvaRUc7g2Ty0a5DCQoeOSg48py/Ke3eYp315z8Tj6YA3Ns6SBS4oWanyo/IWf7La0fl+pu5GqdYac2DCgtOh+pp18BPTZI+WED5j4TN//XgeyAld3OQFDZgbYdzIcWREhCgRKJu+BsvuumLbXG85dAVsrTIjkaVgu6mLdqHIsEqY25fPqD8PlEx0Pudl9nJJccAGEBLcp6R0O4zyCz7MbxvbaGIXS/4pF+sg6ZAYnXe3o3zWk0kmONORQZcZTDE3RYF5eo66+AbkeEFPQRsK7DnKF6GhuaYMXi6ghblqLPecw4RtM3qDPyEFylZto/m5CFx5jDqXC8pKq9kn9NL9wyZLDSyM0j+xhVlFEK1TscCaTLxKazT4YVwB4cmwzSj17+jzbb5jKvNc8+4Z/++VW3jihKAfMq8goMtnTOeNQP7yERdE2fvIMT1YefW/w4S5Bj44TX1so1Hdybd9kOj/hqPQ7MuPHEdvI3QKj/xsWaa5QXcD3HhS6vlr0eS+wwiMbw0rZ/XwoWwqLscHcrsNS3jV04tB8xqHtT0a+TIUAIfMGi8f8zeEpJIvFSKEwZXhP8Dt3CeZ+PeSae3JGM1sW2s795cq3z3hMKssKzju8+CIHwuG8Bwc2XUMQblizRC1NonjrC3it4bJn1LNygQGVOA4oSZ2eWh/rf9pHeklC87J7+NjaG197eNGthmymRwcBej9kgxy0mdIjdskE1CtKkij9mGIT5Sk6MswY6mlAaUbAlCsv5tZpByjIIo1lEUui+7nwnIDyW0IoG0IjBEYwkueDpmow4sF7kuz8+54iB1vl3IWUYeAX9XlkwGhUBDIObmVe/VvHzq/GNEkL5bA44IUizg7mhmpOzYc6xHXRd0TWRBWtuZGYohpr79mXSSi3sh2qKaqtI3zHSu6sWoLP1di93hOpI/OHrm8fqJ0u0Ol9138o9+vZnD2pifi5gvUZUsxh6gerysrGEKB4dTgcqQoZgy2fG6lNToRMlKxoMetFhjkT+Wc4A3hDx9N5wg0dw71BnkycyYHg72CupOLxpN6TOA9+uNr021lf8+z4RgVXyz3SAWLtYcqT81zvAD2mGXvhDTp+F+QoHnpnG/mTRXCikVbjoWR6wKb0d+R5fUaH9jzMqZkyl7sTP6tWufvWdsqvnspFby5deMMfhyadH1moYKBi+DFjiZTo3OxJQqF9H9di+Bs+FEvcInRc/Bu+dcM83vmyJ/Bfx7Zz7aQZfGzqqlM+MvMElj1lD/aju3ngXy0dU3IiKDcsUYdHjSd7uMpN4Tj1jrIfgH8wOZbzr03ZvT0lcIOWDYwc3Ck4//I83iNmmmn/wTeAHWzfM4JaO/T+2cTKPZbyZzJq3tKMFvt99NDDAajfQEDvITqeZLAR5EmyMrBlGhaiI6FuUiqS4UwGJiMgQ8gQyQiMIw6KxzisVwI0z1gYplAjz6+1ecfISa133/p0DqIt3UHV0L447UhE3EuXEHWw6NaMbpdXgblCrwxnAYvNrR8tV9l+/jtlSTzefXHUouEZLhBM40UKEFtUCGoQ1Texklm8IrbEvri5hrrenrw6jovPuO2uO/9w6t0VfpCtYXmdm/7/8auXKNGf5E1rM967JWT6sXaALORgN5+iJJnHlcCemdH3hJ18Jy2jCYS+G20SgiBmWpxbDaWSkKRm+HJCFYwEpL+5nWv7xgO75OwxanQUmObveevGubz801V2/1OJQBvWZd65c0RLr8hIL2WP/orpkFLW7JDv3MR/fbeX816ZUT7L4rw5QrZLRsc5Qe5um+LSKqhHEEhUwaf0PxQSfs2BSdibhQPVaKHJmbyMIe9r70KCEx3ZYkGaPanJGcXMEGVpAG8N1gWIa6F0YS98/2jN8rKVJPCZByex5A5oflYBI+hAbcT+dzYZQiCG0okzOP7UvfCLx5RladCgyP8Ycg036iecnDodA1ZzQgW7PxMzFR5WgOv/QOUe7ulfU9STX3MZ8f8vynKlkLxi6UINafpSRvehLA8x2FAoiaFEQBiXiNsiWjpLTJ4W03FMTFtHTNwSYUoGCQ2aQWINwXDOlQG0g9njup5ycpNGH/PRBYACdqyosvvunPDXFbX15lDfUwB+/rFQOz5Qmjca1+eVN+ytt9H5YUdfvz9CR/FpvK7VUfvrnFS7kdwsPk+WzxnXPWndUV35vDP+/brZvOrac5Z86/rygm/+a3nB168tsf3zJZo/t5n/uHrBOV+65ulXfPlfZrLpU47ebTn/qM84oNOkMSa3u0xe0aPnLuWytrEyf41Fbl5NHDLlPyG1ivcF0bQZHjZzRVPPpmn9rH3B0nnjc8WPyArN4/Jj5/HOT3fwN5fx6leX8l3TVPLUvCMlT10ITO4VqNLZ57PtS9KAaLtSD/ZnO/dkvo/Thv2uy5cfeTahlD1DCuEaaRLDlxwXdctGaXlEDueyZSTwjK/1sHa1J3FKYDwijj5vKZrrDj2lYAKMMVjJa72tGjA2zyggr273RZlEBoQ+yxtWOUeCISNADJSdY9tdjsfdcfDR9TOSMtQBnkdGlQf5MD/urrDtH/vRLKNOQLPxhwwucEjL1RTsHjKalHPBdUw9+LkwmILezPghFedFFFmdz9OAzAJKh9wfy15LTQhWebb9QGg1nsx5UhUQO8yU5d/bSMmykhMFD8pqJd7NjBNDytODvF1vkY8ZmJzTQE1uibt6Df/jc28ke+eqp7mVK6muWUN9zRrqa7ilvobldSC5/XZqK6+m2sHUHRnuYQidIbQHwg7eKz6njkHwBJNWMPfkaSfs+xJr1qAGa/MWMKJ5Vsv+hyxPGypIaMRSHnYtFi+kvoP/viWj2m0JrZBp7QAv35CX6wZq8CpkklE68yvrz5j2iCvLebxlRid//ZF+tv+4zq6/g3aZeeyNhb3vYoOYnIfODDGJdcjiB65IPdn/AGjfCDXJR1pm0ib7nT94DFGBnb30TT0w+QMplWpKfwY2s0y2w7UdyF1fI7IPFlR4xfss+b7/zSGSGKVkHAF1dve3Me2do2nopCO6ySpjSbY+nl//3NH1IyUmo9cfqge5HmnqdEemycFBVjOIWQ5JmDcFXGBkdCp5KIy3vGc2F36qzsZdjprXwhp3+ynCYeqeBTbt810LhXo/G88JiDXnMBhMBRUiVQKFyHuSvll8/oFLTiFplGOOJLfwtR6D/lzJKpYmZRg2rSEuuRgC6ebe888UKgeBKg5hnJshkMJIsnUX2B+Cc0Jg7LBBz6GfbFGY38fssx8RZXnVCspnLaF8HO+6uMramyLa31Bm2hRDixXK9bP/Kv9Mi4mKNABjMLaRqAw2y/94cHMjj4IF/ytSQR4J+diHkX5W3FZl6/vq9PXlrSj6vCtYhlwRFT48/1ENiKTUsn621DPMx7694p9XjawlVUbwk7VBgjAWYq37kKSFqf/o6K5kpN4fAvuTAdqOI+SGOzLffyjtroNhyyHBCR28OMY0ls18ZGOdrn/L6Le5nSpeD9E4T4fBmZeiUUrtxYagLESSK0xQMnWo9Xjj8VlA+MP7mN43NkXRc3tKV39GGhz6ZwPjqb/w1eg+7u6CBYg7wg2HhexrGdXAEttgFIVXFttiaP3rSSxpP+rK8lOX/HPbX1a++SNVuq8tcdxxMZObA1IRPErFTp064K7GOdekFP5tjn0Lps7OHpfvy9QcZJOPmHrx/4s02qMuLDr/7eGHXy4x7XWOrZvJCUiOIMbsRKmSsbPbUv7ATm7411MuIVk7YqqFl4P3aBggwSissdHLRedfv7aM+TKERvHpoTBLgPqRQWcUT6Z9I108A8UV4gs6u0HrXMfVTe3jutXvbZ5xo8GuzUO6qv7QhQIHrP+3eMOxluZjLJGR/Ziz8lScepJRS9sojznAUaZzncdtyailh8q7tITGUJ75c174+APd8COrLMNzvvYrIdhqiLGHTL9yGLwI4ekl/KlHVVlO5YoFcNvyNuyrSrS3WJoioQSUTAkNOmm2155CX4FjFLRA6n3R2CmBLEX3nE/J38e9JDmNm/d5z2wGW35mxu+buH6UZRPQJ5JzTvqMtGht4QdaAXhUMzJvMAbqxCwMj/aoGu1Rh8oP9LO3V5jyvD3subbO3lpCv/eoQFjkSKoHcb4Yc14dIsYXHIy+aJerVL2j32UkWYLTCmlaY8/vDQsv3rr02C81vm/+CEwtZ1afbmISMcQiONV8zjR3nQM8qVcSHHWziBWjrp5Yfgv1ftKrEnrW5onOQs5MUpDhEaoQYInEEIkh5XgyM4J6q2cRsboiAtGICClObdEK12IlpiUTzOSWgwcmuqn6OpmDlAAvntSnJJpQ91DVYEDnXjhqpfBaoUb/F7anNH/YgTdYiYmlqMDUvC1s4j11zdsIJxhS08mngtXFhbpCifbQ8yzBRwGII3EZqU/xmiA+D+FFQUpW72fn78a6F9fwtZ6UnXcrktZRrZP6LG+li8dpRubrOF8j8yESBJS0zuTzh37GbDZYT0pObuwUnFcyPBkpWfGZqc9bMfuC6nBkA3j97dQctf9MqNOoWM3PQeY9qctzNlNfnA0t9s/0GuY5jDHQMyplecNSSsfy2ifAtust85/tycKMfpfRk2X0OkfmPK2aMrl+/jX54gneFpiSNLQ6qAuI+29hTZ0FiSjJQMRuSIxCwQf9j6gNt4SUkIzQQ6SGUA2BGgJ1iE8RnyE+w/gMSVMSV6HqeBTkFCHZy+c29PC1905h4XlC9vmEnm0ZaeKIU0fgEpQs761CRt07slQwxhJbS2gsFqVdPKUsoSvJ6Pp5wIZLF3PsRVv4+F03fHjZqPZFFydpilUl8gGTAkPZWAJVvEvIfIpzKZLlpuG9Y3rPZ7CkW6h+uk4Vh00dQZrlJZHO4H3+OK84lxIYz9MOOuamHtKgTn+QkUhKzWTUTUrdpNRchd2+ym4t/luPCPbMPXhKXQclH1LC0uyFkhoCAkJvCV1G6DKsc3jdwU1jhkUC7vpVytafZCgZohaLJcASAIGmhN4R+ozApyTJbrZld92bW8GLZBWCf1HE9FCwmSXUgJCAUC0RdQLvCOqKPLR4wbd2jGfvhRxzU0a9y2I1JMQSqCXUxhhDArEEpGjNE1Nl93lL0IFLssQqn1JRh3WOwGUYrwSZIfCWwIeE6rGaYjTFuAzjD5XdeNUKypM5+Rt1dicJFGfUaFZ8TkYw8ECshjKWJhPTfO7x6z8+e2zrM5qbftmXZiX8/FMhk07L2WEiFKN5xUeiUEkS9twHC/7ICQP+TLAvppJXHyhSW7WC6JR3p+Kplwyxmn20ZV7r20wj0/Loy51scu10/z4jiQNEDF4azUcFLy6/OdXjqREZx95wKz9/1Pt7v3DFhx5YdgkfOo0ly7fz0GnC8c9OqZzkyR5nKTVZ2orQvpeMSg0yUeoetF/R+w1Ntzcx/4eGTzy4Bupf5IeDls6obtpf1lKSHzm2PRgSqJB5T817EuPAeoSMWuKpPngfy8ZEvLqSSxI48butLDrJU5tdsHKrYIE4AIcn04zMJ2RbE3z1YJ+1R1il/VxfN8SBLdgXXB4Xq3nqAYPA6p6Y7vo8eg7up/etreP/J0Na8oBCZlzRqRMi70jU0betlV1jJpr92m2/63/1s1774Srb9jriphjRlFRdXmmjbgj8kbDjvgX0+kaA5lW8tc0RPFSjudtS9YLJCiybHNe2qmgS037L526G5ePox+hI76uy41ZH26QGPhjmnKVmSP8lHJmDJpeyp+eHXNhMwYC1hsX1Fp7xUJUtPwgIUyGUEIwB64rfzcfbqKBzJqFvRMU+ZxFuFR+4p5MLb8yoTIdIhxY1mH1KJm1BQh6op9bbx6/GRPpxSFfhiZwzZQvHfSKg/AKbM6WIQ1Eqaglx1Pa20fT5+XR8/RY+sbPxe9N48fIWZr3akEkGLi9jsokj+dbmVddfKafMtlN5+ltjpvyDQT0Yo3l3P63TfXsfD7+xn1/vYELGJJdxTfwzKk2enS0VHjrWk7QIEvVifEJ5V8S09Qmb673897AkyTevJh5ts7fVNxNzwSCmesSw2tXEUozh1UrpRjkyZa1jGsMKIhmBWfsaJb5cjk5TvM1K06xhI8kTMuJ+XE288Cg2KhzRspwHpS1MfRHw3LxmOyG/4a1YmkjZ9QBMescDl62954Hlt+wzyIw4yrAq9Hul2VtSazE2o78up5AsWrSppfe+KW1KXfOEVquOPBXZY1QewdSh/0uyPE/1qQN7gY1j/f2xdMVcuPjobEwZMoZHQ1ECyCFaEBwtRQkwoSjHJwuPckfXEZVlygVzFfvKgFIbebBDIbCA1tm5cSbPvvQ1N1y6+Y516MoDXDQX5bg5uAEm8UG57z6YQ9okAynVSqOxmQHRA/LMJmRCJmRCHj05qLJcBFEX086zNB+X9wrJ1OelTN7RnSjhR+/murXPeA1cMUwHRYMvahhlWKaSyUw2nrTZEFMw6RiHqs2DlQbKE8pyQiZkQh77yrKfJZMd2XkBUcmQqMcEBuM8daB6107u/wWsqpx5EJWWMy4PKsoGB1PDxHS0Go+W8uqewdCOogWtUnXCDZ+QCZmQx76yFOJ5AXKSJRMhKupMvclQV6P0PzC7C0Yq8LCFVWm85opTU8Q14o6KMxC2FH21i77bqnkOlnT1EyWP5sTcfDPx4sXUl64gWnaYLTTHLXp+jORY8G0raIlnE/kdpD/aS7rstY9C0OMqyvQgLMN95UqCjha01oZuDfCXX3708KIlSrRSjtwaqFIWYSB6fsNaSidsJkwfxB5/M/U5czE/uxD3rFnnKwtvOTLvpUsiZOWo30FXEREhJKg0ShK1SMMRGfdcLFKi++To7+dVK4h6fo1ta8Pe9zhYUkP5HZks56hnkegdlHmIkDXws6n4ndNwU5egzzpM/HtYu/DVr6b0kxtf9yJD0z8bgrDIkcQAGZV6yJyXPMSyn430wTN5+VeamHUx9DhPWZTEO2yasOfG7XztXdA2eSaX/HtIfK7FiMWIR73DaUL/d7ay6Qr48SPeBfGupUw7dTqnuJBSXZA4xgbdZOs99x/7dtY+YorpLMoPPonOSXNZGDexsF6nIynThKPWbOhuj+jZ2s991W1s+sMa9l6y8ugdgF3vZ1ZbC8dUYo7XZuYklo64js3yLOyKjeiP4KGmnTz8kyey47zzOOx106/QtnE7Ty3PptzSTYUMr1Vq2W72tH2C+w77MryTtpO+x6Rj53Jibz/TvWGa97QZiwlDqiZhXbmPNWxl04fPZM/hXE49H+Tk1hnMr1n2rUaqkBEW7BM2ryIo3C5f2Bs5E2+ISAlbiTCb+7jn8a8be+Buzes45fjTmNNTRtq6cUwl7Evp/6eN/ConazkMxahEpwjJn26g4+TtzIzKzKlHzOlXZntHa1OFPdJKpStgfV+Jh2UvW+56Dt2jqUsfrfzpajpOE2b3hxxTbeZ4m9FZ7yWKLGqEvXHMzlDYsaOfB2a9gw1HzLK88cZT7Qx0UYyJCgZl1bwntoKre3oOGa3zA+zHA/WyWlTBWIBWWqQgDdBhiGelnWZ5NPrFnjKJZ/eFfDQwdKQRrc6TWkNX6vgw8B+PxBj81ZzcW+bi6SHPN8LsWGhujak5oeQF6wVbE2gvs6V1Hvc+dxbf/NocfvSKz7LrSI1h7W2UqquZ1Ok5L1AuzkJOKsMUaympwZgoZ4zxiveCF6WvezIPn7Cen/au4Dstv2aNXE11vN/flzIvMnyiKWOBlnHOgCvRlZW489Zb+bvnPW/8abiVf2JOz91cEsxkcb2JJ0QlSqElRAikTlUNNitT7W+jy3dy3zt6+fa73scPWj/B9vF83542Xlx2vISQtn0MlEnE5Px4uVpMqaNkOByKl1yJBuTVEaHtozqtznuAL491DE0n8YFqE8/RAFfrIMg8tppw15sn8bJljO+9GvI4UdZ+Qk4vV3hZ2sl5VAgl5pj2iElGwU3Ok5WnpOydnLClu5lfv+AH3HjNZdx1JFjEKm9hXp/yip5mXhwGzGkxtMclQolzokGnOA/1mtJjDX/Y8jm+OfN3/EC+wu7DVpYtPKWc0bMoIlJFFGxBmZRpTiNVGwWeaPFkajDqyDSPdHuFismV5cnkHH65EtYBunwkrx/teFQ831QpRTGTLbQHFpMZwjiCqe6R4dHcupwL98ClHSWeqQHtDbqEzNFsXM6ki4HMYiVgdkk5Tmuc/oJZPHfnF7l26uv43ZEYR9MfObG9hfeUQ55Xiuioh0iQIQ6cOqr4nOYlyBudWJQ4jpnZ4nni3r2c2jOHjwF/HO/3N3eTSEhHBC1i8oz0VABPeOr08b/XLddxUm8Ln2xXLijVqKaWVhtgsjrVFCphSNlCQEAUCu1RmWPTCqd3z+IJ65fzmXmXsWXMl18rTUGJGcYwKTebC7ZaT6JCydiieD6kRXOCzlQMagSrNicb1bxJcUw6PjosKdFWLjM1IOfGN57MB7S3/YH0sDyApZTWnCDPb+rhDZ1NPN1ElLxhTxDRnglkGZnNm/SYIGaKh/YpjpO6O3ji6ydz3dvn8Q1ZP36rfddXOCvr4X2TSjzFWKY6izcpmfN5+lUW5VUvoaXJCk1hyHPTgNN3nMWJejbXyhvZOtrvMsP/ZZcXTB2sh1hzjhXfYA2KE7JDMnbkeZmpc4hmpMWaew0IVgNMo24M9SaKdCGfZ9wXLCleoOtRUZbOYjGIGsQk1MO86bkVc3SV5aqlRLuv4fxUeWdnC8/G0uoNTh2ZSUktBEYRayD0VG0v222N7iyjKygzJ+rkIqMs3foJjj3csVQ/zfG2hU+2NvFCKzTXPVVcXkftBR9U2RM4upwhTR1VEqoOnAuwIjS3Ws5uMrz6wW8wZ9z4kEMlyskMfaNNckSMoDNW48fzmXdcQXlhlbc1C+cFSpSVaA1Tkv4K9ya7+VJ3F5+v9fJlqfEnl9IbegwOtSGd7U28vL3GC7n5sjHvg7YkV4RZgGYBPrO4zOIIiAkx3oKT3Hr2FrUBoQTELiRIBZ+AS/Miahe68b27FAQHoc+9gUiLbjjrOayy3e1tXNBZ493tLTzZWMSluECIqdHrU+o2JMCjakAdzif0IdRbLaeo4d07381F4zovq4ju+jLNWuGdzS1c4C1tKJn30NfHnb39fLHSwzVJje+k/dxrqnRJgsMhgWVGOeYVu2tcMJbvHFZZtrGxami9yxOgCB7xOfFkiYC2ckj98bCkPLIbnorkVO9GiPCEZCRpQOcffr+Zpm5Wm4z6pKL8SBw0yEBRJDP0P5rR8EYYXxAekRSmY0/l5CDi3ceUWIQh0sKyiDIqWRe/q+3m6m7lqj7DF3otv6vGZFaIAgidx0nA5I6QpwftXPnL1zFu0qZ5r6ZUb+bNbW08OwiIRcmMIYwztDfj3izj85rxyfpO3ri3jw+kjv/s8+y0QmAdGItEJSaVJ/GikuOvWH3NuC4ZtYWClEHySNHDI658wvGc2jmFi8uGJjK8BphqHw/7Pt7VcjvvZS+fuHUK75ZuLu3p5SbxUE/pS8GHIVOidl6o2fL5Y/3erI9dtQrrTMZOk7DTpOw0KbsyZbfPSEjzelox+MaZTBK2uTrrtcbDvo/VWT+rqzUeyBg/tLFftCLvTTZrnFOqN8d7b+C0uIP3twecUDKU1GCNkCD4rgq/7YUvdNf5x2rKJ3sSvtubsFZi2rxiQsW3KPPKEa/RpYy5+PKUU0iO280Z5ZjzjBICkgmZr7MuUT5spvCRPf/DJ36R8TapcWVvH7eLYHGkAWi5hfnZFF6i72fWYbnhr19xp7vukhNWO9K60BSRk007Q4Yltop70Rxav7VxhIVTUqd4AxFg1FHJhOpvY7L7z5xFZR4vmyfsnqL4gvZeBhpcGeKuCHl0eqpI0bqYBipw9OWOFZT7tvI3U9s4Ux3NmcWIgs9IepXvqXJt2xe4+2fHkC2+hbpex9ydFZ7a18yHWkucpIoRCz6lXGrn4sefya18cXzditaczDn1Ei+WlMxZTBBQ9jG2v8Lt9Q38/caLeODUU9nb+PkNS5nMQp4vnqUlmFlL2VS1VEPDXu0j1g9cbsZzGqVpcB2k6PgiHCYls+e0smVqmkFkMalH+1M2PngMtz/tu1T1b4mWLCKT0/S+7l/K1yv9PF0M/VKj4gJsGpAmW2gZs7J03CTwQMUVtDhpHshpa2Z2b8Lft5aY7/KmPaoWKnXu8TU+WUvYmQk+82jJIyVB9nTzlyO60yePb0pvWLdYahWWTGnjdAM281Q1JDAZ9FlWVXpZVjuZVcefR/fSpUQvXMC0zj1cFEYsjVOakpAo8GRBxBldHZwNrB7rGKxyRgRNOJwGBKJUDOz4SsZv73w5buUrSLidGnB75Wpm1GqcZASvhtQ6xGXsWddGG7B53Mpy2SUks9CHMmo7PU2zQ4zmdEleISWk9bgetj3h0uvouv6Nw5dmWYJORz31ZOpxux3upy3MuPrtq6+uXb5we0vGu15iKLcajGZ521wNsVYJNMBu2cyP+h8NXenzngpSNMDAS84YetCerEdATr+XWX2zWewtbUCiBhs4fHcXv25p5RPx5QVlzz3FLn8jG/QqdiaOcr3OZ8oxU7yAt7jI0dxf5VmvvoEfj6dV8I4WXju9iVm+j+6gRIDFdNfZ2W/49JwPcicf3Pfn5y5jj36C/94xjd5awMywzjrfzwZN6J7TTresHKcltBvP9JwfWgqGXwGxfpwkx6px/Hkpa4UemujMFKzHl0vMOnMrT77qCn4rlzTGKrT9F3fseJC/TZrI6jvpjx11nYFuemh0B2uozP4QDwAPHDCk5XRKE+/AgHiszxNOqHl2+F6+P/W9HPkupgWVRNEPQGgb38e85hY6e2LOMznnMS4jjYVyf5WNe/t5x9wP8NsBfbKMZBls0mtYuaGPadMtr6wo3QjrCFm3zfDQzdcQLx5L+plqVLpW2k1Gb1ZmMgm1SDBJzMzXJjyJd/K7lapRI83q4Tv4fvuTWdcqZN2GWrNDy9C/ujr64NZB8yynM23zZrbcAzrH47AYLRoWqRA0BZT+/vtvfPsauHkDRTOxoVJn+3eUk1aGlOoRwRrPhrUPcfWeyxcuiFu49HRHy0ssYZQHVDEgDgyOelWwmx41BzxDG2F6IVeWRfsWf7S+sg9OaY6ZX/ibxgDekVDl5p/4ot3y/nv+Sqq9X+ZmV+c1seMZklvB1npKJcPjP9zFjBth3Zj0ySeZvamFM7AEkkeHBYfXhD/PnsJAL56lSrRsSK6evI+9wLcbualHYk5q9dwlldzdEF80txEdNyO8mr08LHW2+ojJGiDiseUSx/dZrn7lAu5403Lu2QJ/PgbWf/jr7Fq2VH/HKXL0chI3kclCQu9wNGCfogENnUf+64q0E9WCslkE6R6nsuzLWBSXmUdOEe9N0VncwU/mXjmoKPfZs5ezU7/KDd1d3CmGbX+Brd8W+i6/GL947hj3jUiin+EhImyQgkaEpPjAMcU2s/yVC/jFFR+Xn/d/kPtbFrBnS529s97Ibw5n/g6qLP/IZ3fN5mU3OfrPsbQ0gxNT0NQDlJi6qMb9S09k0XtOXbFm18r9Erfn/e3tK+6pT0tZmSfiLl1BNKeH1qV/94xFNbo+ETB5Ss4sXUeIxaBG8ZLRsz1k6qpHTVkqOUFSXk7kj/rXraW0bQVnFlFfTNHmr+7oqxt+svjtqlw+vCPb+tDans1T569qCXmaglEhtODKrRx/rDCbMSrL7Z2c3GzpxONNiZYsQwOBUoVfycWDEbdlB0lqXryYeiPnDtUSIjUeKyKSyKX8rrqIbRpyUigEWYIzGbU2y3G0ML8uvGxmSLWW8NAbzuZPb7pJ/jz9H/n5t//M+qOSx1pCyEg1wJmQSHWAx1B9jcd0BVulxhM7J9NhXHGlClFd0Qr8ZMRl+FvWA+uH/t1V4xyDrfHznpAtbSEdCDaLaZaErM1yKs2ckMRcFCtdfSlbm1Pur1/NH6s1ftlx/JJNXLIyOWLKEmAq/T/fgv7e0/yMABuYwnIXRA2mpEx+1m7q//L9S972Yfjv1bBpwOW656v0wyC9xrL331matOaziwN6rywzZa7BW8n77FiLFlar9Qp3buSmjY/yXlD8QKOVoyq//h2yqIXZ5M3SMXnUEu/p6W1m20jVGkuZ71XZIYYgEQgNxntSYiZ19xdpKmOQtIeZnSFg8KIE4qgSUpYaD+7/s1dcRfnyCqfPnUlLd4bTraRZBvrPlLuvw7T9i2j1I1SSdazq+AJ7Hs3FvEyJlwt1uZ6te2/kU329lKYHnGoVR0xMmje1l5BSc0Z7CdqamzheI55Xy3j5c57NL/ZczHd+fCp/OZKJ1LRhcAXVeOOiLlJP/NrHtrI0hpkIZHm1ig1ztula4seeWjVWuVmJFwt1+QfW7/oSV1R6+DjNPK4kRBoQ4PJMA1NijkuZWnLMNcKZlHlJ3MqG+s6VP0iuZ2XrpWNjpB5RWZ669Lt7dix76X84eh+X0T7dFIxAgvMOvGVKOSZ7Zoz7SswZvyzz8q8auteXWN/79Fffknznxuc0t/LCpj5+elbLmuUvV+JTAponWUpWyIxBVGmSvNewlSqVncD3YU3Po7YJKDqbK97myscbcKE5Opt33r2IOZZpPqQUZqiziAUyT1a/Z2Sqrq89BXnXOvqBunhiNYgo3iT0NznG3PYisITGY1Xw3pHYgNgZSKYemF7y2XV0+vl81CkLmlppNTHdqSGQkNgokjoymtnj23kj42hqX6rjK4rzgwnaNhNII4KxNn1ePoRO7ddP5RdP/W8u2xPwhkmdvNRYIgzGWUwWgE1JxUNsCbKIOcBM8ZzYm3Lukj+zHPjWEd0ADtWYcq4rMUZJABecfGSDi0YGmsOL1aJ/i8Npz9i9p9XXENfBaoYGWoSKwGSeWks0GPw7WrJ4yHpOeQ0/7H8/e7qmcNm0ybxAoA1FnEKmpDalmglWI0Kp40rCCa7E3IrjmXu/yed6N3Pr3CtHh6uPqCxvXEbtHJ73q9X86BspPW8IaG32WG/x6hHNsw8CC8xpZt6LPLv+2lHvrzC979YbX5o2E5ThnnIz09qFJLRECCYAL4KRvM8GTvOugqlj7y9Czv01fO1RNitBJW9m00ggPlpWputBvVJTza0bT95d3RrsKceP3G97zWLqci0h5E3CnSJB0XTL+bEHpCoWMRG4PHXJo+BS6lH1QGWpjyfMYL4PaU4z1AZME09oUqwhz6sTQ2gs5cNdC2Ggq/vhZg81+Drv0sv5QKZ820W8rO5YGDVzYqmJKfUIpzVqoaUjtHlubQDtAczq68FXrmVL01vGrvyHlQQlKl7QD/gzXhW/vX5k95vJiyoH+rw0LFkdh7t/wnPQdbcRmoDcftLcuDCCa/GPvEXc/HF+r1/nffU+VmYJF2jI08sRx5cyQhdRDrO870XSTHPmMQTQ6pjU30es/TzIKHueHPL2up3XdpWZ+S8Ju7+f0t3n8C4risXzYhLjBRFD3QbQbIlmWkoLY5pOjoiPizAzIqRNaImUwBqMz4NExuf6yJHSR4VtDzYTfnYTb3hUXTZfHEwvHH3AEph7NdWsym7jqA50I3JoKES0jZwvuWQFUUVoFyEUj5o8vyZwiu9j7IEW56jU845mBAZxedNvLbsDFV7N4OsWI4amMjSHEJQtgTV5easpoq1xeATyVM1AvusRy3uVa+gKr+S2D9S4onkBrw4sr6qs5cqeXXzPO+oINg0ghSzN8x8JY57kA16sSw7vAjgkYq5oVn3suuFyComxJL5YDVcYEoEh7M6Y/KiM6eVsKV3KTTdP470twoXWsmRrwsc2p9y2R9hMRiVKUQypc7iyJ54ScFppFi/d/vnRpYONytR/mKt2xMz9R0fl5pRqkiFBkfpGfp7wjkgyrHEEzmPUE1sllAwvGUmmVJ2SSG5A2bSxNxypQH1DRvqpkPr9j/ZGcLawYgAeIUbNWNmpjlQHHDMyIN7ZxUCl1NJVHNAdseleTG9GuxRlLsWUWg+u1D72tJMTSmzySkUcKR7xJsfxuizHH7AnNrLbZCzbtIc3ddd5VyVhWaXKHd5RCQ1iyElKj5ByM9Jos2mPrHt61bvplwtZb17Hj1pSrq+mXJrChbWUr9gqldBjbG6layyEScDjdp99NGLVkLfyyRVm1vfYxiynN7FDfIHfWkQsoUS07GLkJO9VVzH5gY/yKv03ztfrmHukx3XJJfTJpWySVy796Z6FXP3w77hkVx8X9Hm+nkZYJ4RiMA6ct4RhxAVTJ3PSYbvhQ2UL/7oRzn7PVOoPC5PepEye5PHO4MQSipKJEKpHjaAITYB6IQvAe1+UoOZeBlZRmyDVfvasy2haOofST+5jZfJob4IwQ43LQWscibfYQAjLtdHX0OrNxDKGFJqsn9+2tPEqD83W4Tw5QO0M5wJ3ASwbJrDwmVOZFOzk5LoBG2KdBQwugc17enh4bDaNRnxD1tT76I5iWlxAGIKpp9TjZl6kn+LT8p6bE2RxHeCUZfStWsFXN9SQxZdSv+szTDsxYmE4maepzVN8PFAZbyLR3lxxiOKNyYNeoSXQcZr7K86ifPEbeVqQMcVV0L0VoiwitnV+8Y33sf5yoS7LqAE1oEvfx3u3zKI8tZOXhhmBETSz+BbH9PVl2sn7Jx+eGTkJi0M073YmhcJMRfGzWo7sVa2OTD3aqExTQTXAmBLjajm9MeMnx2a8LilzrLdICbRuYW7GWcA3D/Z70+DxdhZLqwHtWcK22nX8bGcP3/7zu2/+9QWrFg/S0R1C3n0ire+7lCfWm5huUtRBnASU5v2KW+SrG3brg3O97NSEayUB7u9/D8sqJzKlpZkXkJFZRz0VpGxZKAknAb8/IpbloPxy79/wvs+30PkmR//v86Rz4/PzDSBqCMVQUqXmoeahnnoSH2JtTEhIYAzGpLi+lM23WPwrnrj0y7c9FhQlgC2soQZrsfW4FFx/W+F6qUboqghdEaFLInRp8f8HW37KGHMNa23cVXF0GUNgwBrFhJ6gNeJv/vwNTka1tGEDZb153/r0KX/ivLifGaFHJSPDkSUltMvz2/nPXT02oF0kkZezpfow/2MhJLcuFSFSw4y9k3k+sri+Qok0R9o45RKSRiJx013UREl8nX7JyIqkZ42j8VlItXJRtDOEPbrYsOP6vCUv5YRe4V+SMv+mbfxzWycf7WzlH+wkXn35g/lH6maaVikRqtETXkLFCnd6R+aL9A9jCb1Ah+L43ypyZDymBb/lvp4aD0WClFKUhD6T0uc7uLj2FU4EuFRpUjTStXl/bn0XM+otvLnDMLuc0d4acKJt4c3NnVx+wbWL22UMmQZLL+KEKODfpkZcN6WZ62ZGfGpeyjv7zuFiZG5VFlLXewe6a8evuZKuMOB+9TmWTkhzKIQ+Y2etn21H1LJsyPmrzq4Hn+P2X1//93fuYNPzEyqvMsQnx7TGIFYweaCcGJ8bBip4HCoZVVGSNKT9txEty0+i+RdPW3FV+qiR6w6/mQqSdyjK7awIWRDxpK7P8RL7cbE2RmzOgmMRhCzPzdSPk233PLjgg8UtdfPNMYsXH1Jxznwz63Z8ge/aOtMCoWyLZtQt7Zw1dw8f8f8u177kafxy5QV5uMN/lym713F2LeQ9TSXmeMkJNqwj6E/YPKudH7Jw4bhsuhnTWNlleVNTzrRoQ0GsJXIVXlO9li0l0V/lieECqtEKhBevZP7uHl5Q9TzNxDQ3Ahaew6hZbR4odzT4AiPX8QfaHtzLutYWtnXEnCieZjzVYpUv+Pkf5MZnwmo5RrM8VUv44oUE7gJO8HlqjHhFM0etmnDPlGBs1F7/F0Wup3vX1Xyj3seJcRNzsDiqZOVmZrservGv5ArDioeuRxLmw7bXM72ygCunlDjfKSlK4iFODJk2c6c8a2kPLBv9Zfpn1tj59JgWTjQgktFMiVKX48K+9/PtF7+GPQON70Tq//FWWrIzODYqckRUEK94Tfl+6dbRJauPWVkWeWYJfLKydClfv2/ZVd/6PXc/PmTX8xL8qZ7o8Uq5PaXWoLF0gu2u0L8K6veUqd905llfeXDlnVQfAn50yWPu5h0sQS5clkix3vOCoMzzpJVma/LItfUEeWvxgYh5ra3K9QMm/SgUZa5Tibfv4AvNVc4g4EQfMzkIaPUC4RReWKnw5C/+kg1fuU02JZ+hv7KW4ya1cKY31AFNhKwE0i/0VPbwxZYrRk4MHtHVeO7Se3b8dNm/mClcYV1ewUOGtpY4vS/hWq6X1Tv/mV/ZT7C5f7lMbY44tVd5SnOZ+bGhlEJNDJEFY8HHMs44WbAP2tkoFRgkxh2DXKPEC9H67k/K133EKeqIjMWrIZpsOP3xCcv/8hk+eOKX5eG3ryb93Pdo3QQv6mjhVRIQqoJPqfkaD/sa/yOXs/P/d2WJLi11vn7Zyj0n8VdqOVcMLTYmxJGEEc/s+yu+t+eLl2yQ66kZSzkIOL6U0qkhTdaBszQHnho93N20he/xkmWjLmDQy4hlOT1rl/DDWb0cbwMsAc1ETJ7Wz4l7O/jY12/ki3ev4K5195K9Bmbs7ORvO2IuEgEcqWTUs5T1fb18t+W7o8P3g8OZr5xh+UqA3+bPogjus7ldcVYz1OxsXtm3iT8ZWDFQ673yzsfuHiiBSTVXlyZvlA6CNYYWE9LiAedRHAmeTFIqBESElNVQrtXyaltVYhllu9SiRPAh/Tgf2huxdNI0npVIvjhWEGlmjok4JgIrjj2uzl5XY5ttZpILKYc5GXC9UuFn5Zh/O6wJOGVZ8qR5/OM972BhSzsXakigHqcRQVMzJ6KcOCXkXJSsBSyWUA2BeCqpo8+ERAZM4qlX9vLdkjuwJnpUSnsqsVG8Ggwud3sNOT8hW8emMPO2tcKXb2DF27t5Ub2Fs+Ma/bZMQJ3+joCzgg6+312j96M/pVpvpmlqQKcxlMMaVW8xoSOten4Vz+TnR+xe3orpn04aQqZCqAFBUMcFdRJ3wpENZLmA0CiCI0sNEgreK+nkHWO31FWJRJbVBGr+Kj6zq8pxTW08wXjAYkyJsAmOd8LxXlDj0UAxalHxVEjxVij3pWxp2clVsmxszPeN1hT/bbjqzX2cJ20sCvrpz1rosE3M7Yh4YXUyf316hf5F0+iqGDomtzMfAe+o40jSCtt79/CVafPzmMCo9uSRVTX3JeRMRHW4cw/ctXMTV1bhq/3/Wy7MfiVxSg0DxuWun89dPw94yZWoSICVkFBLTNKAJs0JPLPWwk2UcfSVlgX6p6SFj2x1/D4AMsBWSUq97LU19qYZiSZ4mxBaiCzE1kFfP1v2VPnW1JQrWt/CjsOdg/XrqbGGN/Z28fX+hF0+ptXHtCWC6wtwPU2U0yZaJaYJQBxVYzBxTEsKdPXxw929vP53v+FtTeOk8I+UtJpRTZQ0M8TOEGSCpAob141v317xWrq2b+QdfZZ7tESHi2mpB9RqCT1G8GGZmW0xx4URs4iIEiF1jizrZeOOfr6lEZ9quWR0+NaopE5attgoRYOEuigmi2juDdCpT5x9RIlbsiq7szo9eFzosSbBm4SMaWMvXpBGuevqm2NzJX9Om3j7rn5+nKVUrSGqB0iSY299pk6PqdJt6vTZlKpPyJwg9ZR16vjkypQfjPedrngtXVsj3re5yl+ykDgQkDq10NDUFjOpBWZOK3F8U4kZ6tHU46xBs4SdO4QVzTP4pnnF6LsLBEzIvgEeRV2VHp+hLo9/ZznEsc+MycAlK4R4HB7nPclOoe8aJb5cqN+mlMbUJOkSSWbAr/UdXLLu8bw6irkkqtHaZmmPmpkKEMZM8WXwjureClvThHtLAf/x7fu59fLPrVDecmRwjfZ/Ybe+hbc/1MpXO1p5XdTK46WN40oRpSCh31kCDYm9JcRRr9XYWk+5uz/guw9O4ebzXniYuF5AUINqSaiq5gzSTqm5fnbPOXb8KbDHfJr7dTkv2mB5TdjFuR1lTgiamVoSYhz4EIwBSalKwo4djtVpD/8x9+9n3iRsrRzJvdZ/InFXL3tE2RwYSr5O5jP6w1669D83xXKkuCsB6wZ6WGND2p2SZRk9608dXzQcgIU5zDTrdfxx201cufNezg1SXjs9ZgGFV+RBCCj5jDRL2F1z9GjIH1PP56e++dAR6EPJ/DdyW+U/WLJhAy/u6OD5Tc0cL4b2OCRCiIgpqYKrstfBto27uTPezZfnJPxmrG1PJnpz7ydXQvNnPsXJ3eB7wLQ0UY+G4G7de8hMhJGWPAyU7ELFItKMCYF4Db2Tluvm8XTgW63EC4dYpKtZEE+7bM3slkU8u2r5mxbL/JpBqhm/m1ThOzzMnz/3VrZevvDodMxTpSzc7PXXi03lp0zWEqfYmKfWlVNbYo7pCdlSyfh1ZPjNLseDJ69iz5Ho3teo/e2+jqf191Lv7EWlg7B3JsHGB9ly2j+Mv3Fc4yID+P2ltE8+nWM7Ux5vYy4olTlNUrTueTBK+M++B7nj5xW2XXQ9FV1N3AgYrFCiS45Eh8TV58c7b7vl1O6HqE3pJN7uSWkjOUHw5s2r1yELj9i6fv+tLFz8ZGZu3klPSzPhjoR0nkXjv9L7DodZSVcRDY1iX/0nOp55BzPnlzhDPH9TspxiMiou44/G8f1VjrvOHKe3MfwAVkTIJQnA5u8yZcq9zPElTpc2nuMCTjNg68o9bgtf3FrnrlOW5Z7B/sxZEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhEzIhg/L/AAi++mNA+ExFAAAAAElFTkSuQmCC" alt="Puninar Logistics">
      </div>
    </header>
    <main>
      <!-- DASHBOARD -->
      <section class="view visible" id="v-dashboard">
        <div class="page-h"><h2>Dashboard Antrian</h2></div>
        <div class="kpi-flow" id="kpiFlow">
          <div class="kf-head"><span class="kf-title">Status All Driver</span><span class="kf-sub" id="flow-sub">dari 0 driver aktif</span></div>
          <div class="kf-wrap">
            <div class="kf-grid" id="flow-grid"></div>
            <div class="kf-side">
              <div class="kpi kx" title="Total driver OR hari ini = Absen OR + Tender hari ini. Absen OR: berkode absen OR dan belum punya shipment hari ini. Tender hari ini: semua driver yang punya shipment pada tanggal aplikasi, apa pun kode absennya, termasuk yang berkode OR dan sudah di-tender (tanggal = yang terbesar antara Execution Date dan Tanggal Tender di DOTS Report; semua tipe shipment, kecuali CANCEL). Satu driver dihitung satu kali."><div class="l"><i class="dot" style="background:#2F6FDE"></i>Total driver OR hari ini</div><div class="v" id="count-ortot">0</div><div class="sub split" id="ortot-detail"></div></div>
              <div class="kpi kx" title="Driver tender hari ini: jumlah driver aktif di Master Driver yang punya minimal 1 shipment hari ini di DOTS Report (semua tipe shipment dan semua status FPT kecuali CANCEL), dihitung satu kali per driver. Rincian 20 FT / 40 FT menurut TIPE TRUCK shipment dengan tender terbaru hari itu."><div class="l"><i class="dot" style="background:#0E9AA7"></i>Driver tender hari ini</div><div class="v" id="count-or">0</div><div class="sub split" id="or-detail"></div></div>
              <div class="kpi kx" title="SPK sudah tender: jumlah shipment SPK (armada sendiri) berstatus FPT NEW atau CARRY OVER yang onroad actual-nya hari ini (tanggal terbesar antara Execution Date dan Tanggal Tender), dihitung per shipment unik (CANCEL dan REPLAN tidak dihitung). Rincian 20 FT / 40 FT dari kolom TIPE TRUCK. Sumber: DOTS Report."><div class="l"><i class="dot" style="background:#8B5CF6"></i>SPK sudah tender</div><div class="v" id="count-spk">0</div><div class="sub split" id="spk-detail"></div></div>
              <div class="kpi kx" title="Jumlah driver aktif menurut Master Driver pada tanggal aplikasi, dan sebaran Kode Absensi-nya (X, OR, OFF, AIS, N/A)"><div class="l"><i class="dot" style="background:#D6409F"></i>Kode Absensi Semua Driver</div><div class="v" id="count-aktif">0</div><div class="kl-break" id="aktif-break"></div></div>
            </div>
          </div>
        </div>
        <div class="dash-grid">
          <div class="panel">
            <h3><svg class="ic" viewBox="0 0 24 24"><circle cx="12" cy="7" r="3.4"/><path d="M6.8 21.5v-5.6c0-2.6 2.3-4.6 5.2-4.6s5.2 2 5.2 4.6v5.6"/></svg><span class="tt" title="Urutan: kode X di atas, lalu OR yang sudah AVA (menurut jam closed GPS), OR lain, AIS, O; absen tertua di atas. AVA langsung bisa dimasukkan ke antrian (tombol + hijau); OR yang belum AVA dan driver yang belum absen (N/A) bertanda + abu-abu dan butuh konfirmasi."><span>List Driver</span><i class="tti" aria-hidden="true">i</i></span><span class="hd-info" id="ldInfo"></span><span class="grow"><button class="btn sm" id="resetLD" type="button" data-sfreset="ld" hidden title="Hapus urutan dan semua filter kolom tabel ini">Reset Filter</button><span class="badge-n" id="badge-total-absen">0 Driver</span></span></h3>
            <div class="alert-note" id="attWarn" hidden></div>
            <div class="toolbar"><input type="search" id="searchDriver" placeholder="Cari ID, Nama, atau Catatan..." aria-label="Cari driver"></div>
            <div class="tbl-wrap nox">
              <table class="t fit lt">
                <colgroup><col style="width:22px"><col style="width:50px"><col><col style="width:48px"><col style="width:56px"><col style="width:58px"><col style="width:34px"></colgroup>
                <thead><tr id="ldHead"></tr></thead>
                <tbody id="tbDriver"></tbody>
              </table>
            </div>
          </div>
          <div class="panel">
            <h3><svg class="ic" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8l4 4-4 4M8 12h8"/></svg><span class="tt" title="Urutan antrian mengikuti jam antri. Driver tetap di antrian walau sudah diorder. SUDAH = ada shipment dengan jam tender sesudah jam antri (shipment yang di-tender sebelum antri tidak dihitung); klik SUDAH untuk melihat detail shipment hari ini."><span>List Antrian</span><i class="tti" aria-hidden="true">i</i></span><span class="hd-info" id="laInfo"></span><span class="grow"><button class="btn sm" id="resetLA" type="button" data-sfreset="la" hidden title="Hapus urutan, semua filter kolom, dan kata kunci pencarian tabel ini">Reset Filter</button><span class="badge-n" id="badge-total-antri">0 Driver</span></span></h3>
            <div class="toolbar"><input type="search" id="searchAntri" placeholder="Cari ID, Nama, atau Catatan..." aria-label="Cari di List Antrian"></div>
            <div class="note" id="fltInfo" hidden></div>
            <div class="tbl-wrap nox">
              <table class="t auto" id="tblAntrian">
                <thead><tr id="laHead"></tr></thead>
                <tbody id="tbAntrian"></tbody>
              </table>
            </div>
          </div>
        </div>
      </section>

      <!-- HISTORY -->
      <section class="view" id="v-history"></section>

      <!-- RAW DATA -->
      <section class="view" id="v-raw"></section>

      <!-- SJ OUTSTANDING -->
      <section class="view" id="v-sj"></section>

      <!-- MASTER DRIVER -->
      <section class="view" id="v-masterDriver">
        <div class="page-h"><h2>Master Data Driver</h2><p id="cntMD">Upload data driver operasional</p>
          <div class="grow">
            <button class="btn" id="resetMD" type="button" data-sfreset="md" hidden title="Hapus urutan, semua filter kolom, dan kata kunci pencarian tabel ini">Reset urut &amp; filter</button>
            <button class="btn" id="btnTplDriver"><svg class="ic" viewBox="0 0 24 24"><path d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16"/></svg>Download (template)</button>
            <label class="btn primary"><svg class="ic" viewBox="0 0 24 24"><path d="M12 16V4m0 0l-4 4m4-4l4 4M4 16v3a1 1 0 001 1h14a1 1 0 001-1v-3"/></svg>Upload Master<input type="file" id="fileMD" accept=".xlsx,.xls" hidden></label>
          </div>
        </div>
        <div class="panel"><div class="toolbar mtool"><input type="search" id="searchMD" placeholder="Cari ID, nama, status, kode absensi, tanggal..." aria-label="Cari di Master Driver"></div><div class="tbl-wrap" style="max-height:72vh"><table class="t"><thead><tr class="flt"><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th><select id="fltStatusDriver" aria-label="Filter status driver"><option value="all">Semua</option><option value="aktif" selected>Aktif</option><option value="nonaktif">Nonaktif</option></select></th></tr><tr id="mdHead"></tr></thead><tbody id="tbMD"></tbody></table></div></div>
      </section>

      <!-- MASTER UNIT -->
      <section class="view" id="v-masterUnit">
        <div class="page-h"><h2>Master Data Unit</h2><p id="cntMU">Upload data unit armada</p>
          <div class="grow">
            <button class="btn" id="resetMU" type="button" data-sfreset="mu" hidden title="Hapus urutan dan semua filter kolom tabel ini">Reset urut &amp; filter</button>
            <button class="btn" id="btnTplUnit"><svg class="ic" viewBox="0 0 24 24"><path d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16"/></svg>Download (template)</button>
            <label class="btn primary"><svg class="ic" viewBox="0 0 24 24"><path d="M12 16V4m0 0l-4 4m4-4l4 4M4 16v3a1 1 0 001 1h14a1 1 0 001-1v-3"/></svg>Upload Master<input type="file" id="fileMU" accept=".xlsx,.xls" hidden></label>
          </div>
        </div>
        <div class="panel"><div class="toolbar mtool"><input type="search" id="searchMU" placeholder="Cari nopol, tipe trailer, status P2H, FMS BD, tanggal..." aria-label="Cari di Master Unit"></div><div class="tbl-wrap" style="max-height:72vh"><table class="t"><thead><tr class="flt"><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th><select id="fltStatusUnit" aria-label="Filter status unit"><option value="all">Semua</option><option value="aktif" selected>Aktif</option><option value="nonaktif">Nonaktif</option></select></th></tr><tr id="muHead"></tr></thead><tbody id="tbMU"></tbody></table></div></div>
      </section>

      <!-- UPLOAD & SETTING -->
      <section class="view" id="v-data"></section>

      <!-- ASUMSI -->
      <section class="view" id="v-asumsi"></section>
    </main>
  </div>
</div>
<datalist id="unit-list"></datalist>
<input type="file" id="fileAny" multiple accept=".xlsx,.xls,.csv" hidden>
<input type="file" id="fileSlot" accept=".xlsx,.xls,.csv" hidden>
<div class="toast" id="toast"></div>
<div class="modal" id="modal" hidden><div class="modal-box" role="dialog" aria-modal="true"><div id="modalBody"></div></div></div>

<script>
// Master bawaan: diambil dari SIGAP  [ID, Nama, Status, Tgl Aktif (serial Excel), Tgl Nonaktif]
const EMB_DRV = [];   // data bawaan HTML user dibuang (build): data dari database
// [Nopol, Tipe Trailer, Sub Status, Tgl Aktif, Tgl Nonaktif]
const EMB_UNIT = [];   // data bawaan HTML user dibuang (build): data dari database
const nmUp = s => String(s == null ? '' : s).toUpperCase();   // nama driver selalu ditulis huruf kapital semua (EDI, bukan Edi)
const initialMasterDrivers = EMB_DRV.map(r => ({ id: r[0], nama: nmUp(r[1]), status: r[2] || '', aktif: r[3], nonaktif: r[4] }));
const initialMasterUnits = EMB_UNIT.map(r => ({ nopol: r[0], type: r[1], sub: r[2] || '', aktif: r[3], nonaktif: r[4] }));


// Data awal (snapshot dari artifact). Berisi data hanya pada file HTML mandiri; di versi artifact bernilai null.
const SEED = null;   // data bawaan HTML user dibuang (build): data dari database
const SEED_AT = 1792556294738;
const LOCAL_KEY = 'quedrive_localdb_v1';
let LOCAL_SEEDED = false;

/* =====================================================================
   UTIL
   ===================================================================== */
const $ = (s, r = document) => r.querySelector(s);
const esc = s => String(s == null ? '' : s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
const IC_ADD = '<svg class="ic" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M12 8v8M8 12h8"/></svg>';
const IC_BAN = '<svg class="ic" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M5.6 5.6l12.8 12.8"/></svg>';
const IC_DEL = '<svg class="ic" viewBox="0 0 24 24"><path d="M4 7h16M10 11v6M14 11v6M6 7l1 13h10l1-13M9 7V4h6v3"/></svg>';
const IC_UP = '<svg class="ic" viewBox="0 0 24 24"><path d="M12 16V4m0 0l-4 4m4-4l4 4M4 16v3a1 1 0 001 1h14a1 1 0 001-1v-3"/></svg>';
const IC_DL = '<svg class="ic" viewBox="0 0 24 24"><path d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16"/></svg>';
function pad(n) { return String(n).padStart(2, '0'); }
// Tanggal aplikasi: "Saat Ini" (ASOF = null) atau tanggal tertentu untuk development (serial hari Excel).
// Pilihan hanya berlaku di browser ini. Jam tetap jam sebenarnya.
const ASOF_KEY = 'quedrive_asof_v1';
let ASOF = null;
try { const v = parseInt(localStorage.getItem(ASOF_KEY), 10); if (v > 40000 && v < 80000) ASOF = v; } catch (e) {}
function nowDate() {
  const n = new Date(); if (ASOF == null) return n;
  const d = new Date((ASOF - 25569) * 86400000);     // tanggal pilihan (UTC) + jam sebenarnya
  return new Date(d.getUTCFullYear(), d.getUTCMonth(), d.getUTCDate(), n.getHours(), n.getMinutes(), n.getSeconds());
}
function getCurrentTimeStr() { const n = nowDate(); return pad(n.getHours()) + ':' + pad(n.getMinutes()); }
function getCurrentDateTimeStr() {
  const n = nowDate(), m = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
  return `${n.getDate()} ${m[n.getMonth()]} ${pad(n.getHours())}:${pad(n.getMinutes())}`;
}
const MON = { JAN:1, FEB:2, MAR:3, APR:4, MAY:5, MEI:5, JUN:6, JUL:7, AUG:8, AGU:8, AGT:8, AGS:8, SEP:9, OCT:10, OKT:10, NOV:11, DEC:12, DES:12 };
const MON_EN = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const ymd = (y, m, d) => Math.floor(Date.UTC(y, m - 1, d) / 86400000) + 25569;      // serial hari Excel
const realSerial = () => { const n = new Date(); return ymd(n.getFullYear(), n.getMonth() + 1, n.getDate()); };
const todaySerial = () => (ASOF != null ? ASOF : realSerial());
const utcDate = s => new Date((s - 25569) * 86400000);
// format tanggal aplikasi: dd-Mmm-yy
function fmtSerial(s) { if (s == null) return '-'; const d = utcDate(s); return pad(d.getUTCDate()) + '-' + MON_EN[d.getUTCMonth()] + '-' + pad(d.getUTCFullYear() % 100); }
const fmtDMY = fmtSerial;
function fmtDM(s) { const d = utcDate(s); return pad(d.getUTCDate()) + '-' + MON_EN[d.getUTCMonth()]; }              // dd-Mmm
const dayOfDate = n => ymd(n.getFullYear(), n.getMonth() + 1, n.getDate());
function fmtTs(ms) { const n = new Date(ms); return fmtSerial(dayOfDate(n)) + ' ' + pad(n.getHours()) + ':' + pad(n.getMinutes()); }  // dd-Mmm-yy HH:MM
// kolom Absen MTA & Antri: dd-Mmm | HH:MM
function antriStr(a) { const n = new Date(a.antri_at || a.seq || Date.now()); return fmtDM(dayOfDate(n)) + ' | ' + pad(n.getHours()) + ':' + pad(n.getMinutes()); }
// tanggal dan jam masing-masing tidak dipotong di tengah, sehingga paling banyak 2 baris: "06-Oct |" / "15:57"
function dtHtml(s) { s = String(s == null ? '' : s); if (!s) return '-'; const m = /^(.*\|)\s(\S+)$/.exec(s); return m ? `<span class="nb">${esc(m[1])}</span> <span class="nb">${esc(m[2])}</span>` : esc(s); }
// Absen MTA: dd-Mmm | HH:MM bila catatan absensi punya jam (Masuk / Dalam Perjalanan), selain itu hanya dd-Mmm
function absenStr(a) { const day = a.absen_day != null ? a.absen_day : dayOfDate(new Date(a.seq || Date.now())); if (!a.jam_absen && a.absen_day == null) return '-'; return a.jam_absen ? fmtDM(day) + ' | ' + a.jam_absen : fmtDM(day); }
function dateKey(s) { const d = utcDate(s); return d.getUTCFullYear() + '-' + pad(d.getUTCMonth() + 1) + '-' + pad(d.getUTCDate()); }
function serialOfKey(k) { const m = /^(\d{4})-(\d{2})-(\d{2})$/.exec(k); return m ? ymd(+m[1], +m[2], +m[3]) : null; }
// {d: hari serial, m: menit dalam hari} atau null
function parseDT(v) {
  if (v == null || v === '') return null;
  if (v instanceof Date) return { d: ymd(v.getFullYear(), v.getMonth() + 1, v.getDate()), m: v.getHours() * 60 + v.getMinutes() };
  if (typeof v === 'number') { if (v < 30000 || v > 80000) return null; const d = Math.floor(v); return { d, m: Math.round((v - d) * 1440) }; }
  const s = String(v).trim(); let m;
  const tm = x => (+x[4] || 0) * 60 + (+x[5] || 0);
  const yy = x => (+x < 100 ? 2000 + +x : +x);
  if ((m = /^(\d{1,2})[-\s\/]([A-Za-z]{3,})[-\s\/,]*(\d{2,4})(?:[ T]+(\d{1,2}):(\d{2}))?/.exec(s))) { const mo = MON[m[2].slice(0, 3).toUpperCase()]; if (mo) return { d: ymd(yy(m[3]), mo, +m[1]), m: tm(m) }; }
  if ((m = /^(\d{1,2})[\/.-](\d{1,2})[\/.-](\d{2,4})(?:[ T]+(\d{1,2}):(\d{2}))?/.exec(s))) return { d: ymd(yy(m[3]), +m[2], +m[1]), m: tm(m) };
  if ((m = /^(\d{4})-(\d{1,2})-(\d{1,2})(?:[ T]+(\d{1,2}):(\d{2}))?/.exec(s))) return { d: ymd(+m[1], +m[2], +m[3]), m: tm(m) };
  return null;
}
// null = kosong, undefined = terisi tapi tidak terbaca
function parseDateCell(v) {
  if (v == null || String(v).trim() === '') return null;
  const p = parseDT(v); return p ? p.d : undefined;
}
const isActive = (r, d) => (r.aktif == null || d >= r.aktif) && (r.nonaktif == null || d < r.nonaktif);
const normKey = k => String(k == null ? '' : k).toUpperCase().replace(/[_\s]+/g, ' ').trim();
function pickCol(row, names) {
  for (const k of Object.keys(row)) if (names.includes(normKey(k))) { const v = row[k]; if (v != null && String(v).trim() !== '') return v; }
  return '';
}
const keyOf = s => String(s).replace(/[^A-Za-z0-9_\-]/g, '_');
const COLS = {
  dId: ['DRIVER ID', 'ID DRIVER'], dNama: ['NAMA DRIVER', 'NAMA'], dStatus: ['STATUS'],
  uNopol: ['NOPOL', 'NOPOL UNIT'], uTipe: ['TIPE TRAILER', 'TYPE UNIT', 'TIPE UNIT', 'TYPE', 'TIPE'], uSub: ['SUB STATUS'],
  aktif: ['TANGGAL AKTIF', 'START AKTIF', 'TGL AKTIF', 'MULAI AKTIF'],
  nonaktif: ['TANGGAL NONAKTIF', 'TANGGAL NON AKTIF', 'NONAKTIF', 'TGL NONAKTIF', 'END AKTIF']
};
const DEFAULTS = { sjPool: 'POOL NAGRAK TRAILER', agingSrc: 'tender', agingRed: 3, orRollover: 'keep', gpsClosed: 'CLOSED, FORCECLOSED', bdSkipDeleted: true, nopolUnique: true };
let toastTimer = null;
function toast(msg) { const t = $('#toast'); t.textContent = msg; t.classList.add('show'); clearTimeout(toastTimer); toastTimer = setTimeout(() => t.classList.remove('show'), 3600); }
function debounce(fn, ms) { let t = null; return (...a) => { clearTimeout(t); t = setTimeout(() => fn(...a), ms); }; }

/* =====================================================================
   LAYER DATA: server (kemampuan db artifact) atau lokal (localStorage)
   ===================================================================== */
const CID = 'c' + Math.random().toString(36).slice(2, 10);
let DB = null, MODE = 'local', CAN_WRITE = true, DBREADY = false;

function makeLocalDb() {
  const LSK = LOCAL_KEY;
  let store = {}, had = false;
  try { const raw = localStorage.getItem(LSK); had = !!raw; store = JSON.parse(raw || '{}'); } catch (e) {}
  // pertama kali dibuka (belum ada data lokal) dan file ini membawa snapshot: pakai snapshot sebagai data awal
  if (!had && SEED) { store = JSON.parse(JSON.stringify(SEED)); LOCAL_SEEDED = true; try { localStorage.setItem(LSK, JSON.stringify(store)); } catch (e) {} }
  const ver = {}, subs = new Set();
  const clone = o => JSON.parse(JSON.stringify(o));
  const persist = debounce(() => { try { localStorage.setItem(LSK, JSON.stringify(store)); } catch (e) {} }, 300);
  const has = p => Object.prototype.hasOwnProperty.call(store, p);
  const mkSnap = p => { const ex = has(p); return { id: p.split('/').pop(), exists: ex, data: () => ex ? clone(store[p]) : undefined, metadata: {} }; };
  const colPaths = c => Object.keys(store).filter(p => p.startsWith(c + '/') && p.indexOf('/', c.length + 1) < 0).sort();
  const qSnap = c => { const docs = colPaths(c).map(mkSnap); return { docs, size: docs.length, empty: !docs.length }; };
  const merge = (a, b) => { for (const k of Object.keys(b)) { if (b[k] && typeof b[k] === 'object' && !Array.isArray(b[k]) && a[k] && typeof a[k] === 'object' && !Array.isArray(a[k])) merge(a[k], b[k]); else a[k] = clone(b[k]); } return a; };
  const bump = p => { ver[p] = (ver[p] || 0) + 1; persist(); setTimeout(() => subs.forEach(f => f()), 0); };
  const sub = (sigFn, snapFn, next) => { let last = null; const f = () => { const g = sigFn(); if (g === last) return; last = g; next(snapFn()); }; subs.add(f); setTimeout(f, 0); return () => subs.delete(f); };
  const docRef = p => ({ id: p.split('/').pop(), path: p,
    get: async () => mkSnap(p),
    set: async d => { store[p] = clone(d); bump(p); },
    update: async d => { if (!has(p)) { const e = new Error('dokumen belum ada'); e.code = 'invalid_argument'; throw e; } merge(store[p], d); bump(p); },
    delete: async () => { delete store[p]; bump(p); },
    acquire: async () => ({ acquired: true }),
    onSnapshot: next => sub(() => p + ':' + (ver[p] || 0) + has(p), () => mkSnap(p), next) });
  const colRef = c => ({ path: c, doc: id => docRef(c + '/' + id), get: async () => qSnap(c),
    onSnapshot: next => sub(() => colPaths(c).map(p => p + ':' + (ver[p] || 0)).join('|'), () => qSnap(c), next) });
  return { doc: docRef, collection: colRef };
}
async function initDb() {
  let real = null;
  try {
    if (window.claude && typeof window.claude.use === 'function') real = await window.claude.use('db');
  } catch (e) { real = null; }
  if (real) {
    DB = real; MODE = 'server';
    try { const u = await window.claude.use('user'); if (u && typeof u.can === 'function') { const c = await u.can('data.write'); if (c === false) CAN_WRITE = false; } } catch (e) {}
  } else { DB = makeLocalDb(); MODE = 'local'; }
}
// satu tulisan pada satu waktu per dokumen (aturan db); tulisan berikutnya menunggu yang sebelumnya
const wq = new Map();
function serial(path, fn) { const prev = wq.get(path) || Promise.resolve(); const p = prev.catch(() => {}).then(fn); wq.set(path, p); return p; }
const dbSet = (path, data) => serial(path, () => DB.doc(path).set(data));
const dbDel = path => serial(path, () => DB.doc(path).delete());
function upsert(path, data) {
  return serial(path, async () => {
    const ref = DB.doc(path);
    try { await ref.update(data); }
    catch (e) { if (e && e.code === 'invalid_argument') await ref.set(data); else throw e; }
  });
}
function handleWriteError(e) {
  const c = e && e.code;
  if (c === 'invalid_argument' || c === 'revoked' || c === 'not_granted') { CAN_WRITE = false; renderShareBar(); toast('Tidak bisa menyimpan: akun ini hanya boleh melihat (perlu akses Contributor atau Editor).'); }
  else if (c === 'quota_exceeded') toast('Penyimpanan server penuh. Hapus data lama (mis. file DOTS/SJ) lalu coba lagi.');
  else toast('Gagal menyimpan ke ' + (MODE === 'server' ? 'server' : 'browser') + '. Coba lagi.');
}
async function dbWrite(fn) {
  if (!CAN_WRITE) { toast('Mode baca saja: akun ini tidak bisa mengubah data.'); return false; }
  try { await fn(); return true; } catch (e) { handleWriteError(e); return false; }
}

/* =====================================================================
   STATE
   ===================================================================== */
const S = {
  mDriverDoc: null, mUnitDoc: null, masterDB: [], unitDB: [],
  queueItems: {}, pending: {}, config: {}, settings: { ...DEFAULTS },
  dotsRows: [], dotsIds: [], sjRows: [], sjIds: [], p2hRows: [], p2hIds: [], p2h: new Map(), klinikRows: [], klinikIds: [], klinik: new Map(), p2hBy: new Map(), klinikBy: new Map(), gpsRows: [], gpsIds: [], gpsBy: new Map(), bdRows: [], bdIds: [], bdBy: new Map(), att: [], ship: new Map(), loaded: {}, notes: { drivers: {}, units: {} }
};
const activeDrivers = () => { const t = todaySerial(); return S.masterDB.filter(r => isActive(r, t)); };
const activeUnits = () => { const t = todaySerial(); return S.unitDB.filter(r => isActive(r, t)); };
const queueMap = () => { const m = { ...S.queueItems }; for (const k of Object.keys(S.pending)) if (!m[k]) m[k] = S.pending[k]; return m; };
// setiap baris punya key penyimpanan (a.key), dipasang pada SALINAN baris (objek asli dari database bisa tidak dapat diubah); satu driver bisa punya beberapa baris = putaran antri (round 1, 2, ...). Putaran yang sudah diganti ditandai retired dan tetap terlihat.
const antrian = () => {
  const all = Object.entries(queueMap()).filter(([k, x]) => x && x.id && qdShowQueue(x.id)).map(([k, x]) => ({ ...x, key: k, nama: nmUp(x.nama) }));
  // putaran lama yang penerusnya sudah dihapus (driver tidak punya baris yang berjalan): baris dengan putaran tertinggi kembali berjalan, tidak diredupkan
  const aktif = new Set(all.filter(a => !a.retired).map(a => a.id)), best = new Map();
  all.forEach(a => { if (a.retired && !aktif.has(a.id)) { const b = best.get(a.id); if (!b || (a.round || 1) > (b.round || 1) || ((a.round || 1) === (b.round || 1) && (a.seq || 0) > (b.seq || 0))) best.set(a.id, a); } });
  best.forEach(a => { a.retired = false; });
  return all.sort((a, b) => (a.antri_at || a.seq || 0) - (b.antri_at || b.seq || 0) || (a.seq || 0) - (b.seq || 0));   // urutan = jam antri (paling awal di atas)
};
const antriAktif = () => antrian().filter(a => !a.retired);
const qkey = a => a.key || keyOf(a.id);
/* ---------------------------------------------------------------------
   Absensi -> Status Absensi -> Status Antrian (AVA / NOT AVA)
   Sumber absensi: Monitoring Attendance (per catatan, ada jam)
   Sumber status shipment terakhir driver: GPS Tracking Report
   --------------------------------------------------------------------- */
const settingWords = (s, fb) => String(s == null || String(s).trim() === '' ? fb : s).split(/[,;\n]/).map(x => x.trim().toUpperCase()).filter(Boolean);
const closeSet = () => new Set(settingWords(S.settings.gpsClosed, DEFAULTS.gpsClosed));
const ABS_CLS = { X: 'c-OR', OR: 'c-ONR', O: 'c-O', AIS: 'c-AIS', S: 'c-S' };
const ABS_SET = new Set(['X', 'OR', 'O', 'AIS', 'S', 'N/A']);
// Data yang tidak ada ditulis N/A (bukan "-") supaya jelas bahwa datanya memang tidak ada: tim menghubungi driver (belum absen / klinik / P2H) atau tim IT (data seharusnya sudah masuk).
const naPill = tip => `<span class="pill c-NA"${tip ? ` title="${esc(tip)}"` : ''}>N/A</span>`;
const NA_ABSEN = 'Jam absen tidak ada di file Monitoring Attendance (atau belum ada kode absensi pada tanggal ini). Hubungi driver bila belum absen, atau tim IT bila datanya seharusnya sudah masuk.';
const mtaHtml = s => (s ? dtHtml(s) : naPill(NA_ABSEN));
function absensiPill(st, why) { return ABS_CLS[st] ? `<span class="pill ${ABS_CLS[st]}" title="${esc(why || '')}">${esc(st)}</span>` : `<span class="pill c-NA" title="${esc(why || '')}">N/A</span>`; }
// KODE ABSENSI dibaca dari file Attendance (grid: baris = driver, kolom = tanggal; kode X, OR, O, A, I, S, ADJ).
// JAM ABSEN MTA (hanya untuk kode X dan OR) dibaca dari file Monitoring Attendance. Dokumen lain diabaikan.
function gridCode(id, day) {
  const key = normId(id); let covered = null; const cands = [];
  for (const f of S.att) {
    if (f.meta.kind === 'mon' || !f.idx.has(day)) continue;
    if (!covered || (f.meta.at || 0) > (covered.meta.at || 0)) covered = f;
    const r = f.map.get(key); if (!r) continue;
    cands.push({ at: f.meta.at || 0, f, cell: String((r[2] || [])[f.idx.get(day)] || '').trim().toUpperCase() });
  }
  if (!covered) return { found: false, noFile: true, code: '', file: '' };
  if (!cands.length) return { found: false, noFile: false, code: '', file: covered.meta.name };
  cands.sort((a, b) => b.at - a.at);
  const pick = cands.find(c => c.cell) || cands[0];
  return { found: true, noFile: false, code: pick.cell, file: pick.f.meta.name };
}
// jam Masuk (untuk kode X) atau Dalam Perjalanan (untuk kode OR) terbaru; jika jenisnya tidak ada dipakai jam yang lain
function monTime(id, day, kode) {
  const key = normId(id); let best = null;
  for (const f of S.att) {
    if (f.meta.kind !== 'mon' || !f.idx.has(day)) continue;
    const r = f.map.get(key); if (!r) continue;
    const cell = String((r[2] || [])[f.idx.get(day)] || ''); if (!cell) continue;
    if (!best || (f.meta.at || 0) > best.at) best = { at: f.meta.at || 0, cell, file: f.meta.name };
  }
  if (!best) return { time: '', file: '' };
  const t = {}; best.cell.split(';').forEach(p => { const [c, tm] = p.split('@'); if (tm) t[c] = tm; });
  return { time: kode === 'OR' ? (t.OR || t.X || '') : (t.X || t.OR || ''), file: best.file };
}
// kode = hasil baca file Attendance (A/I/S digabung AIS; ADJ = OR bila ada shipment valid H-3 s/d H, selain itu X); status = kode setelah aturan "X + shipment DOTS -> OR" (informasi di RAW Data)
function absensiOf(id, day) {
  const a = gridCode(id, day);
  const na = why => ({ status: 'N/A', kode: 'N/A', raw: a.code || '', time: '', file: a.file, why });
  if (!a.found || !a.code) return na(a.noFile ? 'Tanggal ini tidak ada di file Attendance yang diupload' : !a.found ? 'Driver tidak ada di file Attendance' : 'Kolom absensi kosong pada tanggal ini (belum absen)');
  const raw = a.code; let code = raw, pre = '';
  if (code === 'ADJ') {
    let ok = false; for (let d = day - 3; d <= day; d++) if (shipCount(id, d) > 0) { ok = true; break; }
    code = ok ? 'OR' : 'X'; pre = ok ? 'ADJ + ada shipment valid pada H-3 s/d H, dianggap OR. ' : 'ADJ tanpa shipment H-3 s/d H, dianggap X. ';
  }
  let kode;
  if (code === 'X' || code === 'OR' || code === 'O') kode = code;
  else if (code === 'A' || code === 'I' || code === 'S') kode = 'AIS';
  else return na('Kode "' + raw + '" tidak dikenal');
  let time = '', monFile = '';
  if (kode === 'X' || kode === 'OR') { const m = monTime(id, day, kode); time = m.time; monFile = m.file; }
  const status = kode === 'X' && shipCount(id, day) > 0 ? 'OR' : kode;
  const why = pre + ({ X: 'X (masuk, absen di pool)', OR: 'OR (on road)', O: 'O (Off)', AIS: 'A / I / S (alpha, izin, sakit) digabung menjadi AIS' }[kode]) + ' dari file Attendance' + (status !== kode ? '; ada shipment valid pada tanggal ini, Status Absensi di RAW Data = ' + status : '');
  return { kode, status, raw, time, file: a.file, monFile, why };
}
const kodeWhy = (ab, day) => {
  if (ab.kode === 'N/A') return ab.why;
  let s = ab.why.replace(/; ada shipment valid.*$/, '');
  if (ab.kode === 'X' || ab.kode === 'OR') s += ab.time ? ' · jam ' + ab.time + ' dari Monitoring Attendance' : ' · jam belum ada di file Monitoring Attendance';
  if (ab.status !== ab.kode) s += ` | Di RAW Data Status Absensi = ${ab.status} (ada shipment valid pada tanggal ini); Status Antrian tidak terpengaruh`;
  return s;
};
// shipment valid driver pada tanggal onroad tertentu dari DOTS
function shipRowsOn(id, day) {
  const out = [], seen = new Set();
  for (const r of S.dotsRows) {
    const [ship, d1, d2, td, tm, ex, , , , , , fpt, nopol, proses] = r;
    if (d1 !== id && d2 !== id) continue;
    if (onDay(td, tm, ex) !== day || seen.has(ship)) continue; seen.add(ship);
    out.push({ ship, td, tm, fpt, nopol: nopol || '', proses: proses || '' });
  }
  return out;
}
// shipment terakhir driver menurut GPS Tracking pada/ sebelum tanggal tsb (shipment berstatus cancel dilewati)
function lastGps(id, day) {
  const arr = S.gpsBy.get(normId(id)); if (!arr) return null;
  for (let i = arr.length - 1; i >= 0; i--) if (arr[i].day <= day && arr[i].status !== 'CANCEL') return arr[i];
  return null;
}
// Status Antrian: Kode Absensi X = AVA (wajib, walau shipment terakhirnya belum closed: driver bisa diberi order baru, 2 shipment atau lebih dalam sehari).
// Kode OR menjadi AVA hanya jika shipment terakhirnya di GPS Tracking berstatus CLOSED / FORCECLOSED. Kode lain = NOT AVA.
function statusAntrianOf(id, day) {
  const ab = absensiOf(id, day), res = { status: 'NOT AVA', ab, why: '', gps: lastGps(id, day) };
  if (ab.kode === 'X') { res.status = 'AVA'; res.why = 'Kode Absensi X (Masuk): wajib AVA' + (ab.status === 'OR' ? ', walau sudah punya shipment hari ini (bisa mendapat order baru)' : ''); return res; }
  if (ab.kode !== 'OR') { res.why = 'Kode Absensi ' + ab.kode + ' (bukan X)'; return res; }
  if (!S.gpsRows.length) { res.why = 'Kode OR; file GPS Tracking belum diupload'; return res; }
  const g = res.gps;
  if (!g) { res.why = 'Kode OR; driver tidak ditemukan di GPS Tracking (atau semua shipment-nya cancel)'; return res; }
  if (closeSet().has(g.status)) { res.status = 'AVA'; res.closed = g.end || null; res.why = `Kode OR, shipment terakhir ${g.ship} berstatus ${g.status.toLowerCase()} di GPS Tracking` + (g.end ? ` · closed ${soTxt(g.end)} (waktu aktual terakhir)` : ' · jam closed belum ada (upload ulang file GPS Tracking)'); return res; }
  res.why = `Kode OR, shipment terakhir ${g.ship} berstatus ${g.status.toLowerCase()} (belum closed)`; return res;
}
function attCoverage() {
  const t = todaySerial(), grid = S.att.filter(f => f.meta.kind !== 'mon'), mon = S.att.filter(f => f.meta.kind === 'mon');
  if (!grid.length) return { ok: false, msg: 'Belum ada file <b>Attendance</b> (sumber Kode Absensi). Upload di <b>Upload</b>; tanpa itu semua driver N/A dan NOT AVA.' };
  if (!grid.some(f => f.idx.has(t))) { const d0 = Math.min(...grid.map(f => f.meta.d0)), d1 = Math.max(...grid.map(f => f.meta.d1)); return { ok: false, msg: `Tanggal hari ini (${fmtSerial(t)}) tidak ada di file Attendance yang diupload (cakupan ${fmtSerial(d0)} s/d ${fmtSerial(d1)}). Semua driver N/A dan NOT AVA. Upload file Attendance periode terbaru di <b>Upload</b>.` }; }
  if (!mon.some(f => f.idx.has(t))) return { ok: false, msg: `Tanggal hari ini (${fmtSerial(t)}) belum ada di file <b>Monitoring Attendance</b>: jam Absen MTA untuk kode X dan OR tampil N/A. Kode Absensi tetap dari file Attendance.` };
  return { ok: true, msg: '' };
}
// Absen MTA: kode X / OR -> "dd-Mmm | HH:MM" dari Monitoring Attendance (kosong = N/A bila jamnya tidak ada); kode O / AIS -> "-" (tidak perlu jam); kode N/A -> kosong (N/A)
const absenMtaOf = (ab, day) => ((ab.kode === 'X' || ab.kode === 'OR') ? (ab.time ? fmtDM(day) + ' | ' + ab.time : '') : (ab.kode === 'O' || ab.kode === 'AIS') ? '-' : '');
function driverListToday() {
  const t = todaySerial();
  return activeDrivers().map(d => { const sa = statusAntrianOf(d.id, t), ab = sa.ab; return { id_driver: d.id, nama: d.nama, jam_absen: ab.time || '', absen_mta: absenMtaOf(ab, t), kode: ab.kode, kode_why: kodeWhy(ab, t), absensi: ab.status, absensi_raw: ab.raw, absensi_why: ab.why, status_lokasi: sa.status, why: sa.why, closed: sa.closed || null }; });
}
function onDay(td, tm, ex) { let on = td; if (ex != null && ex > on) on = ex; return on; }   // tanggal onroad = tanggal terbesar antara Execution Date dan Tanggal Tender (aturan cut-off tender sudah dihapus)
// daftar shipment satu driver pada satu tanggal onroad (aturan sama dengan rebuildShip)
function shipList(id, day) {
  const out = [], seen = new Set();
  for (const r of S.dotsRows) {
    const [ship, d1, d2, td, tm, ex, cu, no, rt] = r;
    if (d1 !== id && d2 !== id) continue;
    if (onDay(td, tm, ex) !== day || seen.has(ship)) continue; seen.add(ship);
    out.push({ ship, cust: cu || '', order: no || '', route: rt || '', detail: r.length > 6 });
  }
  return out;
}
// pengelompokan tipe truk: 20 FT / 40 FT / lainnya (memuat 20 dan 40 sekaligus atau selain itu)
function truckGroup(tp) { const u = String(tp || '').toUpperCase(), a = /20/.test(u), b = /40/.test(u); return a && !b ? '20' : b && !a ? '40' : 'o'; }
const grpKey = g => (g === '20' ? 't20' : g === '40' ? 't40' : 'oth');
// satu putaran data DOTS untuk tanggal onroad `day`:
//  - OR: driver aktif Master yang punya shipment; tiap driver dihitung SEKALI menurut tipe truk shipment dengan tender terbaru
//  - SPK / SPO: jumlah shipment unik menurut kolom TIPE SHIPMENT, dirinci per tipe truk; HANYA STATUS FPT NEW & CARRY OVER (CANCEL, REPLAN, dan status lain tidak dihitung)
function dayTotals(day) {
  const act = new Set(activeDrivers().map(d => d.id)), seen = new Set(), best = new Map();
  const mk = () => ({ n: 0, t20: 0, t40: 0, oth: 0, types: {} }), sp = { SPK: mk(), SPO: mk() };
  const addType = (o, tp) => { const k = String(tp || '').trim() || '(kosong)'; o.types[k] = (o.types[k] || 0) + 1; };
  const first = S.dotsRows[0], hasFPT = !first || first.length > 11;
  for (const r of S.dotsRows) {
    const [ship, d1, d2, td, tm, ex, , , , tp, ts, fpt] = r;
    if (onDay(td, tm, ex) !== day) continue;
    const g = truckGroup(tp);
    // data lama (belum menyimpan STATUS FPT) tetap dihitung seperti sebelumnya sampai DOTS Report diupload ulang
    const counted = !hasFPT || fpt === 'NEW' || fpt === 'CARRY OVER';
    if (counted && !seen.has(ship)) { seen.add(ship); const o = sp[ts]; if (o) { o.n++; o[grpKey(g)]++; if (g === 'o') addType(o, tp); } }
    const key = td * 1440 + tm;
    for (const d of [d1, d2]) if (d && act.has(d)) { const c = best.get(d); if (!c || key >= c.key) best.set(d, { key, g, tp }); }
  }
  const or = mk(); or.n = best.size; best.forEach(v => { or[grpKey(v.g)]++; if (v.g === 'o') addType(or, v.tp); });
  return { sp, or, best, hasType: !first || first.length > 9, hasTS: !first || first.length > 10, hasFPT };
}
// "Lainnya" = TIPE TRUCK yang tidak memuat 20 maupun 40, atau memuat keduanya; tooltip merinci nilai TIPE TRUCK aslinya
const othTip = o => 'Tipe truk di luar 20 FT / 40 FT: ' + Object.entries(o.types || {}).sort((a, b) => b[1] - a[1] || (a[0] < b[0] ? -1 : 1)).map(([k, v]) => k + ' ' + v).join(', ');
const detailHtml = o => `<span>20 FT <b>${o.t20}</b></span><span>40 FT <b>${o.t40}</b></span>${o.oth ? `<span class="oth" title="${esc(othTip(o))}">Lainnya <b>${o.oth}</b></span>` : ''}`;
// ---- Status P2H per UNIT (kunci = nopol, spasi/strip/titik diabaikan) ----
const normPlat = s => String(s == null ? '' : s).toUpperCase().replace(/[^A-Z0-9]/g, '');
// catatan terbaru pada/ sebelum akhir tanggal aplikasi (baris format lama tanpa waktu dianggap selalu berlaku)
// Saat evaluasi: untuk hari aplikasi = SEKARANG (tanggal aplikasi + jam sebenarnya); untuk tanggal lain (RAW, History lama) = akhir hari itu.
const keyAt = day => { const t = todaySerial(), d = day == null ? t : day; if (d === t) { const n = nowDate(); return d * 1440 + n.getHours() * 60 + n.getMinutes(); } return d * 1440 + 1439; };
// catatan terbaru sampai saat evaluasi. Catatan P2H / Klinik hanya berlaku 24 jam: dari 08.00 hari ke-1 sampai 07.59 hari ke-2.
const asOfPick = (arr, day) => { if (!arr) return null; const end = keyAt(day); for (let i = arr.length - 1; i >= 0; i--) if (arr[i].key <= end) return arr[i]; return null; };
const isExpired = (rec, day) => rec.key >= 0 && keyAt(day) - rec.key >= 1440;
function buildBy(rows) {   // baris: [kunci, status, waktu(serial*1440+menit), catatan, asli]
  const m = new Map();
  for (const r of rows) { if (!m.has(r[0])) m.set(r[0], []); m.get(r[0]).push({ st: String(r[1] || ''), key: r.length > 2 && r[2] != null ? r[2] : -1, note: r[3] || '', orig: r[4] || '' }); }
  for (const a of m.values()) a.sort((x, y) => x.key - y.key);
  return m;
}
const p2hRec = (nopol, day) => { const k = normPlat(nopol), r = k ? asOfPick(S.p2hBy.get(k), day) : null; return r ? { r, expired: isExpired(r, day) } : null; };
const p2hOf = (nopol, day) => { const x = p2hRec(nopol, day); return x && !x.expired ? x.r.st : ''; };
// warna ditebak dari kata pada status; teks yang tampil tetap teks asli dari file
function stClass(st) {
  const u = String(st).toUpperCase();
  if (/\b(PREVENTIVE|FOM|MEDICATION|CASE)\b/.test(u)) return 'c-S';   // oranye: perlu perhatian (fit dengan obat, perawatan, kasus)
  if (/\b(NOT|TIDAK|BELUM|GAGAL|FAIL|NG|REJECT|RUSAK|ABNORMAL|DEFECT|TEMUAN|NO|UNFIT|SAKIT|DITOLAK|BREAKDOWN|BD)\b/.test(u)) return 'c-NG';
  if (/\b(OK|FIT|READY|LOLOS|PASS|NORMAL|SIAP|LAYAK|BAIK|GOOD|SUDAH|DONE|CLEAR|SEHAT)\b/.test(u)) return 'c-OR';
  return 'c-O';
}
const p2hClass = stClass;
// ---- Status Unit (BD / Ready) per NOPOL dari file bd_export ----
// SO Date terakhir untuk nopol itu: jika Handover Time (HO Time) belum terisi -> BD; selain itu Ready.
// "" jika nopol belum diisi; "-" jika file bd_export belum diupload. Mengikuti Tanggal aplikasi (SO setelah tanggal itu diabaikan,
// handover setelah tanggal itu dianggap belum). SO yang dihapus (SO Delete terisi) diabaikan kecuali diatur lain di Setting.
function unitInfo(nopol, day) {
  const d = day == null ? todaySerial() : day;
  if (!String(nopol || '').trim()) return { status: '', rec: null };
  if (!S.bdRows.length) return { status: 'N/A', rec: null };
  const arr = S.bdBy.get(normPlat(nopol)), endKey = d * 1440 + 1439, skip = S.settings.bdSkipDeleted !== false; let rec = null;
  if (arr) for (let i = arr.length - 1; i >= 0; i--) { const r = arr[i]; if (r.so <= endKey && !(skip && r.del)) { rec = r; break; } }
  const bd = !!rec && !(rec.ho != null && rec.ho <= endKey);
  return { status: bd ? 'BD' : 'Ready', rec };
}
const unitStatus = (nopol, day) => unitInfo(nopol, day).status;
const soTxt = k => fmtSerial(Math.floor(k / 1440)) + ' ' + pad(Math.floor((k % 1440) / 60)) + ':' + pad(k % 60);
// ---- Aturan Nopol di antrian: satu nopol hanya boleh dipakai satu driver pada satu tanggal ----
// tanggal baris = tanggal absensi saat driver dimasukkan (absen_day), atau tanggal antri untuk data lama
const rowDay = a => (a.absen_day != null ? a.absen_day : dayOfDate(new Date(a.antri_at || a.seq || Date.now())));
function queueNopolOwner(key, day, exceptId) { return antrian().find(a => !a.retired && a.id !== exceptId && normPlat(a.nopol_unit) === key && rowDay(a) === day) || null; }
function nopolDupMap(list) {   // id -> keterangan, untuk baris yang nopolnya ganda pada tanggal yang sama (data lama / hasil pengaturan "boleh")
  const m = new Map(); if (S.settings.nopolUnique === false) return m;
  list = list.filter(a => !a.retired);
  const g = new Map();
  for (const a of list) { const k = normPlat(a.nopol_unit); if (!k) continue; const key = rowDay(a) + '|' + k; if (!g.has(key)) g.set(key, []); g.get(key).push(a); }
  for (const arr of g.values()) if (arr.length > 1) for (const a of arr) m.set(a.id, 'Nopol ganda dengan ' + arr.filter(x => x !== a).map(x => x.nama).join(', ') + ' pada ' + fmtSerial(rowDay(a)));
  return m;
}
// daftar pilihan nopol: nopol yang sudah dipakai baris lain pada tanggal hari ini tidak ditawarkan lagi
function refreshUnitList() {
  const el = $('#unit-list'); if (!el) return;
  const t = todaySerial(), used = new Set(S.settings.nopolUnique === false ? [] : antrian().filter(a => !a.retired && rowDay(a) === t).map(a => normPlat(a.nopol_unit)).filter(Boolean));
  el.innerHTML = activeUnits().filter(u => !used.has(normPlat(u.nopol))).map(u => `<option value="${esc(u.nopol)}"></option>`).join('');
}
function unitWhy(ui, np) {
  if (!String(np || '').trim()) return 'Nopol belum diketahui (belum diisi di antrian dan tidak ada di GPS Tracking)';
  if (ui.status === 'N/A') return 'Data bd_export belum ada (file belum diupload). Hubungi tim IT';
  if (ui.status === 'BD') return `SO terakhir ${soTxt(ui.rec.so)} (${ui.rec.id || '-'}), HO Time belum${ui.rec.prob ? '. ' + ui.rec.prob : ''}`;
  return ui.rec ? `SO terakhir ${soTxt(ui.rec.so)} sudah handover ${soTxt(ui.rec.ho)}` : 'Tidak ada SO breakdown untuk unit ini';
}
function unitCell(nopol, day) {
  const { status, rec } = unitInfo(nopol, day);
  if (!status) return '';
  if (status === 'N/A') return naPill('Data bd_export belum ada (file belum diupload). Hubungi tim IT.');
  const tip = status === 'BD' ? `SO terakhir ${soTxt(rec.so)} (${rec.id || '-'}), HO Time belum${rec.prob ? '. ' + rec.prob : ''}` : (rec ? `SO terakhir ${soTxt(rec.so)} sudah handover ${soTxt(rec.ho)}` : 'Tidak ada SO breakdown untuk unit ini');
  return `<span class="pill ${status === 'BD' ? 'c-NG' : 'c-OR'}" title="${esc(tip)}">${status}</span>`;
}
// ---- Status Klinik per DRIVER (kunci = ID driver, tanda baca diabaikan) ----
// "-" jika driver tidak ada di data klinik atau datanya belum diupload. Satu fungsi dipakai Dashboard, History, dan Master Driver
const normId = s => String(s == null ? '' : s).toUpperCase().replace(/[^A-Z0-9]/g, '');
const klinikRec = (id, day) => { const r = asOfPick(S.klinikBy.get(normId(id)), day); return r ? { r, expired: isExpired(r, day) } : null; };
const klinikOf = (id, day) => { const x = klinikRec(id, day); return x && !x.expired ? x.r.st : ''; };
const untilTxt = key => soTxt(key + 1439);   // catatan berlaku sampai satu menit sebelum 24 jam
function klinikCell(id, day) {
  const x = klinikRec(id, day);
  if (!x) return naPill('Tidak ada data klinik untuk driver ini. Hubungi driver bila belum periksa klinik, atau tim IT bila datanya seharusnya sudah masuk.');
  const r = x.r;
  if (x.expired) return naPill('Kedaluwarsa: pemeriksaan terakhir ' + soTxt(r.key) + ' (' + r.st + ') berlaku sampai ' + untilTxt(r.key) + '. Perlu pemeriksaan klinik ulang; hubungi driver.');
  const tip = [r.key >= 0 ? 'Pemeriksaan ' + soTxt(r.key) + ', berlaku sampai ' + untilTxt(r.key) : '', r.st === 'FOM' ? 'FOM = Fit On Medication' : '', r.note].filter(Boolean).join(' · ');
  return `<span class="pill ${stClass(r.st)}"${tip ? ` title="${esc(tip)}"` : ''}>${esc(r.st)}</span>`;
}
// kosong hanya jika Nopol belum diisi; N/A jika belum ada P2H untuk unit itu atau P2H sudah kedaluwarsa (lewat 24 jam)
function p2hCell(nopol, day) {
  if (!String(nopol || '').trim()) return '';
  const x = p2hRec(nopol, day);
  if (!x) return naPill('Tidak ada data P2H untuk unit ini. Hubungi driver bila belum P2H, atau tim IT bila datanya seharusnya sudah masuk.');
  const r = x.r;
  if (x.expired) return naPill('Kedaluwarsa: inspeksi terakhir ' + soTxt(r.key) + ' (' + r.st + ') berlaku sampai ' + untilTxt(r.key) + '. Perlu P2H ulang; hubungi driver.');
  const tip = [r.key >= 0 ? 'Inspeksi ' + soTxt(r.key) + ', berlaku sampai ' + untilTxt(r.key) : '', r.note ? 'Item tidak OK: ' + r.note : ''].filter(Boolean).join(' · ');
  return `<span class="pill ${p2hClass(r.st)}"${tip ? ` title="${esc(tip)}"` : ''}>${esc(r.st)}</span>`;
}
// ---- Aging SJ Outs. per driver (angka terbesar = SJ terlama) ----
const sjUsable = () => S.sjRows.length > 0 && S.sjRows[0].length >= 7;
function sjByDriver(day) { const m = new Map(); if (!sjUsable()) return m; for (const r of sjRowsView(day)) { if (!m.has(r.id)) m.set(r.id, []); m.get(r.id).push(r); } return m; }
function sjCell(id, m) {
  const rows = m.get(id);
  if (!rows || !rows.length) return sjUsable() ? '<span class="note" title="Tidak ada SJ outstanding">-</span>' : naPill('Data SJ belum ada (file Download Detail SJ belum diupload). Hubungi tim IT.');
  const ages = rows.map(r => r.aging).filter(a => a != null);
  const mx = ages.length ? Math.max(...ages) : null;
  return `<button class="or-link" data-sj="${esc(id)}" title="Klik untuk lihat SJ outstanding"><span class="age${mx != null && mx >= S.settings.agingRed ? ' red' : ''}">${mx == null ? '-' : mx}</span>${rows.length > 1 ? `<div class="note">${rows.length} SJ</div>` : ''}</button>`;
}
// jumlah shipment per driver per tanggal onroad (aturan SIGAP: tender lewat cut-off => H+1; tanggal terbesar antara tender & execution date)
function rebuildShip() {
  const cnt = new Map(), seen = new Set();
  for (const r of S.dotsRows) {
    const [ship, d1, d2, td, tm, ex] = r;
    const on = onDay(td, tm, ex);
    for (const d of [d1, d2]) {
      if (!d) continue;
      const k = ship + '|' + d + '|' + on; if (seen.has(k)) continue; seen.add(k);
      const kk = d + '|' + on; cnt.set(kk, (cnt.get(kk) || 0) + 1);
    }
  }
  S.ship = cnt;
}
const shipCount = (id, day) => S.ship.get(id + '|' + day) || 0;
// opsi logo QUEDRIVE (ikon garis: .t = truk/jalur oranye, .p = driver biru)
const LOGOS = {"A": "<svg class=\"lg\" viewBox=\"0 0 24 24\"><g class=\"t\" transform=\"translate(0.4 10.6) scale(.48)\" stroke-width=\"3.7\"><rect x=\"1.5\" y=\"4.5\" width=\"11.5\" height=\"9.5\" rx=\"1\"/><path d=\"M5.5 7v4.5M9 7v4.5\" stroke-width=\"2.04\"/><path d=\"M1.5 16.5H13.5\"/><path d=\"M16 16.5V9.2h3.6l2.9 3.1v4.2z\"/><circle cx=\"3.9\" cy=\"19.3\" r=\"1.75\" fill=\"currentColor\" stroke=\"none\"/><circle cx=\"8\" cy=\"19.3\" r=\"1.75\" fill=\"currentColor\" stroke=\"none\"/><circle cx=\"17.5\" cy=\"19.3\" r=\"1.75\" fill=\"currentColor\" stroke=\"none\"/><circle cx=\"21.3\" cy=\"19.3\" r=\"1.75\" fill=\"currentColor\" stroke=\"none\"/></g><g class=\"p\"><circle cx=\"17.2\" cy=\"6.2\" r=\"3.2\"/><path d=\"M11.6 20.6v-3.4c0-3 2.4-4.9 5.6-4.9s5.6 1.9 5.6 4.9v3.4\"/></g></svg>"};
function applyLogo() { const b = $('#brandIcon'); if (!b || b.dataset.logo === 'A') return; b.innerHTML = LOGOS.A; b.dataset.logo = 'A'; }   // satu logo saja (A: driver besar)
function applySettings() { S.settings = { ...DEFAULTS }; applyLogo(); }   // parameter DIKUNCI (tidak bisa diubah pengguna): nilai tetap = DEFAULTS; isi lama di server diabaikan
function applyMasters() {
  S.masterDB = S.mDriverDoc && Array.isArray(S.mDriverDoc.rows)
    ? S.mDriverDoc.rows.map(r => ({ id: r[0], nama: nmUp(r[1]), status: r[2] || '', aktif: r[3], nonaktif: r[4] })) : [...initialMasterDrivers];
  S.unitDB = S.mUnitDoc && Array.isArray(S.mUnitDoc.rows)
    ? S.mUnitDoc.rows.map(r => ({ nopol: r[0], type: r[1], sub: r[2] || '', aktif: r[3], nonaktif: r[4] })) : [...initialMasterUnits];
}
applyMasters();

/* =====================================================================
   SUBSCRIBE
   ===================================================================== */
const chunkNo = id => { const m = /^c(\d+)$/.exec(id); return m ? +m[1] : 1e9; };
function collectChunks(snap) {
  const docs = snap.docs.slice().sort((a, b) => chunkNo(a.id) - chunkNo(b.id));
  return { ids: docs.map(d => d.id), rows: [].concat(...docs.map(d => (d.data() || {}).rows || [])) };
}
function subscribeAll() {
  const onErr = e => { if (e && (e.code === 'revoked')) { CAN_WRITE = false; renderShareBar(); } };
  DB.doc('app/queue').onSnapshot(s => { S.queueItems = (s.exists && s.data().items) || {}; for (const k of Object.keys(S.pending)) if (S.queueItems[k]) delete S.pending[k]; S.loaded.queue = true; renderQueueSafe(); renderListDriver(); updateDashboard(); refreshOpenViews(); }, onErr);
  DB.collection('att').onSnapshot(s => {
    S.att = s.docs.map(d => { const x = d.data() || {}, rows = x.rows || [], days = x.days || []; return { id: d.id, meta: x.meta || {}, days, rows, map: new Map(rows.map(r => [normId(r[0]), r])), idx: new Map(days.map((v, i) => [v, i])) }; });
    S.loaded.att = true; renderListDriver(); renderMasterDriver(); updateDashboard(); renderQueueSafe(); refreshOpenViews();
  }, onErr);
  DB.collection('bd').onSnapshot(s => {
    const c = collectChunks(s); S.bdRows = c.rows; S.bdIds = c.ids; S.bdBy = new Map();
    for (const r of c.rows) { if (!S.bdBy.has(r[0])) S.bdBy.set(r[0], []); S.bdBy.get(r[0]).push({ nopol: r[1], so: r[2], ho: r[3], del: !!r[4], status: r[5], pool: r[6], prob: r[7], id: r[8] }); }
    for (const a of S.bdBy.values()) a.sort((x, y) => x.so - y.so || (x.id < y.id ? -1 : 1));
    S.loaded.bd = true; renderQueueSafe(); refreshOpenViews();
  }, onErr);
  DB.collection('gps').onSnapshot(s => {
    const c = collectChunks(s); S.gpsRows = c.rows; S.gpsIds = c.ids; S.gpsBy = new Map();
    for (const r of c.rows) { const k = normId(r[2]); if (!S.gpsBy.has(k)) S.gpsBy.set(k, []); S.gpsBy.get(k).push({ ship: r[0], nopol: r[1], drv: r[2], day: r[3], status: String(r[4] || '').toUpperCase(), start: r[5], end: r[6] || null }); }
    for (const a of S.gpsBy.values()) a.sort((x, y) => x.day - y.day || x.start - y.start || (x.ship < y.ship ? -1 : 1));
    S.loaded.gps = true; renderListDriver(); renderMasterDriver(); updateDashboard(); renderQueueSafe(); refreshOpenViews();
  }, onErr);
  DB.doc('app/config').onSnapshot(s => { S.config = s.exists ? s.data() : {}; applySettings(); rebuildShip(); S.loaded.config = true; renderAllData(); afterLoad(); }, onErr);
  DB.doc('app/notes').onSnapshot(s => { const x = (s.exists && s.data()) || {}; S.notes = { drivers: x.drivers || {}, units: x.units || {} }; renderMasterDriver(); renderMasterUnit(); renderQueueSafe(); refreshOpenViews(); }, onErr);
  DB.doc('app/master_driver').onSnapshot(s => { S.mDriverDoc = s.exists ? s.data() : null; applyMasters(); S.loaded.md = true; renderMasterDriver(); renderListDriver(); renderQueueSafe(); updateDashboard(); refreshOpenViews(); }, onErr);
  DB.doc('app/master_unit').onSnapshot(s => { S.mUnitDoc = s.exists ? s.data() : null; applyMasters(); S.loaded.mu = true; renderMasterUnit(); refreshOpenViews(); }, onErr);
  DB.collection('dots').onSnapshot(s => { const c = collectChunks(s); S.dotsRows = c.rows; S.dotsIds = c.ids; rebuildShip(); S.loaded.dots = true; renderListDriver(); renderMasterDriver(); renderQueueSafe(); updateDashboard(); refreshOpenViews(); }, onErr);
  DB.collection('p2h').onSnapshot(s => { const c = collectChunks(s); S.p2hRows = c.rows; S.p2hIds = c.ids; S.p2hBy = buildBy(c.rows); S.loaded.p2h = true; renderQueueSafe(); renderMasterUnit(); refreshOpenViews(); }, onErr);
  DB.collection('klinik').onSnapshot(s => { const c = collectChunks(s); S.klinikRows = c.rows; S.klinikIds = c.ids; S.klinikBy = buildBy(c.rows); S.loaded.klinik = true; renderQueueSafe(); renderMasterDriver(); refreshOpenViews(); }, onErr);
  DB.collection('sj').onSnapshot(s => { const c = collectChunks(s); S.sjRows = c.rows; S.sjIds = c.ids; S.loaded.sj = true; renderQueueSafe(); refreshOpenViews(); }, onErr);
}
let afterLoadDone = false;
function afterLoad() {
  if (afterLoadDone || !S.loaded.config) return;
  afterLoadDone = true;
  maybeRollover().then(() => {
    const k = dateKey(todaySerial());
    if (CAN_WRITE && !((S.config.historyDates || []).includes(k))) scheduleHistorySave();
  });
}

/* =====================================================================
   LEASE (penguncian tugas antar pengguna)
   ===================================================================== */
async function tryLease(name, ttlMs) {
  try { const r = await DB.doc('app/' + name).acquire({ holder: CID, ttlMs }); return !!r.acquired; } catch (e) { return false; }
}
/* =====================================================================
   LIST DRIVER & LIST ANTRIAN
   ===================================================================== */
const LD_COLS = [
  { label: 'ID Driver', cls: 'l', get: d => d.id_driver }, { label: 'Nama', cls: 'l', get: d => d.nama }, { label: 'Kode Absensi', get: d => d.kode },
  { label: 'Absen MTA', get: d => d.absen_mta || 'N/A' }, { label: 'Status Antrian', get: d => d.status_lokasi }
];
// ID Driver dipenggal setelah tanda strip (DM- / 03668) supaya kolom ID sempit dan kolom Nama lebih lebar; <wbr> dipakai karena strip diikuti angka tidak otomatis boleh dipenggal
const idBr = s => esc(s).replace(/-/g, '-<wbr>');
function renderListDriver() {
  const q = $('#searchDriver').value.trim().toLowerCase();
  const inQueue = new Set(antriAktif().map(a => a.id));
  const rowsAll = driverListToday().filter(d => !inQueue.has(d.id_driver)), nAva = rowsAll.filter(d => d.status_lokasi === 'AVA').length;
  $('#ldInfo').innerHTML = `<span class="pill c-OR" title="Driver yang bisa langsung masuk antrian">AVA</span> <b>${nAva}</b> · <span class="pill c-O" title="Belum bisa langsung masuk antrian">NOT AVA</span> <b>${rowsAll.length - nAva}</b>`;
  const rows = rowsAll.filter(d => !q || d.id_driver.toLowerCase().includes(q) || d.nama.toLowerCase().includes(q) || noteDriver(d.id_driver).toLowerCase().includes(q));
  // urutan: kode X di atas, lalu OR, AIS, S, O, N/A; dalam tiap kode, absen tertua (jam Absen MTA paling awal) di atas, yang tanpa jam di bawahnya
  const KO = { X: 0, OR: 1, AIS: 2, O: 3, S: 4, 'N/A': 5 };
  // X di atas; lalu OR yang sudah AVA (shipment terakhir closed di GPS Tracking) diurutkan menurut JAM CLOSED, bukan jam absen; lalu OR lain, AIS, O, N/A menurut jam absen
  const grp = d => (d.kode === 'X' ? 0 : d.kode === 'OR' ? (d.status_lokasi === 'AVA' ? 1 : 2) : (KO[d.kode] ?? 9) + 1);
  const byTime = (x, y) => (x && y ? (x < y ? -1 : x > y ? 1 : 0) : x ? -1 : y ? 1 : 0);
  rows.sort((a, b) => (grp(a) - grp(b)) || (grp(a) === 1 ? byTime(a.closed, b.closed) : 0) || byTime(a.jam_absen, b.jam_absen) || (a.id_driver < b.id_driver ? -1 : 1));
  SFREG.ld = { names: LD_COLS.map(c => c.label), matrix: () => sfMatrix(rows, LD_COLS), render: renderListDriver };
  $('#ldHead').innerHTML = '<th>No</th>' + LD_COLS.map((c, i) => sfTh('ld', i, c.label, c.cls)).join('') + '<th>Aksi</th>';
  const view = sfApply('ld', rows, LD_COLS);
  $('#badge-total-absen').textContent = (view.length !== rows.length ? view.length + ' dari ' : '') + rows.length + ' Driver';
  $('#resetLD').hidden = !sfActive('ld');
  const cov = attCoverage(), w = $('#attWarn'); if (w) { w.hidden = cov.ok; w.innerHTML = cov.ok ? '' : cov.msg; }
  $('#tbDriver').innerHTML = view.length ? view.map((d, i) => {
    const ava = d.status_lokasi === 'AVA';
    const orWarn = !ava && (d.kode === 'OR' || d.kode === 'N/A');   // OR yang belum AVA: tombol + abu-abu, boleh diklik dengan konfirmasi
    const act = ava ? `<button class="ibtn add" data-act="add" data-id="${esc(d.id_driver)}" title="Masukan ke antrian" aria-label="Masukan ke antrian">${IC_ADD}</button>` : orWarn ? `<button class="ibtn add muted" data-act="add" data-warn="1" data-id="${esc(d.id_driver)}" title="${d.kode === 'N/A' ? 'Belum absen (Kode Absensi N/A): masih bisa masuk antrian, akan ada pemberitahuan dulu' : 'Belum AVA: shipment terakhir belum closed / forceclosed. Klik untuk masuk antrian dengan konfirmasi'}" aria-label="Masukan ke antrian (ada konfirmasi)">${IC_ADD}</button>` : `<span class="na-ic">${IC_BAN}</span>`;
    return `<tr><td class="num">${i + 1}</td><td class="l idc">${idBr(d.id_driver)}</td><td class="l nm" title="${esc(d.nama)}">${esc(d.nama)}${noteHtml(noteDriver(d.id_driver))}</td>` +
      `<td>${absensiPill(d.kode, d.kode_why)}</td><td class="mono wrap dt">${mtaHtml(d.absen_mta)}</td><td><span class="pill ${ava ? 'c-OR' : 'c-O'}" title="${esc(d.why)}">${esc(d.status_lokasi)}</span></td><td>${act}</td></tr>`;
  }).join('') : `<tr><td colspan="7" class="empty-row">Tidak ada driver</td></tr>`;
}
// ---- Status Order per baris antrian: SATU definisi untuk kartu (Belum / Sudah Diorder), kolom Status Order, Status All Driver, History, dan RAW ----
// SUDAH = ada shipment dengan jam tender SESUDAH (atau tepat pada) jam antri, tanpa batas jam. Shipment yang di-tender SEBELUM jam antri tidak dihitung (order lama).
// BELUM = tidak ada shipment yang di-tender sesudah jam antri. SUDAH (manual) = ditandai manual oleh tim bila hitungan DOTS masih BELUM.
// Putaran: shipment yang sudah ada saat driver antri lagi dicatat di baris putaran baru (field ignore) supaya tidak dihitung dua kali.
const antriKeyOf = a => { const n = new Date(a.antri_at || a.seq || Date.now()); return dayOfDate(n) * 1440 + n.getHours() * 60 + n.getMinutes(); };
let _ge = { ref: null, n: -1, map: null };
const gpsEndMap = () => { if (_ge.ref !== S.gpsRows || _ge.n !== S.gpsRows.length) _ge = { ref: S.gpsRows, n: S.gpsRows.length, map: new Map(S.gpsRows.filter(r => r[6]).map(r => [r[0], r[6]])) }; return _ge.map; };
let _gm = { ref: null, n: -1, map: null };
const gpsStatusMap = () => { if (_gm.ref !== S.gpsRows || _gm.n !== S.gpsRows.length) _gm = { ref: S.gpsRows, n: S.gpsRows.length, map: new Map(S.gpsRows.map(r => [r[0], r[4]])) }; return _gm.map; };
const normOrder = o => (o && o.st === 'LEWAT' ? { st: 'SUDAH', n: o.n || 0 } : o);   // status LEWAT (aturan 4 jam lama) dipetakan: ada order sesudah jam antri = SUDAH
const orderState = (a, day) => { const o = orderStateNat(a, day); return (o.st === 'BELUM' && a.manual_ok && !a.retired) ? { st: 'SUDAH', n: 0, manual: true, remark: a.manual_ok.remark || '', at: a.manual_ok.at || 0 } : o; };   // SUDAH manual hanya berlaku bila hitungan DOTS masih BELUM
function orderStateNat(a, day) {
  if (a.retired && a.frozen) return normOrder(a.frozen);   // putaran yang sudah selesai: status dibekukan saat driver antri lagi
  const ak = antriKeyOf(a), ig = new Set(a.ignore || []), sh = (shipCount(a.id, day) > 0 ? shipRowsOn(a.id, day) : []).filter(s => !ig.has(s.ship));
  const after = sh.filter(s => s.td * 1440 + s.tm >= ak);   // di-tender sesudah (atau tepat pada) jam antri
  return after.length ? { st: 'SUDAH', n: after.length } : { st: 'BELUM', n: 0 };
}
const orderLabel = o => (o.st === 'SUDAH' && o.manual ? 'SUDAH (manual)' : o.st);
const IC_RQ = '<svg class="ic" viewBox="0 0 24 24"><path d="M3 12a9 9 0 0115.5-6.2L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 01-15.5 6.2L3 16"/><path d="M3 21v-5h5"/></svg>';
const rndCell = d => { const r = d.round || 1; return `<span class="rnd${r > 1 ? ' hi' : ''}" title="${r > 1 ? 'Putaran antri ke-' + r + ' hari ini' : 'Putaran antri pertama'}">Ke-${r}</span>${d.retired ? '<div class="note">selesai</div>' : ''}`; };
// shipment putaran ini = shipment hari ini yang belum dicatat di putaran sebelumnya; selesai bila semuanya closed / forceclosed menurut GPS Tracking
function roundInfo(a, day) {
  const ig = new Set(a.ignore || []), sh = (shipCount(a.id, day) > 0 ? shipRowsOn(a.id, day) : []).filter(s => !ig.has(s.ship)), cs = closeSet(), gm = gpsStatusMap();
  const open = sh.filter(s => !cs.has(gm.get(s.ship)));
  return { sh, open, done: sh.length > 0 && open.length === 0 };
}
function requeueBtn(a, day) {
  const ri = roundInfo(a, day);
  return `<button class="ibtn rq${ri.done ? ' ready' : ''}" data-act="requeue" data-key="${esc(qkey(a))}" title="${ri.done ? 'Semua shipment sudah closed / forceclosed: klik untuk antri lagi' : 'Shipment belum selesai (' + ri.open.length + '): masih bisa antri lagi, akan ada konfirmasi'}" aria-label="Antri lagi">${IC_RQ}</button>`;
}
// info customer dan rute sebuah shipment dari DOTS (tanggal berapa pun), untuk popup konfirmasi
function dotsInfoOf(ship) { const r = S.dotsRows.find(x => x[0] === ship); return r ? { cust: r[6] || '', route: r[8] || '' } : { cust: '', route: '' }; }
const shipLines = list => list.map(o => `<br>• No. Shipment <b>${esc(o.ship)}</b>${o.cust ? ' · Customer <b>' + esc(o.cust) + '</b>' : ''}${o.route ? ' · Rute <b>' + esc(o.route) + '</b>' : ''} · status GPS: ${esc(String(o.gps || 'tidak ada di GPS Tracking').toLowerCase())}`).join('');
// shipment driver yang belum closed / forceclosed: dari DOTS hari ini, ditambah shipment terakhir menurut GPS Tracking bila belum closed
function openShipsOf(id, t) {
  const cs = closeSet(), gm = gpsStatusMap(), out = new Map(), info = new Map(shipList(id, t).map(x => [x.ship, x]));
  (shipCount(id, t) > 0 ? shipRowsOn(id, t) : []).forEach(s => { if (!cs.has(gm.get(s.ship))) { const x = info.get(s.ship) || {}; out.set(s.ship, { ship: s.ship, cust: x.cust || '', route: x.route || '', gps: gm.get(s.ship) || '' }); } });
  const lg = lastGps(id, t); if (lg && !cs.has(lg.status) && !out.has(lg.ship)) { const di = dotsInfoOf(lg.ship); out.set(lg.ship, { ship: lg.ship, cust: di.cust, route: di.route, gps: lg.status }); }
  return [...out.values()];
}
function addAskNotAva(id) {
  const d = driverListToday().find(x => x.id_driver === id); if (!d || antriAktif().some(a => a.id === id)) return;
  if (d.kode === 'N/A') { askConfirm('Driver belum absen', `Driver <b>${esc(d.nama)}</b> (${esc(id)}) <b>belum absen</b> (Kode Absensi N/A). Apakah yakin masuk antrian? Tim Driver Management bisa mengingatkan driver untuk absen terlebih dahulu.`, 'Ya, masuk antrian', () => addToAntrian(id, true)); return; }
  const os = openShipsOf(id, todaySerial());
  const body = os.length ? `Apakah yakin masuk antrian? Driver <b>${esc(d.nama)}</b> (${esc(id)}) masih <b>belum selesai</b> shipment:${shipLines(os)}` : `Apakah yakin masuk antrian? Driver <b>${esc(d.nama)}</b> (${esc(id)}) berkode OR (on road) dan belum berstatus AVA, tetapi nomor shipment yang belum selesai tidak ditemukan di DOTS / GPS Tracking.`;
  askConfirm('Masuk antrian?', body, 'Ya, masuk antrian', () => addToAntrian(id, true));
}
function requeueAsk(key) {
  const a = antriAktif().find(x => x.key === key); if (!a) { toast('Baris antrian tidak ditemukan atau sudah diganti putaran baru. Muat ulang halaman lalu coba lagi.'); return; }
  const t = todaySerial(), ri = roundInfo(a, t);
  if (!ri.open.length) { requeueGo(key); return; }
  const info = new Map(shipList(a.id, t).map(x => [x.ship, x])), gm = gpsStatusMap();
  const li = shipLines(ri.open.map(s => { const x = info.get(s.ship) || {}; return { ship: s.ship, cust: x.cust || '', route: x.route || '', gps: gm.get(s.ship) || '' }; }));
  askConfirm('Antri lagi?', `Apakah yakin antri lagi? Driver <b>${esc(a.nama)}</b> (${esc(a.id)}) masih <b>belum selesai</b> shipment:${li}`, 'Ya, antri lagi', () => requeueGo(key));
}
async function requeueGo(key) {
  const a = antriAktif().find(x => x.key === key); if (!a) { toast('Baris antrian tidak ditemukan atau sudah diganti putaran baru. Muat ulang halaman lalu coba lagi.'); return; }
  const t = todaySerial(), now = nowDate().getTime(), round = (a.round || 1) + 1, kNew = keyOf(a.id) + '__r' + round;
  if (antrian().some(x => x.key === kNew)) { toast('Driver ini sudah punya putaran antri ke-' + round + '.'); return; }
  const fz = orderState(a, t), ab = absensiOf(a.id, t);
  const item = { id: a.id, nama: a.nama, jam_absen: ab.time || a.jam_absen || '', absen_day: t, antri_at: now, depo: a.depo || '', nopol_unit: a.nopol_unit || '', type_unit: a.type_unit || '', keterangan: '', waktu_masuk: getCurrentDateTimeStr(), seq: Date.now(), round, ignore: (shipCount(a.id, t) > 0 ? shipRowsOn(a.id, t) : []).map(s => s.ship), prev: key };
  S.pending[kNew] = { ...item, isNew: true };
  renderListDriver(); renderListAntrian();
  const ok = await dbWrite(() => upsert('app/queue', { items: { [key]: { retired: true, retired_at: now, frozen: { st: fz.st, n: fz.n || 0, manual: !!fz.manual, remark: fz.remark || '' } }, [kNew]: item } }));
  if (ok) { scheduleHistorySave(); toast(`${a.nama}: antri lagi (putaran ke-${round}). Putaran sebelumnya tetap terlihat.`); setTimeout(() => { if (S.pending[kNew]) { delete S.pending[kNew]; renderQueueSafe(); renderListDriver(); } }, 5000); }
  else { delete S.pending[kNew]; renderListDriver(); renderListAntrian(); }
}
const typeCell = (tp, ph) => { const s = String(tp || '').trim(); if (!s || s === '-') return `<span class="type-in ph">${ph}</span>`; return truckGroup(s) === '20' ? `<span class="pill c-OR">${esc(s)}</span>` : `<span class="type-in">${esc(s)}</span>`; };   // tipe 20 FT: kotak hijau
const IC_CHK = '<svg class="ic" viewBox="0 0 24 24"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>', IC_EDIT = '<svg class="ic" viewBox="0 0 24 24"><path d="M4 20h4L19 9l-4-4L4 16z"/><path d="M13.5 6.5l4 4"/></svg>';
function orCell(a, o) {
  if (o.st === 'SUDAH' && o.manual) return `<button class="or-link" data-or="${esc(qkey(a))}" data-mode="manual" title="${esc('Ditandai SUDAH manual' + (o.remark ? ': ' + o.remark : '') + '. Klik untuk melihat / mengubah remarks atau menghapus tanda manual')}"><span class="pill c-OR">SUDAH</span><div class="note">manual</div></button>`;
  if (o.st === 'SUDAH') return `<button class="or-link" data-or="${esc(qkey(a))}" title="Ada shipment dengan jam tender sesudah jam antri. Klik untuk lihat detail shipment"><span class="pill c-OR">SUDAH</span><div class="note">${o.n} shipment</div></button>`;
  const tip = 'Belum ada shipment yang di-tender sesudah jam antri. Klik untuk melihat 1 order terakhir dalam 7 hari terakhir';
  return `<span class="or-wrap"><button class="or-link" data-or="${esc(qkey(a))}" data-mode="last" title="${esc(tip)}"><span class="pill c-O">BELUM</span></button>${a.retired ? '' : `<button class="ibtn chk" data-act="manual-ok" data-key="${esc(qkey(a))}" title="Centang manual: ubah menjadi SUDAH (mis. driver jalan tanpa SPK yang ditender), dengan remarks" aria-label="Tandai SUDAH manual">${IC_CHK}</button>`}</span>`;
}
// popup tandai SUDAH manual: remarks boleh diisi; bila sudah manual bisa mengubah remarks atau menghapus tanda
function openManualOrder(key) {
  const a = antriAktif().find(x => x.key === key); if (!a) { toast('Baris antrian tidak ditemukan. Muat ulang halaman lalu coba lagi.'); return; }
  const cur = a.manual_ok || null; $('.modal-box').classList.remove('wide');
  $('#modalBody').innerHTML = `<h3>${cur ? 'Status SUDAH (manual)' : 'Tandai SUDAH manual'} <span class="note">${esc(a.nama)} · ${esc(a.id)}</span></h3>
  <p class="desc">Dipakai bila driver sudah jalan tetapi ordernya tidak tercatat di DOTS (mis. jalan tanpa SPK yang ditender). Status Order baris ini menjadi <b>SUDAH</b> dan ikut dihitung di kartu Status All Driver, History, dan RAW Data. Bila nanti shipment-nya tercatat di DOTS, hitungan otomatis yang dipakai.</p>
  <label class="mo-fld">Remarks<textarea id="moRemark" rows="3" maxlength="300" placeholder="mis. Jalan tanpa SPK, order lewat telepon dari dispatcher">${esc(cur ? cur.remark : '')}</textarea></label>
  <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:14px">${cur ? '<button class="btn" id="moDel" style="margin-right:auto;color:var(--danger)">Hapus tanda manual</button>' : ''}<button class="btn" id="moNo">Batal</button><button class="btn primary" id="moYes">${cur ? 'Simpan remarks' : 'Tandai SUDAH'}</button></div>`;
  $('#modal').hidden = false; $('#moNo').onclick = closeModal; $('#moRemark').focus();
  $('#moYes').onclick = async () => { const remark = $('#moRemark').value.trim(); closeModal(); const ok = await dbWrite(() => upsert('app/queue', { items: { [key]: { manual_ok: { at: cur ? cur.at : Date.now(), remark } } } })); if (ok) { scheduleHistorySave(); toast(`${a.nama}: Status Order ditandai SUDAH (manual)`); } };
  if (cur) $('#moDel').onclick = async () => { closeModal(); const ok = await dbWrite(() => upsert('app/queue', { items: { [key]: { manual_ok: null } } })); if (ok) { scheduleHistorySave(); toast(`${a.nama}: tanda SUDAH manual dihapus`); } };
}
// popup ubah jam antri (jaga-jaga lupa klik +): baris otomatis naik / turun karena urutan List Antrian mengikuti jam antri
function openAntriEdit(key) {
  const a = antriAktif().find(x => x.key === key); if (!a) { toast('Baris antrian tidak ditemukan. Muat ulang halaman lalu coba lagi.'); return; }
  const f = ms => { const n = new Date(ms); return `${n.getFullYear()}-${pad(n.getMonth() + 1)}-${pad(n.getDate())}T${pad(n.getHours())}:${pad(n.getMinutes())}`; };
  const cur = a.antri_at || a.seq || Date.now(); $('.modal-box').classList.remove('wide');
  $('#modalBody').innerHTML = `<h3>Ubah jam antri <span class="note">${esc(a.nama)} · ${esc(a.id)}</span></h3>
  <p class="desc">Jam antri saat ini <b>${esc(antriStr(a))}</b>. Dipakai bila lupa klik + di List Driver, sehingga driver tetap diantrikan sesuai jam sebenarnya. Urutan List Antrian mengikuti jam antri (paling awal di atas), jadi barisnya otomatis naik; Status Order dihitung ulang terhadap jam yang baru. Tidak boleh lebih dari jam sekarang.</p>
  <label class="mo-fld">Jam antri (tanggal dan jam)<input type="datetime-local" id="aeIn" value="${f(cur)}" max="${f(nowDate().getTime())}"></label>
  <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:14px"><button class="btn" id="aeNo">Batal</button><button class="btn primary" id="aeYes">Simpan jam antri</button></div>`;
  $('#modal').hidden = false; $('#aeNo').onclick = closeModal; $('#aeIn').focus();
  $('#aeYes').onclick = async () => { const ms = new Date($('#aeIn').value).getTime(); if (!isFinite(ms)) { toast('Isi tanggal dan jam antri dengan benar.'); return; } if (ms > nowDate().getTime() + 60000) { toast('Jam antri tidak boleh lebih dari jam sekarang.'); return; }
    closeModal(); if (ms === cur) return;
    const ok = await dbWrite(() => upsert('app/queue', { items: { [key]: { antri_at: ms, antri_edit: true } } })); if (ok) { scheduleHistorySave(); toast(`${a.nama}: jam antri diubah menjadi ${antriStr({ antri_at: ms })}`); } };
}
let queuePending = false;
function renderQueueSafe() {
  const ae = document.activeElement;
  if (ae && ae.tagName === 'INPUT' && $('#tbAntrian').contains(ae)) { queuePending = true; return; }
  queuePending = false; renderListAntrian();
}
// kolom List Antrian untuk urut & filter (nilai = teks yang tampil di sel)
const agingOf = (id, sjm) => { const rs = sjm.get(id) || []; if (!rs.length) return sjUsable() ? '-' : 'N/A'; const ag = rs.map(r => r.aging).filter(a => a != null); return ag.length ? Math.max(...ag) : '-'; };
const laCols = sjm => [
  { label: 'ID Driver', cls: 'l', get: x => x.d.id }, { label: 'Nama', cls: 'l', get: x => x.d.nama }, { label: 'Status Klinik', get: x => klinikOf(x.d.id) || 'N/A' },
  { label: 'Nopol Unit', cls: 'l', get: x => x.d.nopol_unit || '-' }, { label: 'Tipe Unit', cls: 'l', get: x => x.d.type_unit || 'Otomatis' },
  { label: 'Status P2H', get: x => (String(x.d.nopol_unit || '').trim() ? (p2hOf(x.d.nopol_unit) || 'N/A') : '') },
  { label: 'Status FMS BD', get: x => (String(x.d.nopol_unit || '').trim() ? unitStatus(x.d.nopol_unit) : '') },
  { label: 'Absen MTA', get: x => absenStr(x.d) }, { label: 'Antri', get: x => antriStr(x.d) }, { label: 'Status Order', get: x => orderLabel(x.o) },
  { label: 'Aging SJ Outs.(hari)', get: x => agingOf(x.d.id, sjm) }, { label: 'Depo', cls: 'l', get: x => x.d.depo || '-' }, { label: 'Keterangan', cls: 'l', get: x => x.d.keterangan || '-' },
  { label: 'Putaran', get: x => `Ke-${x.d.round || 1}${x.d.retired ? ' (selesai)' : ''}` }
];
function renderListAntrian() {
  const tb = $('#tbAntrian'), list = antrian(), t = todaySerial();
  const sjm = sjByDriver(), dupm = nopolDupMap(list), sq = ($('#searchAntri').value || '').trim().toLowerCase();
  const base = list.map((d, i) => ({ d, i, o: orderState(d, t) })).filter(x => (!sq || x.d.id.toLowerCase().includes(sq) || String(x.d.nama || '').toLowerCase().includes(sq) || noteDriver(x.d.id, t).toLowerCase().includes(sq) || noteUnit(x.d.nopol_unit, t).toLowerCase().includes(sq)));
  const LA = laCols(sjm);
  SFREG.la = { names: LA.map(c => c.label), matrix: () => sfMatrix(base, LA), render: renderListAntrian };
  $('#laHead').innerHTML = '<th>No</th>' + LA.map((c, i) => sfTh('la', i, c.label, c.cls)).join('') + '<th>Aksi</th>';
  const rows = sfApply('la', base, LA);   // No tetap = nomor antrian asli (urutan jam antri), walau tabel diurutkan / difilter
  $('#resetLA').hidden = !(sfActive('la') || sq);
  { const act = list.filter(a => !a.retired).length, shown = rows.filter(r => !r.d.retired).length; $('#badge-total-antri').textContent = (shown !== act ? shown + ' dari ' : '') + act + ' Driver'; }   // lencana biru jumlah driver di antrian (putaran yang masih berjalan)
  const fiTxt = !sfActive('la') && !sq ? '' : `Filter aktif: ${rows.length} dari ${list.length} driver`;
  $('#fltInfo').textContent = fiTxt; $('#fltInfo').hidden = !fiTxt;
  if (!list.length) { tb.innerHTML = `<tr><td colspan="16" class="empty-row">Belum ada driver dalam antrian</td></tr>`; updateDashboard(); return; }
  if (!rows.length) { tb.innerHTML = `<tr><td colspan="16" class="empty-row">Tidak ada driver yang cocok dengan filter</td></tr>`; updateDashboard(); return; }
  tb.innerHTML = rows.map(({ d, i, o }) => `<tr class="${d.isNew ? 'new-row' : ''}${d.retired ? ' retired' : ''}${o.st === 'SUDAH' ? ' so-ok' : ''}">
    <td class="num">${i + 1}</td>
    <td class="l">${esc(d.id)}</td>
    <td class="l nm"><b>${esc(d.nama)}</b>${noteHtml(noteDriver(d.id, t))}</td>
    <td>${klinikCell(d.id)}</td>
    <td class="l np"><input type="text" class="cell-in${!d.retired && dupm.has(d.id) ? ' dup' : ''}" list="unit-list" data-id="${esc(d.id)}" data-key="${esc(qkey(d))}" data-f="nopol_unit" value="${esc(d.nopol_unit)}" placeholder="Pilih/ketik..."${d.retired ? ' disabled' : ''}${!d.retired && dupm.has(d.id) ? ` title="${esc(dupm.get(d.id))}"` : ''}>${noteHtml(noteUnit(d.nopol_unit, t))}</td>
    <td class="l">${typeCell(d.type_unit, 'Otomatis')}</td>
    <td class="wrap">${p2hCell(d.nopol_unit)}</td>
    <td class="wrap">${unitCell(d.nopol_unit)}</td>
    <td class="mono wrap dt">${dtHtml(absenStr(d))}</td>
    <td class="mono wrap dt"><b>${dtHtml(antriStr(d))}</b>${d.antri_edit ? '<div class="note" title="Jam antri diubah manual">diedit</div>' : ''}${d.retired ? '' : `<button class="ibtn ed" data-act="edit-antri" data-key="${esc(qkey(d))}" title="Ubah jam antri (jaga-jaga lupa klik +)" aria-label="Ubah jam antri">${IC_EDIT}</button>`}</td>
    <td>${orCell(d, o)}</td>
    <td>${sjCell(d.id, sjm)}</td>
    <td class="l ft">${qdDepoCell(d)}</td>
    <td class="l ft2"><input type="text" class="cell-in" data-id="${esc(d.id)}" data-key="${esc(qkey(d))}" data-f="keterangan" value="${esc(d.keterangan)}" title="${esc(d.keterangan)}" placeholder="Keterangan..."${d.retired ? ' disabled' : ''}></td>
    <td>${rndCell(d)}</td>
    <td class="act">${(!d.retired && o.st === 'SUDAH') ? requeueBtn(d, t) : ''}<button class="ibtn del" data-act="del" data-id="${esc(d.id)}" data-key="${esc(qkey(d))}" title="${d.retired ? 'Hapus baris putaran ini' : 'Hapus dari antrian'}" aria-label="Hapus dari antrian">${IC_DEL}</button></td>
  </tr>`).join('');
  list.forEach(d => { if (d.isNew && S.pending[d.key]) S.pending[d.key].isNew = false; });
  refreshUnitList();
  updateDashboard();
}
async function addToAntrian(id, force) {
  const d = driverListToday().find(x => x.id_driver === id);
  if (!d || (d.status_lokasi !== 'AVA' && !(force && (d.kode === 'OR' || d.kode === 'N/A'))) || antriAktif().some(a => a.id === id)) return;
  const k = keyOf(id);
  const item = { id, nama: d.nama, jam_absen: d.jam_absen || '', absen_day: todaySerial(), antri_at: nowDate().getTime(), depo: '', nopol_unit: '', type_unit: '', keterangan: '', waktu_masuk: getCurrentDateTimeStr(), seq: Date.now() };
  S.pending[k] = { ...item, isNew: true };   // tampil seketika, sebelum server menjawab
  renderListDriver(); renderListAntrian();
  const dead = Object.keys(S.queueItems).filter(x => !S.queueItems[x]).length;
  const ok = await dbWrite(async () => {
    if (dead > 100) { const live = {}; antrian().forEach(a => { live[qkey(a)] = stripLocal(a); }); await dbSet('app/queue', { items: live }); }
    else await upsert('app/queue', { items: { [k]: item } });
  });
  if (ok) { scheduleHistorySave(); setTimeout(() => { if (S.pending[k]) { delete S.pending[k]; renderQueueSafe(); renderListDriver(); } }, 5000); }
  else { delete S.pending[k]; renderListDriver(); renderListAntrian(); }
}
const stripLocal = v => { const c = { ...v }; delete c.isNew; delete c.key; return c; };
async function removeFromAntrian(idOrKey) {
  const hit = antrian().find(a => a.key === idOrKey), k = hit ? hit.key : keyOf(idOrKey);
  const ok = await dbWrite(() => upsert('app/queue', { items: { [k]: null } }));
  if (ok) scheduleHistorySave();
}
function clearAntrian() {
  if (!antrian().length) return;
  askConfirm('Reset antrian?', 'Semua driver di List Antrian akan dihapus untuk <b>semua orang</b> yang membuka aplikasi ini. Riwayat hari ini tetap tersimpan di History Antrian.', 'Reset Antrian', async () => {
    const ok = await dbWrite(() => dbSet('app/queue', { items: {} }));
    if (ok) { toast('Antrian berhasil direset.'); scheduleHistorySave(); }
  });
}
async function updateField(idOrKey, field, value) {
  const hit = antrian().find(a => a.key === idOrKey), k = hit ? hit.key : keyOf(idOrKey);
  const ok = await dbWrite(() => upsert('app/queue', { items: { [k]: { [field]: value } } }));
  if (ok) scheduleHistorySave();
}
async function handleUnitChange(idOrKey, input) {
  const cur0 = antrian().find(a => a.key === idOrKey) || antrian().find(a => a.id === idOrKey), id = cur0 ? cur0.id : idOrKey, qk = cur0 ? cur0.key : keyOf(idOrKey);
  const val = input.value.trim();
  if (val && S.settings.nopolUnique !== false) {
    const cur = cur0, day = cur ? rowDay(cur) : todaySerial(), owner = queueNopolOwner(normPlat(val), day, id);
    if (owner) { input.value = cur ? (cur.nopol_unit || '') : ''; toast(`Nopol ${val} sudah dipakai ${owner.nama} (${owner.id}) pada ${fmtSerial(day)}. Satu nopol hanya boleh dipakai satu driver per tanggal.`); return; }
  }
  const unit = activeUnits().find(u => u.nopol.toUpperCase() === val.toUpperCase());
  const type = unit ? unit.type : '';
  const ti = input.closest('tr').querySelector('.type-in'); if (ti) { ti.textContent = type || 'Otomatis'; ti.classList.toggle('ph', !type); }
  const pc = input.closest('tr').children[6]; if (pc) pc.innerHTML = p2hCell(input.value);
  const uc = input.closest('tr').children[7]; if (uc) uc.innerHTML = unitCell(input.value);
  const ok = await dbWrite(() => upsert('app/queue', { items: { [qk]: { nopol_unit: input.value, type_unit: type } } }));
  if (ok) scheduleHistorySave();
}

/* ---------- kartu status ---------- */
// sebaran Kode Absensi seluruh driver aktif (OFF = kode O; N/A = tidak ada kode absensi hari itu)
function kodeTotals(t) {
  const c = { X: 0, OR: 0, OFF: 0, AIS: 0, 'N/A': 0 };
  activeDrivers().forEach(d => { const k = absensiOf(d.id, t).kode; if (k === 'X') c.X++; else if (k === 'OR') c.OR++; else if (k === 'O') c.OFF++; else if (k === 'AIS') c.AIS++; else c['N/A']++; });
  return c;
}
// ---- Status All Driver: setiap driver aktif masuk TEPAT SATU kelompok (jumlah semua kelompok = jumlah driver aktif) ----
// urutan penentuan: kode N/A -> N/A; kode AIS / O -> AIS + OFF; sudah di List Antrian -> Antri & sudah diorder / Absen X & sudah antri;
// punya shipment hari ini -> Menjalankan order (belum / sudah selesai); kode OR tanpa shipment terbaca -> Menjalankan order & belum selesai; sisanya (kode X) -> Absen X & belum antri
const FLOW = [
  { k: 'xBelum', label: 'Absen X & belum antri', color: '#E0A100', tip: 'Kode Absensi X, belum masuk List Antrian, dan belum punya shipment hari ini.' },
  { k: 'xAntri', label: 'Sudah antri & belum diorder', color: '#F26B21', tip: 'Sudah masuk List Antrian dan belum diorder: belum ada shipment yang di-tender sesudah jam antri (order yang di-tender sebelum antri tidak dihitung). Sama dengan status BELUM di List Antrian.' },
  { k: 'antriOrder', label: 'Antri & sudah diorder', color: '#1F5FBF', tip: 'Sudah masuk List Antrian dan sudah diorder: ada shipment yang di-tender sesudah jam antri (atau ditandai SUDAH manual). Sama dengan status SUDAH di List Antrian.' },
  { k: 'runOpen', label: 'Menjalankan order & belum selesai', color: '#8A5A33', tip: 'Punya shipment hari ini (belum masuk antrian) dan masih ada yang belum closed / forceclosed menurut GPS Tracking. Shipment yang tidak ada di file GPS dianggap belum selesai; driver berkode OR tanpa shipment terbaca di DOTS juga dihitung di sini.' },
  { k: 'runDone', label: 'Menjalankan order & sudah selesai', color: '#6B8E23', tip: 'Punya shipment hari ini (belum masuk antrian) dan semuanya sudah closed / forceclosed menurut GPS Tracking.' },
  { k: 'offAis', label: 'AIS + OFF', color: '#64748B', tip: 'Kode Absensi A, I, S (AIS) atau O (Off).' },
  { k: 'na', label: 'N/A', color: '', tip: 'Belum ada kode absensi (N/A): belum absen atau datanya tidak ada.' }
];
// rincian tipe unit tiap kelompok: 20 FT, 40 FT, (Lainnya bila ada), dan "N/A"
//  - driver di antrian: dari Nopol / Tipe Unit pada baris antrian; nopol kosong = N/A
//  - driver menjalankan order (belum antri): dari TIPE TRUCK shipment dengan tender terbaru hari itu (sama dengan kartu Driver tender hari ini); tanpa shipment di DOTS = N/A
const mkSplit = () => ({ t20: 0, t40: 0, oth: 0, np: 0, types: {} });
const addSplit = (o, key, tp) => { o[key]++; if (key === 'oth') { const k = String(tp || '').trim() || '(kosong)'; o.types[k] = (o.types[k] || 0) + 1; } };
const queueSplitKey = it => (!String(it.nopol_unit || '').trim() || !String(it.type_unit || '').trim() ? 'np' : grpKey(truckGroup(it.type_unit)));
const NP_TIP = { xAntri: 'N/A = nopolnya belum diisi di List Antrian, jadi tipe unitnya belum diketahui', antriOrder: 'N/A = nopolnya belum diisi di List Antrian, jadi tipe unitnya belum diketahui', runOpen: 'N/A = tanpa shipment hari ini di DOTS Report (mis. OR dari hari sebelumnya), jadi nopol dan tipe unitnya belum diketahui', runDone: 'N/A = tanpa shipment hari ini di DOTS Report, jadi nopol dan tipe unitnya belum diketahui' };
const splitHtml = (k, sp) => sp ? `<div class="kf-split"><span>20 FT <b>${sp.t20}</b></span> <span>40 FT <b>${sp.t40}</b></span>${sp.oth ? ` <span class="oth" title="${esc(othTip(sp))}">Lainnya <b>${sp.oth}</b></span>` : ''} <span class="np" title="${esc(NP_TIP[k])}">N/A <b>${sp.np}</b></span></div>` : '';
function flowTotals(t, best) {
  const c = { xBelum: 0, xAntri: 0, antriOrder: 0, runOpen: 0, runDone: 0, offAis: 0, na: 0, late: 0, sp: { xAntri: mkSplit(), antriOrder: mkSplit(), runOpen: mkSplit(), runDone: mkSplit() } };
  best = best || dayTotals(t).best;
  const q = new Map(antriAktif().map(a => [a.id, a])), cs = closeSet(), gst = new Map(S.gpsRows.map(r => [r[0], r[4]]));
  for (const d of activeDrivers()) {
    const ab = absensiOf(d.id, t);
    if (ab.kode === 'N/A') { c.na++; continue; }
    if (ab.kode === 'AIS' || ab.kode === 'O' || ab.kode === 'S') { c.offAis++; continue; }
    const sh = shipCount(d.id, t) > 0 ? shipRowsOn(d.id, t) : [], item = q.get(d.id);
    if (item) { const o = orderState(item, t), k = o.st === 'SUDAH' ? 'antriOrder' : 'xAntri'; c[k]++; addSplit(c.sp[k], queueSplitKey(item), item.type_unit); continue; }
    const bt = best.get(d.id);
    if (sh.length) { const k = sh.every(s => cs.has(gst.get(s.ship))) ? 'runDone' : 'runOpen'; c[k]++; if (bt) addSplit(c.sp[k], grpKey(bt.g), bt.tp); else c.sp[k].np++; continue; }
    if (ab.kode === 'OR') { c.runOpen++; c.sp.runOpen.np++; continue; }
    c.xBelum++;
  }
  return c;
}
const KODE_TIP = { X: 'X = masuk (absen di pool)', OR: 'OR = on road', OFF: 'OFF = Off (kode O)', AIS: 'AIS = alpha, izin, atau sakit', 'N/A': 'N/A = tidak ada kode absensi hari ini (belum absen atau datanya tidak ada)' };
// driver yang sudah antri lebih dari sekali hari ini: [{k: antri ke-berapa, n: jumlah driver}]
function repeatParts() {
  const cnt = new Map(); antrian().forEach(a => cnt.set(a.id, (cnt.get(a.id) || 0) + 1));
  const g = {}; cnt.forEach(n => { if (n >= 2) g[n] = (g[n] || 0) + 1; });
  return Object.keys(g).map(Number).sort((a, b) => a - b).map(k => ({ k, n: g[k] }));
}
function updateDashboard() {
  const t = todaySerial(), act = activeDrivers(), inQ = new Set(antriAktif().map(a => a.id)), kt = kodeTotals(t), dt = dayTotals(t);
  $('#count-aktif').textContent = act.length;
  { let nOr = 0, nShip = 0; for (const d of act) { if (dt.best.has(d.id)) nShip++; else if (absensiOf(d.id, t).kode === 'OR') nOr++; }   // Total driver OR hari ini = Absen OR (kode OR yang belum ber-shipment) + Tender hari ini (semua kode, termasuk OR yang sudah di-tender); 1 driver 1x
    $('#count-ortot').textContent = nOr + nShip; $('#ortot-detail').innerHTML = `<span title="Driver berkode absen OR yang belum punya shipment hari ini (yang sudah di-tender pindah ke Tender hari ini)">Absen OR <b>${nOr}</b></span> <span title="Semua driver yang punya shipment hari ini, apa pun Kode Absensinya (termasuk yang berkode OR)">+ Tender hari ini <b>${nShip}</b></span>`; }
  const fl = flowTotals(t, dt.best), flN = FLOW.reduce((a, f) => a + fl[f.k], 0);
  $('#flow-sub').innerHTML = `dari <b class="kf-n">${act.length}</b> driver aktif` + (flN !== act.length ? ` <span style="color:var(--danger)">· jumlah status ${flN} tidak sama dengan total</span>` : '');
  const totQ = antriAktif().length;
  $('#flow-grid').innerHTML = FLOW.map(f => `<div class="kf${f.k === 'na' ? ' na' : ''}" title="${esc(f.tip)}"><div class="l"><i class="dot" style="background:${f.color || 'transparent'}"></i>${esc(f.label)}</div><div class="vrow"><div class="v" data-k="${f.k}">${fl[f.k]}</div>${splitHtml(f.k, fl.sp[f.k])}</div><div class="ps">${(f.k === 'xAntri' || f.k === 'antriOrder') ? `<div class="p">${totQ ? (fl[f.k] / totQ * 100).toFixed(1) : '0.0'}% dari antrian</div>` : `<div class="p">${act.length ? (fl[f.k] / act.length * 100).toFixed(1) : '0.0'}% dari total</div>`}</div></div>`).join('');
  $('#aktif-break').innerHTML = ['X', 'OR', 'OFF', 'AIS', 'N/A'].map(k => `<span class="ch ch-${k === 'N/A' ? 'na' : k.toLowerCase()}" title="${esc(KODE_TIP[k])}">${k} <b>${kt[k]}</b></span>`).join('');
  const hint = 'Upload ulang DOTS Report untuk rincian';
  const q = antriAktif(), total = q.length, nOld = antrian().length - total, oc = { SUDAH: 0, BELUM: 0 }; q.forEach(a => { const o = orderState(a, t); oc[o.st]++; });
  const rp = repeatParts();
  $('#laInfo').innerHTML = rp.map(p => `<b>${p.n}</b> driver yang sudah ${p.k}x antri`).join(' · ');   // hanya tampil bila ada driver yang antri lebih dari 1x
  $('#count-or').textContent = dt.or.n;
  $('#or-detail').innerHTML = !dt.or.n ? '' : dt.hasType ? detailHtml(dt.or) : hint + ' 20/40 FT';
  for (const k of ['SPK']) {
    const id = k.toLowerCase(), o = dt.sp[k], any = S.dotsRows.length > 0;
    $('#count-' + id).textContent = o.n;
    $('#' + id + '-detail').innerHTML = !any ? '' : !dt.hasTS || !dt.hasFPT ? hint + ' SPK/SPO (status FPT)' : (o.n && dt.hasType ? detailHtml(o) : '');
  }
}

/* =====================================================================
   GANTI TANGGAL
   ===================================================================== */
async function maybeRollover() {
  if (ASOF != null || !DBREADY || !S.loaded.config || !CAN_WRITE) return;
  const t = todaySerial(), last = S.config.lastDay;
  if (last === t) return;
  if (last == null) { await dbWrite(() => upsert('app/config', { lastDay: t })); return; }
  if (!(await tryLease('lock_roll', 20000))) return;
  const cur = await DB.doc('app/config').get();
  const lastNow = cur.exists ? cur.data().lastDay : null;
  if (lastNow == null || lastNow === t) return;
  await saveHistory(lastNow);
  let removed = 0;
  if (S.settings.orRollover === 'remove') {
    const upd = {};
    antrian().forEach(a => { if (shipCount(a.id, lastNow) > 0) { upd[qkey(a)] = null; if (!a.retired) removed++; } });
    if (removed) await dbWrite(() => upsert('app/queue', { items: upd }));
  }
  await dbWrite(() => upsert('app/config', { lastDay: t }));
  toast(removed ? `Ganti tanggal: ${removed} driver OR dikeluarkan dari antrian, sisanya tetap.` : 'Ganti tanggal: antrian dipertahankan, status shipment dihitung ulang untuk hari ini.');
}

/* =====================================================================
   HISTORY ANTRIAN
   ===================================================================== */
function buildSnapshot(day) {
  const q = antrian(), inQ = new Set(q.filter(a => !a.retired).map(a => a.id)), sjm = sjByDriver(day);
  const sjOf = id => { const rs = sjm.get(id) || [], ag = rs.map(r => r.aging).filter(x => x != null); return [ag.length ? Math.max(...ag) : null, rs.length]; };
  return {
    date: dateKey(day), savedAt: Date.now(),
    drivers: activeDrivers().filter(d => !inQ.has(d.id)).map(d => { const sa = statusAntrianOf(d.id, day); return [d.id, d.nama, sa.ab.kode, sa.status, absenMtaOf(sa.ab, day)]; }),
    antrian: q.map(a => [a.id, a.nama, absenStr(a) === '-' ? '' : absenStr(a), a.depo || '', a.nopol_unit || '', a.type_unit || '', a.keterangan || '', antriStr(a), shipCount(a.id, day), ...sjOf(a.id), p2hOf(a.nopol_unit, day), klinikOf(a.id, day), unitStatus(a.nopol_unit, day), orderLabel(orderState(a, day)), a.round || 1, a.retired ? 1 : 0, (orderState(a, day).remark || '')])
  };
}
async function saveHistory(day) {
  if (ASOF != null || !DBREADY || !CAN_WRITE) return;
  const k = dateKey(day);
  try {
    await dbSet('history/' + k, buildSnapshot(day));
    const dates = (S.config.historyDates || []);
    if (!dates.includes(k)) await upsert('app/config', { historyDates: [...dates, k].sort() });
    H.cache[k] = null;
  } catch (e) { handleWriteError(e); }
}
const scheduleHistorySave = debounce(() => { saveHistory(todaySerial()); }, 2500);
const H = { sel: null, cache: {}, loading: null };
const orLabel = n => (n > 0 ? 'SUDAH' : 'BELUM');
const histRound = r => `Ke-${r[15] || 1}${r[16] ? ' (selesai)' : ''}`;
const histOrder = r => { const s = r[14] || orLabel(r[8]); return (s === 'LEWAT 4 JAM' || s === 'SUDAH >4 JAM') ? 'SUDAH' : s; };   // label lama (aturan 4 jam) dipetakan ke SUDAH   // snapshot baru menyimpan Status Order menurut definisi 4 jam di r[14]; snapshot lama memakai ada / tidaknya shipment
const histOrderCell = r => { const s = histOrder(r); return s === 'SUDAH (manual)' ? `<span class="pill c-OR" title="${esc('Ditandai SUDAH manual' + (r[17] ? ': ' + r[17] : ''))}">SUDAH</span><div class="note">manual</div>` : s === 'SUDAH' ? `<span class="pill c-OR">SUDAH</span><div class="note">${r[8]} shipment</div>` : '<span class="pill c-O">BELUM</span>'; };
async function loadHistory(k) {
  if (H.cache[k]) return H.cache[k];
  try { const s = await DB.doc('history/' + k).get(); H.cache[k] = s.exists ? s.data() : { date: k, drivers: [], antrian: [], missing: true }; const c = H.cache[k]; ['drivers', 'antrian'].forEach(f => { c[f] = (c[f] || []).map(r => { r = [...r]; r[1] = nmUp(r[1]); return r; }); }); }
  catch (e) { H.cache[k] = { date: k, drivers: [], antrian: [], missing: true }; }
  return H.cache[k];
}
async function histData() {
  const tk = dateKey(todaySerial()), k = H.sel || tk;
  return k === tk ? buildSnapshot(todaySerial()) : loadHistory(k);
}
async function renderHistory() {
  const el = $('#v-history'), tk = dateKey(todaySerial());
  if (!H.sel) H.sel = tk;
  const dates = [...new Set([...(S.config.historyDates || []), tk])].sort().reverse();
  if (serialOfKey(H.sel) == null) H.sel = tk;   // kalender: tanggal apa pun boleh dipilih (tanpa data tersimpan = "Belum ada data")
  const d = await histData();
  const saved = H.sel === tk ? 'Data hari ini (langsung dari Dashboard Antrian)' : (d.missing ? 'Belum ada data tersimpan untuk tanggal ini' : 'Tersimpan terakhir ' + fmtTs(d.savedAt));
  const colsHD = [{ label: 'ID Driver', cls: 'l', get: r => r[0] }, { label: 'Nama', cls: 'l', get: r => r[1] }, { label: 'Kode Absensi', get: r => (ABS_SET.has(r[2]) ? r[2] : 'N/A') }, { label: 'Absen MTA', get: r => r[4] || 'N/A' }, { label: 'Status Antrian', get: r => r[3] }];
  const colsHA = [{ label: 'ID Driver', cls: 'l', get: r => r[0] }, { label: 'Nama', cls: 'l', get: r => r[1] }, { label: 'Status Klinik', get: r => r[12] || 'N/A' }, { label: 'Nopol Unit', cls: 'l', get: r => r[4] || '-' }, { label: 'Tipe Unit', cls: 'l', get: r => r[5] || '-' },
    { label: 'Status P2H', get: r => r[11] || (r[4] ? 'N/A' : '') }, { label: 'Status FMS BD', get: r => ((r[13] && r[13] !== '-' && r[13] !== 'N/A') ? r[13] : (r[4] ? 'N/A' : '')) },
    { label: 'Absen MTA', get: r => r[2] || '-' }, { label: 'Antri', get: r => r[7] || '-' }, { label: 'Status Order', get: r => histOrder(r) },
    { label: 'Aging SJ Outs.(hari)', get: r => (r[9] == null ? '-' : r[9]) }, { label: 'Depo', cls: 'l', get: r => r[3] || '-' }, { label: 'Keterangan', cls: 'l', get: r => r[6] || '-' }, { label: 'Putaran', get: r => histRound(r) }];
  SFREG.hd = { names: colsHD.map(c => c.label), matrix: () => sfMatrix(d.drivers, colsHD), render: renderHistory };
  SFREG.ha = { names: colsHA.map(c => c.label), matrix: () => sfMatrix(d.antrian, colsHA), render: renderHistory };
  const viewD = sfApply('hd', d.drivers, colsHD), viewA = sfApply('ha', d.antrian, colsHA);
  const when = H.sel === tk ? 'hari ini' : 'pada tanggal ini';
  const cntId = {}; d.antrian.forEach(r => { cntId[r[0]] = (cntId[r[0]] || 0) + 1; }); const gId = {}; Object.values(cntId).forEach(n => { if (n >= 2) gId[n] = (gId[n] || 0) + 1; });
  const repTxt = Object.keys(gId).map(Number).sort((x, y) => x - y).map(k => `<b>${gId[k]}</b> driver yang sudah ${k}x antri`).join(' · ');
  const nAvaH = d.drivers.filter(r => r[3] === 'AVA').length;
  const infoA = (d.missing || !repTxt) ? '' : `<span class="hd-info" id="hAInfo">${repTxt}</span>`;
  const infoD = d.missing ? '' : `<span class="hd-info" id="hDInfo"><span class="pill c-OR" title="Driver yang bisa langsung masuk antrian">AVA</span> <b>${nAvaH}</b> · <span class="pill c-O" title="Belum bisa langsung masuk antrian">NOT AVA</span> <b>${d.drivers.length - nAvaH}</b></span>`;
  const dr = viewD.map((r, i) => `<tr><td class="num">${i + 1}</td><td class="l">${esc(r[0])}</td><td class="l">${esc(r[1])}</td><td>${ABS_SET.has(r[2]) ? absensiPill(r[2], '') : naPill('')}</td><td class="mono wrap dt">${mtaHtml(r[4])}</td><td><span class="pill ${r[3] === 'AVA' ? 'c-OR' : 'c-O'}">${esc(r[3])}</span></td></tr>`).join('') || `<tr><td colspan="6" class="empty-row">Tidak ada data</td></tr>`;
  const an = viewA.map((r, i) => `<tr class="${r[16] ? 'retired' : ''}${/^SUDAH/.test(histOrder(r)) ? ' so-ok' : ''}"><td class="num">${i + 1}</td><td class="l">${esc(r[0])}</td><td class="l">${esc(r[1])}</td><td class="wrap">${r[12] ? `<span class="pill ${stClass(r[12])}">${esc(r[12])}</span>` : naPill('')}</td><td class="l">${esc(r[4]) || '-'}</td><td class="l">${typeCell(r[5], '-')}</td><td class="wrap">${r[11] ? `<span class="pill ${p2hClass(r[11])}">${esc(r[11])}</span>` : (r[4] ? naPill('') : '')}</td><td class="wrap">${r[13] && r[13] !== '-' && r[13] !== 'N/A' ? `<span class="pill ${r[13] === 'BD' ? 'c-NG' : 'c-OR'}">${esc(r[13])}</span>` : (r[4] ? naPill('') : '')}</td><td class="mono wrap dt">${dtHtml(r[2])}</td><td class="mono wrap dt">${dtHtml(r[7])}</td><td class="wrap">${histOrderCell(r)}</td><td class="wrap">${r[9] == null ? '<span class="note">-</span>' : `<span class="age${r[9] >= S.settings.agingRed ? ' red' : ''}">${r[9]}</span>${r[10] > 1 ? `<div class="note">${r[10]} SJ</div>` : ''}`}</td><td class="l">${esc(r[3]) || '-'}</td><td class="l">${esc(r[6]) || '-'}</td><td>${rndCell({ round: r[15] || 1, retired: !!r[16] })}</td></tr>`).join('') || `<tr><td colspan="15" class="empty-row">Tidak ada driver di antrian pada tanggal ini</td></tr>`;
  el.innerHTML = `<div class="page-h"><h2>History Antrian</h2><p>List Driver &amp; List Antrian beserta remarks per tanggal.</p></div>
  <div class="hist-bar"><label>Tanggal <input type="date" id="histSel" value="${H.sel}" max="${tk}" aria-label="Pilih tanggal"></label>
    <span class="note">${esc(saved)}</span>
    <button class="btn primary grow" id="histDl">${IC_DL}Download Excel</button></div>
  <div class="panel"><h3>List Driver${infoD}<span class="grow">${sfResetBtn('hd')}<span class="badge-n">${viewD.length !== d.drivers.length ? viewD.length + ' dari ' : ''}${d.drivers.length} driver</span></span></h3><p class="desc">Driver yang tidak sedang berada di antrian pada tanggal tersebut.</p>
    <div class="tbl-wrap"><table class="t">${sfHead('hd', colsHD)}<tbody>${dr}</tbody></table></div></div>
<div class="panel"><h3>List Antrian${infoA}<span class="grow">${sfResetBtn('ha')}<span class="badge-n">${viewA.length !== d.antrian.length ? viewA.length + ' dari ' : ''}${d.antrian.length} driver</span></span></h3><p class="desc">Kolom sama dengan Dashboard Antrian (tanpa Aksi). Remarks (Depo, Nopol, Keterangan) sesuai yang diisi di Dashboard Antrian. Baris berlatar hijau = Status Order SUDAH.</p>
    <div class="tbl-wrap"><table class="t">${sfHead('ha', colsHA)}<tbody>${an}</tbody></table></div></div>`;
  $('#histSel').onchange = e => { H.sel = serialOfKey(e.target.value) != null ? e.target.value : tk; renderHistory(); };
  $('#histDl').onclick = () => downloadHistory(d);
}
// Excel: tanggal-jam ditulis sebagai tanggal sungguhan berformat dd-mmm-yy hh:mm (tanpa tanda |); yang hanya tanggal berformat dd-mmm-yy.
// Teks di layar "28-Oct | 05:08" / "28-Oct" tidak punya tahun, jadi tahunnya diambil dari tanggal acuan (tanggal data / tanggal aplikasi).
function xlsDate(s, refDay) {
  const m = /^(\d{1,2})-([A-Za-z]{3})(?:-(\d{2}))?(?:\s*\|\s*(\d{1,2}):(\d{2}))?$/.exec(s); if (!m) return s;
  const mi = MON_EN.indexOf(m[2]); if (mi < 0) return s;
  let y = m[3] != null ? 2000 + +m[3] : utcDate(refDay).getUTCFullYear(), day = ymd(y, mi + 1, +m[1]);
  if (m[3] == null && day > refDay + 60) day = ymd(y - 1, mi + 1, +m[1]);   // mis. Des saat tanggal acuan Januari
  const hasT = m[4] != null;
  return { t: 'n', v: day + (hasT ? (+m[4] * 60 + +m[5]) / 1440 : 0), z: hasT ? 'dd-mmm-yy hh:mm' : 'dd-mmm-yy' };
}
const xlsRows = (rows, refDay) => rows.map(r => r.map(c => (typeof c === 'string' ? xlsDate(c, refDay) : c)));
function downloadHistory(d) {
  if (d.missing) { toast('Belum ada data tersimpan untuk tanggal ini.'); return; }
  const wb = XLSX.utils.book_new();
  const ref = serialOfKey(d.date) || todaySerial();
  const w1 = XLSX.utils.aoa_to_sheet([['No', 'ID Driver', 'Nama', 'Kode Absensi', 'Absen MTA', 'Status Antrian'], ...xlsRows(d.drivers.map((r, i) => [i + 1, r[0], r[1], ABS_SET.has(r[2]) ? r[2] : '', r[4] || '', r[3]]), ref)]);
  w1['!cols'] = [6, 14, 30, 11, 17].map(w => ({ wch: w }));
  const w2 = XLSX.utils.aoa_to_sheet([['No', 'ID Driver', 'Nama', 'Status Klinik', 'Nopol Unit', 'Tipe Unit', 'Status P2H', 'Status FMS BD', 'Absen MTA', 'Antri', 'Status Order', 'Jumlah Shipment', 'Aging SJ Outs.(hari)', 'Depo', 'Keterangan', 'Putaran'],
    ...xlsRows(d.antrian.map((r, i) => [i + 1, r[0], r[1], r[12] || 'N/A', r[4], r[5], r[11] || (r[4] ? 'N/A' : ''), (r[13] && r[13] !== '-') ? r[13] : (r[4] ? 'N/A' : ''), r[2], r[7], histOrder(r), r[8], r[9] == null ? '' : r[9], r[3], r[6], histRound(r)]), ref)]);
  w2['!cols'] = [6, 14, 28, 14, 16, 12, 14, 12, 17, 17, 12, 10, 12, 16, 30, 10].map(w => ({ wch: w }));
  XLSX.utils.book_append_sheet(wb, w2, 'List Antrian');
  XLSX.utils.book_append_sheet(wb, w1, 'List Driver');
  saveWb(wb, 'History Antrian - Quedrive ' + d.date + '.xlsx');
}

/* =====================================================================
   SJ OUTSTANDING (Gantungan Surat Jalan)
   ===================================================================== */
const sjQuery = { q: '' };
// hanya SJ milik driver aktif di Master (hari ini), urut aging terbesar (terlama) dulu
function sjRowsView(day) {   // pool tetap: POOL NAGRAK TRAILER (dipakai tab SJ Outstanding, Dashboard, History, dan RAW)
  const st = S.settings, t = day == null ? todaySerial() : day, act = new Map(activeDrivers().map(d => [d.id, d])), rows = [];
  for (const r of S.sjRows) {
    if (r[1] !== st.sjPool) continue;
    const dr = act.get(r[5]); if (!dr) continue;
    const aging = st.agingSrc === 'file' ? r[4] : (r[3] == null ? null : t - r[3]);
    rows.push({ ship: r[0], cust: r[2], tender: r[3], aging, id: dr.id, nama: dr.nama });
  }
  rows.sort((a, b) => (b.aging == null ? -1e9 : b.aging) - (a.aging == null ? -1e9 : a.aging) || a.nama.localeCompare(b.nama) || a.ship.localeCompare(b.ship));
  return rows;
}
// filter Customer (pilihan ganda): sel = null berarti semua customer; selain itu Set berisi nama customer terpilih
const sjFilter = { sel: null, open: false };
const sjIsSel = c => sjFilter.sel === null || sjFilter.sel.has(c);
function sjCustomers(all) { const m = new Map(); all.forEach(r => m.set(r.cust, (m.get(r.cust) || 0) + 1)); return [...m].sort((x, y) => x[0].localeCompare(y[0])); }
function sjFilterLabel(custs) {
  const n = custs.length, s = sjFilter.sel;
  if (s === null) return `Semua customer (${n})`;
  const picked = custs.filter(c => s.has(c[0]));
  return picked.length === 0 ? 'Tidak ada customer dipilih' : picked.length === 1 ? picked[0][0] : `${picked.length} dari ${n} customer`;
}
function renderSJTable() {
  const st = S.settings, all = sjRowsView(), custs = sjCustomers(all), q = sjQuery.q.trim().toLowerCase();
  const byCust = all.filter(r => sjIsSel(r.cust));
  const rows = q ? byCust.filter(r => r.cust.toLowerCase().includes(q) || r.ship.toLowerCase().includes(q) || r.nama.toLowerCase().includes(q) || r.id.toLowerCase().includes(q)) : byCust;
  const red = byCust.filter(r => r.aging != null && r.aging >= st.agingRed).length, meta = S.config.sjMeta;
  $('#sjSummary').innerHTML = `${byCust.length}${byCust.length !== all.length ? ' dari ' + all.length : ''} SJ belum kembali milik driver aktif di ${esc(st.sjPool)} · <b style="color:var(--danger)">${red}</b> aging ≥ ${st.agingRed} hari${meta ? ' · file ' + esc(meta.name) : ''}`;
  $('#custLabel').textContent = sjFilterLabel(custs);
  const none = sjFilter.sel !== null && !custs.some(c => sjFilter.sel.has(c[0]));
  $('#sjTable').innerHTML = `<div class="panel"><div class="tbl-wrap" style="max-height:70vh"><table class="t wrapall"><thead><tr><th>No</th><th class="l">ID Driver</th><th class="l">Nama Driver</th><th class="l">Customer</th><th>Tgl Tender</th><th class="l">No. Shipment</th><th>Aging (Hari)</th></tr></thead><tbody>${
    rows.map((r, i) => `<tr><td class="num">${i + 1}</td><td class="l">${esc(r.id)}</td><td class="l">${esc(r.nama)}</td><td class="l">${esc(r.cust)}</td><td class="num">${fmtDMY(r.tender)}</td><td class="l">${esc(r.ship)}</td><td><span class="age${r.aging != null && r.aging >= st.agingRed ? ' red' : ''}">${r.aging == null ? '-' : r.aging}</span></td></tr>`).join('') || `<tr><td colspan="7" class="empty-row">${none ? 'Tidak ada customer dipilih' : 'Tidak ada SJ outstanding'}</td></tr>`}</tbody></table></div></div>`;
}
function renderSJ() {
  const el = $('#v-sj'), st = S.settings;
  const head = `<div class="page-h"><h2>SJ Outstanding</h2><p>Surat jalan yang belum kembali</p></div>`;
  if (!S.sjRows.length) { el.innerHTML = head + `<div class="panel"><div class="empty"><b>Belum ada data SJ</b>Upload file <i>Download Detail SJ</i> di menu <b>Upload</b>.</div></div>`; return; }
  if (S.sjRows[0].length < 7) { el.innerHTML = head + `<div class="panel"><div class="empty"><b>Data SJ lama belum memuat ID driver</b>Upload ulang file <i>Download Detail SJ</i> di menu <b>Upload</b>.</div></div>`; return; }
  const custs = sjCustomers(sjRowsView());
  el.innerHTML = `<div class="page-h"><h2>SJ Outstanding</h2><p id="sjSummary"></p></div>
  <div class="toolbar sjbar">
    <div class="msel"><button type="button" class="btn msel-btn" id="custBtn" aria-haspopup="listbox" aria-expanded="${sjFilter.open}">Customer: <b id="custLabel"></b><svg class="ic" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg></button>
      <div class="msel-panel" id="custPanel"${sjFilter.open ? '' : ' hidden'}>
        <input type="search" id="custSearch" placeholder="Cari customer..." aria-label="Cari customer">
        <div class="msel-actions"><button type="button" class="btn" id="custAll">Pilih semua</button><button type="button" class="btn" id="custNone">Kosongkan</button></div>
        <div class="msel-list" id="custList" role="listbox" aria-multiselectable="true">${custs.map(([c, n], i) => `<label class="msel-item" data-i="${i}"><input type="checkbox" data-i="${i}"${sjIsSel(c) ? ' checked' : ''}><span>${esc(c)}</span><em>${n}</em></label>`).join('')}</div>
      </div></div>
    <input type="search" id="sjSearch" placeholder="Cari driver, customer, atau shipment..." value="${esc(sjQuery.q)}" aria-label="Cari SJ">
  </div>
  <div id="sjTable"></div>`;
  const panel = $('#custPanel'), btn = $('#custBtn');
  const setOpen = o => { sjFilter.open = o; panel.hidden = !o; btn.setAttribute('aria-expanded', String(o)); if (o) $('#custSearch').focus(); };
  btn.onclick = e => { e.stopPropagation(); setOpen(!sjFilter.open); };
  const checks = () => [...el.querySelectorAll('#custList input')];
  $('#custList').onchange = e => {
    const i = +e.target.dataset.i, name = custs[i][0];
    if (sjFilter.sel === null) sjFilter.sel = new Set(custs.map(c => c[0]));
    if (e.target.checked) sjFilter.sel.add(name); else sjFilter.sel.delete(name);
    if (custs.every(c => sjFilter.sel.has(c[0]))) sjFilter.sel = null;   // semua terpilih = "Semua customer"
    renderSJTable();
  };
  $('#custAll').onclick = () => { sjFilter.sel = null; checks().forEach(c => { c.checked = true; }); renderSJTable(); };
  $('#custNone').onclick = () => { sjFilter.sel = new Set(); checks().forEach(c => { c.checked = false; }); renderSJTable(); };
  $('#custSearch').oninput = e => { const q = e.target.value.trim().toLowerCase(); el.querySelectorAll('#custList .msel-item').forEach(l => { l.hidden = q !== '' && !custs[+l.dataset.i][0].toLowerCase().includes(q); }); };
  const s = $('#sjSearch'); s.oninput = () => { sjQuery.q = s.value; renderSJTable(); };
  renderSJTable();
}
// tutup daftar customer saat klik di luar / tekan Escape
document.addEventListener('click', e => { if (sjFilter.open && !e.target.closest('.msel')) { sjFilter.open = false; const p = $('#custPanel'), b = $('#custBtn'); if (p) p.hidden = true; if (b) b.setAttribute('aria-expanded', 'false'); } });
document.addEventListener('keydown', e => { if (e.key === 'Escape' && sjFilter.open) { sjFilter.open = false; const p = $('#custPanel'), b = $('#custBtn'); if (p) p.hidden = true; if (b) { b.setAttribute('aria-expanded', 'false'); b.focus(); } } });

/* =====================================================================
   MASTER
   ===================================================================== */
const MF = { driver: 'aktif', unit: 'aktif' };   // filter Status Driver / Status Unit di tab Master: all | aktif | nonaktif (bawaan: Aktif)   // filter kolom Status Driver di tab Master Driver: all | aktif | nonaktif
// CATATAN per driver / unit: berlaku sejak tanggal diisi dan seterusnya sampai diubah atau dihapus. Disimpan terpisah dari Master (tidak hilang saat Master di-upload ulang).
const noteOf = (kind, key, day) => { let t = ''; for (const e of ((S.notes[kind] || {})[key]) || []) if (e[0] <= day) t = e[1]; return t; };
const noteDriver = (id, day) => noteOf('drivers', id, day == null ? todaySerial() : day);
const noteUnit = (nopol, day) => { const k = normPlat(nopol); return k ? noteOf('units', k, day == null ? todaySerial() : day) : ''; };
const noteHtml = t => (t ? `<div class="catn" title="${esc(t)}">(${esc(t)})</div>` : '');
async function saveNote(kind, key, text) {
  text = String(text || '').trim(); const day = todaySerial(), old = ((S.notes[kind] || {})[key]) || [];
  let arr = old.filter(e => e[0] !== day).map(e => [e[0], e[1]]); let prev = ''; arr.forEach(e => { if (e[0] < day) prev = e[1]; });
  if (text || prev) arr.push([day, text]); arr.sort((x, y) => x[0] - y[0]);
  if (JSON.stringify(arr) === JSON.stringify(old)) return;
  await dbWrite(() => upsert('app/notes', { [kind]: { [key]: arr.length ? arr : null } }));
}
const MSEARCH = { md: '', mu: '' };   // kata kunci kotak cari di Master Driver / Master Unit (semua kata harus ada di salah satu kolom)
const msearch = (list, cols, q) => { const ws = String(q || '').trim().toLowerCase().split(/\s+/).filter(Boolean); return ws.length ? list.filter(r => { const tx = cols.map(c => String(c.get(r))).join(' | ').toLowerCase(); return ws.every(w => tx.includes(w)); }) : list; };
const mdCols = t => [
  { label: 'ID Driver', cls: 'l', get: d => d.id }, { label: 'Nama Driver', cls: 'l', get: d => d.nama }, { label: 'Catatan', cls: 'l', get: d => noteDriver(d.id, t) }, { label: 'Status', get: d => d.status || '-' },
  { label: 'Status Klinik', get: d => klinikOf(d.id) || 'N/A' }, { label: 'Kode Absensi', get: d => absensiOf(d.id, t).kode },
  { label: 'Tanggal Aktif', get: d => fmtSerial(d.aktif) }, { label: 'Tanggal Nonaktif', get: d => fmtSerial(d.nonaktif) }, { label: 'Status Driver', get: d => (isActive(d, t) ? 'Aktif' : 'Nonaktif') }
];
function renderMasterDriver() {
  const t = todaySerial(), cols = mdCols(t);
  const list = msearch(S.masterDB.filter(d => { const a = isActive(d, t); return MF.driver === 'all' || (MF.driver === 'aktif' ? a : !a); }), cols, MSEARCH.md);
  SFREG.md = { names: cols.map(c => c.label), matrix: () => sfMatrix(list, cols), render: renderMasterDriver };
  $('#mdHead').innerHTML = '<th>No</th>' + cols.map((c, i) => sfTh('md', i, c.label, c.cls)).join('');
  const view = sfApply('md', list, cols);
  $('#tbMD').innerHTML = view.length ? view.map((d, i) => {
    const a = isActive(d, t);
    return `<tr style="${a ? '' : 'opacity:.55'}"><td class="num">${i + 1}</td><td class="l">${esc(d.id)}</td><td class="l">${esc(d.nama)}</td><td class="l"><input type="text" class="cell-in note-in" data-note="drivers" data-k="${esc(d.id)}" value="${esc(noteDriver(d.id, t))}" title="${esc(noteDriver(d.id, t))}" placeholder="Tulis catatan..."${CAN_WRITE ? '' : ' disabled'}></td><td>${esc(d.status || '-')}</td><td>${klinikCell(d.id)}</td><td>${(() => { const ab = absensiOf(d.id, t); return absensiPill(ab.kode, kodeWhy(ab, t)); })()}</td><td class="num">${fmtSerial(d.aktif)}</td><td class="num">${fmtSerial(d.nonaktif)}</td><td><span class="pill ${a ? 'c-OR' : 'c-O'}">${a ? 'Aktif' : 'Nonaktif'}</span></td></tr>`;
  }).join('') : `<tr><td colspan="10" class="empty-row">Tidak ada driver yang cocok dengan filter</td></tr>`;
  $('#cntMD').textContent = S.masterDB.length + ' driver · ' + activeDrivers().length + ' aktif hari ini' + (S.mDriverDoc ? ' · hasil upload' : ' · data bawaan') + (view.length === S.masterDB.length ? '' : ` · menampilkan ${view.length}`);
  $('#resetMD').hidden = !(sfActive('md') || MSEARCH.md.trim());
}
const muCols = t => [
  { label: 'Nopol Unit', cls: 'l', get: u => u.nopol }, { label: 'Tipe Trailer', cls: 'l', get: u => u.type }, { label: 'Catatan', cls: 'l', get: u => noteUnit(u.nopol, t) }, { label: 'Sub Status', get: u => u.sub || '-' },
  { label: 'Status P2H', get: u => p2hOf(u.nopol) || 'N/A' }, { label: 'Status FMS BD', get: u => unitStatus(u.nopol) },
  { label: 'Tanggal Aktif', get: u => fmtSerial(u.aktif) }, { label: 'Tanggal Nonaktif', get: u => fmtSerial(u.nonaktif) }, { label: 'Status Unit', get: u => (isActive(u, t) ? 'Aktif' : 'Nonaktif') }
];
function renderMasterUnit() {
  const t = todaySerial(), cols = muCols(t);
  const list = msearch(S.unitDB.filter(u => { const a = isActive(u, t); return MF.unit === 'all' || (MF.unit === 'aktif' ? a : !a); }), cols, MSEARCH.mu);
  SFREG.mu = { names: cols.map(c => c.label), matrix: () => sfMatrix(list, cols), render: renderMasterUnit };
  $('#muHead').innerHTML = '<th>No</th>' + cols.map((c, i) => sfTh('mu', i, c.label, c.cls)).join('');
  const view = sfApply('mu', list, cols);
  $('#tbMU').innerHTML = view.length ? view.map((u, i) => {
    const a = isActive(u, t);
    return `<tr style="${a ? '' : 'opacity:.55'}"><td class="num">${i + 1}</td><td class="l">${esc(u.nopol)}</td><td class="l">${esc(u.type)}</td><td class="l"><input type="text" class="cell-in note-in" data-note="units" data-k="${esc(normPlat(u.nopol))}" value="${esc(noteUnit(u.nopol, t))}" title="${esc(noteUnit(u.nopol, t))}" placeholder="Tulis catatan..."${CAN_WRITE ? '' : ' disabled'}></td><td>${esc(u.sub || '-')}</td><td class="wrap">${p2hCell(u.nopol)}</td><td class="wrap">${unitCell(u.nopol)}</td><td class="num">${fmtSerial(u.aktif)}</td><td class="num">${fmtSerial(u.nonaktif)}</td><td><span class="pill ${a ? 'c-OR' : 'c-O'}">${a ? 'Aktif' : 'Nonaktif'}</span></td></tr>`;
  }).join('') : `<tr><td colspan="10" class="empty-row">Tidak ada unit yang cocok dengan filter</td></tr>`;
  const act = activeUnits();
  $('#cntMU').textContent = S.unitDB.length + ' unit · ' + act.length + ' aktif hari ini' + (S.mUnitDoc ? ' · hasil upload' : ' · data bawaan') + (view.length === S.unitDB.length ? '' : ` · menampilkan ${view.length}`);
  $('#resetMU').hidden = !(sfActive('mu') || MSEARCH.mu.trim());
  refreshUnitList();
}

/* =====================================================================
   EXCEL
   ===================================================================== */
async function saveWb(wb, filename) {
  try {
    if (window.claude && typeof window.claude.use === 'function') {
      const dl = await window.claude.use('downloads');
      if (dl) { await dl.save({ filename, data: XLSX.write(wb, { type: 'array', bookType: 'xlsx' }) }); return; }
    }
  } catch (e) { if (e && e.code === 'declined') return; }
  XLSX.writeFile(wb, filename);
}
function downloadTemplate(type) {
  const drv = type === 'driver';
  const head = drv ? ['DRIVER ID', 'NAMA DRIVER', 'STATUS', 'TANGGAL AKTIF', 'TANGGAL NONAKTIF'] : ['Nopol', 'Tipe Trailer', 'Sub Status', 'TANGGAL AKTIF', 'TANGGAL NONAKTIF'];
  const rows = drv ? S.masterDB.map(r => [r.id, r.nama, r.status || '', r.aktif, r.nonaktif]) : S.unitDB.map(r => [r.nopol, r.type, r.sub || '', r.aktif, r.nonaktif]);
  const ws = XLSX.utils.aoa_to_sheet([head, ...rows]);
  for (let i = 0; i < rows.length; i++) for (const c of ['D', 'E']) { const cell = ws[c + (i + 2)]; if (cell && typeof cell.v === 'number') cell.z = 'dd-mmm-yy'; }
  ws['!cols'] = head.map((_, i) => ({ wch: i === 1 && drv ? 30 : 16 }));
  const guide = drv ? [
    ['Kolom', 'Keterangan'], ['DRIVER ID', 'Wajib, unik per driver'], ['NAMA DRIVER', 'Wajib. Nama driver'], ['STATUS', 'DM / DOS (informasi)'],
    ['TANGGAL AKTIF', 'Tanggal driver mulai bekerja (driver baru). Kosong = aktif sejak awal. Format tanggal Excel, mis. 01-Oct-26'],
    ['TANGGAL NONAKTIF', 'Tanggal driver TIDAK lagi dihitung (mis. tanggal efektif resign). Driver masih aktif s/d H-1. Kosong = masih aktif. Driver yang tidak aktif hari ini tidak tampil di List Driver'],
    ['Catatan', 'Judul kolom lama (ID_Driver, Nama_Driver) juga masih dibaca saat upload. Hanya sheet pertama yang dibaca']
  ] : [
    ['Kolom', 'Keterangan'], ['Nopol', 'Wajib, unik per unit'], ['Tipe Trailer', 'Wajib. Mis. 20 FT / 40 FT / 40FT-ENGKEL'],
    ['Sub Status', 'MONTHLY_RENT untuk unit sewa bulanan; kosong untuk reguler (informasi)'],
    ['TANGGAL AKTIF', 'Tanggal unit mulai dihitung (unit baru masuk pool). Kosong = aktif sejak awal'],
    ['TANGGAL NONAKTIF', 'Tanggal unit TIDAK lagi dihitung (mis. dijual / pindah pool). Unit masih aktif s/d H-1. Kosong = masih aktif. Unit yang tidak aktif hari ini tidak muncul di pilihan Nopol'],
    ['Catatan', 'Judul kolom lama (Nopol_Unit, Type_Unit) juga masih dibaca saat upload. Hanya sheet pertama yang dibaca']
  ];
  const wb = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(wb, ws, drv ? 'Master Driver' : 'Master Unit');
  XLSX.utils.book_append_sheet(wb, XLSX.utils.aoa_to_sheet(guide), 'Petunjuk');
  saveWb(wb, (drv ? 'Master Driver' : 'Master Unit') + ' - Quedrive.xlsx');
}
// CSV dibaca sendiri (tanpa konversi tanggal/angka otomatis oleh SheetJS); sel kosong = null seperti hasil Excel
function parseCsv(text) {
  text = text.replace(/^\uFEFF/, '');
  const first = text.split(/\r?\n/, 1)[0], delim = [',', ';', '\t'].map(d => [d, first.split(d).length]).sort((a, b) => b[1] - a[1])[0][0];
  const rows = []; let row = [], f = '', q = false;
  for (let i = 0; i < text.length; i++) {
    const c = text[i];
    if (q) { if (c === '"') { if (text[i + 1] === '"') { f += '"'; i++; } else q = false; } else f += c; }
    else if (c === '"') q = true;
    else if (c === delim) { row.push(f); f = ''; }
    else if (c === '\n' || c === '\r') { if (c === '\r' && text[i + 1] === '\n') i++; row.push(f); f = ''; rows.push(row); row = []; }
    else f += c;
  }
  if (f !== '' || row.length) { row.push(f); rows.push(row); }
  return rows.map(r => r.map(x => (x === '' ? null : x)));
}
function readAoa(file) {
  return new Promise((resolve, reject) => {
    const r = new FileReader(), isCsv = /\.csv$/i.test(file.name || '');
    r.onload = e => { try { if (isCsv) { resolve(parseCsv(String(e.target.result))); return; } const wb = XLSX.read(e.target.result, { type: 'array' }); resolve(XLSX.utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]], { header: 1, raw: true, defval: null })); } catch (err) { reject(err); } };
    r.onerror = () => reject(new Error('baca file gagal'));
    if (isCsv) r.readAsText(file); else r.readAsArrayBuffer(file);
  });
}
async function handleMasterUpload(e, type) {
  const f = e.target.files[0]; if (!f) return; e.target.value = '';
  let aoa; try { aoa = await readAoa(f); } catch (err) { toast('File tidak dapat dibaca. Pastikan format Excel (.xlsx/.xls).'); return; }
  const rows = aoaToObjects(aoa);
  let bad = 0;
  const dt = v => { const x = parseDateCell(v); if (x === undefined) { bad++; return null; } return x; };
  const out = [];
  if (type === 'driver') {
    rows.forEach(r => {
      const id = pickCol(r, COLS.dId), nama = pickCol(r, COLS.dNama);
      if (id && nama) out.push([String(id).trim(), String(nama).trim(), String(pickCol(r, COLS.dStatus)).trim(), dt(pickCol(r, COLS.aktif)), dt(pickCol(r, COLS.nonaktif))]);
    });
  } else {
    rows.forEach(r => {
      const np = pickCol(r, COLS.uNopol), tp = pickCol(r, COLS.uTipe);
      if (np && tp) out.push([String(np).trim(), String(tp).trim(), String(pickCol(r, COLS.uSub)).trim(), dt(pickCol(r, COLS.aktif)), dt(pickCol(r, COLS.nonaktif))]);
    });
  }
  if (!out.length) { toast('Tidak ada baris valid di file. Master tidak diubah. Cek judul kolom (lihat Download (template)).'); return; }
  const ok = await dbWrite(() => dbSet(type === 'driver' ? 'app/master_driver' : 'app/master_unit', { rows: out, meta: { name: f.name, at: Date.now() } }));
  if (ok) toast('Master ' + (type === 'driver' ? 'Driver' : 'Unit') + ' berhasil diupload! (' + out.length + ' data)' + (bad ? ' · ' + bad + ' tanggal tidak terbaca (dianggap kosong)' : ''));
}
function aoaToObjects(aoa) {
  const hdr = (aoa[0] || []).map(h => (h == null ? '' : String(h)));
  return aoa.slice(1).map(r => { const o = {}; hdr.forEach((h, i) => { if (h) o[h] = r[i] == null ? '' : r[i]; }); return o; });
}
function resetMaster(type) {
  const n = type === 'driver' ? 'Master Driver' : 'Master Unit';
  askConfirm('Pakai data bawaan?', `${n} hasil upload akan diganti kembali dengan data bawaan (dari SIGAP) untuk semua orang.`, 'Pakai bawaan', async () => {
    const ok = await dbWrite(() => dbDel(type === 'driver' ? 'app/master_driver' : 'app/master_unit'));
    if (ok) toast(n + ' kembali ke data bawaan.');
  });
}

/* =====================================================================
   UPLOAD DOTS & SJ
   ===================================================================== */
function findHeaderRow(aoa, need) {
  for (let r = 0; r < Math.min(aoa.length, 15); r++) {
    const cells = (aoa[r] || []).map(normKey);
    if (need.every(n => cells.includes(n))) { const idx = {}; cells.forEach((c, i) => { if (c && idx[c] == null) idx[c] = i; }); return { r, idx }; }
  }
  return null;
}
const clean = v => String(v == null ? '' : v).trim().replace(/\s+/g, ' ');
const drvId = v => { const s = clean(v).toUpperCase(); return s === '-' ? '' : s; };
async function writeChunks(col, rows, per, oldIds) {
  const n = Math.max(1, Math.ceil(rows.length / per));
  for (let i = 0; i < n; i++) await dbSet(col + '/c' + i, { rows: rows.slice(i * per, (i + 1) * per) });
  for (const id of oldIds) if (chunkNo(id) >= n) await dbDel(col + '/' + id);
}
const normH2 = v => String(v == null ? '' : v).toUpperCase().replace(/[._\-\/]+/g, ' ').replace(/\s+/g, ' ').trim();
const NOPOL_ALIAS = ['NOPOL', 'NOPOL UNIT', 'NOPOL TRUCK', 'NOPOL KENDARAAN', 'NO POL', 'NO POLISI', 'NOMOR POLISI', 'NOMOR POL', 'PLAT', 'PLAT NOMOR', 'NO PLAT', 'NOMOR PLAT', 'NO KENDARAAN', 'NOMOR KENDARAAN', 'NO UNIT', 'LICENSE PLATE', 'PLATE NO'];
const DRIVER_ALIAS = ['DRIVER ID', 'ID DRIVER', 'ID', 'NID', 'NO ID', 'NIK', 'ID PENGEMUDI', 'KODE DRIVER', 'ID KARYAWAN', 'NO DRIVER'];
// dua data terpisah dengan pola yang sama: Status P2H (kunci = nopol unit) dan Status Klinik (kunci = ID driver)
const LOOKUP = {
  p2h: { coll: 'p2h', meta: 'p2hMeta', norm: normPlat, keyAlias: NOPOL_ALIAS, hdrRe: /P2H/, fallback: ['STATUS', 'HASIL', 'KONDISI', 'STATUS UNIT'], label: 'Status P2H', keyLabel: 'Nopol', unit: 'unit', ids: () => S.p2hIds },
  klinik: { coll: 'klinik', meta: 'klinikMeta', norm: normId, keyAlias: DRIVER_ALIAS, hdrRe: /KLINIK|KESEHATAN/, fallback: ['STATUS', 'HASIL', 'KONDISI', 'STATUS DRIVER', 'KESIMPULAN'], label: 'Status Klinik', keyLabel: 'ID Driver', unit: 'driver', ids: () => S.klinikIds }
};
// cari baris judul yang punya kolom kunci + kolom status. strict=true (deteksi otomatis) mewajibkan kata kunci pada judul status
// (P2H / KLINIK); strict=false (tombol khusus di kartu upload) boleh juga kolom "Status"/"Hasil" biasa.
function findLookupCols(aoa, strict, L) {
  const isDate = c => /^(TANGGAL|TGL|DATE|WAKTU|TIME|JAM)/.test(c);
  for (let r = 0; r < Math.min(aoa.length, 15); r++) {
    const row = aoa[r] || [], cells = row.map(normH2);
    const iN = cells.findIndex(c => L.keyAlias.includes(c)); if (iN < 0) continue;
    let iS = cells.findIndex((c, i) => i !== iN && L.hdrRe.test(c) && /STATUS|HASIL|KONDISI|KESIMPULAN/.test(c));
    if (iS < 0) iS = cells.findIndex((c, i) => i !== iN && L.hdrRe.test(c) && !isDate(c));
    if (iS < 0 && !strict) iS = cells.findIndex((c, i) => i !== iN && L.fallback.includes(c));
    if (iS < 0) continue;
    const iD = cells.findIndex((c, i) => i !== iN && i !== iS && isDate(c));
    return { r, iN, iS, iD, nameN: clean(row[iN]), nameS: clean(row[iS]) };
  }
  return null;
}
async function ingestLookup(kind, aoa, h, name) {
  const L = LOOKUP[kind], best = new Map(); let total = 0;
  for (let r = h.r + 1; r < aoa.length; r++) {
    const a = aoa[r]; if (!a || !a.length) continue;
    const key0 = L.norm(a[h.iN]), st = clean(a[h.iS]); if (!key0 || !st) continue; total++;
    let key = r;                                     // tanpa kolom tanggal: baris paling bawah menang
    if (h.iD >= 0) { const p = parseDT(a[h.iD]); key = p ? p.d * 1440 + p.m : -1; }   // dengan tanggal: yang terbaru menang
    const cur = best.get(key0); if (!cur || key >= cur.key) best.set(key0, { key, st });
  }
  if (!best.size) return `${name}: tidak ada baris dengan ${L.keyLabel} dan ${L.label} terisi`;
  const rows = [...best].map(([k, v]) => [k, v.st]), dist = {};
  rows.forEach(r => { const k = r[1].toUpperCase(); dist[k] = (dist[k] || 0) + 1; });
  const top = Object.entries(dist).sort((x, y) => y[1] - x[1]).slice(0, 6);
  const ok = await dbWrite(async () => {
    await writeChunks(L.coll, rows, 3000, L.ids());
    await upsert('app/config', { [L.meta]: { name, at: Date.now(), total, units: rows.length, colN: h.nameN, colS: h.nameS, byDate: h.iD >= 0, dist: top } });
  });
  if (!ok) return `${name}: gagal disimpan`;
  scheduleHistorySave();
  return `${L.label} terbaca: ${rows.length} ${L.unit} (kolom "${h.nameN}" dan "${h.nameS}")`;
}
// ---- Monitoring Attendance: satu catatan per baris (Driver ID, Attendance Date, Time, Attendance Type) ----
function findMonitoring(aoa) {
  for (let r = 0; r < Math.min(aoa.length, 15); r++) {
    const cells = (aoa[r] || []).map(normH2);
    if (['DRIVER ID', 'ATTENDANCE DATE', 'ATTENDANCE TYPE'].every(n => cells.includes(n))) { const ix = {}; cells.forEach((c, i) => { if (c && ix[c] == null) ix[c] = i; }); return { r, ix }; }
  }
  return null;
}
// jam dari teks "HH:MM:SS", pecahan hari Excel, atau objek tanggal -> "HH:MM:SS"
function timeOf(v) {
  if (v == null || v === '') return '';
  if (v instanceof Date) return pad(v.getHours()) + ':' + pad(v.getMinutes()) + ':' + pad(v.getSeconds());
  if (typeof v === 'number') { const f = v - Math.floor(v), s = Math.round(f * 86400); return s ? pad(Math.floor(s / 3600) % 24) + ':' + pad(Math.floor(s / 60) % 60) + ':' + pad(s % 60) : ''; }
  const m = /(\d{1,2}):(\d{2})(?::(\d{2}))?/.exec(String(v)); return m ? pad(+m[1]) + ':' + m[2] + ':' + (m[3] || '00') : '';
}
// Konversi tipe absensi: Masuk -> X; Dalam Perjalanan | ... -> OR (keduanya ada jam); Izin / Sakit -> AIS; Standby -> S;
// Keluar -> N/A (jamnya tidak diambil). Satu hari bisa punya beberapa catatan; dipilih catatan Masuk/Dalam Perjalanan dengan jam TERBARU,
// jika tidak ada: Izin/Sakit, lalu Standby; jika hanya Keluar: kosong (N/A).
async function ingestMonitoring(aoa, h, name) {
  const I = h.ix, rec = new Map(), names = new Map(), unknown = {};
  for (let r = h.r + 1; r < aoa.length; r++) {
    const a = aoa[r]; if (!a || !a.length) continue;
    const id = clean(a[I['DRIVER ID']]).toUpperCase(); if (!id) continue;
    const dp = parseDT(a[I['ATTENDANCE DATE']]); if (!dp) continue;
    const typ = clean(a[I['ATTENDANCE TYPE']]).toLowerCase(), tm = I['TIME'] != null ? timeOf(a[I['TIME']]) : '';
    if (!names.has(id) && I['DRIVER NAME'] != null) names.set(id, clean(a[I['DRIVER NAME']]));
    let code;
    if (typ.startsWith('keluar')) code = 'KEL';
    else if (typ.startsWith('dalam perjalanan')) code = 'OR';
    else if (typ.startsWith('masuk')) code = 'X';
    else if (typ === 'standby') code = 'STB';
    else if (typ === 'izin' || typ === 'sakit') code = 'AIS';
    else { unknown[typ || '(kosong)'] = (unknown[typ || '(kosong)'] || 0) + 1; continue; }
    const k = id + '|' + dp.d; if (!rec.has(k)) rec.set(k, { id, d: dp.d, list: [] });
    rec.get(k).list.push({ code, tm: code === 'KEL' ? '' : tm });
  }
  if (!rec.size) return `${name}: tidak ada catatan absensi yang bisa dibaca`;
  const days = [...new Set([...rec.values()].map(x => x.d))].sort((x, y) => x - y), dayIdx = new Map(days.map((d, i) => [d, i]));
  const byId = new Map(), dist = {};
  for (const x of rec.values()) {
    // jam terbaru catatan Masuk (X) dan Dalam Perjalanan (OR); Izin/Sakit/Standby/Keluar hanya dicatat sebagai penanda
    const latest = c => { const l = x.list.filter(e => e.code === c && e.tm).map(e => e.tm).sort(); return l.length ? l[l.length - 1].slice(0, 5) : ''; };
    const tX = latest('X'), tO = latest('OR'), parts = [];
    if (tX) parts.push('X@' + tX); else if (x.list.some(e => e.code === 'X')) parts.push('X');
    if (tO) parts.push('OR@' + tO); else if (x.list.some(e => e.code === 'OR')) parts.push('OR');
    if (x.list.some(e => e.code === 'AIS')) parts.push('AIS');
    if (x.list.some(e => e.code === 'STB')) parts.push('STB');
    parts.forEach(p => { const k = p.split('@')[0]; dist[k] = (dist[k] || 0) + 1; });
    if (!parts.length) dist['Keluar saja'] = (dist['Keluar saja'] || 0) + 1;
    if (!byId.has(x.id)) byId.set(x.id, new Array(days.length).fill(''));
    byId.get(x.id)[dayIdx.get(x.d)] = parts.join(';');
  }
  const rows = [...byId].map(([id, codes]) => [id, names.get(id) || '', codes]).sort((p, q) => (p[0] < q[0] ? -1 : 1));
  const d0 = days[0], d1 = days[days.length - 1], PER = 250, nCh = Math.ceil(rows.length / PER), at = Date.now();
  const top = Object.entries(dist).sort((x, y) => y[1] - x[1]).slice(0, 8), unk = Object.entries(unknown).slice(0, 4);
  const ok = await dbWrite(async () => {
    for (let k = 0; k < nCh; k++) await dbSet(`att/m${d0}_${k}`, { meta: { name, at, d0, d1, n: Math.min(PER, rows.length - k * PER), total: rows.length, kind: 'mon', group: 'm' + d0 + '_' + at, chunk: k, nChunks: nCh, dist: k === 0 ? top : [], unknown: k === 0 ? unk : [] }, days, rows: rows.slice(k * PER, (k + 1) * PER) });
    for (const f of S.att) if (f.id.startsWith(`m${d0}_`) && (f.meta.chunk || 0) >= nCh) await dbDel('att/' + f.id);
  });
  if (!ok) return `${name}: gagal disimpan`;
  scheduleHistorySave();
  return `Monitoring Attendance terbaca: ${rows.length} driver, ${days.length} tanggal (${fmtSerial(d0)} s/d ${fmtSerial(d1)})${unk.length ? '; tipe tak dikenal diabaikan: ' + unk.map(u => u[0] + ' ' + u[1]).join(', ') : ''}`;
}
// ---- GPS Tracking Report: status shipment terakhir driver (closed, cancel, onprogress, ready, forceclosed) ----
function findGpsTracking(aoa) {
  for (let r = 0; r < Math.min(aoa.length, 15); r++) {
    const cells = (aoa[r] || []).map(normH2);
    if (['SHIPMENT NUMBER', 'DRIVER NUMBER', 'LICENSE NUMBER', 'STATUS'].every(n => cells.includes(n))) { const ix = {}; cells.forEach((c, i) => { if (c && ix[c] == null) ix[c] = i; }); return { r, ix }; }
  }
  return null;
}
async function ingestGps(aoa, h, name) {
  const I = h.ix, ships = new Map(); let total = 0;
  for (let r = h.r + 1; r < aoa.length; r++) {
    const a = aoa[r]; if (!a || !a.length) continue;
    const ship = clean(a[I['SHIPMENT NUMBER']]); if (!ship) continue; total++;
    let s = ships.get(ship);
    if (!s) { const sd = I['SHIPMENT DATE'] != null ? parseDT(a[I['SHIPMENT DATE']]) : null; s = { ship, nopol: clean(a[I['LICENSE NUMBER']]), drv: drvId(a[I['DRIVER NUMBER']]), day: sd ? sd.d : null, status: clean(a[I['STATUS']]).toUpperCase(), start: null, stop: 1e9, end: null }; ships.set(ship, s); }
    const stopNo = parseInt(a[I['STOP NO']], 10), ptd = I['PTD'] != null ? parseDT(a[I['PTD']]) : null;
    const sn = isNaN(stopNo) ? 1e8 : stopNo;
    if (ptd && sn < s.stop) { s.stop = sn; s.start = ptd.d * 1440 + ptd.m; }
    // jam closed = waktu aktual terakhir pada shipment (ATA atau ATD terbaru dari semua stop)
    for (const col of ['ATA', 'ATD']) { const p = I[col] != null ? parseDT(a[I[col]]) : null; if (p) { const k = p.d * 1440 + p.m; if (s.end == null || k > s.end) s.end = k; } }
  }
  const rows = [...ships.values()].filter(s => s.drv && s.day != null).map(s => [s.ship, s.nopol, s.drv, s.day, s.status, s.start == null ? s.day * 1440 : s.start, s.end == null ? 0 : s.end]);
  if (!rows.length) return `${name}: tidak ada shipment dengan Driver Number dan Shipment Date terisi`;
  const dist = {}, drivers = new Set(); let dMin = null, dMax = null;
  rows.forEach(r => { dist[r[4].toLowerCase()] = (dist[r[4].toLowerCase()] || 0) + 1; drivers.add(r[2]); if (dMin == null || r[3] < dMin) dMin = r[3]; if (dMax == null || r[3] > dMax) dMax = r[3]; });
  const top = Object.entries(dist).sort((x, y) => y[1] - x[1]).slice(0, 6);
  const ok = await dbWrite(async () => {
    await writeChunks('gps', rows, 2000, S.gpsIds);
    await upsert('app/config', { gpsMeta: { name, at: Date.now(), total, ships: rows.length, drivers: drivers.size, dMin, dMax, dist: top } });
  });
  if (!ok) return `${name}: gagal disimpan`;
  scheduleHistorySave();
  return `GPS Tracking terbaca: ${rows.length} shipment dari ${drivers.size} driver (status terakhir per driver dipakai untuk Status Antrian)`;
}
// ---- Clinic checkup records: satu baris = satu pemeriksaan (No DM, Tanggal & Time Pemeriksaan, Status fit / FOM, Note) ----
function findClinicRecords(aoa) {
  for (let r = 0; r < Math.min(aoa.length, 15); r++) {
    const cells = (aoa[r] || []).map(normH2);
    if (['NO DM', 'TANGGAL PEMERIKSAAN', 'STATUS'].every(n => cells.includes(n))) { const ix = {}; cells.forEach((c, i) => { if (c && ix[c] == null) ix[c] = i; }); return { r, ix }; }
  }
  return null;
}
const latestOf = (rows, keyIdx) => { const m = new Map(); rows.forEach(r => { const c = m.get(r[0]); if (!c || r[2] >= c[2]) m.set(r[0], r); }); return m; };
async function ingestClinic(aoa, h, name) {
  const I = h.ix, rows = [];
  for (let r = h.r + 1; r < aoa.length; r++) {
    const a = aoa[r]; if (!a || !a.length) continue;
    const id = clean(a[I['NO DM']]).toUpperCase(), st = clean(a[I['STATUS']]).toUpperCase(), dp = parseDT(a[I['TANGGAL PEMERIKSAAN']]); if (!id || !st || !dp) continue;
    const tm = I['TIME PEMERIKSAAN'] != null ? timeOf(a[I['TIME PEMERIKSAAN']]) : '';
    rows.push([normId(id), st, dp.d * 1440 + (tm ? +tm.slice(0, 2) * 60 + +tm.slice(3, 5) : 0), I['NOTE'] != null ? clean(a[I['NOTE']]).slice(0, 60) : '', id]);
  }
  if (!rows.length) return `${name}: tidak ada baris dengan No DM, Tanggal Pemeriksaan, dan Status terisi`;
  const last = latestOf(rows), dist = {}; last.forEach(r => { dist[r[1]] = (dist[r[1]] || 0) + 1; });
  const keys = rows.map(r => r[2]), top = Object.entries(dist).sort((x, y) => y[1] - x[1]).slice(0, 6);
  const ok = await dbWrite(async () => {
    await writeChunks('klinik', rows, 1500, S.klinikIds);
    await upsert('app/config', { klinikMeta: { name, at: Date.now(), total: rows.length, records: rows.length, units: last.size, colN: 'No DM', colS: 'Status', byDate: true, dMin: Math.floor(Math.min(...keys) / 1440), dMax: Math.floor(Math.max(...keys) / 1440), dist: top } });
  });
  if (!ok) return `${name}: gagal disimpan`;
  scheduleHistorySave();
  return `Status Klinik terbaca: ${rows.length} catatan pemeriksaan dari ${last.size} driver (status terbaru per driver: ${top.map(d => d[0] + ' ' + d[1]).join(', ')})`;
}
// ---- P2H inspection records: satu baris = satu inspeksi kendaraan (vehicle license number, status, created date, not ok items) ----
function findP2hRecords(aoa) {
  for (let r = 0; r < Math.min(aoa.length, 15); r++) {
    const cells = (aoa[r] || []).map(normH2);
    if (['VEHICLE LICENSE NUMBER', 'STATUS', 'CREATED DATE'].every(n => cells.includes(n))) { const ix = {}; cells.forEach((c, i) => { if (c && ix[c] == null) ix[c] = i; }); return { r, ix }; }
  }
  return null;
}
async function ingestP2h(aoa, h, name) {
  const I = h.ix, rows = [];
  for (let r = h.r + 1; r < aoa.length; r++) {
    const a = aoa[r]; if (!a || !a.length) continue;
    const nopol = clean(a[I['VEHICLE LICENSE NUMBER']]), k = normPlat(nopol), st = clean(a[I['STATUS']]).toUpperCase(), dp = parseDT(a[I['CREATED DATE']]); if (!k || !st || !dp) continue;
    rows.push([k, st, dp.d * 1440 + dp.m, I['NOT OK ITEMS'] != null ? clean(a[I['NOT OK ITEMS']]).replace(/^>\s*/, '').slice(0, 100) : '', nopol]);
  }
  if (!rows.length) return `${name}: tidak ada baris dengan vehicle license number, status, dan created date terisi`;
  const last = latestOf(rows), dist = {}; last.forEach(r => { dist[r[1]] = (dist[r[1]] || 0) + 1; });
  const keys = rows.map(r => r[2]), top = Object.entries(dist).sort((x, y) => y[1] - x[1]).slice(0, 6);
  const ok = await dbWrite(async () => {
    await writeChunks('p2h', rows, 1500, S.p2hIds);
    await upsert('app/config', { p2hMeta: { name, at: Date.now(), total: rows.length, records: rows.length, units: last.size, colN: 'vehicle license number', colS: 'status', byDate: true, dMin: Math.floor(Math.min(...keys) / 1440), dMax: Math.floor(Math.max(...keys) / 1440), dist: top } });
  });
  if (!ok) return `${name}: gagal disimpan`;
  scheduleHistorySave();
  return `Status P2H terbaca: ${rows.length} catatan inspeksi dari ${last.size} unit (status terbaru per unit: ${top.map(d => d[0] + ' ' + d[1]).join(', ')})`;
}
// ---- bd_export: service order bengkel (Nopol, SO Date, Handover Time, ...) -> Status Unit BD / Ready ----
function findBd(aoa) {
  for (let r = 0; r < Math.min(aoa.length, 15); r++) {
    const cells = (aoa[r] || []).map(normH2);
    if (cells.includes('NOPOL') && cells.includes('SO DATE') && (cells.includes('HANDOVER TIME') || cells.includes('HO TIME'))) { const ix = {}; cells.forEach((c, i) => { if (c && ix[c] == null) ix[c] = i; }); return { r, ix }; }
  }
  return null;
}
async function ingestBd(aoa, h, name) {
  const I = h.ix, hoCol = I['HANDOVER TIME'] != null ? I['HANDOVER TIME'] : I['HO TIME'], rows = [];
  const g = (a, k) => (I[k] != null ? clean(a[I[k]]) : '');
  for (let r = h.r + 1; r < aoa.length; r++) {
    const a = aoa[r]; if (!a || !a.length) continue;
    const nopol = clean(a[I['NOPOL']]), key = normPlat(nopol), sd = parseDT(a[I['SO DATE']]); if (!key || !sd) continue;
    const hv = a[hoCol], hp = (typeof hv === 'string' && /belum/i.test(hv)) ? null : parseDT(hv);   // kosong / "belum" / tidak terbaca = belum handover
    rows.push([key, nopol, sd.d * 1440 + sd.m, hp ? hp.d * 1440 + hp.m : null, g(a, 'SO DELETE') ? 1 : 0, g(a, 'STATUS'), g(a, 'POOL'), g(a, 'PROBLEM DESCRIPTION').slice(0, 80), g(a, 'SO ID')]);
  }
  if (!rows.length) return `${name}: tidak ada baris dengan Nopol dan SO Date terisi`;
  const last = new Map(); let deleted = 0, soMin = null, soMax = null;
  rows.forEach(r => { if (r[4]) deleted++; if (soMin == null || r[2] < soMin) soMin = r[2]; if (soMax == null || r[2] > soMax) soMax = r[2]; if (r[4]) return; const c = last.get(r[0]); if (!c || r[2] >= c[2]) last.set(r[0], r); });
  const nBd = [...last.values()].filter(r => r[3] == null).length, nopols = new Set(rows.map(r => r[0])).size;
  const ok = await dbWrite(async () => {
    await writeChunks('bd', rows, 800, S.bdIds);
    await upsert('app/config', { bdMeta: { name, at: Date.now(), total: rows.length, nopols, deleted, bd: nBd, soMin: Math.floor(soMin / 1440), soMax: Math.floor(soMax / 1440) } });
  });
  if (!ok) return `${name}: gagal disimpan`;
  scheduleHistorySave();
  return `Breakdown Unit terbaca: ${rows.length} SO dari ${nopols} nopol (${nBd} nopol berstatus BD menurut SO terakhir, ${deleted} SO terhapus diabaikan)`;
}
// ---- Attendance (grid): baris = driver, kolom = tanggal, sel = kode (X, OR, O, A, I, S, ADJ). Sumber KODE ABSENSI. ----
function findAttendance(aoa) {
  for (let r = 0; r < Math.min(aoa.length, 15); r++) {
    const row = aoa[r] || [], cells = row.map(normH2);
    const iId = cells.findIndex(c => DRIVER_ALIAS.includes(c)); if (iId < 0) continue;
    const iName = cells.findIndex((c, i) => i !== iId && ['NAMA DRIVER', 'NAMA', 'DRIVER'].includes(c));
    const dayCols = [];
    row.forEach((v, i) => { if (i === iId || i === iName) return; const p = parseDT(v); if (p) dayCols.push([i, p.d]); });
    if (dayCols.length >= 7) return { r, iId, iName, dayCols };
  }
  return null;
}
async function ingestAttendance(aoa, h, name) {
  const rows = [], counts = {}, seen = new Set(), dup = [];
  for (let r = h.r + 1; r < aoa.length; r++) {
    const a = aoa[r]; if (!a || !a.length) continue;
    const id = clean(a[h.iId]).toUpperCase(); if (!id) continue;
    if (seen.has(normId(id))) dup.push(id); seen.add(normId(id));
    const codes = h.dayCols.map(([i]) => (a[i] == null ? '' : String(a[i]).trim().toUpperCase()));
    codes.forEach(c => { if (c) counts[c] = (counts[c] || 0) + 1; });
    rows.push([id, h.iName >= 0 ? clean(a[h.iName]) : '', codes]);
  }
  if (!rows.length) return `${name}: tidak ada baris driver pada file Attendance`;
  const days = h.dayCols.map(d => d[1]), d0 = Math.min(...days), d1 = Math.max(...days);
  const top = Object.entries(counts).sort((x, y) => y[1] - x[1]).slice(0, 8);
  const ok = await dbWrite(() => dbSet('att/a' + d0, { meta: { name, at: Date.now(), d0, d1, n: rows.length, total: rows.length, kind: 'grid', group: 'a' + d0, dist: top, dup: dup.slice(0, 8) }, days, rows }));
  if (!ok) return `${name}: gagal disimpan`;
  scheduleHistorySave();
  return `Attendance terbaca: ${rows.length} baris driver, periode ${fmtSerial(d0)} s/d ${fmtSerial(d1)}${dup.length ? ` (${dup.length} ID ganda, dipakai baris paling bawah)` : ''}`;
}
async function ingestFile(file, forced) {
  let aoa; try { aoa = await readAoa(file); } catch (e) { return `${file.name}: file tidak dapat dibaca`; }
  if (forced === 'att') { const ha = findAttendance(aoa); return ha ? ingestAttendance(aoa, ha, file.name) : `${file.name}: format Attendance tidak dikenali (butuh kolom ID dan minimal 7 kolom tanggal)`; }
  if (forced === 'mon') { const hm = findMonitoring(aoa); return hm ? ingestMonitoring(aoa, hm, file.name) : `${file.name}: format Monitoring Attendance tidak dikenali (butuh kolom Driver ID, Attendance Date, dan Attendance Type)`; }
  if (forced === 'bd') { const hb = findBd(aoa); return hb ? ingestBd(aoa, hb, file.name) : `${file.name}: kolom Nopol, SO Date, dan Handover Time tidak ditemukan`; }
  if (forced === 'gps') { const hg = findGpsTracking(aoa); return hg ? ingestGps(aoa, hg, file.name) : `${file.name}: kolom Shipment Number, License Number, Driver Number, dan Status tidak ditemukan`; }
  if (forced === 'p2h' || forced === 'klinik') {
    const hr = forced === 'p2h' ? findP2hRecords(aoa) : findClinicRecords(aoa);
    if (hr) return forced === 'p2h' ? ingestP2h(aoa, hr, file.name) : ingestClinic(aoa, hr, file.name);
    const L = LOOKUP[forced], hp = findLookupCols(aoa, false, L);
    return hp ? ingestLookup(forced, aoa, hp, file.name) : `${file.name}: kolom ${L.keyLabel} dan ${L.label} tidak ditemukan (judul kolom contoh: "${L.keyLabel}" dan "${L.label}")`;
  }
  let h;
  if ((h = findHeaderRow(aoa, ['SHIPMENT', 'TANGGAL TENDER', 'STATUS FPT', 'ID DRIVER 1']))) return ingestDots(aoa, h, file.name);
  if ((h = findHeaderRow(aoa, ['SHIPMENT NO', 'AGING', 'TENDER TIME', 'CUSTOMER']))) return ingestSJ(aoa, h, file.name);
  const hm = findMonitoring(aoa); if (hm) return ingestMonitoring(aoa, hm, file.name);
  const hg = findGpsTracking(aoa); if (hg) return ingestGps(aoa, hg, file.name);
  const hb = findBd(aoa); if (hb) return ingestBd(aoa, hb, file.name);
  const hcl = findClinicRecords(aoa); if (hcl) return ingestClinic(aoa, hcl, file.name);
  const hp2 = findP2hRecords(aoa); if (hp2) return ingestP2h(aoa, hp2, file.name);
  const ha = findAttendance(aoa); if (ha) return ingestAttendance(aoa, ha, file.name);
  for (const k of ['p2h', 'klinik']) { const hp = findLookupCols(aoa, true, LOOKUP[k]); if (hp) return ingestLookup(k, aoa, hp, file.name); }
  return `${file.name}: tidak dikenali (bukan DOTS Report, Download Detail SJ, Attendance, Monitoring Attendance, GPS Tracking Report, bd_export, P2H inspection records, Clinic checkup records, atau file sederhana Nopol + Status P2H / ID Driver + Status Klinik)`;
}
async function ingestDots(aoa, h, name) {
  const c = k => (h.idx[k] == null ? -1 : h.idx[k]);
  const I = { s: c('SHIPMENT'), d1: c('ID DRIVER 1'), d2: c('ID DRIVER 2'), tt: c('TANGGAL TENDER'), ex: c('EXECUTION DATE'), fpt: c('STATUS FPT'), cu: c('CUSTOMER'), no: c('NOMOR ORDER'), ob: c('ORDER BASE'), rt: c('NAMA RUTE'), tp: c('TIPE TRUCK'), ts: c('TIPE SHIPMENT'), np: c('NOPOL'), ss: c('STATUS SHIPMENT'), pr: c('PROSES STATUS') };
  const rows = []; let total = 0, cancel = 0, replan = 0, noTender = 0, noDrv = 0, tMin = null, tMax = null;
  for (let r = h.r + 1; r < aoa.length; r++) {
    const a = aoa[r]; if (!a || !a.length) continue;
    const ship = clean(a[I.s]); if (!ship) continue; total++;
    const t = parseDT(a[I.tt]); if (!t) { noTender++; continue; }
    const fp = I.fpt >= 0 ? clean(a[I.fpt]).toUpperCase() : '';
    if (fp === 'CANCEL') { cancel++; continue; }
    const d1 = drvId(a[I.d1]), d2 = I.d2 >= 0 ? drvId(a[I.d2]) : '';
    if (!d1 && !d2) { noDrv++; continue; }
    const e = I.ex >= 0 ? parseDT(a[I.ex]) : null;
    rows.push([ship, d1, d2, t.d, t.m, e ? e.d : null, I.cu >= 0 ? clean(a[I.cu]) : '', (I.ob >= 0 ? clean(a[I.ob]) : '') || (I.no >= 0 ? clean(a[I.no]) : ''), I.rt >= 0 ? clean(a[I.rt]) : '', I.tp >= 0 ? clean(a[I.tp]) : '', I.ts >= 0 ? clean(a[I.ts]).toUpperCase() : '', fp, I.np >= 0 ? clean(a[I.np]) : '', (I.ss >= 0 ? clean(a[I.ss]) : '') || (I.pr >= 0 ? clean(a[I.pr]) : '')]);
    if (fp === 'REPLAN') replan++;
    if (tMin == null || t.d < tMin) tMin = t.d; if (tMax == null || t.d > tMax) tMax = t.d;
  }
  if (!rows.length) return `${name}: tidak ada shipment valid (cek kolom TANGGAL TENDER dan ID DRIVER)`;
  const ok = await dbWrite(async () => {
    await writeChunks('dots', rows, 600, S.dotsIds);
    await upsert('app/config', { dotsMeta: { name, at: Date.now(), total, valid: rows.length, cancel, replan, noTender, noDrv, tMin, tMax } });
  });
  if (!ok) return `${name}: gagal disimpan`;
  scheduleHistorySave();
  return `DOTS Report terbaca: ${rows.length} shipment valid (${cancel} CANCEL dan ${noTender} tanpa tender diabaikan; ${replan} REPLAN tidak dihitung di SPK/SPO)`;
}
async function ingestSJ(aoa, h, name) {
  const c = k => (h.idx[k] == null ? -1 : h.idx[k]);
  const I = { s: c('SHIPMENT NO') >= 0 ? c('SHIPMENT NO') : c('SHIPMENT'), pool: c('POOL'), cu: c('CUSTOMER'), tt: c('TENDER TIME'), ag: c('AGING'), st: c('STATUS'), nid: c('NID'), dr: c('DRIVER') };
  const rows = []; let total = 0;
  for (let r = h.r + 1; r < aoa.length; r++) {
    const a = aoa[r]; if (!a || !a.length) continue;
    const ship = clean(a[I.s]); if (!ship) continue; total++;
    if (I.st >= 0) { const st = clean(a[I.st]).toUpperCase(); if (st && !st.includes('BELUM')) continue; }
    const t = parseDT(a[I.tt]); const ag = parseInt(clean(a[I.ag]), 10);
    rows.push([ship, clean(I.pool >= 0 ? a[I.pool] : ''), clean(a[I.cu]), t ? t.d : null, isNaN(ag) ? null : ag, I.nid >= 0 ? drvId(a[I.nid]) : '', I.dr >= 0 ? clean(a[I.dr]) : '']);
  }
  if (!rows.length) return `${name}: tidak ada baris SJ yang terbaca`;
  if (I.nid < 0) return `${name}: kolom NID (ID driver) tidak ditemukan`;
  const ok = await dbWrite(async () => {
    await writeChunks('sj', rows, 1000, S.sjIds);
    await upsert('app/config', { sjMeta: { name, at: Date.now(), total, kept: rows.length } });
  });
  if (!ok) return `${name}: gagal disimpan`;
  scheduleHistorySave();
  return `Download Detail SJ terbaca: ${rows.length} SJ outstanding`;
}
async function ingestFiles(files, forced) {
  if (!files.length) return;
  toast('Membaca ' + files.length + ' file…');
  const msgs = [];
  for (const f of files) msgs.push(await ingestFile(f, forced));
  toast(msgs.join(' · '));
}
async function clearSlot(k) {
  if (k.startsWith('att:')) {
    const key = k.slice(4), docs = S.att.filter(f => (f.meta.group || f.id) === key), f0 = docs[0];
    askConfirm('Hapus file absensi?', `File <b>${esc(f0 ? f0.meta.name : key)}</b> akan dihapus untuk <b>semua orang</b>.`, 'Hapus', async () => { const ok = await dbWrite(async () => { for (const f of docs) await dbDel('att/' + f.id); }); if (ok) { toast('File absensi dihapus.'); scheduleHistorySave(); } });
    return;
  }
  const cfg = { dots: ['dots', 'dotsMeta', 'DOTS Report'], sj: ['sj', 'sjMeta', 'Download Detail SJ'], p2h: ['p2h', 'p2hMeta', 'Status P2H Unit'], klinik: ['klinik', 'klinikMeta', 'Status Klinik Driver'], gps: ['gps', 'gpsMeta', 'GPS Tracking Report'], bd: ['bd', 'bdMeta', 'Breakdown Unit (bd_export)'] }[k];
  askConfirm('Hapus data?', `Data ${cfg[2]} akan dihapus untuk <b>semua orang</b>.`, 'Hapus', async () => {
    const ids = { dots: S.dotsIds, sj: S.sjIds, p2h: S.p2hIds, klinik: S.klinikIds, gps: S.gpsIds, bd: S.bdIds }[k];
    const ok = await dbWrite(async () => { for (const id of ids) await dbDel(cfg[0] + '/' + id); await upsert('app/config', { [cfg[1]]: null }); });
    if (ok) { toast('Data dihapus.'); if (k === 'p2h' || k === 'klinik' || k === 'gps' || k === 'bd') scheduleHistorySave(); }
  });
}
let slotTarget = null;
function pickSlot(k) { slotTarget = k; const i = $('#fileSlot'); i.value = ''; i.click(); }

/* ---------- tampilan Upload ---------- */
function renderData() {
  const el = $('#v-data'), st = S.settings, dm = S.config.dotsMeta, sm = S.config.sjMeta;
  const slot = (k, n, title, ex, need, meta, info) => `<div class="slot"><div class="slot-h"><span class="n">${n}</span>${title}</div><div class="fn">Contoh: ${esc(ex)}<br>Kolom: ${esc(need)}</div>
    ${meta ? `<div class="ok">✓ ${esc(meta.name)}</div><div class="fn">${info}<br>Diupload ${fmtTs(meta.at)}</div>` : `<div class="miss">Belum diupload</div>`}
    <div class="slot-actions"><button class="btn" data-pick="${k}">${meta ? 'Ganti file' : 'Pilih file'}</button>${meta ? `<button class="btn" data-clear="${k}">Hapus</button>` : ''}</div></div>`;
  const mcard = (type, title, n, doc, ex) => `<div class="slot"><div class="slot-h"><span class="n">${n}</span>${title}</div><div class="ok">✓ ${n === 1 ? S.masterDB.length + ' driver · ' + activeDrivers().length + ' aktif hari ini' : S.unitDB.length + ' unit · ' + activeUnits().length + ' aktif hari ini'}</div>
    <div class="fn">${doc ? `Diperbarui dari <b>${esc(doc.meta.name)}</b> (${fmtSerial(dayOfDate(new Date(doc.meta.at)))})` : 'Data bawaan (diambil dari SIGAP)'}<br>Kolom: ${esc(ex)}</div>
    <div class="slot-actions"><button class="btn" data-tpl="${type}">${IC_DL}Download (template)</button><button class="btn" data-mup="${type}">${IC_UP}Upload update</button>${doc ? `<button class="btn" data-mreset="${type}">Pakai bawaan</button>` : ''}</div></div>`;
  const pm = S.config.p2hMeta, km = S.config.klinikMeta, gm = S.config.gpsMeta, bm = S.config.bdMeta;
  const attGroups = new Map(); for (const f of S.att) { const k = f.meta.group || f.id; if (!attGroups.has(k)) attGroups.set(k, []); attGroups.get(k).push(f); }
  const attList = [...attGroups].map(([k, fs]) => { const m0 = (fs.find(f => !(f.meta.chunk > 0)) || fs[0]).meta; return { key: k, m: m0, drivers: fs.reduce((a, f) => a + f.rows.length, 0) }; }).sort((a, b) => a.m.d0 - b.m.d0);
  const attBlock = (kind, title, n, desc, btn) => { const L = attList.filter(a => (a.m.kind === 'mon') === (kind === 'mon'));
    return `<div class="slot"><div class="slot-h"><span class="n">${n}</span>${title}</div><div class="fn">${desc}</div>
    ${L.length ? L.map(a => `<div class="ok">✓ ${esc(a.m.name)}</div><div class="fn">${a.drivers} driver${(a.m.dup || []).length ? ' (ID ganda: ' + esc(a.m.dup.join(', ')) + ', dipakai baris paling bawah)' : ''} · ${fmtSerial(a.m.d0)} s/d ${fmtSerial(a.m.d1)}. Sebaran: ${(a.m.dist || []).map(d => esc(d[0]) + ' ' + d[1]).join(', ')}${(a.m.unknown || []).length ? '. Tipe tak dikenal diabaikan: ' + a.m.unknown.map(u => esc(u[0]) + ' ' + u[1]).join(', ') : ''}<br>Diupload ${fmtTs(a.m.at)} <button class="btn" data-clear="att:${esc(a.key)}">Hapus</button></div>`).join('') : '<div class="miss">Belum diupload</div>'}
    <div class="slot-actions"><button class="btn" data-pick="${kind}">${L.length ? 'Tambah / ganti file' : 'Pilih file'}</button></div></div>`; };
  const attCard = attBlock('att', 'Attendance Driver (Kode Absensi)', 3, 'Contoh: Attendance_Nov_2026.xlsx<br>Kolom: ID, NAMA DRIVER, lalu satu kolom per tanggal (isi: X, OR, O, A, I, S, ADJ). <b>Sumber Kode Absensi</b>. Satu file = satu periode (mis. 26 Okt s/d 25 Nov); periode lain diupload sebagai file terpisah.');
  const monCard = attBlock('mon', 'Monitoring Attendance (jam Absen MTA)', 4, 'Contoh: Monitoring_Attendance.xlsx<br>Kolom: Driver ID, Attendance Date, Time, Attendance Type (Masuk, Dalam Perjalanan | ...). <b>Hanya dipakai untuk jam Absen MTA</b> driver berkode X dan OR; Kode Absensi tidak diambil dari file ini.');
  const fmtRange = (a, b) => (a == null ? '' : `, tender ${fmtSerial(a)} s/d ${fmtSerial(b)}`);
  el.innerHTML = `<div class="page-h"><h2>Upload</h2><p>Data yang diupload di sini tersimpan di server dan dipakai bersama oleh semua yang membuka aplikasi ini.</p></div>
  <div class="drop" id="drop" tabindex="0" role="button" aria-label="Pilih file untuk diupload"><b>Tarik file harian ke sini, atau klik untuk memilih</b><div class="note">DOTS Report, Download Detail SJ, Attendance, Monitoring Attendance, GPS Tracking Report, bd_export (CSV atau Excel), Status P2H Unit, dan Status Klinik Driver dikenali otomatis dari nama kolomnya. Bisa pilih beberapa file sekaligus. Jika file absensi, GPS, P2H, atau Klinik tidak terdeteksi, pakai tombol \"Pilih file\" di kartu masing-masing.</div></div>
  <div class="panel"><h3>File harian</h3><p class="desc">Upload ulang kapan saja untuk memperbarui; data lama akan diganti.</p><div class="slots">
    ${slot('dots', 1, 'DOTS Report', 'DOTS_Report_ALL_06-Oct-2026_14_00.xlsx', 'SHIPMENT, ID DRIVER 1/2, TANGGAL TENDER, EXECUTION DATE, STATUS FPT, CUSTOMER, ORDER BASE, NAMA RUTE, TIPE TRUCK, TIPE SHIPMENT, NOPOL, PROSES STATUS', dm, dm ? `${dm.valid} shipment valid dari ${dm.total} baris (${dm.cancel} CANCEL, ${dm.noTender} tanpa tender diabaikan)${fmtRange(dm.tMin, dm.tMax)}${dm.replan != null ? `. Kartu SPK sudah tender hanya menghitung NEW &amp; CARRY OVER, jadi ${dm.replan} REPLAN dan CANCEL tidak dihitung` : ''}` : '')}
    ${slot('sj', 2, 'Download Detail SJ', '_Download_Detail_SJ.xlsx', 'Shipment No, Pool, Customer, NID, Driver, Tender Time, Aging, Status', sm, sm ? `${sm.kept} SJ outstanding dari ${sm.total} baris, ${st.sjPool === 'ALL' ? 'semua pool' : S.sjRows.filter(r => r[1] === st.sjPool).length + ' di ' + esc(st.sjPool)}; yang ditampilkan hanya milik driver aktif` : '')}
    ${attCard}
    ${monCard}
    ${slot('p2h', 5, 'Status P2H Unit', 'P2H_inspection_records_29_Oct_2026.xlsx', 'vehicle license number, status (READY / BREAKDOWN / PREVENTIVE), created date, not ok items. Format sederhana (Nopol + Status P2H) juga dikenali', pm, pm ? (pm.records ? `${pm.records} catatan inspeksi dari ${pm.units} unit (${fmtSerial(pm.dMin)} s/d ${fmtSerial(pm.dMax)}). Status terbaru per unit: ${(pm.dist || []).map(d => esc(d[0]) + ' ' + d[1]).join(', ')}. Berlaku 24 jam sejak waktu inspeksi (08.00 sampai 07.59 keesokan harinya), lalu menjadi N/A; mengikuti Tanggal aplikasi (inspeksi sesudahnya diabaikan).` : `${pm.units} unit memiliki status P2H${pm.byDate ? ' (status terbaru per nopol)' : ''}. Kolom terbaca: Nopol = "${esc(pm.colN)}", Status = "${esc(pm.colS)}". Sebaran: ${(pm.dist || []).map(d => esc(d[0]) + ' ' + d[1]).join(', ')}`) : '')}
    ${slot('klinik', 6, 'Status Klinik Driver', 'Clinic_checkup_records_29_Oct_2026.xlsx', 'No DM, Tanggal Pemeriksaan, Time Pemeriksaan, Status (fit / FOM), Note. Format sederhana (ID Driver + Status Klinik) juga dikenali', km, km ? (km.records ? `${km.records} catatan pemeriksaan dari ${km.units} driver (${fmtSerial(km.dMin)} s/d ${fmtSerial(km.dMax)}). Status terbaru per driver: ${(km.dist || []).map(d => esc(d[0]) + ' ' + d[1]).join(', ')}. FOM = Fit On Medication. Berlaku 24 jam sejak waktu pemeriksaan (08.00 sampai 07.59 keesokan harinya), lalu menjadi N/A; mengikuti Tanggal aplikasi (pemeriksaan sesudahnya diabaikan).` : `${km.units} driver memiliki status klinik${km.byDate ? ' (status terbaru per driver)' : ''}. Kolom terbaca: ID Driver = "${esc(km.colN)}", Status = "${esc(km.colS)}". Sebaran: ${(km.dist || []).map(d => esc(d[0]) + ' ' + d[1]).join(', ')}`) : '')}
    ${slot('gps', 7, 'GPS Tracking Report', 'GPS_Tracking_Report_28-Oct-2026.xlsx', 'Shipment Number, License Number, Driver Number, Shipment Date, Status (closed, cancel, onprogress, ready, forceclosed), Stop No, PTD', gm, gm ? `${gm.ships} shipment dari ${gm.drivers} driver (${fmtSerial(gm.dMin)} s/d ${fmtSerial(gm.dMax)}). Sebaran status: ${(gm.dist || []).map(d => esc(d[0]) + ' ' + d[1]).join(', ')}. Status shipment terakhir tiap driver dipakai untuk Status Antrian.` : '')}
    ${slot('bd', 8, 'Breakdown Unit (bd_export)', 'bd_export_20261028_1937.csv', 'Nopol, SO Date, Handover Time (opsional: SO Delete, Status, Pool, Problem Description)', bm, bm ? `${bm.total} SO dari ${bm.nopols} nopol (SO ${fmtSerial(bm.soMin)} s/d ${fmtSerial(bm.soMax)}). Nopol yang SO terakhirnya belum handover (BD): ${bm.bd}. ${bm.deleted} SO terhapus (kolom SO Delete terisi).` : '')}
  </div></div>
  <div class="panel"><h3>Data master</h3><p class="desc">Sudah tertanam di aplikasi (diambil dari SIGAP). Untuk memperbarui: download sebagai template, ubah di Excel, lalu upload update.</p><div class="slots">
    ${mcard('driver', 'Master Driver', 1, S.mDriverDoc, 'DRIVER ID, NAMA DRIVER, STATUS, TANGGAL AKTIF, TANGGAL NONAKTIF')}
    ${mcard('unit', 'Master Unit', 2, S.mUnitDoc, 'Nopol, Tipe Trailer, Sub Status, TANGGAL AKTIF, TANGGAL NONAKTIF')}
  </div></div>`;
  const dz = $('#drop');
  dz.onclick = () => { $('#fileAny').value = ''; $('#fileAny').click(); };
  dz.onkeydown = e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); $('#fileAny').click(); } };
  dz.ondragover = e => { e.preventDefault(); dz.classList.add('over'); }; dz.ondragleave = () => dz.classList.remove('over');
  dz.ondrop = e => { e.preventDefault(); dz.classList.remove('over'); ingestFiles([...e.dataTransfer.files]); };
  el.querySelectorAll('[data-pick]').forEach(b => b.onclick = () => pickSlot(b.dataset.pick));
  el.querySelectorAll('[data-clear]').forEach(b => b.onclick = () => clearSlot(b.dataset.clear));
  el.querySelectorAll('[data-tpl]').forEach(b => b.onclick = () => downloadTemplate(b.dataset.tpl));
  el.querySelectorAll('[data-mup]').forEach(b => b.onclick = () => $(b.dataset.mup === 'driver' ? '#fileMD' : '#fileMU').click());
  el.querySelectorAll('[data-mreset]').forEach(b => b.onclick = () => resetMaster(b.dataset.mreset));
}

/* =====================================================================
   DIALOG, NAVIGASI, TEMA, BAR STATUS
   ===================================================================== */
function closeModal() { $('#modal').hidden = true; }
function showSJ(id) {
  const rows = sjRowsView().filter(r => r.id === id), st = S.settings, q = antrian().find(a => a.id === id), nama = q ? q.nama : id;
  $('.modal-box').classList.add('wide');
  $('#modalBody').innerHTML = `<button class="modal-x" id="mx" aria-label="Tutup">×</button><h3>SJ Outstanding ${esc(nama)} <span class="note">${esc(id)}</span></h3>
  <p class="desc">${rows.length} SJ belum kembali. Aging ${st.agingSrc === 'file' ? 'dari kolom Aging file SJ' : 'dihitung dari Tgl Tender sampai hari ini'}; merah jika ≥ ${st.agingRed} hari.</p>
  <div class="tbl-wrap" style="max-height:52vh"><table class="t wrapall"><thead><tr><th class="l">ID Driver</th><th class="l">Nama Driver</th><th class="l">Customer</th><th>Tgl Tender</th><th class="l">No. Shipment</th><th>Aging (Hari)</th></tr></thead><tbody>${
    rows.map(r => `<tr><td class="l">${esc(r.id)}</td><td class="l">${esc(r.nama)}</td><td class="l">${esc(r.cust)}</td><td class="num">${fmtDMY(r.tender)}</td><td class="l">${esc(r.ship)}</td><td><span class="age${r.aging != null && r.aging >= st.agingRed ? ' red' : ''}">${r.aging == null ? '-' : r.aging}</span></td></tr>`).join('') || '<tr><td colspan="6" class="empty-row">Tidak ada SJ outstanding</td></tr>'}</tbody></table></div>`;
  $('#modal').hidden = false; $('#mx').onclick = closeModal; $('#mx').focus();
}
// 1 order terakhir (menurut jam tender) dalam 7 hari terakhir (t-6 s/d t), beserta shipment lain pada nomor order yang sama
function lastOrderOf(id, t) {
  const days = []; for (let d = t; d >= t - 6; d--) if (shipCount(id, d) > 0) days.push(d);
  let best = null; days.forEach(d => shipRowsOn(id, d).forEach(s => { const k = s.td * 1440 + s.tm; if (!best || k > best.k) best = { k, d, ship: s.ship }; }));
  if (!best) return [];
  const ord = (shipList(id, best.d).find(x => x.ship === best.ship) || {}).order || '', out = [];
  days.forEach(d => { const li = new Map(shipList(id, d).map(x => [x.ship, x])); shipRowsOn(id, d).forEach(s => { const x = li.get(s.ship) || {}; if (s.ship === best.ship || (ord && x.order === ord)) out.push({ ship: s.ship, cust: x.cust || '', order: x.order || '', route: x.route || '', td: s.td, tm: s.tm, detail: true }); }); });
  return out;
}
function showShipments(id, mode) {
  const q = antrian().find(a => a.key === id) || antrian().find(a => a.id === id); id = q ? q.id : id;
  const last = mode === 'last', t = todaySerial(), list = last ? lastOrderOf(id, t) : shipList(id, t), nama = q ? q.nama : id;
  const old = !last && list.length && !list.some(x => x.detail), tmap = last ? new Map(list.map(x => [x.ship, x])) : new Map(shipRowsOn(id, t).map(x => [x.ship, x])), ak = q ? antriKeyOf(q) : null;
  const rel = s => { const x = tmap.get(s); if (!x || ak == null) return ''; const d = x.td * 1440 + x.tm - ak, ab = Math.abs(d), tx = `${Math.floor(ab / 60)} j ${ab % 60} m`; return d < 0 ? `${tx} sebelum antri (tidak dihitung)` : `${tx} sesudah antri (dihitung)`; };
  const gmap = gpsStatusMap(), gend = gpsEndMap(), csx = closeSet(), os = q ? orderState(q, t) : null;
  const stCell = s => { const st = gmap.get(s); return st ? `<span class="pill ${csx.has(st) ? 'c-OR' : st === 'CANCEL' ? 'c-O' : 'c-S'}">${esc(st.toLowerCase())}</span>` : naPill('Shipment ini tidak ada di file GPS Tracking (atau file belum diupload). Hubungi tim IT.'); };
  const jamCell = s => { const e = gend.get(s); return e ? esc(fmtDM(Math.floor(e / 1440)) + ' | ' + pad(Math.floor((e % 1440) / 60)) + ':' + pad(e % 60)) : (gmap.has(s) ? '<span class="note" title="Belum ada ATA / ATD di GPS Tracking">-</span>' : naPill('Shipment ini tidak ada di file GPS Tracking.')); };
  const hhm = m => pad(Math.floor(m / 60)) + ':' + pad(m % 60), tdTxt = s => { const x = tmap.get(s); return x ? fmtDM(x.td) + ' | ' + hhm(x.tm) : '-'; };
  const rc = q ? 9 : 8;
  $('.modal-box').classList.add('wide');
  $('#modalBody').innerHTML = `<button class="modal-x" id="mx" aria-label="Tutup">×</button><h3>${last ? 'Order terakhir' : 'Shipment'} ${esc(nama)} <span class="note">${esc(id)}</span></h3>
  <p class="desc">${last ? (list.length ? `1 order terakhir dalam 7 hari terakhir (${esc(fmtSerial(t - 6))} s/d ${esc(fmtSerial(t))}) menurut jam tender terbaru: ${list.length} shipment` : `Tidak ada order dalam 7 hari terakhir (${esc(fmtSerial(t - 6))} s/d ${esc(fmtSerial(t))})`) : `${list.length} shipment pada ${esc(fmtSerial(t))} (tanggal terbesar Execution Date &amp; Tanggal Tender)`}, dari DOTS Report${S.config.dotsMeta ? ' ' + esc(S.config.dotsMeta.name) : ''}.${q ? ` Jam antri: <b>${esc(antriStr(q))}</b>. Order dihitung bila jam tendernya sesudah jam antri; shipment yang di-tender sebelum jam antri tidak dihitung.` : ''}</p>
  ${q && q.retired ? '<div class="alert-note">Putaran sebelumnya: Status Order dibekukan saat driver antri lagi, sehingga jumlah shipment di pil bisa berbeda dengan daftar di bawah (yang memakai data DOTS terbaru).</div>' : ''}
  ${last && q ? '<div class="alert-note">Status Order <b>BELUM</b>: tidak ada shipment yang di-tender sesudah jam antri. Tabel ini hanya riwayat 1 order terakhir.</div>' : ''}
  ${old ? '<div class="alert-note">Data DOTS lama belum memuat customer, nomor order, dan rute. Upload ulang DOTS Report di menu Upload.</div>' : ''}
  <div class="tbl-wrap" style="max-height:52vh"><table class="t wrapall"><thead><tr><th>No</th><th class="l">Customer</th><th class="l">No. Order</th><th class="l">No. Shipment</th><th class="l">Rute</th><th>Tender</th>${q ? '<th>Terhadap jam antri</th>' : ''}<th>Status Shipment</th><th title="ATA / ATD terbaru dari GPS Tracking (untuk shipment closed = jam closed)">Jam Status</th></tr></thead><tbody>${
    list.map((x, i) => `<tr><td class="num">${i + 1}</td><td class="l">${esc(x.cust) || '-'}</td><td class="l">${esc(x.order) || '-'}</td><td class="l">${esc(x.ship)}</td><td class="l">${esc(x.route) || '-'}</td><td class="mono wrap dt">${dtHtml(tdTxt(x.ship))}</td>${q ? `<td class="wrap">${esc(rel(x.ship))}</td>` : ''}<td class="wrap">${stCell(x.ship)}</td><td class="mono wrap dt">${jamCell(x.ship)}</td></tr>`).join('') || `<tr><td colspan="${rc}" class="empty-row">Tidak ada shipment</td></tr>`}</tbody></table></div>`;
  $('#modal').hidden = false; $('#mx').onclick = closeModal; $('#mx').focus();
}
document.addEventListener('keydown', e => { if (e.key === 'Escape' && !$('#modal').hidden) closeModal(); });
function askConfirm(title, msg, yesLabel, onYes) {
  $('.modal-box').classList.remove('wide');
  $('#modalBody').innerHTML = `<h3>${esc(title)}</h3><p>${msg}</p><div style="display:flex;gap:8px;justify-content:flex-end;margin-top:14px"><button class="btn" id="cfNo">Batal</button><button class="btn primary" id="cfYes" style="background:var(--danger);border-color:var(--danger)">${esc(yesLabel)}</button></div>`;
  $('#modal').hidden = false; $('#cfNo').onclick = closeModal; $('#cfYes').onclick = () => { closeModal(); onYes(); }; $('#cfNo').focus();
}
$('#modal').addEventListener('click', e => { if (e.target === $('#modal')) closeModal(); });
let curView = 'dashboard';
function go(v) {
  curView = v;
  document.querySelectorAll('.view').forEach(x => x.classList.toggle('visible', x.id === 'v-' + v));
  document.querySelectorAll('.side-btn').forEach(b => b.classList.toggle('active', b.dataset.v === v));
  const ae = document.activeElement; if (ae && ae !== document.body && ae.blur) ae.blur();   // fokus lama jangan menahan render tab baru
  refreshOpenViews(true);
}
document.querySelectorAll('.side-btn').forEach(b => b.addEventListener('click', () => go(b.dataset.v)));
function inEditing(view) { const a = document.activeElement; return a && ['INPUT', 'SELECT', 'TEXTAREA'].includes(a.tagName) && view.contains(a); }
const pendingViews = new Set();
function refreshOpenViews(force) {
  const map = { history: renderHistory, sj: renderSJ, data: renderData, asumsi: renderAsumsi, raw: renderRaw };
  const fn = map[curView]; if (!fn) return;
  const view = $('#v-' + curView);
  if (!force && curView !== 'history' && inEditing(view)) { pendingViews.add(curView); return; }
  fn();
}
function renderAllData() { renderListDriver(); renderQueueSafe(); updateDashboard(); renderMasterDriver(); renderMasterUnit(); renderShareBar(); refreshOpenViews(); }
document.addEventListener('focusout', () => setTimeout(() => {
  if (queuePending) renderQueueSafe();
  if (pendingViews.has(curView) && !inEditing($('#v-' + curView))) { pendingViews.delete(curView); refreshOpenViews(); }
}, 50));
function renderShareBar() {
  const b = $('#shareBar');
  if (!DBREADY) { b.className = 'sharebar loc'; b.textContent = 'Memuat data…'; return; }
  if (MODE === 'server') {
    if (!CAN_WRITE) { b.className = 'sharebar ro'; b.textContent = 'Mode baca saja: data tersimpan di server, tetapi akun ini tidak bisa mengubahnya (perlu akses Contributor atau Editor).'; }
    else { b.className = 'sharebar srv'; b.textContent = 'Data tersimpan di server dan dibagikan ke semua orang yang membuka aplikasi ini.'; }
  } else {
    b.className = 'sharebar loc';
    b.innerHTML = 'Mode lokal: data hanya tersimpan di browser ini dan tidak dibagikan. Buka lewat link artifact agar tersimpan di server.' +
      (SEED ? ` Data awal dari snapshot artifact${SEED_AT ? ' (' + esc(fmtTs(SEED_AT)) + ')' : ''}.<br><button class="btn" id="btnReseed" type="button">Kembalikan ke snapshot</button>` : '');
    const rb = $('#btnReseed');
    if (rb) rb.onclick = () => askConfirm('Kembalikan ke data snapshot?', 'Semua perubahan yang sudah dibuat di file ini (antrian, upload, setting, riwayat) akan diganti dengan data snapshot artifact.', 'Kembalikan', () => { try { localStorage.removeItem(LOCAL_KEY); } catch (e) {} location.reload(); });
  }
}
// tombol tema di kiri bawah: menampilkan mode TUJUAN (terang -> "Night" dengan ikon bulan, gelap -> "Day" dengan ikon matahari)
const IC_MOON = '<svg viewBox="0 0 24 24" fill="#F2A33A" stroke="#D98200" stroke-width="1.6" stroke-linejoin="round"><path d="M21 12.8A9 9 0 1111.2 3a7 7 0 009.8 9.8z"/></svg>';
const IC_SUN = '<svg viewBox="0 0 24 24" fill="none" stroke="#D98200" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="12" r="4.2" fill="#F2A33A"/><path d="M12 2.5v2.2M12 19.3v2.2M2.5 12h2.2M19.3 12h2.2M5.3 5.3l1.6 1.6M17.1 17.1l1.6 1.6M18.7 5.3l-1.6 1.6M6.9 17.1l-1.6 1.6"/></svg>';
function paintTheme(t) { $('#themeIc').innerHTML = t === 'dark' ? IC_SUN : IC_MOON; $('#themeLbl').textContent = t === 'dark' ? 'Day' : 'Night'; $('#btnTheme').setAttribute('aria-pressed', String(t === 'dark')); }
function setTheme(t) { document.documentElement.setAttribute('data-theme', t); paintTheme(t); try { localStorage.setItem('quedrive_theme', t); } catch (e) {} }
$('#yr').textContent = new Date().getFullYear();
// menu kiri auto-hide: muncul saat mouse didekatkan ke tepi kiri (atau tombol menu / Tab), tertutup lagi saat mouse menjauh
const appShell = $('.app-shell'); let sideTimer = null, sidePinned = false;
function openSide(pin) { clearTimeout(sideTimer); appShell.classList.add('side-open'); if (pin) sidePinned = true; $('#btnMenu').setAttribute('aria-expanded', 'true'); }
function closeSide(force) { clearTimeout(sideTimer); if (sidePinned && !force) return; sidePinned = false; appShell.classList.remove('side-open'); $('#btnMenu').setAttribute('aria-expanded', 'false'); }
const scheduleCloseSide = () => { clearTimeout(sideTimer); sideTimer = setTimeout(() => closeSide(), 280); };
$('.side-hot').addEventListener('mouseenter', () => openSide());
$('.side-hot').addEventListener('mouseleave', scheduleCloseSide);
$('#sidebar').addEventListener('mouseenter', () => clearTimeout(sideTimer));
$('#sidebar').addEventListener('mouseleave', scheduleCloseSide);
$('#sidebar').addEventListener('focusin', () => openSide());
$('#sidebar').addEventListener('focusout', e => { if (!e.relatedTarget || !$('#sidebar').contains(e.relatedTarget)) scheduleCloseSide(); });
$('#btnMenu').addEventListener('click', () => { if (appShell.classList.contains('side-open') && sidePinned) closeSide(true); else openSide(true); });
document.addEventListener('click', e => { if (sidePinned && !e.target.closest('#sidebar') && !e.target.closest('#btnMenu')) closeSide(true); });
document.addEventListener('keydown', e => { if (e.key === 'Escape' && appShell.classList.contains('side-open') && $('#modal').hidden) closeSide(true); });
document.querySelectorAll('.side-btn').forEach(b => b.addEventListener('click', () => setTimeout(() => closeSide(true), 60)));   // setelah pilih menu, layar kembali lega
$('#btnTheme').onclick = () => setTheme(document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
(function () {
  let t = null; try { t = localStorage.getItem('quedrive_theme'); } catch (e) {}
  setTheme(t || (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));
})();

/* ---------- event ---------- */
$('#searchDriver').addEventListener('input', renderListDriver);
$('#searchAntri').addEventListener('input', renderListAntrian);
$('#tbDriver').addEventListener('click', e => { const b = e.target.closest('[data-act="add"]'); if (b) { if (b.dataset.warn) addAskNotAva(b.dataset.id); else addToAntrian(b.dataset.id); } });
$('#tbAntrian').addEventListener('click', e => { const o = e.target.closest('[data-or]'); if (o) { if (o.dataset.mode === 'manual') openManualOrder(o.dataset.or); else showShipments(o.dataset.or, o.dataset.mode); return; } const ea = e.target.closest('[data-act="edit-antri"]'); if (ea) { openAntriEdit(ea.dataset.key); return; } const mo = e.target.closest('[data-act="manual-ok"]'); if (mo) { openManualOrder(mo.dataset.key); return; } const sj = e.target.closest('[data-sj]'); if (sj) { showSJ(sj.dataset.sj); return; } const rq = e.target.closest('[data-act="requeue"]'); if (rq) { requeueAsk(rq.dataset.key); return; } const b = e.target.closest('[data-act="del"]'); if (b) removeFromAntrian(b.dataset.key || b.dataset.id); });
for (const [tb, fn] of [['#tbMD', () => renderMasterDriver()], ['#tbMU', () => renderMasterUnit()]]) { $(tb).addEventListener('change', e => { const t = e.target; if (t.dataset && t.dataset.note) saveNote(t.dataset.note, t.dataset.k, t.value); }); $(tb).addEventListener('input', e => { const t = e.target; if (t.dataset && t.dataset.note) t.title = t.value; }); }
$('#fltStatusDriver').addEventListener('change', e => { MF.driver = e.target.value; renderMasterDriver(); });
$('#searchMD').addEventListener('input', e => { MSEARCH.md = e.target.value; renderMasterDriver(); });
$('#searchMU').addEventListener('input', e => { MSEARCH.mu = e.target.value; renderMasterUnit(); });
$('#fltStatusUnit').addEventListener('change', e => { MF.unit = e.target.value; renderMasterUnit(); });
$('#tbAntrian').addEventListener('input', e => { const t = e.target; if (t.dataset && (t.dataset.f === 'depo' || t.dataset.f === 'keterangan')) t.title = t.value; });   // tulisan lengkap muncul saat kursor didekatkan
$('#tbAntrian').addEventListener('change', e => {
  const t = e.target; if (!t.dataset || !t.dataset.f) return;
  if (t.dataset.f === 'nopol_unit') handleUnitChange(t.dataset.key || t.dataset.id, t); else updateField(t.dataset.key || t.dataset.id, t.dataset.f, t.value);
});
$('#btnTplDriver').addEventListener('click', () => downloadTemplate('driver'));
$('#btnTplUnit').addEventListener('click', () => downloadTemplate('unit'));
$('#fileMD').addEventListener('change', e => handleMasterUpload(e, 'driver'));
$('#fileMU').addEventListener('change', e => handleMasterUpload(e, 'unit'));
$('#fileAny').addEventListener('change', e => { const f = [...e.target.files]; e.target.value = ''; ingestFiles(f); });
$('#fileSlot').addEventListener('change', e => { const f = [...e.target.files]; e.target.value = ''; ingestFiles(f, ['p2h', 'klinik', 'gps', 'att', 'mon', 'bd'].includes(slotTarget) ? slotTarget : null); });

/* =====================================================================
   ASUMSI & LOGIC
   ===================================================================== */
/* =====================================================================
   RAW DATA — data mentah dan hasil hitung yang dipakai di semua tab
   ===================================================================== */
const RAWSEL = { key: 'status', q: '', day: null, sort: null, ex: {}, rid: 0, loading: false };
const hhmm = m => pad(Math.floor(m / 60)) + ':' + pad(m % 60);
const fmtSer = s => (s == null || s === '' ? '' : fmtSerial(s));
function rawDefs() {
  const day = RAWSEL.day != null ? RAWSEL.day : todaySerial();
  const md = S.config.dotsMeta, sm = S.config.sjMeta, pm = S.config.p2hMeta, km = S.config.klinikMeta, gm = S.config.gpsMeta, bm = S.config.bdMeta;
  const nm = m => (m ? m.name : 'belum diupload');
  const actAt = () => S.masterDB.filter(r => isActive(r, day));
  return [
    { key: 'status', label: 'Status Driver (hasil hitung)', date: true, src: `dihitung aplikasi untuk tanggal ${fmtSerial(day)} dari Master Driver + absensi + DOTS + GPS Tracking + bd_export + P2H + Klinik + SJ; kolom Nopol Unit dan Ada di Antrian memakai antrian saat ini`,
      cols: ['ID Driver', 'Nama', 'File Absensi', 'Kode File Absensi', 'Status Absensi', 'Absen MTA', 'Alasan Status Absensi', 'Jumlah Shipment (DOTS)', 'Shipment GPS Terakhir', 'Status GPS', 'Nopol GPS', 'Status Antrian', 'Alasan Status Antrian', 'Status Klinik', 'Ada di Antrian', 'Nopol Unit', 'Sumber Nopol', 'Status P2H', 'Status FMS BD', 'Alasan Status FMS BD', 'Status Order', 'Aging SJ Outs.(hari)', 'Jumlah SJ Outs.', 'Kode Absensi (Dashboard)'],
      n: () => actAt().length,
      rows: () => {
        const inQ = new Map(antriAktif().map(a => [a.id, a])), sjm = sjByDriver(day);
        return actAt().map(d => {
          const sa = statusAntrianOf(d.id, day), ab = sa.ab, g = sa.gps, q = inQ.get(d.id), qn = q && String(q.nopol_unit || '').trim() ? q.nopol_unit : '';
          const np = qn || (g ? g.nopol : ''), src = qn ? 'Antrian' : (g && g.nopol ? 'GPS Tracking' : ''), ui = unitInfo(np, day);
          const sj = sjm.get(d.id) || [], ag = sj.map(r => r.aging).filter(x => x != null);
          return [d.id, d.nama, ab.file || '', ab.raw || 'N/A', ab.status, absenMtaOf(ab, day) || 'N/A', ab.why, shipCount(d.id, day), g ? g.ship : '', g ? g.status.toLowerCase() : '', g ? g.nopol : '', sa.status, sa.why, klinikOf(d.id, day) || 'N/A', q ? 'Ya' : 'Tidak',
            np, src, np ? (p2hOf(np, day) || 'N/A') : '', ui.status, unitWhy(ui, np), shipCount(d.id, day) > 0 ? 'SUDAH' : 'BELUM', ag.length ? Math.max(...ag) : '', sj.length, ab.kode];
        });
      } }
  ];   // RAW Data hanya menampilkan hasil hitung per driver aktif (Master Driver)
}
// ---- urut & filter kolom (klik judul = urut; tombol ▾ = filter berjenjang seperti Excel; "Reset tabel" mengosongkan keduanya) ----
const RAW_MON = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
function rawKey(v) {
  if (typeof v === 'number') return [0, v];
  const s = String(v == null ? '' : v).trim();
  if (s === '' || s === 'N/A' || s === '-') return [3, 0];   // kosong, N/A, dan "-" selalu di bawah
  if (/^-?\d+(\.\d+)?$/.test(s)) return [0, parseFloat(s)];
  const m = /^(\d{1,2})-([A-Za-z]{3})(?:-(\d{2,4}))?(?:\s*\|?\s*(\d{2}):(\d{2}))?$/.exec(s);
  if (m) { const mo = RAW_MON.indexOf(m[2].toLowerCase()); if (mo >= 0) { const y = m[3] ? (+m[3] < 100 ? 2000 + +m[3] : +m[3]) : new Date().getFullYear(); return [1, ((y * 12 + mo) * 31 + +m[1]) * 1440 + (m[4] ? +m[4] * 60 + +m[5] : 0)]; } }
  return [2, s.toLowerCase()];
}
function rawCmp(a, b, dir) {
  const ka = rawKey(a), kb = rawKey(b);
  if (ka[0] === 3 || kb[0] === 3) return ka[0] === kb[0] ? 0 : (ka[0] === 3 ? 1 : -1);   // sel kosong selalu di bawah
  if (ka[0] !== kb[0]) return (ka[0] - kb[0]) * dir;
  return (typeof ka[1] === 'string' ? ka[1].localeCompare(kb[1], 'id', { numeric: true }) : ka[1] - kb[1]) * dir;
}
// Kolom Absen MTA dan Antri difilter per TANGGAL saja (jam tidak ikut), supaya daftar centang tidak terlalu banyak; urut tetap memakai nilai lengkap
const dateOnly = v => { const s = String(v), m = /^(.*?)\s*\|\s*\d{1,2}:\d{2}(?::\d{2})?$/.exec(s); return m ? m[1].trim() : s; };
const isDateCol = n => n === 'Absen MTA' || n === 'Antri';
let RAWFK = new Set();   // indeks kolom (pada tabel yang sedang difilter) yang difilter per tanggal
const setFK = names => { RAWFK = new Set(names.map((n, i) => (isDateCol(n) ? i : -1)).filter(i => i >= 0)); };
const fv = (i, v) => (RAWFK.has(+i) ? dateOnly(v) : String(v));
function rawPass(r, q, ex, skip) {   // skip = kolom yang tidak dicek (daftar nilai berjenjang tidak dipersempit oleh filternya sendiri)
  if (q && !r.some(c => String(c).toLowerCase().includes(q))) return false;
  for (const k in ex) { if (+k === skip) continue; const s = ex[k]; if (s.size && s.has(fv(k, r[k]))) return false; }
  return true;
}
function rawView(cur) {
  setFK(cur.cols);
  const all = cur.rows(), q = RAWSEL.q.trim().toLowerCase();
  let rows = all.filter(r => rawPass(r, q, RAWSEL.ex, -1));
  if (RAWSEL.sort) { const { c, dir } = RAWSEL.sort; rows = rows.map((r, i) => [r, i]).sort((x, y) => rawCmp(x[0][c], y[0][c], dir) || x[1] - y[1]).map(x => x[0]); }
  return { all, rows, q };
}
const rawCur = () => { const defs = rawDefs(); return defs.find(d => d.key === RAWSEL.key) || defs[0]; };
const rawExActive = () => Object.values(RAWSEL.ex).some(s => s.size);
function closeRawPop() { const p = document.getElementById('rawPop'); if (p) p.remove(); }
function openColFilter(ctx, i, btn) {
  const had = document.getElementById('rawPop') && document.getElementById('rawPop').dataset.c === String(i); closeRawPop(); if (had) return;
  const all = ctx.rows, q = ctx.q || '';
  setFK(ctx.names);
  const base = all.filter(r => rawPass(r, q, ctx.st.ex, i)), counts = new Map();
  base.forEach(r => { const v = fv(i, r[i]); counts.set(v, (counts.get(v) || 0) + 1); });
  const vals = [...counts.keys()].sort((a, b) => rawCmp(a, b, 1));
  const pop = document.createElement('div'); pop.className = 'sh-pop fx'; pop.id = 'rawPop'; pop.dataset.c = i;
  pop.innerHTML = `<div class="sh-head">${esc(ctx.names[i])}</div><input type="search" class="sh-q" placeholder="Cari nilai..." aria-label="Cari nilai"><div class="sh-act"><button type="button" data-a="all">Pilih semua</button><button type="button" data-a="none">Kosongkan</button></div><div class="msel-list"></div><div class="sh-note"></div>`;
  document.body.appendChild(pop);
  const list = pop.querySelector('.msel-list'), note = pop.querySelector('.sh-note'), qi = pop.querySelector('.sh-q'); let shown = [];
  const draw = () => {
    const qq = qi.value.trim().toLowerCase(); shown = vals.filter(v => !qq || v.toLowerCase().includes(qq));
    const ex = ctx.st.ex[i] || new Set();
    list.innerHTML = shown.slice(0, 400).map(v => `<label class="msel-row"><input type="checkbox" data-v="${esc(v)}"${ex.has(v) ? '' : ' checked'}><span>${v === '' ? '(kosong)' : esc(v)}</span><span class="ct">${counts.get(v)}</span></label>`).join('') || '<div class="sh-empty">Tidak ada nilai</div>';
    note.textContent = shown.length > 400 ? `Menampilkan 400 dari ${shown.length} nilai; persempit lewat kolom cari.` : `${shown.length} nilai`;
  };
  draw(); qi.oninput = draw;
  const touch = () => ctx.touch();
  list.onchange = e => { const cb = e.target; if (!cb.matches('input[type=checkbox]')) return; const v = cb.dataset.v, s = ctx.st.ex[i] || (ctx.st.ex[i] = new Set()); if (cb.checked) s.delete(v); else s.add(v); if (!s.size) delete ctx.st.ex[i]; touch(); };
  pop.querySelector('.sh-act').onclick = e => { const a = e.target.dataset.a; if (!a) return; const s = ctx.st.ex[i] || (ctx.st.ex[i] = new Set()); shown.forEach(v => { if (a === 'all') s.delete(v); else s.add(v); }); if (!s.size) delete ctx.st.ex[i]; touch(); draw(); };
  const r = btn.getBoundingClientRect(), W = 270;
  pop.style.width = W + 'px'; pop.style.left = Math.max(8, Math.min(r.left, window.innerWidth - W - 8)) + 'px'; pop.style.top = (r.bottom + 4) + 'px'; pop.style.maxHeight = Math.max(240, window.innerHeight - r.bottom - 16) + 'px';
  qi.focus();
}
function openRawFilter(i, btn) { const cur = rawCur(); openColFilter({ names: cur.cols, rows: cur.rows(), st: RAWSEL, q: RAWSEL.q.trim().toLowerCase(), touch: () => { renderRawTable(); } }, i, btn); }
// ---- urut & filter kolom GENERIK (Master Driver, Master Unit, History): klik judul = urut, tombol ▾ = filter berjenjang, "Reset urut & filter" ----
const SFS = {}, SFREG = {};
const sfState = id => SFS[id] || (SFS[id] = { sort: null, ex: {} });
const sfActive = id => { const s = sfState(id); return !!s.sort || Object.values(s.ex).some(x => x.size); };
const sfMatrix = (items, cols) => items.map(it => cols.map(c => c.get(it)));
function sfApply(id, items, cols) {
  setFK(cols.map(c => c.label));
  const st = sfState(id), vals = sfMatrix(items, cols);
  const idx = items.map((_, k) => k).filter(k => rawPass(vals[k], '', st.ex, -1));
  if (st.sort) { const { c, dir } = st.sort; idx.sort((a, b) => rawCmp(vals[a][c], vals[b][c], dir) || a - b); }
  return idx.map(k => items[k]);
}
function sfTh(id, i, label, cls) {
  const st = sfState(id), s = st.sort && st.sort.c === i, f = st.ex[i] && st.ex[i].size;
  return `<th class="rawh${cls ? ' ' + cls : ''}"><span class="sh-lbl" data-sfs="${id}:${i}" title="Klik untuk mengurutkan">${esc(label)}<span class="sh-arrow${s ? '' : ' idle'}">${s ? (st.sort.dir > 0 ? '▲' : '▼') : '↕'}</span></span><button type="button" class="sh-f${f ? ' on' : ''}" data-sff="${id}:${i}" title="Filter kolom ${esc(label)}">▾</button></th>`;
}
const sfHead = (id, cols) => `<thead><tr><th>No</th>${cols.map((c, i) => sfTh(id, i, c.label, c.cls)).join('')}</tr></thead>`;
const sfResetBtn = id => (sfActive(id) ? `<button class="btn" type="button" data-sfreset="${id}" title="Hapus urutan dan semua filter kolom tabel ini">Reset urut &amp; filter</button>` : '');
document.addEventListener('click', e => {
  const s = e.target.closest('[data-sfs]'), f = e.target.closest('[data-sff]'), r = e.target.closest('[data-sfreset]');
  if (s) { const [id, c] = s.dataset.sfs.split(':'), i = +c, st = sfState(id); st.sort = st.sort && st.sort.c === i ? { c: i, dir: -st.sort.dir } : { c: i, dir: 1 }; closeRawPop(); if (SFREG[id]) SFREG[id].render(); }
  else if (f) { const [id, c] = f.dataset.sff.split(':'), reg = SFREG[id]; if (reg) openColFilter({ names: reg.names, rows: reg.matrix(), st: sfState(id), q: '', touch: () => reg.render() }, +c, f); }
  else if (r) { const id = r.dataset.sfreset; SFS[id] = { sort: null, ex: {} }; if (id === 'md' || id === 'mu') { MSEARCH[id] = ''; const si = $('#search' + id.toUpperCase()); if (si) si.value = ''; } if (id === 'la') { $('#searchAntri').value = ''; } closeRawPop(); if (SFREG[id]) SFREG[id].render(); }
});
document.addEventListener('click', e => { if (document.getElementById('rawPop') && !e.target.closest('#rawPop') && !e.target.closest('.sh-f')) closeRawPop(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeRawPop(); });

function renderRaw() {
  closeRawPop();
  const el = $('#v-raw'), defs = rawDefs(), cur = defs.find(d => d.key === RAWSEL.key) || defs[0];
  RAWSEL.key = cur.key;
  el.innerHTML = `<div class="page-h"><h2>RAW Data</h2><p>Hasil hitung per driver aktif (sesuai Master Driver) · klik judul kolom untuk mengurutkan, tombol ▾ untuk memfilter kolom</p></div>
  <div class="raw-bar">
    ${cur.date ? `<label>Tanggal <input type="date" id="rawDay" value="${dateKey(RAWSEL.day != null ? RAWSEL.day : todaySerial())}"></label>` : ''}
    <input type="search" id="rawQ" placeholder="Cari di tabel..." value="${esc(RAWSEL.q)}" aria-label="Cari di tabel">
    <button class="btn" id="rawReset" type="button" hidden title="Hapus semua filter kolom dan urutan">Reset tabel</button>
    <button class="btn primary" id="rawDl" type="button">${IC_DL}Download Excel</button></div>
  <div class="note" id="rawInfo"></div>
  <div class="panel"><div class="tbl-wrap" style="max-height:70vh" id="rawTable"></div></div>`;
  if ($('#rawDay')) { $('#rawDay').onchange = e => { RAWSEL.day = e.target.value ? serialOfKey(e.target.value) : null; renderRaw(); }; }
  $('#rawQ').oninput = e => { RAWSEL.q = e.target.value; renderRawTable(); };
  $('#rawReset').onclick = () => { RAWSEL.sort = null; RAWSEL.ex = {}; closeRawPop(); renderRawTable(); };
  $('#rawDl').onclick = () => downloadRaw();
  $('#rawTable').addEventListener('scroll', closeRawPop);
  renderRawTable();
}
const RAW_CHUNK = 2000;   // baris pertama langsung tampil; sisanya ditambahkan bertahap agar layar tidak membeku pada dataset puluhan ribu baris
function renderRawTable() {
  const cur = rawCur(), { all, rows, q } = rawView(cur), exAct = rawExActive(), viewed = !!q || exAct || !!RAWSEL.sort;
  const cnt = `${rows.length}${rows.length !== all.length ? ' dari ' + all.length : ''} baris`;
  $('#rawInfo').textContent = `Sumber: ${cur.src} · ${cnt}`;
  $('#rawReset').hidden = !(exAct || RAWSEL.sort);
  const th = cur.cols.map((c, i) => { const s = RAWSEL.sort && RAWSEL.sort.c === i, f = RAWSEL.ex[i] && RAWSEL.ex[i].size; return `<th class="rawh"><span class="sh-lbl" data-sort="${i}" title="Klik untuk mengurutkan">${esc(c)}<span class="sh-arrow${s ? '' : ' idle'}">${s ? (RAWSEL.sort.dir > 0 ? '▲' : '▼') : '↕'}</span></span><button type="button" class="sh-f${f ? ' on' : ''}" data-f="${i}" aria-label="Filter kolom ${esc(c)}" title="Filter kolom">▾</button></th>`; }).join('');
  const tr = (r, i) => `<tr><td class="num">${i + 1}</td>${r.map(c => `<td class="l">${esc(c)}</td>`).join('')}</tr>`;
  $('#rawTable').innerHTML = `<table class="t wrapall"><thead><tr><th>No</th>${th}</tr></thead><tbody id="rawBody">${rows.slice(0, RAW_CHUNK).map(tr).join('') || `<tr><td colspan="${cur.cols.length + 1}" class="empty-row">Tidak ada data</td></tr>`}</tbody></table>`;
  if (rows.length > RAW_CHUNK) {   // dataset besar: lebar kolom dikunci dari batch pertama supaya penambahan baris berikutnya cepat (tanpa hitung ulang lebar tiap baris)
    const tbl = $('#rawTable table'), ws = [...tbl.querySelectorAll('thead th')].map(t => Math.ceil(t.getBoundingClientRect().width));
    tbl.insertAdjacentHTML('afterbegin', '<colgroup>' + ws.map(w => `<col style="width:${w}px">`).join('') + '</colgroup>');
    tbl.style.tableLayout = 'fixed'; tbl.style.width = ws.reduce((x, y) => x + y, 0) + 'px';
  }
  const token = RAWSEL.rid = (RAWSEL.rid || 0) + 1; let k = RAW_CHUNK; RAWSEL.loading = rows.length > RAW_CHUNK;
  const more = () => {
    if (token !== RAWSEL.rid) return;
    const tb = document.getElementById('rawBody'); if (!tb) { RAWSEL.loading = false; return; }
    tb.insertAdjacentHTML('beforeend', rows.slice(k, k + RAW_CHUNK).map((r, i) => tr(r, k + i)).join('')); k += RAW_CHUNK;
    if (k < rows.length) { $('#rawInfo').textContent = `Sumber: ${cur.src} · ${cnt} · memuat ${Math.min(k, rows.length)} dari ${rows.length}...`; setTimeout(more, 0); }
    else { RAWSEL.loading = false; $('#rawInfo').textContent = `Sumber: ${cur.src} · ${cnt}`; }
  };
  if (RAWSEL.loading) { $('#rawInfo').textContent = `Sumber: ${cur.src} · ${cnt} · memuat ${RAW_CHUNK} dari ${rows.length}...`; setTimeout(more, 0); }
  $('#rawTable').querySelectorAll('[data-sort]').forEach(el => { el.onclick = () => { const c = +el.dataset.sort; RAWSEL.sort = RAWSEL.sort && RAWSEL.sort.c === c ? { c, dir: -RAWSEL.sort.dir } : { c, dir: 1 }; renderRawTable(); }; });
  $('#rawTable').querySelectorAll('[data-f]').forEach(b => { b.onclick = e => { e.stopPropagation(); openRawFilter(+b.dataset.f, b); }; });
}
function downloadRaw() {   // Download Excel: isi tabel RAW seperti yang tampil (urutan dan filter yang aktif ikut)
  const cur = rawCur(), wb = XLSX.utils.book_new(), ref = RAWSEL.day != null ? RAWSEL.day : todaySerial();
  const ws = XLSX.utils.aoa_to_sheet([cur.cols, ...xlsRows(rawView(cur).rows, ref)]); ws['!cols'] = cur.cols.map(() => ({ wch: 18 }));
  XLSX.utils.book_append_sheet(wb, ws, 'Status Driver');
  saveWb(wb, 'RAW Status Driver ' + dateKey(ref) + ' - Quedrive.xlsx');
}

function renderAsumsi() {
  const st = S.settings;
  $('#v-asumsi').innerHTML = `<div class="page-h"><h2>Asumsi</h2><p>QUEDRIVE – Queue Driver Availability &amp; Status Monitor. Aturan dan cara kerja aplikasi ini, per menu.</p></div><div class="asm">
  <div class="panel"><h3>Gambaran umum</h3><table>
  <tr><td>Tujuan</td><td>Menampilkan <b>antrian driver yang bisa diberi order</b>: siapa yang sudah absen dan tersedia (AVA), siapa yang sudah masuk antrian, dan apakah driver di antrian sudah mendapat order (SUDAH / BELUM). Kartu <b>Status All Driver</b> merangkum seluruh driver aktif.</td></tr>
  <tr><td>Menu</td><td><b>Dashboard Antrian</b> (kartu ringkasan, List Driver, List Antrian) · <b>SJ Outstanding</b> · <b>Master Driver</b> · <b>Master Unit</b> · <b>History Antrian</b> (riwayat per tanggal) · <b>RAW Data</b> (hasil hitung per driver) · <b>Asumsi</b> (halaman ini) · <b>Upload</b> (upload file dan master). Di menu kiri, History Antrian, RAW Data, Asumsi, dan Upload dikelompokkan dalam satu grup <b>Data &amp; Info</b>.</td></tr>
  <tr><td>Penyimpanan di server</td><td>Antrian, catatan Master, file DOTS, SJ, Attendance, Monitoring, P2H, Klinik, GPS, bd_export, master hasil upload, dan History tersimpan di server dan <b>dibagikan ke semua orang</b> yang membuka link aplikasi. Data tidak hilang saat browser ditutup. Yang bisa mengubah data: pemilik, editor, dan anggota dengan akses Contributor; akses hanya-lihat hanya bisa membaca. Jika dibuka di luar link yang dipublikasikan (mis. file HTML biasa), data hanya tersimpan di browser itu sendiri. Status penyimpanan tampil di kiri bawah menu.</td></tr>
  <tr><td>Tanggal aplikasi (development)</td><td>Chip tanggal-jam di kanan atas bisa diklik: <b>Saat Ini</b> (mengikuti tanggal komputer) atau <b>Tanggal tertentu</b>. Selama tanggal tertentu dipakai, <b>semua hitungan</b> (master aktif, Kode Absensi, Status Antrian, kartu, shipment DOTS, status GPS, aging SJ, masa berlaku 24 jam P2H dan Klinik, RAW Data, tampilan History) memakai tanggal itu, sedangkan jam tetap jam sebenarnya; chip berwarna oranye bertanda SIMULASI. Pilihan hanya berlaku di browser ini. Pengaman: pergantian tanggal otomatis dan penyimpanan History dimatikan selama tanggal tertentu dipakai. Perubahan antrian tetap tersimpan ke data bersama.</td></tr>
  <tr><td>Format tanggal</td><td>Di layar: <b>dd-Mmm-yy</b> (06-Oct-26); kolom Antri dan Absen MTA memakai <b>dd-Mmm | HH:MM</b> (atau dd-Mmm bila tanpa jam). Chip kanan atas: Ddd, dd-Mmm-yy | hh:mm. Di file Excel hasil download, tanggal dan jam <b>tanpa tanda |</b>: tanggal Excel biasa berformat <b>dd-mmm-yy hh:mm</b> (yang hanya tanggal: dd-mmm-yy), sehingga bisa diurut dan difilter sebagai tanggal; tahun diambil dari tanggal data (History) atau tanggal aplikasi (RAW).</td></tr>
  <tr><td>Tampilan</td><td>Menu kiri disembunyikan otomatis: muncul saat mouse didekatkan ke tepi kiri (atau lewat tombol ☰) dan menutup lagi setelah memilih menu; lebarnya diatur supaya tidak ada scroll kiri-kanan. Tombol Day/Night di dasar menu (tema tersimpan di browser). Logo Puninar di kanan atas header, merek QUEDRIVE di kiri atas menu. Semua header tabel rata tengah dan tidak terpotong; kolom Absen MTA dan Antri paling banyak dua baris ("06-Oct |" lalu "15:57"). List Driver dan List Antrian berdampingan di layar lebar dan bertumpuk di layar sempit (900 px ke bawah).</td></tr>
  <tr><td>Nama driver</td><td>Nama driver <b>selalu ditulis huruf kapital semua</b> di seluruh aplikasi (mis. EDI, bukan Edi), apa pun penulisannya di file Master atau di data lama.</td></tr>
  <tr><td>Tanda N/A</td><td>Data yang <b>memang tidak ada</b> ditulis <b>N/A</b> (bukan nol atau kosong): driver belum absen, belum periksa klinik, unitnya belum P2H (atau sudah lewat 24 jam), atau file sumbernya belum diupload. Arahkan mouse ke tanda N/A untuk melihat penyebabnya; hubungi driver bila belum melakukannya, atau tim IT bila datanya seharusnya sudah ada. Pengecualian: Status FMS BD tidak N/A untuk unit yang tidak ada di data breakdown (berarti Ready); nopol yang belum diisi tetap kosong.</td></tr>
</table></div>
  <div class="panel"><h3>Dashboard Antrian: kartu ringkasan</h3><table>
  <tr><td>Status All Driver</td><td>Judul kartu: <b>Status All Driver dari n driver aktif</b>. Setiap driver aktif (Master Driver pada tanggal aplikasi) masuk ke <b>tepat satu</b> dari tujuh kelompok, sehingga jumlahnya sama dengan total driver aktif (bila tidak sama muncul peringatan merah). Urutan penentuan: (1) Kode Absensi N/A → <b>N/A</b>; (2) kode A, I, S, atau O → <b>AIS + OFF</b>; (3) driver yang sudah di List Antrian (putaran yang masih berjalan) mengikuti Status Order-nya: SUDAH → <b>Antri &amp; sudah diorder</b>, BELUM → <b>Sudah antri &amp; belum diorder</b>; (4) driver yang punya shipment hari ini (belum di antrian) → <b>Menjalankan order &amp; sudah selesai</b> bila semua shipment-nya closed / forceclosed menurut GPS Tracking, selain itu <b>Menjalankan order &amp; belum selesai</b> (shipment yang tidak ada di file GPS dianggap belum selesai); (5) kode OR tanpa shipment terbaca di DOTS → Menjalankan order &amp; belum selesai; (6) sisanya (kode X, belum antri, belum punya shipment) → <b>Absen X &amp; belum antri</b>. Arahkan mouse ke tiap kelompok untuk melihat definisinya.</td></tr>
  <tr><td>Persentase</td><td>Dua kelompok antrian (Sudah antri &amp; belum diorder dan Antri &amp; sudah diorder) menulis <b>"% dari antrian"</b>, yaitu terhadap jumlah driver di List Antrian (putaran yang masih berjalan). Kelompok lain menulis <b>"% dari total"</b>, yaitu terhadap total driver aktif.</td></tr>
  <tr><td>Rincian 20 FT / 40 FT / N/A</td><td>Empat kelompok (Sudah antri &amp; belum diorder, Antri &amp; sudah diorder, Menjalankan order &amp; belum selesai, Menjalankan order &amp; sudah selesai) menampilkan di kanan angkanya rincian <b>20 FT</b>, <b>40 FT</b> (dan <b>Lainnya</b> bila ada), serta <b>N/A</b>, ditulis ke bawah dan angkanya rata dalam satu kolom. Untuk driver di antrian: dari Nopol Unit dan Tipe Unit pada baris antrian (nopol belum diisi = N/A). Untuk driver yang menjalankan order: dari TIPE TRUCK shipment dengan tender terbaru hari itu (aturan sama dengan kartu Driver tender hari ini); driver tanpa shipment di DOTS (mis. berkode OR dari hari sebelumnya) = N/A. Jadi 20 FT + 40 FT + Lainnya + N/A = angka kelompok. Arahkan mouse ke "N/A" atau "Lainnya" untuk penjelasan. Angka utama semua kelompok sejajar dalam satu baris.</td></tr>
  <tr><td>Total driver OR hari ini</td><td>Kartu paling atas di kanan. Totalnya = <b>Absen OR + Tender hari ini</b>, satu driver dihitung satu kali. <b>Tender hari ini</b> = semua driver aktif yang punya shipment pada tanggal aplikasi, apa pun Kode Absensinya (termasuk yang berkode OR dan sudah di-tender). <b>Absen OR</b> = driver berkode absen OR yang <b>belum</b> punya shipment pada tanggal itu; bila driver berkode OR sudah di-tender, ia pindah dari Absen OR ke Tender hari ini. Jadi Tender hari ini selalu sama dengan angka kartu Driver tender hari ini. Tanggal shipment = yang terbesar antara Execution Date dan Tanggal Tender.</td></tr>
  <tr><td>Driver tender hari ini</td><td>Jumlah driver aktif di Master (seluruhnya, bukan hanya yang di antrian) yang punya ≥ 1 shipment pada tanggal aplikasi (tanggal = terbesar antara Execution Date dan Tanggal Tender). Semua tipe shipment (SPK maupun SPO) dan semua status FPT selain CANCEL ikut dihitung. Dirinci 20 FT / 40 FT / Lainnya: tiap driver dihitung satu kali menurut TIPE TRUCK shipment dengan tender terbaru hari itu. Sumber: DOTS Report dicocokkan dengan Master Driver.</td></tr>
  <tr><td>SPK sudah tender</td><td><b>SPK</b> = shipment dengan TIPE SHIPMENT SPK (armada sendiri). Hanya status FPT <b>NEW</b> dan <b>CARRY OVER</b> yang dihitung (CANCEL dan REPLAN tidak), per shipment unik pada tanggal onroad hari ini, tanpa dibatasi driver Master. Rincian dari TIPE TRUCK: 20 FT (mengandung 20), 40 FT (mengandung 40), Lainnya (selain itu atau memuat 20 dan 40 sekaligus; arahkan mouse ke angkanya untuk nilai aslinya). Angka SPK dihitung per shipment, sedangkan Status Order dihitung per driver.</td></tr>
  <tr><td>Kode Absensi Semua Driver</td><td>Jumlah driver aktif beserta sebaran Kode Absensi-nya (chip X, OR, OFF = kode O, AIS, N/A) dalam satu baris; jumlah chip selalu sama dengan total. Angka OR di sini adalah <b>kode OR dari file Attendance</b>, sedangkan kartu Tender hari ini memakai shipment DOTS, sehingga keduanya bisa berbeda.</td></tr>
  <tr><td>Penjelasan &amp; susunan</td><td>Keempat kartu di kanan kelompok N/A berjejer ke bawah (Total driver OR hari ini, Driver tender hari ini, SPK sudah tender, Kode Absensi Semua Driver), angka utamanya sejajar dalam satu kolom, dan masing-masing punya penjelasan saat kursor didekatkan. Di layar sempit (di bawah 1280 px) keempatnya pindah ke bawah tujuh kelompok. Cakupan hitung: hanya driver yang ada di Master Driver; kartu DOTS bernilai 0 sebelum DOTS diupload, dan upload DOTS baru menimpa yang lama.</td></tr>
</table></div>
  <div class="panel"><h3>Dashboard Antrian: List Driver</h3><table>
  <tr><td>Isi</td><td>Driver aktif yang <b>belum</b> di antrian. Kolom: No, ID Driver, Nama, Kode Absensi, Absen MTA, Status Antrian, Aksi. Kolom ID Driver dipenggal setelah tanda strip (DM- lalu 00164) supaya kolom Nama lebih lebar; di History tabelnya lebar sehingga ID tidak dipenggal. Driver yang sudah masuk antrian tidak tampil di sini; bila barisnya dihapus dari antrian, driver kembali tampil sesuai Kode Absensi dan Absen MTA-nya. Di kanan judul ada lencana biru jumlah driver, dan di samping judul tertulis jumlah <b>AVA</b> dan <b>NOT AVA</b> (semua driver yang belum di antrian, tidak terpengaruh kotak cari).</td></tr>
  <tr><td>Urutan</td><td>Dari atas: kode <b>X</b>; lalu kode <b>OR yang sudah AVA</b> (shipment terakhirnya closed di GPS Tracking), diurutkan menurut <b>jam closed</b> (yang paling dulu closed di atas); lalu OR lainnya (NOT AVA); lalu AIS, O, dan N/A paling bawah. Untuk X dan OR lainnya, absen tertua (jam Absen MTA paling awal) di atas; yang tanpa jam di bawah yang berjam, lalu menurut ID Driver.</td></tr>
  <tr><td>Tombol masuk antrian</td><td>Driver <b>AVA</b>: tombol <b>+ hijau</b> langsung memasukkan ke antrian. Driver berkode <b>OR yang belum AVA</b> (shipment terakhir belum closed / forceclosed): tombol <b>+ abu-abu putus-putus</b>; popup "Apakah yakin masuk antrian?" menyebut driver dan setiap shipment yang belum selesai (No. Shipment, Customer, Rute, status GPS; dari DOTS hari ini yang belum closed ditambah shipment terakhir menurut GPS), lalu Ya, masuk antrian atau Batal. Driver <b>belum absen (Kode Absensi N/A)</b> juga bertombol + abu-abu: popup "Driver ... belum absen (Kode Absensi N/A). Apakah yakin masuk antrian? Tim Driver Management bisa mengingatkan driver untuk absen terlebih dahulu." Driver berkode <b>AIS atau O</b> tidak bisa dimasukkan (ikon larangan).</td></tr>
  <tr><td>Cari, reset, keterangan</td><td>Kotak cari memfilter menurut <b>ID, Nama, atau Catatan</b> (catatan driver tampil kecil dalam kurung di bawah Nama). Tombol kecil <b>Reset Filter</b> di kanan judul baru muncul bila ada urutan, filter kolom, atau pencarian aktif. Penjelasan singkat tabel hanya muncul saat kursor didekatkan ke judul (tanda "i").</td></tr>
</table></div>
  <div class="panel"><h3>Dashboard Antrian: List Antrian</h3><table>
  <tr><td>Kolom</td><td>No (nomor antrian) · ID Driver · Nama · Status Klinik · Nopol Unit · Tipe Unit · Status P2H · Status FMS BD · Absen MTA · Antri · Status Order · Aging SJ Outs.(hari) · Depo · Keterangan · Putaran · Aksi. Urutan kolom yang sama dipakai di History (tanpa Aksi). Tipe Unit <b>20 FT</b> ditulis dalam kotak hijau. Di bawah Nama tampil <b>Catatan</b> driver dan di bawah Nopol Unit tampil <b>Catatan</b> unit, kecil dalam kurung (lihat Master Driver &amp; Master Unit).</td></tr>
  <tr><td>Urutan antrian &amp; jam antri</td><td>Urutan mengikuti <b>jam antri</b>: yang paling awal di atas (nomor 1). Jam antri tercatat saat tombol + ditekan. Bila lupa klik +, jam antri bisa <b>diubah</b> lewat ikon pensil di sel Antri (popup tanggal-jam; tidak boleh lebih dari jam sekarang): baris otomatis naik atau turun, diberi tulisan "diedit", dan Status Order dihitung ulang terhadap jam baru.</td></tr>
  <tr><td>Isian per baris</td><td><b>Depo</b> dan <b>Keterangan</b> diisi manual (isi yang terpotong tampil lengkap saat kursor didekatkan). <b>Nopol Unit</b> dipilih / diketik dari Master Unit aktif; <b>Tipe Unit</b> terisi otomatis bila nopol sama persis (huruf besar / kecil tidak dibedakan), kosong bila tidak ditemukan. Satu nopol hanya boleh dipakai satu driver pada satu tanggal: nopol yang sudah dipakai baris lain di tanggal yang sama <b>ditolak</b> dengan pesan nama pemakainya dan tidak muncul di daftar pilihan; baris lama yang terlanjur ganda diberi bingkai merah. Pembandingan tidak membedakan huruf besar / kecil, spasi, atau strip; nopol yang sama di tanggal berbeda boleh.</td></tr>
  <tr><td>Status Order</td><td><b>Satu definisi</b> untuk kolom ini, kartu Status All Driver, History, dan RAW. Jam tender shipment (TANGGAL TENDER dan jam tender di DOTS, pada tanggal onroad hari ini) dibandingkan dengan jam antri baris itu: <b>SUDAH</b> (hijau, beserta jumlah shipment) bila ada shipment yang di-tender <b>sesudah</b> (atau tepat pada) jam antri, tanpa batas jam; <b>BELUM</b> (abu-abu) bila tidak ada. Shipment yang di-tender <b>sebelum</b> jam antri dianggap order lama dan <b>tidak dihitung</b>. Baris SUDAH berlatar <b>hijau</b> (juga di History). Driver tetap di antrian walau sudah diorder.</td></tr>
  <tr><td>Detail SUDAH</td><td>Klik pil <b>SUDAH</b>: tabel shipment hari itu dengan kolom Customer, No. Order (ORDER BASE; bila kosong NOMOR ORDER), No. Shipment, Rute, Tender, <b>Terhadap jam antri</b> (X j Y m sesudah antri = dihitung, sebelum antri = tidak dihitung), <b>Status Shipment</b> (status di GPS Tracking: closed, forceclosed, onprogress, ready, atau N/A bila tidak ada di file) dan <b>Jam Status</b> (ATA / ATD terbaru; untuk shipment closed = jam closed).</td></tr>
  <tr><td>Detail BELUM</td><td>Pil <b>BELUM</b> juga bisa diklik: tabel yang sama untuk <b>1 order terakhir dalam 7 hari terakhir</b> (menurut jam tender terbaru; semua shipment pada nomor order yang sama), lengkap dengan Terhadap jam antri, Status Shipment, dan Jam Status. Bila tidak ada order dalam 7 hari, popup menyebutnya.</td></tr>
  <tr><td>Centang manual SUDAH</td><td>Di samping pil BELUM ada tombol <b>centang</b>. Klik membuka popup <b>Tandai SUDAH manual</b> dengan kolom <b>Remarks</b> (boleh kosong), dipakai bila driver sudah jalan tetapi ordernya tidak tercatat di DOTS (mis. jalan tanpa SPK yang ditender). Status Order baris itu menjadi SUDAH (pil hijau bertulisan "manual"; tooltip memuat remarks), ikut dihitung di kartu, History ("SUDAH (manual)" beserta remarks), dan tampil di RAW. Klik pil itu untuk mengubah remarks atau <b>Hapus tanda manual</b>. Bila kemudian ada shipment sesudah jam antri di DOTS, hitungan otomatis yang dipakai.</td></tr>
  <tr><td>Antri lagi (putaran)</td><td>Di kolom Aksi baris <b>SUDAH</b> ada tombol <b>Antri lagi</b>: hijau bila semua shipment putaran itu sudah closed / forceclosed (langsung jalan), abu-abu putus-putus bila masih ada yang belum closed (tetap bisa diklik; popup "Apakah yakin antri lagi?" menyebut shipment yang belum selesai). Baris <b>putaran pertama tetap terlihat</b> (diredupkan, "Ke-1 selesai", isian dikunci, status order dibekukan) dan baris <b>putaran ke-2</b> ditambahkan di bawah dengan jam antri sekarang (Nopol, Tipe Unit, Depo disalin; Keterangan dikosongkan). Shipment yang sudah ada saat antri lagi tidak dihitung lagi untuk putaran baru. Putaran lama tidak dihitung di lencana jumlah driver, Status All Driver, dan angka AVA; driver yang antri lebih dari sekali ditulis di samping judul ("n driver yang sudah 2x antri"; yang sudah 3x ditulis terpisah). Nopol putaran lama tidak memblokir driver lain. Bila baris putaran berikutnya dihapus dan driver tidak punya baris yang berjalan, putaran lama <b>kembali berjalan</b> (tidak lagi diredupkan) dan Status Order-nya dihitung ulang. Pada putaran lama, jumlah shipment di pil adalah nilai yang dibekukan saat antri lagi, sedangkan popup memakai data DOTS terbaru (diberi keterangan).</td></tr>
  <tr><td>Aging SJ Outs.(hari)</td><td>Aging SJ outstanding <b>terlama</b> milik driver itu (pool, sumber, dan batas merah sama dengan tab SJ Outstanding; merah bila ≥ ${st.agingRed} hari); bila lebih dari satu SJ tampil jumlahnya di bawah angka. <b>Klik angka</b> untuk daftar SJ-nya. Tampil "-" bila tidak ada SJ outstanding dan <b>N/A</b> bila file SJ belum diupload.</td></tr>
  <tr><td>Hapus, ganti tanggal</td><td>Ikon tempat sampah menghapus satu baris (satu putaran); tidak ada lagi tombol Reset Antrian, dan riwayatnya tetap di History. Saat tanggal berganti, aplikasi menyimpan History hari sebelumnya; baris antrian yang ada <b>tetap di antrian</b> (driver berkode OR juga tetap), Status Order dihitung ulang untuk tanggal baru, dan Kode Absensi serta Absen MTA mengikuti file untuk tanggal baru.</td></tr>
  <tr><td>Cari, filter, reset</td><td>Kotak cari menurut <b>ID, Nama, atau Catatan</b> (catatan driver maupun catatan unit). Filter kolom lewat tombol <b>▾</b> di judul tiap kolom (mis. Tipe Unit; Status Order: SUDAH, SUDAH (manual), BELUM); filter <b>Absen MTA dan Antri hanya sampai tanggal</b> (jam tidak ikut), sedangkan urut ↕ tetap memakai tanggal dan jam lengkap. Tulisan "Filter aktif: n dari m driver" muncul saat tersaring; nomor urut tetap nomor antrian asli dan kartu tetap menghitung seluruh antrian. Tombol <b>Reset Filter</b> mengosongkan urutan, filter, dan kotak cari. Lencana biru di kanan judul = jumlah driver di antrian (bila tersaring: "n dari m Driver").</td></tr>
</table></div>
  <div class="panel"><h3>Absensi, Status Antrian &amp; GPS</h3><table>
  <tr><td>Sumber Kode Absensi</td><td><b>Kode Absensi</b> dibaca dari file <b>Attendance</b> (baris = driver, kolom = tanggal, satu file = satu periode, isi sel X, OR, O, A, I, S, atau ADJ). Bila periode melewati dua file, tanggal dicari di file yang memuatnya (bila dua file memuat tanggal yang sama dipakai yang terakhir diupload). ID dicocokkan tanpa membedakan huruf besar / kecil, spasi, dan strip. File <b>Monitoring Attendance</b> <b>tidak</b> menentukan kode; hanya memberi jam.</td></tr>
  <tr><td>Hasil baca kode</td><td>X → <b>X</b> (masuk, absen di pool) · OR → <b>OR</b> (on road) · O → <b>O / OFF</b> · A (Alpha), I (Izin), S (Sakit) → <b>AIS</b> (digabung) · ADJ → OR atau X (lihat ADJ). Sel kosong, driver tidak ada di file, atau tanggal di luar file → <b>N/A</b>. Kode tampil di List Driver, Master Driver, History (List Driver), dan RAW Data (kolom Kode Absensi (Dashboard); kolom Kode File Absensi menampilkan kode asli di file).</td></tr>
  <tr><td>ADJ (adjustment)</td><td>Driver kadang lupa absen padahal masih jalan. Kode ADJ dianggap <b>OR</b> bila driver punya shipment valid (Driver 1 / 2) dengan tanggal onroad H-3 s/d H dari tanggal ADJ; bila tidak ada, dianggap <b>X</b>.</td></tr>
  <tr><td>Jam &amp; Absen MTA</td><td>Jam dibaca dari <b>Monitoring Attendance</b> (Driver ID, Attendance Date, Time, Attendance Type) hanya untuk kode X dan OR: X memakai jam catatan <b>Masuk</b> terbaru pada tanggal itu (bila tak ada, jam Dalam Perjalanan), OR memakai jam <b>Dalam Perjalanan</b> terbaru. Tipe Izin, Sakit, Standby, Keluar, dan Approval Status tidak dipakai. <b>Absen MTA</b>: X dan OR tampil dd-Mmm | HH:MM (N/A bila jamnya tidak ada di Monitoring); O dan AIS tampil "-"; N/A tampil N/A. Nilainya ikut tersimpan saat driver dimasukkan ke antrian.</td></tr>
  <tr><td>Status Absensi (RAW)</td><td>Kode Absensi setelah aturan: kode X yang punya shipment valid pada tanggal onroad itu menjadi OR; kode lain tetap. Hanya informasi di RAW Data dan <b>tidak</b> memengaruhi Status Antrian (yang ditentukan oleh Kode Absensi).</td></tr>
  <tr><td>Status Antrian (AVA / NOT AVA)</td><td>Kode <b>X</b> = <b>AVA</b> (wajib, walau sudah punya shipment dan shipment terakhirnya belum closed: bisa diberi order baru). Kode <b>OR</b> menjadi AVA <b>hanya bila shipment terakhirnya di GPS Tracking Report closed atau forceclosed</b>. Kode AIS, O, dan N/A = NOT AVA. Alasan tiap driver terlihat di tooltip pil dan di RAW Data (Alasan Status Antrian). Driver yang sudah di antrian tidak otomatis dikeluarkan bila statusnya berubah.</td></tr>
  <tr><td>Shipment terakhir (GPS Tracking)</td><td>File GPS Tracking memuat banyak shipment per driver (status closed, cancel, onprogress, ready, forceclosed). Shipment terakhir = <b>Shipment Date terbaru</b> pada atau sebelum tanggal yang dicek (jam PTD stop pertama sebagai pembeda); shipment <b>cancel dilewati</b>. Pencocokan lewat Driver Number = ID Driver. <b>Jam closed</b> = ATA atau ATD terbaru dari semua stop; dipakai mengurutkan OR yang AVA dan tampil di tooltip Status Antrian. Selama file GPS belum diupload, pengecualian OR tidak berlaku.</td></tr>
  <tr><td>Reset tiap ganti hari</td><td>Absensi berlaku per tanggal dan reset pukul 00.00: begitu hari berganti, aplikasi menghitung ulang untuk tanggal baru (layar disegarkan otomatis). Driver yang belum punya kode di file Attendance pada tanggal itu berkode N/A. Hasil kemarin tetap tersimpan di History.</td></tr>
  <tr><td>Cakupan tanggal</td><td>Bila tanggal hari ini tidak ada di file Attendance, semua driver N/A dan NOT AVA dan muncul peringatan di atas List Driver. Bila ada di Attendance tetapi belum di Monitoring, Kode Absensi tetap tampil dan hanya jam Absen MTA (X dan OR) yang N/A; peringatan juga tampil. Di RAW Data bisa dipilih tanggal lain.</td></tr>
</table></div>
  <div class="panel"><h3>Status Klinik, Status P2H &amp; Status FMS BD</h3><table>
  <tr><td>Status Klinik</td><td>Status <b>driver</b>, dicocokkan dengan ID Driver. Sumber: file <b>Clinic checkup records</b> (No DM, Tanggal Pemeriksaan, Time Pemeriksaan, Status, Note). Dipakai pemeriksaan terbaru sampai saat ini (menurut Tanggal aplikasi). <b>Berlaku 24 jam</b> sejak waktu pemeriksaan (diperiksa tanggal 1 pukul 08.00 → berlaku sampai tanggal 2 pukul 07.59), sesudah itu <b>N/A</b> sampai ada pemeriksaan baru; driver tanpa data juga N/A. Status: <b>FIT</b> (hijau), <b>FOM</b> = Fit On Medication (oranye; tooltip memuat catatan obat), UNFIT / SAKIT merah. Format sederhana lama (ID Driver + Status Klinik) masih dikenali dan tanpa batas waktu. Dipakai di Dashboard, Master Driver, History, dan RAW.</td></tr>
  <tr><td>Status P2H</td><td>Status <b>unit</b>, mengikuti Nopol Unit baris. Kosong bila nopol belum diisi; <b>N/A</b> bila belum punya inspeksi atau inspeksinya sudah kedaluwarsa. Sumber: <b>P2H inspection records</b> (vehicle license number, status, created date, not ok items). Dipakai inspeksi terbaru sampai saat ini; <b>berlaku 24 jam</b> sejak waktu inspeksi (juga untuk BREAKDOWN dan PREVENTIVE), lalu N/A. Pencocokan nopol tidak membedakan huruf besar / kecil, spasi, strip, atau titik. Status: <b>READY</b> hijau, <b>BREAKDOWN</b> merah, <b>PREVENTIVE</b> oranye; tooltip memuat waktu inspeksi, batas berlaku, dan item tidak OK. Format sederhana (Nopol + Status P2H) juga dikenali.</td></tr>
  <tr><td>Status FMS BD (BD / Ready)</td><td>Dari file <b>bd_export</b> (service order bengkel FMS) menurut <b>Nopol</b>. Dilihat <b>SO Date terakhir</b> nopol itu: bila Handover Time-nya belum terisi (kosong atau "belum") → <b>BD</b>; selain itu <b>Ready</b>, termasuk nopol yang tidak ada di file. <b>N/A</b> bila file belum diupload; kosong bila nopol belum diisi. SO yang dihapus (SO Delete terisi) diabaikan. Mengikuti Tanggal aplikasi: SO sesudah tanggal itu diabaikan dan handover sesudahnya dianggap belum. Tampil di List Antrian, History, RAW, dan Master Unit. Berbeda dengan kolom <i>Status Unit</i> di Master Unit (Aktif / Nonaktif).</td></tr>
</table></div>
  <div class="panel"><h3>DOTS Report: shipment, tanggal onroad, SPK</h3><table>
  <tr><td>Shipment valid</td><td>Baris DOTS dengan TANGGAL TENDER terisi, STATUS FPT bukan CANCEL, dan punya ID DRIVER 1 dan / atau 2. Baris lain diabaikan. Shipment yang sama pada driver dan tanggal yang sama dihitung satu kali. REPLAN ikut dihitung untuk Driver tender hari ini dan Status Order; kartu SPK sudah tender lebih ketat (hanya NEW dan CARRY OVER).</td></tr>
  <tr><td>Tanggal onroad</td><td>Tanggal onroad = <b>tanggal terbesar antara Execution Date dan Tanggal Tender</b> (hanya tanggalnya). Contoh: tender 06-Oct, Execution Date 05-Oct → onroad 06-Oct; tender 01-Oct, Execution Date 02-Oct → onroad 02-Oct. Aturan cut-off tender sudah dihapus.</td></tr>
  <tr><td>Jumlah shipment</td><td>Jumlah shipment valid dengan driver sebagai ID DRIVER 1 atau 2 pada tanggal onroad tertentu. Driver dengan ≥ 1 shipment hari ini = <b>Tender hari ini</b>. Status Order di List Antrian memakai definisi sendiri terhadap jam antri (lihat Status Order). Di dataset RAW, kolom Status Order berarti SUDAH bila driver punya shipment hari ini (driver di RAW tidak punya jam antri).</td></tr>
</table></div>
  <div class="panel"><h3>SJ Outstanding</h3><table>
  <tr><td>Sumber &amp; isi</td><td>File <b>Download Detail SJ</b> (NID = ID driver, Customer, Tender Time, Shipment No, Pool, Aging); hanya baris berstatus <i>Belum Kembali</i>. Yang tampil hanya SJ milik <b>driver aktif di Master Driver</b> pada <b>POOL NAGRAK TRAILER</b> (tetap; tidak ada filter pool); nama driver dari Master.</td></tr>
  <tr><td>Kolom &amp; urutan</td><td>No, ID Driver, Nama Driver, Customer, Tgl Tender, No. Shipment, Aging (Hari); diurutkan dari aging terlama. Ringkasan di atas: jumlah SJ, pool, dan jumlah yang aging ≥ ${st.agingRed} hari (merah).</td></tr>
  <tr><td>Cari &amp; filter Customer</td><td>Kotak cari (driver, customer, shipment) dan tombol <b>Customer</b> yang membuka daftar customer bercentang beserta jumlah SJ-nya (semua terpilih bawaan; Pilih semua / Kosongkan). Keduanya bisa digabung; pilihan hanya berlaku di layar yang sedang dibuka.</td></tr>
  <tr><td>Aging</td><td>Dihitung dari <b>Tgl Tender sampai hari ini</b> (hari kalender), sehingga bertambah otomatis tiap hari; merah bila ≥ ${st.agingRed} hari.</td></tr>
</table></div>
  <div class="panel"><h3>Master Driver &amp; Master Unit</h3><table>
  <tr><td>Data bawaan &amp; upload</td><td>Bawaan dari master SIGAP (${initialMasterDrivers.length} driver, ${initialMasterUnits.length} unit) lengkap dengan Status (DM/DOS), Sub Status unit, serta Tanggal Aktif dan Nonaktif. Perbarui lewat <b>Download (template)</b>, ubah, lalu upload (tombol <b>Upload update</b> di menu Upload, atau <b>Upload Master</b> di tab Master); hasil upload menggantikan data bawaan untuk semua orang, dan sesudahnya muncul tombol <b>Pakai bawaan</b> di menu Upload untuk mengembalikannya.</td></tr>
  <tr><td>Aktif / nonaktif</td><td>Aktif pada tanggal aplikasi bila tanggal ≥ TANGGAL AKTIF dan < TANGGAL NONAKTIF; kosong = tidak dibatasi. Yang belum / tidak lagi aktif tidak tampil di List Driver dan tidak dihitung di kartu; unit nonaktif tidak muncul di pilihan Nopol.</td></tr>
  <tr><td>Kolom</td><td><b>Master Driver</b>: ID Driver, Nama Driver, <b>Catatan</b>, Status (DM/DOS), Status Klinik, Kode Absensi, lalu di paling kanan <b>Tanggal Aktif, Tanggal Nonaktif, Status Driver</b> (Aktif / Nonaktif). <b>Master Unit</b>: Nopol Unit, Tipe Trailer, <b>Catatan</b>, Sub Status, Status P2H, Status FMS BD, lalu di paling kanan <b>Tanggal Aktif, Tanggal Nonaktif, Status Unit</b> (Aktif / Nonaktif; bukan BD / Ready). Status Klinik, P2H, Kode Absensi, dan FMS BD memakai fungsi yang sama dengan Dashboard dan mengikuti Tanggal aplikasi.</td></tr>
  <tr><td>Filter, cari, urut</td><td>Filter <b>Status Driver / Status Unit</b> (Semua / Aktif / Nonaktif) ada tepat di atas kolom paling kanan; bawaannya <b>Aktif</b>. <b>Kotak cari</b> di atas tabel: ketik satu atau beberapa kata, baris yang tampil memuat semua kata itu di salah satu kolom (termasuk Catatan); "menampilkan n" di judul halaman ikut berubah. Urut dan filter kolom seperti tabel lain; tombol <b>Reset urut &amp; filter</b> juga mengosongkan kotak cari.</td></tr>
  <tr><td>Catatan</td><td>Kolom <b>Catatan</b> bisa diisi langsung (simpan dengan Enter atau pindah kotak). Catatan <b>menempel mulai tanggal aplikasi saat diisi dan seterusnya</b> sampai diubah atau dikosongkan; tanggal sebelumnya memakai catatan lama atau kosong. Disimpan terpisah dari Master, jadi tidak hilang saat Master di-upload ulang. Tampil juga di List Antrian (di bawah Nama dan di bawah Nopol Unit).</td></tr>
</table></div>
  <div class="panel"><h3>History Antrian</h3><table>
  <tr><td>Isi</td><td>Setiap perubahan List Driver / List Antrian (termasuk Depo, Nopol, Keterangan, Status Order manual) tersimpan otomatis sebagai riwayat per tanggal. Pilih tanggal lewat <b>kalender</b> (tanggal tanpa data menampilkan "Belum ada data tersimpan"; tombol Today kembali ke hari ini). <b>List Driver di atas</b>, <b>List Antrian di bawah</b>; keduanya langsung terlihat dalam satu layar tanpa menggulir halaman (menggulir hanya di dalam tabel) dan lebar tabel muat tanpa scroll ke kanan (teks turun baris, kata tidak dipotong).</td></tr>
  <tr><td>Kolom</td><td>Sama dengan Dashboard (tanpa Aksi): List Antrian 15 kolom, List Driver 6 kolom. Putaran lama diredupkan ("Ke-1 selesai"); baris SUDAH berlatar hijau; Status Order manual tampil "SUDAH" bertulisan manual (tooltip remarks). Aging SJ, Status Klinik, Status P2H, dan Status FMS BD adalah nilai yang tercatat pada tanggal itu (N/A bila tak ada datanya; "-" untuk riwayat lama yang belum mencatat aging). Di samping judul: List Driver memuat jumlah AVA / NOT AVA; List Antrian memuat "n driver yang sudah 2x antri" (hanya bila ada) dan lencana jumlah driver.</td></tr>
  <tr><td>Download Excel</td><td>Satu file Excel dua sheet, <b>List Antrian</b> dan <b>List Driver</b>, berisi seluruh data tanggal itu (tidak terpengaruh urut / filter di layar). Tanggal-jam berformat dd-mmm-yy hh:mm.</td></tr>
  <tr><td>Batasan</td><td>Riwayat tersimpan mulai aplikasi versi ini dipakai. Riwayat sebuah tanggal = kondisi terakhir yang tercatat pada tanggal itu; jumlah shipment mengikuti DOTS yang sudah diupload saat itu. Snapshot lama yang menyimpan status dengan aturan Status Order sebelumnya (berbatas waktu) ditampilkan sebagai SUDAH.</td></tr>
</table></div>
  <div class="panel"><h3>RAW Data</h3><table>
  <tr><td>Isi</td><td>Satu tabel: <b>hasil hitung per driver aktif</b> (sesuai Master Driver) untuk tanggal yang dipilih (isian Tanggal; kosongkan lewat tombol Clear kalender untuk kembali ke tanggal aplikasi). Kolom: ID Driver, Nama, File Absensi, Kode File Absensi, Status Absensi, Absen MTA, Alasan Status Absensi, Jumlah Shipment (DOTS), Shipment GPS Terakhir, Status GPS, Nopol GPS, Status Antrian, Alasan Status Antrian, Status Klinik, Ada di Antrian, Nopol Unit, Sumber Nopol, Status P2H, Status FMS BD, Alasan Status FMS BD, Status Order, Aging SJ Outs.(hari), Jumlah SJ Outs., dan <b>Kode Absensi (Dashboard)</b>. Kolom Nopol Unit dan Ada di Antrian memakai antrian saat ini; nopol cadangan dari GPS Tracking bila driver belum di antrian.</td></tr>
  <tr><td>Cari, urut, filter</td><td>Semua baris tampil dalam satu halaman (digulir di dalam kotak, judul kolom tetap terlihat). Kotak cari di atas tabel; klik judul kolom untuk mengurutkan (angka sebagai angka, tanggal-jam kronologis, kosong selalu di bawah); tombol <b>▾</b> membuka daftar nilai bercentang yang berjenjang (hanya nilai yang masih ada pada baris yang lolos pencarian dan filter lain, lengkap dengan jumlahnya; Absen MTA difilter per tanggal). Tombol <b>Reset tabel</b> muncul bila ada filter atau urutan.</td></tr>
  <tr><td>Download Excel</td><td>Tombol <b>Download Excel</b> mengunduh isi tabel <b>seperti yang tampil</b> (pencarian, filter, dan urutan yang aktif ikut) sebagai satu file, satu sheet "Status Driver"; nama file memuat tanggal. Tanggal-jam berformat dd-mmm-yy hh:mm. Dataset lain (absensi, DOTS, SJ, P2H, klinik, breakdown, GPS, master, antrian, setting) tidak lagi ditampilkan di RAW Data.</td></tr>
</table></div>
  <div class="panel"><h3>Sumber data &amp; pemakaiannya</h3><table>
  <tr><td>DOTS Report</td><td>Status Order, kartu Tender hari ini / Total driver OR hari ini / SPK sudah tender, jumlah shipment, Status Absensi (X + shipment → OR) dan ADJ; tanggal onroad lihat bagian DOTS.</td></tr>
  <tr><td>Download Detail SJ</td><td>Tab SJ Outstanding dan kolom Aging SJ Outs.(hari).</td></tr>
  <tr><td>Attendance</td><td>Kode Absensi (X, OR, O, AIS, N/A) dan dari situ Status Antrian (kode X = AVA).</td></tr>
  <tr><td>Monitoring Attendance</td><td>Hanya jam Absen MTA untuk driver berkode X dan OR.</td></tr>
  <tr><td>GPS Tracking Report</td><td>Status Antrian untuk driver OR (shipment terakhir closed / forceclosed = AVA), jam closed untuk mengurutkan OR yang AVA, status dan jam shipment di popup Status Order, serta penentu "selesai / belum selesai" di Status All Driver; nopol cadangan di RAW.</td></tr>
  <tr><td>bd_export</td><td>Status FMS BD (BD / Ready) per nopol: List Antrian, History, RAW, dan Master Unit.</td></tr>
  <tr><td>P2H inspection records · Clinic checkup records</td><td>Status P2H per unit dan Status Klinik per driver.</td></tr>
  <tr><td>Master Driver &amp; Master Unit</td><td>Daftar driver dan unit aktif (menurut tanggal aktif dan nonaktif) untuk semua tab.</td></tr>
  <tr><td>Tanggal aplikasi</td><td>Semua hitungan di atas mengikuti Tanggal aplikasi (chip kanan atas): catatan setelah tanggal itu diabaikan.</td></tr>
</table></div>
  <div class="panel"><h3>Upload: format file</h3><table>
  <tr><td>Cara upload</td><td>Seret file ke area upload; jenis file dikenali otomatis dari judul kolomnya, atau pakai tombol <b>Pilih file</b> pada kartunya (file yang tidak cocok dengan kartunya ditolak). Upload baru menggantikan yang lama (kecuali Attendance dan Monitoring: tanggal awal yang sama menggantikan yang lama).</td></tr>
  <tr><td>DOTS Report</td><td>Sheet pertama dengan baris judul yang memuat SHIPMENT, TANGGAL TENDER, STATUS FPT, dan ID DRIVER 1; kolom lain yang dipakai: ID DRIVER 2, EXECUTION DATE, CUSTOMER, ORDER BASE, NAMA RUTE, TIPE TRUCK, TIPE SHIPMENT, NOPOL, PROSES STATUS. Baris CANCEL dibuang.</td></tr>
  <tr><td>Download Detail SJ</td><td>Sheet pertama dengan kolom Shipment No, Aging, Tender Time, Customer (juga dipakai: Pool, NID, Status, Driver). Hanya SJ yang belum kembali yang dipakai.</td></tr>
  <tr><td>Attendance</td><td>Sheet pertama dengan kolom ID (juga dibaca: Driver ID, NID, NIK), NAMA DRIVER, lalu minimal 7 kolom tanggal (judul seperti "26 October 2026" atau tanggal Excel). Sel berisi X, OR, O, A, I, S, ADJ; kolom lain seperti JML diabaikan. Satu file = satu periode.</td></tr>
  <tr><td>Monitoring Attendance</td><td>Sheet pertama dengan kolom Driver ID, Attendance Date, Time, Attendance Type (Driver Name dipakai sebagai nama). Satu baris = satu catatan; hanya jenis Masuk dan Dalam Perjalanan (jamnya) yang dipakai.</td></tr>
  <tr><td>GPS Tracking Report</td><td>Sheet pertama dengan kolom Shipment Number, License Number (nopol), Driver Number (ID driver), Shipment Date, Status, Stop No, PTD; ATA dan ATD dipakai untuk jam closed. Satu shipment tampil di beberapa baris (satu per stop); aplikasi menyimpan satu baris per shipment beserta waktu aktual terakhirnya.</td></tr>
  <tr><td>Breakdown Unit (bd_export)</td><td>File CSV atau Excel dengan kolom Nopol, SO Date, Handover Time; SO Delete, Status, Pool, Problem Description dipakai bila ada. Satu baris = satu service order.</td></tr>
  <tr><td>Status P2H Unit</td><td>P2H inspection records: vehicle license number, status (READY / BREAKDOWN / PREVENTIVE), created date, not ok items; satu baris = satu inspeksi, semua catatan disimpan sehingga status mengikuti Tanggal aplikasi. Format sederhana (Nopol / Nopol Unit / No Polisi / Plat Nomor + Status P2H, opsional Tanggal) juga dikenali. Setelah upload tampil jumlah catatan, periode, dan sebaran status terbaru.</td></tr>
  <tr><td>Status Klinik Driver</td><td>Clinic checkup records: No DM, Tanggal Pemeriksaan, Time Pemeriksaan, Status (fit / FOM), Note; satu baris = satu pemeriksaan, semua catatan disimpan. Format sederhana (ID Driver / Driver ID / NID / NIK + Status Klinik, opsional Tanggal) juga dikenali. Setelah upload tampil jumlah catatan, periode, dan sebaran status terbaru.</td></tr>
  <tr><td>Master Driver</td><td>DRIVER ID, NAMA DRIVER, STATUS, TANGGAL AKTIF, TANGGAL NONAKTIF (judul lama ID_Driver / Nama_Driver juga dibaca). Baris tanpa ID atau nama dilewati. Tanggal: tanggal Excel atau teks 01-Oct-26, 01/10/2026, 2026-10-01; yang tidak terbaca dianggap kosong dan diberitahu saat upload.</td></tr>
  <tr><td>Master Unit</td><td>Nopol, Tipe Trailer, Sub Status, TANGGAL AKTIF, TANGGAL NONAKTIF (judul lama Nopol_Unit / Type_Unit juga dibaca). Baris tanpa nopol atau tipe dilewati.</td></tr>
</table></div>
  <div class="panel"><h3>Parameter tetap (dikunci)</h3><table>
  <tr><td>Aturan tanggal onroad (OR &amp; shipment)</td><td><b>Actual</b>: tanggal terbesar antara Execution Date dan Tanggal Tender. Berlaku untuk OR, shipment, dan aturan X + order. Aturan cut-off tender (H+1) dan parameter Jam cut-off tender sudah <b>dihapus</b>.</td></tr>
  <tr><td>Status GPS yang membuat driver OR menjadi AVA</td><td><b>CLOSED, FORCECLOSED</b> (kolom Status di GPS Tracking Report; shipment cancel dilewati saat mencari shipment terakhir).</td></tr>
  <tr><td>Nopol di List Antrian</td><td><b>Satu nopol hanya untuk satu driver per tanggal.</b></td></tr>
  <tr><td>Batas merah Aging (hari)</td><td><b>3 hari</b>: aging ≥ 3 hari berwarna merah.</td></tr>
  <tr><td>Sumber Aging SJ</td><td><b>Dihitung dari Tgl Tender ke hari ini</b> (bukan kolom Aging di file SJ).</td></tr>
  <tr><td>Saat ganti tanggal, driver yang sudah OR</td><td><b>Tetap di antrian</b>; driver yang belum OR selalu tetap di antrian.</td></tr>
  <tr><td>SO yang dihapus di bd_export</td><td><b>Diabaikan</b> (dianggap tidak ada).</td></tr>
  <tr><td>Pool SJ Outstanding</td><td><b>POOL NAGRAK TRAILER</b> untuk tab SJ Outstanding, Dashboard, History, dan RAW; tidak ada filter pool.</td></tr>
  <tr><td>Yang berlaku untuk siapa</td><td>Parameter di atas tidak bisa diubah pengguna. File yang diupload, antrian, dan catatan <b>tersimpan di server dan dipakai semua orang</b>; hanya <b>Tanggal aplikasi</b>, tema terang / gelap, dan pilihan tampilan menu yang tersimpan di browser masing-masing.</td></tr>
</table></div>
  <div class="panel"><h3>Istilah</h3><table>
  <tr><td>Kode</td><td><b>X</b>: sudah masuk / absen di pool · <b>OR</b>: on road (sedang jalan membawa order) · <b>O / OFF</b>: libur · <b>AIS</b>: alpha, izin, atau sakit · <b>N/A</b>: tidak ada data.</td></tr>
  <tr><td>Status</td><td><b>AVA</b>: tersedia, boleh masuk antrian · <b>NOT AVA</b>: belum · <b>SUDAH / BELUM</b>: Status Order (ada / belum ada shipment yang di-tender sesudah jam antri) · <b>BD / Ready</b>: Status FMS BD · <b>FIT / FOM</b>: Status Klinik (FOM = Fit On Medication) · <b>READY / BREAKDOWN / PREVENTIVE</b>: Status P2H · <b>Aktif / Nonaktif</b>: status Master menurut tanggal aktif dan nonaktif (berbeda arti dengan Status Antrian dan Status FMS BD).</td></tr>
</table></div>
  </div>`;
}

/* =====================================================================
   START
   ===================================================================== */
const DAY_EN = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
// kanan atas: Ddd, dd-Mmm-yy | hh:mm  (contoh: Wed, 07-Oct-26 | 14:18)
function clockText() { const n = nowDate(); return DAY_EN[n.getDay()] + ', ' + fmtSerial(dayOfDate(n)) + ' | ' + getCurrentTimeStr(); }
// Dipanggil tiap 15 detik. Ganti hari (00.00): absensi, shipment, P2H, dan Klinik dihitung ulang untuk tanggal baru (belum ada catatan absensi = N/A).
// Menit berganti: bila ada catatan P2H / Klinik yang masa berlakunya (24 jam) habis pada menit itu, tabel disegarkan.
let lastRenderDay = null, lastExpKey = null;
function anyExpiryBetween(lo, hi) {
  for (const m of [S.p2hBy, S.klinikBy]) for (const arr of m.values()) for (const r of arr) if (r.key >= 0) { const e = r.key + 1440; if (e > lo && e <= hi) return true; }
  return false;
}
function tickClock() {
  $('#clock').textContent = clockText();
  const t = todaySerial(), nk = keyAt();
  if (lastRenderDay == null) { lastRenderDay = t; lastExpKey = nk; }
  else if (t !== lastRenderDay) { lastRenderDay = t; lastExpKey = nk; renderAllData(); refreshOpenViews(true); }
  else if (nk !== lastExpKey) { const lo = lastExpKey; lastExpKey = nk; if (nk > lo && anyExpiryBetween(lo, nk)) { renderQueueSafe(); renderMasterDriver(); refreshOpenViews(); } }
  maybeRollover();
}
// ---- pilihan tanggal aplikasi (kanan atas) ----
function paintAsOf() {
  const sim = ASOF != null, btn = $('#clockBtn');
  btn.classList.toggle('sim', sim); $('#clockDev').hidden = !sim;
  btn.title = sim ? 'Tanggal simulasi (development). Klik untuk mengubah' : 'Klik untuk memilih tanggal: Saat Ini / Tanggal tertentu';
  const r = document.querySelector('input[name="asofMode"][value="' + (sim ? 'date' : 'now') + '"]'); if (r) r.checked = true;
  $('#asofDate').disabled = !sim; $('#asofDate').value = dateKey(sim ? ASOF : realSerial());
  $('#clock').textContent = clockText();
}
function setAsOf(v) {
  ASOF = v;
  try { if (v == null) localStorage.removeItem(ASOF_KEY); else localStorage.setItem(ASOF_KEY, String(v)); } catch (e) {}
  lastRenderDay = null; lastExpKey = null;
  paintAsOf(); renderAllData(); refreshOpenViews(true); renderAsumsi();
  if (v == null) maybeRollover();
}
function toggleAsofPop(open) {
  const p = $('#asofPop'), show = open == null ? p.hidden : open;
  p.hidden = !show; $('#clockBtn').setAttribute('aria-expanded', String(show));
}
$('#clockBtn').addEventListener('click', e => { e.stopPropagation(); toggleAsofPop(); });
document.querySelectorAll('input[name="asofMode"]').forEach(r => r.addEventListener('change', e => { if (e.target.value === 'now') setAsOf(null); else setAsOf(ASOF != null ? ASOF : realSerial()); }));
$('#asofDate').addEventListener('change', e => { const s = serialOfKey(e.target.value); if (s != null) setAsOf(s); });
document.addEventListener('click', e => { if (!$('#asofPop').hidden && !e.target.closest('.clock-wrap')) toggleAsofPop(false); });
document.addEventListener('keydown', e => { if (e.key === 'Escape' && !$('#asofPop').hidden) toggleAsofPop(false); });
async function initApp() {
  paintAsOf();
  renderMasterDriver(); renderMasterUnit(); renderListDriver(); renderListAntrian(); renderAsumsi(); tickClock(); renderShareBar();
  await initDb();
  DBREADY = true;
  renderShareBar();
  subscribeAll();
  setInterval(tickClock, 15000);
  setInterval(() => { $('#clock').textContent = clockText(); }, 5000);
}
window.addEventListener('load', initApp);

// ikon menu kiri dipasang di kiri judul tiap halaman (judul bisa digambar ulang kapan saja, jadi dipantau)
(function () {
  let q = false;
  const iconize = () => { q = false; document.querySelectorAll('.side-btn[data-v]').forEach(b => { const h2 = $(`#v-${b.dataset.v} .page-h h2`), svg = b.querySelector('svg'); if (h2 && svg && !h2.querySelector(':scope > .h2-ic')) { const s = document.createElement('span'); s.className = 'h2-ic'; s.setAttribute('aria-hidden', 'true'); s.innerHTML = svg.outerHTML; h2.prepend(s); } }); };
  new MutationObserver(() => { if (!q) { q = true; requestAnimationFrame(iconize); } }).observe(document.body, { childList: true, subtree: true });
  iconize();
})();
</script>
<script src="/assets/js/quedrive-adapter.js?v=<?= @filemtime(__DIR__ . '/../../public/assets/js/quedrive-adapter.js') ?>"></script>
</body>
</html>
