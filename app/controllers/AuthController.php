<?php
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    // Muestra el formulario de login
    public function showLoginForm() {
        $titulo = "Iniciar sesión";
        require_once __DIR__ . '/../views/auth/login.php';
    }

    // Procesa el login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=auth&action=showLoginForm');
            exit;
        }

        $cuenta = $_POST['cuenta'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';

        if (empty($cuenta) || empty($contrasena)) {
            $_SESSION['error'] = "Todos los campos son obligatorios.";
            header('Location: index.php?controller=auth&action=showLoginForm');
            exit;
        }

        $usuario = $this->usuarioModel->getByCuenta($cuenta);

        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['success'] = "Bienvenido, " . $usuario['nombre'];
            header('Location: index.php?controller=welcome&action=index');
            exit;
        } else {
            $_SESSION['error'] = "Credenciales incorrectas.";
            header('Location: index.php?controller=auth&action=showLoginForm');
            exit;
        }
    }

    // Muestra el formulario de registro
    public function showRegisterForm() {
        $titulo = "Registro de usuario";
        require_once __DIR__ . '/../views/auth/registro.php';
    }

    // Procesa el registro
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=auth&action=showRegisterForm');
            exit;
        }

        $datos = [
            'cuenta' => $_POST['cuenta'] ?? '',
            'nombre' => $_POST['nombre'] ?? '',
            'apellido_paterno' => $_POST['apellido_paterno'] ?? '',
            'apellido_materno' => $_POST['apellido_materno'] ?? '',
            'correo' => $_POST['correo'] ?? '',
            'contrasena' => $_POST['contrasena'] ?? '',
            'telefono' => $_POST['telefono'] ?? ''
        ];

        // Validaciones básicas
        $errores = [];
        if (empty($datos['cuenta'])) $errores[] = "La cuenta es obligatoria.";
        if (empty($datos['nombre'])) $errores[] = "El nombre es obligatorio.";
        if (!filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) $errores[] = "Correo inválido.";
        if (strlen($datos['contrasena']) < 6) $errores[] = "La contraseña debe tener al menos 6 caracteres.";

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['old'] = $datos;
            header('Location: index.php?controller=auth&action=showRegisterForm');
            exit;
        }

        // Verificar si ya existe cuenta o correo
        if ($this->usuarioModel->existeCuenta($datos['cuenta'])) {
            $_SESSION['error'] = "La cuenta ya está registrada.";
            header('Location: index.php?controller=auth&action=showRegisterForm');
            exit;
        }
        if ($this->usuarioModel->existeCorreo($datos['correo'])) {
            $_SESSION['error'] = "El correo ya está registrado.";
            header('Location: index.php?controller=auth&action=showRegisterForm');
            exit;
        }

        // Hashear contraseña
        $datos['contrasena'] = password_hash($datos['contrasena'], PASSWORD_DEFAULT);

        if ($this->usuarioModel->crear($datos)) {
            $_SESSION['success'] = "Registro exitoso. Ahora puedes iniciar sesión.";
            header('Location: index.php?controller=auth&action=showLoginForm');
        } else {
            $_SESSION['error'] = "Error al registrar. Intenta nuevamente.";
            header('Location: index.php?controller=auth&action=showRegisterForm');
        }
    }

    // Cierra sesión
    public function logout() {
        session_destroy();
        header('Location: index.php?controller=welcome&action=index');
        exit;
    }
}