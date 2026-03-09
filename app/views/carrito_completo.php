<?php
$titulo = "Carrito de compras";
include __DIR__ . '/layout/header.php';
$items = Carrito::getContenido();
$total = Carrito::total();
?>

<div class="cart-full-container">
    <h1>Carrito de compras</h1>
    <?php if (empty($items)): ?>
        <div class="empty-cart-full">
            <p>Tu carrito está vacío.</p>
            <a href="index.php?controller=producto&action=index" class="btn btn-primary">Ver productos</a>
        </div>
    <?php else: ?>
        <table class="cart-full-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td class="cart-product-info">
                            <img src="img/<?= htmlspecialchars($item['imagen'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($item['nombre']) ?>" width="50" height="50">
                            <span><?= htmlspecialchars($item['nombre']) ?></span>
                        </td>
                        <td>$<?= number_format($item['precio'], 2) ?></td>
                        <td>
                            <form action="index.php?controller=carrito&action=actualizar" method="POST" class="update-form-inline">
                                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                <input type="number" name="cantidad" value="<?= $item['cantidad'] ?>" min="1" max="10" class="quantity-input">
                                <button type="submit" class="btn-small">Actualizar</button>
                            </form>
                        </td>
                        <td>$<?= number_format($item['precio'] * $item['cantidad'], 2) ?></td>
                        <td>
                            <a href="index.php?controller=carrito&action=quitar&id=<?= $item['id'] ?>" class="btn-remove">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total:</strong></td>
                    <td><strong>$<?= number_format($total, 2) ?></strong></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        <div class="cart-full-actions">
            <a href="index.php?controller=producto&action=index" class="btn btn-secondary">Seguir comprando</a>
            <a href="index.php?controller=carrito&action=checkout" class="btn btn-primary">Proceder al pago</a>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>