<?php
namespace Models;

class Admin {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }
}
?>
