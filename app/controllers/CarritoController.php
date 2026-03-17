<?php

require_once __DIR__ . '/../models/Carrito.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Tamano.php';
require_once __DIR__ . '/../models/Extra.php';

class CarritoController {

    /**
     * Agrega un producto al carrito (soporta JSON y POST tradicional)
     */
    public function agregar() {
        // Detectar tipo de petición
        $input = json_decode(file_get_contents("php://input"), true);
        $isJson = $input !== null;

        if ($isJson) {
            header('Content-Type: application/json');
            $producto_id = intval($input['producto_id'] ?? 0);
            $cantidad = intval($input['cantidad'] ?? 1);
            $id_tamano = isset($input['id_tamano']) ? intval($input['id_tamano']) : null;
            $extras_ids = isset($input['extras']) ? array_map('intval', $input['extras']) : [];
        } else {
            $producto_id = intval($_POST['id'] ?? 0);
            $cantidad = intval($_POST['cantidad'] ?? 1);
            $id_tamano = isset($_POST['id_tamano']) ? intval($_POST['id_tamano']) : null;
            $extras_ids = isset($_POST['extras']) ? (array)$_POST['extras'] : [];
            if (is_string($extras_ids)) {
                $extras_ids = array_map('intval', explode(',', $extras_ids));
            }
        }

        if ($producto_id <= 0) {
            if ($isJson) {
                echo json_encode(["status" => "error", "message" => "ID de producto inválido"]);
            } else {
                $_SESSION['error'] = "ID de producto inválido";
                header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
            }
            return;
        }

        $productoModel = new Producto();
        $producto = $productoModel->getById($producto_id);

        if (!$producto) {
            if ($isJson) {
                echo json_encode(["status" => "error", "message" => "Producto no encontrado"]);
            } else {
                $_SESSION['error'] = "Producto no encontrado";
                header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
            }
            return;
        }

        $precio_base = floatval($producto['precio'] ?? 0);

        // Obtener incremento y nombre del tamaño si se seleccionó
        $incremento_tamano = 0;
        $nombre_tamano = null;
        if ($id_tamano) {
            $tamanoModel = new Tamano();
            $tamano = $tamanoModel->getById($id_tamano);
            if ($tamano) {
                $incremento_tamano = floatval($tamano['incremento_precio']);
                $nombre_tamano = $tamano['nombre_tamano'];
            }
        }

        // Obtener detalles de los extras
        $extras_detalle = [];
        if (!empty($extras_ids)) {
            $extraModel = new Extra();
            foreach ($extras_ids as $id_extra) {
                $extra = $extraModel->getById($id_extra);
                if ($extra) {
                    $extras_detalle[] = [
                        'id_extra' => $id_extra,
                        'nombre_extra' => $extra['nombre_extra'],
                        'precio_extra' => floatval($extra['precio_extra'])
                    ];
                }
            }
        }

        // Llamar al modelo con todos los parámetros
        Carrito::agregar(
            $producto['id_producto'],
            $producto['nombre'],
            $precio_base,
            $cantidad,
            $producto['imagen'] ?? 'default.jpg',
            $id_tamano,
            $nombre_tamano,          // Nuevo: guardamos el nombre del tamaño
            $extras_ids,
            $incremento_tamano,
            $extras_detalle
        );

        if ($isJson) {
            echo json_encode([
                "status" => "success",
                "totalItems" => Carrito::totalItems()
            ]);
        } else {
            $_SESSION['success'] = "Producto agregado al carrito";
            header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
        }
    }

    /**
     * Elimina un ítem del carrito (por clave)
     */
    public function quitar() {
        $clave = $_GET['id'] ?? null;
        if ($clave) {
            Carrito::eliminar($clave);
        }
        header("Location: index.php?controller=carrito&action=ver");
    }

    public function eliminar() {
        $this->quitar();
    }

    /**
     * Muestra la página completa del carrito
     */
    public function ver() {
        $items = Carrito::obtener();
        $subtotal = Carrito::subtotal();
        $taxRate = $this->getTaxRate();
        $taxName = $this->getTaxName();
        $total = Carrito::total();

        include __DIR__ . '/../views/carrito_completo.php';
    }

    /**
     * Renderiza el panel lateral del carrito (para AJAX)
     */
    public function render() {
        $items = Carrito::obtener();
        $subtotal = Carrito::subtotal();
        $taxRate = $this->getTaxRate();
        $taxName = $this->getTaxName();
        $total = Carrito::total();
        include __DIR__ . '/../views/carrito.php';
    }

    /**
     * Devuelve el total de ítems en JSON (para actualizar contador)
     */
    public function totalItems() {
        header('Content-Type: application/json');
        echo json_encode(['total' => Carrito::totalItems()]);
    }

    /**
     * Actualiza la cantidad de un ítem del carrito vía AJAX
     */
    public function actualizarCantidad() {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents("php://input"), true);
        if (!$input || !isset($input['clave']) || !isset($input['cantidad'])) {
            echo json_encode(["status" => "error", "message" => "Datos inválidos"]);
            return;
        }

        $clave = $input['clave'];
        $cantidad = intval($input['cantidad']);

        if ($cantidad < 1) $cantidad = 1;
        if ($cantidad > 10) $cantidad = 10;

        Carrito::actualizarCantidad($clave, $cantidad);

        echo json_encode([
            "status" => "success",
            "subtotal" => Carrito::subtotal(),
            "total" => Carrito::total(),
            "totalItems" => Carrito::totalItems()
        ]);
    }

    // Funciones auxiliares de impuestos
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