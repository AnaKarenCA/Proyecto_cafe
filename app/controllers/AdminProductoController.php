<?php

require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Categoria.php';

class AdminProductoController
{
    private $uploadDir;

    public function __construct()
    {
        // Ruta absoluta a la carpeta de imágenes
        $this->uploadDir = __DIR__ . '/../../public/img/productos/';
        // Crear la carpeta si no existe
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    public function index()
    {
        $productoModel = new Producto();
        $productos = $productoModel->getAll();
        require __DIR__ . '/../views/admin/productos/lista.php';
    }

    public function crear()
    {
        $categoriaModel = new Categoria();
        $categorias = $categoriaModel->getAll();
        require __DIR__ . '/../views/admin/productos/crear.php';
    }

    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controller=adminProducto&action=index");
            exit;
        }

        // Validar campos
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $precio = filter_var($_POST['precio'] ?? 0, FILTER_VALIDATE_FLOAT);
        $categoria = filter_var($_POST['categoria'] ?? 0, FILTER_VALIDATE_INT);

        if (empty($nombre) || $precio === false || $precio <= 0 || !$categoria) {
            header("Location: index.php?controller=adminProducto&action=crear&error=1");
            exit;
        }

        // Procesar imagen
        $imagenNombre = $this->uploadImage($_FILES['imagen'] ?? null);

        $productoModel = new Producto();
        $productoModel->crear(
            $nombre,
            $descripcion,
            $precio,
            $categoria,
            $imagenNombre
        );

        header("Location: index.php?controller=adminProducto&action=index");
        exit;
    }

    public function editar()
    {
        $id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);
        if (!$id) {
            header("Location: index.php?controller=adminProducto&action=index");
            exit;
        }

        $productoModel = new Producto();
        $categoriaModel = new Categoria();

        $producto = $productoModel->getById($id);
        if (!$producto) {
            header("Location: index.php?controller=adminProducto&action=index");
            exit;
        }

        $categorias = $categoriaModel->getAll();

        // Variables para la vista
        $imagenWebPath = '/img/productos/';
        $imagenExistente = !empty($producto['imagen']) && file_exists($this->uploadDir . $producto['imagen']);

        require __DIR__ . '/../views/admin/productos/editar.php';
    }

    public function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controller=adminProducto&action=index");
            exit;
        }

        $id = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT);
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $precio = filter_var($_POST['precio'] ?? 0, FILTER_VALIDATE_FLOAT);
        $categoria = filter_var($_POST['categoria'] ?? 0, FILTER_VALIDATE_INT);

        if (!$id || empty($nombre) || $precio === false || $precio <= 0 || !$categoria) {
            header("Location: index.php?controller=adminProducto&action=editar&id=$id&error=1");
            exit;
        }

        $productoModel = new Producto();
        $productoActual = $productoModel->getById($id);
        if (!$productoActual) {
            header("Location: index.php?controller=adminProducto&action=index");
            exit;
        }

        // Procesar nueva imagen (si se subió)
        $imagenNombre = $this->uploadImage($_FILES['imagen'] ?? null, $productoActual['imagen']);

        $productoModel->actualizar(
            $id,
            $nombre,
            $descripcion,
            $precio,
            $categoria,
            $imagenNombre
        );

        header("Location: index.php?controller=adminProducto&action=index");
        exit;
    }

    public function eliminar()
    {
        $id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);
        if (!$id) {
            header("Location: index.php?controller=adminProducto&action=index");
            exit;
        }

        $productoModel = new Producto();
        $producto = $productoModel->getById($id);
        if ($producto && !empty($producto['imagen'])) {
            $rutaImagen = $this->uploadDir . $producto['imagen'];
            if (file_exists($rutaImagen)) {
                unlink($rutaImagen);
            }
        }

        $productoModel->eliminar($id);
        header("Location: index.php?controller=adminProducto&action=index");
        exit;
    }

    /**
     * Sube una imagen al servidor con validación y saneamiento.
     *
     * @param array|null $file Archivo de $_FILES['imagen']
     * @param string|null $oldImage Nombre de imagen anterior (para eliminar)
     * @return string|null Nombre del archivo subido o el anterior si no se subió
     */
    private function uploadImage($file, $oldImage = null)
    {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            return $oldImage; // No se subió archivo, conservar el anterior
        }

        // Validar tipo MIME real
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            // Tipo no permitido: conservar imagen anterior
            return $oldImage;
        }

        // Validar tamaño máximo (2MB)
        if ($file['size'] > 2 * 1024 * 1024) {
            return $oldImage;
        }

        // Generar nombre seguro
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $nombreBase = pathinfo($file['name'], PATHINFO_FILENAME);
        $nombreBase = $this->sanitizeFileName($nombreBase);
        $nuevoNombre = uniqid() . '_' . $nombreBase . '.' . $extension;

        $rutaDestino = $this->uploadDir . $nuevoNombre;

        if (move_uploaded_file($file['tmp_name'], $rutaDestino)) {
            // Eliminar la imagen anterior si existe y es diferente
            if ($oldImage && $oldImage !== $nuevoNombre) {
                $rutaVieja = $this->uploadDir . $oldImage;
                if (file_exists($rutaVieja)) {
                    unlink($rutaVieja);
                }
            }
            return $nuevoNombre;
        }

        return $oldImage; // Falló la subida
    }

    /**
     * Limpia un nombre de archivo: elimina acentos y caracteres especiales.
     */
    private function sanitizeFileName($filename)
    {
        $unwanted = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
            'ñ' => 'n', 'Ñ' => 'N',
        ];
        $filename = strtr($filename, $unwanted);
        $filename = preg_replace('/[^a-zA-Z0-9_-]/', '', $filename);
        return substr($filename, 0, 50);
    }
}