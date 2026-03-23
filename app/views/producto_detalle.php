<?php
// $producto debe venir del controlador
if (!isset($producto)) {
    die('Producto no encontrado');
}

// Obtener datos adicionales
$tamanoModel = new Tamano();
$alergenoModel = new Alergeno();
$extraModel = new Extra();

$tamanos = $tamanoModel->getByProducto($producto['id_producto']);
$alergenos = $alergenoModel->getByProducto($producto['id_producto']);
$extras = $extraModel->getByProducto($producto['id_producto']);
$ingredientes = $ingredientes ?? [];

$titulo = htmlspecialchars($producto['nombre']);
include __DIR__ . '/layout/header.php';


// Configuración de unidades según idioma
$lang = $_SESSION['idioma'] ?? 'es';
$volumeUnits = [
    'es' => 'ml',
    'en' => 'fl oz',
    'de' => 'ml'
];
$currentUnit = $volumeUnits[$lang] ?? 'ml';

// Conversión de volúmenes (oz a ml)
$sizeVolumes = [
    'Chico'   => ['oz' => 8,   'ml' => 240],
    'Mediano' => ['oz' => 12,  'ml' => 355],
    'Grande'  => ['oz' => 16,  'ml' => 475]
];
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
<style>
    /* Estilos generales */
    .product-container {
        max-width: 1100px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        background: var(--bg-surface);
        padding: 40px;
        border-radius: 30px;
        box-shadow: 0 20px 50px var(--shadow);
    }
    @media (max-width: 850px) {
        .product-container { grid-template-columns: 1fr; }
    }
    .product-image-side {
        text-align: center;
    }
    .product-image-side img {
        max-width: 80%;
        filter: drop-shadow(0 20px 30px rgba(0,0,0,0.1));
    }

    /* Botón personalizar receta (usando variables del sistema) */
    .btn-customize {
        cursor: pointer;
        position: relative;
        padding: 10px 24px;
        font-size: 18px;
        color: var(--accent-strong);
        border: 2px solid var(--accent-strong);
        border-radius: 34px;
        background-color: transparent;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.23, 1, 0.320, 1);
        overflow: hidden;
        margin-bottom: 15px;
        display: inline-block;
    }
    .btn-customize::before {
        content: '';
        position: absolute;
        inset: 0;
        margin: auto;
        width: 50px;
        height: 50px;
        border-radius: inherit;
        scale: 0;
        z-index: -1;
        background-color: var(--accent-strong);
        transition: all 0.6s cubic-bezier(0.23, 1, 0.320, 1);
    }
    .btn-customize:hover::before {
        scale: 3;
    }
    .btn-customize:hover {
        color: #212121;
        scale: 1.1;
        box-shadow: 0 0px 20px rgba(193, 163, 98, 0.4);
    }
    .btn-customize:active {
        scale: 1;
    }

    /* Ocultar/mostrar personalización */
    .custom-grid {
        display: none;
        margin-top: 20px;
    }
    .custom-grid.show {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    /* Checkboxes de ingredientes (rojo al activarse) */
    .ingredient-checkbox {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }
    .ingredient-checkbox input {
        appearance: none;
        width: 20px;
        height: 20px;
        border: 2px solid var(--accent-warm);
        border-radius: 4px;
        background: var(--bg-surface);
        transition: all 0.2s;
        cursor: pointer;
    }
    .ingredient-checkbox input:checked {
        background-color: #e74c3c;
        border-color: #e74c3c;
        position: relative;
    }
    .ingredient-checkbox input:checked::after {
        content: "✓";
        display: block;
        text-align: center;
        color: white;
        font-size: 14px;
        line-height: 18px;
    }
    .ingredient-checkbox span {
        color: var(--text-primary);
    }

    /* Extras – nuevo estilo de checkbox */
    .extra-checkboxes {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 10px;
    }
    .extra-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .extra-label {
        color: var(--text-primary);
    }
    /* Estilos para el nuevo checkbox de extras */
    .extra-checkbox-wrapper {
        display: inline-flex;
        align-items: center;
        cursor: pointer;
    }
    .extra-checkbox-input {
        display: none;
    }
    .extra-checkbox-box {
        width: 28px;
        height: 28px;
        background-color: var(--bg-surface);
        border: 2px solid var(--accent-warm);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .extra-checkbox-box svg {
        width: 18px;
        height: 18px;
        color: white;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .extra-checkbox-input:checked + .extra-checkbox-box {
        background-color: var(--accent-strong);
        border-color: var(--accent-strong);
    }
    .extra-checkbox-input:checked + .extra-checkbox-box svg {
        opacity: 1;
    }
    .extra-checkbox-wrapper:hover .extra-checkbox-box {
        transform: scale(1.05);
        box-shadow: 0 0 8px var(--accent-strong);
    }

    /* Selector de leche */
    .custom-select {
        width: fit-content;
        cursor: pointer;
        position: relative;
        transition: 300ms;
        color: var(--text-light);
        overflow: hidden;
        margin-top: 5px;
    }
    .custom-select .selected {
        background-color: var(--bg-surface);
        padding: 8px 12px;
        margin-bottom: 3px;
        border-radius: var(--border-radius);
        position: relative;
        z-index: 100;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border: 1px solid var(--accent-warm);
        color: var(--text-primary);
    }
    .custom-select .selected .arrow {
        position: relative;
        height: 12px;
        transform: rotate(-90deg);
        width: 20px;
        fill: currentColor;
        transition: 300ms;
    }
    .custom-select:hover .selected .arrow {
        transform: rotate(0deg);
    }
    .custom-select .options {
        display: flex;
        flex-direction: column;
        border-radius: var(--border-radius);
        padding: 5px;
        background-color: var(--bg-surface);
        border: 1px solid var(--accent-warm);
        position: relative;
        top: -100px;
        opacity: 0;
        transition: 300ms;
        z-index: 99;
    }
    .custom-select:hover .options {
        opacity: 1;
        top: 0;
    }
    .custom-select .options div {
        margin: 2px 0;
    }
    .custom-select .option {
        border-radius: var(--border-radius);
        padding: 8px 12px;
        transition: 300ms;
        background-color: var(--bg-surface);
        width: 180px;
        font-size: 0.9rem;
        display: inline-block;
        cursor: pointer;
        color: var(--text-primary);
    }
    .custom-select .option:hover {
        background-color: var(--accent-warm);
        color: var(--text-light);
    }
    .custom-select .options input[type="radio"] {
        display: none;
    }
    .custom-select .options label {
        display: inline-block;
        width: 100%;
    }
    .custom-select .options label::before {
        content: attr(data-txt);
    }
    .custom-select .options input[type="radio"]:checked + label {
        display: none;
    }
    .custom-select .selected .selected-text {
        flex: 1;
    }

    /* Control de cantidad (alineado a la izquierda) */
    .quantity-control {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 20px 0;
        justify-content: flex-start;
    }
    .qty-btn-detalle {
        width: 36px;
        height: 36px;
        background: var(--accent-warm);
        border: none;
        border-radius: 50%;
        font-size: 1.5rem;
        font-weight: bold;
        cursor: pointer;
        color: var(--text-light);
        transition: background 0.2s;
    }
    .qty-btn-detalle:hover {
        background: var(--accent-strong);
    }
    .quantity-input-detalle {
        width: 60px;
        text-align: center;
        font-size: 1.2rem;
        padding: 6px;
        border: 1px solid var(--accent-warm);
        border-radius: var(--border-radius);
        background: var(--bg-surface);
        color: var(--text-primary);
    }

    /* Total y botón de añadir */
    .add-section {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        margin-top: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }
    .total-price {
        font-size: 1.8rem;
        font-weight: bold;
        color: var(--accent-strong);
    }
    .btn-add-to-cart {
        position: relative;
        width: 220px;
        height: 48px;
        cursor: pointer;
        display: flex;
        align-items: center;
        border: 2px solid var(--accent-strong);
        background-color: var(--accent-warm);
        border-radius: var(--border-radius);
        overflow: hidden;
        transition: all 0.3s;
    }
    .btn-add-to-cart .btn__text {
        transform: translateX(30px);
        color: var(--text-light);
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s;
        white-space: nowrap;
    }
    .btn-add-to-cart .btn__icon {
        position: absolute;
        right: 0;
        height: 100%;
        width: 48px;
        background-color: var(--accent-strong);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }
    .btn-add-to-cart .svg {
        width: 24px;
        stroke: var(--text-light);
    }
    .btn-add-to-cart:hover {
        background: var(--accent-strong);
    }
    .btn-add-to-cart:hover .btn__text {
        color: transparent;
    }
    .btn-add-to-cart:hover .btn__icon {
        width: 100%;
        transform: translateX(0);
    }
    .btn-add-to-cart:active .btn__icon {
        background-color: var(--primary-deep);
    }
    .btn-add-to-cart:active {
        border-color: var(--primary-deep);
    }
</style>
<!-- Botón de retroceso -->
<div class="back-button">
    <a href="index.php?controller=producto&action=index" class="btn-back">
        <i class="ti ti-arrow-left"></i> <?= __('back_to_products') ?>
    </a>
</div>

<style>
.back-button {
    margin-bottom: 20px;
    text-align: left;
}
.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: var(--accent-warm);
    color: var(--text-light);
    border-radius: 30px;
    text-decoration: none;
    font-weight: 500;
    transition: background 0.3s;
}
.btn-back:hover {
    background: var(--accent-strong);
}
</style>
<div class="product-container">
    <div class="product-image-side">
        <img src="/img/productos/<?= htmlspecialchars($producto['imagen'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
        <div class="allergen-wrap">
            <?php foreach ($alergenos as $alergeno): ?>
            <div class="allergen-item">
                <span class="material-icons"><?= htmlspecialchars($alergeno['icono'] ?? '⚠️') ?></span>
                <span><?= htmlspecialchars($alergeno['nombre_alergeno']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="nutrition-accordion">
            <button class="nutrition-btn" onclick="toggleNutrition()">
                <span>⚡ 250 kcal | <?= __('info_nutrition') ?></span>
                <span class="arrow">▼</span>
            </button>
            <div class="nutrition-panel" id="nutritionPanel">
                <div class="nutrition-content">
                    <strong><?= __('fats') ?>:</strong> 9g | <strong><?= __('sugars') ?>:</strong> 25g<br>
                    <strong><?= __('protein') ?>:</strong> 5g | <strong><?= __('sodium') ?>:</strong> 150mg<br>
                    <small>*<?= __('approximate_values') ?>.</small>
                </div>
            </div>
        </div>
    </div>

    <div class="product-details">
        <h1><?= htmlspecialchars($producto['nombre']) ?></h1>

        <?php if (!empty($tamanos)): ?>
        <span class="section-label"><?= __('cup_size') ?></span>
        <div class="size-cards-container">
            <?php foreach ($tamanos as $index => $tamano): ?>
                <?php
                $sizeName = $tamano['nombre_tamano'];
                $translatedName = __('size_' . $sizeName);
                $volumeOz = $sizeVolumes[$sizeName]['oz'] ?? 0;
                $volumeValue = ($currentUnit === 'ml') ? $sizeVolumes[$sizeName]['ml'] : $volumeOz;
                $volumeUnit = $currentUnit;
                ?>
                <div class="size-card <?= $index == 0 ? 'active' : '' ?>" data-id="<?= $tamano['id_tamano'] ?>" data-incremento="<?= $tamano['incremento_precio'] ?>" onclick="selectSize(this)">
                    <div class="size-card-inner">
                        <div class="size-card-front">
                            <strong><?= htmlspecialchars($translatedName) ?></strong>
                            <small><?= $volumeValue . ' ' . $volumeUnit ?></small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Control de cantidad (arriba, antes de personalizar) -->
        <div class="quantity-control">
            <button class="qty-btn-detalle" id="qty-minus" type="button">-</button>
            <input type="number" id="quantity" class="quantity-input-detalle" value="1" min="1" max="10">
            <button class="qty-btn-detalle" id="qty-plus" type="button">+</button>
        </div>

        <!-- Botón personalizar receta -->
        <button class="btn-customize" id="btn-customize-toggle"><?= __('customize_recipe') ?></button>

        <div class="custom-grid" id="custom-options">
            <?php if (!empty($extras)): ?>
            <div class="custom-section">
                <label class="section-label"><?= __('toppings') ?></label>
                <div class="extra-checkboxes">
                    <?php foreach ($extras as $extra): ?>
                        <label class="extra-checkbox-wrapper">
                            <input type="checkbox" class="extra-checkbox-input" data-id="<?= $extra['id_extra'] ?>" data-precio="<?= $extra['precio_extra'] ?>" onchange="calculate()">
                            <span class="extra-checkbox-box">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </span>
                            <span class="extra-label ml-2"><?= htmlspecialchars($extra['nombre_extra']) ?> (+<?= format_currency($extra['precio_extra']) ?>)</span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="custom-section">
                <label class="section-label"><?= __('milk_type') ?></label>
                <div class="custom-select" id="milk-select">
                    <div class="selected" data-default="Entera" data-almond="Almendra (+0.80)" data-oat="Avena (+0.80)">
                        <span class="selected-text">Entera</span>
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" class="arrow">
                            <path d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"></path>
                        </svg>
                    </div>
                    <div class="options">
                        <div title="whole">
                            <input id="milk-whole" name="milk" type="radio" value="0" data-text="Entera" checked>
                            <label class="option" for="milk-whole" data-txt="Entera"></label>
                        </div>
                        <div title="almond">
                            <input id="milk-almond" name="milk" type="radio" value="0.8" data-text="Almendra (+0.80)">
                            <label class="option" for="milk-almond" data-txt="Almendra (+0.80)"></label>
                        </div>
                        <div title="oat">
                            <input id="milk-oat" name="milk" type="radio" value="0.8" data-text="Avena (+0.80)">
                            <label class="option" for="milk-oat" data-txt="Avena (+0.80)"></label>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!empty($ingredientes)): ?>
            <div class="custom-section">
                <label class="section-label"><?= __('remove_ingredients') ?></label>
                <div class="ingredients-list">
                    <?php foreach ($ingredientes as $ing): ?>
                    <label class="ingredient-checkbox">
                        <input type="checkbox" class="ingredient-check" data-id="<?= $ing['id_ingrediente'] ?>" onchange="calculate()">
                        <span><?= htmlspecialchars($ing['nombre_ingrediente']) ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sección total + botón -->
        <div class="add-section">
            <div class="total-price" id="total-price"><?= __('total') ?>: <?= format_currency($producto['precio']) ?></div>
            <button class="btn-add-to-cart" onclick="agregarAlCarrito()">
                <span class="btn__text"><?= __('add') ?></span>
                <span class="btn__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" viewBox="0 0 24 24" stroke-width="2" stroke-linejoin="round" stroke-linecap="round" stroke="currentColor" height="24" fill="none" class="svg">
                        <line y2="19" y1="5" x2="12" x1="12"></line>
                        <line y2="12" y1="12" x2="19" x1="5"></line>
                    </svg>
                </span>
            </button>
        </div>
    </div>
</div>

<script>
    // Tasas de cambio desde PHP
    const exchangeRates = {
        'es': 1,
        'en': 0.056,
        'de': 0.048
    };
    const currentLang = '<?= $_SESSION['idioma'] ?? 'es' ?>';
    const currentRate = exchangeRates[currentLang];
    const currencySymbol = {
        'es': 'MXN',
        'en': 'USD',
        'de': 'EUR'
    }[currentLang] || 'MXN';

    function convertAndFormat(amountMXN) {
        const converted = amountMXN * currentRate;
        return '$' + converted.toFixed(2) + ' ' + currencySymbol;
    }

    let productId = <?= $producto['id_producto'] ?>;
    let basePriceMXN = <?= $producto['precio'] ?>;
    let selectedSizeId = null;
    let selectedSizeIncrementMXN = 0;
    let selectedExtras = [];
    let selectedMilkPriceMXN = 0;
    let quantity = 1;

    function selectSize(el) {
        document.querySelectorAll('.size-card').forEach(c => c.classList.remove('active'));
        el.classList.add('active');
        selectedSizeId = el.dataset.id;
        selectedSizeIncrementMXN = parseFloat(el.dataset.incremento) || 0;
        calculate();
    }

    // Botón toggle para mostrar/ocultar personalización
    const btnCustomize = document.getElementById('btn-customize-toggle');
    const customOptions = document.getElementById('custom-options');
    btnCustomize.addEventListener('click', function() {
        customOptions.classList.toggle('show');
    });

    function calculate() {
        let totalMXN = basePriceMXN + selectedSizeIncrementMXN;

        // Sumar extras seleccionados (nuevo: usamos .extra-checkbox-input)
        selectedExtras = [];
        document.querySelectorAll('.extra-checkbox-input:checked').forEach(cb => {
            let precio = parseFloat(cb.dataset.precio) || 0;
            totalMXN += precio;
            selectedExtras.push({
                id: parseInt(cb.dataset.id),
                precio: precio
            });
        });

        // Sumar leche si personalización activa (panel visible)
        const isCustomOpen = customOptions.classList.contains('show');
        if (isCustomOpen) {
            let milkSelect = document.querySelector('.price-mod');
            if (milkSelect) {
                selectedMilkPriceMXN = parseFloat(milkSelect.value) || 0;
                totalMXN += selectedMilkPriceMXN;
            }
        } else {
            selectedMilkPriceMXN = 0;
        }

        document.getElementById('total-price').innerHTML = '<?= __('total') ?>: ' + convertAndFormat(totalMXN);
    }

    // Control de cantidad
    const qtyInput = document.getElementById('quantity');
    document.getElementById('qty-minus').addEventListener('click', () => {
        let val = parseInt(qtyInput.value);
        if (val > 1) qtyInput.value = val - 1;
    });
    document.getElementById('qty-plus').addEventListener('click', () => {
        let val = parseInt(qtyInput.value);
        if (val < 10) qtyInput.value = val + 1;
    });
    qtyInput.addEventListener('change', function() {
        let val = parseInt(this.value);
        if (isNaN(val) || val < 1) this.value = 1;
        if (val > 10) this.value = 10;
    });

    function agregarAlCarrito() {
        let extraIds = selectedExtras.map(e => e.id);
        let cantidad = parseInt(qtyInput.value);

        let data = {
            producto_id: productId,
            cantidad: cantidad,
            id_tamano: selectedSizeId,
            extras: extraIds
        };

        fetch('index.php?controller=carrito&action=agregar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Producto agregado',
                    timer: 1200,
                    showConfirmButton: false
                });
                if (data.totalItems !== undefined) {
                    document.getElementById('cartCount').innerText = data.totalItems;
                } else {
                    actualizarContador();
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'No se pudo agregar el producto'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({ icon: 'error', title: 'Error de conexión' });
        });
    }

    function toggleNutrition() {
        const panel = document.getElementById('nutritionPanel');
        const btn = document.querySelector('.nutrition-btn');
        panel.classList.toggle('open');
        btn.classList.toggle('open');
    }

    // Inicializar
    document.addEventListener('DOMContentLoaded', function() {
        const firstSize = document.querySelector('.size-card');
        if (firstSize) selectSize(firstSize);
        // Asegurar que el selector de leche tenga el evento change
        const milkRadios = document.querySelectorAll('#milk-select input[type="radio"]');
        milkRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    const text = this.dataset.text;
                    document.querySelector('#milk-select .selected-text').innerText = text;
                    selectedMilkPriceMXN = parseFloat(this.value) || 0;
                    calculate();
                }
            });
        });
    });
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>