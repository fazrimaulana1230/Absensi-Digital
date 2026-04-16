<?php
/**
 * Halaman Riwayat Absensi (Guru)
 * Menampilkan data absensi yang sudah dicatat guru
 */
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="content-area">
    <!-- Header -->
    <div class="mb-4 animate-in">
        <h5 style="font-weight: 700; margin-bottom: 0.25rem;">Riwayat Absensi</h5>
        <p style="color: var(--text-secondary); font-size: 0.85rem; margin: 0;">
            Menampilkan <strong><?= count($riwayat) ?></strong> data absensi yang telah Anda catat
        </p>
    </div>

    <!-- Filter -->
    <div class="card-dark mb-4 animate-in">
        <div class="card-header">
            <h6><i class="bi bi-funnel me-2"></i>Filter</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?= BASE_URL ?>/controllers/AbsensiController.php">
                <input type="hidden" name="action" value="riwayat">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label-dark">Dari Tanggal</label>
                        <input type="date" name="dari" class="form-control form-control-dark" 
                               value="<?= htmlspecialchars($tanggal_dari) ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-dark">Sampai Tanggal</label>
                        <input type="date" name="sampai" class="form-control form-control-dark" 
                               value="<?= htmlspecialchars($tanggal_sampai) ?>">
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary-custom w-100">
                            <i class="bi bi-search me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Riwayat -->
    <div class="card-dark animate-in">
        <div class="card-header">
            <h6><i class="bi bi-clock-history me-2"></i>Data Riwayat</h6>
            <span style="font-size: 0.75rem; color: var(--text-muted);">
                <?= date('d/m/Y', strtotime($tanggal_dari)) ?> - <?= date('d/m/Y', strtotime($tanggal_sampai)) ?>
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($riwayat)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4" style="color: var(--text-muted);">
                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0">Tidak ada data riwayat absensi</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($riwayat as $i => $row): ?>
                                <tr>
                                    <td style="color: var(--text-muted);"><?= $i + 1 ?></td>
                                    <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                    <td>
                                        <code style="color: var(--accent-light); background: rgba(6,182,212,0.1); padding: 2px 8px; border-radius: 4px;">
                                            <?= htmlspecialchars($row['nis']) ?>
                                        </code>
                                    </td>
                                    <td style="font-weight: 500;"><?= htmlspecialchars($row['nama']) ?></td>
                                    <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                                    <td>
                                        <span class="badge-status badge-<?= strtolower($row['status']) ?>">
                                            <?= $row['status'] ?>
                                        </span>
                                    </td>
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
