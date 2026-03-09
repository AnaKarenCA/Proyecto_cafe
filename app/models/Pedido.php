<?php
require_once __DIR__ . '/../../config/database.php';

class Pedido {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    /**
     * Crea un nuevo pedido
     * @param int $id_usuario
     * @param float $total
     * @param string $comentarios
     * @param string $metodo_pago
     * @return int|false ID del pedido o false si falla
     */
    public function crear($id_usuario, $total, $comentarios, $metodo_pago) {
        $sql = "INSERT INTO pedidos (id_usuario, total, estado, metodo_pago, comentarios) 
                VALUES (:id_usuario, :total, 'pendiente', :metodo_pago, :comentarios)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            'id_usuario' => $id_usuario,
            'total' => $total,
            'metodo_pago' => $metodo_pago,
            'comentarios' => $comentarios
        ]);
        if ($result) {
            return $this->db->lastInsertId();
        }
        return false;
    }
    public function obtenerPorId($id) {
    $sql = "SELECT * FROM pedidos WHERE id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

   

    public function obtenerPorUsuario($id_usuario) {

        $sql = "SELECT * FROM pedidos
                WHERE id_usuario = ?
                ORDER BY fecha_pedido DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_usuario]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
public function obtenerUltimosPedidos($id_usuario) {
    $sql = "SELECT * 
            FROM pedidos 
            WHERE id_usuario = :id 
            ORDER BY fecha_pedido DESC 
            LIMIT 3";

    $stmt = $this->db->prepare($sql);
    $stmt->execute(['id' => $id_usuario]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}