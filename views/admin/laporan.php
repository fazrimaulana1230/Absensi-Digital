<?php
/**
 * Halaman Laporan Absensi (Admin)
 * Dengan filter dan fitur print
 */
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="content-area">
    <?php
    $nama_kelas_title = "Semua Kelas";
    if ($id_kelas > 0) {
        foreach ($kelasList as $kelas) {
            if ($kelas['id_kelas'] == $id_kelas) {
                $nama_kelas_title = "Kelas " . trim($kelas['nama_kelas']);
                break;
            }
        }
    }
    ?>
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2 print-header">
        <div>
            <h5 style="font-weight: 700; margin-bottom: 0.25rem;">Laporan Absensi <?= htmlspecialchars($nama_kelas_title) ?></h5>
            <p style="color: var(--text-secondary); font-size: 0.85rem; margin: 0;">
                Periode: <?= date('d/m/Y', strtotime($tanggal_dari)) ?> - <?= date('d/m/Y', strtotime($tanggal_sampai)) ?>
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>/controllers/ExportExcelController.php?<?= http_build_query($_GET) ?>" class="btn btn-success no-print">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
            </a>
            <button class="btn btn-primary-custom no-print" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Cetak Laporan
            </button>
        </div>
    </div>

    <!-- Filter -->
    <div class="card-dark mb-4 no-print animate-in">
        <div class="card-header">
            <h6><i class="bi bi-funnel me-2"></i>Filter Data</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?= BASE_URL ?>/controllers/LaporanController.php">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label-dark">Tanggal Dari</label>
                        <input type="date" name="dari" class="form-control form-control-dark" 
                               value="<?= htmlspecialchars($tanggal_dari) ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-dark">Tanggal Sampai</label>
                        <input type="date" name="sampai" class="form-control form-control-dark" 
                               value="<?= htmlspecialchars($tanggal_sampai) ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label-dark">Kelas</label>
                        <select name="kelas" class="form-select form-select-dark">
                            <option value="0">Semua Kelas</option>
                            <?php foreach ($kelasList as $kelas): ?>
                                <option value="<?= $kelas['id_kelas'] ?>" <?= $id_kelas == $kelas['id_kelas'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($kelas['nama_kelas']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label-dark">Siswa</label>
                        <select name="siswa" class="form-select form-select-dark">
                            <option value="0">Semua Siswa</option>
                            <?php foreach ($siswaList as $s): ?>
                                <option value="<?= $s['id_siswa'] ?>" <?= $id_siswa == $s['id_siswa'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($s['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary-custom w-100">
                            <i class="bi bi-search me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistik Ringkasan -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card card-success animate-in" style="padding: 1rem;">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon" style="width: 36px; height: 36px; font-size: 1rem;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <div class="stat-value" style="font-size: 1.25rem;"><?= $statsLaporan['Hadir'] ?></div>
                        <div class="stat-label" style="font-size: 0.7rem;">Hadir</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card card-info animate-in" style="padding: 1rem;">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon" style="width: 36px; height: 36px; font-size: 1rem;">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <div>
                        <div class="stat-value" style="font-size: 1.25rem;"><?= $statsLaporan['Izin'] ?></div>
                        <div class="stat-label" style="font-size: 0.7rem;">Izin</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card card-warning animate-in" style="padding: 1rem;">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon" style="width: 36px; height: 36px; font-size: 1rem;">
                        <i class="bi bi-bandaid-fill"></i>
                    </div>
                    <div>
                        <div class="stat-value" style="font-size: 1.25rem;"><?= $statsLaporan['Sakit'] ?></div>
                        <div class="stat-label" style="font-size: 0.7rem;">Sakit</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card card-danger animate-in" style="padding: 1rem;">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon" style="width: 36px; height: 36px; font-size: 1rem;">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <div>
                        <div class="stat-value" style="font-size: 1.25rem;"><?= $statsLaporan['Alpha'] ?></div>
                        <div class="stat-label" style="font-size: 0.7rem;">Alpha</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Laporan -->
    <div class="card-dark animate-in">
        <div class="card-header">
            <h6><i class="bi bi-table me-2"></i>Data Absensi</h6>
            <span style="font-size: 0.75rem; color: var(--text-muted);">
                Periode: <?= date('d/m/Y', strtotime($tanggal_dari)) ?> - <?= date('d/m/Y', strtotime($tanggal_sampai)) ?>
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table-dark-custom">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Status</th>
                            <th>Guru</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($laporan)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4" style="color: var(--text-muted);">
                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0">Tidak ada data untuk filter yang dipilih</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($laporan as $i => $row): ?>
                                <tr>
                                    <td style="color: var(--text-muted);"><?= $i + 1 ?></td>
                                    <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                    <td><code style="color: var(--accent-light); background: rgba(6,182,212,0.1); padding: 2px 8px; border-radius: 4px;"><?= htmlspecialchars($row['nis']) ?></code></td>
                                    <td style="font-weight: 500;"><?= htmlspecialchars($row['nama_siswa']) ?></td>
                                    <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                                    <td>
                                        <?php
                                        $badgeClass = 'badge-' . strtolower($row['status']);
                                        ?>
                                        <span class="badge-status <?= $badgeClass ?>"><?= $row['status'] ?></span>
                                    </td>
                                    <td style="color: var(--text-secondary);"><?= htmlspecialchars($row['nama_guru']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</div><!-- /.main-content -->

<?php include __DIR__ . '/../layout/footer.php'; ?>
