<?php include 'layout/header.php'; ?>

    <h1 style="padding: 2rem; color: var(--color-dark-green);">
        <?= htmlspecialchars($categoria['nombre'] ?? 'Productos') ?>
    </h1>
    <div class="product-grid">
        <?php if (isset($productos) && count($productos) > 0): ?>
            <?php foreach ($productos as $p): ?>
            <div class="product-card">
                <img src="https://images.unsplash.com/photo-1541167760496-1628856ab772?w=400" alt="<?= htmlspecialchars($p['nombre']) ?>">
                <div class="product-info">
                    <div class="product-name"><?= htmlspecialchars($p['nombre']) ?></div>
                    <div class="product-price">$<?= number_format($p['precio'], 2) ?> USD</div>
                    <div class="product-desc"><?= htmlspecialchars($p['descripcion']) ?></div>
                    <div class="allergens">
                        <?php if (strpos($p['alergenos'] ?? '', 'lácteos') !== false): ?>
                            <i class="fas fa-cow" title="Lácteos"></i>
                        <?php endif; ?>
                        <?php if (strpos($p['alergenos'] ?? '', 'gluten') !== false): ?>
                            <i class="fas fa-wheat-alt" title="Gluten"></i>
                        <?php endif; ?>
                    </div>
                    <div class="add-to-cart">
                        <div class="quantity">
                            <button>-</button>
                            <span>1</span>
                            <button>+</button>
                        </div>
                        <button class="add-btn" data-id="<?= $p['id'] ?>">Agregar</button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center;">No hay productos en esta categoría.</p>
        <?php endif; ?>
    </div>

<?php include 'layout/footer.php'; ?>