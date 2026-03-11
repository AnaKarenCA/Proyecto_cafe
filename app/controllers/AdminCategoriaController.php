<?php

require_once __DIR__ . '/../models/Categoria.php';

class AdminCategoriaController
{
    public function index()
    {
        $categoriaModel = new Categoria();
        $categorias = $categoriaModel->getAll();

        require __DIR__ . '/../views/admin/categorias/lista.php';
    }

    public function crear()
    {
        require __DIR__ . '/../views/admin/categorias/crear.php';
    }

    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controller=adminCategoria&action=index");
            exit;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $icono = trim($_POST['icono'] ?? '');
        $orden = (int)($_POST['orden'] ?? 0);

        if (empty($nombre)) {
            header("Location: index.php?controller=adminCategoria&action=crear&error=1");
            exit;
        }

        $categoriaModel = new Categoria();
        $categoriaModel->crear($nombre, $descripcion, $icono, $orden);

        header("Location: index.php?controller=adminCategoria&action=index");
        exit;
    }

    public function editar()
    {
        $id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);
        if (!$id) {
            header("Location: index.php?controller=adminCategoria&action=index");
            exit;
        }

        $categoriaModel = new Categoria();
        $categoria = $categoriaModel->getById($id);
        if (!$categoria) {
            header("Location: index.php?controller=adminCategoria&action=index");
            exit;
        }

        require __DIR__ . '/../views/admin/categorias/editar.php';
    }

    public function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controller=adminCategoria&action=index");
            exit;
        }

        $id = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT);
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $icono = trim($_POST['icono'] ?? '');
        $orden = (int)($_POST['orden'] ?? 0);

        if (!$id || empty($nombre)) {
            header("Location: index.php?controller=adminCategoria&action=editar&id=$id&error=1");
            exit;
        }

        $categoriaModel = new Categoria();
        $categoriaModel->actualizar($id, $nombre, $descripcion, $icono, $orden);

        header("Location: index.php?controller=adminCategoria&action=index");
        exit;
    }

    public function eliminar()
    {
        $id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);
        if (!$id) {
            header("Location: index.php?controller=adminCategoria&action=index");
            exit;
        }

        $categoriaModel = new Categoria();
        $categoriaModel->eliminar($id);

        header("Location: index.php?controller=adminCategoria&action=index");
        exit;
    }
}