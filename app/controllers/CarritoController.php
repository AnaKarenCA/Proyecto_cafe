<?php
session_start();
require_once __DIR__ . '/../models/Carrito.php';

class CarritoController {
    public function agregar() {
        if (!isset($_SESSION['usuario_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'No autenticado']);
            return;
        }
        $usuario_id = $_SESSION['usuario_id'];
        $producto_id = $_POST['producto_id'] ?? 0;
        $cantidad = $_POST['cantidad'] ?? 1;
        $result = Carrito::agregar($usuario_id, $producto_id, $cantidad);
        echo json_encode(['success' => $result]);
    }

    public function obtener() {
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode([]);
            return;
        }
        $items = Carrito::obtener($_SESSION['usuario_id']);
        echo json_encode($items);
    }

    public function actualizar() {
        if (!isset($_SESSION['usuario_id'])) return;
        $item_id = $_POST['item_id'] ?? 0;
        $cantidad = $_POST['cantidad'] ?? 1;
        Carrito::actualizarCantidad($item_id, $cantidad);
        echo json_encode(['success' => true]);
    }

    public function eliminar() {
        if (!isset($_SESSION['usuario_id'])) return;
        $item_id = $_POST['item_id'] ?? 0;
        Carrito::eliminar($item_id);
        echo json_encode(['success' => true]);
    }
}