<?php
require_once 'koneksi.php';

echo "<h2>🚀 Setup Database Sekolah</h2>";

try {
    // Buat tabel users
    echo "<p>👥 Membuat tabel users...</p>";
    $conn->query("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        nama VARCHAR(100) NOT NULL,
        role ENUM('guru', 'siswa') NOT NULL,
        kelas VARCHAR(20) NULL,
        email VARCHAR(100) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✅ Tabel users berhasil dibuat<br>";
    
    // Buat tabel mata_pelajaran
    echo "<p>📚 Membuat tabel mata_pelajaran...</p>";
    $conn->query("CREATE TABLE IF NOT EXISTS mata_pelajaran (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        deskripsi TEXT,
        guru_id INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (guru_id) REFERENCES users(id) ON DELETE SET NULL
    )");
    echo "✅ Tabel mata_pelajaran berhasil dibuat<br>";
    
    // Buat tabel materi
    echo "<p>📖 Membuat tabel materi...</p>";
    $conn->query("CREATE TABLE IF NOT EXISTS materi (
        id INT AUTO_INCREMENT PRIMARY KEY,
        judul VARCHAR(200) NOT NULL,
        deskripsi TEXT,
        file_path VARCHAR(255),
        mapel_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (mapel_id) REFERENCES mata_pelajaran(id) ON DELETE CASCADE
    )");
    echo "✅ Tabel materi berhasil dibuat<br>";
    
    // Buat tabel tugas
    echo "<p>📝 Membuat tabel tugas...</p>";
    $conn->query("CREATE TABLE IF NOT EXISTS tugas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        judul VARCHAR(200) NOT NULL,
        deskripsi TEXT,
        deadline DATETIME NOT NULL,
        mapel_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (mapel_id) REFERENCES mata_pelajaran(id) ON DELETE CASCADE
    )");
    echo "✅ Tabel tugas berhasil dibuat<br>";
    
    // Buat tabel pengumpulan
    echo "<p>📤 Membuat tabel pengumpulan...</p>";
    $conn->query("CREATE TABLE IF NOT EXISTS pengumpulan (
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
    )");
    echo "✅ Tabel pengumpulan berhasil dibuat<br>";
    
    // Cek apakah sudah ada data
    $result = $conn->query("SELECT COUNT(*) as count FROM users");
    $count = $result->fetch_assoc()['count'];
    
    if ($count == 0) {
        echo "<p>👤 Menambahkan data sample...</p>";
        
        // Insert user sample
        $password_hash = password_hash('password123', PASSWORD_DEFAULT);
        $conn->query("INSERT INTO users (username, password, nama, role, kelas) VALUES
            ('guru1', '$password_hash', 'Guru Satu', 'guru', NULL),
            ('siswa1', '$password_hash', 'Siswa Satu', 'siswa', 'XII')");
        echo "✅ Data user sample berhasil ditambahkan<br>";
        
        // Insert mata pelajaran sample
        $conn->query("INSERT INTO mata_pelajaran (nama, deskripsi, guru_id) VALUES
            ('Matematika', 'Mata pelajaran matematika dasar', 1),
            ('Bahasa Indonesia', 'Mata pelajaran bahasa Indonesia', 1)");
        echo "✅ Data mata pelajaran sample berhasil ditambahkan<br>";
        
        // Insert tugas sample
        $conn->query("INSERT INTO tugas (judul, deskripsi, deadline, mapel_id) VALUES
            ('Tugas Matematika 1', 'Kerjakan soal matematika halaman 10-15', '2024-12-31 23:59:59', 1),
            ('Tugas Bahasa Indonesia', 'Buat esai tentang pendidikan', '2024-12-25 23:59:59', 2)");
        echo "✅ Data tugas sample berhasil ditambahkan<br>";
    } else {
        echo "<p>ℹ️ Data sudah ada, tidak perlu menambahkan sample</p>";
    }
    
    echo "<h3>🎉 Setup database selesai!</h3>";
    echo "<p><strong>Akun default:</strong></p>";
    echo "<ul>";
    echo "<li>Guru: username = guru1, password = password123</li>";
    echo "<li>Siswa: username = siswa1, password = password123</li>";
    echo "</ul>";
    echo "<p><a href='login.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Mulai Login</a></p>";
    
} catch (Exception $e) {
    echo "<h3>❌ Error: " . $e->getMessage() . "</h3>";
    echo "<p>Silakan periksa koneksi database Anda.</p>";
}
?> 