<?php
/**
 * Controller Siswa
 * Menangani CRUD data siswa (admin only)
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Siswa.php';
require_once __DIR__ . '/../models/Kelas.php';

cekAdmin();

$siswaModel = new Siswa($conn);
$kelasModel = new Kelas($conn);
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

switch ($action) {
    case 'index':
        indexSiswa();
        break;
    case 'create':
        createSiswa();
        break;
    case 'update':
        updateSiswa();
        break;
    case 'delete':
        deleteSiswa();
        break;
    default:
        indexSiswa();
        break;
}

/**
 * Tampilkan daftar siswa
 */
function indexSiswa() {
    global $siswaModel, $kelasModel;
    $siswaList = $siswaModel->getAll();
    $kelasList = $kelasModel->getAll();
    $pageTitle = 'Data Siswa';
    include __DIR__ . '/../views/admin/siswa.php';
}

/**
 * Tambah siswa baru
 */
function createSiswa() {
    global $siswaModel;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama = sanitize($_POST['nama'] ?? '');
        $nis = sanitize($_POST['nis'] ?? '');
        $id_kelas = intval($_POST['id_kelas'] ?? 0);

        // Validasi
        if (empty($nama) || empty($nis) || $id_kelas <= 0) {
            setFlash('danger', 'Semua field wajib diisi!');
        } elseif ($siswaModel->nisExists($nis)) {
            setFlash('danger', 'NIS sudah digunakan!');
        } elseif ($siswaModel->create($nama, $nis, $id_kelas)) {
            setFlash('success', 'Siswa berhasil ditambahkan!');
        } else {
            setFlash('danger', 'Gagal menambahkan siswa!');
        }
    }

    header("Location: " . BASE_URL . "/controllers/SiswaController.php");
    exit();
}

/**
 * Update data siswa
 */
function updateSiswa() {
    global $siswaModel;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = intval($_POST['id_siswa'] ?? 0);
        $nama = sanitize($_POST['nama'] ?? '');
        $nis = sanitize($_POST['nis'] ?? '');
        $id_kelas = intval($_POST['id_kelas'] ?? 0);

        if (empty($nama) || empty($nis) || $id_kelas <= 0) {
            setFlash('danger', 'Semua field wajib diisi!');
        } elseif ($siswaModel->nisExists($nis, $id)) {
            setFlash('danger', 'NIS sudah digunakan siswa lain!');
        } elseif ($siswaModel->update($id, $nama, $nis, $id_kelas)) {
            setFlash('success', 'Data siswa berhasil diupdate!');
        } else {
            setFlash('danger', 'Gagal mengupdate data siswa!');
        }
    }

    header("Location: " . BASE_URL . "/controllers/SiswaController.php");
    exit();
}

/**
 * Hapus siswa
 */
function deleteSiswa() {
    global $siswaModel;

    $id = intval($_GET['id'] ?? 0);
    if ($id > 0 && $siswaModel->delete($id)) {
        setFlash('success', 'Siswa berhasil dihapus!');
    } else {
        setFlash('danger', 'Gagal menghapus siswa!');
    }

    header("Location: " . BASE_URL . "/controllers/SiswaController.php");
    exit();
}
