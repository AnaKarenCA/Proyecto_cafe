<?php
require_once __DIR__ . '/../../config/Database.php';

class Reserva {

    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function crear($datos) {
        $this->db->beginTransaction();

        try {
            // Buscar una mesa disponible
            $sqlMesa = "SELECT m.id_mesa FROM mesas m
                        WHERE m.disponible = 1
                        AND NOT EXISTS (
                            SELECT 1 FROM reserva_mesa rm
                            JOIN reservas r ON rm.id_reserva = r.id_reserva
                            WHERE rm.id_mesa = m.id_mesa
                            AND r.fecha = ?
                            AND r.hora = ?
                        )
                        LIMIT 1";
            $stmt = $this->db->prepare($sqlMesa);
            $stmt->execute([$datos['fecha'], $datos['hora']]);
            $mesa = $stmt->fetch();

            if (!$mesa) {
                $this->db->rollBack();
                return false;
            }

            // Insertar reserva
            $sqlReserva = "INSERT INTO reservas 
                           (id_usuario, nombre, correo, telefono, fecha, hora, personas)
                           VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sqlReserva);
            $stmt->execute([
                $datos['id_usuario'],
                $datos['nombre'],
                $datos['correo'],
                $datos['telefono'],
                $datos['fecha'],
                $datos['hora'],
                $datos['personas']
            ]);
            $id_reserva = $this->db->lastInsertId();

            // Asignar mesa
            $sqlAsignar = "INSERT INTO reserva_mesa (id_reserva, id_mesa) VALUES (?, ?)";
            $stmt = $this->db->prepare($sqlAsignar);
            $stmt->execute([$id_reserva, $mesa['id_mesa']]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error al crear reserva: " . $e->getMessage());
            return false;
        }
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