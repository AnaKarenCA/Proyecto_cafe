<?php 
if (!isset($titulo)) $titulo = "Productos"; 
include __DIR__ . '/layout/header.php'; 
?>

<section class="products-header">
    <h2><?= htmlspecialchars($titulo) ?></h2>

    <?php if (isset($categorias) && count($categorias) > 0): ?>
        <form action="index.php" method="GET" class="filter-form">
            <input type="hidden" name="controller" value="producto">
            <input type="hidden" name="action" value="index">

            <label for="categoria">Filtrar por categoría:</label>

            <select name="categoria" id="categoria" onchange="this.form.submit()">
                <option value="">Todas las categorías</option>

                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id_categoria'] ?>"
                        <?= (isset($_GET['categoria']) && $_GET['categoria'] == $cat['id_categoria']) ? 'selected' : '' ?>>
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
                    <img src="img/<?= htmlspecialchars($producto['imagen'] ?? 'default.jpg') ?>" 
                         alt="<?= htmlspecialchars($producto['nombre']) ?>">

                    <h3><?= htmlspecialchars($producto['nombre']) ?></h3>

                    <p class="description">
                        <?= htmlspecialchars($producto['descripcion'] ?? '') ?>
                    </p>

                    <p class="price">
                        $<?= number_format($producto['precio'], 2) ?> MXN
                    </p>

                    <div class="product-actions">
                        <a href="index.php?controller=producto&action=show&id=<?= $producto['id_producto'] ?>" 
                           class="btn btn-secondary">
                           Detalles
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    <?php else: ?>
        <p>No se encontraron productos.</p>
    <?php endif; ?>
</section>

<?php include __DIR__ . '/layout/footer.php'; ?>