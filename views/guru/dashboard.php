<?php
/**
 * Dashboard Guru
 * Menampilkan ringkasan dan akses cepat
 */
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="content-area">
    <!-- Welcome -->
    <div class="mb-4 animate-in">
        <h4 style="font-weight: 700; margin-bottom: 0.25rem;">
            Halo, <?= htmlspecialchars($_SESSION['nama_guru'] ?? $_SESSION['username']) ?>! 👋
        </h4>
        <p style="color: var(--text-secondary); font-size: 0.9rem;">
            Selamat datang di panel guru. Berikut ringkasan Anda hari ini.
        </p>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card card-primary animate-in">
                <div class="stat-icon">
                    <i class="bi bi-building"></i>
                </div>
                <div class="stat-value"><?= count($kelasList) ?></div>
                <div class="stat-label">Kelas Diajar</div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card card-success animate-in">
                <div class="stat-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-value"><?= $totalSiswaGuru ?></div>
                <div class="stat-label">Total Siswa</div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card card-warning animate-in">
                <div class="stat-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="stat-value"><?= $statsHariIni['Hadir'] ?></div>
                <div class="stat-label">Hadir Hari Ini</div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card card-danger animate-in">
                <div class="stat-icon">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
                <div class="stat-value"><?= $statsHariIni['Alpha'] ?></div>
                <div class="stat-label">Alpha Hari Ini</div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Kelas yang Diajar -->
        <div class="col-lg-7">
            <div class="card-dark animate-in">
                <div class="card-header">
                    <h6><i class="bi bi-building me-2"></i>Kelas yang Diajar</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($kelasList)): ?>
                        <div class="text-center py-4" style="color: var(--text-muted);">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mt-2 mb-0">Belum ada kelas yang ditugaskan</p>
                        </div>
                    <?php else: ?>
                        <div class="row g-2">
                            <?php foreach ($kelasList as $kelas): ?>
                                <div class="col-sm-6">
                                    <a href="<?= BASE_URL ?>/controllers/AbsensiController.php?kelas=<?= $kelas['id_kelas'] ?>&tanggal=<?= date('Y-m-d') ?>" 
                                       class="d-flex align-items-center gap-3 p-3"
                                       style="background: var(--bg-input); border-radius: var(--border-radius-sm); transition: var(--transition);"
                                       onmouseover="this.style.background='rgba(99,102,241,0.1)'; this.style.transform='translateX(5px)'"
                                       onmouseout="this.style.background='var(--bg-input)'; this.style.transform='translateX(0)'">
                                        <div style="width: 42px; height: 42px; border-radius: 12px; background: rgba(99,102,241,0.15); display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-building" style="color: var(--primary-light); font-size: 1.1rem;"></i>
                                        </div>
                                        <div>
                                            <div style="font-size: 0.9rem; font-weight: 600;"><?= htmlspecialchars($kelas['nama_kelas']) ?></div>
                                            <div style="font-size: 0.75rem; color: var(--text-muted);">Klik untuk absensi</div>
                                        </div>
                                        <i class="bi bi-chevron-right ms-auto" style="color: var(--text-muted);"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Akses Cepat -->
        <div class="col-lg-5">
            <div class="card-dark animate-in">
                <div class="card-header">
                    <h6><i class="bi bi-lightning-fill me-2"></i>Akses Cepat</h6>
                </div>
                <div class="card-body">
                    <a href="<?= BASE_URL ?>/controllers/AbsensiController.php" 
                       class="d-flex align-items-center gap-3 p-3 mb-2" 
                       style="background: var(--bg-input); border-radius: var(--border-radius-sm); transition: var(--transition);"
                       onmouseover="this.style.background='rgba(16,185,129,0.1)'"
                       onmouseout="this.style.background='var(--bg-input)'">
                        <div style="width: 40px; height: 40px; border-radius: 10px; background: var(--success-light); display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-clipboard-check-fill" style="color: var(--success);"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.85rem; font-weight: 600;">Input Absensi</div>
                            <div style="font-size: 0.7rem; color: var(--text-muted);">Catat kehadiran siswa</div>
                        </div>
                        <i class="bi bi-chevron-right ms-auto" style="color: var(--text-muted);"></i>
                    </a>
                    <a href="<?= BASE_URL ?>/controllers/AbsensiController.php?action=riwayat" 
                       class="d-flex align-items-center gap-3 p-3" 
                       style="background: var(--bg-input); border-radius: var(--border-radius-sm); transition: var(--transition);"
                       onmouseover="this.style.background='rgba(99,102,241,0.1)'"
                       onmouseout="this.style.background='var(--bg-input)'">
                        <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(99,102,241,0.15); display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-clock-history" style="color: var(--primary-light);"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.85rem; font-weight: 600;">Riwayat Absensi</div>
                            <div style="font-size: 0.7rem; color: var(--text-muted);">Lihat data sebelumnya</div>
                        </div>
                        <i class="bi bi-chevron-right ms-auto" style="color: var(--text-muted);"></i>
                    </a>
                </div>
            </div>

            <!-- Statistik Hari Ini -->
            <div class="card-dark mt-3 animate-in">
                <div class="card-header">
                    <h6><i class="bi bi-bar-chart-fill me-2"></i>Hari Ini</h6>
                    <span style="font-size: 0.75rem; color: var(--text-muted);"><?= date('d/m/Y') ?></span>
                </div>
                <div class="card-body">
                    <?php $totalHariIni = array_sum($statsHariIni); ?>
                    <?php if ($totalHariIni > 0): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span style="font-size: 0.8rem; color: var(--text-secondary);">Hadir</span>
                            <span style="font-size: 0.8rem; font-weight: 600; color: var(--success);"><?= $statsHariIni['Hadir'] ?></span>
                        </div>
                        <div class="progress mb-3" style="height: 5px; background: var(--bg-input);">
                            <div class="progress-bar" style="width: <?= $totalHariIni > 0 ? round($statsHariIni['Hadir']/$totalHariIni*100) : 0 ?>%; background: var(--success);"></div>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span style="font-size: 0.8rem; color: var(--text-secondary);">Izin</span>
                            <span style="font-size: 0.8rem; font-weight: 600; color: var(--info);"><?= $statsHariIni['Izin'] ?></span>
                        </div>
                        <div class="progress mb-3" style="height: 5px; background: var(--bg-input);">
                            <div class="progress-bar" style="width: <?= $totalHariIni > 0 ? round($statsHariIni['Izin']/$totalHariIni*100) : 0 ?>%; background: var(--info);"></div>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span style="font-size: 0.8rem; color: var(--text-secondary);">Sakit</span>
                            <span style="font-size: 0.8rem; font-weight: 600; color: var(--warning);"><?= $statsHariIni['Sakit'] ?></span>
                        </div>
                        <div class="progress mb-3" style="height: 5px; background: var(--bg-input);">
                            <div class="progress-bar" style="width: <?= $totalHariIni > 0 ? round($statsHariIni['Sakit']/$totalHariIni*100) : 0 ?>%; background: var(--warning);"></div>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span style="font-size: 0.8rem; color: var(--text-secondary);">Alpha</span>
                            <span style="font-size: 0.8rem; font-weight: 600; color: var(--danger);"><?= $statsHariIni['Alpha'] ?></span>
                        </div>
                        <div class="progress" style="height: 5px; background: var(--bg-input);">
                            <div class="progress-bar" style="width: <?= $totalHariIni > 0 ? round($statsHariIni['Alpha']/$totalHariIni*100) : 0 ?>%; background: var(--danger);"></div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-3" style="color: var(--text-muted); font-size: 0.85rem;">
                            Belum ada absensi hari ini
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

</div><!-- /.main-content -->

<?php include __DIR__ . '/../layout/footer.php'; ?>
