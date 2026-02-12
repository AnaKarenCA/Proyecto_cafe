<?php
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Categoria.php';

class ProductoController {
    public function destacados() {
        $productos = Producto::getDestacados(3);
        include __DIR__ . '/../views/welcome.php';
    }

    public function porCategoria() {
        $cat_id = $_GET['id'] ?? 1;
        $categoria = Categoria::getById($cat_id);
        $productos = Producto::getPorCategoria($cat_id);
        include __DIR__ . '/../views/productos.php';
    }
}