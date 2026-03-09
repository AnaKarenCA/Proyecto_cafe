<?php
require_once __DIR__ . '/../../config/database.php';

class DetallePedido {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    /**
     * Inserta un detalle de pedido
     * @param int $id_pedido
     * @param int $id_producto
     * @param int $cantidad
     * @param float $precio_unitario
     * @return bool
     */
    public function insertar($id_pedido, $id_producto, $cantidad, $precio_unitario) {
        $sql = "INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario) 
                VALUES (:id_pedido, :id_producto, :cantidad, :precio_unitario)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id_pedido' => $id_pedido,
            'id_producto' => $id_producto,
            'cantidad' => $cantidad,
            'precio_unitario' => $precio_unitario
        ]);
    }
    public function obtenerPorId($id) {
    $sql = "SELECT * FROM pedidos WHERE id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}