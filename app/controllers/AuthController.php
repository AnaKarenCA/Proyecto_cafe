<?php

require_once __DIR__ . '/../models/Usuario.php';

class AuthController {

    private $usuarioModel;

    public function __construct() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->usuarioModel = new Usuario();
    }

    /* ================================
       Mostrar login
    ================================= */
    public function showLoginForm() {
        require_once __DIR__ . '/../views/auth/login.php';
    }

    /* ================================
       Procesar login
    ================================= */
public function login() {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?controller=auth&action=showLoginForm');
        exit;
    }

    $cuenta = trim($_POST['cuenta'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    $usuario = $this->usuarioModel->getByCuenta($cuenta);

    if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {

        session_regenerate_id(true);

        // Suponiendo que ya tienes $usuario con los datos
$_SESSION['usuario_id'] = $usuario['id_usuario'];
$_SESSION['usuario_nombre'] = $usuario['nombre'];
$_SESSION['rol'] = $usuario['rol'];

// Cargar configuración del usuario (idioma, tema)
$configModel = new UsuarioConfiguracion();
$config = $configModel->getById($usuario['id_usuario']);
$_SESSION['idioma'] = $config['idioma'] ?? 'es';
$_SESSION['tema'] = $config['tema'] ?? 'claro';
        header('Location: index.php?controller=welcome&action=index');
        exit;

    } else {

        $_SESSION['error'] = "Credenciales incorrectas.";
        header('Location: index.php?controller=auth&action=showLoginForm');
        exit;
    }
}

    /* ================================
       Mostrar registro
    ================================= */
    public function showRegisterForm() {
        require_once __DIR__ . '/../views/auth/registro.php';
    }

    /* ================================
       Procesar registro
    ================================= */
    public function register() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=auth&action=showRegisterForm');
            exit;
        }

        $errores = [];

        $datos = [
            'cuenta' => trim($_POST['cuenta'] ?? ''),
            'nombre' => trim($_POST['nombre'] ?? ''),
            'apellido_paterno' => trim($_POST['apellido_paterno'] ?? ''),
            'apellido_materno' => trim($_POST['apellido_materno'] ?? ''),
            'correo' => trim($_POST['correo'] ?? ''),
            'contrasena' => $_POST['contrasena'] ?? '',
            'telefono' => trim($_POST['telefono'] ?? '')
        ];

        if (strlen($datos['contrasena']) < 6) {
            $errores[] = "La contraseña debe tener mínimo 6 caracteres.";
        }

        if ($this->usuarioModel->existeCuenta($datos['cuenta'])) {
            $errores[] = "La cuenta ya existe.";
        }

        if ($this->usuarioModel->existeCorreo($datos['correo'])) {
            $errores[] = "El correo ya existe.";
        }

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['old'] = $datos;
            header('Location: index.php?controller=auth&action=showRegisterForm');
            exit;
        }

        $datos['contrasena'] = password_hash($datos['contrasena'], PASSWORD_DEFAULT);

        $id_usuario = $this->usuarioModel->crear($datos);

        if ($id_usuario) {

            $this->usuarioModel->setIdioma($id_usuario, 'es');

            $_SESSION['success'] = "Cuenta creada correctamente.";
            header('Location: index.php?controller=auth&action=showLoginForm');
            exit;

        } else {

            $_SESSION['error'] = "Error al registrar.";
            header('Location: index.php?controller=auth&action=showRegisterForm');
            exit;
        }
    }

    /* ================================
       Cambiar idioma
    ================================= */
    public function cambiarIdioma() {

        $lang = $_GET['lang'] ?? 'es';
        $permitidos = ['es', 'en', 'de'];
        if (!in_array($lang, $permitidos)) {
            $lang = 'es';
        }

        $_SESSION['idioma'] = $lang;

        if (isset($_SESSION['usuario_id'])) {
            $this->usuarioModel->setIdioma($_SESSION['usuario_id'], $lang);
        }

        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
        exit;
    }

    /* ================================
       Logout seguro
    ================================= */
    public function logout() {

        $_SESSION = [];
        session_unset();
        session_destroy();

        header('Location: index.php?controller=welcome&action=index');
        exit;
    }
}