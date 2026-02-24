<?php $titulo = "Categorías"; ?>
<?php include __DIR__ . '/layout/header.php'; ?>

<section class="categories-full">
    <h2>Todas las categorías</h2>
    <?php if (isset($categorias) && count($categorias) > 0): ?>
        <div class="category-grid">
            <?php foreach ($categorias as $categoria): ?>
                <div class="category-card">
                    <h3><?= htmlspecialchars($categoria['nombre_categoria']) ?></h3>
                    <p><?= htmlspecialchars($categoria['descripcion'] ?? '') ?></p>
                    <a href="index.php?controller=categoria&action=show&id=<?= $categoria['id_categoria'] ?>" class="btn btn-secondary">Ver productos</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No hay categorías disponibles.</p>
    <?php endif; ?>
</section>

<?php include __DIR__ . '/layout/footer.php'; ?>