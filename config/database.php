<?php
/**
 * Konfigurasi Database
 * File ini berisi koneksi ke database MySQL
 * dan fungsi-fungsi helper untuk session
 */

// Mulai session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ================================================================
// Konfigurasi Database
// ================================================================
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'absensi_digital');

// Base URL aplikasi
define('BASE_URL', '/absensi2');

// ================================================================
// Koneksi Database menggunakan mysqli
// ================================================================
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set charset ke utf8mb4
$conn->set_charset("utf8mb4");

// ================================================================
// Fungsi Helper
// ================================================================

/**
 * Cek apakah user sudah login
 * Redirect ke halaman login jika belum
 */
function cekLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: " . BASE_URL . "/index.php");
        exit();
    }
}

/**
 * Cek apakah user adalah admin
 * Redirect ke halaman login jika bukan admin
 */
function cekAdmin() {
    cekLogin();
    if ($_SESSION['role'] !== 'admin') {
        header("Location: " . BASE_URL . "/index.php");
        exit();
    }
}

/**
 * Cek apakah user adalah guru
 * Redirect ke halaman login jika bukan guru
 */
function cekGuru() {
    cekLogin();
    if ($_SESSION['role'] !== 'guru') {
        header("Location: " . BASE_URL . "/index.php");
        exit();
    }
}

/**
 * Sanitasi input untuk mencegah XSS
 * @param string $data Input yang akan disanitasi
 * @return string Input yang sudah disanitasi
 */
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Set flash message untuk notifikasi
 * @param string $type Tipe pesan (success, danger, warning, info)
 * @param string $message Isi pesan
 */
function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Tampilkan flash message jika ada
 * @return string HTML alert Bootstrap
 */
function getFlash() {
    if (isset($_SESSION['flash'])) {
        $type = $_SESSION['flash']['type'];
        $message = $_SESSION['flash']['message'];
        unset($_SESSION['flash']);
        return '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">
                    ' . $message . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>';
    }
    return '';
}
