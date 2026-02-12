<?php include 'layout/header.php'; ?>

    <!-- CARRUSEL -->
    <div class="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1200" alt="Café">
            </div>
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=1200" alt="Café y pastel">
            </div>
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1445116572660-236099ec97a0?w=1200" alt="Taza de café">
            </div>
        </div>
        <div class="carousel-controls">
            <i class="fas fa-chevron-left" onclick="moveSlide(-1)"></i>
            <i class="fas fa-chevron-right" onclick="moveSlide(1)"></i>
        </div>
    </div>

    <!-- SECCIÓN CLIMA -->
    <section style="padding: 2rem; background-color: var(--color-beige); margin: 2rem 0;">
        <h2 style="color: var(--color-dark-green); text-align: center;">☀️ Clima hoy: ¡Ideal para un frappé!</h2>
        <div class="product-grid" style="grid-template-columns: repeat(3, 1fr);">
            <div class="product-card">
                <img src="https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=400" alt="Frappé">
                <div class="product-info">
                    <div class="product-name">Frappé de Vainilla</div>
                    <div class="product-price">$6.50 USD</div>
                    <div class="add-to-cart">
                        <div class="quantity">
                            <button>-</button>
                            <span>1</span>
                            <button>+</button>
                        </div>
                        <button class="add-btn" data-id="4">Agregar</button>
                    </div>
                </div>
            </div>
            <!-- Puedes agregar más productos fijos aquí -->
        </div>
    </section>

    <!-- PRODUCTOS DESTACADOS (DESDE BD) -->
    <section style="padding: 2rem;">
        <h2 style="color: var(--color-dark-green); text-align: center;">✨ Productos Destacados</h2>
        <div class="product-grid">
            <?php if (isset($productos) && count($productos) > 0): ?>
                <?php foreach ($productos as $p): ?>
                <div class="product-card">
                    <img src="https://images.unsplash.com/photo-1541167760496-1628856ab772?w=400" alt="<?= htmlspecialchars($p['nombre']) ?>">
                    <div class="product-info">
                        <div class="product-name"><?= htmlspecialchars($p['nombre']) ?></div>
                        <div class="product-price">$<?= number_format($p['precio'], 2) ?> USD</div>
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
                <p style="text-align: center;">No hay productos destacados.</p>
            <?php endif; ?>
        </div>
    </section>

<?php include 'layout/footer.php'; ?>