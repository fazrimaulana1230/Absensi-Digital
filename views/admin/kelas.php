<?php
/**
 * Halaman Data Kelas (Admin)
 * CRUD data kelas dengan modal form
 */
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="content-area">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h5 style="font-weight: 700; margin-bottom: 0.25rem;">Data Kelas</h5>
            <p style="color: var(--text-secondary); font-size: 0.85rem; margin: 0;">
                Total: <strong><?= count($kelasList) ?></strong> kelas
            </p>
        </div>
        <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addKelasModal">
            <i class="bi bi-plus-lg me-1"></i> Tambah Kelas
        </button>
    </div>

    <!-- Grid Kelas -->
    <div class="row g-3">
        <?php if (empty($kelasList)): ?>
            <div class="col-12">
                <div class="card-dark">
                    <div class="card-body text-center py-5" style="color: var(--text-muted);">
                        <i class="bi bi-building" style="font-size: 3rem;"></i>
                        <p class="mt-2 mb-0">Belum ada data kelas</p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($kelasList as $i => $kelas): ?>
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="card-dark animate-in" style="position: relative; overflow: hidden;">
                        <div style="position: absolute; top: 0; left: 0; right: 0; height: 3px; background: var(--gradient-primary);"></div>
                        <div class="card-body text-center py-4">
                            <div style="width: 56px; height: 56px; border-radius: 16px; background: rgba(99,102,241,0.12); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 1.5rem;">
                                <i class="bi bi-building" style="color: var(--primary-light);"></i>
                            </div>
                            <h6 style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem;">
                                <?= htmlspecialchars($kelas['nama_kelas']) ?>
                            </h6>
                            <p style="color: var(--text-secondary); font-size: 0.8rem; margin-bottom: 1rem;">
                                <i class="bi bi-people me-1"></i><?= $kelas['jumlah_siswa'] ?> siswa
                            </p>
                            <div class="d-flex gap-2 justify-content-center">
                                <button class="btn-action btn-edit" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editModal"
                                        data-item='<?= json_encode([
                                            "id_kelas" => $kelas["id_kelas"],
                                            "nama_kelas" => $kelas["nama_kelas"]
                                        ]) ?>'
                                        title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <a href="<?= BASE_URL ?>/controllers/KelasController.php?action=delete&id=<?= $kelas['id_kelas'] ?>" 
                                   class="btn-action btn-delete"
                                   data-confirm="Hapus kelas <?= htmlspecialchars($kelas['nama_kelas']) ?>? Semua siswa di kelas ini juga akan terhapus."
                                   title="Hapus">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Tambah Kelas -->
<div class="modal fade" id="addKelasModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content modal-content-dark">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Tambah Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/controllers/KelasController.php?action=create" data-validate>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label-dark">Nama Kelas <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="nama_kelas" class="form-control form-control-dark" 
                               placeholder="Contoh: X-A, XI-IPA, XII-IPS" required>
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

<!-- Modal Edit Kelas -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content modal-content-dark">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/controllers/KelasController.php?action=update" data-validate>
                <input type="hidden" name="id_kelas">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label-dark">Nama Kelas <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="nama_kelas" class="form-control form-control-dark" required>
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
