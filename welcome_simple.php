<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Sekolah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>🏫 Sistem Manajemen Sekolah</h2>
        
        <div class="panel">
            <h3>🔧 Perbaiki Database</h3>
            <p>Jika ada error 500, jalankan perbaikan database terlebih dahulu:</p>
            <div style="margin: 20px 0;">
                <a href="fix_nama.php" class="btn btn-success" style="margin-right: 10px;">🔧 Perbaiki Kolom Nama</a>
                <a href="perbaiki_cepat.php" class="btn btn-warning" style="margin-right: 10px;">🔧 Perbaiki Cepat</a>
                <a href="setup.php" class="btn btn-danger">🔄 Setup Ulang</a>
            </div>
        </div>
        
        <div class="panel">
            <h3>🔐 Login</h3>
            <p>Akun default:</p>
            <ul>
                <li><strong>Guru:</strong> username = guru1, password = password123</li>
                <li><strong>Siswa:</strong> username = siswa1, password = password123</li>
            </ul>
            <div style="margin: 20px 0;">
                <a href="login.php" class="btn btn-primary">🔐 Login</a>
            </div>
        </div>
        
        <div class="panel">
            <h3>📊 Status</h3>
            <div style="margin: 20px 0;">
                <a href="status_simple.php" class="btn btn-info">📊 Cek Status</a>
            </div>
        </div>
    </div>
</body>
</html> 