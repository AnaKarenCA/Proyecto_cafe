<?php include __DIR__ . '/layout/header.php'; ?>

<section class="categoria-detalle">

    <h2><?= htmlspecialchars($categoria['nombre_categoria']) ?></h2>
    <p><?= htmlspecialchars($categoria['descripcion'] ?? '') ?></p>

    <hr>

    <?php if (!empty($productos)): ?>
        <div class="productos-grid">
            <?php foreach ($productos as $producto): ?>
    <div class="product-card">

        <img src="img/productos\<?= htmlspecialchars($producto['imagen'] ?? 'default.jpg') ?>" 
             alt="<?= htmlspecialchars($producto['nombre']) ?>">

        <h3><?= htmlspecialchars($producto['nombre']) ?></h3>

        <p><?= htmlspecialchars($producto['descripcion'] ?? '') ?></p>

        <p class="price">
            $<?= number_format($producto['precio'], 2) ?> MXN
        </p>

        <div class="product-actions">
            <a href="index.php?controller=producto&action=show&id=<?= $producto['id_producto'] ?>" 
               class="btn btn-secondary">
                Ver detalles
            </a>

            <form action="index.php?controller=carrito&action=agregar" method="POST">
                <input type="hidden" name="id" value="<?= $producto['id_producto'] ?>">
                <input type="hidden" name="cantidad" value="1">
                <button type="submit" class="btn btn-primary">
                    Agregar
                </button>
            </form>
        </div>

    </div>
<?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No hay productos en esta categoría.</p>
    <?php endif; ?>

</section>

<?php include __DIR__ . '/layout/footer.php'; ?>