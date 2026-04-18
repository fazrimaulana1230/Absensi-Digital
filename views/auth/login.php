<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login - Sistem Informasi Absensi Digital SMA">
    <title>Login | Absensi Digital SMA</title>
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="login-page">
        <div class="login-card">
            <!-- Logo -->
            <div class="login-logo">
                <div class="logo-icon" style="background: transparent; box-shadow: none; padding: 0;">
                    <img src="<?= BASE_URL ?>/assets/img/logo.png" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
                <h1>Absensi Digital</h1>
                <p>Sistem Informasi Absensi SMA Nusantara</p>
            </div>

            <!-- Error Message -->
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="
                    background: var(--danger-light);
                    border: 1px solid rgba(239, 68, 68, 0.3);
                    color: var(--danger);
                    border-radius: var(--border-radius-sm);
                    font-size: 0.85rem;
                ">
                    <i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" style="filter: invert(1);"></button>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST" action="<?= BASE_URL ?>/controllers/AuthController.php" data-validate>
                <div class="mb-3">
                    <label class="form-label-dark" for="username">
                        <i class="bi bi-person me-1"></i>Username
                    </label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           class="form-control form-control-dark" 
                           placeholder="Masukkan username" 
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                           required 
                           autofocus>
                </div>

                <div class="mb-4">
                    <label class="form-label-dark" for="password">
                        <i class="bi bi-lock me-1"></i>Password
                    </label>
                    <div class="position-relative">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="form-control form-control-dark" 
                               placeholder="Masukkan password" 
                               required>
                        <button type="button" 
                                id="togglePassword" 
                                class="btn position-absolute top-50 end-0 translate-middle-y" 
                                style="color: var(--text-muted); border: none; background: none;">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary-custom w-100 py-2">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                </button>
            </form>

            <!-- Demo Credentials -->
            <div class="mt-4 p-3" style="
                background: rgba(99, 102, 241, 0.08);
                border-radius: var(--border-radius-sm);
                border: 1px solid rgba(99, 102, 241, 0.15);
            ">
                <p style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.5rem;">
                    <i class="bi bi-info-circle me-1"></i>Demo Account:
                </p>
                <div style="font-size: 0.8rem; color: var(--text-secondary);">
                    <div class="mb-1"><strong>Admin:</strong> admin / admin123</div>
                    <div><strong>Guru:</strong> budi / guru123</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Toggle Password Visibility -->
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const pwd = document.getElementById('password');
            const icon = this.querySelector('i');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                pwd.type = 'password';
                icon.className = 'bi bi-eye';
            }
        });
    </script>
</body>
</html>
