<?php $titulo = "Reservar mesa"; ?>
<?php include __DIR__ . '/layout/header.php'; ?>

<div class="reserva-container">
    <h2>Reserva tu mesa</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['errores']) && is_array($_SESSION['errores'])): ?>
        <div class="alert alert-error">
            <ul>
                <?php foreach ($_SESSION['errores'] as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['errores']); ?>
    <?php endif; ?>

    <?php $old = $_SESSION['old'] ?? []; unset($_SESSION['old']); ?>

    <form action="index.php?controller=reserva&action=store" method="POST" class="reserva-form">
        <div class="form-group">
            <label for="nombre">Nombre completo:</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($old['nombre_cliente'] ?? (isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : '')) ?>" required aria-required="true">
        </div>
        <div class="form-group">
            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($old['email_cliente'] ?? '') ?>" required aria-required="true">
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" value="<?= htmlspecialchars($old['telefono_cliente'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="fecha">Fecha de la reserva:</label>
            <input type="date" id="fecha" name="fecha" value="<?= htmlspecialchars($old['fecha_reserva'] ?? '') ?>" required aria-required="true">
        </div>
        <div class="form-group">
            <label for="hora">Hora:</label>
            <input type="time" id="hora" name="hora" value="<?= htmlspecialchars($old['hora_reserva'] ?? '') ?>" required aria-required="true">
        </div>
        <div class="form-group">
            <label for="personas">Número de personas:</label>
            <input type="number" id="personas" name="personas" value="<?= htmlspecialchars($old['num_personas'] ?? 1) ?>" min="1" max="20" required aria-required="true">
        </div>
        <div class="form-group">
            <label for="comentarios">Comentarios adicionales:</label>
            <textarea id="comentarios" name="comentarios" rows="3"><?= htmlspecialchars($old['comentarios'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Enviar solicitud de reserva</button>
    </form>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>