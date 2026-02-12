<?php
session_start();
require_once __DIR__ . '/../models/Reserva.php';

class ReservaController {
    public function formulario() {
        include __DIR__ . '/../views/reserva.php';
    }

    public function guardar() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario_id = $_SESSION['usuario_id'];
            $fecha = $_POST['fecha'];
            $hora = $_POST['hora'];
            $personas = $_POST['personas'];
            $tipo_mesa = $_POST['tipo_mesa'];
            $detalles = $_POST['detalles'] ?? '';
            Reserva::crear($usuario_id, $fecha, $hora, $personas, $tipo_mesa, $detalles);
            header('Location: /reserva?exito=1');
            exit;
        }
    }
}