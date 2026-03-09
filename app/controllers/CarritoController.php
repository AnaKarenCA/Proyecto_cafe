<?php
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../models/DetallePedido.php';

class CarritoController {
    private $productoModel;
    private $pedidoModel;
    private $detalleModel;

    public function __construct() {
        $this->productoModel = new Producto();
        $this->pedidoModel = new Pedido();
        $this->detalleModel = new DetallePedido();
    }

    // Inicializa el carrito en sesión si no existe
    private function initCarrito() {
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
    }

    // Muestra la página completa del carrito
    public function ver() {
        $this->initCarrito();
        require_once __DIR__ . '/../views/carrito_completo.php';
    }

    // Agrega un producto al carrito
    public function agregar() {
        $this->initCarrito();
        $id = $_POST['id'] ?? $_GET['id'] ?? null;
        $cantidad = (int)($_POST['cantidad'] ?? 1);

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
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php?controller=producto&action=index'));
    }

    // Actualiza la cantidad de un producto en el carrito
    public function actualizar() {
        $this->initCarrito();
        $id = $_POST['id'] ?? null;
        $cantidad = (int)($_POST['cantidad'] ?? 1);

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
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php?controller=carrito&action=ver'));
    }

    // Muestra el formulario de checkout (requiere login)
    public function checkout() {
        $this->initCarrito();
        if (empty($_SESSION['carrito'])) {
            $_SESSION['error'] = "El carrito está vacío.";
            header('Location: index.php?controller=producto&action=index');
            exit;
        }

        if (!isset($_SESSION['usuario_id'])) {
            $_SESSION['redirect_after_login'] = 'index.php?controller=carrito&action=checkout';
            header('Location: index.php?controller=auth&action=showLoginForm');
            exit;
        }

        $items = $_SESSION['carrito'];
        $total = $this->calcularTotal($items);
        $titulo = "Confirmar pedido";
        require_once __DIR__ . '/../views/checkout.php';
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
        $total = $this->calcularTotal($_SESSION['carrito']);

        $db = Database::connect();
        $db->beginTransaction();

        try {
            $id_pedido = $this->pedidoModel->crear($id_usuario, $total, $comentarios, $metodo_pago);
            if (!$id_pedido) {
                throw new Exception("Error al crear el pedido.");
            }

            foreach ($_SESSION['carrito'] as $item) {
                $ok = $this->detalleModel->insertar($id_pedido, $item['id'], $item['cantidad'], $item['precio']);
                if (!$ok) {
                    throw new Exception("Error al guardar detalle del pedido.");
                }
            }

            $db->commit();
            $_SESSION['success'] = "¡Pedido realizado con éxito!";
            unset($_SESSION['carrito']);
            header('Location: index.php?controller=welcome&action=index');
            exit;
        } catch (Exception $e) {
            $db->rollBack();
            $_SESSION['error'] = "Error al procesar el pedido: " . $e->getMessage();
            header('Location: index.php?controller=carrito&action=checkout');
            exit;
        }
    }

    private function calcularTotal($items) {
        $total = 0;
        foreach ($items as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        return $total;
    }
    public function actualizarAjax() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['cantidad'])) {
        $id = (int)$_POST['id'];
        $cantidad = (int)$_POST['cantidad'];
        if ($cantidad >= 1 && $cantidad <= 10) {
           Carrito::actualizarCantidad($id, $cantidad);
        }
    }
    $this->responderJson();
}

public function quitarAjax() {
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        Carrito::quitar($id);
    }
    $this->responderJson();
}

public function render() {
    // Devuelve solo el HTML del carrito (sin layout)
    include 'views/carrito.php';
    exit;
}

private function responderJson() {
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit;
}
}