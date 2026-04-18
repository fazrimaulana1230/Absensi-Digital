<?php
/**
 * Halaman Data Siswa (Admin)
 * CRUD data siswa dengan modal form
 */
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="content-area">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2 print-header">
        <div>
            <h5 style="font-weight: 700; margin-bottom: 0.25rem;">Data Siswa</h5>
            <p style="color: var(--text-secondary); font-size: 0.85rem; margin: 0;">
                Total: <strong><?= count($siswaList) ?></strong> siswa terdaftar
            </p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary-custom no-print animate-in" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Cetak Data
            </button>
            <a href="<?= BASE_URL ?>/controllers/ExportExcelController.php?type=siswa" class="btn btn-success no-print animate-in">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
            </a>
            <button class="btn btn-primary-custom no-print" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-lg me-1"></i> Tambah Siswa
            </button>
        </div>
    </div>

    <!-- Tabel Siswa -->
    <div class="card-dark animate-in">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table-dark-custom" id="tableSiswa">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th style="width: 100px; text-align: center;" class="no-print">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($siswaList)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4" style="color: var(--text-muted);">
                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0">Belum ada data siswa</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($siswaList as $i => $siswa): ?>
                                <tr>
                                    <td style="color: var(--text-muted);"><?= $i + 1 ?></td>
                                    <td><code style="color: var(--accent-light); background: rgba(6,182,212,0.1); padding: 2px 8px; border-radius: 4px;"><?= htmlspecialchars($siswa['nis']) ?></code></td>
                                    <td style="font-weight: 500;"><?= htmlspecialchars($siswa['nama']) ?></td>
                                    <td><span class="badge-status badge-hadir"><?= htmlspecialchars($siswa['nama_kelas']) ?></span></td>
                                    <td class="text-center no-print">
                                        <button class="btn-action btn-edit" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal"
                                                data-item='<?= json_encode([
                                                    "id_siswa" => $siswa["id_siswa"],
                                                    "nama" => $siswa["nama"],
                                                    "nis" => $siswa["nis"],
                                                    "id_kelas" => $siswa["id_kelas"]
                                                ]) ?>'
                                                title="Edit">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <a href="<?= BASE_URL ?>/controllers/SiswaController.php?action=delete&id=<?= $siswa['id_siswa'] ?>" 
                                           class="btn-action btn-delete"
                                           data-confirm="Hapus siswa <?= htmlspecialchars($siswa['nama']) ?>?"
                                           title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </a>
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

<!-- Modal Tambah Siswa -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content modal-content-dark">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Tambah Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/controllers/SiswaController.php?action=create" data-validate>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label-dark">Nama Siswa <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="nama" class="form-control form-control-dark" placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-dark">NIS <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="nis" class="form-control form-control-dark" placeholder="Masukkan NIS" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-dark">Kelas <span style="color: var(--danger);">*</span></label>
                        <select name="id_kelas" class="form-select form-select-dark" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($kelasList as $kelas): ?>
                                <option value="<?= $kelas['id_kelas'] ?>"><?= htmlspecialchars($kelas['nama_kelas']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm" style="color: var(--text-secondary);" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom btn-sm">
                        <i class="bi bi-check-lg me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Siswa -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content modal-content-dark">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/controllers/SiswaController.php?action=update" data-validate>
                <input type="hidden" name="id_siswa">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label-dark">Nama Siswa <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="nama" class="form-control form-control-dark" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-dark">NIS <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="nis" class="form-control form-control-dark" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-dark">Kelas <span style="color: var(--danger);">*</span></label>
                        <select name="id_kelas" class="form-select form-select-dark" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($kelasList as $kelas): ?>
                                <option value="<?= $kelas['id_kelas'] ?>"><?= htmlspecialchars($kelas['nama_kelas']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm" style="color: var(--text-secondary);" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom btn-sm">
                        <i class="bi bi-check-lg me-1"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</div><!-- /.main-content -->

<?php include __DIR__ . '/../layout/footer.php'; ?>
