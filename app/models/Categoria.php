<?php
require_once __DIR__ . '/../../config/database.php';

class Categoria {
    public static function getAll() {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM categorias ORDER BY id");
        return $stmt->fetchAll();
    }

    public static function getById($id) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM categorias WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
}