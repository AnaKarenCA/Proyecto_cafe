<?php
$titulo = "Bienvenido a Omnis Café";
require_once __DIR__ . '/../helpers/i18n.php';
include __DIR__ . '/layout/header.php';

$temp_min = $weatherData['main']['temp_min'] ?? null;
$temp_max = $weatherData['main']['temp_max'] ?? null;
$idioma = $_SESSION['idioma'] ?? 'es';
?>

<div class="hero-section">
    <section class="carousel-section">
        <h2 class="carousel-title"><?= __('recommendations_today') ?>: <span class="climate-highlight"><?= htmlspecialchars(ucfirst($nombreClima)) ?></span></h2>
        <div class="carousel-container">
            <div class="carousel-slides">
                <?php if (isset($carrusel_items) && count($carrusel_items) > 0): ?>
                    <?php foreach ($carrusel_items as $item): ?>
                        <div class="carousel-slide" style="background-image: url('/img/productos/<?= htmlspecialchars($item['imagen'] ?? 'default.jpg') ?>');">
                            <div class="carousel-content">
                                <div class="carousel-name"><?= htmlspecialchars($item['nombre']) ?></div>
                                <div class="carousel-des"><?= htmlspecialchars($item['descripcion'] ?? '') ?></div>
                                <a href="index.php?controller=producto&action=show&id=<?= $item['id_producto'] ?>" class="button"><?= __('ver_detalles') ?></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="carousel-slide" style="background-image: url('/img/productos/default.jpg');">
                        <div class="carousel-content">
                            <div class="carousel-name">Omnis Café</div>
                            <div class="carousel-des"><?= __('welcome_message') ?></div>
                            <a href="#" class="button"><?= __('ver_mas') ?></a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="carousel-nav">
                <button class="carousel-prev" aria-label="Anterior">◁</button>
                <button class="carousel-next" aria-label="Siguiente">▷</button>
            </div>
        </div>
    </section>
</div>

<!-- Widget de clima dinámico según idioma -->
<div class="weather-widget-container">
    <div id="weather-widget"></div>
    <script>
        (function() {
            const lang = '<?= $idioma ?>';
            let widgetCode = '';
            if (lang === 'es') {
                widgetCode = `<div id="ww_d31f695226ac3" v='1.3' loc='auto' a='{"t":"responsive","lang":"es","sl_lpl":1,"ids":[],"font":"Arial","sl_ics":"one_a","sl_sot":"celsius","cl_bkg":"#FFFFFF00","cl_font":"#000000","cl_cloud":"#d4d4d4","cl_persp":"#2196F3","cl_sun":"#FFC107","cl_moon":"#FFC107","cl_thund":"#FF5722","cl_odd":"#00000000","sl_tof":"5"}'>Más previsiones: <a href="https://tiempolargo.com/buenos_aires_tiempo_25_dias/" id="ww_d31f695226ac3_u" target="_blank">25 días tiempo Buenos Aires</a></div>`;
            } else if (lang === 'en') {
                widgetCode = `<div id="ww_d31f695226ac3" v='1.3' loc='auto' a='{"t":"responsive","lang":"en","sl_lpl":1,"ids":[],"font":"Arial","sl_ics":"one_a","sl_sot":"fahrenheit","cl_bkg":"#FFFFFF00","cl_font":"#000000","cl_cloud":"#d4d4d4","cl_persp":"#2196F3","cl_sun":"#FFC107","cl_moon":"#FFC107","cl_thund":"#FF5722","cl_odd":"#00000000","sl_tof":"5"}'>More forecasts: <a href="https://oneweather.org/orlando/30_days/" id="ww_d31f695226ac3_u" target="_blank">Orlando weather 30 day</a></div>`;
            } else if (lang === 'de') {
                widgetCode = `<div id="ww_d31f695226ac3" v='1.3' loc='auto' a='{"t":"responsive","lang":"de","sl_lpl":1,"ids":[],"font":"Arial","sl_ics":"one_a","sl_sot":"fahrenheit","cl_bkg":"#FFFFFF00","cl_font":"#000000","cl_cloud":"#d4d4d4","cl_persp":"#2196F3","cl_sun":"#FFC107","cl_moon":"#FFC107","cl_thund":"#FF5722","cl_odd":"#00000000","sl_tof":"5"}'>Wetter widget: <a href="https://weatherwidget.org/de/" id="ww_d31f695226ac3_u" target="_blank">Wetter widget</a></div>`;
            }
            document.getElementById('weather-widget').innerHTML = widgetCode;
            const script = document.createElement('script');
            script.src = 'https://app3.weatherwidget.org/js/?id=ww_d31f695226ac3';
            script.async = true;
            document.body.appendChild(script);
        })();
    </script>
