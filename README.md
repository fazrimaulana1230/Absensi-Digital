# 📋 Sistem Informasi Absensi Digital SMA (V2)

Aplikasi web untuk mencatat kehadiran siswa secara digital di Sekolah Menengah Atas (SMA). Digunakan oleh **Admin** untuk mengelola data dan melihat laporan, serta oleh **Guru** untuk mencatat absensi siswa. Dalam versi V2 ini, aplikasi mendapatkan pembaharuan UI dengan fitur premium Dark Mode dan Light Mode.

---

## 🚀 Fitur Utama

### 🔐 Autentikasi
- Login dengan username & password
- Password di-hash dengan **bcrypt**
- Session-based authentication
- Redirect otomatis berdasarkan role (Admin / Guru)

### 🎨 UI/UX Enhancement (V2 Baru!)
| Fitur | Deskripsi |
|-------|-----------|
| **Theme Switcher** | Fitur modern switch Dark ↔ Light Mode |
| **Auto-save Mode** | Tema otomatis tersimpan secara persisten menggunakan localStorage |
| **Premium Interface** | Warna tema premium dengan layout pill modern, dan efek soft drop-shadow |

### 👨‍💼 Panel Admin
| Fitur | Deskripsi |
|-------|-----------|
| **Dashboard** | Statistik total siswa, guru, kelas, dan absensi hari ini |
| **CRUD Siswa** | Tambah, edit, hapus data siswa (nama, NIS, kelas) |
| **CRUD Guru** | Tambah guru + buat akun login otomatis, edit, hapus, assign kelas |
| **CRUD Kelas** | Tambah, edit, hapus data kelas |
| **Laporan Absensi** | Filter berdasarkan tanggal, kelas, siswa + cetak / print |

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
│   │   ├── header.php            # Head HTML, CSS imports
│   │   ├── sidebar.php           # Navigasi sidebar (role-based)
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
│   │   └── style.css             # Custom CSS (dark theme)
│   └── js/
│       └── app.js                # Custom JavaScript
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
