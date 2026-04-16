<?php
/**
 * Model Absensi
 * Mengelola data tabel absensi
 */

class Absensi {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Simpan atau update absensi 
     * Menggunakan INSERT ... ON DUPLICATE KEY UPDATE 
     * agar tidak duplikat per siswa per hari
     * 
     * @param int $id_siswa
     * @param int $id_guru
     * @param string $tanggal Format Y-m-d
     * @param string $status Hadir/Izin/Sakit/Alpha
     * @return bool
     */
    public function saveAbsensi($id_siswa, $id_guru, $tanggal, $status) {
        $stmt = $this->conn->prepare(
            "INSERT INTO absensi (id_siswa, id_guru, tanggal, status) 
             VALUES (?, ?, ?, ?) 
             ON DUPLICATE KEY UPDATE status = ?, id_guru = ?"
        );
        $stmt->bind_param("iisssi", $id_siswa, $id_guru, $tanggal, $status, $status, $id_guru);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Ambil absensi berdasarkan kelas dan tanggal
     * @param int $id_kelas
     * @param string $tanggal
     * @return array
     */
    public function getByKelasAndTanggal($id_kelas, $tanggal) {
        $stmt = $this->conn->prepare(
            "SELECT s.id_siswa, s.nama, s.nis, a.status, a.id_absen
             FROM siswa s 
             LEFT JOIN absensi a ON s.id_siswa = a.id_siswa AND a.tanggal = ?
             WHERE s.id_kelas = ?
             ORDER BY s.nama ASC"
        );
        $stmt->bind_param("si", $tanggal, $id_kelas);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $data;
    }

    /**
     * Ambil riwayat absensi guru
     * @param int $id_guru
     * @param string|null $tanggal_dari
     * @param string|null $tanggal_sampai
     * @param int|null $id_kelas
     * @return array
     */
    public function getRiwayatGuru($id_guru, $tanggal_dari = null, $tanggal_sampai = null, $id_kelas = null) {
        $sql = "SELECT a.*, s.nama, s.nis, k.nama_kelas 
                FROM absensi a 
                JOIN siswa s ON a.id_siswa = s.id_siswa 
                JOIN kelas k ON s.id_kelas = k.id_kelas 
                WHERE a.id_guru = ?";
        $params = [$id_guru];
        $types = "i";

        if ($tanggal_dari) {
            $sql .= " AND a.tanggal >= ?";
            $params[] = $tanggal_dari;
            $types .= "s";
        }
        if ($tanggal_sampai) {
            $sql .= " AND a.tanggal <= ?";
            $params[] = $tanggal_sampai;
            $types .= "s";
        }
        if ($id_kelas) {
            $sql .= " AND s.id_kelas = ?";
            $params[] = $id_kelas;
            $types .= "i";
        }

        $sql .= " ORDER BY a.tanggal DESC, k.nama_kelas, s.nama";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $data;
    }

    /**
     * Ambil laporan absensi (untuk admin)
     * Dengan filter tanggal, kelas, siswa
     */
    public function getLaporan($tanggal_dari = null, $tanggal_sampai = null, $id_kelas = null, $id_siswa = null) {
        $sql = "SELECT a.*, s.nama as nama_siswa, s.nis, k.nama_kelas, g.nama as nama_guru
                FROM absensi a 
                JOIN siswa s ON a.id_siswa = s.id_siswa 
                JOIN kelas k ON s.id_kelas = k.id_kelas 
                JOIN guru g ON a.id_guru = g.id_guru
                WHERE 1=1";
        $params = [];
        $types = "";

        if ($tanggal_dari) {
            $sql .= " AND a.tanggal >= ?";
            $params[] = $tanggal_dari;
            $types .= "s";
        }
        if ($tanggal_sampai) {
            $sql .= " AND a.tanggal <= ?";
            $params[] = $tanggal_sampai;
            $types .= "s";
        }
        if ($id_kelas) {
            $sql .= " AND s.id_kelas = ?";
            $params[] = $id_kelas;
            $types .= "i";
        }
        if ($id_siswa) {
            $sql .= " AND a.id_siswa = ?";
            $params[] = $id_siswa;
            $types .= "i";
        }

        $sql .= " ORDER BY a.tanggal DESC, k.nama_kelas, s.nama";

        if (!empty($params)) {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $this->conn->query($sql);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Hitung statistik absensi
     * @return array Statistik per status
     */
    public function getStatistik($tanggal = null) {
        $sql = "SELECT status, COUNT(*) as total FROM absensi";
        if ($tanggal) {
            $sql .= " WHERE tanggal = ?";
        }
        $sql .= " GROUP BY status";

        if ($tanggal) {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $tanggal);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $this->conn->query($sql);
        }

        $stats = ['Hadir' => 0, 'Izin' => 0, 'Sakit' => 0, 'Alpha' => 0];
        while ($row = $result->fetch_assoc()) {
            $stats[$row['status']] = $row['total'];
        }
        return $stats;
    }

    /**
     * Hitung total record absensi
     * @return int
     */
    public function count() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM absensi");
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    /**
     * Hitung absensi hari ini
     * @return int  
     */
    public function countToday() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM absensi WHERE tanggal = CURDATE()");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['total'];
    }
}
