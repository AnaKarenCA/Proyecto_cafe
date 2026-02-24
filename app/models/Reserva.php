<?php
require_once __DIR__ . '/../../config/database.php';

class Reserva {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    /**
     * Crea una nueva reserva
     * @param array $datos Contiene: id_usuario (opcional), nombre_cliente, email_cliente, telefono_cliente, fecha_reserva, hora_reserva, num_personas, comentarios
     * @return bool|int ID de la reserva o false
     */
    public function crear($datos) {
        $sql = "INSERT INTO reservas (id_usuario, nombre_cliente, email_cliente, telefono_cliente, fecha_reserva, hora_reserva, num_personas, comentarios, estado)
                VALUES (:id_usuario, :nombre_cliente, :email_cliente, :telefono_cliente, :fecha_reserva, :hora_reserva, :num_personas, :comentarios, 'pendiente')";
        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([
            'id_usuario' => $datos['id_usuario'] ?? null,
            'nombre_cliente' => $datos['nombre_cliente'],
            'email_cliente' => $datos['email_cliente'],
            'telefono_cliente' => $datos['telefono_cliente'] ?? null,
            'fecha_reserva' => $datos['fecha_reserva'],
            'hora_reserva' => $datos['hora_reserva'],
            'num_personas' => $datos['num_personas'],
            'comentarios' => $datos['comentarios'] ?? null
        ]);
        if ($success) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Obtiene reservas de un usuario específico
     */
    public function getByUsuario($id_usuario) {
        $stmt = $this->db->prepare("SELECT * FROM reservas WHERE id_usuario = :id ORDER BY fecha_reserva DESC, hora_reserva DESC");
        $stmt->execute(['id' => $id_usuario]);
        return $stmt->fetchAll();
    }

    /**
     * Obtiene una reserva por ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM reservas WHERE id_reserva = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Actualiza el estado de una reserva (para administración)
     */
    public function actualizarEstado($id, $estado) {
        $stmt = $this->db->prepare("UPDATE reservas SET estado = :estado WHERE id_reserva = :id");
        return $stmt->execute(['id' => $id, 'estado' => $estado]);
    }

    /**
     * Obtiene todas las reservas (para administración)
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT r.*, u.nombre as usuario_nombre 
                                   FROM reservas r 
                                   LEFT JOIN usuarios u ON r.id_usuario = u.id_usuario 
                                   ORDER BY r.fecha_reserva DESC, r.hora_reserva DESC");
        return $stmt->fetchAll();
    }

    /**
     * Cancela una reserva (el usuario puede cancelar si está en pendiente)
     */
    public function cancelar($id, $id_usuario = null) {
        if ($id_usuario) {
            // Verificar que la reserva pertenezca al usuario
            $stmt = $this->db->prepare("UPDATE reservas SET estado = 'cancelada' WHERE id_reserva = :id AND id_usuario = :usuario AND estado = 'pendiente'");
            return $stmt->execute(['id' => $id, 'usuario' => $id_usuario]);
        } else {
            // Para administración sin verificación de usuario
            $stmt = $this->db->prepare("UPDATE reservas SET estado = 'cancelada' WHERE id_reserva = :id");
            return $stmt->execute(['id' => $id]);
        }
    }
}