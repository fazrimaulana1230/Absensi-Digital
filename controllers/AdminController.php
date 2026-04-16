<?php
/**
 * Controller Admin
 * Menangani dashboard admin dan routing ke sub-controller
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Siswa.php';
require_once __DIR__ . '/../models/Guru.php';
require_once __DIR__ . '/../models/Kelas.php';
require_once __DIR__ . '/../models/Absensi.php';

// Cek apakah user adalah admin
cekAdmin();

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Inisialisasi model
$siswaModel = new Siswa($conn);
$guruModel = new Guru($conn);
$kelasModel = new Kelas($conn);
$absensiModel = new Absensi($conn);

switch ($page) {
    case 'dashboard':
        showDashboard();
        break;
    default:
        showDashboard();
        break;
}

/**
 * Tampilkan dashboard admin dengan statistik
 */
function showDashboard() {
    global $siswaModel, $guruModel, $kelasModel, $absensiModel;

    // Ambil statistik
    $totalSiswa = $siswaModel->count();
    $totalGuru = $guruModel->count();
    $totalKelas = $kelasModel->count();
    $totalAbsensi = $absensiModel->countToday();
    $statsHariIni = $absensiModel->getStatistik(date('Y-m-d'));

    // Load view
    $pageTitle = 'Dashboard Admin';
    include __DIR__ . '/../views/admin/dashboard.php';
}
