<?php
class Carrito {

    /**
     * Inicializa el carrito en sesión si no existe
     */
    public static function init() {
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
    }

    /**
     * Obtiene el contenido del carrito
     */
    public static function getContenido() {
        self::init();
        return $_SESSION['carrito'];
    }

    /**
     * Agrega un producto al carrito (o incrementa cantidad)
     * @param int $id_producto
     * @param string $nombre
     * @param float $precio
     * @param int $cantidad
     * @param string $imagen (opcional)
     */
    public static function agregar($id_producto, $nombre, $precio, $cantidad = 1, $imagen = '') {
        self::init();
        $encontrado = false;
        foreach ($_SESSION['carrito'] as &$item) {
            if ($item['id'] == $id_producto) {
                $item['cantidad'] += $cantidad;
                $encontrado = true;
                break;
            }
        }
        if (!$encontrado) {
            $_SESSION['carrito'][] = [
                'id' => $id_producto,
                'nombre' => $nombre,
                'precio' => $precio,
                'cantidad' => $cantidad,
                'imagen' => $imagen
            ];
        }
    }

    /**
     * Actualiza la cantidad de un producto específico
     */
    public static function actualizarCantidad($id_producto, $nueva_cantidad) {
        self::init();
        foreach ($_SESSION['carrito'] as &$item) {
            if ($item['id'] == $id_producto) {
                if ($nueva_cantidad <= 0) {
                    self::quitar($id_producto);
                } else {
                    $item['cantidad'] = $nueva_cantidad;
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Elimina un producto del carrito
     */
    public static function quitar($id_producto) {
        self::init();
        $_SESSION['carrito'] = array_filter($_SESSION['carrito'], function($item) use ($id_producto) {
            return $item['id'] != $id_producto;
        });
    }

    /**
     * Vacía el carrito
     */
    public static function vaciar() {
        $_SESSION['carrito'] = [];
    }

    /**
     * Calcula el total del carrito
     */
    public static function total() {
        self::init();
        $total = 0;
        foreach ($_SESSION['carrito'] as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        return $total;
    }

    /**
     * Cuenta el número de productos (suma de cantidades)
     */
    public static function contarItems() {
        self::init();
        $count = 0;
        foreach ($_SESSION['carrito'] as $item) {
            $count += $item['cantidad'];
        }
        return $count;
    }
}