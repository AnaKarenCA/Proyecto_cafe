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
    public function crear($data)
{
    $sql = "INSERT INTO pedidos (id_usuario, total, estado, metodo_pago, comentarios) 
            VALUES (:id_usuario, :total, :estado, :metodo_pago, :comentarios)";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        'id_usuario' => $data['id_usuario'],
        'total' => $data['total'],
        'estado' => $data['estado'],
        'metodo_pago' => $data['metodo_pago'],
        'comentarios' => $data['comentarios']
    ]);
    return $this->db->lastInsertId();
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