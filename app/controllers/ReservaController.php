<?php
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Usuario.php';

// Configurar zona horaria del restaurante (México)
date_default_timezone_set('America/Mexico_City');

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
        $hoy = date('Y-m-d');

        // Recuperar datos pendientes (si vienen de login)
        $pending = $_SESSION['pending_reservation'] ?? null;
        $nombre = $pending['nombre'] ?? '';
        $telefono = $pending['telefono'] ?? '';
        $email = $pending['email'] ?? '';
        $fecha = $pending['fecha'] ?? $fecha;
        $personas = $pending['personas'] ?? 1;

        // Si el usuario está logueado, usar sus datos y limpiar pendientes
        if (isset($_SESSION['usuario_id'])) {
            $usuarioModel = new Usuario();
            $usuario = $usuarioModel->getById($_SESSION['usuario_id']);
            if ($usuario) {
                $nombre = $usuario['nombre'] ?? '';
                $telefono = $usuario['telefono'] ?? '';
                $email = $usuario['correo'] ?? '';
            }
            unset($_SESSION['pending_reservation']);
        }

        $disponibilidad = $this->reservaModel->disponibilidadPorFecha($fecha);
        $horasOcupadas = [];
        foreach ($disponibilidad as $d) {
            $horaFormateada = date('H:i', strtotime($d['hora']));
            $horasOcupadas[$horaFormateada] = $d['total'];
        }

        require_once __DIR__ . '/../views/reserva.php';
    }

    public function store() {
        $input = json_decode(file_get_contents('php://input'), true);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$input) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Solicitud inválida']);
            exit;
        }

        // Verificar autenticación
        if (!isset($_SESSION['usuario_id'])) {
            // Guardar datos en sesión para después del login
            $_SESSION['pending_reservation'] = $input;
            echo json_encode(['success' => false, 'redirect' => 'index.php?controller=auth&action=showLoginForm']);
            exit;
        }

        $datos = [
            'id_usuario' => $_SESSION['usuario_id'],
            'nombre' => trim($input['nombre'] ?? ''),
            'correo' => trim($input['email'] ?? ''),
            'telefono' => trim($input['telefono'] ?? ''),
            'fecha' => $input['fecha'] ?? '',
            'hora' => $input['hora'] ?? '',
            'personas' => (int)($input['personas'] ?? 1)
        ];

        // Validaciones
        if (empty($datos['nombre'])) {
            echo json_encode(['success' => false, 'message' => 'El nombre es obligatorio.']);
            exit;
        }
        if ($datos['personas'] < 1 || $datos['personas'] > 6) {
            echo json_encode(['success' => false, 'message' => 'Máximo 6 personas por mesa.']);
            exit;
        }
        if (empty($datos['fecha']) || empty($datos['hora'])) {
            echo json_encode(['success' => false, 'message' => 'Debe seleccionar fecha y hora.']);
            exit;
        }

        $resultado = $this->reservaModel->crear($datos);

        if ($resultado) {
            echo json_encode([
                'success' => true,
                'data' => [
                    'nombre' => $datos['nombre'],
                    'fecha' => $datos['fecha'],
                    'hora' => $datos['hora'],
                    'personas' => $datos['personas']
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No hay mesas disponibles para esa fecha y hora.']);
        }
    }

    public function availability() {
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $hoy = date('Y-m-d');
        $horaActual = (int)date('H');
        $horaInicio = 9;
        $horaFin = 17;
        $maxMesas = 10;

        $disponibilidad = $this->reservaModel->disponibilidadPorFecha($fecha);
        $horasOcupadas = [];
        foreach ($disponibilidad as $d) {
            $horasOcupadas[date('H:i', strtotime($d['hora']))] = $d['total'];
        }

        $horas = [];
        for ($h = $horaInicio; $h < $horaFin; $h++) {
            $hora = str_pad($h, 2, "0", STR_PAD_LEFT) . ":00";

            // Si la fecha es hoy y la hora ya pasó, omitir
            if ($fecha === $hoy && $h <= $horaActual) {
                continue;
            }

            $ocupadas = $horasOcupadas[$hora] ?? 0;
            $disponibles = $maxMesas - $ocupadas;

            if ($disponibles > 0) {
                $status = ($disponibles <= 3) ? 'limited' : 'available';
                $horas[] = [
                    'time' => $hora,
                    'available' => $disponibles,
                    'status' => $status
                ];
            } else {
                $horas[] = [
                    'time' => $hora,
                    'available' => 0,
                    'status' => 'full'
                ];
            }
        }

        header('Content-Type: application/json');
        echo json_encode(['hours' => $horas]);
    }
}