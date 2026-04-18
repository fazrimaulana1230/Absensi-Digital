<?php
/**
 * Controller ExportExcel
 * Menangani fungsi ekspor tabel data (Laporan/Riwayat, Siswa, Guru) ke Microsoft Excel
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Absensi.php';
require_once __DIR__ . '/../models/Siswa.php';
require_once __DIR__ . '/../models/Guru.php';
require_once __DIR__ . '/../models/Kelas.php';
require_once __DIR__ . '/../models/User.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "/views/auth/login.php");
    exit();
}

$role = $_SESSION['role'] ?? '';
$type = $_GET['type'] ?? 'absensi'; // absensi, siswa, guru

$absensiModel = new Absensi($conn);
$siswaModel = new Siswa($conn);
$guruModel = new Guru($conn);
$kelasModel = new Kelas($conn);
$userModel = new User($conn);

$data = [];
$filename = "Export.xls";
$title = "Data Export";

$colspan = 1;

if ($type === 'siswa' && $role === 'admin') {
    $data = $siswaModel->getAll();
    $filename = "Data_Siswa_" . date('Ymd_His') . ".xls";
    $title = "Data Siswa";
    $colspan = 4;
} elseif ($type === 'guru' && $role === 'admin') {
    $data = $guruModel->getAll();
    foreach ($data as &$guru) {
        $guru['kelas'] = $guruModel->getKelasGuru($guru['id_guru']);
        $user = $userModel->getByGuruId($guru['id_guru']);
        $guru['username'] = $user ? $user['username'] : '-';
    }
    $filename = "Data_Guru_" . date('Ymd_His') . ".xls";
    $title = "Data Guru";
    $colspan = 5;
} elseif ($type === 'absensi') {
    $tanggal_dari = $_GET['dari'] ?? date('Y-m-01');
    $tanggal_sampai = $_GET['sampai'] ?? date('Y-m-d');
    $id_kelas = intval($_GET['kelas'] ?? 0);
    $id_siswa = intval($_GET['siswa'] ?? 0);
    
    // Resolve Nama Kelas untuk Title
    $nama_kelas = "Semua Kelas";
    if ($id_kelas > 0) {
        $k = $kelasModel->getById($id_kelas);
        if ($k) {
            $nama_kelas = "Kelas " . $k['nama_kelas'];
        }
    }
    
    $title = "Absensi Siswa " . $nama_kelas;
    
    if ($role === 'admin') {
        $data = $absensiModel->getLaporan(
            $tanggal_dari,
            $tanggal_sampai,
            $id_kelas > 0 ? $id_kelas : null,
            $id_siswa > 0 ? $id_siswa : null
        );

        $filename = "Laporan_Absensi_" . date('Ymd_His') . ".xls";
        $colspan = 7;
    } elseif ($role === 'guru') {
        $id_guru = $_SESSION['id_guru'];

        $data = $absensiModel->getRiwayatGuru(
            $id_guru,
            $tanggal_dari,
            $tanggal_sampai,
            $id_kelas > 0 ? $id_kelas : null
        );
        
        $filename = "Riwayat_Absensi_Guru_" . date('Ymd_His') . ".xls";
        $colspan = 6;
    }
} else {
    echo "Akses ditolak.";
    exit();
}

// Konfigurasi HTTP Headers agar file terunduh sebagai format Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export Excel</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; vertical-align: top; }
        .header-cell { background-color: #e2efd9; font-weight: bold; text-align: center; }
        .no-border { border: none !important; }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <td colspan="<?= $colspan ?>" align="center" style="font-size: 16px; font-weight: bold; border: none; text-align: center;">
                    <?= htmlspecialchars($title) ?>
                </td>
            </tr>
            <tr>
                <td colspan="<?= $colspan ?>" align="center" style="font-size: 12px; border: none; text-align: center;">
                    <?php if ($type === 'absensi'): ?>
                        Periode: <?= date('d/m/Y', strtotime($tanggal_dari)) ?> - <?= date('d/m/Y', strtotime($tanggal_sampai)) ?>
                    <?php else: ?>
                        Diekspor pada: <?= date('d/m/Y H:i:s') ?>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <!-- Spacer -->
                <td colspan="<?= $colspan ?>" style="border: none; height: 10px;"></td>
            </tr>
            <tr>
                <?php if ($type === 'siswa'): ?>
                    <th class="header-cell">No</th>
                    <th class="header-cell">NIS</th>
                    <th class="header-cell">Nama Siswa</th>
                    <th class="header-cell">Kelas</th>
                <?php elseif ($type === 'guru'): ?>
                    <th class="header-cell">No</th>
                    <th class="header-cell">NIP</th>
                    <th class="header-cell">Nama Guru</th>
                    <th class="header-cell">Username Login</th>
                    <th class="header-cell">Kelas yang Diajar</th>
                <?php elseif ($type === 'absensi'): ?>
                    <th class="header-cell">No</th>
                    <th class="header-cell">Tanggal</th>
                    <th class="header-cell">NIS</th>
                    <th class="header-cell">Nama Siswa</th>
                    <th class="header-cell">Kelas</th>
                    <th class="header-cell">Status</th>
                    <?php if ($role === 'admin'): ?>
                    <th class="header-cell">Guru Pengajar</th>
                    <?php endif; ?>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data)): ?>
                <tr>
                    <td colspan="<?= $colspan ?>" style="text-align:center;">Tidak ada data</td>
                </tr>
            <?php else: ?>
                <?php foreach ($data as $i => $row): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        
                        <?php if ($type === 'siswa'): ?>
                            <td style="mso-number-format:'\@';"><?= htmlspecialchars($row['nis']) ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                            
                        <?php elseif ($type === 'guru'): ?>
                            <td style="mso-number-format:'\@';"><?= htmlspecialchars($row['nip'] ?: '-') ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td>
                                <?php 
                                    if (!empty($row['kelas'])) {
                                        $kelasArr = array_map(function($k) { return $k['nama_kelas']; }, $row['kelas']);
                                        echo htmlspecialchars(implode(', ', $kelasArr));
                                    } else {
                                        echo "Belum diatur";
                                    }
                                ?>
                            </td>
                            
                        <?php elseif ($type === 'absensi'): ?>
                            <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                            <td style="mso-number-format:'\@';"><?= htmlspecialchars($row['nis']) ?></td>
                            <td><?= htmlspecialchars($row['nama_siswa'] ?? $row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                            <td><?= $row['status'] ?></td>
                            <?php if ($role === 'admin'): ?>
                            <td><?= htmlspecialchars($row['nama_guru']) ?></td>
                            <?php endif; ?>
                            
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
