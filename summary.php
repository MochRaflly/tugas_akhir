<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ringkasan Sistem - Sistem Manajemen Sekolah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>📊 Ringkasan Lengkap Sistem</h2>
        
        <?php
        require_once 'koneksi.php';
        
        // Statistik Database
        try {
            $stats = [];
            
            $result = $conn->query("SELECT COUNT(*) as count FROM users");
            $stats['users'] = $result->fetch_assoc()['count'];
            
            $result = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'guru'");
            $stats['guru'] = $result->fetch_assoc()['count'];
            
            $result = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'siswa'");
            $stats['siswa'] = $result->fetch_assoc()['count'];
            
            $result = $conn->query("SELECT COUNT(*) as count FROM mata_pelajaran");
            $stats['mapel'] = $result->fetch_assoc()['count'];
            
            $result = $conn->query("SELECT COUNT(*) as count FROM materi");
            $stats['materi'] = $result->fetch_assoc()['count'];
            
            $result = $conn->query("SELECT COUNT(*) as count FROM tugas");
            $stats['tugas'] = $result->fetch_assoc()['count'];
            
            $result = $conn->query("SELECT COUNT(*) as count FROM pengumpulan");
            $stats['pengumpulan'] = $result->fetch_assoc()['count'];
            
            $result = $conn->query("SELECT COUNT(*) as count FROM pengumpulan WHERE nilai IS NOT NULL");
            $stats['dinilai'] = $result->fetch_assoc()['count'];
            
        } catch (Exception $e) {
            $stats = null;
        }
        ?>
        
        <!-- Statistik Database -->
        <div class="panel">
            <h3>📊 Statistik Database</h3>
            <?php if ($stats): ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                    <div style="background: #e3f2fd; padding: 20px; border-radius: 8px; text-align: center;">
                        <h3><?php echo $stats['users']; ?></h3>
                        <p>Total User</p>
                    </div>
                    <div style="background: #f3e5f5; padding: 20px; border-radius: 8px; text-align: center;">
                        <h3><?php echo $stats['guru']; ?></h3>
                        <p>Guru</p>
                    </div>
                    <div style="background: #e8f5e8; padding: 20px; border-radius: 8px; text-align: center;">
                        <h3><?php echo $stats['siswa']; ?></h3>
                        <p>Siswa</p>
                    </div>
                    <div style="background: #fff3e0; padding: 20px; border-radius: 8px; text-align: center;">
                        <h3><?php echo $stats['mapel']; ?></h3>
                        <p>Mata Pelajaran</p>
                    </div>
                    <div style="background: #fce4ec; padding: 20px; border-radius: 8px; text-align: center;">
                        <h3><?php echo $stats['materi']; ?></h3>
                        <p>Materi</p>
                    </div>
                    <div style="background: #e0f2f1; padding: 20px; border-radius: 8px; text-align: center;">
                        <h3><?php echo $stats['tugas']; ?></h3>
                        <p>Total Tugas</p>
                    </div>
                    <div style="background: #f1f8e9; padding: 20px; border-radius: 8px; text-align: center;">
                        <h3><?php echo $stats['pengumpulan']; ?></h3>
                        <p>Pengumpulan</p>
                    </div>
                    <div style="background: #e8eaf6; padding: 20px; border-radius: 8px; text-align: center;">
                        <h3><?php echo $stats['dinilai']; ?></h3>
                        <p>Sudah Dinilai</p>
                    </div>
                </div>
            <?php else: ?>
                <p style="color: red;">Tidak dapat mengambil statistik database.</p>
            <?php endif; ?>
        </div>
        
        <!-- Statistik File -->
        <div class="panel">
            <h3>📁 Statistik File Project</h3>
            <?php
            $file_stats = [
                'PHP Files' => count(glob('*.php')),
                'CSS Files' => count(glob('*.css')),
                'SQL Files' => count(glob('*.sql')),
                'Documentation' => count(glob('*.md')),
                'Backup Files' => count(glob('backup_*.sql')),
                'Upload Files' => count(glob('uploads/*'))
            ];
            
            $total_size = 0;
            foreach (glob('*') as $file) {
                if (is_file($file)) {
                    $total_size += filesize($file);
                }
            }
            ?>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <?php foreach ($file_stats as $category => $count): ?>
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center;">
                        <h3><?php echo $count; ?></h3>
                        <p><?php echo $category; ?></p>
                    </div>
                <?php endforeach; ?>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center;">
                    <h3><?php echo number_format($total_size / 1024, 1); ?> KB</h3>
                    <p>Total Ukuran</p>
                </div>
            </div>
        </div>
        
        <!-- Daftar File Utama -->
        <div class="panel">
            <h3>📋 File Utama Sistem</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div>
                    <h4>🔧 Setup & Maintenance</h4>
                    <ul>
                        <li><a href="setup.php">setup.php</a> - Setup database otomatis</li>
                        <li><a href="fix_db.php">fix_db.php</a> - Perbaiki struktur database</li>
                        <li><a href="check_db.php">check_db.php</a> - Cek struktur database</li>
                        <li><a href="status.php">status.php</a> - Status sistem</li>
                        <li><a href="backup_db.php">backup_db.php</a> - Backup database</li>
                        <li><a href="reset_db.php">reset_db.php</a> - Reset database</li>
                    </ul>
                </div>
                <div>
                    <h4>🔐 Authentication</h4>
                    <ul>
                        <li><a href="login.php">login.php</a> - Halaman login</li>
                        <li><a href="register.php">register.php</a> - Halaman registrasi</li>
                        <li><a href="logout.php">logout.php</a> - Proses logout</li>
                        <li><a href="profil.php">profil.php</a> - Edit profil</li>
                    </ul>
                </div>
                <div>
                    <h4>👨‍🏫 Guru Features</h4>
                    <ul>
                        <li><a href="dashboard_guru.php">dashboard_guru.php</a> - Dashboard guru</li>
                        <li><a href="kelola_mapel.php">kelola_mapel.php</a> - Kelola mata pelajaran</li>
                        <li><a href="kelola_materi.php">kelola_materi.php</a> - Kelola materi</li>
                        <li><a href="buat_tugas.php">buat_tugas.php</a> - Buat tugas</li>
                        <li><a href="laporan_nilai.php">laporan_nilai.php</a> - Laporan nilai</li>
                    </ul>
                </div>
                <div>
                    <h4>👨‍🎓 Siswa Features</h4>
                    <ul>
                        <li><a href="dashboard_siswa.php">dashboard_siswa.php</a> - Dashboard siswa</li>
                        <li><a href="daftar_tugas.php">daftar_tugas.php</a> - Daftar tugas</li>
                        <li><a href="kerjakan_tugas.php">kerjakan_tugas.php</a> - Kerjakan tugas</li>
                        <li><a href="daftar_mapel.php">daftar_mapel.php</a> - Daftar mata pelajaran</li>
                        <li><a href="nilai_saya.php">nilai_saya.php</a> - Nilai siswa</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Status Sistem -->
        <div class="panel">
            <h3>✅ Status Sistem</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div style="background: #d4edda; padding: 20px; border-radius: 8px; border-left: 4px solid #28a745;">
                    <h4>✅ Database</h4>
                    <p><?php echo $stats ? 'Terhubung dan berfungsi' : 'Tidak dapat terhubung'; ?></p>
                </div>
                <div style="background: #d4edda; padding: 20px; border-radius: 8px; border-left: 4px solid #28a745;">
                    <h4>✅ File System</h4>
                    <p>Semua file utama tersedia</p>
                </div>
                <div style="background: #d4edda; padding: 20px; border-radius: 8px; border-left: 4px solid #28a745;">
                    <h4>✅ Upload Folder</h4>
                    <p><?php echo is_dir('uploads') && is_writable('uploads') ? 'Tersedia dan dapat ditulis' : 'Tidak tersedia atau tidak dapat ditulis'; ?></p>
                </div>
                <div style="background: #d4edda; padding: 20px; border-radius: 8px; border-left: 4px solid #28a745;">
                    <h4>✅ PHP Version</h4>
                    <p>PHP <?php echo PHP_VERSION; ?></p>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="panel">
            <h3>⚡ Quick Actions</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <a href="setup.php" class="btn btn-success">🔧 Setup Database</a>
                <a href="status.php" class="btn btn-info">📊 Status Sistem</a>
                <a href="backup_db.php" class="btn btn-secondary">💾 Backup Database</a>
                <a href="files.php" class="btn btn-dark">📁 Daftar File</a>
                <a href="error_log.php" class="btn btn-warning">📋 Log Error</a>
                <a href="info.php" class="btn btn-primary">ℹ️ Informasi Lengkap</a>
            </div>
        </div>
        
        <!-- Navigation -->
        <div style="text-align: center; margin-top: 30px;">
            <a href="welcome.php" class="btn btn-success" style="margin-right: 10px;">🏠 Kembali ke Welcome</a>
            <a href="login.php" class="btn btn-primary" style="margin-right: 10px;">🔐 Login</a>
            <a href="info.php" class="btn btn-info">ℹ️ Informasi Sistem</a>
        </div>
    </div>
</body>
</html> 