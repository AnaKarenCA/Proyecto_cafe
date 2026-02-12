<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Omnis Café</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header>
        <div class="logo">Omnis Café</div>
        <nav class="top-menu">
            <ul>
                <li><a href="/">Inicio</a></li>
                <li><a href="/categorias">Categorías</a></li>
                <li><a href="#">Más comprado</a></li>
                <li><a href="/reserva">Reservar mesa</a></li>
            </ul>
        </nav>
        <div class="header-icons">
            <i class="fas fa-search"></i>
            <i class="fas fa-shopping-cart cart-icon"></i>
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <a href="/logout"><i class="fas fa-sign-out-alt"></i> <?= $_SESSION['usuario_nombre'] ?></a>
            <?php else: ?>
                <a href="/login"><i class="fas fa-user"></i></a>
            <?php endif; ?>
        </div>
    </header>
    <main>