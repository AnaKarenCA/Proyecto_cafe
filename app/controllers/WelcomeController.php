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

    public function index() {
        // Productos destacados (manual) - Podemos cambiar a mas vendidos si hay datos
        $productos_destacados = $this->productoModel->getDestacados(6);
        
        // Los más vendidos:
        // $productos_destacados = $this->productoModel->getMasVendidos(6);
        
        $categorias = $this->categoriaModel->getAll();
        
        // Recomendaciones por clima (ejemplo: usamos 'Caluroso' como predeterminado)
        // A futuro, btener el clima de una API o por estación
        $recomendaciones_clima = $this->productoModel->getPorClima('Caluroso');
        
        // Para el carrusel, podemos usar los mismos productos destacados o crear un array fijo
        $carrusel_items = $this->productoModel->getDestacados(6); // 3 imágenes para el carrusel
        
        $titulo = "Bienvenido a Omnis Café";
        require_once __DIR__ . '/../views/welcome.php';
    }
}