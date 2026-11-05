-- Tabel milik QUEDRIVE (MySQL / MariaDB): semua data yang diisi admin di dashboard.
-- Rencana: ditempatkan di DB1 (db_utilisasi). Sementara di laptop: database quedrive (MySQL XAMPP).
-- Dibuat oleh: php database/buat_db.php   (aman dijalankan ulang, tidak menghapus data)
-- diubah_pada (DATETIME(6)) dipakai browser untuk mengambil perubahan saja, agar semua komputer sinkron.

-- List Antrian di Dashboard Antrian: 1 baris = 1 driver masuk antrian.
-- status AKTIF = masih di antrian; KELUAR = sudah dihapus dari antrian / antrian dikosongkan / ganti hari.
CREATE TABLE IF NOT EXISTS qd_antrian (
  id           BIGINT        NOT NULL AUTO_INCREMENT PRIMARY KEY,
  kunci        VARCHAR(64)   NOT NULL,              -- ID driver versi aman (titik/spasi jadi _), dipakai dashboard
  tanggal      DATE          NOT NULL,              -- tanggal masuk antrian
  driver_id    VARCHAR(30)   NOT NULL,
  nama         VARCHAR(100)  NULL,
  jam_absen    VARCHAR(10)   NULL,                  -- jam absen MTA (HH:MM)
  absen_day    INT           NULL,                  -- tanggal absen (nomor hari Excel, dipakai dashboard)
  waktu_antri  DATETIME      NULL,                  -- waktu masuk antrian
  waktu_masuk  VARCHAR(30)   NULL,                  -- teks waktu antri seperti tampil di dashboard
  seq          BIGINT        NULL,                  -- urutan antrian (milidetik)
  nopol_unit   VARCHAR(30)   NULL,
  tipe_unit    VARCHAR(30)   NULL,
  depo         VARCHAR(255)  NULL,
  keterangan   VARCHAR(255)  NULL,
  status       VARCHAR(10)   NOT NULL DEFAULT 'AKTIF',
  waktu_keluar DATETIME      NULL,
  diubah_pada  DATETIME(6)   NOT NULL,
  diubah_oleh  VARCHAR(64)   NULL,
  KEY idx_status (status, kunci),
  KEY idx_tanggal (tanggal),
  KEY idx_diubah (diubah_pada)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Setting & informasi dashboard (menu Upload & Setting: cut-off, pool SJ, batas aging, logo, info file upload, dll.)
CREATE TABLE IF NOT EXISTS qd_setting (
  kunci       VARCHAR(64)  NOT NULL PRIMARY KEY,    -- 'config'
  nilai       LONGTEXT     NOT NULL,                -- JSON
  diubah_pada DATETIME(6)  NOT NULL,
  diubah_oleh VARCHAR(64)  NULL,
  KEY idx_diubah (diubah_pada)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- History harian (menu History): snapshot List Antrian & List Driver per tanggal.
CREATE TABLE IF NOT EXISTS qd_history (
  tanggal     DATE         NOT NULL PRIMARY KEY,
  data        LONGTEXT     NULL,                    -- JSON
  dihapus     TINYINT(1)   NOT NULL DEFAULT 0,
  diubah_pada DATETIME(6)  NOT NULL,
  KEY idx_diubah (diubah_pada)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- File yang diupload di Upload & Setting (DOTS, SJ cadangan, P2H cadangan, Klinik), dipotong per bagian.
CREATE TABLE IF NOT EXISTS qd_upload (
  jenis       VARCHAR(20)  NOT NULL,                -- dots / sj / p2h / klinik
  bagian      VARCHAR(20)  NOT NULL,                -- c0, c1, ...
  data        LONGTEXT     NULL,                    -- JSON {rows: [...]}
  dihapus     TINYINT(1)   NOT NULL DEFAULT 0,
  diubah_pada DATETIME(6)  NOT NULL,
  PRIMARY KEY (jenis, bagian),
  KEY idx_diubah (diubah_pada)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
