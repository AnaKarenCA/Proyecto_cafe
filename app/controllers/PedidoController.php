<?php
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../models/DetallePedido.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Carrito.php';

class PedidoController {

    private $pedidoModel;
    private $detalleModel;
    private $productoModel;

    public function __construct() {
        $this->pedidoModel = new Pedido();
        $this->detalleModel = new DetallePedido();
        $this->productoModel = new Producto();
    }

    public function repetir() {

        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=auth&action=showLoginForm");
            exit();
        }

        if (!isset($_GET['id'])) {
            header("Location: index.php");
            exit();
        }

        $pedidoId = (int) $_GET['id'];
        $usuarioId = $_SESSION['usuario_id'];

        // Verificar que el pedido pertenece al usuario
        $pedido = $this->pedidoModel->obtenerPorId($pedidoId);

        if (!$pedido || $pedido['usuario_id'] != $usuarioId) {
            $_SESSION['error'] = "Pedido no válido.";
            header("Location: index.php?controller=usuario&action=perfil");
            exit();
        }

        // Obtener detalles del pedido
        $detalles = $this->detalleModel->obtenerPorPedido($pedidoId);

        if (empty($detalles)) {
            $_SESSION['error'] = "El pedido no tiene productos.";
            header("Location: index.php?controller=usuario&action=perfil");
            exit();
        }

        // Vaciar carrito actual
        Carrito::vaciar();

        // Agregar productos al carrito
        foreach ($detalles as $detalle) {

            $producto = $this->productoModel->getById($detalle['producto_id']);

            if ($producto) {
                Carrito::agregar(
                    $producto['id'],
                    $producto['nombre'],
                    $producto['precio'],
                    $detalle['cantidad'],
                    $producto['imagen'] ?? ''
                );
            }
        }

        $_SESSION['success'] = "Pedido agregado nuevamente al carrito.";

        header("Location: index.php?controller=carrito&action=ver");
        exit();
    }
}