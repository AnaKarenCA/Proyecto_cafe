<?php
require_once __DIR__ . '/../../config/database.php';

class Clima {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM climas ORDER BY nombre_clima");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
