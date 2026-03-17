<?php
$titulo = "Carrito de compras";
include __DIR__ . '/layout/header.php';

$items = $items ?? Carrito::obtener();
$subtotal = $subtotal ?? Carrito::subtotal();
$taxRate = $taxRate ?? 0.16;
$taxName = $taxName ?? 'IVA';
$total = $total ?? Carrito::total();
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
                    <th>Precio unit.</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $clave => $item): ?>
                    <?php
                    $subtotal_item = ($item['precio_unitario'] ?? 0) * $item['cantidad'];
                    ?>
                    <tr id="cart-row-<?= htmlspecialchars($clave) ?>" data-clave="<?= htmlspecialchars($clave) ?>">
                        <td class="cart-product-info">
                            <img src="/img/productos/<?= htmlspecialchars($item['imagen'] ?? 'default.jpg') ?>" width="50" height="50" alt="">
                            <div>
                                <strong><?= htmlspecialchars($item['nombre']) ?></strong>
                                <?php if (!empty($item['nombre_tamano'])): ?>
                                    <br><small>Tamaño: <?= htmlspecialchars($item['nombre_tamano']) ?></small>
                                <?php endif; ?>
                                <?php if (!empty($item['extras_detalle'])): ?>
                                    <br><small>Extras:
                                    <?php
                                    $nombres = array_column($item['extras_detalle'], 'nombre_extra');
                                    echo implode(', ', $nombres);
                                    ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="cart-price" data-precio="<?= $item['precio_unitario'] ?>">$<?= number_format($item['precio_unitario'] ?? 0, 2) ?></td>
                        <td>
                            <input type="number" 
                                   class="quantity-input-auto" 
                                   data-clave="<?= htmlspecialchars($clave) ?>" 
                                   value="<?= $item['cantidad'] ?>" 
                                   min="1" max="10" 
                                   style="width: 60px; padding: 5px;">
                        </td>
                        <td class="cart-subtotal">$<?= number_format($subtotal_item, 2) ?></td>
                        <td>
                            <button class="btn-remove-item" data-clave="<?= htmlspecialchars($clave) ?>">
                                <span class="text">Delete</span>
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <path d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z"></path>
                                    </svg>
                                </span>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                    <td id="cart-subtotal"><strong>$<?= number_format($subtotal, 2) ?></strong></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right"><?= htmlspecialchars($taxName) ?>:</td>
                    <td id="cart-tax">$<?= number_format($total - $subtotal, 2) ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total:</strong></td>
                    <td id="cart-total"><strong>$<?= number_format($total, 2) ?></strong></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <div class="cart-full-actions">
            <a href="index.php?controller=producto&action=index" class="btn btn-secondary">Seguir comprando</a>
            <a href="index.php?controller=checkout&action=index" id="checkout-btn" class="btn btn-primary">Proceder al pago</a>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Actualizar cantidad al cambiar el input
    const inputs = document.querySelectorAll('.quantity-input-auto');
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            const clave = this.dataset.clave;
            const nuevaCantidad = parseInt(this.value);
            if (nuevaCantidad < 1 || nuevaCantidad > 10) {
                Swal.fire({
                    icon: 'error',
                    title: 'Cantidad no válida',
                    text: 'Debe ser entre 1 y 10'
                });
                this.value = this.defaultValue;
                return;
            }

            fetch('index.php?controller=carrito&action=actualizarCantidad', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ clave: clave, cantidad: nuevaCantidad })
            })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    // Actualizar los totales en la tabla
                    document.getElementById('cart-subtotal').innerHTML = '<strong>$' + data.subtotal.toFixed(2) + '</strong>';
                    document.getElementById('cart-tax').innerText = '$' + (data.total - data.subtotal).toFixed(2);
                    document.getElementById('cart-total').innerHTML = '<strong>$' + data.total.toFixed(2) + '</strong>';
                    
                    // Actualizar el subtotal de la fila
                    const row = document.querySelector(`#cart-row-${clave}`);
                    const precio = parseFloat(row.querySelector('.cart-price').dataset.precio);
                    row.querySelector('.cart-subtotal').innerText = '$' + (precio * nuevaCantidad).toFixed(2);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'No se pudo actualizar'
                    });
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión'
                });
            });
        });
    });

    // Eliminar ítem con SweetAlert
    document.querySelectorAll('.btn-remove-item').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const clave = this.dataset.clave;

            Swal.fire({
                title: '¿Eliminar producto?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('index.php?controller=carrito&action=quitar&id=' + encodeURIComponent(clave))
                        .then(r => {
                            if (r.ok) {
                                location.reload(); // Recargar la página completa
                            } else {
                                Swal.fire('Error', 'No se pudo eliminar', 'error');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            Swal.fire('Error de conexión', '', 'error');
                        });
                }
            });
        });
    });

    // Validar sesión antes de ir a checkout
    document.getElementById('checkout-btn')?.addEventListener('click', function(e) {
        <?php if (!isset($_SESSION['usuario_id'])): ?>
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Inicia sesión',
                text: 'Debes iniciar sesión para continuar con el pago.',
                showConfirmButton: true
            }).then(() => {
                window.location.href = 'index.php?controller=auth&action=showLoginForm';
            });
        <?php endif; ?>
    });
});
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>