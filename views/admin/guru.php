<?php
/**
 * Halaman Data Guru (Admin)
 * CRUD data guru dengan modal form
 */
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="content-area">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2 print-header">
        <div>
            <h5 style="font-weight: 700; margin-bottom: 0.25rem;">Data Guru</h5>
            <p style="color: var(--text-secondary); font-size: 0.85rem; margin: 0;">
                Total: <strong><?= count($guruList) ?></strong> guru terdaftar
            </p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary-custom no-print animate-in" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Cetak Data
            </button>
            <a href="<?= BASE_URL ?>/controllers/ExportExcelController.php?type=guru" class="btn btn-success no-print animate-in">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
            </a>
            <button class="btn btn-primary-custom no-print" data-bs-toggle="modal" data-bs-target="#addGuruModal">
                <i class="bi bi-plus-lg me-1"></i> Tambah Guru
            </button>
        </div>
    </div>

    <!-- Tabel Guru -->
    <div class="card-dark animate-in">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table-dark-custom">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>NIP</th>
                            <th>Nama Guru</th>
                            <th>Kelas yang Diajar</th>
                            <th style="width: 100px; text-align: center;" class="no-print">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($guruList)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4" style="color: var(--text-muted);">
                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0">Belum ada data guru</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($guruList as $i => $guru): ?>
                                <tr>
                                    <td style="color: var(--text-muted);"><?= $i + 1 ?></td>
                                    <td><code style="color: var(--accent-light); background: rgba(6,182,212,0.1); padding: 2px 8px; border-radius: 4px;"><?= htmlspecialchars($guru['nip'] ?: '-') ?></code></td>
                                    <td style="font-weight: 500;"><?= htmlspecialchars($guru['nama']) ?></td>
                                    <td>
                                        <?php if (!empty($guru['kelas'])): ?>
                                            <?php foreach ($guru['kelas'] as $k): ?>
                                                <span class="badge-status badge-izin me-1"><?= htmlspecialchars($k['nama_kelas']) ?></span>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span style="color: var(--text-muted); font-size: 0.8rem;">Belum diatur</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center no-print">
                                        <button class="btn-action btn-edit" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal"
                                                data-item='<?= json_encode([
                                                    "id_guru" => $guru["id_guru"],
                                                    "nama" => $guru["nama"],
                                                    "nip" => $guru["nip"],
                                                    "kelas_ids" => $guru["kelas_ids"]
                                                ]) ?>'
                                                title="Edit">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <a href="<?= BASE_URL ?>/controllers/GuruController.php?action=delete&id=<?= $guru['id_guru'] ?>" 
                                           class="btn-action btn-delete"
                                           data-confirm="Hapus guru <?= htmlspecialchars($guru['nama']) ?>? Akun login juga akan terhapus."
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

<!-- Modal Tambah Guru -->
<div class="modal fade" id="addGuruModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content modal-content-dark">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Tambah Guru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/controllers/GuruController.php?action=create" data-validate>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label-dark">Nama Guru <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="nama" class="form-control form-control-dark" placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-dark">NIP</label>
                        <input type="text" name="nip" class="form-control form-control-dark" placeholder="Masukkan NIP (opsional)">
                    </div>
                    <hr style="border-color: var(--border-color);">
                    <p class="form-label-dark mb-2"><i class="bi bi-key me-1"></i>Akun Login</p>
                    <div class="mb-3">
                        <label class="form-label-dark">Username <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="username" class="form-control form-control-dark" placeholder="Username untuk login" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-dark">Password <span style="color: var(--danger);">*</span></label>
                        <input type="password" name="password" class="form-control form-control-dark" placeholder="Password untuk login" required>
                    </div>
                    <hr style="border-color: var(--border-color);">
                    <div class="mb-3">
                        <label class="form-label-dark">Kelas yang Diajar</label>
                        <select name="kelas_ids[]" class="form-select form-select-dark" multiple size="4">
                            <?php foreach ($kelasList as $kelas): ?>
                                <option value="<?= $kelas['id_kelas'] ?>"><?= htmlspecialchars($kelas['nama_kelas']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small style="color: var(--text-muted);">Tekan Ctrl + klik untuk pilih beberapa kelas</small>
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

<!-- Modal Edit Guru -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content modal-content-dark">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Guru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/controllers/GuruController.php?action=update" data-validate>
                <input type="hidden" name="id_guru">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label-dark">Nama Guru <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="nama" class="form-control form-control-dark" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-dark">NIP</label>
                        <input type="text" name="nip" class="form-control form-control-dark">
                    </div>
                    <div class="mb-3">
                        <label class="form-label-dark">Kelas yang Diajar</label>
                        <select name="kelas_ids[]" class="form-select form-select-dark" multiple size="4">
                            <?php foreach ($kelasList as $kelas): ?>
                                <option value="<?= $kelas['id_kelas'] ?>"><?= htmlspecialchars($kelas['nama_kelas']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small style="color: var(--text-muted);">Tekan Ctrl + klik untuk pilih beberapa kelas</small>
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
