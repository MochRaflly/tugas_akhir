<?php
session_start();

// Redirect berdasarkan role
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'guru') {
        header("Location: dashboard_guru.php");
    } else {
        header("Location: dashboard_siswa.php");
    }
    exit();
}

// Jika belum login, redirect ke welcome page
header("Location: welcome.php");
exit();
?> 