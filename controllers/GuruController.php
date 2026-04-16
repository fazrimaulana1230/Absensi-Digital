<?php
/**
 * Controller Guru (Admin)
 * Menangani CRUD data guru oleh admin
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Guru.php';
require_once __DIR__ . '/../models/Kelas.php';
require_once __DIR__ . '/../models/User.php';

cekAdmin();

$guruModel = new Guru($conn);
$kelasModel = new Kelas($conn);
$userModel = new User($conn);
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

switch ($action) {
    case 'index':
        indexGuru();
        break;
    case 'create':
        createGuru();
        break;
    case 'update':
        updateGuru();
        break;
    case 'delete':
        deleteGuru();
        break;
    default:
        indexGuru();
        break;
}

/**
 * Tampilkan daftar guru
 */
function indexGuru() {
    global $guruModel, $kelasModel;
    $guruList = $guruModel->getAll();
    $kelasList = $kelasModel->getAll();

    // Untuk setiap guru, ambil kelas yang diajar
    foreach ($guruList as &$guru) {
        $guru['kelas'] = $guruModel->getKelasGuru($guru['id_guru']);
        $guru['kelas_ids'] = $guruModel->getKelasIds($guru['id_guru']);
    }

    $pageTitle = 'Data Guru';
    include __DIR__ . '/../views/admin/guru.php';
}

/**
 * Tambah guru baru + buat akun user
 */
function createGuru() {
    global $guruModel, $userModel;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama = sanitize($_POST['nama'] ?? '');
        $nip = sanitize($_POST['nip'] ?? '');
        $username = sanitize($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $kelas_ids = $_POST['kelas_ids'] ?? [];

        if (empty($nama) || empty($username) || empty($password)) {
            setFlash('danger', 'Nama, username, dan password wajib diisi!');
        } else {
            // Buat data guru
            $id_guru = $guruModel->create($nama, $nip);
            if ($id_guru) {
                // Buat akun user untuk guru
                $userModel->create($username, $password, 'guru', $id_guru);

                // Set kelas yang diajar
                if (!empty($kelas_ids)) {
                    $guruModel->updateKelasGuru($id_guru, $kelas_ids);
                }

                setFlash('success', 'Guru berhasil ditambahkan!');
            } else {
                setFlash('danger', 'Gagal menambahkan guru!');
            }
        }
    }

    header("Location: " . BASE_URL . "/controllers/GuruController.php");
    exit();
}

/**
 * Update data guru
 */
function updateGuru() {
    global $guruModel;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = intval($_POST['id_guru'] ?? 0);
        $nama = sanitize($_POST['nama'] ?? '');
        $nip = sanitize($_POST['nip'] ?? '');
        $kelas_ids = $_POST['kelas_ids'] ?? [];

        if (empty($nama)) {
            setFlash('danger', 'Nama guru wajib diisi!');
        } elseif ($guruModel->update($id, $nama, $nip)) {
            // Update kelas yang diajar
            $guruModel->updateKelasGuru($id, $kelas_ids);
            setFlash('success', 'Data guru berhasil diupdate!');
        } else {
            setFlash('danger', 'Gagal mengupdate data guru!');
        }
    }

    header("Location: " . BASE_URL . "/controllers/GuruController.php");
    exit();
}

/**
 * Hapus guru
 */
function deleteGuru() {
    global $guruModel;

    $id = intval($_GET['id'] ?? 0);
    if ($id > 0 && $guruModel->delete($id)) {
        setFlash('success', 'Guru berhasil dihapus!');
    } else {
        setFlash('danger', 'Gagal menghapus guru!');
    }

    header("Location: " . BASE_URL . "/controllers/GuruController.php");
    exit();
}
