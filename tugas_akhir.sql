-- Membuat database
CREATE DATABASE IF NOT EXISTS tugas_akhir;
USE tugas_akhir;

-- Membuat tabel users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('siswa','guru') NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    kelas VARCHAR(20)
);

-- Membuat tabel mata_pelajaran dengan relasi ke users (guru)
CREATE TABLE IF NOT EXISTS mata_pelajaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_mapel VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    guru_id INT,
    FOREIGN KEY (guru_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Membuat tabel materi
CREATE TABLE IF NOT EXISTS materi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    konten TEXT,
    mapel_id INT,
    file_name VARCHAR(255),
    FOREIGN KEY (mapel_id) REFERENCES mata_pelajaran(id) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Membuat tabel tugas
CREATE TABLE IF NOT EXISTS tugas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    deskripsi TEXT,
    mapel_id INT,
    deadline DATETIME,
    FOREIGN KEY (mapel_id) REFERENCES mata_pelajaran(id) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Membuat tabel pengumpulan
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