<?php
/**
 * Controller Laporan
 * Menangani laporan absensi oleh admin
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Absensi.php';
require_once __DIR__ . '/../models/Kelas.php';
require_once __DIR__ . '/../models/Siswa.php';

cekAdmin();

$absensiModel = new Absensi($conn);
$kelasModel = new Kelas($conn);
$siswaModel = new Siswa($conn);

// Filter parameter
$tanggal_dari = $_GET['dari'] ?? date('Y-m-01');
$tanggal_sampai = $_GET['sampai'] ?? date('Y-m-d');
$id_kelas = intval($_GET['kelas'] ?? 0);
$id_siswa = intval($_GET['siswa'] ?? 0);

// Ambil data
$kelasList = $kelasModel->getAll();
$siswaList = $siswaModel->getAll();
$laporan = $absensiModel->getLaporan(
    $tanggal_dari,
    $tanggal_sampai,
    $id_kelas > 0 ? $id_kelas : null,
    $id_siswa > 0 ? $id_siswa : null
);

// Hitung statistik laporan
$statsLaporan = ['Hadir' => 0, 'Izin' => 0, 'Sakit' => 0, 'Alpha' => 0];
foreach ($laporan as $row) {
    $statsLaporan[$row['status']]++;
}

$pageTitle = 'Laporan Absensi';
include __DIR__ . '/../views/admin/laporan.php';
