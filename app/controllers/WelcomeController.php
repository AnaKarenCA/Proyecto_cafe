<?php
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Categoria.php';

class WelcomeController {
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

        $productos_destacados = $this->productoModel->getDestacados(6, $idioma);
        $categorias = $this->categoriaModel->getAll();
        $recomendaciones_clima = $this->productoModel->getPorClima('Caluroso', $idioma);
        $carrusel_items = $this->productoModel->getDestacados(6, $idioma);

        $titulo = "Bienvenido a Omnis Café";

        require_once __DIR__ . '/../views/welcome.php';
    }
}