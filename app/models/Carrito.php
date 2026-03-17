<?php

class Carrito {

    public static function iniciar() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        } else {
            // Migrar items antiguos
            foreach ($_SESSION['carrito'] as $clave => &$item) {
                if (!isset($item['precio_unitario'])) {
                    $precio = isset($item['precio_base']) ? floatval($item['precio_base']) : 0;
                    if (isset($item['incremento_tamano'])) {
                        $precio += floatval($item['incremento_tamano']);
                    }
                    if (isset($item['extras_detalle']) && is_array($item['extras_detalle'])) {
                        foreach ($item['extras_detalle'] as $extra) {
                            $precio += isset($extra['precio_extra']) ? floatval($extra['precio_extra']) : 0;
                        }
                    } else {
                        $item['extras_detalle'] = [];
                    }
                    $item['precio_unitario'] = $precio;
                }
                if (!isset($item['extras_detalle'])) {
                    $item['extras_detalle'] = [];
                }
                if (!isset($item['id_tamano'])) {
                    $item['id_tamano'] = null;
                }
                if (!isset($item['nombre_tamano'])) {
                    $item['nombre_tamano'] = null;
                }
            }
        }
    }

    public static function agregar($id_producto, $nombre, $precio_base, $cantidad, $imagen,
                                   $id_tamano = null, $nombre_tamano = null, $extras_ids = [], $incremento_tamano = 0, $extras_detalle = []) {
        self::iniciar();

        $precio_unitario = $precio_base + $incremento_tamano;
        foreach ($extras_detalle as $extra) {
            $precio_unitario += $extra['precio_extra'];
        }

        // Clave única basada en producto, tamaño y extras
        $clave = $id_producto . '_' . ($id_tamano ?? '0') . '_' . implode('_', $extras_ids);

        if (isset($_SESSION['carrito'][$clave])) {
            $_SESSION['carrito'][$clave]['cantidad'] += $cantidad;
            $_SESSION['carrito'][$clave]['precio_unitario'] = $precio_unitario; // actualizar por si cambió
        } else {
            $_SESSION['carrito'][$clave] = [
                'id_producto' => $id_producto,
                'nombre'      => $nombre,
                'precio_base' => $precio_base,
                'precio_unitario' => $precio_unitario,
                'cantidad'    => $cantidad,
                'imagen'      => $imagen,
                'id_tamano'   => $id_tamano,
                'nombre_tamano' => $nombre_tamano,
                'extras_ids'  => $extras_ids,
                'extras_detalle' => $extras_detalle,
                'incremento_tamano' => $incremento_tamano
            ];
        }
    }

    public static function actualizarCantidad($clave, $cantidad) {
        self::iniciar();
        if (isset($_SESSION['carrito'][$clave])) {
            $_SESSION['carrito'][$clave]['cantidad'] = $cantidad;
        }
    }

    public static function eliminar($clave) {
        self::iniciar();
        unset($_SESSION['carrito'][$clave]);
    }

    public static function obtener() {
        self::iniciar();
        return $_SESSION['carrito'];
    }

    public static function getContenido() {
        return self::obtener();
    }

    public static function subtotal() {
        self::iniciar();
        $total = 0;
        foreach ($_SESSION['carrito'] as $item) {
            $total += $item['precio_unitario'] * $item['cantidad'];
        }
        return $total;
    }

    public static function total() {
        $subtotal = self::subtotal();
        $tasa = self::getTaxRate();
        return $subtotal * (1 + $tasa);
    }

    public static function totalItems() {
        self::iniciar();
        $total = 0;
        foreach ($_SESSION['carrito'] as $item) {
            $total += $item['cantidad'];
        }
        return $total;
    }

    private static function getTaxRate() {
        $idioma = $_SESSION['idioma'] ?? 'es';
        switch ($idioma) {
            case 'es': return 0.16;
            case 'en': return 0.07;
            case 'de': return 0.19;
            default: return 0.16;
        }
    }

    public static function getTaxName() {
        $idioma = $_SESSION['idioma'] ?? 'es';
        switch ($idioma) {
            case 'es': return 'IVA (16%)';
            case 'en': return 'Sales Tax (7%)';
            case 'de': return 'MwSt (19%)';
            default: return 'IVA';
        }
    }

    public static function vaciar() {
        self::iniciar();
        $_SESSION['carrito'] = [];
    }
}