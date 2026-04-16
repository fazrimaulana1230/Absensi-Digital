<?php
/**
 * Model Siswa
 * Mengelola data tabel siswa
 */

class Siswa {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Ambil semua siswa beserta nama kelas
     * @return array
     */
    public function getAll() {
        $sql = "SELECT s.*, k.nama_kelas 
                FROM siswa s 
                JOIN kelas k ON s.id_kelas = k.id_kelas 
                ORDER BY k.nama_kelas, s.nama ASC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Ambil siswa berdasarkan ID
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT s.*, k.nama_kelas FROM siswa s JOIN kelas k ON s.id_kelas = k.id_kelas WHERE s.id_siswa = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $siswa = $result->fetch_assoc();
        $stmt->close();
        return $siswa;
    }

    /**
     * Ambil siswa berdasarkan kelas
     * @param int $id_kelas
     * @return array
     */
    public function getByKelas($id_kelas) {
        $stmt = $this->conn->prepare("SELECT * FROM siswa WHERE id_kelas = ? ORDER BY nama ASC");
        $stmt->bind_param("i", $id_kelas);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $data;
    }

    /**
     * Tambah siswa baru
     * @param string $nama
     * @param string $nis
     * @param int $id_kelas
     * @return bool
     */
    public function create($nama, $nis, $id_kelas) {
        $stmt = $this->conn->prepare("INSERT INTO siswa (nama, nis, id_kelas) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $nama, $nis, $id_kelas);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Update data siswa
     */
    public function update($id, $nama, $nis, $id_kelas) {
        $stmt = $this->conn->prepare("UPDATE siswa SET nama = ?, nis = ?, id_kelas = ? WHERE id_siswa = ?");
        $stmt->bind_param("ssii", $nama, $nis, $id_kelas, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Hapus siswa
     */
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM siswa WHERE id_siswa = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Hitung total siswa
     * @return int
     */
    public function count() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM siswa");
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    /**
     * Cek apakah NIS sudah ada
     * @param string $nis
     * @param int|null $excludeId ID yang dikecualikan (untuk update)
     * @return bool
     */
    public function nisExists($nis, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM siswa WHERE nis = ? AND id_siswa != ?");
            $stmt->bind_param("si", $nis, $excludeId);
        } else {
            $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM siswa WHERE nis = ?");
            $stmt->bind_param("s", $nis);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['total'] > 0;
    }
}
