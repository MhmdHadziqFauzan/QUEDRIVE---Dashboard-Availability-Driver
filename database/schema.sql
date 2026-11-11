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

-- Kolom tambahan List Antrian (tampilan HTML user 9-Nov): seluruh isi baris disimpan juga sebagai JSON (data) agar field baru
-- dari tampilan tidak hilang; kolom lain untuk laporan. Satu driver bisa punya beberapa baris (putaran antri ke-1, ke-2, ...).
ALTER TABLE qd_antrian
  ADD COLUMN IF NOT EXISTS data           LONGTEXT     NULL,                 -- isi baris lengkap (JSON) seperti di dashboard
  ADD COLUMN IF NOT EXISTS putaran        INT          NOT NULL DEFAULT 1,   -- antri ke-1, ke-2, ...
  ADD COLUMN IF NOT EXISTS selesai        TINYINT(1)   NOT NULL DEFAULT 0,   -- 1 = putaran sudah diganti putaran berikutnya (antri lagi)
  ADD COLUMN IF NOT EXISTS manual_sudah   TINYINT(1)   NOT NULL DEFAULT 0,   -- 1 = Status Order ditandai SUDAH manual
  ADD COLUMN IF NOT EXISTS manual_remarks VARCHAR(300) NULL,                 -- remarks tanda SUDAH manual (boleh kosong)
  ADD COLUMN IF NOT EXISTS jam_diedit     TINYINT(1)   NOT NULL DEFAULT 0;   -- 1 = jam antri diubah manual (lupa klik +)

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

-- Catatan per driver (Master Driver) dan per unit (Master Unit). Berlaku mulai tanggal diisi sampai diubah;
-- tanggal sebelumnya tetap memakai catatan lama. Isi kosong = catatan dihapus mulai tanggal itu.
-- Disimpan terpisah dari master (master dibaca dari database sumber), jadi tidak hilang saat master berubah.
CREATE TABLE IF NOT EXISTS qd_catatan (
  jenis         VARCHAR(10)   NOT NULL,             -- DRIVER / UNIT
  kunci         VARCHAR(40)   NOT NULL,             -- ID driver (mis. DM-01234) / nopol tanpa spasi-strip-titik (mis. B1234ABC)
  berlaku_mulai DATE          NOT NULL,
  catatan       VARCHAR(255)  NOT NULL DEFAULT '',  -- catatan unit maks 12 karakter (dicek di app/store.php)
  diubah_pada   DATETIME(6)   NOT NULL,
  diubah_oleh   VARCHAR(64)   NULL,
  PRIMARY KEY (jenis, kunci, berlaku_mulai),
  KEY idx_diubah (diubah_pada)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===== Role & akses data per project (dasar menu login nanti). Semua admin memakai menu & fungsi yang sama;
-- yang dibedakan hanya data project / pool yang boleh dilihat. Belum ada halaman login: user yang dipakai = QD_LOGIN_USER di .env,
-- bila kosong pakai 'admin_exim'.

-- Daftar role
CREATE TABLE IF NOT EXISTS qd_role (
  kode        VARCHAR(30)   NOT NULL PRIMARY KEY,   -- mis. ADMIN_EXIM
  nama        VARCHAR(100)  NOT NULL,
  keterangan  VARCHAR(255)  NULL,
  aktif       TINYINT(1)    NOT NULL DEFAULT 1,
  dibuat_pada DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Akses data per role. jenis = PROJECT (nilai = mst_driver.project_name) atau POOL (nilai = ownership_shipment)
CREATE TABLE IF NOT EXISTS qd_role_akses (
  role_kode   VARCHAR(30)   NOT NULL,
  jenis       VARCHAR(10)   NOT NULL,
  nilai       VARCHAR(100)  NOT NULL,
  PRIMARY KEY (role_kode, jenis, nilai)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- User dashboard (password / SSO ditambahkan saat menu login dibuat)
CREATE TABLE IF NOT EXISTS qd_user (
  username    VARCHAR(50)   NOT NULL PRIMARY KEY,
  nama        VARCHAR(100)  NOT NULL,
  role_kode   VARCHAR(30)   NOT NULL,
  aktif       TINYINT(1)    NOT NULL DEFAULT 1,
  dibuat_pada DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_role (role_kode)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data awal (INSERT IGNORE: tidak menimpa bila sudah diubah)
INSERT IGNORE INTO qd_role (kode, nama, keterangan) VALUES
  ('ADMIN_EXIM', 'Admin Exim Trailer', 'Akses project Exim Trailer, LOBP, ESA - Pool Nagrak Trailer');
INSERT IGNORE INTO qd_role_akses (role_kode, jenis, nilai) VALUES
  ('ADMIN_EXIM', 'PROJECT', 'Exim Trailler'),
  ('ADMIN_EXIM', 'PROJECT', 'LOBP'),
  ('ADMIN_EXIM', 'PROJECT', 'ESA'),
  ('ADMIN_EXIM', 'POOL', 'POOL NAGRAK TRAILER');
INSERT IGNORE INTO qd_user (username, nama, role_kode) VALUES
  ('admin_exim', 'Admin Exim Trailer', 'ADMIN_EXIM');
