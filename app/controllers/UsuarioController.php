<?php
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../models/Reserva.php';

class UsuarioController {
    

    private $pedidoModel;
    private $reservaModel;

    public function __construct() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?controller=auth&action=showLoginForm');
            exit;
        }

        $this->pedidoModel = new Pedido();
        $this->reservaModel = new Reserva();
    }

    /* ==============================
       MIS PEDIDOS
    ============================== */
    public function pedidos() {

        $id_usuario = $_SESSION['usuario_id'];

        $pedidos = $this->pedidoModel->obtenerPorUsuario($id_usuario);

        require_once __DIR__ . '/../views/usuario/pedidos.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    /* ==============================
       MIS RESERVAS
    ============================== */
    public function reservas() {

        $id_usuario = $_SESSION['usuario_id'];

        $reservas = $this->reservaModel->obtenerPorUsuario($id_usuario);

        require_once __DIR__ . '/../views/usuario/reservas.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }
    public function perfil() {

    $id_usuario = $_SESSION['usuario_id'];

    // Obtener datos del usuario desde la BD
    require_once __DIR__ . '/../models/Usuario.php';
    $usuarioModel = new Usuario();
    $usuario = $usuarioModel->getById($id_usuario);

    // Obtener últimos pedidos
    $ultimosPedidos = $this->pedidoModel->obtenerUltimosPedidos($id_usuario);

    require_once __DIR__ . '/../views/usuario/perfil.php';
}
}