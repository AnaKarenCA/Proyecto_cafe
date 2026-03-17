<?php
require_once __DIR__ . '/../../config/database.php';

class DetallePedido
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function crear($data)
    {
        $sql = "INSERT INTO detalle_pedido (id_pedido, id_producto, id_tamano, cantidad, precio_unitario, subtotal, personalizacion) 
                VALUES (:id_pedido, :id_producto, :id_tamano, :cantidad, :precio_unitario, :subtotal, :personalizacion)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_pedido' => $data['id_pedido'],
            'id_producto' => $data['id_producto'],
            'id_tamano' => $data['id_tamano'],
            'cantidad' => $data['cantidad'],
            'precio_unitario' => $data['precio_unitario'],
            'subtotal' => $data['subtotal'],
            'personalizacion' => $data['personalizacion']
        ]);
        return $this->db->lastInsertId();
    }

    public function agregarExtra($id_detalle, $id_extra)
    {
        $sql = "INSERT INTO detalle_extra (id_detalle, id_extra) VALUES (:id_detalle, :id_extra)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id_detalle' => $id_detalle, 'id_extra' => $id_extra]);
    }
}