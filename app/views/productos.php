<?php 
if (!isset($titulo)) $titulo = "Productos"; 
?>
<?php include __DIR__ . '/layout/header.php'; ?>

<section class="products-header">
    <h2><?= htmlspecialchars($titulo) ?></h2>
    <?php if (isset($categorias) && count($categorias) > 0): ?>
        <form action="index.php?controller=producto&action=index" method="GET" class="filter-form">
            <label for="categoria">Filtrar por categoría:</label>
            <select name="categoria" id="categoria" onchange="this.form.submit()">
                <option value="">Todas las categorías</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id_categoria'] ?>" <?= (isset($_GET['categoria']) && $_GET['categoria'] == $cat['id_categoria']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nombre_categoria']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    <?php endif; ?>
</section>

<section class="products-list">
    <?php if (isset($productos) && count($productos) > 0): ?>
        <div class="product-grid">
            <?php foreach ($productos as $producto): ?>
                <div class="product-card">
                    <img src="img/<?= htmlspecialchars($producto['imagen'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                    <h3><?= htmlspecialchars($producto['nombre']) ?></h3>
                    <p class="description"><?= htmlspecialchars($producto['descripcion'] ?? '') ?></p>
                    <p class="price">$<?= number_format($producto['precio'], 2) ?> MXN</p>
                    <form action="index.php?controller=carrito&action=agregar" method="POST" class="add-to-cart-form">
                        <input type="hidden" name="id" value="<?= $producto['id_producto'] ?>">
                        <label for="cantidad_<?= $producto['id_producto'] ?>" class="sr-only">Cantidad</label>
                        <input type="number" name="cantidad" id="cantidad_<?= $producto['id_producto'] ?>" value="1" min="1" max="10" class="quantity-input">
                        <button type="submit" class="btn btn-primary">Agregar al carrito</button>
                    </form>
                    <a href="index.php?controller=producto&action=show&id=<?= $producto['id_producto'] ?>" class="btn btn-secondary">Detalles</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No se encontraron productos.</p>
    <?php endif; ?>
</section>

<?php include __DIR__ . '/layout/footer.php'; ?>