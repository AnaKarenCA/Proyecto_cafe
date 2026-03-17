<?php
$items = $items ?? Carrito::obtener();
$subtotal = $subtotal ?? Carrito::subtotal();
$taxName = $taxName ?? 'IVA';
$total = $total ?? Carrito::total();
?>

<div class="cart-sidebar-content">
    <div class="cart-header">
        <h3>Mi carrito</h3>
        <button id="cartClose" class="cart-close" aria-label="Cerrar carrito">✕</button>
    </div>

    <?php if (empty($items)): ?>
        <p class="empty-cart">Tu carrito está vacío</p>
    <?php else: ?>
        <div class="cart-items">
            <?php foreach ($items as $clave => $item): ?>
                <div class="cart-item" data-key="<?= htmlspecialchars($clave) ?>">
                    <img src="/img/productos/<?= htmlspecialchars($item['imagen'] ?? 'default.jpg') ?>" width="60" height="60" alt="">
                    <div class="cart-info">
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
                        <div class="cart-quantity-control">
                            <button class="qty-btn minus" data-clave="<?= htmlspecialchars($clave) ?>">-</button>
                            <input type="number" class="qty-input" data-clave="<?= htmlspecialchars($clave) ?>" value="<?= $item['cantidad'] ?>" min="1" max="10" readonly>
                            <button class="qty-btn plus" data-clave="<?= htmlspecialchars($clave) ?>">+</button>
                            <span class="item-price">$<?= number_format($item['precio_unitario'] ?? 0, 2) ?></span>
                        </div>
                    </div>
                    <!-- Botón eliminar como botón, no como enlace -->
<button class="Btn remove-item-btn" data-clave="<?= htmlspecialchars($clave) ?>" aria-label="Eliminar">
    <div class="sign">
        <svg viewBox="0 0 16 16" class="bi bi-trash3-fill" fill="currentColor" height="18" width="18" xmlns="http://www.w3.org/2000/svg">
            <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"></path>
        </svg>
    </div>
    <div class="text">Delete</div>
</button>                </div>
            <?php endforeach; ?>
        </div>

        <hr>
        <div class="cart-summary">
            <p><strong>Subtotal:</strong> $<?= number_format($subtotal, 2) ?></p>
            <p><small><?= htmlspecialchars($taxName) ?> incluido</small></p>
            <p><strong>Total:</strong> $<?= number_format($total, 2) ?></p>
        </div>

        <div class="cart-actions">
            <a href="index.php?controller=carrito&action=ver" class="btn btn-primary">Ver carrito completo</a>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar clicks en botones + y -
    document.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const clave = this.dataset.clave;
            const input = document.querySelector(`.qty-input[data-clave="${clave}"]`);
            let cantidad = parseInt(input.value);
            if (this.classList.contains('plus')) {
                cantidad = Math.min(cantidad + 1, 10);
            } else if (this.classList.contains('minus')) {
                cantidad = Math.max(cantidad - 1, 1);
            } else {
                return;
            }

            // Actualizar servidor
            fetch('index.php?controller=carrito&action=actualizarCantidad', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ clave: clave, cantidad: cantidad })
            })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    input.value = cantidad;
                    // Recargar sidebar para reflejar nuevos totales
                    if (typeof toggleCart === 'function') {
                        toggleCart(); // Esto recarga el sidebar
                    } else {
                        location.reload();
                    }
                } else {
                    alert('Error al actualizar cantidad');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error de conexión');
            });
        });
    });

    // Manejar clicks en botones eliminar
    document.querySelectorAll('.remove-item-btn').forEach(btn => {
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
                                // Recargar el sidebar
                                if (typeof toggleCart === 'function') {
                                    toggleCart();
                                } else {
                                    location.reload();
                                }
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
});
</script>