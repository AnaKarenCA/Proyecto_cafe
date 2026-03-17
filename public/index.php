<?php
session_start();

require_once __DIR__ . '/../app/helpers/i18n.php';

// Definir constantes de ruta
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Autocarga simple de clases (controladores y modelos)
spl_autoload_register(function ($class) {
    $file = APP_PATH . '/controllers/' . $class . '.php';
    if (file_exists($file)) { require_once $file; return true; }
    $file = APP_PATH . '/models/' . $class . '.php';
    if (file_exists($file)) { require_once $file; return true; }
    return false;
});

// --- MODO 1: Parámetros explícitos (index.php?controller=...&action=...) ---
if (isset($_GET['controller']) && isset($_GET['action'])) {
    $controllerName = ucfirst(strtolower($_GET['controller'])) . 'Controller';
    $actionName = $_GET['action'];
    $id = $_GET['id'] ?? null;

    if (!class_exists($controllerName)) {
        die("Controlador no encontrado");
    }
    $controller = new $controllerName();
    if (!method_exists($controller, $actionName)) {
        die("Acción no encontrada");
    }
    if ($id !== null) {
        $controller->$actionName($id);
    } else {
        $controller->$actionName();
    }
    exit;
}
if (isset($_GET['categoria'])) {
    $controller = new CategoriaController();
    $controller->show($_GET['categoria']);
    exit;
}
// --- MODO 2: Rutas amigables (path) ---
$base = '/Proyecto_cafe/public';
$request = $_SERVER['REQUEST_URI'];
$request = str_replace($base, '', $request);
$request = strtok($request, '?');
if ($request == '') {
    $request = '/';
}

switch ($request) {
    case '/':
    case '/welcome':
        $controller = new WelcomeController();
        $controller->index();
        break;

    case '/categorias':
        $controller = new CategoriaController();
        $controller->index();
        break;

    case (preg_match('/^\/categoria\/(\d+)$/', $request, $matches) ? true : false):
        $controller = new CategoriaController();
        $controller->show($matches[1]);
        break;

    case '/productos':
        $controller = new ProductoController();
        $controller->index();
        break;

    case (preg_match('/^\/producto\/(\d+)$/', $request, $matches) ? true : false):
        $controller = new ProductoController();
        $controller->show($matches[1]);
        break;

    case '/reserva':
        $controller = new ReservaController();
        $controller->index();
        break;

    case '/reserva/guardar':
        $controller = new ReservaController();
        $controller->store();
        break;

    case '/login':
        $controller = new AuthController();
        $controller->showLoginForm();
        break;

    case '/registro':
        $controller = new AuthController();
        $controller->showRegisterForm();
        break;

    case '/logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    case '/carrito':
        $controller = new CarritoController();
        $controller->ver();
        break;

    case '/carrito/agregar':
        $controller = new CarritoController();
        $controller->agregar();
        break;

    case '/carrito/actualizar':
        $controller = new CarritoController();
        $controller->actualizar();
        break;

    case '/carrito/eliminar':
        $controller = new CarritoController();
        $controller->quitar();
        break;
    case '/carrito/totalItems':
    $controller = new CarritoController();
    $controller->totalItems();
    break;
case '/carrito/actualizarCantidad':
    $controller = new CarritoController();
    $controller->actualizarCantidad();
    break;

    default:
        http_response_code(404);
        echo "404 - Página no encontrada";
        break;
}