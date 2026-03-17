<?php $titulo = "Bienvenido a Omnis Café"; ?>
<?php include __DIR__ . '/layout/header.php'; ?>

<!-- Carrusel estilo "slide con vista previa" -->
<section class="carousel-section">
    <div class="carousel-new">
        <div class="slides">
            <?php if (isset($carrusel_items) && count($carrusel_items) > 0): ?>
                <?php foreach ($carrusel_items as $item): ?>
                    <div class="slide-item" style="background-image: url('/img/productos/<?= htmlspecialchars($item['imagen'] ?? 'default.jpg') ?>');">
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
                <div class="slide-item" style="background-image: url('/img/productos/default.jpg');">
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

<!-- Productos destacados / más vendidos -->
<section class="featured-products">
    <h2>Productos destacados</h2>
    <?php if (isset($productos_destacados) && count($productos_destacados) > 0): ?>
        <div class="product-grid">
            <?php foreach ($productos_destacados as $producto): ?>
                <div class="product-card">
                    <img src="/img/productos/<?= htmlspecialchars($producto['imagen'] ?? 'default.jpg') ?>" 
                         alt="<?= htmlspecialchars($producto['nombre']) ?>">
                    <h3><?= htmlspecialchars($producto['nombre']) ?></h3>
                    <p class="price">$<?= number_format($producto['precio'] ?? 0, 2) ?> MXN</p>
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
                    <img src="/img/productos/<?= htmlspecialchars($producto['imagen'] ?? 'default.jpg') ?>" 
                         alt="<?= htmlspecialchars($producto['nombre']) ?>">
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
<section class="info-cafe">
    <h2>Información del Café</h2>
    <div class="info-grid">
        <div class="info-card">
            <span class="material-symbols-outlined">schedule</span>
            <h3>Horario</h3>
            <p>Lunes a Domingo</p>
            <p><strong>9:00 AM – 5:00 PM</strong></p>
        </div>
        <div class="info-card">
            <span class="material-symbols-outlined">table_restaurant</span>
            <h3>Mesas disponibles</h3>
            <p>10 mesas</p>
            <p>6 sillas por mesa</p>
        </div>
        <div class="info-card">
            <span class="material-symbols-outlined">event_available</span>
            <h3>Reservaciones</h3>
            <p>Reserva tu mesa fácilmente</p>
            <a href="index.php?controller=reserva&action=index" class="btn btn-primary">Reservar mesa</a>
        </div>
    </div>
</section>

<!-- Incluir carrito como panel lateral -->


<?php include __DIR__ . '/layout/footer.php'; ?>