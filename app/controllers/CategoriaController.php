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

    require_once APP_PATH . '/models/Producto.php';
    require_once APP_PATH . '/models/Categoria.php';

    $productoModel = new Producto();
    $categoriaModel = new Categoria();

    $idioma = $_SESSION['lang'] ?? 'es';

    $categoria = $categoriaModel->getById($id);
    $productos = $productoModel->getByCategoria($id, $idioma);

    require APP_PATH . '/views/categoria.php';
}
}