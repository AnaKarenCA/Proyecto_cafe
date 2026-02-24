<?php
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Pedido.php';  // Se asume que existe
require_once __DIR__ . '/../models/DetallePedido.php';

class CarritoController {
    private $productoModel;
    private $pedidoModel;
    private $detalleModel;

    public function __construct() {
        $this->productoModel = new Producto();
        // Estos modelos deberían crearse; por simplicidad se usan métodos estáticos o se asumen existentes
    }

    // Inicializa el carrito en sesión si no existe
    private function initCarrito() {
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
    }

    // Muestra el contenido del carrito (vista parcial o completa)
    public function ver() {
        $this->initCarrito();
        $items = $_SESSION['carrito'];
        $total = $this->calcularTotal($items);
        // Puede ser una vista parcial incluida en otras páginas o una página completa
        require_once __DIR__ . '/../views/carrito.php';
    }

    // Agrega un producto al carrito
    public function agregar() {
        $this->initCarrito();
        $id = $_POST['id'] ?? $_GET['id'] ?? null;
        $cantidad = $_POST['cantidad'] ?? 1;

        if (!$id) {
            $_SESSION['error'] = "Producto no especificado.";
            header('Location: index.php?controller=producto&action=index');
            exit;
        }

        $producto = $this->productoModel->getById($id);
        if (!$producto) {
            $_SESSION['error'] = "Producto no encontrado.";
            header('Location: index.php?controller=producto&action=index');
            exit;
        }

        // Si ya existe, aumentar cantidad
        $encontrado = false;
        foreach ($_SESSION['carrito'] as &$item) {
            if ($item['id'] == $id) {
                $item['cantidad'] += $cantidad;
                $encontrado = true;
                break;
            }
        }
        if (!$encontrado) {
            $_SESSION['carrito'][] = [
                'id' => $id,
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'cantidad' => $cantidad,
                'imagen' => $producto['imagen'] ?? ''
            ];
        }

        $_SESSION['success'] = "Producto agregado al carrito.";
        header('Location: index.php?controller=carrito&action=ver');
    }

    // Actualiza la cantidad de un producto en el carrito
    public function actualizar() {
        $this->initCarrito();
        $id = $_POST['id'] ?? null;
        $cantidad = $_POST['cantidad'] ?? 1;

        if ($id && $cantidad > 0) {
            foreach ($_SESSION['carrito'] as &$item) {
                if ($item['id'] == $id) {
                    $item['cantidad'] = $cantidad;
                    break;
                }
            }
        }
        header('Location: index.php?controller=carrito&action=ver');
    }

    // Elimina un producto del carrito
    public function quitar() {
        $this->initCarrito();
        $id = $_GET['id'] ?? null;
        if ($id) {
            $_SESSION['carrito'] = array_filter($_SESSION['carrito'], function($item) use ($id) {
                return $item['id'] != $id;
            });
        }
        header('Location: index.php?controller=carrito&action=ver');
    }

    // Muestra el formulario de checkout (requiere login)
    public function checkout() {
        $this->initCarrito();
        if (empty($_SESSION['carrito'])) {
            $_SESSION['error'] = "El carrito está vacío.";
            header('Location: index.php?controller=producto&action=index');
            exit;
        }

        // Verificar autenticación
        if (!isset($_SESSION['usuario_id'])) {
            $_SESSION['redirect_after_login'] = 'index.php?controller=carrito&action=checkout';
            header('Location: index.php?controller=auth&action=showLoginForm');
            exit;
        }

        // Mostrar formulario de confirmación de pedido
        $items = $_SESSION['carrito'];
        $total = $this->calcularTotal($items);
        $titulo = "Confirmar pedido";
        require_once __DIR__ . '/../views/checkout.php'; // Vista no listada, se asume creada
    }

    // Procesa el pedido (guarda en BD)
    public function confirmar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=carrito&action=ver');
            exit;
        }

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?controller=auth&action=showLoginForm');
            exit;
        }

        $this->initCarrito();
        if (empty($_SESSION['carrito'])) {
            $_SESSION['error'] = "Carrito vacío.";
            header('Location: index.php?controller=producto&action=index');
            exit;
        }

        $id_usuario = $_SESSION['usuario_id'];
        $comentarios = $_POST['comentarios'] ?? '';
        $metodo_pago = $_POST['metodo_pago'] ?? 'efectivo';

        // Calcular total
        $total = $this->calcularTotal($_SESSION['carrito']);

        // Aquí se llamaría al modelo Pedido para guardar
        // Por simplicidad, se muestra un mensaje de éxito y se vacía el carrito
        // En un caso real: $pedido_id = $this->pedidoModel->crear($id_usuario, $total, $comentarios, $metodo_pago);
        // luego guardar cada detalle con $this->detalleModel->insertar(...)

        $_SESSION['success'] = "¡Pedido realizado con éxito! (simulación)";
        unset($_SESSION['carrito']);
        header('Location: index.php?controller=welcome&action=index');
        exit;
    }

    // Calcula el total del carrito
    private function calcularTotal($items) {
        $total = 0;
        foreach ($items as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        return $total;
    }
}