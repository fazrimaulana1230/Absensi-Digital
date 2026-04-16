-- ================================================================
-- Sistem Informasi Absensi Digital SMA
-- Database: absensi_digital
-- ================================================================

-- Buat database
CREATE DATABASE IF NOT EXISTS absensi_digital
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE absensi_digital;

-- ================================================================
-- Tabel: kelas
-- Menyimpan data kelas
-- ================================================================
CREATE TABLE kelas (
    id_kelas INT AUTO_INCREMENT PRIMARY KEY,
    nama_kelas VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ================================================================
-- Tabel: guru
-- Menyimpan data guru
-- ================================================================
CREATE TABLE guru (
    id_guru INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    nip VARCHAR(20) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ================================================================
-- Tabel: users
-- Menyimpan data login pengguna (admin & guru)
-- ================================================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'guru') NOT NULL DEFAULT 'guru',
    id_guru INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_guru) REFERENCES guru(id_guru) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ================================================================
-- Tabel: guru_kelas
-- Relasi many-to-many antara guru dan kelas
-- ================================================================
CREATE TABLE guru_kelas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_guru INT NOT NULL,
    id_kelas INT NOT NULL,
    FOREIGN KEY (id_guru) REFERENCES guru(id_guru) ON DELETE CASCADE,
    FOREIGN KEY (id_kelas) REFERENCES kelas(id_kelas) ON DELETE CASCADE,
    UNIQUE KEY unique_guru_kelas (id_guru, id_kelas)
) ENGINE=InnoDB;

-- ================================================================
-- Tabel: siswa
-- Menyimpan data siswa
-- ================================================================
CREATE TABLE siswa (
    id_siswa INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    nis VARCHAR(20) NOT NULL UNIQUE,
    id_kelas INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_kelas) REFERENCES kelas(id_kelas) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ================================================================
-- Tabel: absensi
-- Menyimpan data absensi harian
-- ================================================================
CREATE TABLE absensi (
    id_absen INT AUTO_INCREMENT PRIMARY KEY,
    id_siswa INT NOT NULL,
    id_guru INT NOT NULL,
    tanggal DATE NOT NULL,
    status ENUM('Hadir', 'Izin', 'Sakit', 'Alpha') NOT NULL DEFAULT 'Hadir',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_siswa) REFERENCES siswa(id_siswa) ON DELETE CASCADE,
    FOREIGN KEY (id_guru) REFERENCES guru(id_guru) ON DELETE CASCADE,
    UNIQUE KEY unique_absensi (id_siswa, tanggal)
) ENGINE=InnoDB;

-- ================================================================
-- Data Sample
-- ================================================================

-- Insert Kelas
INSERT INTO kelas (nama_kelas) VALUES
('X-A'), ('X-B'), ('XI-IPA'), ('XI-IPS'), ('XII-IPA'), ('XII-IPS');

-- Insert Guru
INSERT INTO guru (nama, nip) VALUES
('Budi Santoso', '198501012010011001'),
('Siti Rahayu', '198703152011012002'),
('Ahmad Hidayat', '199001202012011003');

-- Insert Users
-- Password: admin123 (bcrypt hash)
INSERT INTO users (username, password, role, id_guru) VALUES
('admin', '$2y$10$/ZpQFEYwLRPYjhdY67A8s.yPZmH4o2f8o/gjSEn.OzphrahbdPqwC', 'admin', NULL);

-- Password: guru123 (bcrypt hash)
INSERT INTO users (username, password, role, id_guru) VALUES
('budi', '$2y$10$EYzrgGIvivLObSbTJabeOOX1Did4awYm1VP6UVFXLII.zNpFyjJ7y', 'guru', 1),
('siti', '$2y$10$EYzrgGIvivLObSbTJabeOOX1Did4awYm1VP6UVFXLII.zNpFyjJ7y', 'guru', 2),
('ahmad', '$2y$10$EYzrgGIvivLObSbTJabeOOX1Did4awYm1VP6UVFXLII.zNpFyjJ7y', 'guru', 3);

-- Relasi Guru-Kelas
INSERT INTO guru_kelas (id_guru, id_kelas) VALUES
(1, 1), (1, 2),  -- Budi mengajar X-A, X-B
(2, 3), (2, 4),  -- Siti mengajar XI-IPA, XI-IPS
(3, 5), (3, 6);  -- Ahmad mengajar XII-IPA, XII-IPS

-- Insert Siswa
INSERT INTO siswa (nama, nis, id_kelas) VALUES
-- Kelas X-A
('Andi Pratama', '10001', 1),
('Bima Sakti', '10002', 1),
('Citra Dewi', '10003', 1),
('Dian Permata', '10004', 1),
-- Kelas X-B
('Eka Saputra', '10005', 2),
('Fina Melati', '10006', 2),
('Galih Ramadhan', '10007', 2),
-- Kelas XI-IPA
('Hana Safitri', '11001', 3),
('Irfan Hakim', '11002', 3),
('Jasmine Putri', '11003', 3),
-- Kelas XI-IPS
('Kevin Anggara', '11004', 4),
('Lestari Wulan', '11005', 4),
-- Kelas XII-IPA
('Muhamad Rizki', '12001', 5),
('Nadia Kusuma', '12002', 5),
('Oscar Pratama', '12003', 5),
-- Kelas XII-IPS
('Putri Amalia', '12004', 6),
('Qori Hidayat', '12005', 6);

-- Insert sample absensi (contoh data hari ini)
INSERT INTO absensi (id_siswa, id_guru, tanggal, status) VALUES
(1, 1, CURDATE(), 'Hadir'),
(2, 1, CURDATE(), 'Hadir'),
(3, 1, CURDATE(), 'Sakit'),
(4, 1, CURDATE(), 'Hadir'),
(5, 1, CURDATE(), 'Izin'),
(6, 1, CURDATE(), 'Hadir'),
(7, 1, CURDATE(), 'Alpha');
