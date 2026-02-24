<nav class="main-nav" aria-label="Menú principal">
    <ul class="nav-list">
        <li><a href="index.php?controller=welcome&action=index">Inicio</a></li>
        <li><a href="index.php?controller=categoria&action=index">Categorías</a></li>
        <li><a href="index.php?controller=producto&action=index">Productos</a></li>
        <li><a href="index.php?controller=reserva&action=index">Reservar mesa</a></li>
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <li><a href="index.php?controller=carrito&action=ver">Carrito (<?= Carrito::contarItems() ?>)</a></li>
            <li><a href="index.php?controller=auth&action=logout">Cerrar sesión (<?= htmlspecialchars($_SESSION['usuario_nombre']) ?>)</a></li>
        <?php else: ?>
            <li><a href="index.php?controller=auth&action=showLoginForm">Iniciar sesión</a></li>
            <li><a href="index.php?controller=auth&action=showRegisterForm">Registrarse</a></li>
        <?php endif; ?>
    </ul>
</nav>