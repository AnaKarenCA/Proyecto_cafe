<?php
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Categoria.php';

class ProductoController {
    private $productoModel;
    private $categoriaModel;

    public function __construct() {
        $this->productoModel = new Producto();
        $this->categoriaModel = new Categoria();
    }

    private function getIdioma() {
        return $_SESSION['idioma'] ?? 'es';
    }

    public function index() {

        $idioma = $this->getIdioma();
        $categoria_id = $_GET['categoria'] ?? null;
        $busqueda = $_GET['q'] ?? null;

        if ($categoria_id) {
            $productos = $this->productoModel->getByCategoria($categoria_id, $idioma);
        } elseif ($busqueda) {
            $productos = $this->productoModel->buscar($busqueda, $idioma);
        } else {
            $productos = $this->productoModel->getAll($idioma);
        }

        $categorias = $this->categoriaModel->getAll();
        $titulo = "Productos";

        require_once __DIR__ . '/../views/productos.php';
    }

    public function show($id) {

        $idioma = $this->getIdioma();
        $producto = $this->productoModel->getById($id, $idioma);

        if (!$producto) {
            header('HTTP/1.0 404 Not Found');
            echo "Producto no encontrado";
            exit;
        }

        $titulo = $producto['nombre'];
        require_once __DIR__ . '/../views/producto_detalle.php';
    }

    public function destacados() {
        $idioma = $this->getIdioma();
        return $this->productoModel->getDestacados(6, $idioma);
    }

    public function buscarAjax() {
        $idioma = $this->getIdioma();
        $termino = $_GET['q'] ?? '';

        if (strlen($termino) < 2) {
            echo json_encode([]);
            exit;
        }

        $productos = $this->productoModel->buscar($termino, $idioma);

        header('Content-Type: application/json');
        echo json_encode($productos);
    }
}