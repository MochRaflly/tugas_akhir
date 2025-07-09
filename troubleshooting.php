<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panduan Troubleshooting - Sistem Manajemen Sekolah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>🔧 Panduan Troubleshooting</h2>
        
        <div class="panel">
            <h3>🚨 Error 500 - Internal Server Error</h3>
            <div style="background: #f8d7da; padding: 20px; border-radius: 8px; border-left: 4px solid #dc3545; margin: 20px 0;">
                <h4>Gejala:</h4>
                <ul>
                    <li>Halaman menampilkan "This page isn't working right now"</li>
                    <li>Error 500 di browser</li>
                    <li>Halaman kosong atau tidak tampil</li>
                </ul>
                
                <h4>Solusi:</h4>
                <ol>
                    <li><strong>Jalankan <a href="setup.php">setup.php</a></strong> untuk membuat struktur database yang benar</li>
                    <li><strong>Jalankan <a href="fix_db.php">fix_db.php</a></strong> jika ada error struktur tabel</li>
                    <li><strong>Cek <a href="error_log.php">error_log.php</a></strong> untuk melihat detail error</li>
                    <li><strong>Jalankan <a href="status.php">status.php</a></strong> untuk cek status sistem</li>
                    <li>Pastikan XAMPP/MariaDB sudah berjalan</li>
                    <li>Periksa file <code>koneksi.php</code> untuk konfigurasi database</li>
                </ol>
            </div>
        </div>
        
        <div class="panel">
            <h3>🗄️ Error Database</h3>
            <div style="background: #fff3cd; padding: 20px; border-radius: 8px; border-left: 4px solid #ffc107; margin: 20px 0;">
                <h4>Gejala:</h4>
                <ul>
                    <li>"Unknown column" error</li>
                    <li>"Table doesn't exist" error</li>
                    <li>Koneksi database gagal</li>
                    <li>Data tidak tampil</li>
                </ul>
                
                <h4>Solusi:</h4>
                <ol>
                    <li><strong>Jalankan <a href="setup.php">setup.php</a></strong> untuk membuat ulang database</li>
                    <li><strong>Jalankan <a href="fix_db.php">fix_db.php</a></strong> untuk memperbaiki struktur</li>
                    <li><strong>Jalankan <a href="check_db.php">check_db.php</a></strong> untuk cek struktur tabel</li>
                    <li>Pastikan database <code>sekolah_db</code> sudah dibuat</li>
                    <li>Periksa username dan password database di <code>koneksi.php</code></li>
                    <li>Jika perlu reset total, jalankan <a href="reset_db.php">reset_db.php</a></li>
                </ol>
            </div>
        </div>
        
        <div class="panel">
            <h3>🔐 Error Login</h3>
            <div style="background: #d1ecf1; padding: 20px; border-radius: 8px; border-left: 4px solid #17a2b8; margin: 20px 0;">
                <h4>Gejala:</h4>
                <ul>
                    <li>Username/password salah</li>
                    <li>Login tidak berhasil</li>
                    <li>Redirect ke login terus</li>
                </ul>
                
                <h4>Solusi:</h4>
                <ol>
                    <li><strong>Gunakan akun default:</strong>
                        <ul>
                            <li>Guru: username = <code>guru1</code>, password = <code>password123</code></li>
                            <li>Siswa: username = <code>siswa1</code>, password = <code>password123</code></li>
                        </ul>
                    </li>
                    <li>Jalankan <a href="setup.php">setup.php</a> untuk membuat akun default</li>
                    <li>Pastikan database terhubung dengan benar</li>
                    <li>Cek apakah tabel <code>users</code> ada dan berisi data</li>
                </ol>
            </div>
        </div>
        
        <div class="panel">
            <h3>📁 Error File Upload</h3>
            <div style="background: #e2e3e5; padding: 20px; border-radius: 8px; border-left: 4px solid #6c757d; margin: 20px 0;">
                <h4>Gejala:</h4>
                <ul>
                    <li>File tidak dapat diupload</li>
                    <li>Error permission</li>
                    <li>File tidak tersimpan</li>
                </ul>
                
                <h4>Solusi:</h4>
                <ol>
                    <li>Pastikan folder <code>uploads/</code> ada dan dapat ditulis</li>
                    <li>Periksa permission folder (777 untuk development)</li>
                    <li>Pastikan ukuran file tidak melebihi limit</li>
                    <li>Cek tipe file yang diizinkan</li>
                    <li>Jalankan <a href="status.php">status.php</a> untuk cek status folder</li>
                </ol>
            </div>
        </div>
        
        <div class="panel">
            <h3>🎨 Error Tampilan</h3>
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #6c757d; margin: 20px 0;">
                <h4>Gejala:</h4>
                <ul>
                    <li>CSS tidak terload</li>
                    <li>Tampilan tidak rapi</li>
                    <li>Style tidak konsisten</li>
                </ul>
                
                <h4>Solusi:</h4>
                <ol>
                    <li>Pastikan file <code>style.css</code> ada dan dapat diakses</li>
                    <li>Periksa path CSS di file HTML</li>
                    <li>Clear cache browser</li>
                    <li>Periksa console browser untuk error</li>
                </ol>
            </div>
        </div>
        
        <div class="panel">
            <h3>🔍 Langkah Diagnostik</h3>
            <div style="background: #d4edda; padding: 20px; border-radius: 8px; border-left: 4px solid #28a745; margin: 20px 0;">
                <h4>Urutan troubleshooting yang disarankan:</h4>
                <ol>
                    <li><strong>Jalankan <a href="status.php">status.php</a></strong> - Cek status sistem secara umum</li>
                    <li><strong>Jalankan <a href="setup.php">setup.php</a></strong> - Setup database jika belum</li>
                    <li><strong>Jalankan <a href="fix_db.php">fix_db.php</a></strong> - Perbaiki struktur database</li>
                    <li><strong>Jalankan <a href="error_log.php">error_log.php</a></strong> - Cek log error PHP</li>
                    <li><strong>Jalankan <a href="check_db.php">check_db.php</a></strong> - Cek struktur database</li>
                    <li><strong>Jalankan <a href="summary.php">summary.php</a></strong> - Lihat ringkasan sistem</li>
                    <li>Jika semua gagal, jalankan <a href="reset_db.php">reset_db.php</a> untuk reset total</li>
                </ol>
            </div>
        </div>
        
        <div class="panel">
            <h3>📞 Support & Kontak</h3>
            <div style="background: #e9ecef; padding: 20px; border-radius: 8px;">
                <h4>Jika masalah masih berlanjut:</h4>
                <ul>
                    <li>Periksa dokumentasi di <a href="README.md">README.md</a></li>
                    <li>Lihat informasi lengkap di <a href="info.php">info.php</a></li>
                    <li>Periksa daftar file di <a href="files.php">files.php</a></li>
                    <li>Buat backup sebelum melakukan perubahan besar</li>
                    <li>Pastikan semua file project terupload dengan lengkap</li>
                </ul>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="panel">
            <h3>⚡ Quick Actions</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <a href="setup.php" class="btn btn-success">🔧 Setup Database</a>
                <a href="fix_db.php" class="btn btn-warning">🔧 Fix Database</a>
                <a href="status.php" class="btn btn-info">📊 Status Sistem</a>
                <a href="error_log.php" class="btn btn-danger">📋 Log Error</a>
                <a href="check_db.php" class="btn btn-secondary">🔍 Cek Database</a>
                <a href="reset_db.php" class="btn btn-dark">🔄 Reset Database</a>
            </div>
        </div>
        
        <!-- Navigation -->
        <div style="text-align: center; margin-top: 30px;">
            <a href="welcome.php" class="btn btn-success" style="margin-right: 10px;">🏠 Kembali ke Welcome</a>
            <a href="summary.php" class="btn btn-primary" style="margin-right: 10px;">📊 Ringkasan Sistem</a>
            <a href="info.php" class="btn btn-info">ℹ️ Informasi Sistem</a>
        </div>
    </div>
</body>
</html> 