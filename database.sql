-- Database dan tabel
CREATE DATABASE IF NOT EXISTS tugas_akhir;
USE tugas_akhir;

-- Tabel users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('siswa','guru') NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    kelas VARCHAR(20)
);

-- Tabel mata_pelajaran
CREATE TABLE IF NOT EXISTS mata_pelajaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_mapel VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    guru_id INT,
    FOREIGN KEY (guru_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Tabel materi
CREATE TABLE IF NOT EXISTS materi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    konten TEXT,
    mapel_id INT,
    file_name VARCHAR(255),
    FOREIGN KEY (mapel_id) REFERENCES mata_pelajaran(id) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Tabel tugas
CREATE TABLE IF NOT EXISTS tugas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    deskripsi TEXT,
    mapel_id INT,
    deadline DATETIME,
    FOREIGN KEY (mapel_id) REFERENCES mata_pelajaran(id) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Tabel pengumpulan
CREATE TABLE IF NOT EXISTS pengumpulan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tugas_id INT,
    siswa_id INT,
    file_name VARCHAR(255),
    nilai DECIMAL(5,2),
    komentar TEXT,
    tanggal_kirim DATETIME,
    FOREIGN KEY (tugas_id) REFERENCES tugas(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (siswa_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Sample data users (password: guru123, siswa123)
INSERT INTO users (username, password, role, nama_lengkap, kelas) VALUES
('guru1', '$2y$10$6s6QwQn6QwQn6QwQn6QwQnOQwQn6QwQn6QwQn6QwQn6QwQn6QwQn6', 'guru', 'Budi Guru', NULL),
('siswa1', '$2y$10$8QwQn6QwQn6QwQn6QwQnOQwQn6QwQn6QwQn6QwQn6QwQn6QwQn6', 'siswa', 'Andi Siswa', 'XII RPL');

-- Sample data mata_pelajaran
INSERT INTO mata_pelajaran (nama_mapel, deskripsi, guru_id) VALUES
('Matematika', 'Pelajaran Matematika Dasar', 2),
('Bahasa Indonesia', 'Pelajaran Bahasa Indonesia', 2);

-- Sample data materi
INSERT INTO materi (judul, konten, mapel_id, file_name) VALUES
('Materi Aljabar', 'Ini adalah materi aljabar.', 1, NULL),
('Materi Puisi', 'Ini adalah materi puisi.', 2, NULL);

-- Sample data tugas
INSERT INTO tugas (judul, deskripsi, mapel_id, deadline) VALUES
('Tugas Aljabar', 'Kerjakan soal aljabar.', 1, '2024-07-31 23:59:00'),
('Tugas Puisi', 'Buat puisi tentang alam.', 2, '2024-08-05 23:59:00');

-- Sample data pengumpulan
INSERT INTO pengumpulan (tugas_id, siswa_id, file_name, nilai, komentar, tanggal_kirim) VALUES
(1, 3, NULL, 90, 'Bagus sekali!', '2024-07-20 10:00:00'),
(2, 3, NULL, 85, 'Sudah baik, perbaiki diksi.', '2024-07-22 11:00:00'); 