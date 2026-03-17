<?php
require_once __DIR__ . '/../models/Carrito.php';
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../models/DetallePedido.php';
require_once __DIR__ . '/../models/Producto.php';

class CheckoutController
{
    public function index()
    {
        // Verificar que el usuario haya iniciado sesión
        if (!isset($_SESSION['usuario_id'])) {
            $_SESSION['error'] = "Debes iniciar sesión para continuar con el pago.";
            header('Location: index.php?controller=auth&action=showLoginForm');
            exit;
        }

        // Obtener datos del carrito
        $items = Carrito::obtener();
        if (empty($items)) {
            $_SESSION['error'] = "Tu carrito está vacío.";
            header('Location: index.php?controller=producto&action=index');
            exit;
        }

        $subtotal = Carrito::subtotal();
        $taxRate = $this->getTaxRate();
        $taxName = $this->getTaxName();
        $total = Carrito::total();
        $impuestos = $total - $subtotal;

        // Pasar variables a la vista
        require __DIR__ . '/../views/checkout.php';
    }

    public function procesar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=checkout&action=index');
            exit;
        }

        // Verificar sesión
        if (!isset($_SESSION['usuario_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'No autenticado']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos inválidos']);
            exit;
        }

        $id_usuario = $_SESSION['usuario_id'];
        $nombre = $input['nombre'] ?? '';
        $apellido = $input['apellido'] ?? '';
        $telefono = $input['telefono'] ?? '';
        $email = $input['email'] ?? '';
        $tipo_entrega = $input['tipo_entrega'] ?? 'pickup';
        $fecha_entrega = $input['fecha'] ?? null;
        $hora_entrega = $input['hora'] ?? null;
        $direccion = $input['direccion'] ?? '';
        $metodo_pago = $input['metodo_pago'] ?? '';
        $metodo_pago_detalle = $input['metodo_pago_detalle'] ?? '';

        // Calcular total
        $total = Carrito::total();

        // Crear pedido
        $pedidoModel = new Pedido();
        $id_pedido = $pedidoModel->crear([
            'id_usuario' => $id_usuario,
            'total' => $total,
            'estado' => 'pendiente',
            'metodo_pago' => $metodo_pago,
            'comentarios' => json_encode([
                'nombre' => $nombre,
                'apellido' => $apellido,
                'telefono' => $telefono,
                'email' => $email,
                'tipo_entrega' => $tipo_entrega,
                'direccion' => $direccion,
                'fecha_entrega' => $fecha_entrega,
                'hora_entrega' => $hora_entrega,
                'metodo_pago_detalle' => $metodo_pago_detalle
            ])
        ]);

        if (!$id_pedido) {
            http_response_code(500);
            echo json_encode(['error' => 'No se pudo crear el pedido']);
            exit;
        }

        // Guardar detalles del pedido
        $items = Carrito::obtener();
        $detalleModel = new DetallePedido();
        foreach ($items as $item) {
            $id_detalle = $detalleModel->crear([
                'id_pedido' => $id_pedido,
                'id_producto' => $item['id_producto'],
                'id_tamano' => $item['id_tamano'] ?? null,
                'cantidad' => $item['cantidad'],
                'precio_unitario' => $item['precio_unitario'],
                'subtotal' => $item['precio_unitario'] * $item['cantidad'],
                'personalizacion' => json_encode($item['extras_detalle'] ?? [])
            ]);

            // Guardar extras si existen
            if (!empty($item['extras_ids'])) {
                foreach ($item['extras_ids'] as $id_extra) {
                    $detalleModel->agregarExtra($id_detalle, $id_extra);
                }
            }
        }

        // Vaciar carrito
        Carrito::vaciar();

        echo json_encode(['success' => true, 'id_pedido' => $id_pedido]);
    }

    public function confirmacion($id_pedido)
    {
        $pedidoModel = new Pedido();
        $pedido = $pedidoModel->getById($id_pedido);
        if (!$pedido) {
            header('Location: index.php');
            exit;
        }
        require __DIR__ . '/../views/checkout-confirmacion.php';
    }

    private function getTaxRate() {
        $idioma = $_SESSION['idioma'] ?? 'es';
        switch ($idioma) {
            case 'es': return 0.16;
            case 'en': return 0.07;
            case 'de': return 0.19;
            default: return 0.16;
        }
    }

    private function getTaxName() {
        $idioma = $_SESSION['idioma'] ?? 'es';
        switch ($idioma) {
            case 'es': return 'IVA (16%)';
            case 'en': return 'Sales Tax (7%)';
            case 'de': return 'MwSt (19%)';
            default: return 'IVA';
        }
    }
}