<?php $titulo = "Mis pedidos"; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="perfil-container">

    <div class="perfil-card">
        <h2>Mis pedidos</h2>

        <?php if (empty($pedidos)): ?>
            <div class="alerta-vacia">
                No tienes pedidos registrados.
            </div>
        <?php else: ?>

            <div class="tabla-wrapper">
                <table class="tabla-pedidos">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Método de pago</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $p): ?>
                            <tr>
                                <td>#<?= $p['id_pedido'] ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($p['fecha_pedido'])) ?></td>
                                <td class="total">$<?= number_format($p['total'], 2) ?></td>
                                <td>
                                    <span class="estado <?= $p['estado'] ?>">
                                        <?= ucfirst($p['estado']) ?>
                                    </span>
                                </td>
                                <td><?= ucfirst($p['metodo_pago']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>

    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>