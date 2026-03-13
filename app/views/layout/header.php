<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ===== NUEVO: incluir helper de traducción =====
require_once __DIR__ . '/../../helpers/i18n.php';

$rol = $_SESSION['rol'] ?? null;
$idioma_actual = $_SESSION['idioma'] ?? 'es'; // 'es', 'en', 'de'
?>

<!DOCTYPE html>
<html lang="<?= $idioma_actual ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titulo) ? htmlspecialchars($titulo) . ' | Omnis Café' : 'Omnis Café' ?></title>
    
    <!-- Material Symbols (para iconos que aún uses) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1"/>
    
    <!-- CSS combinado -->
    <link rel="stylesheet" href="/css/css.php">
</head>
<body>

<!-- ========== BOTÓN DE ACCESIBILIDAD CON IMAGEN ========== -->
<div class="accessibility-toolbar">
    <button id="accessibilityBtn" class="accessibility-btn" aria-label="<?= __('accesibilidad') ?>">
        <img src="/img/icons/accesibilidad-icon.png" alt="<?= __('accesibilidad') ?>" class="icon-img">
    </button>
    <div id="accessibilityPanel" class="accessibility-panel" hidden>
        <button id="fontIncrease"><?= __('aumentar_texto') ?></button>
        <button id="fontDecrease"><?= __('disminuir_texto') ?></button>
        <button id="screenReader"><?= __('leer_pantalla') ?></button>
    </div>
</div>

<header class="main-header">
    <div class="container">
        
        <!-- ========== LOGO CON IMAGEN ========== -->
        <div class="logo">
            <a href="index.php?controller=welcome&action=index" class="logo-link">
                <img src="/img/icons/logo-cafe.png" alt="Omnis Café" class="logo-img">
                <span class="logo-text">Omnis Café</span>
            </a>
        </div>

        <!-- ========== NAVEGACIÓN PRINCIPAL ========== -->
        <nav class="main-nav">
            <ul class="nav-list">
                <!-- ===== TEXTO TRADUCIDO ===== -->
                <li><a href="index.php?controller=welcome&action=index"><?= __('inicio') ?></a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle"><?= __('categorias') ?> ▼</a>
                    <ul class="dropdown-menu">
                        <?php
                        require_once __DIR__ . '/../../models/Categoria.php';
                        $catModel = new Categoria();
                        $categorias_menu = $catModel->getAll();
                        foreach ($categorias_menu as $cat):
                        ?>
                        <li>
                            <a href="index.php?controller=categoria&action=show&id=<?= $cat['id_categoria'] ?>">
                                <?= htmlspecialchars($cat['nombre_categoria']) ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li><a href="index.php?controller=producto&action=index"><?= __('productos') ?></a></li>
                <li><a href="index.php?controller=reserva&action=index"><?= __('reservar_mesa') ?></a></li>
            </ul>
        </nav>

        <!-- ========== ICONOS DE USUARIO, IDIOMAS, CARRITO ========== -->
        <div class="header-icons">
            
            <!-- Botón modo oscuro (icono Material) -->
            <!-- SWITCH DE TEMA (UIVERSE) -->
