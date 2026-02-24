<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titulo) ? htmlspecialchars($titulo) . ' | Omnis Café' : 'Omnis Café' ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Botón de accesibilidad flotante (visible en todas las páginas) -->
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
            <?php include __DIR__ . '/menu.php'; ?>
        </div>
    </header>

    <main class="main-content">