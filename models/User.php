<?php
/**
 * Model User
 * Mengelola data tabel users
 */

class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Login - cari user berdasarkan username
     * @param string $username
     * @return array|null Data user atau null jika tidak ditemukan
     */
    public function login($username) {
        $stmt = $this->conn->prepare("SELECT u.*, g.nama as nama_guru FROM users u LEFT JOIN guru g ON u.id_guru = g.id_guru WHERE u.username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    /**
     * Ambil semua user
     * @return array Daftar user
     */
    public function getAll() {
        $sql = "SELECT u.*, g.nama as nama_guru FROM users u LEFT JOIN guru g ON u.id_guru = g.id_guru ORDER BY u.id ASC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Ambil user berdasarkan ID
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    /**
     * Tambah user baru
     * @param string $username
     * @param string $password Password plain text (akan di-hash)
     * @param string $role 'admin' atau 'guru'
     * @param int|null $id_guru
     * @return bool
     */
    public function create($username, $password, $role, $id_guru = null) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (username, password, role, id_guru) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $username, $hashed, $role, $id_guru);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Update data user
     */
    public function update($id, $username, $role, $id_guru = null, $password = null) {
        if ($password) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("UPDATE users SET username = ?, password = ?, role = ?, id_guru = ? WHERE id = ?");
            $stmt->bind_param("sssii", $username, $hashed, $role, $id_guru, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE users SET username = ?, role = ?, id_guru = ? WHERE id = ?");
            $stmt->bind_param("ssii", $username, $role, $id_guru, $id);
        }
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Hapus user
     */
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
