<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titulo) ? htmlspecialchars($titulo) . ' | Omnis Café' : 'Omnis Café' ?></title>
    <!-- Material Symbols -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1" />
<link rel="stylesheet" href="/css/css.php">
</head>
<body class="auth-page">
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

    <main class="auth-main">