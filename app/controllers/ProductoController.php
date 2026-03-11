<?php
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Categoria.php';

class ProductoController {

    private $productoModel;
    private $categoriaModel;

    public function __construct() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->productoModel = new Producto();
        $this->categoriaModel = new Categoria();
    }

    private function getIdioma() {
        return $_SESSION['idioma'] ?? 'es';
    }

    private function verificarAdmin(){

        if(!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin'){
            header('Location:index.php');
            exit;
        }
    }

    /* =============================
       LISTA PUBLICA
    ============================= */
    public function index(){

        $idioma = $this->getIdioma();
        $productos = $this->productoModel->getAll($idioma);
        $categorias = $this->categoriaModel->getAll();

        require __DIR__.'/../views/productos.php';
    }

    /* =============================
       DETALLE
    ============================= */
    public function show($id){

        $idioma = $this->getIdioma();
        $producto = $this->productoModel->getById($id,$idioma);

        require __DIR__.'/../views/producto_detalle.php';
    }

    /* =============================
       PANEL ADMIN
    ============================= */
    public function admin(){

        $this->verificarAdmin();

        $productos = $this->productoModel->getAll();

        require __DIR__.'/../views/admin/productos/lista.php';
    }

    /* =============================
       FORM CREAR
    ============================= */
    public function crear(){

        $this->verificarAdmin();

        $categorias = $this->categoriaModel->getAll();

        require __DIR__.'/../views/admin/productos/crear.php';
    }

    /* =============================
       GUARDAR PRODUCTO
    ============================= */
    public function guardar(){

        $this->verificarAdmin();

        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $categoria = $_POST['categoria'];
        $imagen = $_POST['imagen'];

        $this->productoModel->crear($nombre,$descripcion,$precio,$categoria,$imagen);

        header('Location:index.php?controller=producto&action=admin');
    }

    /* =============================
       ELIMINAR
    ============================= */
    public function eliminar(){

        $this->verificarAdmin();

        $id = $_GET['id'];

        $this->productoModel->eliminar($id);

        header('Location:index.php?controller=producto&action=admin');
    }

}