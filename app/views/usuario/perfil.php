<?php $titulo = "Mi Perfil"; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="perfil-container">
    <div class="perfil-card">
        <h2>Mi Perfil</h2>

        <?php if (!empty($usuario)): ?>

            <div class="datos-usuario">
                <p><strong>Nombre:</strong> <?= htmlspecialchars($usuario['nombre'] ?? '') ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($usuario['correo'] ?? '') ?></p>
            </div>

        <?php else: ?>
            <p>Error al cargar datos del usuario.</p>
        <?php endif; ?>

        <h3 style="margin-top:40px;">Volver a pedir</h3>

        <?php if (!empty($ultimosPedidos)): ?>
            <div class="pedidos-grid">
                <?php foreach ($ultimosPedidos as $pedido): ?>
                    <div class="pedido-card">
                        <p><strong>Pedido #<?= $pedido['id'] ?? '' ?></strong></p>
                        <p>Total: $<?= number_format($pedido['total'] ?? 0, 2) ?></p>
                        <p>Fecha: <?= $pedido['fecha_pedido'] ?? '' ?></p>

                        <a href="index.php?controller=pedido&action=repetir&id=<?= $pedido['id'] ?? '' ?>" 
                           class="btn btn-primary">
                            Volver a pedir
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No tienes pedidos recientes.</p>
        <?php endif; ?>

    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>