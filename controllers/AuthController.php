<?php
/**
 * Controller Auth
 * Menangani login dan logout
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

$userModel = new User($conn);
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

switch ($action) {
    case 'login':
        handleLogin($userModel);
        break;
    case 'logout':
        handleLogout();
        break;
    default:
        handleLogin($userModel);
        break;
}

/**
 * Handle proses login
 */
function handleLogin($userModel) {
    $error = '';

    // Jika sudah login, redirect
    if (isset($_SESSION['user_id'])) {
        redirectByRole();
        return;
    }

    // Proses form login
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = sanitize($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validasi input tidak boleh kosong
        if (empty($username) || empty($password)) {
            $error = 'Username dan password wajib diisi!';
        } else {
            // Cari user di database
            $user = $userModel->login($username);

            if ($user && password_verify($password, $user['password'])) {
                // Login berhasil - set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['id_guru'] = $user['id_guru'];
                $_SESSION['nama_guru'] = $user['nama_guru'];

                redirectByRole();
                return;
            } else {
                $error = 'Username atau password salah!';
            }
        }
    }

    // Tampilkan halaman login
    include __DIR__ . '/../views/auth/login.php';
}

/**
 * Handle proses logout
 */
function handleLogout() {
    // Hapus semua session
    session_unset();
    session_destroy();

    // Redirect ke halaman login
    header("Location: " . BASE_URL . "/controllers/AuthController.php");
    exit();
}

/**
 * Redirect berdasarkan role user
 */
function redirectByRole() {
    if ($_SESSION['role'] === 'admin') {
        header("Location: " . BASE_URL . "/controllers/AdminController.php");
    } else {
        header("Location: " . BASE_URL . "/controllers/GuruDashboardController.php");
    }
    exit();
}
