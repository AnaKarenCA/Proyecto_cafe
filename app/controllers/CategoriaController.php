<?php
require_once __DIR__ . '/../models/Categoria.php';

class CategoriaController {
    public function index() {
        $categorias = Categoria::getAll();
        include __DIR__ . '/../views/categorias.php';
    }
}