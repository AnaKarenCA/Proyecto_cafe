<?php
session_start();
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $usuario = Usuario::login($email, $password);
            if ($usuario) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                header('Location: /');
                exit;
            } else {
                $error = "Credenciales incorrectas";
                include __DIR__ . '/../views/auth/login.php';
            }
        } else {
            include __DIR__ . '/../views/auth/login.php';
        }
    }

    public function registro() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            if (Usuario::registrar($nombre, $email, $password)) {
                header('Location: /login');
                exit;
            } else {
                $error = "Error al registrar (el email puede estar duplicado)";
                include __DIR__ . '/../views/auth/registro.php';
            }
        } else {
            include __DIR__ . '/../views/auth/registro.php';
        }
    }

    public function logout() {
        session_destroy();
        header('Location: /login');
        exit;
    }
}