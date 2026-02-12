<?php
require_once __DIR__ . '/../../config/database.php';

class Producto {
    public static function getDestacados($limite = 3) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM productos WHERE destacado = TRUE LIMIT :limite");
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getPorCategoria($categoria_id) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM productos WHERE categoria_id = :cat_id");
        $stmt->bindParam(':cat_id', $categoria_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getById($id) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM productos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
}