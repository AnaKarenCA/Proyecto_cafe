<?php
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../models/Producto.php';

class CategoriaController {
    private $categoriaModel;
    private $productoModel;

    public function __construct() {
        $this->categoriaModel = new Categoria();
        $this->productoModel = new Producto();
    }

    // Lista todas las categorías (para el menú o página principal)
    public function index() {
        $categorias = $this->categoriaModel->getAll();
        $titulo = "Categorías";
        require_once __DIR__ . '/../views/categorias.php';
    }

    // Muestra los productos de una categoría específica
    public function show($id) {
        $categoria = $this->categoriaModel->getById($id);
        if (!$categoria) {
            header('HTTP/1.0 404 Not Found');
            echo "Categoría no encontrada";
            exit;
        }
        $productos = $this->productoModel->getByCategoria($id);
        $titulo = $categoria['nombre_categoria'];
        require_once __DIR__ . '/../views/productos.php';
    }
}