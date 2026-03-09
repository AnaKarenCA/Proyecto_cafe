<!DOCTYPE html>
<html lang="es">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titulo) ? htmlspecialchars($titulo) . ' | Omnis Café' : 'Omnis Café' ?></title>
    <!-- Material Symbols -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1" />
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Botón de accesibilidad flotante -->
    <div class="accessibility-toolbar">
        <button id="accessibilityBtn" class="accessibility-btn" aria-label="Opciones de accesibilidad" aria-expanded="false">
            Accesibilidad
        </button>
        <div id="accessibilityPanel" class="accessibility-panel" hidden>
            <button id="fontIncrease" class="accessibility-option">Aumentar texto</button>
            <button id="fontDecrease" class="accessibility-option">Disminuir texto</button>
            <button id="screenReader" class="accessibility-option">Leer pantalla</button>
        </div>
    </div>

    <header class="main-header">
        <div class="container">
            <div class="logo">
                <a href="index.php?controller=welcome&action=index">
                    <h1>Omnis Café</h1>
                </a>
            </div>
            <nav class="main-nav">
                <ul class="nav-list">
                    <li><a href="index.php?controller=welcome&action=index">Inicio</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle">Categorías <span class="arrow">▼</span></a>
                        <ul class="dropdown-menu">
                            <?php
                            // Obtener categorías para el menú desplegable
                            require_once __DIR__ . '/../../models/Categoria.php';
                            $catModel = new Categoria();
                            $categorias_menu = $catModel->getAll();
                            foreach ($categorias_menu as $cat): ?>
                                <li><a href="index.php?controller=categoria&action=show&id=<?= $cat['id_categoria'] ?>"><?= htmlspecialchars($cat['nombre_categoria']) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li><a href="index.php?controller=producto&action=index">Productos</a></li>
                    <li><a href="index.php?controller=reserva&action=index">Reservar mesa</a></li>
                </ul>
            </nav>
            <div class="header-icons">
                <!-- Toggle modo oscuro -->
                <button id="darkModeToggle" class="icon-btn" aria-label="Cambiar modo claro/oscuro">
                    <span class="material-symbols-outlined">dark_mode</span>
                </button>

                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <!-- Usuario autenticado: menú desplegable -->
                    <div class="dropdown user-dropdown">
                        <a href="#" class="dropdown-toggle icon-link" aria-label="Menú de usuario">
                            <span class="material-symbols-outlined">account_circle</span>
                            <span class="user-name"><?= htmlspecialchars($_SESSION['usuario_nombre']) ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="index.php?controller=usuario&action=perfil">Mi perfil</a></li>
                            <li><a href="index.php?controller=usuario&action=pedidos">Mis pedidos</a></li>
                            <li><a href="index.php?controller=usuario&action=reservas">Mis reservas</a></li>
                            <li><a href="index.php?controller=auth&action=logout">Cerrar sesión</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <!-- Usuario no autenticado: icono de login -->
                    <a href="index.php?controller=auth&action=showLoginForm" class="icon-link" aria-label="Iniciar sesión o registrarse">
                        <span class="material-symbols-outlined">account_circle</span>
                    </a>
                <?php endif; ?>

                <!-- Icono del carrito (toggle) -->
                <a href="javascript:void(0)" id="cartToggle" class="icon-link cart-icon" aria-label="Carrito">
                    <span class="material-symbols-outlined">receipt_long</span>
                    <?php
                    require_once __DIR__ . '/../../models/Carrito.php';
                    $numItems = Carrito::contarItems();
                    if ($numItems > 0): ?>
                        <span class="cart-badge"><?= $numItems ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
        <a href="/cambiar-idioma?lang=es">ES</a> |
<a href="/cambiar-idioma?lang=en">EN</a>
    </header>

    <!-- Sidebar del carrito -->
    <?php include __DIR__ . '/../carrito.php'; ?>

    <main class="main-content">