<?php
require_once __DIR__ . '/../../config/database.php';

class Reserva {
    public static function crear($usuario_id, $fecha, $hora, $personas, $tipo_mesa, $detalles) {
        $db = Database::connect();
        $stmt = $db->prepare("INSERT INTO reservas (usuario_id, fecha, hora, personas, tipo_mesa, detalles) VALUES (:uid, :fecha, :hora, :personas, :tipo, :detalles)");
        $stmt->bindParam(':uid', $usuario_id, PDO::PARAM_INT);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora', $hora);
        $stmt->bindParam(':personas', $personas, PDO::PARAM_INT);
        $stmt->bindParam(':tipo', $tipo_mesa);
        $stmt->bindParam(':detalles', $detalles);
        return $stmt->execute();
    }
}