<?php $titulo = "Bienvenido a Omnis Café"; ?>
<?php include __DIR__ . '/layout/header.php'; ?>

<!-- Carrusel estilo "slide con vista previa" -->
<section class="carousel-section">
    <div class="carousel-new">
        <div class="slides">
            <?php if (isset($carrusel_items) && count($carrusel_items) > 0): ?>
                <?php foreach ($carrusel_items as $item): ?>
                    <div class="slide-item" style="background-image: url('img/<?= htmlspecialchars($item['imagen'] ?? 'default.jpg') ?>');">
                        <div class="content">
                            <div class="name"><?= htmlspecialchars($item['nombre']) ?></div>
                            <div class="des"><?= htmlspecialchars($item['descripcion'] ?? '') ?></div>
                            <a href="index.php?controller=producto&action=show&id=<?= $item['id_producto'] ?>" class="seeMore">
                                <button>Ver detalles</button>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="slide-item" style="background-image: url('img/default.jpg');">
                    <div class="content">
                        <div class="name">Omnis Café</div>
                        <div class="des">Disfruta de nuestras especialidades</div>
                        <a href="#" class="seeMore"><button>Ver más</button></a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="carousel-controls">
            <button class="carousel-prev" aria-label="Anterior">◁</button>
            <button class="carousel-next" aria-label="Siguiente">▷</button>
        </div>
    </div>
</section>

<!-- Hero -->
<section class="hero">
    <h2>Disfruta del mejor café</h2>
    <p>Explora nuestro menú y haz tu pedido de manera fácil y accesible.</p>
</section>

<!-- Productos destacados / más vendidos -->
<section class="featured-products">
    <h2>Productos destacados</h2>
    <?php if (isset($productos_destacados) && count($productos_destacados) > 0): ?>
        <div class="product-grid">
            <?php foreach ($productos_destacados as $producto): ?>
                <div class="product-card">
                    <img src="img/<?= htmlspecialchars($producto['imagen'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                    <h3><?= htmlspecialchars($producto['nombre']) ?></h3>
                    <p class="price">$<?= number_format($producto['precio'], 2) ?> MXN</p>
                    <div class="product-actions">
                        <a href="index.php?controller=producto&action=show&id=<?= $producto['id_producto'] ?>" class="btn btn-secondary">Ver detalles</a>
                        <form action="index.php?controller=carrito&action=agregar" method="POST" class="add-to-cart-form">
                            <input type="hidden" name="id" value="<?= $producto['id_producto'] ?>">
                            <input type="hidden" name="cantidad" value="1">
                            <button type="submit" class="btn btn-primary">Agregar</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No hay productos destacados en este momento.</p>
    <?php endif; ?>
</section>

<!-- Recomendaciones por clima -->
<section class="climate-recommendations">
    <h2>Recomendaciones para hoy</h2>
    <?php if (isset($recomendaciones_clima) && count($recomendaciones_clima) > 0): ?>
        <div class="product-grid">
            <?php foreach ($recomendaciones_clima as $producto): ?>
                <div class="product-card">
                    <img src="img/<?= htmlspecialchars($producto['imagen'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                    <h3><?= htmlspecialchars($producto['nombre']) ?></h3>
                    <p class="price">$<?= number_format($producto['precio'], 2) ?> MXN</p>
                    <div class="product-actions">
                        <a href="index.php?controller=producto&action=show&id=<?= $producto['id_producto'] ?>" class="btn btn-secondary">Ver detalles</a>
                        <form action="index.php?controller=carrito&action=agregar" method="POST" class="add-to-cart-form">
                            <input type="hidden" name="id" value="<?= $producto['id_producto'] ?>">
                            <input type="hidden" name="cantidad" value="1">
                            <button type="submit" class="btn btn-primary">Agregar</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No hay recomendaciones disponibles.</p>
    <?php endif; ?>
</section>

<!-- Nuestras categorías con iconos -->
<section class="categories-summary">
    <h2>Nuestras categorías</h2>
    <?php if (isset($categorias) && count($categorias) > 0): ?>
        <div class="category-grid">
            <?php foreach ($categorias as $categoria): ?>
                <a href="index.php?controller=categoria&action=show&id=<?= $categoria['id_categoria'] ?>" class="category-card-link">
                    <div class="category-card">
                        <?php if (!empty($categoria['icono'])): ?>
                            <span class="category-icon"><?= htmlspecialchars($categoria['icono']) ?></span>
                        <?php else: ?>
                            <span class="category-icon"></span> <!-- Icono por defecto -->
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($categoria['nombre_categoria']) ?></h3>
                        <p><?= htmlspecialchars($categoria['descripcion'] ?? '') ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<!-- Incluir carrito como panel lateral -->
<?php include __DIR__ . '/carrito.php'; ?>

<?php include __DIR__ . '/layout/footer.php'; ?>