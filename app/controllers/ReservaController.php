<?php
require_once __DIR__ . '/../models/Reserva.php';

class ReservaController {
    private $reservaModel;

    public function __construct() {
        $this->reservaModel = new Reserva();
    }

    // Muestra el formulario de reserva (público)
    public function index() {
        $titulo = "Reservar mesa";
        require_once __DIR__ . '/../views/reserva.php';
    }

    // Procesa la reserva (público, pero puede asociarse a usuario si está logueado)
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=reserva&action=index');
            exit;
        }

        $datos = [
            'nombre_cliente' => $_POST['nombre'] ?? '',
            'email_cliente' => $_POST['email'] ?? '',
            'telefono_cliente' => $_POST['telefono'] ?? '',
            'fecha_reserva' => $_POST['fecha'] ?? '',
            'hora_reserva' => $_POST['hora'] ?? '',
            'num_personas' => $_POST['personas'] ?? 1,
            'comentarios' => $_POST['comentarios'] ?? ''
        ];

        // Validaciones
        $errores = [];
        if (empty($datos['nombre_cliente'])) $errores[] = "El nombre es obligatorio.";
        if (!filter_var($datos['email_cliente'], FILTER_VALIDATE_EMAIL)) $errores[] = "Correo inválido.";
        if (empty($datos['fecha_reserva']) || empty($datos['hora_reserva'])) $errores[] = "Fecha y hora son obligatorias.";
        if ($datos['num_personas'] < 1) $errores[] = "Número de personas inválido.";

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['old'] = $datos;
            header('Location: index.php?controller=reserva&action=index');
            exit;
        }

        // Si el usuario está logueado, asociar reserva a su cuenta
        if (isset($_SESSION['usuario_id'])) {
            $datos['id_usuario'] = $_SESSION['usuario_id'];
        } else {
            $datos['id_usuario'] = null;
        }

        if ($this->reservaModel->crear($datos)) {
            $_SESSION['success'] = "Reserva solicitada con éxito. Te contactaremos pronto.";
        } else {
            $_SESSION['error'] = "Error al procesar la reserva. Intenta nuevamente.";
        }
        header('Location: index.php?controller=reserva&action=index');
    }

    // Lista las reservas del usuario autenticado (requiere login)
    public function misReservas() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?controller=auth&action=showLoginForm');
            exit;
        }

        $reservas = $this->reservaModel->getByUsuario($_SESSION['usuario_id']);
        $titulo = "Mis reservas";
        require_once __DIR__ . '/../views/mis_reservas.php'; // Vista no listada, se puede crear
    }
}