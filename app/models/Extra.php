<?php
require_once __DIR__ . '/../../config/database.php';

class Extra {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM extras ORDER BY id_extra");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM extras WHERE id_extra = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    public function getByProducto($producto_id) {
        $sql = "SELECT e.* FROM extras e
                INNER JOIN producto_extra pe ON e.id_extra = pe.id_extra
                WHERE pe.id_producto = :producto_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['producto_id' => $producto_id]);
        return $stmt->fetchAll();
    }
}
