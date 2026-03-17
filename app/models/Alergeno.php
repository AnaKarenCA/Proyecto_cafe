<?php
require_once __DIR__ . '/../../config/database.php';

class Alergeno {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM alergeno ORDER BY nombre_alergeno");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByProducto($id_producto) {
        $stmt = $this->pdo->prepare("
            SELECT a.* FROM alergeno a
            INNER JOIN producto_alergeno pa ON a.id_alergeno = pa.id_alergeno
            WHERE pa.id_producto = ?
        ");
        $stmt->execute([$id_producto]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
