<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../helpers/i18n.php';

$rol = $_SESSION['rol'] ?? null;
$idioma_actual = $_SESSION['idioma'] ?? 'es';
?>

<!DOCTYPE html>
<html lang="<?= $idioma_actual ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titulo) ? htmlspecialchars($titulo) . ' | Omnis Café' : 'Omnis Café' ?></title>
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <link rel="stylesheet" href="css/css.php">
</head>
<body>

<header class="main-header">
    <div class="container">
        
        <div class="logo">
            <a href="index.php?controller=welcome&action=index" class="logo-link">
                <img src="/img/icons/logo-cafe.png" alt="Omnis Café" class="logo-img">
                <span class="logo-text">Omnis Café</span>
            </a>
        </div>

        <nav class="main-nav">
            <ul class="nav-list">
                <li><a href="index.php?controller=producto&action=index"><?= __('productos') ?></a></li>
                <li><a href="index.php?controller=reserva&action=index"><?= __('reservar_mesa') ?></a></li>
            </ul>
        </nav>

        <!-- Iconos de usuario, idioma, carrito -->
        <div class="header-icons">
            
            <!-- Switch de tema -->
            <label class="theme-switch" for="themeToggle" aria-label="<?= __('cambiar_tema') ?>">
                <input type="checkbox" id="themeToggle" class="theme-switch__checkbox" <?= ($_SESSION['tema'] ?? 'claro') == 'oscuro' ? 'checked' : '' ?>>
                <div class="theme-switch__container">
                    <div class="theme-switch__clouds"></div>
                    <div class="theme-switch__stars-container">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 144 55" fill="none">...</svg>
                    </div>
                    <div class="theme-switch__circle-container">
                        <div class="theme-switch__sun-moon-container">
                            <div class="theme-switch__moon">
                                <div class="theme-switch__spot"></div>
                                <div class="theme-switch__spot"></div>
                                <div class="theme-switch__spot"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </label>

            <!-- Selector de idiomas -->
            <?php
            $flag_map = ['es' => 'flag-mexico.png', 'en' => 'flag-usa.png', 'de' => 'flag-germany.png'];
            $current_flag = $flag_map[$idioma_actual] ?? 'flag-mexico.png';
            ?>
            <div class="language-dropdown">
                <button class="language-btn" aria-label="Seleccionar idioma" aria-haspopup="true" aria-expanded="false">
                    <img src="/img/icons/<?= $current_flag ?>" alt="" class="flag-icon">
                    <span class="material-symbols-outlined">arrow_drop_down</span>
                </button>
                <ul class="language-menu" hidden>
                    <li><a href="index.php?controller=language&action=change&lang=es" class="language-option <?= $idioma_actual == 'es' ? 'active' : '' ?>"><img src="/img/icons/flag-mexico.png" alt="Español" class="flag-icon"> Español</a></li>
                    <li><a href="index.php?controller=language&action=change&lang=en" class="language-option <?= $idioma_actual == 'en' ? 'active' : '' ?>"><img src="/img/icons/flag-usa.png" alt="English" class="flag-icon"> English</a></li>
                    <li><a href="index.php?controller=language&action=change&lang=de" class="language-option <?= $idioma_actual == 'de' ? 'active' : '' ?>"><img src="/img/icons/flag-germany.png" alt="Deutsch" class="flag-icon"> Deutsch</a></li>
                </ul>
            </div>

            <!-- Usuario -->
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <div class="dropdown user-dropdown">
                    <a href="#" class="dropdown-toggle icon-link" aria-label="<?= __('menu_usuario') ?>">
                        <img src="/img/icons/user-icon.png" alt="<?= __('usuario') ?>" class="icon-img">
                        <span class="user-name"><?= htmlspecialchars($_SESSION['usuario_nombre']) ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <?php if ($rol === 'admin'): ?>
                            <li><a href="index.php?controller=usuario&action=perfil"><?= __('mi_perfil') ?></a></li>
                            <li><a href="index.php?controller=adminProducto&action=index"><?= __('administrar_productos') ?></a></li>
                            <li><a href="index.php?controller=adminCategoria&action=index"><?= __('administrar_categorias') ?></a></li>
                            <li><a href="index.php?controller=adminPedido&action=index"><?= __('ver_pedidos') ?></a></li>
                            <li><a href="index.php?controller=adminReserva&action=index"><?= __('ver_reservas') ?></a></li>
                        <?php endif; ?>
                        <?php if ($rol === 'cliente'): ?>
                            <li><a href="index.php?controller=usuario&action=perfil"><?= __('mi_perfil') ?></a></li>
                            <li><a href="index.php?controller=usuario&action=pedidos"><?= __('mis_pedidos') ?></a></li>
                            <li><a href="index.php?controller=usuario&action=reservas"><?= __('mis_reservas') ?></a></li>
                        <?php endif; ?>
                        <li><a href="index.php?controller=auth&action=logout"><?= __('cerrar_sesion') ?></a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="index.php?controller=auth&action=showLoginForm" class="icon-link" aria-label="<?= __('iniciar_sesion') ?>">
                    <img src="/img/icons/user-icon.png" alt="<?= __('iniciar_sesion') ?>" class="icon-img">
                </a>
            <?php endif; ?>

            <!-- Carrito -->
            <?php
            require_once __DIR__ . '/../../models/Carrito.php';
            $numItems = Carrito::totalItems();
            ?>
            <a href="javascript:void(0)" onclick="toggleCart()" id="cartToggle" class="icon-link cart-icon" aria-label="<?= __('carrito') ?>">
                <img src="/img/icons/factura.png" alt="<?= __('carrito') ?>" class="icon-img">
                <span id="cartCount" class="cart-badge"><?= $numItems ?></span>
            </a>
        </div>
    </div>

    <!-- Scripts -->
    <script src="js/cart.js"></script>
    <script src="js/carousel.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<!-- Contenedor del carrito lateral -->
<div id="cartSidebar" class="cart-sidebar"></div>
<main class="main-content">