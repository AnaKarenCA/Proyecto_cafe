<?php
// Esta vista se incluye en otras páginas para mostrar el carrito en un panel lateral.
// Se espera que la sesión ya esté iniciada.
$items = Carrito::getContenido();
$total = Carrito::total();
$numItems = Carrito::contarItems();
?>

<aside class="cart-sidebar" aria-label="Carrito de compras">
    <h3>Tu carrito (<?= $numItems ?> producto<?= $numItems != 1 ? 's' : '' ?>)</h3>
    <?php if (empty($items)): ?>
        <p>El carrito está vacío.</p>
    <?php else: ?>
        <ul class="cart-items">
            <?php foreach ($items as $item): ?>
                <li class="cart-item">
                    <img src="img/<?= htmlspecialchars($item['imagen'] ?? 'default.jpg') ?>" alt="" width="50">
                    <div class="item-info">
                        <h4><?= htmlspecialchars($item['nombre']) ?></h4>
                        <p>$<?= number_format($item['precio'], 2) ?> x <?= $item['cantidad'] ?></p>
                        <p class="item-subtotal">Subtotal: $<?= number_format($item['precio'] * $item['cantidad'], 2) ?></p>
                    </div>
                    <form action="index.php?controller=carrito&action=actualizar" method="POST" class="update-form">
                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                        <label for="cantidad_<?= $item['id'] ?>" class="sr-only">Cantidad</label>
                        <input type="number" name="cantidad" id="cantidad_<?= $item['id'] ?>" value="<?= $item['cantidad'] ?>" min="1" max="10" class="quantity-input">
                        <button type="submit" class="btn-small">Actualizar</button>
                    </form>
                    <a href="index.php?controller=carrito&action=quitar&id=<?= $item['id'] ?>" class="remove-link" aria-label="Eliminar <?= htmlspecialchars($item['nombre']) ?>">✕</a>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="cart-total">
            <strong>Total: $<?= number_format($total, 2) ?></strong>
        </div>
        <div class="cart-actions">
            <a href="index.php?controller=carrito&action=ver" class="btn btn-secondary">Ver carrito completo</a>
            <a href="index.php?controller=carrito&action=checkout" class="btn btn-primary">Proceder al pago</a>
        </div>
    <?php endif; ?>
</aside>