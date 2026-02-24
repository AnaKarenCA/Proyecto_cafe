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

    /**
     * Lista todos los productos con filtros opcionales (categoría o búsqueda)
     */
    public function index() {
        $categoria_id = $_GET['categoria'] ?? null;
        $busqueda = $_GET['q'] ?? null;

        if ($categoria_id) {
            $productos = $this->productoModel->getByCategoria($categoria_id);
        } elseif ($busqueda) {
            $productos = $this->productoModel->buscar($busqueda);
        } else {
            $productos = $this->productoModel->getAll();
        }

        $categorias = $this->categoriaModel->getAll();
        $titulo = "Productos";
        require_once __DIR__ . '/../views/productos.php';
    }

    /**
     * Muestra el detalle de un producto individual
     * @param int $id ID del producto
     */
    public function show($id) {
        $producto = $this->productoModel->getById($id);
        if (!$producto) {
            header('HTTP/1.0 404 Not Found');
            echo "Producto no encontrado";
            exit;
        }
        $titulo = $producto['nombre'];
        require_once __DIR__ . '/../views/producto_detalle.php';
    }

    /**
     * Devuelve productos destacados (usado por WelcomeController)
     */
    public function destacados() {
        return $this->productoModel->getDestacados();
    }

    /**
     * Método para búsqueda por voz (responde JSON)
     */
    public function buscarAjax() {
        $termino = $_GET['q'] ?? '';
        if (strlen($termino) < 2) {
            echo json_encode([]);
            exit;
        }
        $productos = $this->productoModel->buscar($termino);
        header('Content-Type: application/json');
        echo json_encode($productos);
    }
}