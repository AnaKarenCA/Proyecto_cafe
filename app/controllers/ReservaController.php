<?php
require_once __DIR__ . '/../models/Reserva.php';

class ReservaController {

    private $reservaModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->reservaModel = new Reserva();
    }

    public function index() {

        $fecha = $_GET['fecha'] ?? date('Y-m-d');

        $disponibilidad = $this->reservaModel->disponibilidadPorFecha($fecha);

        $horasOcupadas = [];

        foreach ($disponibilidad as $d) {

    // Convertir 10:00:00 → 10:00
    $horaFormateada = date('H:i', strtotime($d['hora']));

    $horasOcupadas[$horaFormateada] = $d['total'];
}

        require_once __DIR__ . '/../views/reserva.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    public function store() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=reserva&action=index');
            exit;
        }

        $datos = [
            'id_usuario' => $_SESSION['usuario_id'] ?? null,
            'nombre' => trim($_POST['nombre'] ?? ''),
            'correo' => trim($_POST['email'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'fecha' => $_POST['fecha'] ?? '',
            'hora' => $_POST['hora'] ?? '',
            'personas' => (int)($_POST['personas'] ?? 1)
        ];

        $errores = [];

        if (empty($datos['nombre'])) {
            $errores[] = "El nombre es obligatorio.";
        }

        if (!filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = "Correo electrónico inválido.";
        }

        if ($datos['personas'] < 1 || $datos['personas'] > 6) {
            $errores[] = "Cada mesa admite máximo 6 personas.";
        }

        if (empty($datos['fecha']) || empty($datos['hora'])) {
            $errores[] = "Debe seleccionar fecha y hora.";
        }

        if (!empty($errores)) {
            $_SESSION['reserva_error'] = implode("<br>", $errores);
            header('Location: index.php?controller=reserva&action=index');
            exit;
        }

        $reserva = $this->reservaModel->crear($datos);

        if ($reserva) {

            $_SESSION['reserva_success'] = [
                'nombre' => $datos['nombre'],
                'fecha' => $datos['fecha'],
                'hora' => $datos['hora'],
                'personas' => $datos['personas']
            ];

        } else {
            $_SESSION['reserva_error'] = "No hay mesas disponibles para esa fecha y hora.";
        }

        header('Location: index.php?controller=reserva&action=index');
        exit;
    }
}