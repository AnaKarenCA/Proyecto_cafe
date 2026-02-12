<?php
session_start();

$request = $_SERVER['REQUEST_URI'];
$request = strtok($request, '?');

switch ($request) {
    case '/':
    case '/welcome':
        require_once __DIR__ . '/../app/controllers/ProductoController.php';
        $controller = new ProductoController();
        $controller->destacados();
        break;

    case '/categorias':
        require_once __DIR__ . '/../app/controllers/CategoriaController.php';
        $controller = new CategoriaController();
        $controller->index();
        break;

    case '/productos':
        require_once __DIR__ . '/../app/controllers/ProductoController.php';
        $controller = new ProductoController();
        $controller->porCategoria();
        break;

    case '/reserva':
        require_once __DIR__ . '/../app/controllers/ReservaController.php';
        $controller = new ReservaController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->guardar();
        } else {
            $controller->formulario();
        }
        break;

    case '/login':
        require_once __DIR__ . '/../app/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->login();
        break;

    case '/registro':
        require_once __DIR__ . '/../app/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->registro();
        break;

    case '/logout':
        require_once __DIR__ . '/../app/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;

    case '/carrito/agregar':
        require_once __DIR__ . '/../app/controllers/CarritoController.php';
        $controller = new CarritoController();
        $controller->agregar();
        break;

    case '/carrito/obtener':
        require_once __DIR__ . '/../app/controllers/CarritoController.php';
        $controller = new CarritoController();
        $controller->obtener();
        break;

    case '/carrito/actualizar':
        require_once __DIR__ . '/../app/controllers/CarritoController.php';
        $controller = new CarritoController();
        $controller->actualizar();
        break;

    case '/carrito/eliminar':
        require_once __DIR__ . '/../app/controllers/CarritoController.php';
        $controller = new CarritoController();
        $controller->eliminar();
        break;

    default:
        http_response_code(404);
        echo "404 - Página no encontrada";
        break;
}