<?php
require_once __DIR__ . '/../../config/database.php';

class Carrito {
    public static function agregar($usuario_id, $producto_id, $cantidad = 1) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM carrito WHERE usuario_id = :uid AND producto_id = :pid");
        $stmt->bindParam(':uid', $usuario_id, PDO::PARAM_INT);
        $stmt->bindParam(':pid', $producto_id, PDO::PARAM_INT);
        $stmt->execute();
        $item = $stmt->fetch();
        if ($item) {
            $stmt = $db->prepare("UPDATE carrito SET cantidad = cantidad + :cant WHERE id = :id");
            $stmt->bindParam(':cant', $cantidad, PDO::PARAM_INT);
            $stmt->bindParam(':id', $item['id'], PDO::PARAM_INT);
            return $stmt->execute();
        } else {
            $stmt = $db->prepare("INSERT INTO carrito (usuario_id, producto_id, cantidad) VALUES (:uid, :pid, :cant)");
            $stmt->bindParam(':uid', $usuario_id, PDO::PARAM_INT);
            $stmt->bindParam(':pid', $producto_id, PDO::PARAM_INT);
            $stmt->bindParam(':cant', $cantidad, PDO::PARAM_INT);
            return $stmt->execute();
        }
    }

    public static function obtener($usuario_id) {
        $db = Database::connect();
        $stmt = $db->prepare("
            SELECT c.*, p.nombre, p.precio, p.imagen 
            FROM carrito c
            JOIN productos p ON c.producto_id = p.id
            WHERE c.usuario_id = :uid
        ");
        $stmt->bindParam(':uid', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function actualizarCantidad($item_id, $cantidad) {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE carrito SET cantidad = :cant WHERE id = :id");
        $stmt->bindParam(':cant', $cantidad, PDO::PARAM_INT);
        $stmt->bindParam(':id', $item_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function eliminar($item_id) {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM carrito WHERE id = :id");
        $stmt->bindParam(':id', $item_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function limpiar($usuario_id) {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM carrito WHERE usuario_id = :uid");
        $stmt->bindParam(':uid', $usuario_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}