<label class="theme-switch" for="themeToggle" aria-label="<?= __('cambiar_tema') ?>">
    <input type="checkbox" id="themeToggle" class="theme-switch__checkbox" <?= ($_SESSION['tema'] ?? 'claro') == 'oscuro' ? 'checked' : '' ?>>
    <div class="theme-switch__container">
        <div class="theme-switch__clouds"></div>
        <div class="theme-switch__stars-container">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 144 55" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M135.831 3.00688C135.055 3.85027 134.111 4.29946 133 4.35447C134.111 4.40947 135.055 4.85867 135.831 5.71123C136.607 6.55462 136.996 7.56303 136.996 8.72727C136.996 7.95722 137.172 7.25134 137.525 6.59129C137.886 5.93124 138.372 5.39954 138.98 5.00535C139.598 4.60199 140.268 4.39114 141 4.35447C139.88 4.2903 138.936 3.85027 138.16 3.00688C137.384 2.16348 136.996 1.16425 136.996 0C136.996 1.16425 136.607 2.16348 135.831 3.00688ZM31 23.3545C32.1114 23.2995 33.0551 22.8503 33.8313 22.0069C34.6075 21.1635 34.9956 20.1642 34.9956 19C34.9956 20.1642 35.3837 21.1635 36.1599 22.0069C36.9361 22.8503 37.8798 23.2903 39 23.3545C38.2679 23.3911 37.5976 23.602 36.9802 24.0053C36.3716 24.3995 35.8864 24.9312 35.5248 25.5913C35.172 26.2513 34.9956 26.9572 34.9956 27.7273C34.9956 26.563 34.6075 25.5546 33.8313 24.7112C33.0551 23.8587 32.1114 23.4095 31 23.3545ZM0 36.3545C1.11136 36.2995 2.05513 35.8503 2.83131 35.0069C3.6075 34.1635 3.99559 33.1642 3.99559 32C3.99559 33.1642 4.38368 34.1635 5.15987 35.0069C5.93605 35.8503 6.87982 36.2903 8 36.3545C7.26792 36.3911 6.59757 36.602 5.98015 37.0053C5.37155 37.3995 4.88644 37.9312 4.52481 38.5913C4.172 39.2513 3.99559 39.9572 3.99559 40.7273C3.99559 39.563 3.6075 38.5546 2.83131 37.7112C2.05513 36.8587 1.11136 36.4095 0 36.3545ZM56.8313 24.0069C56.0551 24.8503 55.1114 25.2995 54 25.3545C55.1114 25.4095 56.0551 25.8587 56.8313 26.7112C57.6075 27.5546 57.9956 28.563 57.9956 29.7273C57.9956 28.9572 58.172 28.2513 58.5248 27.5913C58.8864 26.9312 59.3716 26.3995 59.9802 26.0053C60.5976 25.602 61.2679 25.3911 62 25.3545C60.8798 25.2903 59.9361 24.8503 59.1599 24.0069C58.3837 23.1635 57.9956 22.1642 57.9956 21C57.9956 22.1642 57.6075 23.1635 56.8313 24.0069ZM81 25.3545C82.1114 25.2995 83.0551 24.8503 83.8313 24.0069C84.6075 23.1635 84.9956 22.1642 84.9956 21C84.9956 22.1642 85.3837 23.1635 86.1599 24.0069C86.9361 24.8503 87.8798 25.2903 89 25.3545C88.2679 25.3911 87.5976 25.602 86.9802 26.0053C86.3716 26.3995 85.8864 26.9312 85.5248 27.5913C85.172 28.2513 84.9956 28.9572 84.9956 29.7273C84.9956 28.563 84.6075 27.5546 83.8313 26.7112C83.0551 25.8587 82.1114 25.4095 81 25.3545ZM136 36.3545C137.111 36.2995 138.055 35.8503 138.831 35.0069C139.607 34.1635 139.996 33.1642 139.996 32C139.996 33.1642 140.384 34.1635 141.16 35.0069C141.936 35.8503 142.88 36.2903 144 36.3545C143.268 36.3911 142.598 36.602 141.98 37.0053C141.372 37.3995 140.886 37.9312 140.525 38.5913C140.172 39.2513 139.996 39.9572 139.996 40.7273C139.996 39.563 139.607 38.5546 138.831 37.7112C138.055 36.8587 137.111 36.4095 136 36.3545ZM101.831 49.0069C101.055 49.8503 100.111 50.2995 99 50.3545C100.111 50.4095 101.055 50.8587 101.831 51.7112C102.607 52.5546 102.996 53.563 102.996 54.7273C102.996 53.9572 103.172 53.2513 103.525 52.5913C103.886 51.9312 104.372 51.3995 104.98 51.0053C105.598 50.602 106.268 50.3911 107 50.3545C105.88 50.2903 104.936 49.8503 104.16 49.0069C103.384 48.1635 102.996 47.1642 102.996 46C102.996 47.1642 102.607 48.1635 101.831 49.0069Z" fill="currentColor"></path>
            </svg>
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

            <!-- ===== SELECTOR DE IDIOMAS CON BANDERAS (APUNTANDO AL CONTROLADOR) ===== -->
            <!-- SELECTOR DE IDIOMAS MEJORADO -->
<?php
// Mapeo de idioma a archivo de bandera
$flag_map = [
    'es' => 'flag-mexico.png',
    'en' => 'flag-usa.png',
    'de' => 'flag-germany.png'
];
$current_flag = $flag_map[$idioma_actual] ?? 'flag-mexico.png';
?>

<!-- SELECTOR DE IDIOMAS MEJORADO -->
<div class="language-dropdown">
    <button class="language-btn" aria-label="Seleccionar idioma" aria-haspopup="true" aria-expanded="false">
        <img src="/img/icons/<?= $current_flag ?>" alt="" class="flag-icon">
        <span class="material-symbols-outlined">arrow_drop_down</span>
    </button>
    <ul class="language-menu" hidden>
        <li><a href="index.php?controller=language&action=change&lang=es" class="language-option <?= $idioma_actual == 'es' ? 'active' : '' ?>">
            <img src="/img/icons/flag-mexico.png" alt="Español" class="flag-icon"> Español
        </a></li>
        <li><a href="index.php?controller=language&action=change&lang=en" class="language-option <?= $idioma_actual == 'en' ? 'active' : '' ?>">
            <img src="/img/icons/flag-usa.png" alt="English" class="flag-icon"> English
        </a></li>
        <li><a href="index.php?controller=language&action=change&lang=de" class="language-option <?= $idioma_actual == 'de' ? 'active' : '' ?>">
            <img src="/img/icons/flag-germany.png" alt="Deutsch" class="flag-icon"> Deutsch
        </a></li>
    </ul>
</div>
            <!-- ICONO DE USUARIO (imagen) -->
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

            <!-- CARRITO -->
            <!-- CARRITO CON IMAGEN -->
<a href="javascript:void(0)" id="cartToggle" class="icon-link cart-icon" aria-label="<?= __('carrito') ?>">
    <img src="/img/icons/factura.png" alt="<?= __('carrito') ?>" class="icon-img">
    <?php
    require_once __DIR__ . '/../../models/Carrito.php';
    $numItems = Carrito::contarItems();
    if ($numItems > 0):
    ?>
    <span class="cart-badge"><?= $numItems ?></span>
    <?php endif; ?>
</a>
        </div>
    </div>
</header>

<!-- Incluir carrito lateral -->
<?php include __DIR__ . '/../carrito.php'; ?>

<main class="main-content">