</div>


<!-- Productos más vendidos -->
<section class="top-products">
    <h2><?= __('best_sellers') ?></h2>
    <?php if (isset($productos_destacados) && count($productos_destacados) > 0): ?>
        <div class="top-list">
            <?php foreach ($productos_destacados as $index => $producto): ?>
                <div class="top-item" style="background-image: url('/img/productos/<?= htmlspecialchars($producto['imagen'] ?? 'default.jpg') ?>');">
                    <div class="top-item-overlay"></div>
                    <div class="top-item-number"><?= $index + 1 ?></div>
                    <div class="top-item-content">
                        <h3><?= htmlspecialchars($producto['nombre']) ?></h3>
                        <p class="top-item-description"><?= htmlspecialchars($producto['descripcion'] ?? '') ?></p>
                        <p class="top-item-price"><?= format_currency($producto['precio'] ?? 0) ?></p>
                        <?php if (isset($producto['total_vendido'])): ?>
                            <p class="top-item-sold"><?= $producto['total_vendido'] ?> <?= __('sold') ?></p>
                        <?php endif; ?>
                        <div class="top-item-actions">
                            <a href="index.php?controller=producto&action=show&id=<?= $producto['id_producto'] ?>" class="button"><?= __('ver_detalles') ?></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p><?= __('no_products') ?></p>
    <?php endif; ?>
</section>

<!-- Información del Café (rediseñada) -->
<section class="info-cafe">
    <h2><?= __('cafe_info') ?></h2>
    <div class="info-grid">
        <div class="info-card glass-effect">
            <div class="info-icon">
                <span class="material-symbols-outlined">schedule</span>
            </div>
            <h3><?= __('hours') ?></h3>
            <p><?= __('mon_sun') ?></p>
            <p class="highlight">9:00 <?= __('am') ?> – 5:00 <?= __('pm') ?></p>
        </div>
        <div class="info-card glass-effect">
            <div class="info-icon">
                <span class="material-symbols-outlined">table_restaurant</span>
            </div>
            <h3><?= __('tables_available') ?></h3>
            <p>10 <?= __('tables') ?></p>
            <p class="highlight">6 <?= __('seats_per_table') ?></p>
        </div>
        <div class="info-card glass-effect">
            <div class="info-icon">
                <span class="material-symbols-outlined">event_available</span>
            </div>
            <h3><?= __('reservations') ?></h3>
            <p><?= __('reserve_easily') ?></p>
            <a href="index.php?controller=reserva&action=index" class="btn-reservar"><?= __('reserve_table') ?></a>
        </div>
    </div>
</section>

<?php if (!$hasLocation): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                fetch('index.php?controller=welcome&action=setLocation', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ lat: position.coords.latitude, lon: position.coords.longitude })
                })
                .then(response => response.json())
                .then(data => { if (data.success) location.reload(); })
                .catch(err => console.error(err));
            }, function(error) { console.warn('Geolocalización no permitida:', error); });
        }
    });
</script>
<?php endif; ?>

<?php include __DIR__ . '/layout/footer.php'; ?>