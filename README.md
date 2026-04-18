# 📋 Sistem Informasi Absensi Digital SMA

Aplikasi web untuk mencatat kehadiran siswa secara digital di Sekolah Menengah Atas (SMA). Digunakan oleh **Admin** untuk mengelola data dan melihat laporan, serta oleh **Guru** untuk mencatat absensi siswa.

---

## 📜 Changelog

### v3.0.0 - Analytics & Export Update
- **Export Excel Native**: Fitur unduh laporan absensi, riwayat kehadiran, data guru, & siswa (`.xls`) tanpa library (*Zero Dependency*).
- **Cetak PDF/Print Responsive**: Styling khusus media-print yang responsif (menyembunyikan tabel aksi, form input) untuk laporan tercetak yang rapi.
- **Dashboard Chart.js**: Visualisasi rekap absensi interaktif menggunakan grafik *Doughnut* di panel *Dashboard* Admin maupun Guru.

### v2.0.0 - UI/UX Refactor
- **Dark / Light Mode Toggle**: Switch tema dinamis dengan auto-save persisten menggunakan `localStorage`.
- **Premium Interface**: Redesign antarmuka dengan elemen glassmorphism, efek soft shadow, & layout pill modern.

### v1.0.0 - Initial Release
- **Sistem Autentikasi Dasar**: Login Admin & Guru terpisah.
- **Manajemen CRUD**: Kelola Data Siswa, Guru, dan Kelas.
- **Input Absensi**: Form pencatatan kehadiran oleh Guru.
- **Laporan Sederhana**: Tabel riwayat.

---

## 🚀 Gambaran Fitur Aplikasi

### 👨‍💼 Panel Admin
| Fitur | Deskripsi |
|-------|-----------|
| **Dashboard** | Statistik dan grafik Chart.js interaktif total siswa, guru, kelas, dan absensi hari ini |
| **CRUD Siswa** | Tambah, edit, hapus data siswa (nama, NIS, kelas) |
| **CRUD Guru** | Tambah guru + buat akun login otomatis, edit, hapus, assign kelas |
| **CRUD Kelas** | Tambah, edit, hapus data kelas |
| **Laporan Absensi** | Filter laporan absensi komprehensif lengkap fitur ekspor Excel dan Print/PDF |

### 👨‍🏫 Panel Guru
| Fitur | Deskripsi |
|-------|-----------|
| **Dashboard** | Ringkasan kelas yang diajar & statistik hari ini |
| **Input Absensi** | Pilih kelas & tanggal, set status per siswa (Hadir / Izin / Sakit / Alpha) |
| **Quick Select** | Tombol cepat untuk set semua siswa sekaligus |
| **Riwayat Absensi** | Lihat riwayat dengan filter tanggal dan kelas |

---

## 🛠️ Teknologi

