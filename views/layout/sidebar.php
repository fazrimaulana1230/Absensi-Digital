<?php
/**
 * Sidebar Navigation
 * Menampilkan menu berbeda berdasarkan role
 */
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$role = $_SESSION['role'] ?? 'guest';
?>

<!-- Sidebar -->
<nav id="sidebar" class="sidebar">
    <!-- Brand -->
    <div class="sidebar-brand">
        <div class="brand-icon">📋</div>
        <div>
            <h5>Absensi Digital</h5>
            <small>SMA Nusantara</small>
        </div>
    </div>

    <!-- Navigation -->
    <div class="sidebar-nav">
        <?php if ($role === 'admin'): ?>
            <!-- Menu Admin -->
            <div class="nav-label">Menu Utama</div>
            <div class="nav-item">
                <a href="<?= BASE_URL ?>/controllers/AdminController.php" 
                   class="nav-link <?= $currentPage === 'AdminController' ? 'active' : '' ?>">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <div class="nav-label">Kelola Data</div>
            <div class="nav-item">
                <a href="<?= BASE_URL ?>/controllers/SiswaController.php" 
                   class="nav-link <?= $currentPage === 'SiswaController' ? 'active' : '' ?>">
                    <i class="bi bi-people-fill"></i>
                    <span>Data Siswa</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="<?= BASE_URL ?>/controllers/GuruController.php" 
                   class="nav-link <?= $currentPage === 'GuruController' ? 'active' : '' ?>">
                    <i class="bi bi-person-badge-fill"></i>
                    <span>Data Guru</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="<?= BASE_URL ?>/controllers/KelasController.php" 
                   class="nav-link <?= $currentPage === 'KelasController' ? 'active' : '' ?>">
                    <i class="bi bi-building"></i>
                    <span>Data Kelas</span>
                </a>
            </div>

            <div class="nav-label">Laporan</div>
            <div class="nav-item">
                <a href="<?= BASE_URL ?>/controllers/LaporanController.php" 
                   class="nav-link <?= $currentPage === 'LaporanController' ? 'active' : '' ?>">
                    <i class="bi bi-file-earmark-bar-graph-fill"></i>
                    <span>Laporan Absensi</span>
                </a>
            </div>

        <?php elseif ($role === 'guru'): ?>
            <!-- Menu Guru -->
            <div class="nav-label">Menu Utama</div>
            <div class="nav-item">
                <a href="<?= BASE_URL ?>/controllers/GuruDashboardController.php" 
                   class="nav-link <?= $currentPage === 'GuruDashboardController' ? 'active' : '' ?>">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <div class="nav-label">Absensi</div>
            <div class="nav-item">
                <a href="<?= BASE_URL ?>/controllers/AbsensiController.php" 
                   class="nav-link <?= $currentPage === 'AbsensiController' && (!isset($_GET['action']) || $_GET['action'] !== 'riwayat') ? 'active' : '' ?>">
                    <i class="bi bi-clipboard-check-fill"></i>
                    <span>Input Absensi</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="<?= BASE_URL ?>/controllers/AbsensiController.php?action=riwayat" 
                   class="nav-link <?= $currentPage === 'AbsensiController' && isset($_GET['action']) && $_GET['action'] === 'riwayat' ? 'active' : '' ?>">
                    <i class="bi bi-clock-history"></i>
                    <span>Riwayat Absensi</span>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- User Info -->
    <div class="sidebar-user">
        <div class="user-avatar">
            <?= strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)) ?>
        </div>
        <div class="user-info">
            <div class="name"><?= htmlspecialchars($_SESSION['nama_guru'] ?? $_SESSION['username'] ?? 'User') ?></div>
            <div class="role"><?= ucfirst($_SESSION['role'] ?? 'Guest') ?></div>
        </div>
        <a href="<?= BASE_URL ?>/controllers/AuthController.php?action=logout" 
           class="btn-action ms-auto" title="Logout"
           data-confirm="Apakah Anda yakin ingin logout?">
            <i class="bi bi-box-arrow-right"></i>
        </a>
    </div>
</nav>

<!-- Main Content Wrapper -->
<div class="main-content">
    <!-- Topbar -->
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button id="sidebarToggle" class="btn-action d-lg-none">
                <i class="bi bi-list"></i>
            </button>
            <span class="page-title"><?= $pageTitle ?? 'Dashboard' ?></span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <!-- Theme Toggle -->
            <div class="theme-switch-wrapper me-2">
                <label class="theme-switch" for="themeToggle">
                    <input type="checkbox" id="themeToggle" class="theme-switch-input" />
                    <div class="slider round">
                        <i class="bi bi-moon-stars-fill icon-dark"></i>
                        <i class="bi bi-sun-fill icon-light"></i>
                    </div>
                </label>
            </div>
            
            <div class="topbar-date d-none d-md-flex">
                <i class="bi bi-calendar3"></i>
                <?= strftime('%A, %d %B %Y') ?: date('l, d F Y') ?>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <div class="content-area pb-0 mb-0">
        <?= getFlash() ?>
    </div>
