# 🏫 Sistem Manajemen Sekolah

Sistem manajemen sekolah berbasis web yang memungkinkan guru dan siswa untuk mengelola mata pelajaran, materi pembelajaran, tugas, dan nilai secara digital.

## ✨ Fitur Utama

### 👨‍🏫 Untuk Guru
- **Dashboard Guru**: Statistik dan ringkasan aktivitas mengajar
- **Kelola Mata Pelajaran**: Tambah, edit, dan hapus mata pelajaran
- **Kelola Materi**: Upload dan kelola materi pembelajaran
- **Buat Tugas**: Buat tugas dengan deadline dan deskripsi
- **Laporan Nilai**: Lihat dan beri nilai tugas siswa
- **Profil**: Edit informasi profil dan password

### 👨‍🎓 Untuk Siswa
- **Dashboard Siswa**: Statistik tugas dan nilai
- **Daftar Tugas**: Lihat semua tugas yang tersedia
- **Kerjakan Tugas**: Upload file tugas dan komentar
- **Daftar Mata Pelajaran**: Lihat mata pelajaran yang tersedia
- **Daftar Materi**: Download materi pembelajaran
- **Nilai Saya**: Lihat nilai tugas yang sudah dinilai
- **Profil**: Edit informasi profil dan password

## 🛠️ Teknologi yang Digunakan

- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript
- **Server**: PHP Built-in Server
- **Styling**: Custom CSS dengan gradient dan modern design

## 🚀 Cara Menjalankan

### 1. Persiapan Database
Pastikan XAMPP/MariaDB sudah berjalan dan buat database:
```sql
CREATE DATABASE sekolah_db;
```

### 2. Konfigurasi Database
Edit file `koneksi.php`:
```php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'sekolah_db';
```

### 3. Setup Database Otomatis
Jalankan file setup untuk membuat struktur database:
```
http://localhost:8000/setup.php
```

### 4. Jalankan Server
```bash
# Dari direktori project
php -S localhost:8000
```

### 5. Akses Aplikasi
Buka browser dan kunjungi: `http://localhost:8000`

## 🔧 Troubleshooting

### Jika ada error database:
1. **Cek struktur database**: `http://localhost:8000/check_db.php`
2. **Perbaiki database**: `http://localhost:8000/fix_db.php`
3. **Setup ulang**: `http://localhost:8000/setup.php`

### Error "Unknown column":
- Jalankan `fix_db.php` untuk memperbaiki struktur tabel
- Atau jalankan `setup.php` untuk membuat ulang database

## 👥 Akun Default

### Guru
- Username: `guru1`
- Password: `password123`

### Siswa
- Username: `siswa1`
- Password: `password123`

## 📋 Struktur Database

### Tabel `users`
- `id` (Primary Key)
- `username` (Unique)
- `password` (Hashed)
- `nama` (Nama lengkap)
- `role` (guru/siswa)
- `kelas` (Untuk siswa)
- `email` (Opsional)
- `created_at` (Timestamp)

### Tabel `mata_pelajaran`
- `id` (Primary Key)
- `nama` (Nama mata pelajaran)
- `deskripsi` (Deskripsi mata pelajaran)
- `guru_id` (Foreign Key ke users)
- `created_at` (Timestamp)

### Tabel `materi`
- `id` (Primary Key)
- `judul` (Judul materi)
- `deskripsi` (Deskripsi materi)
- `file_path` (Path file materi)
- `mapel_id` (Foreign Key ke mata_pelajaran)
- `created_at` (Timestamp)

### Tabel `tugas`
- `id` (Primary Key)
- `judul` (Judul tugas)
- `deskripsi` (Deskripsi tugas)
- `deadline` (Deadline tugas)
- `mapel_id` (Foreign Key ke mata_pelajaran)
- `created_at` (Timestamp)

### Tabel `pengumpulan`
- `id` (Primary Key)
- `tugas_id` (Foreign Key ke tugas)
- `siswa_id` (Foreign Key ke users)
- `file_path` (Path file tugas)
- `komentar` (Komentar siswa)
- `nilai` (Nilai dari guru)
- `komentar_guru` (Komentar dari guru)
- `created_at` (Timestamp)

## 📁 Struktur File

```
tugas_akhir/
├── style.css              # File CSS utama
├── koneksi.php            # Konfigurasi database
├── index.php              # Halaman utama (redirect)
├── login.php              # Halaman login
├── register.php           # Halaman registrasi
├── logout.php             # Proses logout
├── profil.php             # Edit profil
├── dashboard_guru.php     # Dashboard guru
├── dashboard_siswa.php    # Dashboard siswa
├── daftar_tugas.php       # Daftar tugas (siswa)
├── kerjakan_tugas.php     # Kerjakan tugas (siswa)
├── daftar_mapel.php       # Daftar mata pelajaran (siswa)
├── daftar_materi.php      # Daftar materi (siswa)
├── nilai_saya.php         # Nilai siswa
├── setup.php              # Setup database otomatis
├── fix_db.php             # Perbaiki struktur database
├── check_db.php           # Cek struktur database
├── create_database.sql    # Script SQL untuk database
├── uploads/               # Folder upload file
└── README.md              # Dokumentasi
```

## 🎨 Desain dan UI/UX

### Fitur Desain
- **Responsive Design**: Menyesuaikan dengan berbagai ukuran layar
- **Modern Gradient**: Background gradient yang menarik
- **Card-based Layout**: Layout berbasis card untuk informasi
- **Status Badges**: Badge berwarna untuk status tugas
- **Hover Effects**: Efek hover pada tombol dan card
- **Consistent Styling**: Desain yang konsisten di semua halaman

### Warna dan Tema
- **Primary**: Gradient biru-ungu (#667eea → #764ba2)
- **Success**: Hijau (#28a745)
- **Warning**: Kuning-orange (#ffc107)
- **Danger**: Merah (#dc3545)
- **Info**: Biru (#17a2b8)

## 🔒 Keamanan

- **Password Hashing**: Menggunakan `password_hash()` dan `password_verify()`
- **Prepared Statements**: Mencegah SQL injection
- **Session Management**: Manajemen session yang aman
- **Input Validation**: Validasi input di sisi server
- **File Upload Security**: Validasi tipe dan ukuran file

## 📱 Responsive Design

Sistem ini responsive dan dapat diakses dari:
- Desktop (1200px+)
- Tablet (768px - 1199px)
- Mobile (< 768px)

## 🔧 Customization

### Mengubah Warna Tema
Edit file `style.css` bagian CSS variables atau gradient colors.

### Menambah Fitur
1. Buat file PHP baru
2. Tambahkan routing di navigation
3. Update database jika diperlukan
4. Test fitur baru

## 📞 Support

Jika ada pertanyaan atau masalah:

1. **Error Database**: Jalankan `setup.php` atau `fix_db.php`
2. **Error 500**: Periksa log error PHP dan struktur database
3. **Login Gagal**: Pastikan database terkonfigurasi dengan benar
4. **File Upload**: Periksa permission folder uploads/
5. **Tampilan**: Pastikan file style.css terload dengan benar

## 📄 License

Project ini dibuat untuk keperluan pembelajaran dan tugas akhir.

---

**Dibuat dengan ❤️ untuk Sistem Manajemen Sekolah**