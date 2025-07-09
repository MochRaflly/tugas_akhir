-- Perbaikan struktur database
USE sekolah_db;

-- Perbaiki tabel mata_pelajaran
ALTER TABLE mata_pelajaran CHANGE nama_mapel nama VARCHAR(100) NOT NULL;

-- Perbaiki tabel users
ALTER TABLE users CHANGE nama_lengkap nama VARCHAR(100) NOT NULL;

-- Tambahkan kolom yang mungkin hilang
ALTER TABLE users ADD COLUMN IF NOT EXISTS email VARCHAR(100) NULL;
ALTER TABLE users ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Perbaiki tabel materi
ALTER TABLE materi ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Perbaiki tabel tugas
ALTER TABLE tugas ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Perbaiki tabel pengumpulan
ALTER TABLE pengumpulan CHANGE tanggal_kirim created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE pengumpulan ADD COLUMN IF NOT EXISTS komentar TEXT NULL;
ALTER TABLE pengumpulan ADD COLUMN IF NOT EXISTS komentar_guru TEXT NULL;

-- Update data yang ada jika diperlukan
UPDATE users SET nama = nama_lengkap WHERE nama_lengkap IS NOT NULL;
UPDATE mata_pelajaran SET nama = nama_mapel WHERE nama_mapel IS NOT NULL;

-- Hapus kolom lama jika masih ada
ALTER TABLE users DROP COLUMN IF EXISTS nama_lengkap;
ALTER TABLE mata_pelajaran DROP COLUMN IF EXISTS nama_mapel; 