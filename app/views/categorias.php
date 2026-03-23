<?php $titulo = "Categorías"; ?>
<?php include __DIR__ . '/layout/header.php'; ?>

<section class="categoria-detalle">
    <h2><?= htmlspecialchars($categoria['nombre_categoria']) ?></h2>
    <p><?= htmlspecialchars($categoria['descripcion'] ?? '') ?></p>

    <div class="productos-grid-cards">
        <?php if (!empty($productos)): ?>
            <?php foreach ($productos as $producto): ?>
                <div class="el-wrapper">
                    <div class="box-up">
                        <img class="img" src="/img/productos/<?= htmlspecialchars($producto['imagen'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                        <div class="img-info">
                            <div class="info-inner">
                                <span class="p-name"><?= htmlspecialchars($producto['nombre']) ?></span>
                                <span class="p-company"><?= htmlspecialchars($producto['descripcion'] ?? '') ?></span>
                            </div>
                            <div class="a-size">
                                <?= __('precio') ?>: <?= format_currency($producto['precio'] ?? 0) ?>
                            </div>
                        </div>
                    </div>

                    <div class="box-down">
                        <div class="h-bg">
                            <div class="h-bg-inner"></div>
                        </div>
                        <a class="cart" href="index.php?controller=producto&action=show&id=<?= $producto['id_producto'] ?>">
                            <span class="price"><?= format_currency($producto['precio'] ?? 0) ?></span>
                            <span class="add-to-cart">
                                <span class="txt"><?= __('ver_detalles') ?></span>
                            </span>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p><?= __('no_products') ?></p>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/layout/footer.php'; ?>