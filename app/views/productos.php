<?php 
$titulo = __('productos'); 
include __DIR__ . '/layout/header.php'; 
?>

<div class="products-layout">
    <aside class="filters-sidebar">
        <h3><?= __('filter_by') ?></h3>

        <!-- Búsqueda -->
        <div class="ask-ai-wrapper">
            <div class="ai-input-container">
                <span class="underline-effect"></span>
                <span class="ripple-circle"></span>
                <span class="bg-fade"></span>
                <span class="floating-dots">
                    <span></span><span></span><span></span><span></span>
                </span>
                <input type="text" id="search-input" placeholder="<?= __('search_placeholder') ?>" class="ai-input" />
                <span class="icon-container">
                    <svg viewBox="0 0 24 24" height="24" width="24" xmlns="http://www.w3.org/2000/svg" class="ai-icon">
                        <path d="M7.94 3.078h8.11c1.37 0 2.47 0 3.34.12c.9.12 1.66.38 2.26.98s.86 1.36.98 2.26c.12.87.12 1.97.12 3.34v2.05c0 .41-.34.75-.75.75s-.75-.34-.75-.75v-2c0-1.43 0-2.44-.1-3.19c-.1-.73-.28-1.12-.56-1.4s-.66-.46-1.4-.56c-.76-.1-1.76-.1-3.19-.1H8c-1.43 0-2.44 0-3.19.1c-.73.1-1.12.28-1.4.56s-.46.67-.56 1.4c-.1.76-.1 1.76-.1 3.19s0 2.44.1 3.19c.1.73.28 1.12.56 1.4s.66.46 1.4.56c.76.1 1.76.1 3.19.1h3c.41 0 .75.34.75.75s-.34.75-.75.75H7.95c-1.37 0-2.47 0-3.34-.12c-.9-.12-1.66-.38-2.26-.98s-.86-1.36-.98-2.26c-.12-.87-.12-1.97-.12-3.34v-.11c0-1.37 0-2.47.12-3.34c.12-.9.38-1.66.98-2.26s1.36-.86 2.26-.98c.87-.12 1.97-.12 3.34-.12zm8.76 10.88l-.04.09a4.34 4.34 0 0 1-2.45 2.45l-.09.04c-1.17.46-1.17 2.12 0 2.58l.09.04c1.12.44 2.01 1.33 2.45 2.45l.04.09c.46 1.17 2.12 1.17 2.58 0l.04-.09a4.34 4.34 0 0 1 2.45-2.45l.09-.04c1.17-.46 1.17-2.12 0-2.58l-.09-.04a4.34 4.34 0 0 1-2.45-2.45l-.04-.09c-.46-1.17-2.12-1.17-2.58 0m1.29.81a5.83 5.83 0 0 0 3.06 3.06a5.83 5.83 0 0 0-3.06 3.06a5.83 5.83 0 0 0-3.06-3.06a5.83 5.83 0 0 0 3.06-3.06M6.74 8.828c0-.41-.34-.75-.75-.75s-.75.34-.75.75v2c0 .41.34.75.75.75s.75-.34.75-.75zm8.25-1.75c.41 0 .75.34.75.75v4c0 .41-.34.75-.75.75s-.75-.34-.75-.75v-4c0-.41.34-.75.75-.75m-2.25 2.25c0-.41-.34-.75-.75-.75s-.75.34-.75.75v1c0 .41.34.75.75.75s.75-.34.75-.75zm5.25-.75c.41 0 .75.34.75.75v1c0 .41-.34.75-.75.75s-.75-.34-.75-.75v-1c0-.41.34-.75.75-.75m-8.25-.75c0-.41-.34-.75-.75-.75s-.75.34-.75.75v4c0 .41.34.75.75.75s.75-.34.75-.75z" fill-rule="evenodd" fill="currentColor"></path>
                    </svg>
                </span>
            </div>
        </div>

        <!-- Filtro por categoría -->
        <div class="filter-section">
            <h4><?= __('category') ?></h4>
            <select id="category-filter" class="filter-select">
                <option value=""><?= __('all_categories') ?></option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id_categoria'] ?>"><?= htmlspecialchars($cat['nombre_categoria']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Filtro por alérgenos (exclusión) -->
        <div class="filter-section">
            <h4><?= __('allergens') ?></h4>
            <p class="filter-note"><?= __('allergen_filter_note') ?></p>
            <div class="filter-checkboxes">
                <?php foreach ($alergenos as $alergeno): ?>
                    <label class="checkbox-label">
                        <input type="checkbox" class="filter-allergen" value="<?= $alergeno['id_alergeno'] ?>">
                        <span class="custom-checkbox"></span>
                        <?= htmlspecialchars($alergeno['nombre_alergeno']) ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Filtro por clima -->
        <div class="filter-section">
            <h4><?= __('climate') ?></h4>
            <div class="filter-checkboxes">
                <?php foreach ($climas as $clima): ?>
                    <label class="checkbox-label">
                        <input type="checkbox" class="filter-climate" value="<?= $clima['id_clima'] ?>">
                        <span class="custom-checkbox"></span>
                        <?php 
                        $clima_traduccion = __('clima_' . $clima['nombre_clima']);
                        // Si la traducción falla, usar el nombre original de la BD
                        echo htmlspecialchars($clima_traduccion === 'clima_' . $clima['nombre_clima'] ? $clima['nombre_clima'] : $clima_traduccion);
                        ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Filtro por tamaño -->
        <div class="filter-section">
            <h4><?= __('size') ?></h4>
            <div class="filter-checkboxes">
                <?php foreach ($tamanos as $tamano): ?>
                    <label class="checkbox-label">
                        <input type="checkbox" class="filter-size" value="<?= $tamano['id_tamano'] ?>">
                        <span class="custom-checkbox"></span>
                        <?= htmlspecialchars($tamano['nombre_tamano']) ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <button id="reset-filters" class="btn-reset-filters"><?= __('reset_filters') ?></button>
    </aside>

    <section class="products-main">
        <?php if (isset($productos) && count($productos) > 0): ?>
            <div class="top-list" id="products-grid">
                <?php foreach ($productos as $producto): ?>
                    <div class="top-item product-card-item"
                         data-id="<?= $producto['id_producto'] ?>"
                         data-category="<?= $producto['id_categoria'] ?>"
                         data-name="<?= htmlspecialchars($producto['nombre']) ?>"
                         data-allergens="<?= $this->productoModel->getAllergenIds($producto['id_producto']) ?>"
                         data-climates="<?= $this->productoModel->getClimateIds($producto['id_producto']) ?>"
                         data-sizes="<?= $this->productoModel->getSizeIds($producto['id_producto']) ?>"
                         style="background-image: url('/img/productos/<?= htmlspecialchars($producto['imagen'] ?? 'default.jpg') ?>'); background-size: cover; background-position: center;">
                        <div class="top-item-overlay"></div>
                        <div class="top-item-content">
                            <h3><?= htmlspecialchars($producto['nombre']) ?></h3>
                            <p class="top-item-description"><?= htmlspecialchars($producto['descripcion'] ?? '') ?></p>
                            <p class="top-item-price"><?= format_currency($producto['precio'] ?? 0) ?></p>
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
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const categorySelect = document.getElementById('category-filter');
    const resetBtn = document.getElementById('reset-filters');
    const productItems = document.querySelectorAll('.product-card-item');

    const allergenCheckboxes = document.querySelectorAll('.filter-allergen');
    const climateCheckboxes = document.querySelectorAll('.filter-climate');
    const sizeCheckboxes = document.querySelectorAll('.filter-size');

    function filterProducts() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedCategory = categorySelect.value;

        const selectedAllergens = Array.from(allergenCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        const selectedClimates = Array.from(climateCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        const selectedSizes = Array.from(sizeCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        productItems.forEach(item => {
            const name = item.dataset.name.toLowerCase();
            const category = item.dataset.category;

            let matchesSearch = searchTerm === '' || name.includes(searchTerm);
            let matchesCategory = selectedCategory === '' || category === selectedCategory;

            // Filtro de alérgenos: EXCLUIR productos que contengan algún alérgeno seleccionado
            let matchesAllergens = true;
            if (selectedAllergens.length > 0) {
                const productAllergens = item.dataset.allergens ? item.dataset.allergens.split(',') : [];
                // Si el producto tiene algún alérgeno seleccionado, NO debe mostrarse
                matchesAllergens = !selectedAllergens.some(a => productAllergens.includes(a));
            }

            let matchesClimates = true;
            if (selectedClimates.length > 0) {
                const productClimates = item.dataset.climates ? item.dataset.climates.split(',') : [];
                matchesClimates = selectedClimates.some(c => productClimates.includes(c));
            }

            let matchesSizes = true;
            if (selectedSizes.length > 0) {
                const productSizes = item.dataset.sizes ? item.dataset.sizes.split(',') : [];
                matchesSizes = selectedSizes.some(s => productSizes.includes(s));
            }

            if (matchesSearch && matchesCategory && matchesAllergens && matchesClimates && matchesSizes) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterProducts);
    categorySelect.addEventListener('change', filterProducts);
    allergenCheckboxes.forEach(cb => cb.addEventListener('change', filterProducts));
    climateCheckboxes.forEach(cb => cb.addEventListener('change', filterProducts));
    sizeCheckboxes.forEach(cb => cb.addEventListener('change', filterProducts));

    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        categorySelect.value = '';
        allergenCheckboxes.forEach(cb => cb.checked = false);
        climateCheckboxes.forEach(cb => cb.checked = false);
        sizeCheckboxes.forEach(cb => cb.checked = false);
        filterProducts();
    });
});
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>