<?php
/**
 * Entry Point Aplikasi
 * Redirect ke halaman login
 */
session_start();

// Jika sudah login, redirect ke dashboard sesuai role
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: controllers/AdminController.php");
    } else {
        header("Location: controllers/GuruController.php");
    }
    exit();
}

// Jika belum login, redirect ke halaman login
header("Location: controllers/AuthController.php");
exit();
