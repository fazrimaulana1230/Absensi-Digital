<?php
/**
 * Dashboard Admin
 * Menampilkan statistik dan ringkasan data
 */
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="content-area">
    <!-- Welcome -->
    <div class="mb-4 animate-in">
        <h4 style="font-weight: 700; margin-bottom: 0.25rem;">
            Selamat Datang, <?= htmlspecialchars($_SESSION['username']) ?>! 👋
        </h4>
        <p style="color: var(--text-secondary); font-size: 0.9rem;">
            Berikut ringkasan data absensi hari ini.
        </p>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card card-primary animate-in">
                <div class="stat-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-value"><?= $totalSiswa ?></div>
                <div class="stat-label">Total Siswa</div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card card-success animate-in">
                <div class="stat-icon">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
                <div class="stat-value"><?= $totalGuru ?></div>
                <div class="stat-label">Total Guru</div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card card-warning animate-in">
                <div class="stat-icon">
                    <i class="bi bi-building"></i>
                </div>
                <div class="stat-value"><?= $totalKelas ?></div>
                <div class="stat-label">Total Kelas</div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card card-info animate-in">
                <div class="stat-icon">
                    <i class="bi bi-clipboard-check-fill"></i>
                </div>
                <div class="stat-value"><?= $totalAbsensi ?></div>
                <div class="stat-label">Absensi Hari Ini</div>
            </div>
        </div>
    </div>

    <!-- Absensi Hari Ini Stats -->
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card-dark animate-in">
                <div class="card-header">
                    <h6><i class="bi bi-bar-chart-fill me-2"></i>Statistik Kehadiran Hari Ini</h6>
                    <span style="font-size: 0.8rem; color: var(--text-muted);"><?= date('d/m/Y') ?></span>
                </div>
                <div class="card-body">
                    <?php 
                    $totalHariIni = array_sum($statsHariIni);
                    if ($totalHariIni > 0):
                    ?>
                    <div class="row g-3">
                        <div class="col-6 col-md-3 text-center">
                            <div style="font-size: 2rem; font-weight: 800; color: var(--success);">
                                <?= $statsHariIni['Hadir'] ?>
                            </div>
                            <div style="font-size: 0.8rem; color: var(--text-secondary);">Hadir</div>
                            <div class="progress mt-2" style="height: 4px; background: var(--bg-input);">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: <?= round($statsHariIni['Hadir']/$totalHariIni*100) ?>%; background: var(--success);"></div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 text-center">
                            <div style="font-size: 2rem; font-weight: 800; color: var(--info);">
                                <?= $statsHariIni['Izin'] ?>
                            </div>
                            <div style="font-size: 0.8rem; color: var(--text-secondary);">Izin</div>
                            <div class="progress mt-2" style="height: 4px; background: var(--bg-input);">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: <?= round($statsHariIni['Izin']/$totalHariIni*100) ?>%; background: var(--info);"></div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 text-center">
                            <div style="font-size: 2rem; font-weight: 800; color: var(--warning);">
                                <?= $statsHariIni['Sakit'] ?>
                            </div>
                            <div style="font-size: 0.8rem; color: var(--text-secondary);">Sakit</div>
                            <div class="progress mt-2" style="height: 4px; background: var(--bg-input);">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: <?= round($statsHariIni['Sakit']/$totalHariIni*100) ?>%; background: var(--warning);"></div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 text-center">
                            <div style="font-size: 2rem; font-weight: 800; color: var(--danger);">
                                <?= $statsHariIni['Alpha'] ?>
                            </div>
                            <div style="font-size: 0.8rem; color: var(--text-secondary);">Alpha</div>
                            <div class="progress mt-2" style="height: 4px; background: var(--bg-input);">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: <?= round($statsHariIni['Alpha']/$totalHariIni*100) ?>%; background: var(--danger);"></div>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-4" style="color: var(--text-muted);">
                        <i class="bi bi-inbox" style="font-size: 2.5rem;"></i>
                        <p class="mt-2 mb-0">Belum ada data absensi hari ini</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-dark animate-in">
                <div class="card-header">
                    <h6><i class="bi bi-lightning-fill me-2"></i>Akses Cepat</h6>
                </div>
                <div class="card-body">
                    <a href="<?= BASE_URL ?>/controllers/SiswaController.php" 
                       class="d-flex align-items-center gap-3 p-3 mb-2" 
                       style="background: var(--bg-input); border-radius: var(--border-radius-sm); transition: var(--transition);"
                       onmouseover="this.style.background='rgba(99,102,241,0.1)'"
                       onmouseout="this.style.background='var(--bg-input)'">
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(99,102,241,0.15); display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-people-fill" style="color: var(--primary-light);"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.85rem; font-weight: 600;">Data Siswa</div>
                            <div style="font-size: 0.7rem; color: var(--text-muted);">Kelola data siswa</div>
                        </div>
                        <i class="bi bi-chevron-right ms-auto" style="color: var(--text-muted);"></i>
                    </a>
                    <a href="<?= BASE_URL ?>/controllers/LaporanController.php" 
                       class="d-flex align-items-center gap-3 p-3 mb-2" 
                       style="background: var(--bg-input); border-radius: var(--border-radius-sm); transition: var(--transition);"
                       onmouseover="this.style.background='rgba(99,102,241,0.1)'"
                       onmouseout="this.style.background='var(--bg-input)'">
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: var(--success-light); display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-file-earmark-bar-graph-fill" style="color: var(--success);"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.85rem; font-weight: 600;">Laporan</div>
                            <div style="font-size: 0.7rem; color: var(--text-muted);">Lihat laporan absensi</div>
                        </div>
                        <i class="bi bi-chevron-right ms-auto" style="color: var(--text-muted);"></i>
                    </a>
                    <a href="<?= BASE_URL ?>/controllers/GuruController.php" 
                       class="d-flex align-items-center gap-3 p-3" 
                       style="background: var(--bg-input); border-radius: var(--border-radius-sm); transition: var(--transition);"
                       onmouseover="this.style.background='rgba(99,102,241,0.1)'"
                       onmouseout="this.style.background='var(--bg-input)'">
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: var(--warning-light); display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-person-badge-fill" style="color: var(--warning);"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.85rem; font-weight: 600;">Data Guru</div>
                            <div style="font-size: 0.7rem; color: var(--text-muted);">Kelola data guru</div>
                        </div>
                        <i class="bi bi-chevron-right ms-auto" style="color: var(--text-muted);"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

</div><!-- /.main-content -->

<?php include __DIR__ . '/../layout/footer.php'; ?>
