<?php
/**
 * Controller Absensi
 * Menangani input absensi oleh guru
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Absensi.php';
require_once __DIR__ . '/../models/Guru.php';
require_once __DIR__ . '/../models/Siswa.php';
require_once __DIR__ . '/../models/Kelas.php';

cekGuru();

$absensiModel = new Absensi($conn);
$guruModel = new Guru($conn);
$siswaModel = new Siswa($conn);
$kelasModel = new Kelas($conn);
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

switch ($action) {
    case 'index':
        indexAbsensi();
        break;
    case 'save':
        saveAbsensi();
        break;
    case 'riwayat':
        riwayatAbsensi();
        break;
    default:
        indexAbsensi();
        break;
}

/**
 * Tampilkan form input absensi
 */
function indexAbsensi() {
    global $guruModel, $absensiModel;

    $id_guru = $_SESSION['id_guru'];

    // Ambil kelas yang diajar guru ini
    $kelasList = $guruModel->getKelasGuru($id_guru);

    // Jika kelas dipilih, ambil siswa dan absensi
    $id_kelas = intval($_GET['kelas'] ?? 0);
    $tanggal = $_GET['tanggal'] ?? date('Y-m-d');
    $siswaAbsensi = [];

    if ($id_kelas > 0) {
        $siswaAbsensi = $absensiModel->getByKelasAndTanggal($id_kelas, $tanggal);
    }

    $pageTitle = 'Input Absensi';
    include __DIR__ . '/../views/guru/absensi.php';
}

/**
 * Simpan data absensi
 */
function saveAbsensi() {
    global $absensiModel;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_guru = $_SESSION['id_guru'];
        $tanggal = sanitize($_POST['tanggal'] ?? date('Y-m-d'));
        $id_kelas = intval($_POST['id_kelas'] ?? 0);
        $absensi = $_POST['status'] ?? [];

        if (empty($absensi)) {
            setFlash('danger', 'Tidak ada data absensi yang dikirim!');
        } else {
            $success = true;
            foreach ($absensi as $id_siswa => $status) {
                if (!$absensiModel->saveAbsensi(intval($id_siswa), $id_guru, $tanggal, sanitize($status))) {
                    $success = false;
                }
            }

            if ($success) {
                setFlash('success', 'Absensi berhasil disimpan!');
            } else {
                setFlash('warning', 'Sebagian data absensi gagal disimpan.');
            }
        }
    }

    $kelas = intval($_POST['id_kelas'] ?? 0);
    $tanggal = sanitize($_POST['tanggal'] ?? date('Y-m-d'));
    header("Location: " . BASE_URL . "/controllers/AbsensiController.php?kelas=$kelas&tanggal=$tanggal");
    exit();
}

/**
 * Tampilkan riwayat absensi guru
 */
function riwayatAbsensi() {
    global $absensiModel, $guruModel;

    $id_guru = $_SESSION['id_guru'];

    // Filter
    $tanggal_dari = $_GET['dari'] ?? date('Y-m-01');
    $tanggal_sampai = $_GET['sampai'] ?? date('Y-m-d');
    $id_kelas = intval($_GET['kelas'] ?? 0);

    // Ambil kelas yang diajar
    $kelasList = $guruModel->getKelasGuru($id_guru);

    // Ambil riwayat
    $riwayat = $absensiModel->getRiwayatGuru($id_guru, $tanggal_dari, $tanggal_sampai, $id_kelas > 0 ? $id_kelas : null);

    $pageTitle = 'Riwayat Absensi';
    include __DIR__ . '/../views/guru/riwayat.php';
}
