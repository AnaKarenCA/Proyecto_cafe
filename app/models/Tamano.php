<?php
require_once __DIR__ . '/../../config/database.php';

class Tamano {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM tamanos ORDER BY id_tamano");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
    $sql = "SELECT * FROM tamanos WHERE id_tamano = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
}
    public function getByProducto($producto_id) {
        $sql = "SELECT t.* FROM tamanos t
                INNER JOIN producto_tamano pt ON t.id_tamano = pt.id_tamano
                WHERE pt.id_producto = :producto_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['producto_id' => $producto_id]);
        return $stmt->fetchAll();
    }
}