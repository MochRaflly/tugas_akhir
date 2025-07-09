<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - Sistem Manajemen Sekolah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>🏫 Selamat Datang di Sistem Manajemen Sekolah</h2>
        
        <div class="panel">
            <h3>🚀 Langkah Pertama</h3>
            <p>Sebelum menggunakan sistem, pastikan database sudah disetup dengan benar.</p>
            
            <div style="margin: 20px 0;">
                <a href="setup.php" class="btn btn-success" style="margin-right: 10px;">🔧 Setup Database</a>
                <a href="check_db.php" class="btn btn-info" style="margin-right: 10px;">🔍 Cek Database</a>
                <a href="status.php" class="btn btn-warning" style="margin-right: 10px;">📊 Status Sistem</a>
                <a href="backup_db.php" class="btn btn-secondary" style="margin-right: 10px;">💾 Backup Database</a>
                <a href="files.php" class="btn btn-dark" style="margin-right: 10px;">📁 Daftar File</a>
                <a href="login.php" class="btn">🔐 Login</a>
            </div>
        </div>
        
        <div class="panel">
            <h3>📋 Informasi Sistem</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div>
                    <h4>👨‍🏫 Untuk Guru</h4>
                    <ul>
                        <li>Kelola mata pelajaran</li>
                        <li>Upload materi pembelajaran</li>
                        <li>Buat dan kelola tugas</li>
                        <li>Beri nilai tugas siswa</li>
                        <li>Lihat laporan nilai</li>
                    </ul>
                </div>
                <div>
                    <h4>👨‍🎓 Untuk Siswa</h4>
                    <ul>
                        <li>Lihat daftar tugas</li>
                        <li>Kerjakan dan upload tugas</li>
                        <li>Download materi pembelajaran</li>
                        <li>Lihat nilai tugas</li>
                        <li>Kelola profil</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="panel">
            <h3>🔑 Akun Default</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
                    <h4>👨‍🏫 Guru</h4>
                    <p><strong>Username:</strong> guru1</p>
                    <p><strong>Password:</strong> password123</p>
                </div>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
                    <h4>👨‍🎓 Siswa</h4>
                    <p><strong>Username:</strong> siswa1</p>
                    <p><strong>Password:</strong> password123</p>
                </div>
            </div>
        </div>
        
        <div class="panel">
            <h3>🔧 Troubleshooting</h3>
            <div style="background: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107;">
                <h4>Jika ada error:</h4>
                <ol>
                    <li>Pastikan XAMPP/MariaDB sudah berjalan</li>
                    <li>Jalankan <a href="setup.php">setup.php</a> untuk membuat database</li>
                                    <li>Jalankan <a href="fix_db.php">fix_db.php</a> jika ada error struktur</li>
                <li>Jalankan <a href="status.php">status.php</a> untuk cek status sistem</li>
                <li>Jalankan <a href="error_log.php">error_log.php</a> untuk cek log error</li>
                <li>Periksa file <code>koneksi.php</code> untuk konfigurasi database</li>
                <li>Jika perlu reset total, jalankan <a href="reset_db.php">reset_db.php</a></li>
                </ol>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="login.php" class="btn btn-success" style="font-size: 1.2em; padding: 15px 30px; margin-right: 10px;">🚀 Mulai Menggunakan Sistem</a>
            <a href="summary.php" class="btn btn-primary" style="font-size: 1.1em; padding: 15px 30px; margin-right: 10px;">📊 Ringkasan Sistem</a>
            <a href="info.php" class="btn btn-info" style="font-size: 1.1em; padding: 15px 30px;">ℹ️ Informasi Lengkap</a>
        </div>
    </div>
</body>
</html> 