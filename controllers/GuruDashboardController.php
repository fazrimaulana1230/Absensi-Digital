<?php
/**
 * Controller Dashboard Guru
 * Menampilkan dashboard untuk guru
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Guru.php';
require_once __DIR__ . '/../models/Absensi.php';
require_once __DIR__ . '/../models/Siswa.php';

cekGuru();

$guruModel = new Guru($conn);
$absensiModel = new Absensi($conn);

$id_guru = $_SESSION['id_guru'];

// Ambil kelas yang diajar
$kelasList = $guruModel->getKelasGuru($id_guru);

// Hitung total siswa di kelas guru
$totalSiswaGuru = 0;
foreach ($kelasList as $kelas) {
    $siswaModel = new Siswa($conn);
    $siswaDiKelas = $siswaModel->getByKelas($kelas['id_kelas']);
    $totalSiswaGuru += count($siswaDiKelas);
}

// Statistik absensi hari ini oleh guru ini
$riwayatHariIni = $absensiModel->getRiwayatGuru($id_guru, date('Y-m-d'), date('Y-m-d'));
$statsHariIni = ['Hadir' => 0, 'Izin' => 0, 'Sakit' => 0, 'Alpha' => 0];
foreach ($riwayatHariIni as $row) {
    $statsHariIni[$row['status']]++;
}

$pageTitle = 'Dashboard Guru';
include __DIR__ . '/../views/guru/dashboard.php';