| Layer | Teknologi |
|-------|-----------|
| **Frontend** | HTML5, CSS3, JavaScript |
| **CSS Framework** | [Bootstrap 5.3](https://getbootstrap.com/) |
| **Icons** | [Bootstrap Icons](https://icons.getbootstrap.com/) |
| **Font** | [Inter](https://fonts.google.com/specimen/Inter) (Google Fonts) |
| **Backend** | PHP Native (struktur MVC sederhana) |
| **Database** | MySQL / MariaDB |
| **Server** | Apache (XAMPP) |

---

## 📁 Struktur Folder

```
absensi2/
│
├── config/
│   └── database.php              # Koneksi database & fungsi helper
│
├── controllers/
│   ├── AuthController.php        # Login & logout
│   ├── AdminController.php       # Dashboard admin
│   ├── SiswaController.php       # CRUD siswa
│   ├── GuruController.php        # CRUD guru (admin)
│   ├── KelasController.php       # CRUD kelas
│   ├── AbsensiController.php     # Input absensi (guru)
│   ├── LaporanController.php     # Laporan absensi (admin)
│   └── GuruDashboardController.php # Dashboard guru
│
├── models/
│   ├── User.php                  # Model users
│   ├── Siswa.php                 # Model siswa
│   ├── Guru.php                  # Model guru
│   ├── Kelas.php                 # Model kelas
│   └── Absensi.php               # Model absensi
│
├── views/
│   ├── layout/
│   │   ├── header.php            # Head HTML, CSS imports & Theme Init
│   │   ├── sidebar.php           # Sidebar & Topbar (Theme Toggle Button)
│   │   └── footer.php            # Script JS imports
│   ├── auth/
│   │   └── login.php             # Halaman login
│   ├── admin/
│   │   ├── dashboard.php         # Dashboard admin
│   │   ├── siswa.php             # Kelola data siswa
│   │   ├── guru.php              # Kelola data guru
│   │   ├── kelas.php             # Kelola data kelas
│   │   └── laporan.php           # Laporan absensi
│   └── guru/
│       ├── dashboard.php         # Dashboard guru
│       ├── absensi.php           # Input absensi
│       └── riwayat.php           # Riwayat absensi
│
├── assets/
│   ├── css/
│   │   └── style.css             # Custom CSS (Dark & Light Theme)
│   └── js/
│       └── app.js                # Custom JavaScript (Theme Toggle Logic)
│
├── database/
│   └── absensi_digital.sql       # Script SQL lengkap + data sample
│
├── index.php                     # Entry point
├── .htaccess                     # Security rules
└── README.md                     # Dokumentasi (file ini)
```

---

## 🗄️ Struktur Database

### ERD (Entity Relationship)

```
┌──────────┐     ┌──────────────┐     ┌──────────┐
│  users   │     │  guru_kelas  │     │  kelas   │
├──────────┤     ├──────────────┤     ├──────────┤
│ id (PK)  │     │ id (PK)      │     │ id_kelas │
│ username │     │ id_guru (FK) │────▶│ nama_kelas│
│ password │     │ id_kelas(FK) │     └────┬─────┘
│ role     │     └──────────────┘          │
│ id_guru  │──┐                            │
└──────────┘  │  ┌──────────┐     ┌───────┴──────┐
              └─▶│  guru    │     │    siswa      │
                 ├──────────┤     ├──────────────┤
                 │ id_guru  │     │ id_siswa (PK)│
                 │ nama     │     │ nama         │
                 │ nip      │     │ nis (UNIQUE) │
                 └────┬─────┘     │ id_kelas(FK) │
                      │           └──────┬───────┘
                      │                  │
                      │  ┌───────────────┘
                      │  │
                 ┌────┴──┴───────┐
                 │   absensi     │
                 ├───────────────┤
                 │ id_absen (PK) │
                 │ id_siswa (FK) │
                 │ id_guru  (FK) │
                 │ tanggal       │
                 │ status        │
                 └───────────────┘
```

### Tabel

| Tabel | Deskripsi |
|-------|-----------|
| `users` | Akun login (username, password, role, relasi ke guru) |
| `guru` | Data guru (nama, NIP) |
| `kelas` | Data kelas (nama kelas) |
| `guru_kelas` | Relasi many-to-many guru ↔ kelas |
| `siswa` | Data siswa (nama, NIS, kelas) |
| `absensi` | Data absensi (siswa, guru, tanggal, status) |

---

## ⚡ Instalasi

### Prasyarat

- [XAMPP](https://www.apachefriends.org/) (PHP 7.4+ & MySQL/MariaDB)
- Web browser modern (Chrome, Firefox, Edge)

### Langkah Instalasi

1. **Clone / Download** project ke folder htdocs XAMPP:

   ```bash
   cd C:\xampp\htdocs
   git clone <repository-url> absensi2
   ```

   Atau download dan extract ke `C:\xampp\htdocs\absensi2\`

2. **Jalankan XAMPP** — Start **Apache** dan **MySQL**

3. **Import database** — Pilih salah satu cara:

   **Cara 1: Via phpMyAdmin**
   - Buka http://localhost/phpmyadmin
   - Klik tab **Import**
   - Pilih file `database/absensi_digital.sql`
   - Klik **Go**

   **Cara 2: Via command line**
   ```bash
   cd C:\xampp\htdocs\absensi2
   C:\xampp\mysql\bin\mysql.exe -u root < database/absensi_digital.sql
   ```

4. **Konfigurasi database** (jika diperlukan):

   Edit file `config/database.php` sesuai pengaturan MySQL Anda:

   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');           // Sesuaikan jika ada password
   define('DB_NAME', 'absensi_digital');
   ```

5. **Akses aplikasi**:

   Buka browser → http://localhost/absensi2/

---

## 🔑 Akun Demo

| Role | Username | Password |
|------|----------|----------|
| Admin | `admin` | `admin123` |
| Guru (Budi Santoso) | `budi` | `guru123` |
| Guru (Siti Rahayu) | `siti` | `guru123` |
| Guru (Ahmad Hidayat) | `ahmad` | `guru123` |

---

## 📸 Screenshot

### Halaman Login
Tampilan login modern dengan dark theme dan glassmorphism effect.

### Dashboard Admin
Menampilkan statistik total siswa, guru, kelas, dan data absensi hari ini beserta progress bar kehadiran.

### Dashboard Guru
Menampilkan kelas yang diajar, jumlah siswa, statistik kehadiran hari ini, dan akses cepat ke input absensi.

### Input Absensi
Form input absensi dengan pilihan kelas & tanggal, radio button status per siswa, dan tombol quick-select untuk set semua siswa sekaligus.

### Laporan Absensi
Tabel laporan dengan filter tanggal, kelas, dan siswa. Dilengkapi ringkasan statistik dan fitur cetak/print.

---

## 🔒 Keamanan

| Fitur | Implementasi |
|-------|-------------|
| **Password Hashing** | `password_hash()` dengan algoritma bcrypt |
| **SQL Injection Prevention** | Prepared statements (mysqli) di semua query |
| **XSS Prevention** | `htmlspecialchars()` untuk sanitasi output |
| **Access Control** | Role-based (admin/guru) dengan pengecekan session |
| **Directory Protection** | `.htaccess` untuk deny akses langsung ke `/config` dan `/models` |

---

## 📐 Arsitektur

Aplikasi menggunakan pola **MVC sederhana**:

```
┌─────────────┐     ┌──────────────┐     ┌─────────────┐
│   Browser   │────▶│  Controller  │────▶│    Model     │
│  (request)  │     │  (logic)     │     │  (database)  │
└─────────────┘     └──────┬───────┘     └─────────────┘
                           │
                    ┌──────▼───────┐
                    │    View      │
                    │  (tampilan)  │
                    └──────────────┘
```

- **Model** — Berinteraksi dengan database (query, CRUD)
- **View** — Menampilkan data ke user (HTML + Bootstrap)
- **Controller** — Mengatur logika bisnis, validasi, routing

---

## 📝 Status Kehadiran

| Status | Keterangan | Warna Badge |
|--------|------------|-------------|
| ✅ Hadir | Siswa hadir di kelas | Hijau |
| 📝 Izin | Siswa izin tidak masuk | Biru |
| 🤒 Sakit | Siswa sakit | Kuning |
| ❌ Alpha | Siswa tidak hadir tanpa keterangan | Merah |

---

## 🤝 Kontribusi

1. Fork repository ini
2. Buat branch baru (`git checkout -b fitur-baru`)
3. Commit perubahan (`git commit -m 'Tambah fitur baru'`)
4. Push ke branch (`git push origin fitur-baru`)
5. Buat Pull Request

---

## 📄 Lisensi

Project ini dibuat untuk keperluan edukasi dan dapat digunakan secara bebas.

---

## 💡 Catatan Pengembangan

- Kode ditulis dengan komentar bahasa Indonesia agar mudah dipahami pemula
- Struktur folder mengikuti pola MVC sederhana
- Semua query menggunakan prepared statements untuk keamanan
- UI menggunakan dark theme premium dengan animasi halus
- Responsive design — mendukung tampilan desktop dan mobile

---

<p align="center">
  Dibuat dengan ❤️ menggunakan PHP, MySQL & Bootstrap 5
</p>
