<?php
// $producto debe estar definido por el controlador
$titulo = htmlspecialchars($producto['nombre']);
include __DIR__ . '/layout/header.php';
?>

<div class="product-detail-container">
    <div class="product-detail-card">
        <div class="product-detail-image">
            <img src="img/<?= htmlspecialchars($producto['imagen'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
        </div>
        <div class="product-detail-info">
            <h1><?= htmlspecialchars($producto['nombre']) ?></h1>
            <p class="product-description"><?= htmlspecialchars($producto['descripcion'] ?? 'Sin descripción') ?></p>
            <p class="product-price">$<?= number_format($producto['precio'], 2) ?> MXN</p>
            <form action="index.php?controller=carrito&action=agregar" method="POST" class="add-to-cart-form">
                <input type="hidden" name="id" value="<?= $producto['id_producto'] ?>">
                <label for="cantidad">Cantidad:</label>
                <input type="number" name="cantidad" id="cantidad" value="1" min="1" max="10" class="quantity-input">
                <button type="submit" class="btn btn-primary">Agregar al carrito</button>
            </form>
            <a href="index.php?controller=producto&action=index" class="btn btn-secondary">Volver a productos</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>