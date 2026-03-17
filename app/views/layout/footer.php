    </main>

    <footer class="main-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Omnis Café. Todos los derechos reservados.</p>
            <p>Diseñado con enfoque de accesibilidad universal.</p>
        </div>
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
    </footer>

    <script src="js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>