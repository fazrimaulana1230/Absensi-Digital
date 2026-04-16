<?php
/**
 * Controller Kelas
 * Menangani CRUD data kelas (admin only)
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Kelas.php';

cekAdmin();

$kelasModel = new Kelas($conn);
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

switch ($action) {
    case 'index':
        indexKelas();
        break;
    case 'create':
        createKelas();
        break;
    case 'update':
        updateKelas();
        break;
    case 'delete':
        deleteKelas();
        break;
    default:
        indexKelas();
        break;
}

/**
 * Tampilkan daftar kelas
 */
function indexKelas() {
    global $kelasModel;
    $kelasList = $kelasModel->getAll();
    $pageTitle = 'Data Kelas';
    include __DIR__ . '/../views/admin/kelas.php';
}

/**
 * Tambah kelas baru
 */
function createKelas() {
    global $kelasModel;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama_kelas = sanitize($_POST['nama_kelas'] ?? '');

        if (empty($nama_kelas)) {
            setFlash('danger', 'Nama kelas wajib diisi!');
        } elseif ($kelasModel->create($nama_kelas)) {
            setFlash('success', 'Kelas berhasil ditambahkan!');
        } else {
            setFlash('danger', 'Gagal menambahkan kelas!');
        }
    }

    header("Location: " . BASE_URL . "/controllers/KelasController.php");
    exit();
}

/**
 * Update nama kelas
 */
function updateKelas() {
    global $kelasModel;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = intval($_POST['id_kelas'] ?? 0);
        $nama_kelas = sanitize($_POST['nama_kelas'] ?? '');

        if (empty($nama_kelas)) {
            setFlash('danger', 'Nama kelas wajib diisi!');
        } elseif ($kelasModel->update($id, $nama_kelas)) {
            setFlash('success', 'Data kelas berhasil diupdate!');
        } else {
            setFlash('danger', 'Gagal mengupdate data kelas!');
        }
    }

    header("Location: " . BASE_URL . "/controllers/KelasController.php");
    exit();
}

/**
 * Hapus kelas
 */
function deleteKelas() {
    global $kelasModel;

    $id = intval($_GET['id'] ?? 0);
    if ($id > 0 && $kelasModel->delete($id)) {
        setFlash('success', 'Kelas berhasil dihapus!');
    } else {
        setFlash('danger', 'Gagal menghapus kelas! Pastikan tidak ada siswa di kelas ini.');
    }

    header("Location: " . BASE_URL . "/controllers/KelasController.php");
    exit();
}
