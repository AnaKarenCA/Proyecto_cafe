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

$titulo = htmlspecialchars($producto['nombre']);
include __DIR__ . '/layout/header.php';
?>

<!-- Estilos adicionales -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
<style>
    /* Estilos para el grid de personalización */
    .custom-grid {
        display: none;
    }
    .custom-grid.show {
        display: grid;
    }
    .extra-checkboxes {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-top: 10px;
    }
    .extra-checkboxes label {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.9rem;
        cursor: pointer;
    }
    /* Otros estilos que ya tenías */
    .product-container { max-width: 1100px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1fr; gap: 40px; background: #fff; padding: 40px; border-radius: 30px; box-shadow: 0 20px 50px rgba(0,0,0,0.05); }
    @media (max-width: 850px) { .product-container { grid-template-columns: 1fr; } }
    .product-image-side { text-align: center; }
    .product-image-side img { max-width: 80%; filter: drop-shadow(0 20px 30px rgba(0,0,0,0.1)); }
    /* ... resto de tus estilos existentes ... */
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

        <!-- Acordeón de información nutricional -->
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
        <div class="base-price" id="display-price">$<?= number_format($producto['precio'], 2) ?></div>

        <?php if (!empty($tamanos)): ?>
            <span class="section-label"><?= __('cup_size') ?></span>
            <div class="size-cards-container">
                <?php foreach ($tamanos as $index => $tamano): ?>
                    <div class="size-card <?= $index == 0 ? 'active' : '' ?>"
                         data-id="<?= $tamano['id_tamano'] ?>"
                         data-incremento="<?= $tamano['incremento_precio'] ?>"
                         onclick="selectSize(this)">
                        <div class="size-card-inner">
                            <div class="size-card-front">
                                <strong><?= htmlspecialchars($tamano['nombre_tamano']) ?></strong>
                                <small><?= $tamano['nombre_tamano'] == 'Chico' ? '8oz' : ($tamano['nombre_tamano'] == 'Mediano' ? '12oz' : '16oz') ?></small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="customize-card">
<div style="display: flex; align-items: center; gap: 10px; flex-wrap: nowrap;">
    <label class="neon-checkbox" for="custom-toggle">
        <input type="checkbox" id="custom-toggle" onchange="toggleCustom()">
        <div class="neon-checkbox__frame">
            <div class="neon-checkbox__box">
                <div class="neon-checkbox__check-container">
                    <svg viewBox="0 0 24 24" class="neon-checkbox__check">
                        <path d="M3,12.5l7,7L21,5"></path>
                    </svg>
                </div>
                <div class="neon-checkbox__glow"></div>
                <div class="neon-checkbox__borders">
                    <span></span><span></span><span></span><span></span>
                </div>
            </div>
            <div class="neon-checkbox__effects">
                <div class="neon-checkbox__particles">
                    <span></span><span></span><span></span><span></span>
                    <span></span><span></span><span></span><span></span>
                    <span></span><span></span><span></span><span></span>
                </div>
                <div class="neon-checkbox__rings">
                    <div class="ring"></div>
                    <div class="ring"></div>
                    <div class="ring"></div>
                </div>
                <div class="neon-checkbox__sparks">
                    <span></span><span></span><span></span><span></span>
                </div>
            </div>
        </div>
    </label>
    <span class="checkbox-label"><?= __('customize_recipe') ?></span>
</div>

            <div class="custom-grid" id="custom-options">
                <?php if (!empty($extras)): ?>
<div style="grid-column: span 2;">
    <label class="section-label"><?= __('toppings') ?></label>
    <div class="extra-checkboxes">
        <?php foreach ($extras as $extra): ?>
        <label class="checkbox-container">
            <input type="checkbox" class="extra-checkbox" data-id="<?= $extra['id_extra'] ?>" data-precio="<?= $extra['precio_extra'] ?>" value="<?= $extra['id_extra'] ?>" onchange="calculate()">
            <span class="checkmark"></span>
            <span class="extra-label"><?= htmlspecialchars($extra['nombre_extra']) ?> (+$<?= number_format($extra['precio_extra'], 2) ?>)</span>
        </label>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

                <div style="grid-column: span 2;">
                    <label class="section-label"><?= __('milk_type') ?></label>
                    <!-- Selector personalizado de leche -->
                    <div class="custom-select" id="milk-select">
                        <div class="selected" data-default="Entera" data-almond="Almendra (+$0.80)" data-oat="Avena (+$0.80)">
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
                                <input id="milk-almond" name="milk" type="radio" value="0.8" data-text="Almendra (+$0.80)">
                                <label class="option" for="milk-almond" data-txt="Almendra (+$0.80)"></label>
                            </div>
                            <div title="oat">
                                <input id="milk-oat" name="milk" type="radio" value="0.8" data-text="Avena (+$0.80)">
                                <label class="option" for="milk-oat" data-txt="Avena (+$0.80)"></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="grid-column: span 2;">
                    <label class="section-label"><?= __('remove_ingredients') ?></label>
                    <div class="reject-checkboxes">
                        <!-- Cada checkbox con estilo reject -->
                        <div class="reject-checkbox">
                            <div class="checkbox-wrapper">
                                <input type="checkbox" id="no-cinnamon" class="ingredient-check" onchange="calculate()">
                                <label for="no-cinnamon">
                                    <div class="tick_mark">
                                        <div class="cross"></div>
                                    </div>
                                </label>
                            </div>
                            <span><?= __('no_cinnamon') ?></span>
                        </div>
                        <div class="reject-checkbox">
                            <div class="checkbox-wrapper">
                                <input type="checkbox" id="no-lactose" class="ingredient-check" onchange="calculate()">
                                <label for="no-lactose">
                                    <div class="tick_mark">
                                        <div class="cross"></div>
                                    </div>
                                </label>
                            </div>
                            <span><?= __('no_lactose') ?></span>
                        </div>
                        <div class="reject-checkbox">
                            <div class="checkbox-wrapper">
                                <input type="checkbox" id="no-sugar" class="ingredient-check" onchange="calculate()">
                                <label for="no-sugar">
                                    <div class="tick_mark">
                                        <div class="cross"></div>
                                    </div>
                                </label>
                            </div>
                            <span><?= __('no_sugar') ?></span>
                        </div>
                        <div class="reject-checkbox">
                            <div class="checkbox-wrapper">
                                <input type="checkbox" id="no-ice" class="ingredient-check" onchange="calculate()">
                                <label for="no-ice">
                                    <div class="tick_mark">
                                        <div class="cross"></div>
                                    </div>
                                </label>
                            </div>
                            <span><?= __('no_ice') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button class="btn-add-to-cart" onclick="agregarAlCarrito()">
            <span class="btn__text">Añadir al carrito</span>
            <span class="btn__icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" viewBox="0 0 24 24" stroke-width="2" stroke-linejoin="round" stroke-linecap="round" stroke="currentColor" height="24" fill="none" class="svg">
                    <line y2="19" y1="5" x2="12" x1="12"></line>
                    <line y2="12" y1="12" x2="19" x1="5"></line>
                </svg>
            </span>
        </button>
    </div>
</div>

<script>
    let productId = <?= $producto['id_producto'] ?>;
    let basePrice = <?= $producto['precio'] ?>;
    let selectedSizeId = null;
    let selectedSizeIncrement = 0;
    let selectedExtras = [];
    let selectedMilkPrice = 0; // Precio adicional de la leche

    function selectSize(el) {
        document.querySelectorAll('.size-card').forEach(c => c.classList.remove('active'));
        el.classList.add('active');
        selectedSizeId = el.dataset.id;
        selectedSizeIncrement = parseFloat(el.dataset.incremento) || 0;
        calculate();
    }

    function toggleCustom() {
        const show = document.getElementById('custom-toggle').checked;
        document.getElementById('custom-options').classList.toggle('show', show);
        calculate();
    }

    function toggleNutrition() {
        const panel = document.getElementById('nutritionPanel');
        const btn = document.querySelector('.nutrition-btn');
        panel.classList.toggle('open');
        btn.classList.toggle('open');
    }

    // Manejar selector de leche
    document.addEventListener('DOMContentLoaded', function() {
        const milkSelect = document.getElementById('milk-select');
        const selectedDiv = milkSelect.querySelector('.selected');
        const options = milkSelect.querySelectorAll('.options input[type="radio"]');

        // Actualizar texto seleccionado al hacer clic en una opción
        options.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    const text = this.dataset.text;
                    selectedDiv.querySelector('.selected-text').innerText = text;
                    selectedMilkPrice = parseFloat(this.value) || 0;
                    calculate();
                }
            });
        });

        // Opción por defecto (Entera)
        document.getElementById('milk-whole').checked = true;
        selectedMilkPrice = 0;
    });

    function calculate() {
        let total = basePrice + selectedSizeIncrement;

        // Sumar extras seleccionados
        selectedExtras = [];
        document.querySelectorAll('.extra-checkbox:checked').forEach(cb => {
            let precio = parseFloat(cb.dataset.precio) || 0;
            total += precio;
            selectedExtras.push({
                id: parseInt(cb.dataset.id),
                precio: precio
            });
        });

        // Sumar leche si personalización activa
        if (document.getElementById('custom-toggle').checked) {
            total += selectedMilkPrice;
        }

        // Ingredientes eliminados no afectan precio, pero podrían guardarse

        const final = total.toFixed(2);
        document.getElementById('display-price').innerText = '$' + final;
        document.querySelector('.btn-add-to-cart .btn__text').innerText = 'Añadir al carrito — $' + final;
    }

    function agregarAlCarrito() {
        let extraIds = selectedExtras.map(e => e.id);

        let data = {
            producto_id: productId,
            cantidad: 1,
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

    // Inicializar selección por defecto (primer tamaño)
    document.addEventListener('DOMContentLoaded', function() {
        const firstSize = document.querySelector('.size-card');
        if (firstSize) selectSize(firstSize);
    });
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>