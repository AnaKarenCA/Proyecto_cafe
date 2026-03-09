<?php
// $items y $total deben estar definidos por el controlador
$titulo = "Confirmar pedido";
include __DIR__ . '/layout/header.php';
?>

<div class="checkout-container">
    <h2>Confirmar pedido</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="checkout-content">
        <div class="checkout-items">
            <h3>Productos en tu carrito</h3>
            <table class="checkout-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio unit.</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nombre']) ?></td>
                            <td><?= $item['cantidad'] ?></td>
                            <td>$<?= number_format($item['precio'], 2) ?></td>
                            <td>$<?= number_format($item['precio'] * $item['cantidad'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Total:</strong></td>
                        <td><strong>$<?= number_format($total, 2) ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="checkout-form">
            <h3>Datos del pedido</h3>
            <form action="index.php?controller=carrito&action=confirmar" method="POST">
                <div class="form-group">
                    <label for="metodo_pago">Método de pago:</label>
                    <select name="metodo_pago" id="metodo_pago" required>
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta">Tarjeta de crédito/débito</option>
                        <option value="transferencia">Transferencia bancaria</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="comentarios">Comentarios adicionales:</label>
                    <textarea name="comentarios" id="comentarios" rows="4"><?= htmlspecialchars($_POST['comentarios'] ?? '') ?></textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Confirmar pedido</button>
                    <a href="index.php?controller=carrito&action=ver" class="btn btn-secondary">Volver al carrito</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>