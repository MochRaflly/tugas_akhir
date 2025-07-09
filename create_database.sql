-- Buat database
CREATE DATABASE IF NOT EXISTS sekolah_db;
USE sekolah_db;

-- Tabel users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    role ENUM('guru', 'siswa') NOT NULL,
    kelas VARCHAR(20) NULL,
    email VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel mata_pelajaran
CREATE TABLE IF NOT EXISTS mata_pelajaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    guru_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (guru_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Tabel materi
CREATE TABLE IF NOT EXISTS materi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    deskripsi TEXT,
    file_path VARCHAR(255),
    mapel_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (mapel_id) REFERENCES mata_pelajaran(id) ON DELETE CASCADE
);

-- Tabel tugas
CREATE TABLE IF NOT EXISTS tugas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    deskripsi TEXT,
    deadline DATETIME NOT NULL,
    mapel_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (mapel_id) REFERENCES mata_pelajaran(id) ON DELETE CASCADE
);

-- Tabel pengumpulan
CREATE TABLE IF NOT EXISTS pengumpulan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tugas_id INT NOT NULL,
    siswa_id INT NOT NULL,
    file_path VARCHAR(255),
    komentar TEXT,
    nilai DECIMAL(5,2),
    komentar_guru TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tugas_id) REFERENCES tugas(id) ON DELETE CASCADE,
    FOREIGN KEY (siswa_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert data sample
INSERT INTO users (username, password, nama, role, kelas) VALUES
('guru1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Guru Satu', 'guru', NULL),
('siswa1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Siswa Satu', 'siswa', 'XII');

-- Insert mata pelajaran sample
INSERT INTO mata_pelajaran (nama, deskripsi, guru_id) VALUES
('Matematika', 'Mata pelajaran matematika dasar', 1),
('Bahasa Indonesia', 'Mata pelajaran bahasa Indonesia', 1);

-- Insert tugas sample
INSERT INTO tugas (judul, deskripsi, deadline, mapel_id) VALUES
('Tugas Matematika 1', 'Kerjakan soal matematika halaman 10-15', '2024-12-31 23:59:59', 1),
('Tugas Bahasa Indonesia', 'Buat esai tentang pendidikan', '2024-12-25 23:59:59', 2); 