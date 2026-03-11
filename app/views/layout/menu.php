<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$rol = $_SESSION['rol'] ?? null;
?>

<nav class="main-nav" aria-label="Menú principal">
    <ul class="nav-list">

        <li>
            <a href="index.php?controller=welcome&action=index">Inicio</a>
        </li>

        <li>
            <a href="index.php?controller=categoria&action=index">Categorías</a>
        </li>

        <li>
            <a href="index.php?controller=producto&action=index">Productos</a>
        </li>

        <li>
            <a href="index.php?controller=reserva&action=index">Reservar mesa</a>
        </li>


        <?php if (isset($_SESSION['usuario_id'])): ?>

            <!-- =============================
                 MENÚ ADMIN
            ============================== -->
            <?php if ($rol === 'admin'): ?>

                <li>
                    <a href="index.php?controller=adminProducto&action=index">
                        Administrar Productos
                    </a>
                </li>

                <li>
                    <a href="index.php?controller=adminCategoria&action=index">
                        Administrar Categorías
                    </a>
                </li>

                <li>
                    <a href="index.php?controller=adminPedido&action=index">
                        Ver Pedidos
                    </a>
                </li>

                <li>
                    <a href="index.php?controller=adminReserva&action=index">
                        Ver Reservas
                    </a>
                </li>

            <?php endif; ?>


            <!-- =============================
                 MENÚ CLIENTE
            ============================== -->
            <?php if ($rol === 'cliente'): ?>

                <li>
                    <a href="index.php?controller=carrito&action=ver">
                        Carrito (<?= Carrito::contarItems() ?>)
                    </a>
                </li>

                <li>
                    <a href="index.php?controller=usuario&action=perfil">
                        Mi perfil
                    </a>
                </li>

                <li>
                    <a href="index.php?controller=usuario&action=pedidos">
                        Mis pedidos
                    </a>
                </li>

                <li>
                    <a href="index.php?controller=usuario&action=reservas">
                        Mis reservas
                    </a>
                </li>

            <?php endif; ?>


            <!-- =============================
                 LOGOUT
            ============================== -->
            <li>
                <a href="index.php?controller=auth&action=logout">
                    Cerrar sesión (<?= htmlspecialchars($_SESSION['usuario_nombre']) ?>)
                </a>
            </li>

        <?php else: ?>

            <li>
                <a href="index.php?controller=auth&action=showLoginForm">
                    Iniciar sesión
                </a>
            </li>

            <li>
                <a href="index.php?controller=auth&action=showRegisterForm">
                    Registrarse
                </a>
            </li>

        <?php endif; ?>

    </ul>
</nav>