<?php
/**
 * Halaman Input Absensi (Guru)
 * Form untuk mencatat kehadiran siswa per kelas per tanggal
 */
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="content-area">
    <!-- Header -->
    <div class="mb-4 animate-in">
        <h5 style="font-weight: 700; margin-bottom: 0.25rem;">Input Absensi</h5>
        <p style="color: var(--text-secondary); font-size: 0.85rem; margin: 0;">
            Pilih kelas dan tanggal untuk mencatat kehadiran siswa
        </p>
    </div>

    <!-- Pilih Kelas & Tanggal -->
    <div class="card-dark mb-4 animate-in">
        <div class="card-header">
            <h6><i class="bi bi-funnel me-2"></i>Pilih Kelas & Tanggal</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?= BASE_URL ?>/controllers/AbsensiController.php">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label-dark">Kelas</label>
                        <select name="kelas" class="form-select form-select-dark auto-submit" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($kelasList as $kelas): ?>
                                <option value="<?= $kelas['id_kelas'] ?>" <?= $id_kelas == $kelas['id_kelas'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($kelas['nama_kelas']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-dark">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control form-control-dark auto-submit" 
                               value="<?= htmlspecialchars($tanggal) ?>">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary-custom w-100">
                            <i class="bi bi-search me-1"></i>Tampilkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Form Absensi -->
    <?php if ($id_kelas > 0 && !empty($siswaAbsensi)): ?>
        <div class="card-dark animate-in">
            <div class="card-header">
                <h6>
                    <i class="bi bi-clipboard-check me-2"></i>
                    Daftar Siswa
                    <?php 
                    // Ambil nama kelas
                    foreach ($kelasList as $k) {
                        if ($k['id_kelas'] == $id_kelas) {
                            echo '- ' . htmlspecialchars($k['nama_kelas']);
                            break;
                        }
                    }
                    ?>
                </h6>
                <span style="font-size: 0.8rem; color: var(--text-muted);">
                    <?= date('d/m/Y', strtotime($tanggal)) ?>
                </span>
            </div>
            <div class="card-body">
                <!-- Quick Select Buttons -->
                <div class="quick-absensi no-print">
                    <span style="font-size: 0.75rem; color: var(--text-muted); align-self: center; margin-right: 0.5rem;">
                        <i class="bi bi-lightning me-1"></i>Set semua:
                    </span>
                    <button type="button" class="btn-quick quick-hadir" data-status="Hadir">✓ Hadir</button>
                    <button type="button" class="btn-quick quick-izin" data-status="Izin">📝 Izin</button>
                    <button type="button" class="btn-quick quick-sakit" data-status="Sakit">🤒 Sakit</button>
                    <button type="button" class="btn-quick quick-alpha" data-status="Alpha">✗ Alpha</button>
                </div>

                <form method="POST" action="<?= BASE_URL ?>/controllers/AbsensiController.php?action=save">
                    <input type="hidden" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>">
                    <input type="hidden" name="id_kelas" value="<?= $id_kelas ?>">

                    <div class="table-responsive">
                        <table class="table-dark-custom">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Status Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($siswaAbsensi as $i => $siswa): ?>
                                    <tr>
                                        <td style="color: var(--text-muted);"><?= $i + 1 ?></td>
                                        <td>
                                            <code style="color: var(--accent-light); background: rgba(6,182,212,0.1); padding: 2px 8px; border-radius: 4px;">
                                                <?= htmlspecialchars($siswa['nis']) ?>
                                            </code>
                                        </td>
                                        <td style="font-weight: 500;"><?= htmlspecialchars($siswa['nama']) ?></td>
                                        <td>
                                            <div class="absensi-radio-group">
                                                <?php
                                                $statuses = ['Hadir', 'Izin', 'Sakit', 'Alpha'];
                                                $currentStatus = $siswa['status'] ?? 'Hadir';
                                                foreach ($statuses as $s):
                                                    $inputId = 'status_' . $siswa['id_siswa'] . '_' . strtolower($s);
                                                ?>
                                                    <input type="radio" 
                                                           class="btn-check" 
                                                           name="status[<?= $siswa['id_siswa'] ?>]" 
                                                           id="<?= $inputId ?>" 
                                                           value="<?= $s ?>"
                                                           <?= $currentStatus === $s ? 'checked' : '' ?>>
                                                    <label class="btn-absensi btn-absensi-<?= strtolower($s) ?>" 
                                                           for="<?= $inputId ?>">
                                                        <?= $s ?>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="bi bi-check2-all me-1"></i>Simpan Absensi
                        </button>
                    </div>
                </form>
            </div>
        </div>

    <?php elseif ($id_kelas > 0 && empty($siswaAbsensi)): ?>
        <div class="card-dark animate-in">
            <div class="card-body text-center py-5" style="color: var(--text-muted);">
                <i class="bi bi-people" style="font-size: 3rem;"></i>
                <p class="mt-2 mb-0">Tidak ada siswa di kelas ini</p>
            </div>
        </div>
    <?php else: ?>
        <div class="card-dark animate-in">
            <div class="card-body text-center py-5" style="color: var(--text-muted);">
                <i class="bi bi-hand-index-thumb" style="font-size: 3rem;"></i>
                <p class="mt-2 mb-0">Silakan pilih kelas dan tanggal terlebih dahulu</p>
            </div>
        </div>
    <?php endif; ?>
</div>

</div><!-- /.main-content -->

<?php include __DIR__ . '/../layout/footer.php'; ?>
