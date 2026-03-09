<?php
require_once __DIR__ . '/../../config/Database.php';

class Reserva {

    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function crear($datos) {

        // Verificar disponibilidad antes de insertar
        $sqlCheck = "SELECT COUNT(*) FROM reservas WHERE fecha = ? AND hora = ?";
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->execute([$datos['fecha'], $datos['hora']]);

        $total = $stmtCheck->fetchColumn();

        if ($total >= 10) {
            return false; // No hay mesas disponibles
        }

        $sql = "INSERT INTO reservas 
                (id_usuario, nombre, correo, telefono, fecha, hora, personas)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $datos['id_usuario'],
            $datos['nombre'],
            $datos['correo'],
            $datos['telefono'],
            $datos['fecha'],
            $datos['hora'],
            $datos['personas']
        ]);
    }

    public function disponibilidadPorFecha($fecha) {

        $sql = "SELECT hora, COUNT(*) as total
                FROM reservas
                WHERE fecha = ?
                GROUP BY hora";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fecha]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function obtenerPorUsuario($id_usuario) {

    $sql = "SELECT * FROM reservas
            WHERE id_usuario = ?
            ORDER BY fecha DESC, hora DESC";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$id_usuario]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}