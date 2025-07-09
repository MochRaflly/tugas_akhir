-- Perbaikan struktur database sekolah_db
USE sekolah_db;

-- Perbaiki tabel mata_pelajaran - ubah nama_mapel menjadi nama
ALTER TABLE mata_pelajaran CHANGE nama_mapel nama VARCHAR(100) NOT NULL;

-- Perbaiki tabel users - ubah nama_lengkap menjadi nama
ALTER TABLE users CHANGE nama_lengkap nama VARCHAR(100) NOT NULL;

-- Tambahkan kolom yang diperlukan
ALTER TABLE users ADD COLUMN email VARCHAR(100) NULL;
ALTER TABLE users ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Perbaiki tabel pengumpulan
ALTER TABLE pengumpulan CHANGE tanggal_kirim created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE pengumpulan ADD COLUMN komentar TEXT NULL;
ALTER TABLE pengumpulan ADD COLUMN komentar_guru TEXT NULL; 