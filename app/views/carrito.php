<?php
$items = Carrito::getContenido();
$total = Carrito::total();
$numItems = Carrito::contarItems();
$iva = $total * 0.16; // Suponiendo 16% de IVA
$subtotal = $total - $iva;
?>

<aside class="cart-sidebar" id="cartSidebar" aria-label="Carrito de compras">
    <div class="cart-header">
        <h3>Tu carrito (<?= $numItems ?> producto<?= $numItems != 1 ? 's' : '' ?>)</h3>
        <button class="cart-close" id="cartClose" aria-label="Cerrar carrito">&times;</button>
    </div>

    <?php if (empty($items)): ?>
        <div class="empty-cart">
            <p>El carrito está vacío.</p>
            <a href="index.php?controller=producto&action=index" class="btn btn-secondary">Ver productos</a>
        </div>
    <?php else: ?>
        <ul class="cart-items" id="cartItemsList">
            <?php foreach ($items as $item): 
                $itemId = $item['id'];
                $itemNombre = htmlspecialchars($item['nombre']);
                $itemPrecio = number_format($item['precio'], 2);
                $itemCantidad = $item['cantidad'];
                $itemSubtotal = number_format($item['precio'] * $item['cantidad'], 2);
                $itemImagen = htmlspecialchars($item['imagen'] ?? 'default.jpg');
            ?>
                <li class="cart-item" data-id="<?= $itemId ?>">
                    <img src="img/<?= $itemImagen ?>" alt="" width="50" height="50" loading="lazy">
                    <div class="item-details">
                        <h4 class="item-name"><?= $itemNombre ?></h4>
                        <p class="item-price">$<?= $itemPrecio ?> c/u</p>
                        <div class="quantity-control">
                            <button class="qty-btn minus" aria-label="Disminuir cantidad de <?= $itemNombre ?>" data-id="<?= $itemId ?>">−</button>
                            <input type="number" class="quantity-input" value="<?= $itemCantidad ?>" min="1" max="10" data-id="<?= $itemId ?>" aria-label="Cantidad de <?= $itemNombre ?>">
                            <button class="qty-btn plus" aria-label="Aumentar cantidad de <?= $itemNombre ?>" data-id="<?= $itemId ?>">+</button>
                        </div>
                        <p class="item-subtotal">Subtotal: $<span class="subtotal-value"><?= $itemSubtotal ?></span></p>
                    </div>
                    <button class="remove-item" aria-label="Eliminar <?= $itemNombre ?>" data-id="<?= $itemId ?>">✕</button>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Resumen del pedido -->
        <div class="cart-summary">
            <div class="summary-row">
                <span>Subtotal</span>
                <span id="subtotal">$<?= number_format($subtotal, 2) ?></span>
            </div>
            <div class="summary-row">
                <span>IVA (16%)</span>
                <span id="iva">$<?= number_format($iva, 2) ?></span>
            </div>
            <div class="summary-row total">
                <span>Total</span>
                <span id="total">$<?= number_format($total, 2) ?></span>
            </div>
        </div>

        <div class="cart-actions">
            <a href="index.php?controller=carrito&action=ver" class="btn btn-secondary">Ver carrito completo</a>
            <a href="index.php?controller=carrito&action=checkout" class="btn btn-primary">Confirmar pedido</a>
        </div>
    <?php endif; ?>
</aside>

<!-- Incluir el JavaScript al final del body o en un archivo aparte -->
<script src="js/carrito.js"></script>