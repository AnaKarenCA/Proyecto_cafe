<?php
header('Content-Type: text/css; charset=UTF-8');
header('Cache-Control: public, max-age=86400');

$files = [
    'base/variables.css',
    'base/reset.css',
    'layout/header.css',
    'layout/footer.css',
    'layout/grid.css',
    'layout/sidebar.css',
    'components/buttons.css',
    'components/forms.css',
    'components/cards.css',
    'components/alerts.css',
    'components/carousel.css',
    'components/accessibility.css',
    'components/dropdown.css',
    'components/tables.css',
    'pages/home.css',
    'pages/productos.css',
    'pages/producto-detalle.css',
    'pages/checkout.css',
    'pages/auth.css',
    'pages/perfil.css',
    'pages/reservas.css',      // NUEVO
    'pages/admin.css',          // NUEVO
    'components/theme-switch.css', 
    'layout/menu.css', 
    'responsive.css'
    
    
];

foreach ($files as $file) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        echo "/* $file */\n";
        readfile($path);
        echo "\n\n";
    }
}
?>