<?php
/**
 * Model Guru
 * Mengelola data tabel guru dan relasi guru_kelas
 */

class Guru {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Ambil semua guru
     * @return array
     */
    public function getAll() {
        $sql = "SELECT * FROM guru ORDER BY nama ASC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Ambil guru berdasarkan ID
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM guru WHERE id_guru = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $guru = $result->fetch_assoc();
        $stmt->close();
        return $guru;
    }

    /**
     * Tambah guru baru
     * @param string $nama
     * @param string $nip
     * @return int|false ID guru baru atau false jika gagal
     */
    public function create($nama, $nip) {
        $stmt = $this->conn->prepare("INSERT INTO guru (nama, nip) VALUES (?, ?)");
        $stmt->bind_param("ss", $nama, $nip);
        $result = $stmt->execute();
        $id = $this->conn->insert_id;
        $stmt->close();
        return $result ? $id : false;
    }

    /**
     * Update data guru
     */
    public function update($id, $nama, $nip) {
        $stmt = $this->conn->prepare("UPDATE guru SET nama = ?, nip = ? WHERE id_guru = ?");
        $stmt->bind_param("ssi", $nama, $nip, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Hapus guru
     */
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM guru WHERE id_guru = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Hitung total guru
     * @return int
     */
    public function count() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM guru");
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    /**
     * Ambil kelas yang diajar guru
     * @param int $id_guru
     * @return array
     */
    public function getKelasGuru($id_guru) {
        $stmt = $this->conn->prepare(
            "SELECT k.* FROM kelas k 
             JOIN guru_kelas gk ON k.id_kelas = gk.id_kelas 
             WHERE gk.id_guru = ? 
             ORDER BY k.nama_kelas ASC"
        );
        $stmt->bind_param("i", $id_guru);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $data;
    }

    /**
     * Update relasi guru-kelas
     * @param int $id_guru
     * @param array $id_kelas_arr Array ID kelas
     */
    public function updateKelasGuru($id_guru, $id_kelas_arr) {
        // Hapus relasi lama
        $stmt = $this->conn->prepare("DELETE FROM guru_kelas WHERE id_guru = ?");
        $stmt->bind_param("i", $id_guru);
        $stmt->execute();
        $stmt->close();

        // Insert relasi baru
        if (!empty($id_kelas_arr)) {
            $stmt = $this->conn->prepare("INSERT INTO guru_kelas (id_guru, id_kelas) VALUES (?, ?)");
            foreach ($id_kelas_arr as $id_kelas) {
                $stmt->bind_param("ii", $id_guru, $id_kelas);
                $stmt->execute();
            }
            $stmt->close();
        }
    }

    /**
     * Ambil ID kelas yang diajar guru (array of IDs)
     * @param int $id_guru
     * @return array
     */
    public function getKelasIds($id_guru) {
        $stmt = $this->conn->prepare("SELECT id_kelas FROM guru_kelas WHERE id_guru = ?");
        $stmt->bind_param("i", $id_guru);
        $stmt->execute();
        $result = $stmt->get_result();
        $ids = [];
        while ($row = $result->fetch_assoc()) {
            $ids[] = $row['id_kelas'];
        }
        $stmt->close();
        return $ids;
    }
}
