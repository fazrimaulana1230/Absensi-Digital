<?php
/**
 * Model Kelas
 * Mengelola data tabel kelas
 */

class Kelas {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Ambil semua kelas
     * @return array
     */
    public function getAll() {
        $sql = "SELECT k.*, 
                (SELECT COUNT(*) FROM siswa s WHERE s.id_kelas = k.id_kelas) as jumlah_siswa
                FROM kelas k ORDER BY k.nama_kelas ASC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Ambil kelas berdasarkan ID
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM kelas WHERE id_kelas = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $kelas = $result->fetch_assoc();
        $stmt->close();
        return $kelas;
    }

    /**
     * Tambah kelas baru
     * @param string $nama_kelas
     * @return bool
     */
    public function create($nama_kelas) {
        $stmt = $this->conn->prepare("INSERT INTO kelas (nama_kelas) VALUES (?)");
        $stmt->bind_param("s", $nama_kelas);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Update nama kelas
     */
    public function update($id, $nama_kelas) {
        $stmt = $this->conn->prepare("UPDATE kelas SET nama_kelas = ? WHERE id_kelas = ?");
        $stmt->bind_param("si", $nama_kelas, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Hapus kelas
     */
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM kelas WHERE id_kelas = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Hitung total kelas
     * @return int
     */
    public function count() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM kelas");
        $row = $result->fetch_assoc();
        return $row['total'];
    }
}
