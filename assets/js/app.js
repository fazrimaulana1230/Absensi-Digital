/**
 * Sistem Informasi Absensi Digital SMA
 * Custom JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {

    // ================================================================
    // Sidebar Toggle (Mobile)
    // ================================================================
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('show');
            sidebarOverlay.classList.toggle('show');
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function () {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        });
    }

    // ================================================================
    // Form Validation
    // ================================================================
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            const inputs = form.querySelectorAll('[required]');
            let isValid = true;

            inputs.forEach(function (input) {
                // Hapus pesan error sebelumnya
                input.classList.remove('is-invalid');

                if (input.value.trim() === '') {
                    isValid = false;
                    input.classList.add('is-invalid');

                    // Tambah efek shake
                    input.style.animation = 'shake 0.4s ease';
                    setTimeout(() => {
                        input.style.animation = '';
                    }, 400);
                }
            });

            if (!isValid) {
                e.preventDefault();
                showToast('Harap isi semua field yang wajib!', 'danger');
            }
        });
    });

    // Hapus class is-invalid saat user mulai mengetik
    document.querySelectorAll('.form-control-dark, .form-select-dark').forEach(function (input) {
        input.addEventListener('input', function () {
            this.classList.remove('is-invalid');
        });
    });

    // ================================================================
    // Konfirmasi Hapus
    // ================================================================
    document.querySelectorAll('[data-confirm]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            const message = this.getAttribute('data-confirm') || 'Apakah Anda yakin ingin menghapus data ini?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // ================================================================
    // Quick Select All Absensi
    // ================================================================
    document.querySelectorAll('.btn-quick').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const status = this.getAttribute('data-status');
            const radios = document.querySelectorAll('input[type="radio"][value="' + status + '"]');
            radios.forEach(function (radio) {
                radio.checked = true;
            });
            showToast('Semua siswa diset ke: ' + status, 'info');
        });
    });

    // ================================================================
    // Auto-submit filter form on change
    // ================================================================
    document.querySelectorAll('.auto-submit').forEach(function (element) {
        element.addEventListener('change', function () {
            this.closest('form').submit();
        });
    });

    // ================================================================
    // Toast Notification
    // ================================================================
    window.showToast = function (message, type) {
        const toast = document.createElement('div');
        toast.className = 'position-fixed bottom-0 end-0 p-3';
        toast.style.zIndex = '1090';
        toast.innerHTML = `
            <div class="toast show align-items-center text-white bg-${type} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    };

    // ================================================================
    // Auto-dismiss alerts
    // ================================================================
    document.querySelectorAll('.alert-dismissible').forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 500);
        }, 4000);
    });

    // ================================================================
    // Modal - populate edit forms
    // ================================================================
    document.querySelectorAll('[data-bs-target="#editModal"]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const data = JSON.parse(this.getAttribute('data-item'));
            const form = document.querySelector('#editModal form');

            if (form && data) {
                // Populate form fields berdasarkan data attribute
                Object.keys(data).forEach(function (key) {
                    const input = form.querySelector('[name="' + key + '"]');
                    if (input) {
                        if (input.type === 'select-multiple') {
                            // Handle multi-select
                            const values = data[key];
                            Array.from(input.options).forEach(function (option) {
                                option.selected = values.includes(parseInt(option.value));
                            });
                        } else {
                            input.value = data[key];
                        }
                    }
                });
            }
        });
    });

    // ================================================================
    // Animate elements on scroll
    // ================================================================
    const animateElements = document.querySelectorAll('.animate-in');
    animateElements.forEach(function (el, index) {
        el.style.opacity = '0';
        setTimeout(function () {
            el.style.opacity = '';
        }, 50);
    });

});

// Shake animation (inject via CSS)
const shakeStyle = document.createElement('style');
shakeStyle.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    .is-invalid {
        border-color: var(--danger) !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2) !important;
    }
`;
document.head.appendChild(shakeStyle);
