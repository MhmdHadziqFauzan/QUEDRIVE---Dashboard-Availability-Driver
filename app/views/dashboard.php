<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>QUEDRIVE – Queue Driver Availability &amp; Status Monitor</title>
<!-- SheetJS 0.18.5 (baca & buat file Excel), disimpan di server sendiri agar tetap jalan tanpa internet -->
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
.sidebar{width:206px;flex-shrink:0;background:var(--surface);border-right:1px solid var(--border);display:flex;flex-direction:column;padding:18px 14px;position:sticky;top:0;height:100vh;overflow-y:auto}
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
.btn.trk{padding:2px 9px;font-size:11px;text-decoration:none}
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
.dash-grid{display:grid;grid-template-columns:clamp(316px,23vw,440px) minmax(0,1fr);gap:16px;align-items:start}
.dash-grid>*{min-width:0}
.dash-grid .panel{margin-bottom:0}
@media(max-width:1365px){.dash-grid{grid-template-columns:minmax(0,1fr)}.dash-grid .panel{margin-bottom:16px}}   /* laptop >= 1366 px: tetap kiri-kanan */
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
select.cell-in{max-width:220px;text-overflow:ellipsis;cursor:pointer}
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
.modal[hidden],.side-btn[hidden]{display:none}
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
.hist-bar select{background:var(--accent-bg);border:1px solid var(--accent);border-radius:7px;padding:6px 8px;font-weight:700;color:var(--text)}
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
.sjbar{align-items:flex-start;gap:8px}
.sjbar .msel-panel{left:auto;right:0}
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
/* list antrian ringkas agar muat di samping List Driver pada layar laptop */
table.t.auto{font-size:11px}
table.t.auto th,table.t.auto td{padding:5px 3px}
table.t.auto td.nm{min-width:68px}
table.t.auto td.np{width:80px}
table.t.auto td.np .cell-in{width:76px;min-width:72px}
table.t.auto td.ft .cell-in{min-width:50px}
table.t.auto td.ft2 .cell-in{min-width:56px}
table.t thead tr.flt select{min-width:46px}
table.t.auto .type-in{white-space:normal}
/* laptop 1366-1439 px: jarak & kolom lebih rapat agar List Driver tetap lega */
@media(min-width:1366px) and (max-width:1439px){.dash-grid{gap:10px;grid-template-columns:330px minmax(0,1fr)}.dash-grid .panel{padding:10px}table.t.auto td.np{width:72px}table.t.auto td.np .cell-in{width:68px;min-width:64px}table.t.auto td.nm{min-width:62px}table.t.auto td.ft2 .cell-in{min-width:48px}table.t.auto th{font-size:10px}}
/* list driver: dropdown detail absen (Tipe Absensi, Last Shipment, Status Klinik) */
.act2{display:inline-flex;align-items:center;gap:1px}
.ibtn.dd svg.ic{transition:transform .15s}
.ibtn.dd.open{color:var(--accent);background:var(--accent-bg)}
.ibtn.dd.open svg.ic{transform:rotate(180deg)}
table.t.fit.lt tr.dd-open td{border-bottom-color:transparent;background:var(--accent-bg)}
table.t.fit.lt tr.dd-row td{white-space:normal;text-align:left;padding:2px 8px 8px 30px;background:var(--accent-bg)}
.dd-list{display:flex;flex-direction:column;gap:4px;background:var(--surface);border:1px solid var(--border);border-radius:8px;padding:7px 9px}
/* checkbox & label sejajar dengan baris pertama nilai (pill), walau keterangan turun ke baris berikutnya */
.dd-it{display:grid;grid-template-columns:14px 82px minmax(0,1fr);align-items:start;gap:6px;font-size:10.5px;line-height:18px}
.dd-it input{margin:3px 0 0;width:12px;height:12px;accent-color:var(--accent2);pointer-events:none}
.dd-k{font-weight:700;color:var(--muted)}
.dd-v{display:flex;align-items:center;gap:0 5px;flex-wrap:wrap;min-width:0;min-height:18px}
.dd-v .pill{line-height:14px}
.dd-s{white-space:nowrap}
.kl-cd{font-size:9.5px;font-weight:700;color:var(--accent);background:var(--accent-bg);border:1px solid var(--accent);border-radius:999px;padding:0 6px;white-space:nowrap}
.kl-cd.exp{color:var(--danger);border-color:var(--danger);background:transparent}
.c-WR{background:var(--warn);color:#fff}
/* list driver: tombol & pop up filter */
.df-wrap{position:relative;flex-shrink:0}
#btnDF.on,#btnMDF.on,#btnMUF.on{border-color:var(--accent);color:var(--accent);background:var(--accent-bg)}
.df-o.off{opacity:.5;cursor:not-allowed}
#v-masterDriver .toolbar select,#fltStatusLD{padding:6px 8px;font-weight:600}
.df-n{min-width:16px;padding:0 5px;border-radius:999px;background:var(--accent);color:#fff;font-size:10px;line-height:16px;text-align:center}
.df-n[hidden],.df-panel[hidden]{display:none}
.df-panel{position:absolute;z-index:40;top:calc(100% + 6px);right:0;width:min(300px,86vw);background:var(--surface);border:1px solid var(--border);border-radius:10px;box-shadow:0 12px 32px rgba(0,0,0,.18);padding:10px 12px}
.df-h{display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;font-size:12.5px}
.df-h .modal-x{position:static;width:26px;height:26px;font-size:16px}
.df-g{border-top:1px solid var(--border);padding:7px 0 8px}
.df-l{font-size:10.5px;font-weight:700;color:var(--muted);letter-spacing:.03em;text-transform:uppercase;margin-bottom:5px}
.df-opts{display:flex;flex-wrap:wrap;gap:6px}
.df-o{display:inline-flex;align-items:center;gap:5px;padding:3px 9px;border:1px solid var(--border);border-radius:999px;font-size:11.5px;cursor:pointer;background:var(--surface2)}
.df-o:has(input:checked){border-color:var(--accent);background:var(--accent-bg);color:var(--accent);font-weight:600}
.df-o input{margin:0;accent-color:var(--accent)}
.df-act{display:flex;justify-content:flex-end;gap:6px;border-top:1px solid var(--border);padding-top:8px}
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
.logo-wrap{grid-column:1/-1;display:flex;flex-direction:column;gap:6px}
.logo-title{font-size:11.5px;font-weight:700;color:var(--muted)}
.logo-opts{display:flex;gap:10px;flex-wrap:wrap}
.logo-opt{display:flex;flex-direction:column;align-items:center;gap:4px;width:104px;padding:10px 6px 8px;border:2px solid var(--border);border-radius:10px;background:var(--surface);cursor:pointer;color:var(--text)}
.logo-opt:hover{border-color:var(--muted)}
.logo-opt[aria-pressed="true"]{border-color:var(--accent);background:var(--accent-bg)}
.logo-opt[aria-pressed="true"] .lg{--lg-bg:var(--accent-bg)}
.logo-opt svg.lg{width:56px;height:56px}
.logo-opt span{font-size:10.5px;font-weight:600;text-align:center;line-height:1.25}
@media(max-width:800px){.hdr-logo{height:30px}}
/* tombol Day/Night + tulisan kecil di kiri bawah (seperti APEX) */
.theme-pill{display:flex;align-items:center;justify-content:center;gap:8px;margin:10px auto 8px;padding:7px 18px;border:1px solid var(--accent);background:var(--accent-bg);color:var(--accent);border-radius:999px;font-size:12.5px;cursor:pointer;min-width:104px}
.theme-pill b{font-weight:700}
.theme-pill:hover{filter:brightness(.97)}
.theme-ic{display:inline-flex}
.theme-ic svg{width:17px;height:17px}
.side-credit{text-align:center;font-size:10.5px;color:var(--muted);line-height:1.5;padding-bottom:6px}
.side-credit .sc-name{letter-spacing:.06em}
</style>
</head>
<body>
<div class="app-shell">
  <aside class="sidebar">
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
    <div class="side-label">Data</div>
    <nav class="side-nav">
      <!-- Upload & Setting disembunyikan sementara (hapus atribut hidden untuk menampilkan lagi) -->
      <button class="side-btn" data-v="data" hidden><svg class="ic" viewBox="0 0 24 24"><path d="M12 16V4m0 0l-4 4m4-4l4 4M4 16v3a1 1 0 001 1h14a1 1 0 001-1v-3"/></svg>Upload &amp; Setting</button>
      <button class="side-btn" data-v="history"><svg class="ic" viewBox="0 0 24 24"><path d="M3 12a9 9 0 109-9 9 9 0 00-6.4 2.6L3 8"/><path d="M3 3v5h5M12 7v5l3 2"/></svg>History Antrian</button>
    </nav>
    <div class="side-label">Info</div>
    <nav class="side-nav">
      <button class="side-btn" data-v="asumsi"><svg class="ic" viewBox="0 0 24 24"><path d="M12 22a10 10 0 100-20 10 10 0 000 20zM12 16v-4M12 8h.01"/></svg>Asumsi &amp; Logic</button>
    </nav>
    <div class="sidebar-footer"><div class="sharebar loc" id="shareBar">Memuat data…</div><button type="button" class="theme-pill" id="btnTheme" title="Ganti tema terang/gelap" aria-label="Ganti tema terang/gelap"><span class="theme-ic" id="themeIc"></span><b id="themeLbl">Night</b></button><div class="side-credit"><div class="sc-name">QUEDRIVE</div><div>© <span id="yr">2026</span> Transport Trailer</div></div></div>
  </aside>
  <div class="app-content">
    <header>
      <div class="hdr-title">QUEDRIVE – QUEUE DRIVER AVAILABILITY &amp; STATUS MONITOR</div>
      <div class="hdr-actions">
        <div class="clock" title="Waktu saat ini"><svg class="ic" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg><span id="clock">---, --</span></div>
        <img class="hdr-logo" src="data:image/png;base64,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" alt="Puninar Logistics">
      </div>
    </header>
    <main>
      <!-- DASHBOARD -->
      <section class="view visible" id="v-dashboard">
        <div class="page-h"><h2>Dashboard Availability Driver</h2><p>Pemantauan antrian dan ketersediaan operasional</p></div>
        <div class="kpi-group">
          <div class="kpi-lead"><div class="t">Total Antrian</div><div class="big" id="count-antrean">0</div><div class="s">driver di List Antrian hari ini</div></div>
          <div class="kpi-row">
            <div class="kpi quadbox"><div class="quad">
              <div class="qc"><div class="l"><i class="dot" style="background:var(--warn)"></i>Belum Diorder</div><div class="v" id="count-belum">0</div></div>
              <div class="qc"><div class="l"><i class="dot" style="background:var(--s-or)"></i>Sudah Diorder</div><div class="v" id="count-sudah">0</div></div>
              <div class="qc"><div class="l"><i class="dot" style="background:var(--danger)"></i>% Belum Diorder</div><div class="v" id="count-pct">-</div></div>
              <div class="qc"><div class="l"><i class="dot" style="background:var(--s-ais)"></i>Tersedia (AVA)</div><div class="v" id="count-ava">0</div><div class="sub">Sudah absen, belum antri</div></div>
            </div></div>
            <div class="kpi"><div class="l"><i class="dot" style="background:var(--s-or)"></i>On Road (OR) hari ini</div><div class="v" id="count-or">0</div><div class="sub split" id="or-detail"></div></div>
            <div class="kpi"><div class="l"><i class="dot" style="background:var(--accent)"></i>Shipment SPK hari ini</div><div class="v" id="count-spk">0</div><div class="sub split" id="spk-detail"></div></div>
            <div class="kpi"><div class="l"><i class="dot" style="background:#7C5CBF"></i>Shipment SPO hari ini</div><div class="v" id="count-spo">0</div><div class="sub split" id="spo-detail"></div></div>
          </div>
        </div>
        <div class="dash-grid">
          <div class="panel">
            <h3><svg class="ic" viewBox="0 0 24 24"><circle cx="12" cy="7" r="3.4"/><path d="M6.8 21.5v-5.6c0-2.6 2.3-4.6 5.2-4.6s5.2 2 5.2 4.6v5.6"/></svg>List Driver<span class="grow"><span class="badge-n" id="badge-total-absen">0 Driver</span></span></h3>
            <p class="desc">Driver yang sudah absen hari ini (AVA) di atas dan bisa dimasukkan ke antrian.</p>
            <div class="toolbar"><input type="search" id="searchDriver" placeholder="Cari ID atau Nama Driver..." aria-label="Cari driver"><select id="fltStatusLD" aria-label="Filter status driver"><option value="all">Semua</option><option value="AVA">AVA</option><option value="NOT AVA">NOT AVA</option></select><div class="df-wrap">
              <button class="btn" id="btnDF" type="button" aria-haspopup="dialog" aria-expanded="false" aria-controls="dfPanel"><svg class="ic" viewBox="0 0 24 24"><path d="M3 5h18l-7 8v6l-4 2v-8z"/></svg>Filter<span class="df-n" id="dfCount" hidden>0</span></button>
              <div class="df-panel" id="dfPanel" role="dialog" aria-label="Filter List Driver" hidden><div class="df-h"><b>Filter List Driver</b><button class="modal-x" id="dfClose" type="button" aria-label="Tutup">&times;</button></div><div id="dfBody"></div><div class="df-act"><button class="btn" id="dfReset" type="button">Reset</button><button class="btn primary" id="dfDone" type="button">Terapkan</button></div></div>
            </div></div>
            <div class="tbl-wrap nox">
              <table class="t fit lt">
                <colgroup><col style="width:22px"><col style="width:58px"><col><col style="width:38px"><col style="width:54px"><col style="width:50px"></colgroup>
                <thead><tr><th>No</th><th class="l">ID Driver</th><th class="l">Nama</th><th>Jam</th><th>Status Driver</th><th>Aksi</th></tr></thead>
                <tbody id="tbDriver"></tbody>
              </table>
            </div>
          </div>
          <div class="panel">
            <h3><svg class="ic" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8l4 4-4 4M8 12h8"/></svg>List Antrian<span class="grow"><button class="btn danger" id="btnReset"><svg class="ic" viewBox="0 0 24 24"><path d="M1 4v6h6"/><path d="M3.5 15a9 9 0 102.1-9.4L1 10"/></svg>Reset Antrian</button></span></h3>
            <p class="desc">Urutan antrian mengikuti jam antri. Driver tetap di antrian walau sudah diorder; klik <b>SUDAH</b> untuk melihat detail shipment hari ini.</p>
            <div class="note" id="fltInfo" hidden></div>
            <div class="tbl-wrap nox">
              <table class="t auto" id="tblAntrian">
                <thead><tr class="flt"><th></th><th></th><th></th><th></th><th><select id="fltTipe" aria-label="Filter tipe unit"><option value="all">Semua</option></select></th><th></th><th></th><th><select id="fltOrder" aria-label="Filter status order"><option value="all">Semua</option><option value="or">SUDAH</option><option value="belum">BELUM</option></select></th><th></th><th></th><th></th><th></th><th></th><th></th></tr><tr><th>No</th><th class="l">ID Driver</th><th class="l">Nama</th><th class="l">Nopol Unit</th><th class="l">Tipe Unit</th><th>Absen MTA</th><th>Antri</th><th>Status Order</th><th>Status Klinik</th><th>Status P2H</th><th>Aging SJ Outs.(hari)</th><th class="l">Depo</th><th class="l">Keterangan</th><th>Aksi</th></tr></thead>
                <tbody id="tbAntrian"></tbody>
              </table>
            </div>
          </div>
        </div>
      </section>

      <!-- HISTORY -->
      <section class="view" id="v-history"></section>

      <!-- SJ OUTSTANDING -->
      <section class="view" id="v-sj"></section>

      <!-- MASTER DRIVER -->
      <section class="view" id="v-masterDriver">
        <div class="page-h"><h2>Master Data Driver</h2><p id="cntMD">Memuat data driver…</p></div>
        <div class="panel"><div class="toolbar"><input type="search" id="searchMD" placeholder="Cari ID atau Nama Driver..." aria-label="Cari driver di Master Driver"><select id="fltStatusDriver" aria-label="Filter status driver"><option value="all">Status Driver: Semua</option><option value="Aktif">Aktif</option><option value="Nonaktif">Nonaktif</option></select><div class="df-wrap md-df"><button class="btn" id="btnMDF" type="button" aria-haspopup="dialog" aria-expanded="false" aria-controls="mdfPanel"><svg class="ic" viewBox="0 0 24 24"><path d="M3 5h18l-7 8v6l-4 2v-8z"/></svg>Filter<span class="df-n" id="mdfCount" hidden>0</span></button><div class="df-panel" id="mdfPanel" role="dialog" aria-label="Filter Master Driver" hidden><div class="df-h"><b>Filter Master Driver</b><button class="modal-x" id="mdfClose" type="button" aria-label="Tutup">&times;</button></div><div id="mdfBody"></div><div class="df-act"><button class="btn" id="mdfReset" type="button">Reset</button><button class="btn primary" id="mdfDone" type="button">Selesai</button></div></div></div></div>
        <div class="tbl-wrap" style="max-height:72vh"><table class="t"><thead><tr><th>No</th><th class="l">ID Driver</th><th class="l">Nama Driver</th><th>Status Driver</th><th>Status Klinik</th><th>Last Shipment Status</th><th>Last Unloading</th><th>Last Location</th></tr></thead><tbody id="tbMD"></tbody></table></div></div>
      </section>

      <!-- MASTER UNIT -->
      <section class="view" id="v-masterUnit">
        <div class="page-h"><h2>Master Data Unit</h2><p id="cntMU">Memuat data unit…</p></div>
        <div class="panel"><div class="toolbar"><input type="search" id="searchMU" placeholder="Cari Nopol, Tipe, Pool, Customer..." aria-label="Cari unit di Master Unit"><div class="df-wrap mu-df"><button class="btn" id="btnMUF" type="button" aria-haspopup="dialog" aria-expanded="false" aria-controls="mufPanel"><svg class="ic" viewBox="0 0 24 24"><path d="M3 5h18l-7 8v6l-4 2v-8z"/></svg>Filter<span class="df-n" id="mufCount" hidden>0</span></button><div class="df-panel" id="mufPanel" role="dialog" aria-label="Filter Master Unit" hidden><div class="df-h"><b>Filter Master Unit</b><button class="modal-x" id="mufClose" type="button" aria-label="Tutup">&times;</button></div><div id="mufBody"></div><div class="df-act"><button class="btn" id="mufReset" type="button">Reset</button><button class="btn primary" id="mufDone" type="button">Selesai</button></div></div></div></div><div class="tbl-wrap" style="max-height:72vh"><table class="t"><thead><tr><th>No</th><th class="l">Nopol Unit</th><th class="l">Tipe Unit</th><th class="l">Pool</th><th class="l">Customer</th><th>Status P2H</th></tr></thead><tbody id="tbMU"></tbody></table></div></div>
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


const LOCAL_KEY = 'quedrive_localdb_v1';

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
function getCurrentTimeStr() { const n = new Date(); return pad(n.getHours()) + ':' + pad(n.getMinutes()); }
function getCurrentDateTimeStr() {
  const n = new Date(), m = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
  return `${n.getDate()} ${m[n.getMonth()]} ${pad(n.getHours())}:${pad(n.getMinutes())}`;
}
const MON = { JAN:1, FEB:2, MAR:3, APR:4, MAY:5, MEI:5, JUN:6, JUL:7, AUG:8, AGU:8, AGT:8, AGS:8, SEP:9, OCT:10, OKT:10, NOV:11, DEC:12, DES:12 };
const MON_EN = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const ymd = (y, m, d) => Math.floor(Date.UTC(y, m - 1, d) / 86400000) + 25569;      // serial hari Excel
const todaySerial = () => { const n = new Date(); return ymd(n.getFullYear(), n.getMonth() + 1, n.getDate()); };
const utcDate = s => new Date((s - 25569) * 86400000);
// format tanggal aplikasi: dd-Mmm-yy
function fmtSerial(s) { if (s == null) return '-'; const d = utcDate(s); return pad(d.getUTCDate()) + '-' + MON_EN[d.getUTCMonth()] + '-' + pad(d.getUTCFullYear() % 100); }
const fmtDMY = fmtSerial;
function fmtDM(s) { const d = utcDate(s); return pad(d.getUTCDate()) + '-' + MON_EN[d.getUTCMonth()]; }              // dd-Mmm
const dayOfDate = n => ymd(n.getFullYear(), n.getMonth() + 1, n.getDate());
function fmtTs(ms) { const n = new Date(ms); return fmtSerial(dayOfDate(n)) + ' ' + pad(n.getHours()) + ':' + pad(n.getMinutes()); }  // dd-Mmm-yy HH:MM
// kolom Absen MTA & Antri: dd-Mmm | HH:MM
function antriStr(a) { const n = new Date(a.seq || Date.now()); return fmtDM(dayOfDate(n)) + ' | ' + pad(n.getHours()) + ':' + pad(n.getMinutes()); }
// tanggal dan jam masing-masing tidak dipotong di tengah, sehingga paling banyak 2 baris: "06-Oct |" / "15:57"
function dtHtml(s) { s = String(s == null ? '' : s); if (!s) return '-'; const m = /^(.*\|)\s(\S+)$/.exec(s); return m ? `<span class="nb">${esc(m[1])}</span> <span class="nb">${esc(m[2])}</span>` : esc(s); }
function absenStr(a) { if (!a.jam_absen) return '-'; const day = a.absen_day != null ? a.absen_day : dayOfDate(new Date(a.seq || Date.now())); return fmtDM(day) + ' | ' + a.jam_absen; }
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
const DEFAULTS = { logo: 'A', cutoffOn: false, cutoff: '18:00', sjPool: 'POOL NAGRAK TRAILER', agingSrc: 'tender', agingRed: 3, orRollover: 'keep' };
let toastTimer = null;
function toast(msg) { const t = $('#toast'); t.textContent = msg; t.classList.add('show'); clearTimeout(toastTimer); toastTimer = setTimeout(() => t.classList.remove('show'), 3600); }
function debounce(fn, ms) { let t = null; return (...a) => { clearTimeout(t); t = setTimeout(() => fn(...a), ms); }; }

/* =====================================================================
   LAYER DATA: database QUEDRIVE (MySQL lewat /api/store). Tidak memakai penyimpanan browser.
   ===================================================================== */
const CID = 'c' + Math.random().toString(36).slice(2, 10);
let DB = null, MODE = 'local', CAN_WRITE = true, DBREADY = false;

// cadangan bila database QUEDRIVE tidak bisa dihubungi: data hanya di memori halaman (tidak disimpan di browser), mode baca saja
function makeMemoryDb() {
  const store = {}, ver = {}, subs = new Set();
  const clone = o => JSON.parse(JSON.stringify(o));
  const persist = () => {};
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
// Penyimpanan di database QUEDRIVE (MySQL) lewat backend /api/store: dipakai bersama semua browser.
// Antarmuka sama dengan makeMemoryDb; perubahan dari browser lain diambil tiap STORE_POLL_MS.
const STORE_POLL_MS = 3000;
async function storePost(body) {
  const r = await fetch('/api/store', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(body) });
  if (!r.ok) {
    let m = r.status + ' ' + r.statusText; try { m = (await r.json()).detail || m; } catch (e) {}
    const e = new Error(m); e.status = r.status; e.code = r.status === 409 ? 'invalid_argument' : 'server'; throw e;   // 409 = dokumen belum ada (upsert lalu set)
  }
  return r.json();
}
async function makeServerDb() {
  if (location.protocol === 'file:') return null;
  let first;
  try { first = await apiGet('/api/store?since=0'); } catch (e) { return null; }   // database QUEDRIVE belum ada: pakai browser
  const store = {}, ver = {}, subs = new Set();
  let since = Math.max(0, (first.ver || 0) - 5e6);   // waktu server (mikrodetik)
  const clone = o => JSON.parse(JSON.stringify(o));
  const notify = () => setTimeout(() => subs.forEach(f => f()), 0);
  const apply = docs => { for (const d of docs) { if ((ver[d.path] || 0) >= d.ver) continue; if (d.deleted) delete store[d.path]; else store[d.path] = d.data; ver[d.path] = d.ver; } if (docs.length) notify(); };
  apply(first.docs);
  // sekali saja: data lama yang dulu tersimpan di browser ini dipindahkan ke database (bila database belum berisi), lalu dihapus dari browser
  let old = null; try { old = JSON.parse(localStorage.getItem(LOCAL_KEY) || 'null'); } catch (e) {}
  if (old) {
    let gagal = 0;
    const kosong = !first.docs.some(d => d.path !== 'app/queue') && !Object.keys((first.docs.find(d => d.path === 'app/queue') || { data: { items: {} } }).data.items || {}).length;
    if (kosong) for (const [path, data] of Object.entries(old)) {
      try { apply([(await storePost({ op: 'set', path, data })).doc]); } catch (e) { if (e.status !== 422) gagal++; }   // 422 = jenis data lama yang tidak dipakai lagi
    }
    if (!gagal) try { localStorage.removeItem(LOCAL_KEY); } catch (e) {}
  }
  const poll = async () => {
    try { const r = await apiGet('/api/store?since=' + since); apply(r.docs); since = Math.max(since, (r.ver || 0) - 5e6); } catch (e) {}
    setTimeout(poll, STORE_POLL_MS);
  };
  setTimeout(poll, STORE_POLL_MS);
  const has = p => Object.prototype.hasOwnProperty.call(store, p);
  const mkSnap = p => { const ex = has(p); return { id: p.split('/').pop(), exists: ex, data: () => ex ? clone(store[p]) : undefined, metadata: {} }; };
  const colPaths = c => Object.keys(store).filter(p => p.startsWith(c + '/') && p.indexOf('/', c.length + 1) < 0).sort();
  const qSnap = c => { const docs = colPaths(c).map(mkSnap); return { docs, size: docs.length, empty: !docs.length }; };
  const sub = (sigFn, snapFn, next) => { let last = null; const f = () => { const g = sigFn(); if (g === last) return; last = g; next(snapFn()); }; subs.add(f); setTimeout(f, 0); return () => subs.delete(f); };
  const write = async (op, path, data) => { const r = await storePost({ op, path, data }); apply([r.doc]); };
  const docRef = p => ({ id: p.split('/').pop(), path: p,
    get: async () => mkSnap(p),
    set: d => write('set', p, d),
    update: d => write('update', p, d),
    delete: () => write('delete', p),
    acquire: async o => storePost({ op: 'acquire', path: p, holder: o && o.holder, ttlMs: o && o.ttlMs }),
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
  } else {
    const srv = await makeServerDb();
    if (srv) { DB = srv; MODE = 'server'; } else { DB = makeMemoryDb(); MODE = 'local'; CAN_WRITE = false; }
  }
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
  if (!CAN_WRITE) { toast(MODE === 'local' ? 'Database QUEDRIVE tidak terhubung: perubahan tidak bisa disimpan.' : 'Mode baca saja: akun ini tidak bisa mengubah data.'); return false; }
  try { await fn(); return true; } catch (e) { handleWriteError(e); return false; }
}

/* =====================================================================
   STATE
   ===================================================================== */
const S = {
  masterDB: [], unitDB: [],   // diisi dari backend (/api/drivers, /api/units)
  queueItems: {}, pending: {}, config: {}, settings: { ...DEFAULTS },
  dotsRows: [], dotsIds: [], sjRows: [], sjIds: [], p2hRows: [], p2hIds: [], p2h: new Map(), klinikRows: [], klinikIds: [], klinik: new Map(), ship: new Map(), loaded: {}
};
const activeDrivers = () => { const t = todaySerial(); return S.masterDB.filter(r => isActive(r, t)); };
const activeUnits = () => { const t = todaySerial(); return S.unitDB.filter(r => isActive(r, t)); };
const queueMap = () => { const m = { ...S.queueItems }; for (const k of Object.keys(S.pending)) if (!m[k]) m[k] = S.pending[k]; return m; };
const antrian = () => Object.values(queueMap()).filter(x => x && x.id).sort((a, b) => (a.seq || 0) - (b.seq || 0));
/* =====================================================================
   SUMBER DATA: backend lokal PHP (app/lib.php, public/api/index.php) -> DB1 / db_utilisasi
   ===================================================================== */
const API = { ok: false, err: '', at: 0, tanggal: null, drivers: [], units: [], p2h: new Map(), klinik: new Map(), sj: null, sjErr: '', depo: [], spk: null };
const API_REFRESH_MS = 30000;   // backend juga meng-cache 30 detik
async function apiGet(path) {
  const r = await fetch(path, { cache: 'no-store' });
  if (!r.ok) { let m = r.status + ' ' + r.statusText; try { m = (await r.json()).detail || m; } catch (e) {} throw new Error(m); }
  return r.json();
}
// data terakhir tetap dipakai bila backend sesaat tidak bisa dihubungi
async function loadApi() {
  try {
    if (location.protocol === 'file:') throw new Error('halaman dibuka sebagai file, bukan lewat backend');
    const [d, u, dp, sp] = await Promise.all([apiGet('/api/drivers'), apiGet('/api/units'), apiGet('/api/depo').catch(() => null), apiGet('/api/spk-spo').catch(() => null)]);
    if (dp) API.depo = dp;   // daftar depo gagal dimuat: pakai daftar terakhir
    if (sp) API.spk = sp;    // kartu SPK / SPO dari trx_shipment_preparation
    Object.assign(API, { ok: true, err: '', at: Date.now(), tanggal: d.tanggal, drivers: d.drivers, units: u });
    S.masterDB = d.drivers.map(x => ({ id: x.id, nama: x.nama }));
    S.unitDB = u.map(x => ({ nopol: x.nopol, type: x.tipe_unit, pool: x.pool, customer: x.customer }));
    API.p2h = new Map(u.filter(x => x.p2h).map(x => [normPlat(x.nopol), { st: x.p2h, at: x.p2h_at }]));
    API.klinik = new Map(d.drivers.filter(x => x.klinik).map(x => [normId(x.id), x.klinik]));
  } catch (e) { API.ok = false; API.err = e.message || String(e); }
  // SJ Outstanding (odongnew_db) dimuat terpisah: bila gagal, data driver & unit tetap tampil
  try {
    if (location.protocol === 'file:') throw new Error('halaman dibuka sebagai file');
    API.sj = (await apiGet('/api/sj')).map(x =>
      [x.shipment_number, x.pool || '', x.customer || '', serialOfKey(String(x.tender_date || '').slice(0, 10)), null, x.driver_id || '', '', x.nopol || '']);
    API.sjErr = '';
  } catch (e) { API.sjErr = e.message || String(e); }
  S.loaded.api = true;
  renderListDriver(); renderQueueSafe(); updateDashboard(); renderMasterDriver(); renderMasterUnit(); renderShareBar(); refreshOpenViews();
  autoFillNopol();
}
// List Driver hari ini; Tipe Absensi, Last Shipment & AVA dihitung di backend
function driverListToday() {
  return API.drivers.map(x => ({ id_driver: x.id, nama: x.nama, jam_absen: x.jam, status_lokasi: x.status, tipe_absen: x.tipe_absen,
    last_loc: x.last_shipment, shipment_number: x.shipment_number, tender_date: x.tender_date, closed_date: x.closed_date, nopol: x.nopol, tipe_unit: x.tipe_unit, aktif: x.aktif,
    last_unloading: x.last_unloading, last_unloading_at: x.last_unloading_at, tracking_nopol: x.tracking_nopol, nopol_driver_lain: x.nopol_driver_lain }));
}
const cutMin = () => { const m = /^(\d{1,2}):(\d{2})/.exec(S.settings.cutoff || '18:00'); return m ? (+m[1]) * 60 + (+m[2]) : 1080; };
const cutOn = () => S.settings.cutoffOn === true;
// tanggal onroad = actual eksekusi = tanggal terbesar antara Execution Date dan Tanggal Tender
// (opsional: tender lewat jam cut-off dihitung H+1 sebelum dibandingkan)
function onDay(td, tm, ex) { let on = td + (cutOn() && tm > cutMin() ? 1 : 0); if (ex != null && ex > on) on = ex; return on; }
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
  const mk = () => ({ n: 0, t20: 0, t40: 0, oth: 0 }), sp = { SPK: mk(), SPO: mk() };
  const first = S.dotsRows[0], hasFPT = !first || first.length > 11;
  for (const r of S.dotsRows) {
    const [ship, d1, d2, td, tm, ex, , , , tp, ts, fpt] = r;
    if (onDay(td, tm, ex) !== day) continue;
    const g = truckGroup(tp);
    // data lama (belum menyimpan STATUS FPT) tetap dihitung seperti sebelumnya sampai DOTS Report diupload ulang
    const counted = !hasFPT || fpt === 'NEW' || fpt === 'CARRY OVER';
    if (counted && !seen.has(ship)) { seen.add(ship); const o = sp[ts]; if (o) { o.n++; o[grpKey(g)]++; } }
    const key = td * 1440 + tm;
    for (const d of [d1, d2]) if (d && act.has(d)) { const c = best.get(d); if (!c || key >= c.key) best.set(d, { key, g }); }
  }
  const or = mk(); or.n = best.size; best.forEach(v => { or[grpKey(v.g)]++; });
  return { sp, or, hasType: !first || first.length > 9, hasTS: !first || first.length > 10, hasFPT };
}
const detailHtml = o => `<span>20 FT <b>${o.t20}</b></span><span>40 FT <b>${o.t40}</b></span>${o.oth ? `<span>Lainnya <b>${o.oth}</b></span>` : ''}`;
// ---- Status P2H per UNIT (kunci = nopol, spasi/strip/titik diabaikan) ----
const normPlat = s => String(s == null ? '' : s).toUpperCase().replace(/[^A-Z0-9]/g, '');
// database puninar_p2h didahulukan; file P2H yang diupload hanya untuk unit yang tidak ada di database
const p2hOf = nopol => { const k = normPlat(nopol); return k ? (API.p2h.get(k)?.st || S.p2h.get(k) || '') : ''; };
// warna ditebak dari kata pada status; teks yang tampil tetap teks asli dari file
function stClass(st) {
  const u = String(st).toUpperCase();
  if (/\b(NOT|TIDAK|BELUM|GAGAL|FAIL|NG|REJECT|RUSAK|ABNORMAL|DEFECT|TEMUAN|NO|UNFIT|SAKIT|DITOLAK|BREAKDOWN)\b/.test(u)) return 'c-NG';
  if (/\b(PREVENTIVE|SERVICE|MAINTENANCE)\b/.test(u)) return 'c-WR';
  if (/\b(OK|FIT|READY|LOLOS|PASS|NORMAL|SIAP|LAYAK|BAIK|GOOD|SUDAH|DONE|CLEAR|SEHAT)\b/.test(u)) return 'c-OR';
  return 'c-O';
}
const p2hClass = stClass;
// ---- Status Klinik per DRIVER (kunci = ID driver, tanda baca diabaikan) ----
// sumber utama: trx_dms_absent.status_klinik (baris absen hari ini); file upload Klinik hanya cadangan bila database tidak terhubung.
// "-" jika driver belum punya data klinik. Satu fungsi dipakai Dashboard, History, dan Master Driver
const normId = s => String(s == null ? '' : s).toUpperCase().replace(/[^A-Z0-9]/g, '');
const klinikOf = id => API.klinik.get(normId(id)) || (API.ok ? '' : S.klinik.get(normId(id))) || '';
// pill = tampilan List Antrian (seperti Status Order): belum ada status -> pill BELUM
function klinikCell(id, pill = false) {
  const st = klinikOf(id);
  if (st) return `<span class="pill ${stClass(st)}">${esc(st)}</span>`;
  return pill ? '<span class="pill c-O" title="Driver ini belum punya data Status Klinik">BELUM</span>' : '<span class="note" title="Driver ini belum punya data Status Klinik">-</span>';
}
// "-" jika nopol belum diisi atau belum ada data P2H untuk unit itu
function p2hCell(nopol) {
  if (!String(nopol || '').trim()) return '<span class="note" title="Nopol Unit belum diisi">-</span>';
  const st = p2hOf(nopol);
  const at = API.p2h.get(normPlat(nopol))?.at;
  return st ? `<span class="pill ${p2hClass(st)}"${at ? ` title="P2H terakhir ${fmtApiDT(at)}"` : ''}>${esc(st)}</span>` : '<span class="note" title="Tidak ada P2H unit ini dalam 30 hari terakhir">-</span>';
}
// ---- Aging SJ Outs. per driver (angka terbesar = SJ terlama) ----
// baris SJ: [shipment, pool, customer, tender (serial), aging file, ID driver, nama, nopol]; database didahulukan, file upload cadangan
const sjDb = () => API.sj !== null;
const sjSrc = () => sjDb() ? API.sj : S.sjRows;
const sjUsable = () => sjDb() || (S.sjRows.length > 0 && S.sjRows[0].length >= 7);
function sjByDriver(day) { const m = new Map(); if (!sjUsable()) return m; for (const r of sjRowsView(day)) { if (!m.has(r.id)) m.set(r.id, []); m.get(r.id).push(r); } return m; }
function sjCell(id, m) {
  const rows = m.get(id);
  if (!rows || !rows.length) return `<span class="note" title="${sjUsable() ? 'Tidak ada SJ outstanding' : 'Data SJ belum diupload'}">-</span>`;
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
const LOGOS = {"A":"<svg class=\"lg\" viewBox=\"0 0 24 24\"><g class=\"t\" transform=\"translate(0.4 10.6) scale(.48)\" stroke-width=\"3.7\"><rect x=\"1.5\" y=\"4.5\" width=\"11.5\" height=\"9.5\" rx=\"1\"/><path d=\"M5.5 7v4.5M9 7v4.5\" stroke-width=\"2.04\"/><path d=\"M1.5 16.5H13.5\"/><path d=\"M16 16.5V9.2h3.6l2.9 3.1v4.2z\"/><circle cx=\"3.9\" cy=\"19.3\" r=\"1.75\" fill=\"currentColor\" stroke=\"none\"/><circle cx=\"8\" cy=\"19.3\" r=\"1.75\" fill=\"currentColor\" stroke=\"none\"/><circle cx=\"17.5\" cy=\"19.3\" r=\"1.75\" fill=\"currentColor\" stroke=\"none\"/><circle cx=\"21.3\" cy=\"19.3\" r=\"1.75\" fill=\"currentColor\" stroke=\"none\"/></g><g class=\"p\"><circle cx=\"17.2\" cy=\"6.2\" r=\"3.2\"/><path d=\"M11.6 20.6v-3.4c0-3 2.4-4.9 5.6-4.9s5.6 1.9 5.6 4.9v3.4\"/></g></svg>","B":"<svg class=\"lg\" viewBox=\"0 0 24 24\"><g class=\"t\" transform=\"translate(2.2 3.4) scale(.88)\" stroke-width=\"2.3\"><rect x=\"1.5\" y=\"4.5\" width=\"11.5\" height=\"9.5\" rx=\"1\"/><path d=\"M5.5 7v4.5M9 7v4.5\" stroke-width=\"1.26\"/><path d=\"M1.5 16.5H13.5\"/><path d=\"M16 16.5V9.2h3.6l2.9 3.1v4.2z\"/><circle cx=\"3.9\" cy=\"19.3\" r=\"1.75\" fill=\"currentColor\" stroke=\"none\"/><circle cx=\"8\" cy=\"19.3\" r=\"1.75\" fill=\"currentColor\" stroke=\"none\"/><circle cx=\"17.5\" cy=\"19.3\" r=\"1.75\" fill=\"currentColor\" stroke=\"none\"/><circle cx=\"21.3\" cy=\"19.3\" r=\"1.75\" fill=\"currentColor\" stroke=\"none\"/></g><g class=\"p\" style=\"fill:var(--lg-bg,#E7EEFA)\"><circle cx=\"8.2\" cy=\"7.6\" r=\"3.4\"/><path d=\"M2.2 21.6v-3c0-3.2 2.7-5.2 6-5.2s6 2 6 5.2v3z\"/></g></svg>","C":"<svg class=\"lg\" viewBox=\"0 0 24 24\"><g class=\"t\" style=\"fill:var(--lg-bg,#E7EEFA)\"><circle cx=\"20.4\" cy=\"10.6\" r=\"1.8\"/><path d=\"M17.3 19.4v-1.6c0-1.7 1.3-2.7 3.1-2.7s3.1 1 3.1 2.7v1.6z\"/></g><g class=\"t\" style=\"fill:var(--lg-bg,#E7EEFA)\"><circle cx=\"14.8\" cy=\"9.2\" r=\"2.3\"/><path d=\"M10.6 20.2v-2.3c0-2.2 1.9-3.6 4.2-3.6s4.2 1.4 4.2 3.6v2.3z\"/></g><g class=\"p\" style=\"fill:var(--lg-bg,#E7EEFA)\"><circle cx=\"7.6\" cy=\"7.6\" r=\"3.3\"/><path d=\"M1.8 21.6v-3.3c0-3 2.5-5 5.8-5s5.8 2 5.8 5v3.3z\"/></g></svg>","D":"<svg class=\"lg\" viewBox=\"0 0 24 24\"><g class=\"p\"><path d=\"M10.6 22.2s7.4-6.2 7.4-12.2a7.4 7.4 0 10-14.8 0c0 6 7.4 12.2 7.4 12.2z\"/><circle cx=\"10.6\" cy=\"8.8\" r=\"2.5\"/><path d=\"M6.4 14.8c.6-2.2 2.2-3.2 4.2-3.2s3.6 1 4.2 3.2\"/></g><g class=\"t\"><circle cx=\"21.2\" cy=\"20.8\" r=\"1.2\" fill=\"currentColor\"/><circle cx=\"21.2\" cy=\"16.4\" r=\"1.2\" fill=\"currentColor\"/><circle cx=\"21.2\" cy=\"12\" r=\"1.2\" fill=\"currentColor\"/></g></svg>"};
const LOGO_NAMES = { A: 'A · Driver besar', B: 'B · Driver di depan truk', C: 'C · Antrian driver', D: 'D · Pin lokasi + driver' };
function applyLogo() { const b = $('#brandIcon'); if (!b) return; const k = LOGOS[S.settings.logo] ? S.settings.logo : 'A'; if (b.dataset.logo !== k) { b.innerHTML = LOGOS[k]; b.dataset.logo = k; } }
function applySettings() { S.settings = { ...DEFAULTS, ...((S.config && S.config.settings) || {}) }; applyLogo(); }

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
  DB.doc('app/config').onSnapshot(s => { S.config = s.exists ? s.data() : {}; applySettings(); rebuildShip(); S.loaded.config = true; renderAllData(); afterLoad(); }, onErr);
  DB.collection('dots').onSnapshot(s => { const c = collectChunks(s); S.dotsRows = c.rows; S.dotsIds = c.ids; rebuildShip(); S.loaded.dots = true; renderQueueSafe(); updateDashboard(); refreshOpenViews(); }, onErr);
  DB.collection('p2h').onSnapshot(s => { const c = collectChunks(s); S.p2hRows = c.rows; S.p2hIds = c.ids; S.p2h = new Map(c.rows.map(r => [r[0], r[1]])); S.loaded.p2h = true; renderQueueSafe(); renderMasterUnit(); refreshOpenViews(); }, onErr);
  DB.collection('klinik').onSnapshot(s => { const c = collectChunks(s); S.klinikRows = c.rows; S.klinikIds = c.ids; S.klinik = new Map(c.rows.map(r => [r[0], r[1]])); S.loaded.klinik = true; renderQueueSafe(); renderMasterDriver(); refreshOpenViews(); }, onErr);
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
   KUNCI (lease): agar proses ganti tanggal tidak dijalankan ganda
   ===================================================================== */
async function tryLease(name, ttlMs) {
  try { const r = await DB.doc('app/' + name).acquire({ holder: CID, ttlMs }); return !!r.acquired; } catch (e) { return false; }
}

/* =====================================================================
   LIST DRIVER & LIST ANTRIAN
   ===================================================================== */
function renderListDriver() {
  const q = $('#searchDriver').value.trim().toLowerCase();
  const inQueue = new Set(antrian().map(a => a.id));
  const rows = driverListToday().filter(d => !inQueue.has(d.id_driver) && (d.id_driver.toLowerCase().includes(q) || d.nama.toLowerCase().includes(q)) && dfOk(d));
  const rank = d => (d.status_lokasi === 'AVA' ? 0 : d.tipe_absen === 'Pool' || d.tipe_absen === 'On Road' ? 1 : d.jam_absen ? 2 : 3);   // AVA, hadir/OR, izin/sakit/tidak hadir, belum absen
  rows.sort((a, b) => rank(a) - rank(b));
  $('#badge-total-absen').textContent = rows.length + ' Driver';
  $('#tbDriver').innerHTML = rows.length ? rows.map((d, i) => {
    const ava = d.status_lokasi === 'AVA', open = DD_OPEN.has(d.id_driver);
    const act = ava ? `<button class="ibtn add" data-act="add" data-id="${esc(d.id_driver)}" title="Masukan ke antrian" aria-label="Masukan ke antrian">${IC_ADD}</button>` : `<span class="na-ic">${IC_BAN}</span>`;
    const dd = `<button class="ibtn dd${open ? ' open' : ''}" data-act="dd" data-id="${esc(d.id_driver)}" title="Detail absen" aria-label="Detail absen" aria-expanded="${open}">${IC_DD}</button>`;
    return `<tr${open ? ' class="dd-open"' : ''}><td class="num">${i + 1}</td><td class="l">${esc(d.id_driver)}</td><td class="l nm" title="${esc(d.nama)}">${esc(d.nama)}</td>` +
      `<td class="mono">${d.jam_absen ? esc(d.jam_absen) : '-'}</td><td><span class="pill ${ava ? 'c-OR' : 'c-O'}">${esc(d.status_lokasi)}</span></td><td><span class="act2">${act}${dd}</span></td></tr>` +
      (open ? `<tr class="dd-row"><td colspan="6">${driverDetailHtml(d)}</td></tr>` : '');
  }).join('') : `<tr><td colspan="6" class="empty-row">${!S.loaded.api ? 'Memuat data driver dari database…' : !API.ok && !API.drivers.length ? 'Data driver tidak bisa dimuat: ' + esc(API.err) : dfActive() || DF_STATUS !== 'all' ? 'Tidak ada driver yang cocok dengan filter' : 'Tidak ada driver'}</td></tr>`;
}
// filter List Driver (pop up): pilihan kosong dalam satu field = semua
const tipeDriver = id => { const m = /^([A-Z]+)-/.exec(String(id).toUpperCase()); return m ? m[1] : ''; };   // DM-00045 -> DM
const DF_FIELDS = [
  ['drv', 'Tipe Driver', [['DM', 'DM'], ['CD', 'CD'], ['DOS', 'DOS']], d => tipeDriver(d.id_driver)],
  ['tipe', 'Tipe Absensi', [['Pool', 'Pool'], ['On Road', 'On Road'], ['Izin', 'Izin'], ['Sakit', 'Sakit'], ['Tidak Hadir', 'Tidak Hadir'], ['Belum Absen', 'Belum']], d => d.tipe_absen],
  ['ship', 'Last Shipment', [['Ready', 'Ready'], ['On Progress', 'On Progress'], ['Closed', 'Closed'], ['-', 'Tidak ada']], d => d.last_loc || '-']
];
const DF = Object.fromEntries(DF_FIELDS.map(f => [f[0], new Set()]));
let DF_STATUS = 'all';   // dropdown Status Driver di toolbar: all / AVA / NOT AVA
const dfOk = d => (DF_STATUS === 'all' || d.status_lokasi === DF_STATUS) && DF_FIELDS.every(([k, , , get]) => !DF[k].size || DF[k].has(get(d)));
const dfActive = () => DF_FIELDS.reduce((n, [k]) => n + DF[k].size, 0);
function renderDriverFilter() {
  $('#dfBody').innerHTML = DF_FIELDS.map(([k, l, opts]) => `<div class="df-g" role="group" aria-label="${l}"><div class="df-l">${l}</div><div class="df-opts">` +
    opts.map(([v, t]) => `<label class="df-o"><input type="checkbox" data-k="${k}" value="${esc(v)}"${DF[k].has(v) ? ' checked' : ''}>${esc(t)}</label>`).join('') + '</div></div>').join('');
  dfBadge();
}
function dfBadge() { const n = dfActive(); $('#dfCount').textContent = n; $('#dfCount').hidden = !n; $('#btnDF').classList.toggle('on', n > 0); }
function toggleDF(open) { const p = $('#dfPanel'); if (open == null) open = p.hidden; p.hidden = !open; $('#btnDF').setAttribute('aria-expanded', open); }
const DD_OPEN = new Set();
const IC_DD = '<svg class="ic" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>';
// "2026-10-30 01:16" -> "30-Oct 01:16"
function fmtApiDT(s) { const m = /^(\d{4})-(\d{2})-(\d{2})[ T](\d{2}:\d{2})/.exec(s || ''); return m ? fmtDM(ymd(+m[1], +m[2], +m[3])) + ' ' + m[4] : '-'; }
const TIPE_CLS = { 'Pool': 'c-OR', 'On Road': 'c-AIS', 'Izin': 'c-WR', 'Sakit': 'c-WR', 'Tidak Hadir': 'c-NG' };
const shipPill = s => `<span class="pill ${s === 'Closed' ? 'c-OR' : s === 'On Progress' ? 'c-WR' : 'c-AIS'}">${esc(s)}</span>`;
function driverDetailHtml(d) {
  const it = (ok, k, v) => `<div class="dd-it"><input type="checkbox" tabindex="-1" aria-hidden="true"${ok ? ' checked' : ''}><span class="dd-k">${k}</span><span class="dd-v">${v}</span></div>`;
  const tipe = TIPE_CLS[d.tipe_absen] ? `<span class="pill ${TIPE_CLS[d.tipe_absen]}">${esc(d.tipe_absen)}</span>` : '<span class="note">Belum absen</span>';
  const loc = d.last_loc
    ? `${shipPill(d.last_loc)}<span class="note"><span class="dd-s">${esc(d.shipment_number)}</span> · <span class="dd-s">${esc(d.nopol || '-')}</span> · <span class="dd-s">tender ${fmtApiDT(d.tender_date)}</span>${d.closed_date ? ` · <span class="dd-s">closed ${fmtApiDT(d.closed_date)}</span>` : ''}</span>`
    : '<span class="note">Tidak ada shipment 7 hari terakhir</span>';
  return `<div class="dd-list">${it(d.tipe_absen === 'Pool', 'Tipe Absensi', tipe)}${it(d.last_loc === 'Closed', 'Last Shipment', loc)}</div>`;
}
// ---- Status Order dari database (trx_gps_tracking, lewat /api/drivers) ----
// SUDAH = Last Shipment driver Ready / On Progress (sudah dipairing ke shipment baru) dengan tender tidak lebih awal dari
// waktu ia masuk antrian (shipment lama tidak dihitung). Database tidak terhubung: cadangan dari DOTS Report.
const ORDER_ST = ['Ready', 'On Progress'];
const drvApi = id => API.drivers.find(x => x.id === id);
function orderOf(a) {
  const d = drvApi(a.id);
  if (!d || !ORDER_ST.includes(d.last_shipment) || !d.tender_date) return null;
  return a.seq && Date.parse(d.tender_date) < a.seq - 60000 ? null : d;
}
const orderCount = (a, day) => API.ok ? (orderOf(a) ? 1 : 0) : shipCount(a.id, day);
function orCell(a, day) {
  if (API.ok) {
    const o = orderOf(a);
    return o ? `<span class="pill c-OR" title="${esc(o.shipment_number)} · ${esc(o.last_shipment)} · tender ${esc(fmtApiDT(o.tender_date))}">SUDAH</span>` : '<span class="pill c-O">BELUM</span>';
  }
  const n = shipCount(a.id, day);
  return n > 0 ? `<button class="or-link" data-or="${esc(a.id)}" title="Klik untuk lihat detail shipment"><span class="pill c-OR">SUDAH</span><div class="note">${n} shipment</div></button>` : `<span class="pill c-O">BELUM</span>`;
}
// driver SUDAH diorder dan Nopol Unit masih kosong: diisi otomatis dengan nopol shipment-nya (isian manual tidak ditimpa)
const autoNopolDone = new Set();
async function autoFillNopol() {
  if (!CAN_WRITE || !API.ok) return;
  const items = {};
  for (const a of antrian()) {
    const o = orderOf(a), k = a.id + '|' + (o && o.shipment_number);
    if (!o || !o.nopol || String(a.nopol_unit || '').trim() || autoNopolDone.has(k)) continue;
    autoNopolDone.add(k);
    const unit = activeUnits().find(u => normPlat(u.nopol) === normPlat(o.nopol));
    items[keyOf(a.id)] = { nopol_unit: unit ? unit.nopol : o.nopol, type_unit: unit ? unit.type : '' };
  }
  if (Object.keys(items).length && await dbWrite(() => upsert('app/queue', { items }))) scheduleHistorySave();
}
let queuePending = false;
function renderQueueSafe() {
  const ae = document.activeElement;
  if (ae && ae.tagName === 'INPUT' && $('#tbAntrian').contains(ae)) { queuePending = true; return; }
  queuePending = false; renderListAntrian();
}
const QF = { v: 'all', tipe: 'all' };
// pilihan Tipe Unit diambil dari isi antrian saat ini (+ "Belum diisi" jika ada baris tanpa tipe)
function syncTipeOptions(list) {
  const sel = $('#fltTipe'); if (!sel) return;
  const types = [...new Set(list.map(a => a.type_unit).filter(Boolean))].sort((a, b) => a.localeCompare(b, undefined, { numeric: true }));
  const opts = [['all', 'Semua'], ...types.map(x => [x, x])];
  if (list.some(a => !a.type_unit)) opts.push(['__none', 'Kosong']);
  if (!opts.some(o => o[0] === QF.tipe)) QF.tipe = 'all';
  const key = JSON.stringify(opts);
  if (sel.dataset.key !== key) { sel.innerHTML = opts.map(([v, l]) => `<option value="${esc(v)}">${esc(l)}</option>`).join(''); sel.dataset.key = key; }
  sel.value = QF.tipe;
}
const tipeOk = a => QF.tipe === 'all' || (QF.tipe === '__none' ? !a.type_unit : a.type_unit === QF.tipe);
function renderListAntrian() {
  const tb = $('#tbAntrian'), list = antrian(), t = todaySerial();
  const sjm = sjByDriver();
  syncTipeOptions(list);
  const rows = list.map((d, i) => ({ d, i, or: orderCount(d, t) > 0 })).filter(x => (QF.v === 'all' || (QF.v === 'or' ? x.or : !x.or)) && tipeOk(x.d));
  const fiTxt = QF.v === 'all' && QF.tipe === 'all' ? '' : `Filter aktif: ${rows.length} dari ${list.length} driver`;
  $('#fltInfo').textContent = fiTxt; $('#fltInfo').hidden = !fiTxt;
  if (!list.length) { tb.innerHTML = `<tr><td colspan="14" class="empty-row">Belum ada driver dalam antrian</td></tr>`; updateDashboard(); return; }
  if (!rows.length) { tb.innerHTML = `<tr><td colspan="14" class="empty-row">Tidak ada driver yang cocok dengan filter</td></tr>`; updateDashboard(); return; }
  tb.innerHTML = rows.map(({ d, i }) => `<tr class="${d.isNew ? 'new-row' : ''}">
    <td class="num">${i + 1}</td>
    <td class="l">${esc(d.id)}</td>
    <td class="l nm"><b>${esc(d.nama)}</b></td>
    <td class="l np"><input type="text" class="cell-in" list="unit-list" data-id="${esc(d.id)}" data-f="nopol_unit" value="${esc(d.nopol_unit)}" placeholder="Pilih/ketik..."></td>
    <td class="l"><span class="type-in${d.type_unit ? '' : ' ph'}">${esc(d.type_unit) || 'Otomatis'}</span></td>
    <td class="mono wrap dt">${dtHtml(absenStr(d))}</td>
    <td class="mono wrap dt"><b>${dtHtml(antriStr(d))}</b></td>
    <td>${orCell(d, t)}</td>
    <td>${klinikCell(d.id, true)}</td>
    <td class="wrap">${p2hCell(d.nopol_unit)}</td>
    <td>${sjCell(d.id, sjm)}</td>
    <td class="l ft">${depoSelect(d)}</td>
    <td class="l ft2"><input type="text" class="cell-in" data-id="${esc(d.id)}" data-f="keterangan" value="${esc(d.keterangan)}" placeholder="Keterangan..."></td>
    <td><button class="ibtn del" data-act="del" data-id="${esc(d.id)}" title="Hapus dari antrian" aria-label="Hapus dari antrian">${IC_DEL}</button></td>
  </tr>`).join('');
  list.forEach(d => { if (d.isNew) d.isNew = false; });
  updateDashboard();
}
// pilihan Depo di List Antrian: db_utilisasi / mst_depo ; geo_code (dimuat bersama data driver)
function depoSelect(d) {
  const list = API.depo;
  const opts = d.depo && !list.includes(d.depo) ? [d.depo, ...list] : list;   // isian lama tetap tampil
  return `<select class="cell-in" data-id="${esc(d.id)}" data-f="depo" aria-label="Depo"><option value="">Pilih depo...</option>` +
    opts.map(v => `<option value="${esc(v)}"${v === d.depo ? ' selected' : ''}>${esc(v)}</option>`).join('') + '</select>';
}
async function addToAntrian(id) {
  const d = driverListToday().find(x => x.id_driver === id);
  if (!d || d.status_lokasi !== 'AVA' || antrian().some(a => a.id === id)) return;
  const k = keyOf(id);
  const item = { id, nama: d.nama, jam_absen: d.jam_absen, absen_day: todaySerial(), depo: '', nopol_unit: '', type_unit: '', keterangan: '', waktu_masuk: getCurrentDateTimeStr(), seq: Date.now() };
  S.pending[k] = { ...item, isNew: true };   // tampil seketika, sebelum server menjawab
  renderListDriver(); renderListAntrian();
  const dead = Object.keys(S.queueItems).filter(x => !S.queueItems[x]).length;
  const ok = await dbWrite(async () => {
    if (dead > 100) { const live = {}; antrian().forEach(a => { live[keyOf(a.id)] = stripLocal(a); }); await dbSet('app/queue', { items: live }); }
    else await upsert('app/queue', { items: { [k]: item } });
  });
  if (ok) { scheduleHistorySave(); setTimeout(() => { if (S.pending[k]) { delete S.pending[k]; renderQueueSafe(); renderListDriver(); } }, 5000); }
  else { delete S.pending[k]; renderListDriver(); renderListAntrian(); }
}
const stripLocal = v => { const c = { ...v }; delete c.isNew; return c; };
async function removeFromAntrian(id) {
  const ok = await dbWrite(() => upsert('app/queue', { items: { [keyOf(id)]: null } }));
  if (ok) scheduleHistorySave();
}
function clearAntrian() {
  if (!antrian().length) return;
  askConfirm('Reset antrian?', 'Semua driver di List Antrian akan dihapus untuk <b>semua orang</b> yang membuka aplikasi ini. Riwayat hari ini tetap tersimpan di History Antrian.', 'Reset Antrian', async () => {
    const ok = await dbWrite(() => dbSet('app/queue', { items: {} }));
    if (ok) { toast('Antrian berhasil direset.'); scheduleHistorySave(); }
  });
}
async function updateField(id, field, value) {
  const ok = await dbWrite(() => upsert('app/queue', { items: { [keyOf(id)]: { [field]: value } } }));
  if (ok) scheduleHistorySave();
}
async function handleUnitChange(id, input) {
  const val = input.value.trim();
  const unit = activeUnits().find(u => u.nopol.toUpperCase() === val.toUpperCase());
  const type = unit ? unit.type : '';
  const ti = input.closest('tr').querySelector('.type-in'); if (ti) { ti.textContent = type || 'Otomatis'; ti.classList.toggle('ph', !type); }
  const pc = input.closest('tr').children[9]; if (pc) pc.innerHTML = p2hCell(input.value);
  const ok = await dbWrite(() => upsert('app/queue', { items: { [keyOf(id)]: { nopol_unit: input.value, type_unit: type } } }));
  if (ok) scheduleHistorySave();
}

/* ---------- kartu status ---------- */
function updateDashboard() {
  const t = todaySerial(), inQ = new Set(antrian().map(a => a.id));
  const ava = driverListToday().filter(d => d.status_lokasi === 'AVA' && !inQ.has(d.id_driver)).length;
  const dt = dayTotals(t), hint = 'Upload ulang DOTS Report untuk rincian';
  const q = antrian(), total = q.length, belum = q.filter(a => orderCount(a, t) === 0).length;   // Sudah Diorder = di antrian & Status Order SUDAH
  $('#count-antrean').textContent = total;
  $('#count-belum').textContent = belum;
  $('#count-sudah').textContent = total - belum;
  $('#count-pct').textContent = total ? (Math.round(belum / total * 1000) / 10) + '%' : '-';
  // On Road hari ini: absen On Road (trx_dms_absent.initial = OR) hari ini, ditambah driver yang baru dipairing hari ini
  // (Last Shipment Ready / On Progress dengan tender hari ini); tiap driver dihitung sekali
  const hariIni = dateKey(t);
  $('#count-or').textContent = driverListToday().filter(d => d.tipe_absen === 'On Road' || (ORDER_ST.includes(d.last_loc) && String(d.tender_date || '').slice(0, 10) === hariIni)).length;
  $('#or-detail').innerHTML = '';
  // SPK / SPO: dari database (trx_shipment_preparation); file DOTS hanya cadangan bila database tidak bisa dibaca
  for (const k of ['SPK', 'SPO']) {
    const id = k.toLowerCase(), db = API.spk && API.spk[k], o = db || dt.sp[k], any = db || S.dotsRows.length > 0;
    $('#count-' + id).textContent = o.n;
    $('#' + id + '-detail').innerHTML = !any ? '' : db ? (o.n ? detailHtml(o) : '') : !dt.hasTS || !dt.hasFPT ? hint + ' SPK/SPO (status FPT)' : (o.n && dt.hasType ? detailHtml(o) : '');
  }
  $('#count-ava').textContent = ava;
}

/* =====================================================================
   GANTI TANGGAL
   ===================================================================== */
async function maybeRollover() {
  if (!DBREADY || !S.loaded.config || !CAN_WRITE) return;
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
    antrian().forEach(a => { if (orderCount(a, lastNow) > 0) { upd[keyOf(a.id)] = null; removed++; } });
    if (removed) await dbWrite(() => upsert('app/queue', { items: upd }));
  }
  await dbWrite(() => upsert('app/config', { lastDay: t }));
  toast(removed ? `Ganti tanggal: ${removed} driver OR dikeluarkan dari antrian, sisanya tetap.` : 'Ganti tanggal: antrian dipertahankan, status shipment dihitung ulang untuk hari ini.');
}

/* =====================================================================
   HISTORY ANTRIAN
   ===================================================================== */
function buildSnapshot(day) {
  const q = antrian(), inQ = new Set(q.map(a => a.id)), sjm = sjByDriver(day);
  const sjOf = id => { const rs = sjm.get(id) || [], ag = rs.map(r => r.aging).filter(x => x != null); return [ag.length ? Math.max(...ag) : null, rs.length]; };
  return {
    date: dateKey(day), savedAt: Date.now(),
    drivers: driverListToday().filter(d => !inQ.has(d.id_driver)).map(d => [d.id_driver, d.nama, d.jam_absen || '', d.status_lokasi]),   // data database saat disimpan
    antrian: q.map(a => [a.id, a.nama, a.jam_absen ? absenStr(a) : '', a.depo || '', a.nopol_unit || '', a.type_unit || '', a.keterangan || '', antriStr(a), orderCount(a, day), ...sjOf(a.id), p2hOf(a.nopol_unit), klinikOf(a.id)])
  };
}
async function saveHistory(day) {
  if (!DBREADY || !CAN_WRITE) return;
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
async function loadHistory(k) {
  if (H.cache[k]) return H.cache[k];
  try { const s = await DB.doc('history/' + k).get(); H.cache[k] = s.exists ? s.data() : { date: k, drivers: [], antrian: [], missing: true }; }
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
  if (!dates.includes(H.sel)) H.sel = tk;
  const d = await histData();
  const saved = H.sel === tk ? 'Data hari ini (langsung dari Dashboard Antrian)' : (d.missing ? 'Belum ada data tersimpan untuk tanggal ini' : 'Tersimpan terakhir ' + fmtTs(d.savedAt));
  const dr = d.drivers.map((r, i) => `<tr><td class="num">${i + 1}</td><td class="l">${esc(r[0])}</td><td class="l">${esc(r[1])}</td><td class="mono">${r[3] === 'AVA' ? esc(r[2]) : '-'}</td><td><span class="pill ${r[3] === 'AVA' ? 'c-OR' : 'c-O'}">${esc(r[3])}</span></td></tr>`).join('') || `<tr><td colspan="5" class="empty-row">Tidak ada data</td></tr>`;
  const an = d.antrian.map((r, i) => `<tr><td class="num">${i + 1}</td><td class="l">${esc(r[0])}</td><td class="l">${esc(r[1])}</td><td class="l">${esc(r[4]) || '-'}</td><td class="l">${esc(r[5]) || '-'}</td><td class="mono wrap dt">${dtHtml(r[2])}</td><td class="mono wrap dt">${dtHtml(r[7])}</td><td class="wrap">${r[8] > 0 ? `<span class="pill c-OR">SUDAH</span><div class="note">${r[8]} shipment</div>` : '<span class="pill c-O">BELUM</span>'}</td><td class="wrap">${r[12] ? `<span class="pill ${stClass(r[12])}">${esc(r[12])}</span>` : '<span class="note">-</span>'}</td><td class="wrap">${r[11] ? `<span class="pill ${p2hClass(r[11])}">${esc(r[11])}</span>` : (r[4] ? '<span class="note">-</span>' : '')}</td><td class="wrap">${r[9] == null ? '<span class="note">-</span>' : `<span class="age${r[9] >= S.settings.agingRed ? ' red' : ''}">${r[9]}</span>${r[10] > 1 ? `<div class="note">${r[10]} SJ</div>` : ''}`}</td><td class="l">${esc(r[3]) || '-'}</td><td class="l">${esc(r[6]) || '-'}</td></tr>`).join('') || `<tr><td colspan="13" class="empty-row">Tidak ada driver di antrian pada tanggal ini</td></tr>`;
  el.innerHTML = `<div class="page-h"><h2>History Antrian</h2><p>List Driver &amp; List Antrian beserta remarks per tanggal.</p></div>
  <div class="hist-bar"><label>Tanggal <select id="histSel">${dates.map(x => `<option value="${x}"${x === H.sel ? ' selected' : ''}>${esc(fmtSerial(serialOfKey(x)))}${x === tk ? ' (hari ini)' : ''}</option>`).join('')}</select></label>
    <span class="note">${esc(saved)}</span>
    <button class="btn primary grow" id="histDl">${IC_DL}Download Excel</button></div>
  <div class="panel"><h3>List Antrian<span class="grow"><span class="badge-n">${d.antrian.length} driver</span></span></h3><p class="desc">Kolom sama dengan Dashboard Antrian (tanpa Aksi). Remarks (Depo, Nopol, Keterangan) sesuai yang diisi di Dashboard Antrian; Status Order = jumlah shipment pada tanggal tersebut.</p>
    <div class="tbl-wrap"><table class="t"><thead><tr><th>No</th><th class="l">ID Driver</th><th class="l">Nama</th><th class="l">Nopol Unit</th><th class="l">Tipe Unit</th><th>Absen MTA</th><th>Antri</th><th>Status Order</th><th>Status Klinik</th><th>Status P2H</th><th>Aging SJ Outs.(hari)</th><th class="l">Depo</th><th class="l">Keterangan</th></tr></thead><tbody>${an}</tbody></table></div></div>
  <div class="panel"><h3>List Driver<span class="grow"><span class="badge-n">${d.drivers.length} driver</span></span></h3><p class="desc">Driver yang tidak sedang berada di antrian pada tanggal tersebut.</p>
    <div class="tbl-wrap"><table class="t"><thead><tr><th>No</th><th class="l">ID Driver</th><th class="l">Nama</th><th>Jam Absen</th><th>Status Driver</th></tr></thead><tbody>${dr}</tbody></table></div></div>`;
  $('#histSel').onchange = e => { H.sel = e.target.value; renderHistory(); };
  $('#histDl').onclick = () => downloadHistory(d);
}
function downloadHistory(d) {
  if (d.missing) { toast('Belum ada data tersimpan untuk tanggal ini.'); return; }
  const wb = XLSX.utils.book_new();
  const w1 = XLSX.utils.aoa_to_sheet([['No', 'ID Driver', 'Nama', 'Jam Absen', 'Status Driver'], ...d.drivers.map((r, i) => [i + 1, r[0], r[1], r[2], r[3]])]);
  w1['!cols'] = [6, 14, 30, 11, 14].map(w => ({ wch: w }));
  const w2 = XLSX.utils.aoa_to_sheet([['No', 'ID Driver', 'Nama', 'Nopol Unit', 'Tipe Unit', 'Absen MTA', 'Antri', 'Status Order', 'Jumlah Shipment', 'Status Klinik', 'Status P2H', 'Aging SJ Outs.(hari)', 'Depo', 'Keterangan'],
    ...d.antrian.map((r, i) => [i + 1, r[0], r[1], r[4], r[5], r[2], r[7], orLabel(r[8]), r[8], r[12] || '', r[11] || '', r[9] == null ? '' : r[9], r[3], r[6]])]);
  w2['!cols'] = [6, 14, 28, 16, 12, 17, 17, 12, 10, 14, 14, 12, 16, 30].map(w => ({ wch: w }));
  XLSX.utils.book_append_sheet(wb, w2, 'List Antrian');
  XLSX.utils.book_append_sheet(wb, w1, 'List Driver');
  saveWb(wb, 'History Antrian - Quedrive ' + d.date + '.xlsx');
}

/* =====================================================================
   SJ OUTSTANDING (Gantungan Surat Jalan)
   ===================================================================== */
const sjQuery = { q: '' };
// urutan Aging: desc = terlama dulu (bawaan), asc = terbaru dulu; SJ tanpa aging selalu di bawah
const sjSort = { dir: 'desc' };
// hanya SJ milik driver aktif di Master (hari ini), urut aging terbesar (terlama) dulu
function sjRowsView(day) {
  const st = S.settings, t = day == null ? todaySerial() : day, act = new Map(activeDrivers().map(d => [d.id, d])), rows = [];
  for (const r of sjSrc()) {
    if (st.sjPool !== 'ALL' && r[1] !== st.sjPool) continue;
    const dr = act.get(r[5]); if (!dr) continue;
    const aging = st.agingSrc === 'file' && !sjDb() ? r[4] : (r[3] == null ? null : t - r[3]);   // dari database: selalu dari Tgl Tender
    rows.push({ ship: r[0], cust: r[2], tender: r[3], aging, id: dr.id, nama: dr.nama });
  }
  rows.sort((a, b) => (b.aging == null ? -1e9 : b.aging) - (a.aging == null ? -1e9 : a.aging) || a.nama.localeCompare(b.nama) || a.ship.localeCompare(b.ship));
  return rows;
}
// contoh 1 baris (dari data SJ sebelum terhubung ke database) agar halaman SJ Outstanding tetap utuh selama data SJ belum ada;
// hanya untuk halaman ini, tidak ikut ke kolom Aging SJ di List Antrian
const SJ_DUMMY = ['SHELUX20261005-0009', 'POOL NAGRAK TRAILER', 'PT. ELECTROLUX INDONESIA', 46300, -1, 'DM-01166', 'DEDE IBRAHIM'];
function sjDummyRows() { const r = SJ_DUMMY; return [{ ship: r[0], cust: r[2], tender: r[3], aging: S.settings.agingSrc === 'file' ? r[4] : todaySerial() - r[3], id: r[5], nama: r[6] }]; }
const sjAll = () => sjUsable() ? sjRowsView() : sjDummyRows();
// filter Customer (pilihan ganda): sel = null berarti semua customer; selain itu Set berisi nama customer terpilih
const sjFilter = { sel: null, open: false };
const sjIsSel = c => sjFilter.sel === null || sjFilter.sel.has(c);
// sel tetap berupa Set setelah user memilih: customer baru dari refresh data tidak otomatis ikut tercentang
function sjCustomers(all) { const m = new Map(); all.forEach(r => m.set(r.cust, (m.get(r.cust) || 0) + 1)); return [...m].sort((x, y) => x[0].localeCompare(y[0])); }
function sjFilterLabel(custs) {
  const n = custs.length, s = sjFilter.sel;
  if (s === null) return `Semua customer (${n})`;
  const picked = custs.filter(c => s.has(c[0]));
  if (n && picked.length === n) return `Semua customer (${n})`;
  return picked.length === 0 ? 'Tidak ada customer dipilih' : picked.length === 1 ? picked[0][0] : `${picked.length} dari ${n} customer`;
}
function renderSJTable() {
  const st = S.settings, all = sjAll(), custs = sjCustomers(all), q = sjQuery.q.trim().toLowerCase();
  const byCust = all.filter(r => sjIsSel(r.cust));
  const rows = (q ? byCust.filter(r => r.cust.toLowerCase().includes(q) || r.ship.toLowerCase().includes(q) || r.nama.toLowerCase().includes(q) || r.id.toLowerCase().includes(q)) : byCust.slice())
    .sort((a, b) => (a.aging == null) - (b.aging == null) || (sjSort.dir === 'asc' ? a.aging - b.aging : b.aging - a.aging) || a.nama.localeCompare(b.nama) || a.ship.localeCompare(b.ship));
  const red = byCust.filter(r => r.aging != null && r.aging >= st.agingRed).length, meta = S.config.sjMeta;
  $('#sjSummary').innerHTML = (sjUsable() ? '' : `<span class="pill c-WR">Contoh data</span> Data SJ belum ada${API.sjErr ? ' (database SJ: ' + esc(API.sjErr) + ')' : ''}, menampilkan 1 baris contoh · `) + `${byCust.length}${byCust.length !== all.length ? ' dari ' + all.length : ''} SJ belum kembali milik driver aktif${st.sjPool === 'ALL' ? ' (semua pool)' : ' di ' + esc(st.sjPool)} · <b style="color:var(--danger)">${red}</b> aging ≥ ${st.agingRed} hari${sjDb() ? ' · sumber odongnew_db / so.so_trx_document' : meta ? ' · file ' + esc(meta.name) : ''}`;
  const fl = sjFilterLabel(custs), picked = sjFilter.sel === null ? custs.length : custs.filter(c => sjFilter.sel.has(c[0])).length, nb = $('#custCount');
  $('#custBtn').title = 'Customer: ' + fl;
  nb.textContent = picked; nb.hidden = picked === custs.length;
  const none = sjFilter.sel !== null && !custs.some(c => sjFilter.sel.has(c[0]));
  $('#sjTable').innerHTML = `<div class="panel"><div class="tbl-wrap" style="max-height:70vh"><table class="t wrapall"><thead><tr><th>No</th><th class="l">ID Driver</th><th class="l">Nama Driver</th><th class="l">Customer</th><th>Tgl Tender</th><th class="l">No. Shipment</th><th>Aging (Hari)</th></tr></thead><tbody>${
    rows.map((r, i) => `<tr><td class="num">${i + 1}</td><td class="l">${esc(r.id)}</td><td class="l">${esc(r.nama)}</td><td class="l">${esc(r.cust)}</td><td class="num">${fmtDMY(r.tender)}</td><td class="l">${esc(r.ship)}</td><td><span class="age${r.aging != null && r.aging >= st.agingRed ? ' red' : ''}">${r.aging == null ? '-' : r.aging}</span></td></tr>`).join('') || `<tr><td colspan="7" class="empty-row">${none ? 'Tidak ada customer dipilih' : 'Tidak ada SJ outstanding'}</td></tr>`}</tbody></table></div></div>`;
}
function renderSJ() {
  const el = $('#v-sj');
  const custs = sjCustomers(sjAll());
  el.innerHTML = `<div class="page-h"><h2>SJ Outstanding</h2><p id="sjSummary"></p></div>
  <div class="toolbar sjbar">
    <input type="search" id="sjSearch" placeholder="Cari driver, customer, atau shipment..." value="${esc(sjQuery.q)}" aria-label="Cari SJ">
    <button type="button" class="btn" id="sjSortBtn"></button>
    <div class="msel"><button type="button" class="btn msel-btn" id="custBtn" aria-haspopup="listbox" aria-expanded="${sjFilter.open}"><svg class="ic" viewBox="0 0 24 24"><path d="M3 5h18l-7 8v6l-4 2v-8z"/></svg>Filter<span class="df-n" id="custCount" hidden>0</span></button>
      <div class="msel-panel" id="custPanel"${sjFilter.open ? '' : ' hidden'}>
        <input type="search" id="custSearch" placeholder="Cari customer..." aria-label="Cari customer">
        <div class="msel-actions"><button type="button" class="btn" id="custAll">Pilih semua</button><button type="button" class="btn" id="custNone">Kosongkan</button></div>
        <div class="msel-list" id="custList" role="listbox" aria-multiselectable="true">${custs.map(([c, n], i) => `<label class="msel-item" data-i="${i}"><input type="checkbox" data-i="${i}"${sjIsSel(c) ? ' checked' : ''}><span>${esc(c)}</span><em>${n}</em></label>`).join('')}</div>
      </div></div>
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
    renderSJTable();
  };
  $('#custAll').onclick = () => { sjFilter.sel = null; checks().forEach(c => { c.checked = true; }); renderSJTable(); };
  $('#custNone').onclick = () => { sjFilter.sel = new Set(); checks().forEach(c => { c.checked = false; }); renderSJTable(); };
  $('#custSearch').oninput = e => { const q = e.target.value.trim().toLowerCase(); el.querySelectorAll('#custList .msel-item').forEach(l => { l.hidden = q !== '' && !custs[+l.dataset.i][0].toLowerCase().includes(q); }); };
  const s = $('#sjSearch'); s.oninput = () => { sjQuery.q = s.value; renderSJTable(); };
  const sb = $('#sjSortBtn'), sortLbl = () => {
    const asc = sjSort.dir === 'asc';
    sb.innerHTML = `<svg class="ic" viewBox="0 0 24 24"><path d="${asc ? 'M7 20V4M3 8l4-4 4 4' : 'M7 4v16M3 16l4 4 4-4'}"/><path d="M14 6h7M14 12h5M14 18h3"/></svg>Aging: ${asc ? 'Terbaru' : 'Terlama'}`;
    sb.title = asc ? 'Aging terkecil (SJ terbaru) di atas. Klik untuk terlama dulu' : 'Aging terbesar (SJ terlama) di atas. Klik untuk terbaru dulu';
  };
  sortLbl(); sb.onclick = () => { sjSort.dir = sjSort.dir === 'asc' ? 'desc' : 'asc'; sortLbl(); renderSJTable(); };
  renderSJTable();
}
// tutup daftar customer saat klik di luar / tekan Escape
document.addEventListener('click', e => { if (sjFilter.open && !e.target.closest('.msel')) { sjFilter.open = false; const p = $('#custPanel'), b = $('#custBtn'); if (p) p.hidden = true; if (b) b.setAttribute('aria-expanded', 'false'); } });
document.addEventListener('keydown', e => { if (e.key === 'Escape' && sjFilter.open) { sjFilter.open = false; const p = $('#custPanel'), b = $('#custBtn'); if (p) p.hidden = true; if (b) { b.setAttribute('aria-expanded', 'false'); b.focus(); } } });

/* =====================================================================
   MASTER
   ===================================================================== */
const MF = { driver: 'all' };   // filter Status Driver (Aktif / Nonaktif)
// Status Driver dari DB2 puninar_dms / v_driver_career_utilisasi ; is_active (TRUE = Aktif), dicocokkan lewat nik
const drvStatus = d => d.aktif === true ? 'Aktif' : d.aktif === false ? 'Nonaktif' : null;
const SRC_NOTE = ' · sumber DB1 / db_utilisasi';
// filter Master Driver (pop up): pilihan kosong dalam satu field = semua
const MDF_FIELDS = [   // [kunci, judul, pilihan, nilai per driver, menunggu sumber data]
  ['tipe', 'Tipe Driver', [['DM', 'DM'], ['CD', 'CD'], ['DOS', 'DOS']], d => tipeDriver(d.id_driver), false],
  ['klinik', 'Status Klinik', [['FIT', 'Fit'], ['UNFIT', 'Unfit'], ['-', 'Belum ada data']], d => klinikOf(d.id_driver).toUpperCase() || '-', false],
  ['ship', 'Last Shipment Status', [['On Progress', 'On Progress'], ['Ready', 'Ready'], ['Closed', 'Closed'], ['-', 'Tidak ada shipment']], d => d.last_loc || '-', false]
];
const MDF = Object.fromEntries(MDF_FIELDS.map(f => [f[0], new Set()]));
// field yang menunggu sumber data tetap bisa diklik, tetapi belum memengaruhi tabel
const mdfOk = d => MDF_FIELDS.every(([k, , , get, wait]) => wait || !MDF[k].size || MDF[k].has(get(d)));
const mdfActive = () => MDF_FIELDS.reduce((n, [k]) => n + MDF[k].size, 0);
function renderMDFilter() {
  $('#mdfBody').innerHTML = MDF_FIELDS.map(([k, l, opts, , wait]) => `<div class="df-g" role="group" aria-label="${l}"><div class="df-l">${l}${wait ? ' · belum memengaruhi tabel' : ''}</div><div class="df-opts">` +
    opts.map(([v, t]) => `<label class="df-o"><input type="checkbox" data-k="${k}" value="${esc(v)}"${MDF[k].has(v) ? ' checked' : ''}>${esc(t)}</label>`).join('') + '</div></div>').join('');
  mdfBadge();
}
function mdfBadge() { const n = mdfActive(); $('#mdfCount').textContent = n; $('#mdfCount').hidden = !n; $('#btnMDF').classList.toggle('on', n > 0); }
function toggleMDF(open) { const p = $('#mdfPanel'); if (open == null) open = p.hidden; p.hidden = !open; $('#btnMDF').setAttribute('aria-expanded', open); }
// Last Location: tombol ke live tracking Puninar, nopol dalam Base64 (B-9028-TEJ -> Qi05MDI4LVRFSg==)
const TRACK_URL = 'https://app.puninar.com/3/public/live-tracking?licenseNumber=';
function lastLocCell(d) {
  if (d.tracking_nopol) {
    const b64 = btoa(d.tracking_nopol).replace(/\+/g, '%2B').replace(/\//g, '%2F');
    return `<td><a class="btn trk" href="${TRACK_URL}${b64}" target="_blank" rel="noopener" title="Live tracking ${esc(d.tracking_nopol)} (${esc(d.last_loc)})">Lokasi</a></td>`;
  }
  const why = d.nopol_driver_lain ? `Nopol ${d.nopol || ''} sekarang dipakai shipment driver lain (${d.nopol_driver_lain})`
    : d.last_loc === 'Closed' ? 'Shipment terakhir sudah Closed' : d.last_loc ? 'Nopol shipment tidak ada' : 'Tidak ada shipment 7 hari terakhir';
  return `<td class="note" title="${esc(why)}">-</td>`;
}
// urutan default Master Driver: DM, DOS, CD, lalu ID lain; di dalam kelompok urut ID driver
const MD_TIPE_URUT = ['DM', 'DOS', 'CD'];
const mdRank = id => { const i = MD_TIPE_URUT.indexOf(tipeDriver(id)); return i < 0 ? MD_TIPE_URUT.length : i; };
function renderMasterDriver() {
  const all = driverListToday(), q = $('#searchMD').value.trim().toLowerCase();
  const list = all.filter(d => (d.id_driver.toLowerCase().includes(q) || d.nama.toLowerCase().includes(q)) && mdfOk(d) && (MF.driver === 'all' || drvStatus(d) === MF.driver))
    .sort((a, b) => mdRank(a.id_driver) - mdRank(b.id_driver) || a.id_driver.localeCompare(b.id_driver));
  $('#tbMD').innerHTML = list.length ? list.map((d, i) => `<tr><td class="num">${i + 1}</td><td class="l">${esc(d.id_driver)}</td><td class="l">${esc(d.nama)}</td>` +
    (drvStatus(d) ? `<td><span class="pill ${d.aktif ? 'c-OR' : 'c-O'}">${drvStatus(d)}</span></td>` : '<td><span class="note" title="Driver tidak ditemukan di puninar_dms atau database belum terhubung">?</span></td>') +
    `<td>${klinikCell(d.id_driver)}</td>` + (d.last_loc ? `<td>${shipPill(d.last_loc)}</td>` : '<td class="note" title="Tidak ada shipment 7 hari terakhir">-</td>') +
    (d.last_unloading ? `<td class="l" title="Tiba ${fmtApiDT(d.last_unloading_at)}">${esc(d.last_unloading)}</td>` : '<td class="note" title="Tidak ada stop bongkar (D) dalam 7 hari terakhir">-</td>') +
    lastLocCell(d) + '</tr>').join('')   // Status Klinik, Last Shipment Status, Last Unloading, Last Location
    : `<tr><td colspan="8" class="empty-row">${!S.loaded.api ? 'Memuat data driver…' : !all.length ? 'Data driver tidak bisa dimuat: ' + esc(API.err) : 'Tidak ada driver yang cocok dengan pencarian / filter'}</td></tr>`;
  $('#cntMD').textContent = all.length + ' driver' + (list.length < all.length ? ` · menampilkan ${list.length}` : '') + SRC_NOTE + ' + puninar_dms (Status Driver)';
}
// filter Master Unit (pop up): Status P2H; tidak dicentang = semua
const MUF_P2H = [['READY', 'READY'], ['BREAKDOWN', 'BREAKDOWN'], ['PREVENTIVE', 'PREVENTIVE'], ['-', 'Tidak ada data P2H']];
const MUF = new Set();
function renderMUFilter() {
  $('#mufBody').innerHTML = '<div class="df-g" role="group" aria-label="Status P2H"><div class="df-l">Status P2H</div><div class="df-opts">' +
    MUF_P2H.map(([v, t]) => `<label class="df-o"><input type="checkbox" value="${esc(v)}"${MUF.has(v) ? ' checked' : ''}>${esc(t)}</label>`).join('') + '</div></div>';
  mufBadge();
}
function mufBadge() { $('#mufCount').textContent = MUF.size; $('#mufCount').hidden = !MUF.size; $('#btnMUF').classList.toggle('on', MUF.size > 0); }
function toggleMUF(open) { const p = $('#mufPanel'); if (open == null) open = p.hidden; p.hidden = !open; $('#btnMUF').setAttribute('aria-expanded', open); }
function renderMasterUnit() {
  const all = activeUnits(), q = $('#searchMU').value.trim().toLowerCase();
  const units = all.filter(u => [u.nopol, u.type, u.pool, u.customer].some(v => String(v || '').toLowerCase().includes(q)) && (!MUF.size || MUF.has(p2hOf(u.nopol) || '-')));
  $('#tbMU').innerHTML = units.length ? units.map((u, i) => `<tr><td class="num">${i + 1}</td><td class="l">${esc(u.nopol)}</td><td class="l">${esc(u.type || '-')}</td><td class="l">${esc(u.pool || '-')}</td><td class="l">${esc(u.customer || '-')}</td><td class="wrap">${p2hCell(u.nopol)}</td></tr>`).join('')
    : `<tr><td colspan="6" class="empty-row">${!S.loaded.api ? 'Memuat data unit…' : !API.ok ? 'Data unit tidak bisa dimuat: ' + esc(API.err) : all.length ? 'Tidak ada unit yang cocok dengan pencarian / filter' : 'Tidak ada unit aktif'}</td></tr>`;
  $('#cntMU').textContent = all.length + ' unit aktif (MST_UNIT_TAHUN)' + (units.length < all.length ? ` · menampilkan ${units.length}` : '') + SRC_NOTE + ' + puninar_p2h (Status P2H)';
  $('#unit-list').innerHTML = all.map(u => `<option value="${esc(u.nopol)}">${esc(u.type || '')}</option>`).join('');
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
function readAoa(file) {
  return new Promise((resolve, reject) => {
    const r = new FileReader();
    r.onload = e => { try { const wb = XLSX.read(e.target.result, { type: 'array' }); resolve(XLSX.utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]], { header: 1, raw: true, defval: null })); } catch (err) { reject(err); } };
    r.onerror = () => reject(new Error('baca file gagal'));
    r.readAsArrayBuffer(file);
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
async function ingestFile(file, forced) {
  let aoa; try { aoa = await readAoa(file); } catch (e) { return `${file.name}: file tidak dapat dibaca`; }
  if (forced === 'p2h' || forced === 'klinik') {
    const L = LOOKUP[forced], hp = findLookupCols(aoa, false, L);
    return hp ? ingestLookup(forced, aoa, hp, file.name) : `${file.name}: kolom ${L.keyLabel} dan ${L.label} tidak ditemukan (judul kolom contoh: "${L.keyLabel}" dan "${L.label}")`;
  }
  let h;
  if ((h = findHeaderRow(aoa, ['SHIPMENT', 'TANGGAL TENDER', 'STATUS FPT', 'ID DRIVER 1']))) return ingestDots(aoa, h, file.name);
  if ((h = findHeaderRow(aoa, ['SHIPMENT NO', 'AGING', 'TENDER TIME', 'CUSTOMER']))) return ingestSJ(aoa, h, file.name);
  for (const k of ['p2h', 'klinik']) { const hp = findLookupCols(aoa, true, LOOKUP[k]); if (hp) return ingestLookup(k, aoa, hp, file.name); }
  return `${file.name}: tidak dikenali (bukan DOTS Report, Download Detail SJ, data Status P2H Unit, atau data Status Klinik Driver)`;
}
async function ingestDots(aoa, h, name) {
  const c = k => (h.idx[k] == null ? -1 : h.idx[k]);
  const I = { s: c('SHIPMENT'), d1: c('ID DRIVER 1'), d2: c('ID DRIVER 2'), tt: c('TANGGAL TENDER'), ex: c('EXECUTION DATE'), fpt: c('STATUS FPT'), cu: c('CUSTOMER'), no: c('NOMOR ORDER'), ob: c('ORDER BASE'), rt: c('NAMA RUTE'), tp: c('TIPE TRUCK'), ts: c('TIPE SHIPMENT') };
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
    rows.push([ship, d1, d2, t.d, t.m, e ? e.d : null, I.cu >= 0 ? clean(a[I.cu]) : '', (I.ob >= 0 ? clean(a[I.ob]) : '') || (I.no >= 0 ? clean(a[I.no]) : ''), I.rt >= 0 ? clean(a[I.rt]) : '', I.tp >= 0 ? clean(a[I.tp]) : '', I.ts >= 0 ? clean(a[I.ts]).toUpperCase() : '', fp]);
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
  const cfg = { dots: ['dots', 'dotsMeta', 'DOTS Report'], sj: ['sj', 'sjMeta', 'Download Detail SJ'], p2h: ['p2h', 'p2hMeta', 'Status P2H Unit'], klinik: ['klinik', 'klinikMeta', 'Status Klinik Driver'] }[k];
  askConfirm('Hapus data?', `Data ${cfg[2]} akan dihapus untuk <b>semua orang</b>.`, 'Hapus', async () => {
    const ids = k === 'dots' ? S.dotsIds : k === 'sj' ? S.sjIds : k === 'p2h' ? S.p2hIds : S.klinikIds;
    const ok = await dbWrite(async () => { for (const id of ids) await dbDel(cfg[0] + '/' + id); await upsert('app/config', { [cfg[1]]: null }); });
    if (ok) { toast('Data dihapus.'); if (k === 'p2h' || k === 'klinik') scheduleHistorySave(); }
  });
}
let slotTarget = null;
function pickSlot(k) { slotTarget = k; const i = $('#fileSlot'); i.value = ''; i.click(); }
async function setSetting(key, val) {
  const ok = await dbWrite(() => upsert('app/config', { settings: { [key]: val } }));
  if (ok && ['cutoff', 'cutoffOn', 'sjPool', 'agingSrc', 'agingRed'].includes(key)) scheduleHistorySave();
}

/* ---------- tampilan Upload & Setting ---------- */
function renderData() {
  const el = $('#v-data'), st = S.settings, dm = S.config.dotsMeta, sm = S.config.sjMeta;
  const pools = [...new Set([...sjSrc().map(r => r[1]).filter(Boolean), DEFAULTS.sjPool])].sort();
  const slot = (k, n, title, ex, need, meta, info) => `<div class="slot"><div class="slot-h"><span class="n">${n}</span>${title}</div><div class="fn">Contoh: ${esc(ex)}<br>Kolom: ${esc(need)}</div>
    ${meta ? `<div class="ok">✓ ${esc(meta.name)}</div><div class="fn">${info}<br>Diupload ${fmtTs(meta.at)}</div>` : `<div class="miss">Belum diupload</div>`}
    <div class="slot-actions"><button class="btn" data-pick="${k}">${meta ? 'Ganti file' : 'Pilih file'}</button>${meta ? `<button class="btn" data-clear="${k}">Hapus</button>` : ''}</div></div>`;
  const pm = S.config.p2hMeta, km = S.config.klinikMeta;
  const fmtRange = (a, b) => (a == null ? '' : `, tender ${fmtSerial(a)} s/d ${fmtSerial(b)}`);
  el.innerHTML = `<div class="page-h"><h2>Upload &amp; Setting</h2><p>Data yang diupload dan setting di sini tersimpan di server dan dipakai bersama oleh semua yang membuka aplikasi ini.</p></div>
  <div class="drop" id="drop" tabindex="0" role="button" aria-label="Pilih file untuk diupload"><b>Tarik file harian ke sini, atau klik untuk memilih</b><div class="note">DOTS Report, Download Detail SJ, data Status P2H Unit, dan data Status Klinik Driver dikenali otomatis dari nama kolomnya. Bisa pilih beberapa file sekaligus. Jika file P2H atau Klinik tidak terdeteksi, pakai tombol \"Pilih file\" di kartu masing-masing.</div></div>
  <div class="panel"><h3>File harian</h3><p class="desc">Upload ulang kapan saja untuk memperbarui; data lama akan diganti. SJ dan P2H sudah diambil dari database, jadi file keduanya hanya <b>cadangan</b> bila database tidak bisa dibaca.</p><div class="slots">
    ${slot('dots', 1, 'DOTS Report', 'DOTS_Report_ALL_06-Oct-2026_14_00.xlsx', 'SHIPMENT, ID DRIVER 1/2, TANGGAL TENDER, EXECUTION DATE, STATUS FPT, CUSTOMER, ORDER BASE, NAMA RUTE, TIPE TRUCK, TIPE SHIPMENT', dm, dm ? `${dm.valid} shipment valid dari ${dm.total} baris (${dm.cancel} CANCEL, ${dm.noTender} tanpa tender diabaikan)${fmtRange(dm.tMin, dm.tMax)}${dm.replan != null ? `. Kotak Shipment SPK/SPO hanya menghitung NEW &amp; CARRY OVER, jadi ${dm.replan} REPLAN dan CANCEL tidak dihitung` : ''}` : '')}
    ${slot('sj', 2, 'Download Detail SJ (cadangan)', '_Download_Detail_SJ.xlsx', 'Shipment No, Pool, Customer, NID, Driver, Tender Time, Aging, Status', sm, sm ? `${sm.kept} SJ outstanding dari ${sm.total} baris, ${st.sjPool === 'ALL' ? 'semua pool' : S.sjRows.filter(r => r[1] === st.sjPool).length + ' di ' + esc(st.sjPool)}; yang ditampilkan hanya milik driver aktif` : '')}
    ${slot('p2h', 3, 'Status P2H Unit (cadangan)', '(nama file bebas)', 'Nopol, Status P2H (opsional: Tanggal untuk status terbaru)', pm, pm ? `${pm.units} unit memiliki status P2H${pm.byDate ? ' (status terbaru per nopol)' : ''}. Kolom terbaca: Nopol = "${esc(pm.colN)}", Status = "${esc(pm.colS)}". Sebaran: ${(pm.dist || []).map(d => esc(d[0]) + ' ' + d[1]).join(', ')}` : '')}
    ${slot('klinik', 4, 'Status Klinik Driver (cadangan)', '(nama file bebas)', 'ID Driver, Status Klinik (opsional: Tanggal untuk status terbaru)', km, km ? `${km.units} driver memiliki status klinik${km.byDate ? ' (status terbaru per driver)' : ''}. Kolom terbaca: ID Driver = "${esc(km.colN)}", Status = "${esc(km.colS)}". Sebaran: ${(km.dist || []).map(d => esc(d[0]) + ' ' + d[1]).join(', ')}` : '')}
  </div></div>
  <div class="panel"><h3>Data dari database</h3><p class="desc">Diambil langsung dari database lewat backend PHP, diperbarui otomatis tiap 30 detik. Tidak perlu upload.</p><div class="slots">
    <div class="slot"><div class="slot-h"><span class="n">1</span>Master Driver</div><div class="${API.ok ? 'ok' : 'miss'}">${API.ok ? '✓ ' + API.drivers.length + ' driver' : 'Belum terhubung'}</div><div class="fn">DB1 / db_utilisasi / trx_dms_absent (termasuk Status Klinik: ${API.klinik.size} driver)</div></div>
    <div class="slot"><div class="slot-h"><span class="n">2</span>Master Unit</div><div class="${API.ok ? 'ok' : 'miss'}">${API.ok ? '✓ ' + API.units.length + ' unit aktif' : 'Belum terhubung'}</div><div class="fn">DB1 / db_utilisasi / mst_lookup_lines (MST_UNIT_TAHUN, ACTIVE)</div></div>
    <div class="slot"><div class="slot-h"><span class="n">3</span>Status P2H</div><div class="${API.p2h.size ? 'ok' : 'miss'}">${API.p2h.size ? '✓ ' + API.p2h.size + ' unit punya P2H (30 hari)' : 'Belum terhubung'}</div><div class="fn">puninar_p2h / check_records</div></div>
    <div class="slot"><div class="slot-h"><span class="n">4</span>SJ Outstanding</div><div class="${sjDb() ? 'ok' : 'miss'}">${sjDb() ? '✓ ' + API.sj.length + ' SJ belum kembali (semua pool)' : 'Belum terhubung' + (API.sjErr ? ': ' + esc(API.sjErr) : '')}</div><div class="fn">odongnew_db / so.so_trx_document</div></div>
    <div class="slot"><div class="slot-h"><span class="n">5</span>Depo</div><div class="${API.depo.length ? 'ok' : 'miss'}">${API.depo.length ? '✓ ' + API.depo.length + ' depo' : 'Belum terhubung'}</div><div class="fn">DB1 / db_utilisasi / mst_depo</div></div>
    <div class="slot"><div class="slot-h"><span class="n">6</span>Penyimpanan dashboard</div><div class="${MODE === 'server' ? 'ok' : 'miss'}">${MODE === 'server' ? '✓ Database QUEDRIVE (MySQL)' : 'Tidak terhubung (baca saja)'}</div><div class="fn">qd_antrian, qd_setting, qd_history, qd_upload</div></div>
  </div></div>
  <div class="panel"><h3>Parameter</h3><p class="desc">Perubahan langsung dipakai semua orang.</p><div class="set-grid">
    <label>Aturan tanggal onroad (OR &amp; shipment)<select id="setCutOn"><option value="off"${st.cutoffOn ? '' : ' selected'}>Actual: tanggal terbesar Execution Date &amp; Tanggal Tender</option><option value="on"${st.cutoffOn ? ' selected' : ''}>Pakai cut-off tender (H+1)</option></select><small>Default actual eksekusi. Jika cut-off dipakai, tender yang lewat jam cut-off dihitung hari berikutnya sebelum dibandingkan dengan Execution Date.</small></label>
    <label>Jam cut-off tender<input type="time" id="setCut" value="${esc(st.cutoff)}"${st.cutoffOn ? '' : ' disabled'}><small>Hanya berlaku jika aturan di sebelah memakai cut-off.</small></label>
    <label>Pool untuk SJ Outstanding<select id="setPool">${pools.map(p => `<option value="${esc(p)}"${p === st.sjPool ? ' selected' : ''}>${esc(p)}</option>`).join('')}<option value="ALL"${st.sjPool === 'ALL' ? ' selected' : ''}>Semua pool</option></select><small>Daftar pool diambil dari file Download Detail SJ.</small></label>
    <label>Sumber Aging SJ<select id="setAge"><option value="tender"${st.agingSrc !== 'file' ? ' selected' : ''}>Hitung dari Tgl Tender ke hari ini</option><option value="file"${st.agingSrc === 'file' ? ' selected' : ''}>Pakai kolom Aging di file SJ</option></select><small>Kolom Aging di file SJ = selisih hari terhadap Target Date (negatif = belum jatuh tempo).</small></label>
    <label>Batas merah Aging (hari)<input type="number" min="0" max="60" id="setRed" value="${st.agingRed}"><small>Aging sama dengan atau lebih dari angka ini diberi warna merah.</small></label>
    <label>Saat ganti tanggal, driver yang sudah OR<select id="setRoll"><option value="keep"${st.orRollover !== 'remove' ? ' selected' : ''}>Tetap di antrian</option><option value="remove"${st.orRollover === 'remove' ? ' selected' : ''}>Dikeluarkan dari antrian</option></select><small>Driver yang belum OR selalu tetap di antrian. Riwayat harian tersimpan di History Antrian.</small></label>
    <div class="logo-wrap"><div class="logo-title">Logo aplikasi</div><div class="logo-opts" role="radiogroup" aria-label="Pilih logo aplikasi">${Object.keys(LOGOS).map(k => `<button type="button" class="logo-opt" role="radio" aria-checked="${st.logo === k}" aria-pressed="${st.logo === k}" data-logo="${k}" title="${esc(LOGO_NAMES[k])}">${LOGOS[k]}<span>${esc(LOGO_NAMES[k])}</span></button>`).join('')}</div><small>Pilihan logo dipakai semua orang. A = logo lama dengan driver diperbesar.</small></div>
  </div></div>`;
  const dz = $('#drop');
  dz.onclick = () => { $('#fileAny').value = ''; $('#fileAny').click(); };
  dz.onkeydown = e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); $('#fileAny').click(); } };
  dz.ondragover = e => { e.preventDefault(); dz.classList.add('over'); }; dz.ondragleave = () => dz.classList.remove('over');
  dz.ondrop = e => { e.preventDefault(); dz.classList.remove('over'); ingestFiles([...e.dataTransfer.files]); };
  el.querySelectorAll('[data-pick]').forEach(b => b.onclick = () => pickSlot(b.dataset.pick));
  el.querySelectorAll('[data-clear]').forEach(b => b.onclick = () => clearSlot(b.dataset.clear));
  $('#setCutOn').onchange = e => setSetting('cutoffOn', e.target.value === 'on');
  $('#setCut').onchange = e => setSetting('cutoff', e.target.value || '18:00');
  $('#setPool').onchange = e => setSetting('sjPool', e.target.value);
  $('#setAge').onchange = e => setSetting('agingSrc', e.target.value);
  $('#setRed').onchange = e => setSetting('agingRed', Math.max(0, Math.min(60, parseInt(e.target.value, 10) || 3)));
  $('#setRoll').onchange = e => setSetting('orRollover', e.target.value);
  el.querySelectorAll('.logo-opt').forEach(b => { b.onclick = () => setSetting('logo', b.dataset.logo); });
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
function showShipments(id) {
  const t = todaySerial(), list = shipList(id, t), q = antrian().find(a => a.id === id), nama = q ? q.nama : id;
  const old = list.length && !list.some(x => x.detail);
  $('.modal-box').classList.add('wide');
  $('#modalBody').innerHTML = `<button class="modal-x" id="mx" aria-label="Tutup">×</button><h3>Shipment ${esc(nama)} <span class="note">${esc(id)}</span></h3>
  <p class="desc">${list.length} shipment pada ${esc(fmtSerial(t))} (${cutOn() ? 'cut-off tender ' + esc(S.settings.cutoff) : 'tanggal terbesar Execution Date &amp; Tanggal Tender'}), dari DOTS Report${S.config.dotsMeta ? ' ' + esc(S.config.dotsMeta.name) : ''}.</p>
  ${old ? '<div class="alert-note">Data DOTS lama belum memuat customer, nomor order, dan rute. Upload ulang DOTS Report di menu Upload &amp; Setting.</div>' : ''}
  <div class="tbl-wrap" style="max-height:52vh"><table class="t wrapall"><thead><tr><th>No</th><th class="l">Customer</th><th class="l">No. Order</th><th class="l">No. Shipment</th><th class="l">Rute</th></tr></thead><tbody>${
    list.map((x, i) => `<tr><td class="num">${i + 1}</td><td class="l">${esc(x.cust) || '-'}</td><td class="l">${esc(x.order) || '-'}</td><td class="l">${esc(x.ship)}</td><td class="l">${esc(x.route) || '-'}</td></tr>`).join('') || '<tr><td colspan="5" class="empty-row">Tidak ada shipment</td></tr>'}</tbody></table></div>`;
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
  const map = { history: renderHistory, sj: renderSJ, data: renderData, asumsi: renderAsumsi };
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
  if (!S.loaded.api) { b.className = 'sharebar loc'; b.textContent = 'Memuat data dari database…'; return; }
  if (!API.ok) {
    b.className = 'sharebar ro';
    b.innerHTML = `Database tidak terhubung: ${esc(API.err)}.<br>Jalankan <b>php -S 127.0.0.1:8000 -t public server/router.php</b> dari folder project lalu buka <b>http://127.0.0.1:8000</b>.` + (API.at ? `<br>Menampilkan data terakhir ${esc(fmtTs(API.at))}.` : '');
    return;
  }
  b.className = 'sharebar srv';
  b.innerHTML = `Data sumber diperbarui ${esc(fmtTs(API.at))}. ` + (MODE === 'server'
    ? 'Antrian, upload &amp; setting tersimpan di <b>database QUEDRIVE</b> (dipakai bersama).'
    : '<b>Database QUEDRIVE tidak terhubung</b>: antrian, upload &amp; setting tidak bisa diubah sampai database tersambung lagi.');
}
// tombol tema di kiri bawah: menampilkan mode TUJUAN (terang -> "Night" dengan ikon bulan, gelap -> "Day" dengan ikon matahari)
const IC_MOON = '<svg viewBox="0 0 24 24" fill="#F2A33A" stroke="#D98200" stroke-width="1.6" stroke-linejoin="round"><path d="M21 12.8A9 9 0 1111.2 3a7 7 0 009.8 9.8z"/></svg>';
const IC_SUN = '<svg viewBox="0 0 24 24" fill="none" stroke="#D98200" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="12" r="4.2" fill="#F2A33A"/><path d="M12 2.5v2.2M12 19.3v2.2M2.5 12h2.2M19.3 12h2.2M5.3 5.3l1.6 1.6M17.1 17.1l1.6 1.6M18.7 5.3l-1.6 1.6M6.9 17.1l-1.6 1.6"/></svg>';
function paintTheme(t) { $('#themeIc').innerHTML = t === 'dark' ? IC_SUN : IC_MOON; $('#themeLbl').textContent = t === 'dark' ? 'Day' : 'Night'; $('#btnTheme').setAttribute('aria-pressed', String(t === 'dark')); }
function setTheme(t) { document.documentElement.setAttribute('data-theme', t); paintTheme(t); try { localStorage.setItem('quedrive_theme', t); } catch (e) {} }
$('#yr').textContent = new Date().getFullYear();
$('#btnTheme').onclick = () => setTheme(document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
(function () {
  let t = null; try { t = localStorage.getItem('quedrive_theme'); } catch (e) {}
  setTheme(t || (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));
})();

/* ---------- event ---------- */
$('#searchDriver').addEventListener('input', renderListDriver);
$('#fltStatusLD').addEventListener('change', e => { DF_STATUS = e.target.value; renderListDriver(); });
renderDriverFilter();
$('#btnDF').addEventListener('click', () => toggleDF());
$('#dfClose').addEventListener('click', () => toggleDF(false));
$('#dfDone').addEventListener('click', () => toggleDF(false));
$('#dfReset').addEventListener('click', () => { Object.values(DF).forEach(x => x.clear()); renderDriverFilter(); renderListDriver(); });
$('#dfBody').addEventListener('change', e => { const c = e.target; if (!c.dataset.k) return; DF[c.dataset.k][c.checked ? 'add' : 'delete'](c.value); dfBadge(); renderListDriver(); });
document.addEventListener('click', e => { if (!$('#dfPanel').hidden && !e.target.closest('.df-wrap')) toggleDF(false); });
document.addEventListener('keydown', e => { if (e.key === 'Escape' && !$('#dfPanel').hidden) { toggleDF(false); $('#btnDF').focus(); } });
renderMDFilter();
renderMUFilter();
$('#searchMD').addEventListener('input', renderMasterDriver);
$('#searchMU').addEventListener('input', renderMasterUnit);
$('#btnMUF').addEventListener('click', () => toggleMUF());
$('#mufClose').addEventListener('click', () => toggleMUF(false));
$('#mufDone').addEventListener('click', () => toggleMUF(false));
$('#mufReset').addEventListener('click', () => { MUF.clear(); renderMUFilter(); renderMasterUnit(); });
$('#mufBody').addEventListener('change', e => { const c = e.target; MUF[c.checked ? 'add' : 'delete'](c.value); mufBadge(); renderMasterUnit(); });
document.addEventListener('click', e => { if (!$('#mufPanel').hidden && !e.target.closest('.mu-df')) toggleMUF(false); });
document.addEventListener('keydown', e => { if (e.key === 'Escape' && !$('#mufPanel').hidden) { toggleMUF(false); $('#btnMUF').focus(); } });
$('#btnMDF').addEventListener('click', () => toggleMDF());
$('#mdfClose').addEventListener('click', () => toggleMDF(false));
$('#mdfDone').addEventListener('click', () => toggleMDF(false));
$('#mdfReset').addEventListener('click', () => { Object.values(MDF).forEach(x => x.clear()); renderMDFilter(); renderMasterDriver(); });
$('#mdfBody').addEventListener('change', e => { const c = e.target; if (!c.dataset.k) return; MDF[c.dataset.k][c.checked ? 'add' : 'delete'](c.value); mdfBadge(); renderMasterDriver(); });
document.addEventListener('click', e => { if (!$('#mdfPanel').hidden && !e.target.closest('.md-df')) toggleMDF(false); });
document.addEventListener('keydown', e => { if (e.key === 'Escape' && !$('#mdfPanel').hidden) { toggleMDF(false); $('#btnMDF').focus(); } });
$('#tbDriver').addEventListener('click', e => {
  const t = e.target.closest('[data-act="dd"]');
  if (t) { const id = t.dataset.id; DD_OPEN.has(id) ? DD_OPEN.delete(id) : DD_OPEN.add(id); renderListDriver(); return; }
  const b = e.target.closest('[data-act="add"]'); if (b) addToAntrian(b.dataset.id);
});
$('#tbAntrian').addEventListener('click', e => { const o = e.target.closest('[data-or]'); if (o) { showShipments(o.dataset.or); return; } const sj = e.target.closest('[data-sj]'); if (sj) { showSJ(sj.dataset.sj); return; } const b = e.target.closest('[data-act="del"]'); if (b) removeFromAntrian(b.dataset.id); });
$('#fltOrder').addEventListener('change', e => { QF.v = e.target.value; renderListAntrian(); });
$('#fltTipe').addEventListener('change', e => { QF.tipe = e.target.value; renderListAntrian(); });
$('#fltStatusDriver').addEventListener('change', e => { MF.driver = e.target.value; renderMasterDriver(); });
$('#tbAntrian').addEventListener('change', e => {
  const t = e.target; if (!t.dataset || !t.dataset.f) return;
  if (t.dataset.f === 'nopol_unit') handleUnitChange(t.dataset.id, t); else updateField(t.dataset.id, t.dataset.f, t.value);
});
$('#btnReset').addEventListener('click', clearAntrian);
$('#fileAny').addEventListener('change', e => { const f = [...e.target.files]; e.target.value = ''; ingestFiles(f); });
$('#fileSlot').addEventListener('change', e => { const f = [...e.target.files]; e.target.value = ''; ingestFiles(f, slotTarget === 'p2h' || slotTarget === 'klinik' ? slotTarget : null); });

/* =====================================================================
   ASUMSI & LOGIC
   ===================================================================== */
function renderAsumsi() {
  const st = S.settings;
  $('#v-asumsi').innerHTML = `<div class="page-h"><h2>Asumsi &amp; Logic</h2><p>QUEDRIVE – Queue Driver Availability &amp; Status Monitor. Aturan yang dipakai aplikasi ini.</p></div><div class="asm">
  <div class="panel"><h3>Tujuan &amp; penyimpanan data</h3><table>
  <tr><td>Tujuan</td><td>Menampilkan <b>antrian driver yang bisa diberi order</b>, status ketersediaan (AVA), dan sudah berapa shipment yang didapat driver hari ini.</td></tr>
  <tr><td>Sumber data</td><td><b>Driver, absensi, Last Shipment, dan unit</b> diambil dari database <b>DB1 / db_utilisasi</b> lewat backend lokal PHP (<b>app/lib.php</b>, API <b>public/api/index.php</b>), diperbarui otomatis tiap 30 detik. Halaman harus dibuka lewat <b>http://127.0.0.1:8000</b> (jalankan <b>php -S 127.0.0.1:8000 -t public server/router.php</b> dari folder project). Sumber lain: <b>puninar_dms</b> (Status Driver), <b>puninar_p2h</b> (Status P2H), <b>odongnew_db</b> (SJ Outstanding & Aging SJ), dan <b>db_utilisasi / mst_depo</b> (pilihan Depo). Antrian, Depo, Keterangan, file yang diupload, setting, dan History tersimpan di <b>database QUEDRIVE (MySQL)</b> sehingga sama di semua komputer; setiap driver yang masuk antrian tersimpan sebagai 1 baris di tabel <b>qd_antrian</b> (lengkap dengan waktu antri dan waktu keluar). Data tidak disimpan di browser; bila database QUEDRIVE tidak bisa dihubungi, dashboard menjadi baca saja.</td></tr>
  <tr><td>Master Driver &amp; Unit</td><td><b>Master Driver</b> = semua driver di trx_dms_absent hari ini (filter awalan DM- dinonaktifkan sementara). <b>Master Unit</b> = mst_lookup_lines dengan lookup_code_header = MST_UNIT_TAHUN dan status = ACTIVE (Nopol = name, Tipe Unit = attribute_v3, Pool = attribute_v4, Customer = attribute_v2). Master tidak perlu diupload lagi.</td></tr>
  <tr><td>Format tanggal</td><td>Seluruh aplikasi memakai <b>dd-Mmm-yy</b> (contoh 06-Oct-26); kolom Absen MTA dan Antri memakai dd-Mmm | HH:MM. Saat upload master, tanggal juga dibaca dalam bentuk teks 01-Oct-26, 01/10/2026, atau 2026-10-01.</td></tr>
  <tr><td>Logo &amp; tata letak header</td><td>Logo <b>Puninar</b> berada di <b>kanan atas header</b> (sama seperti APEX), sedangkan merek <b>QUEDRIVE</b> dengan ikon driver dan truk berada di kiri atas menu. Ikon Quedrive punya empat pilihan (A sampai D) di <b>Upload &amp; Setting → Logo aplikasi</b>; default A, yaitu logo lama dengan driver diperbesar agar berbeda dari logo DUIT. Tombol <b>Day/Night</b> berada di <b>kiri bawah</b> menu (menampilkan mode tujuan: "Night" saat terang, "Day" saat gelap) dengan tulisan kecil nama aplikasi dan tahun di bawahnya; pilihan tema tersimpan di browser.</td></tr>
  <tr><td>Filter &amp; jam di layar</td><td>Status penyimpanan data (terhubung ke database atau tidak) tampil di <b>kiri bawah layar</b>. Di tab <b>Master Driver</b> ada kolom pencarian (ID / Nama), pilihan <b>Status Driver</b> (Semua / Aktif / Nonaktif), dan tombol <b>Filter</b>: Tipe Driver (DM, CD, DOS menurut awalan ID Driver) dan Status Klinik (Fit / Unfit). Status Driver dan Status Klinik sudah bisa dipilih, tetapi <b>belum memengaruhi tabel</b> sampai sumber datanya ditentukan. Di kanan atas layar tertulis hari, tanggal, dan jam dengan format <b>Ddd, dd-Mmm-yy | hh:mm</b> (mis. Wed, 07-Oct-26 | 14:18).</td></tr>
  <tr><td>Kolom di tab Master</td><td><b>Master Driver</b>: No, ID Driver, Nama Driver, Status Driver (Aktif / Nonaktif), Status Klinik, Last Unloading, Last Location. <b>Status Driver</b> dari puninar_dms / v_driver_career_utilisasi ; is_active (TRUE = Aktif, FALSE = Nonaktif), dicocokkan lewat nik = ID Driver. <b>Last Unloading</b> = location_name dari stop bongkar (trx_gps_tracking ; stop_type = D) terbaru milik driver (primary_driver_num) menurut final_ata_pta, tender 7 hari terakhir, tanpa cancel; stop yang waktunya belum lewat diabaikan, "-" bila tidak ada. <b>Last Shipment Status</b> = sama dengan Last Shipment di List Driver. <b>Last Location</b> = tombol <b>Lokasi</b> ke live tracking Puninar (nopol dalam Base64), hanya bila shipment terakhir On Progress / Ready <b>dan</b> shipment terbaru yang memakai nopol itu memang milik driver ini; selain itu "-". Status Klinik masih <b>"?"</b> karena sumber datanya belum ditentukan. <b>Master Unit</b>: Tipe Unit, Pool, Customer, dan <b>Status P2H</b> (dari file P2H yang diupload, menurut nopol).</td></tr>
  </table></div>

  <div class="panel"><h3>Absen &amp; List Driver</h3><table>
  <tr><td><span class="step">1</span>AVA / NOT AVA</td><td><b>AVA</b> = <b>initial = X</b> (hadir di pool) <b>dan Last Shipment = Closed atau tidak ada shipment 7 hari terakhir</b>; tombol + aktif. Selain itu <b>NOT AVA</b> dan tidak bisa dimasukkan ke antrian. Urutan: AVA, lalu Pool/On Road, lalu Izin/Sakit/Tidak Hadir, lalu belum absen. Saat ganti hari, sebelum baris absen hari baru dibuat (±00:00-00:04), daftar driver kemarin ditampilkan dengan status Belum Absen.</td></tr>
  <tr><td><span class="step">2</span>Tipe Absensi &amp; Jam</td><td>Dari <b>trx_dms_absent.initial</b> hari ini (huruf besar/kecil diabaikan): X = Pool, OR = On Road, A = Tidak Hadir, I = Izin, S = Sakit; selain itu (kosong, O, ADJ) = Belum Absen. <b>Jam</b> = updated_at saat initial terisi.</td></tr>
  <tr><td><span class="step">3</span>Last Shipment</td><td>Dari <b>trx_gps_tracking</b> 7 hari terakhir (tender_date); shipment cancel diabaikan; driver dicocokkan ke <b>primary_driver_num dan secondary_driver_num</b> (driver yang ada di dalam truk ikut memiliki shipment itu; berlaku juga untuk Last Unloading dan Last Location). Yang dipakai adalah shipment dengan waktu <b>paling dekat ke sekarang</b>: waktu = <b>closed_date</b> bila Closed (forceclosed dihitung Closed), selain itu <b>tender_date</b> (On Progress / Ready). Ikon ▾ di kolom Aksi menampilkan Tipe Absensi dan Last Shipment (nomor shipment, nopol, tanggal tender, tanggal closed).</td></tr>
  <tr><td><span class="step">4</span>Filter List Driver</td><td>Dropdown <b>Status Driver</b> (Semua, AVA, NOT AVA) di sebelah kiri tombol Filter. Tombol <b>Filter</b> membuka pop up: Tipe Driver (DM, CD, DOS menurut awalan ID Driver), Tipe Absensi (Pool, On Road, Izin, Sakit, Tidak Hadir, Belum), Last Shipment (Ready, On Progress, Closed, Tidak ada). Dalam satu field boleh pilih lebih dari satu (atau); antar field harus terpenuhi semua (dan). Field tanpa pilihan = semua.</td></tr>
  <tr class="warn"><td>Belum terhubung</td><td><b>Lokasi Geofence</b> belum punya sumber database. <b>Status Order</b> dari Last Shipment (trx_gps_tracking); file DOTS hanya cadangan. Kartu <b>OR</b> dari absen On Road hari ini (trx_dms_absent.initial = OR) ditambah driver yang baru dipairing hari ini. Kartu <b>SPK / SPO</b> dari <b>trx_shipment_preparation ; shipment_type</b> (shipment unik dengan tanggal onroad hari ini; tanpa tanggal tender atau dibatalkan tidak dihitung; rincian 20/40 FT dari trx_gps_tracking, yang belum ada masuk Lainnya).</td></tr></table></div>

  <div class="panel"><h3>List Antrian &amp; Status Order</h3><table>
  <tr><td><span class="step">1</span>Urutan antrian</td><td>Mengikuti urutan driver dimasukkan (pertama masuk = nomor 1); kolom <b>Antri</b> mencatat tanggal dan jam saat tombol + ditekan, kolom <b>Absen MTA</b> tanggal dan jam absen driver (format dd-Mmm | HH:MM). Antrian dipakai bersama oleh semua orang. Belum ada fitur mengubah urutan manual.</td></tr>
  <tr><td><span class="step">2</span>Isian per baris</td><td><b>Depo</b> dipilih dari dropdown (db_utilisasi / mst_depo ; geo_code, urut abjad); <b>Keterangan</b> diisi manual. <b>Nopol Unit</b> dipilih/diketik dari Master Unit aktif; <b>Tipe Unit</b> terisi otomatis jika nopol sama persis (huruf besar/kecil tidak dibedakan), kosong jika tidak ditemukan.</td></tr>
  <tr><td><span class="step">3</span>Status Order</td><td>Dari <b>Last Shipment</b> driver (DB1 / db_utilisasi / trx_gps_tracking). <b>SUDAH</b> (hijau; arahkan kursor untuk melihat nomor shipment &amp; jam tender) jika Last Shipment <b>Ready atau On Progress</b> dan tender-nya <b>setelah driver masuk antrian</b> (artinya sudah dipairing ke shipment baru); <b>BELUM</b> (abu-abu) jika belum. Shipment baru muncul setelah di-tender, jadi yang baru dipairing tetapi belum di-tender masih BELUM. Bila <b>Nopol Unit</b> masih kosong, diisi otomatis dengan nopol shipment tersebut (isian manual tidak ditimpa). Bila database tidak terhubung, memakai file DOTS Report. Driver <b>tetap di antrian</b> walau sudah diorder (tidak otomatis dikembalikan ke List Driver) karena bisa diorder lagi. Kartu <b>Sudah Diorder</b> = driver di antrian dengan Status Order SUDAH. Pada data DOTS (cadangan), <b>klik tulisan SUDAH</b> untuk melihat detail shipment hari itu: Customer, No. Order (ORDER BASE; jika kosong memakai NOMOR ORDER), No. Shipment, dan Rute (NAMA RUTE).</td></tr>
  <tr><td>Aging SJ Outs.(hari)</td><td>Menggantikan kolom Out. SJ. Berisi <b>aging SJ outstanding terlama</b> milik driver tersebut, dengan sumber, pool, dan batas merah yang sama seperti tab SJ Outstanding (merah jika ≥ ${st.agingRed} hari). Jika driver punya lebih dari satu SJ, tampil jumlah SJ di bawah angka. <b>Klik angka</b> untuk melihat daftarnya: ID Driver, Nama Driver, Customer, Tgl Tender, No. Shipment, Aging (Hari). Tampil "-" jika tidak ada SJ outstanding atau file SJ belum diupload.</td></tr>
  <tr><td>Urutan kolom</td><td>No (nomor antrian), ID Driver, Nama, Nopol Unit, Tipe Unit, Absen MTA, Antri, Status Order, Status Klinik, Status P2H, Aging SJ Outs.(hari), Depo, Keterangan, Aksi.</td></tr>
  <tr><td>Status Klinik</td><td>Status klinik adalah status <b>driver</b>, dicocokkan dengan <b>ID Driver</b> pada baris tersebut (huruf besar/kecil, spasi, dan strip diabaikan). Datanya dari <b>trx_dms_absent.status_klinik</b> (baris absen hari ini); file di <b>Upload &amp; Setting → Status Klinik Driver</b> hanya cadangan bila database tidak terhubung. <b>Bila status klinik kosong (NULL), di List Antrian tampil BELUM</b> (di Master Driver dan History tampil "-"). Teks status tampil apa adanya; warna hijau untuk kata seperti FIT/OK/SEHAT/LOLOS, merah untuk UNFIT/NOT/TIDAK/SAKIT, abu-abu untuk lainnya. Jika file memuat kolom tanggal, dipakai status terbaru per driver; jika tidak, baris paling bawah. Dipakai di Dashboard Antrian, Master Driver, dan History.</td></tr>
  <tr><td>Status P2H</td><td>P2H adalah status <b>unit</b>, jadi mengikuti <b>Nopol Unit</b> di baris tersebut. Tampil <b>-</b> jika Nopol belum diisi, atau jika Nopol sudah diisi tetapi unit itu tidak ada di data P2H. Datanya dari file terpisah yang diupload di <b>Upload &amp; Setting → Status P2H Unit</b> (kolom Nopol dan Status P2H, nama file bebas). Pencocokan nopol tidak membedakan huruf besar/kecil, spasi, strip, atau titik (B-9001-TEH sama dengan B 9001 TEH). Teks status ditampilkan apa adanya dari file; warna hijau untuk kata seperti OK/FIT/READY/LOLOS, merah untuk NOT/TIDAK/BELUM/GAGAL, abu-abu untuk lainnya. Jika file memuat kolom tanggal, dipakai status terbaru per nopol; jika tidak, baris paling bawah.</td></tr>
  <tr><td>Susunan layar Dashboard</td><td><b>List Driver di kiri dan List Antrian di kanan</b> bila lebar jendela browser sekitar 1540 px atau lebih (lebar List Driver menyesuaikan layar, 380–460 px). Pada jendela yang lebih sempit keduanya disusun atas-bawah agar kolom List Antrian tidak terpotong dan tanpa scroll ke samping (mengecilkan zoom browser, mis. Ctrl dan minus, akan mengembalikan susunan berdampingan).</td></tr>
  <tr><td>Tampilan header &amp; tanggal-jam</td><td>Semua header tabel rata tengah (kiri-kanan dan atas-bawah) dan dibuat tidak terpotong. Kolom Absen MTA dan Antri memakai font sedikit lebih kecil, dan tanggal serta jam tidak dipecah di tengah, sehingga paling banyak 2 baris ("06-Oct |" lalu "15:57").</td></tr>
  <tr><td>Filter Tipe Unit</td><td>Di <b>baris filter tepat di atas header kolom Tipe Unit</b> ada pilihan: Semua, lalu tipe yang ada di antrian saat ini (mis. 20 FT, 40 FT), dan <b>Kosong</b> untuk driver yang nopolnya belum dipilih. Bisa digabung dengan filter Status Order. Hanya mengubah tampilan; kartu di atas tetap menghitung seluruh antrian.</td></tr>
  <tr><td>Filter Status Order</td><td>Di <b>baris filter tepat di atas header kolom Status Order</b> ada pilihan <b>Semua / SUDAH / BELUM</b>. Filter hanya mengubah tampilan; nomor urut tetap nomor antrian sebenarnya dan kartu di atas tetap menghitung seluruh antrian.</td></tr>
  <tr class="warn"><td>Placeholder &amp; validasi</td><td>Belum ada pengecekan nopol yang dipakai ganda, atau pemblokiran driver yang sudah diorder.</td></tr>
  <tr><td><span class="step">4</span>Hapus &amp; reset</td><td>Ikon tempat sampah menghapus satu driver. <b>Reset Antrian</b> (dengan konfirmasi) mengosongkan seluruh antrian untuk semua orang; riwayatnya tetap ada di History Antrian.</td></tr>
  <tr><td><span class="step">5</span>Ganti tanggal</td><td>Dicek otomatis saat tanggal berganti. Driver yang <b>belum OR tetap di antrian</b>. Driver yang sudah OR mengikuti setting <i>Saat ganti tanggal, driver yang sudah OR</i> (sekarang: <b>${st.orRollover === 'remove' ? 'dikeluarkan dari antrian' : 'tetap di antrian'}</b>). Penentuan OR memakai shipment pada tanggal sebelumnya dari DOTS terakhir yang diupload. Status Order dihitung ulang untuk tanggal baru; absen perlu diulang.</td></tr></table></div>

  <div class="panel"><h3>Status OR dari DOTS Report</h3><table>
  <tr><td>Shipment valid (OR &amp; Status Order)</td><td>Baris dengan TANGGAL TENDER terisi, STATUS FPT bukan CANCEL, dan punya ID DRIVER 1 dan/atau 2. Baris lain diabaikan. Shipment yang sama pada driver dan tanggal yang sama dihitung satu kali. Aturan ini dipakai untuk <b>status OR dan Status Order</b> (REPLAN masih ikut dihitung di sini). Kotak Shipment SPK / SPO memakai aturan yang lebih ketat: hanya NEW dan CARRY OVER.</td></tr>
  <tr><td>Tanggal onroad (actual eksekusi)</td><td>Tanggal onroad = <b>tanggal terbesar antara Execution Date dan Tanggal Tender</b> (hanya tanggalnya, tanpa jam). Contoh: tender 06-Oct, Execution Date 05-Oct → onroad 06-Oct; tender 01-Oct, Execution Date 02-Oct → onroad 02-Oct. Aturan <b>cut-off tender</b> (tender lewat jam tertentu dihitung H+1, seperti di SIGAP) bisa diaktifkan di Upload &amp; Setting; sekarang: <b>${cutOn() ? 'dipakai, jam ' + esc(st.cutoff) : 'tidak dipakai'}</b>.</td></tr>
  <tr><td>Jumlah shipment</td><td>Jumlah shipment valid dengan driver sebagai ID DRIVER 1 atau ID DRIVER 2 pada tanggal onroad tersebut. Driver dengan ≥ 1 shipment pada hari ini = <b>OR</b>.</td></tr>
  <tr class="warn"><td>OFF &amp; AIS</td><td>Kartu OFF dan AIS dihapus karena DOTS Report tidak memuat data libur/izin/sakit/alpha. Jika diperlukan, dibutuhkan sumber data absensi (seperti di SIGAP).</td></tr></table></div>

  <div class="panel"><h3>Kartu status (Dashboard)</h3><table>
  <tr><td>Total Antrian</td><td>Jumlah driver yang ada di List Antrian pada tanggal hari ini (termasuk yang sudah OR dan yang masih tersisa dari hari sebelumnya).</td></tr>
  <tr><td>Belum Diorder</td><td>Jumlah driver di List Antrian yang <b>belum punya shipment (belum ditender)</b> pada tanggal hari ini, menurut DOTS Report dan aturan cut-off. Sama dengan status <i>BELUM</i> di List Antrian. </td></tr>
  <tr><td>Sudah Diorder</td><td>Total Antrian − Belum Diorder, yaitu driver di antrian yang sudah punya shipment hari ini. </td></tr>
  <tr><td>% Belum Diorder</td><td>Belum Diorder ÷ Total Antrian × 100 (satu desimal). Tampil "-" jika antrian kosong. Sebelum DOTS Report diupload, semua driver dianggap belum diorder.</td></tr>
  <tr><td>On Road (OR) hari ini</td><td>Jumlah driver yang <b>absen On Road hari ini</b>: <b>trx_dms_absent.initial = OR</b> pada tanggal hari ini (sama dengan Tipe Absensi "On Road" di List Driver). Tanpa rincian tipe unit.</td></tr>
  <tr><td>Shipment SPK / SPO hari ini</td><td>Dua kotak terpisah. <b>SPK</b> = shipment dengan TIPE SHIPMENT SPK (armada sendiri); <b>SPO</b> = TIPE SHIPMENT SPO (vendor). Dihitung per <b>shipment unik</b> pada tanggal onroad actual hari ini, tanpa dibatasi driver Master. <b>Hanya shipment dengan STATUS FPT NEW dan CARRY OVER yang dihitung</b>; CANCEL dan REPLAN (serta status lain) tidak dihitung. Rincian tipe truk dari kolom TIPE TRUCK: <b>20 FT</b> (mengandung 20, mis. 20FT, CONTAINER_20FT), <b>40 FT</b> (mengandung 40), dan <b>Lainnya</b> (selain itu atau memuat 20 dan 40 sekaligus; tampil hanya jika ada). Angka SPK dihitung per shipment, sedangkan Status Order dihitung per driver (shipment berdua dihitung untuk masing-masing driver).</td></tr>
  <tr><td>Tersedia (AVA)</td><td>Driver aktif yang <b>sudah absen hari ini dan belum masuk antrian</b> (keterangan kecil di bawah angkanya: "Sudah absen, belum antri").</td></tr>
  <tr><td>Susunan kotak</td><td>Satu kotak terbagi 4: kiri atas <b>Belum Diorder</b>, kiri bawah <b>% Belum Diorder</b>, kanan atas <b>Sudah Diorder</b>, kanan bawah <b>Tersedia (AVA)</b>. Di sebelahnya kotak On Road (OR), Shipment SPK, dan Shipment SPO.</td></tr></table></div>

  <div class="panel"><h3>History Antrian</h3><table>
  <tr><td>Isi</td><td>Setiap perubahan List Driver / List Antrian (termasuk remarks Depo, Nopol, Keterangan) disimpan otomatis sebagai riwayat <b>per tanggal</b>. Pilih tanggal untuk melihat List Antrian dan List Driver pada tanggal tersebut.</td></tr>
  <tr><td>Kolom</td><td>Header List Antrian di History <b>sama dengan Dashboard Antrian</b> (No, ID Driver, Nama, Nopol Unit, Tipe Unit, Absen MTA, Antri, Status Order, Status Klinik, Status P2H, Aging SJ Outs.(hari), Depo, Keterangan), hanya tanpa Aksi. Aging SJ Outs. adalah nilai yang tercatat pada tanggal tersebut (tampil "-" untuk riwayat lama yang belum mencatatnya). Status Klinik dan Status P2H adalah status yang tercatat pada tanggal tersebut ("-" jika tidak ada datanya; khusus P2H kosong jika nopol belum diisi).</td></tr>
  <tr><td>Download</td><td>Satu file Excel dengan dua sheet: List Antrian dan List Driver untuk tanggal yang dipilih. Status Klinik, Status P2H, dan Aging SJ Outs.(hari) ikut.</td></tr>
  <tr class="warn"><td>Batasan</td><td>Riwayat tersimpan mulai aplikasi versi ini dipakai. Riwayat sebuah tanggal adalah kondisi terakhir yang tercatat pada tanggal itu; jumlah shipment mengikuti DOTS yang sudah diupload saat itu.</td></tr></table></div>

  <div class="panel"><h3>SJ Outstanding (Gantungan Surat Jalan)</h3><table>
  <tr><td>Sumber</td><td>Database <b>odongnew_db / so.so_trx_document</b> (API /api/sj). SJ <i>belum kembali</i> = <b>trip_status = TRIP_ENDED</b> dan <b>last_status_id kosong</b> (dokumen belum diterima admin mana pun), baris yang dihapus diabaikan. Kolom: shipment_number, driver_id, customer_name, tender_date, power_unit. File Download Detail SJ hanya dipakai bila database SJ tidak bisa dibaca.</td></tr>
  <tr><td>Driver aktif</td><td>Yang ditampilkan hanya SJ milik <b>driver aktif di Master Driver</b> (NID cocok dengan ID driver dan aktif hari ini); nama driver diambil dari Master. SJ milik vendor atau driver di luar Master tidak ditampilkan.</td></tr>
  <tr><td>Kolom &amp; urutan</td><td>No, ID Driver, Nama Driver, Customer, Tgl Tender, No. Shipment, Aging (Hari). Diurutkan dari <b>aging terlama</b> ke terbaru. Ada kotak cari untuk driver, customer, atau shipment.</td></tr>
  <tr><td>Filter Customer</td><td>Tombol <b>Customer</b> di atas tabel membuka daftar customer (lengkap dengan jumlah SJ-nya) dengan kotak centang. Defaultnya <b>Semua customer</b> terpilih; centang/hapus centang untuk memilih satuan atau beberapa customer, atau pakai <b>Pilih semua</b> / <b>Kosongkan</b>. Daftar customer mengikuti SJ yang tampil (pool dan driver aktif). Filter ini bisa digabung dengan kotak cari, dan ringkasan di atas menunjukkan jumlah SJ terpilih dari total. Pilihan hanya berlaku di layar yang sedang dibuka (tidak disimpan ke server).</td></tr>
  <tr><td>Pool</td><td>Tabel SJ tidak punya kolom pool, jadi pool diambil dari <b>Master Unit</b> lewat nopol (power_unit → mst_lookup_lines.attribute_v4). Yang ditampilkan sesuai setting <b>Pool untuk SJ Outstanding</b> (sekarang: <b>${st.sjPool === 'ALL' ? 'Semua pool' : esc(st.sjPool)}</b>); SJ yang nopolnya tidak ada di Master Unit hanya muncul pada pilihan Semua pool.</td></tr>
  <tr><td>Aging</td><td>Dihitung dari <b>Tgl Tender sampai hari ini</b> (hari kalender), jadi angkanya bertambah otomatis tiap hari.${sjDb() ? '' : st.agingSrc === 'file' ? ' Data dari file: memakai kolom Aging di file SJ.' : ''}</td></tr>
  <tr><td>Warna merah</td><td>Aging <b>≥ ${st.agingRed} hari</b> diberi warna merah.</td></tr></table></div>

  <div class="panel"><h3>Format file upload</h3><table>
  <tr><td>DOTS Report</td><td>Sheet pertama dengan kolom SHIPMENT, ID DRIVER 1, ID DRIVER 2, TANGGAL TENDER, EXECUTION DATE, STATUS FPT; untuk detail shipment juga CUSTOMER, ORDER BASE / NOMOR ORDER, dan NAMA RUTE; untuk rincian 20/40 FT dan SPK/SPO juga TIPE TRUCK dan TIPE SHIPMENT. Dikenali otomatis dari judul kolom (bisa baris judul tidak di baris pertama).</td></tr>
  <tr><td>Download Detail SJ</td><td>Sheet pertama dengan kolom Shipment No, Pool, Customer, NID, Driver, Tender Time, Aging, Status.</td></tr>
  <tr><td>Status Klinik Driver</td><td>Sheet pertama dengan kolom <b>ID Driver</b> (juga dibaca: Driver ID, NID, NIK) dan <b>Status Klinik</b> (atau kolom lain yang judulnya memuat "Klinik" atau "Kesehatan"); opsional kolom Tanggal. Judul kolom boleh di salah satu dari 15 baris pertama. Setelah upload ditampilkan kolom yang terbaca dan sebaran statusnya supaya bisa dicek.</td></tr>
  <tr><td>Status P2H Unit</td><td>Sheet pertama dengan kolom <b>Nopol</b> (juga dibaca: Nopol Unit, No Polisi, Plat Nomor) dan <b>Status P2H</b> (atau kolom lain yang judulnya memuat "P2H"); opsional kolom Tanggal. Judul kolom boleh di salah satu dari 15 baris pertama. Setelah upload ditampilkan kolom yang terbaca dan sebaran statusnya supaya bisa dicek.</td></tr>
  <tr><td>Master Driver</td><td>DRIVER ID, NAMA DRIVER, STATUS, TANGGAL AKTIF, TANGGAL NONAKTIF (judul lama ID_Driver / Nama_Driver juga dibaca). Baris tanpa ID atau nama dilewati. Tanggal: format tanggal Excel atau teks seperti 01-Oct-26, 01/10/2026, 2026-10-01; yang tidak terbaca dianggap kosong dan diberitahu saat upload.</td></tr>
  <tr><td>Master Unit</td><td>Nopol, Tipe Trailer, Sub Status, TANGGAL AKTIF, TANGGAL NONAKTIF (judul lama Nopol_Unit / Type_Unit juga dibaca). Baris tanpa nopol atau tipe dilewati.</td></tr></table></div>
  </div>`;
}

/* =====================================================================
   START
   ===================================================================== */
const DAY_EN = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
// kanan atas: Ddd, dd-Mmm-yy | hh:mm  (contoh: Wed, 07-Oct-26 | 14:18)
function clockText() { const n = new Date(); return DAY_EN[n.getDay()] + ', ' + fmtSerial(dayOfDate(n)) + ' | ' + getCurrentTimeStr(); }
function tickClock() {
  $('#clock').textContent = clockText();
  maybeRollover();
}
async function initApp() {
  renderMasterDriver(); renderMasterUnit(); renderListDriver(); renderListAntrian(); renderAsumsi(); tickClock(); renderShareBar(); loadApi();
  await initDb();
  DBREADY = true;
  renderShareBar();
  subscribeAll();
  setInterval(tickClock, 15000);
  setInterval(() => { $('#clock').textContent = clockText(); }, 5000);
  setInterval(loadApi, API_REFRESH_MS);   // data driver & unit dari database
}
// filter selalu mulai dari kosong setiap halaman dimuat ulang; saat pindah menu filter tidak direset
function resetFilterControls() {
  document.querySelectorAll('.toolbar input[type=search], #fltStatusLD, #fltStatusDriver, #fltTipe, #fltOrder').forEach(el => {
    el.setAttribute('autocomplete', 'off');
    if (el.tagName === 'SELECT') el.selectedIndex = 0; else el.value = '';
  });
}
window.addEventListener('load', () => { resetFilterControls(); initApp(); });
</script>
</body>
</html>
