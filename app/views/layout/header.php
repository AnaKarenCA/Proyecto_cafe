<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$rol = $_SESSION['rol'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= isset($titulo) ? htmlspecialchars($titulo) . ' | Omnis Café' : 'Omnis Café' ?></title>

<link rel="stylesheet"
href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1"/>

<link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="accessibility-toolbar">
<button id="accessibilityBtn" class="accessibility-btn">
Accesibilidad
</button>

<div id="accessibilityPanel" class="accessibility-panel" hidden>

<button id="fontIncrease">Aumentar texto</button>
<button id="fontDecrease">Disminuir texto</button>
<button id="screenReader">Leer pantalla</button>

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

<li>
<a href="index.php?controller=welcome&action=index">Inicio</a>
</li>

<li class="dropdown">

<a href="#" class="dropdown-toggle">
Categorías ▼
</a>

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

<li>
<a href="index.php?controller=producto&action=index">
Productos
</a>
</li>

<li>
<a href="index.php?controller=reserva&action=index">
Reservar mesa
</a>
</li>

</ul>

</nav>


<div class="header-icons">

<button id="darkModeToggle" class="icon-btn">

<span class="material-symbols-outlined">
dark_mode
</span>

</button>


<?php if (isset($_SESSION['usuario_id'])): ?>


<div class="dropdown user-dropdown">

<a href="#" class="dropdown-toggle icon-link">

<span class="material-symbols-outlined">
account_circle
</span>

<span class="user-name">
<?= htmlspecialchars($_SESSION['usuario_nombre']) ?>
</span>

</a>

<ul class="dropdown-menu">


<?php if ($rol === 'admin'): ?>
<li>
<a href="index.php?controller=usuario&action=perfil">
Mi perfil
</a>
</li>
<li>

<a href="index.php?controller=adminProducto&action=index">

Administrar productos

</a>

</li>


<li>

<a href="index.php?controller=adminCategoria&action=index">

Administrar categorías

</a>

</li>


<li>

<a href="index.php?controller=adminPedido&action=index">

Ver pedidos

</a>

</li>


<li>

<a href="index.php?controller=adminReserva&action=index">

Ver reservas

</a>

</li>

<?php endif; ?>


<?php if ($rol === 'cliente'): ?>

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


<li>

<a href="index.php?controller=auth&action=logout">

Cerrar sesión

</a>

</li>

</ul>

</div>


<?php else: ?>

<a href="index.php?controller=auth&action=showLoginForm"
class="icon-link">

<span class="material-symbols-outlined">

account_circle

</span>

</a>

<?php endif; ?>


<a href="javascript:void(0)"
id="cartToggle"
class="icon-link cart-icon">

<span class="material-symbols-outlined">

receipt_long

</span>

<?php

require_once __DIR__ . '/../../models/Carrito.php';

$numItems = Carrito::contarItems();

if ($numItems > 0):

?>

<span class="cart-badge">

<?= $numItems ?>

</span>

<?php endif; ?>

</a>

</div>

</div>


<div style="text-align:center;margin-top:10px">

<a href="/cambiar-idioma?lang=es">ES</a>
|
<a href="/cambiar-idioma?lang=en">EN</a>

</div>

</header>

<?php include __DIR__ . '/../carrito.php'; ?>

<main class="main-content">