<?php
require_once __DIR__ . '/../../config/database.php';

class Pedido {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function crear($data)
    {
        $sql = "INSERT INTO pedidos 
                (id_usuario, total, estado, metodo_pago, tipo_entrega, direccion, fecha_entrega, hora_entrega, metodo_pago_detalle, comentarios) 
                VALUES 
                (:id_usuario, :total, :estado, :metodo_pago, :tipo_entrega, :direccion, :fecha_entrega, :hora_entrega, :metodo_pago_detalle, :comentarios)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_usuario' => $data['id_usuario'],
            'total' => $data['total'],
            'estado' => $data['estado'],
            'metodo_pago' => $data['metodo_pago'],
            'tipo_entrega' => $data['tipo_entrega'],
            'direccion' => $data['direccion'],
            'fecha_entrega' => $data['fecha_entrega'],
            'hora_entrega' => $data['hora_entrega'],
            'metodo_pago_detalle' => $data['metodo_pago_detalle'],
            'comentarios' => $data['comentarios']
        ]);
        return $this->db->lastInsertId();
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM pedidos WHERE id_pedido = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPorUsuario($id_usuario) {
        $sql = "SELECT * FROM pedidos WHERE id_usuario = ? ORDER BY fecha_pedido DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerUltimosPedidos($id_usuario) {
        $sql = "SELECT * FROM pedidos WHERE id_usuario = :id ORDER BY fecha_pedido DESC LIMIT 3";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}