<?php $titulo = "Registro de usuario"; ?>
<?php include __DIR__ . '/../layout/auth_header.php'; ?>

<div class="auth-split-container registro">
    <div class="auth-image-side" style="background-image: url('img/registro-image.jpg');"></div>
    <div class="auth-form-side">
        <div class="auth-form-wrapper">
            <h2>Crear cuenta</h2>

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

            <form action="index.php?controller=auth&action=register" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="cuenta">Nombre de usuario:</label>
                    <input type="text" id="cuenta" name="cuenta" value="<?= htmlspecialchars($old['cuenta'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="nombre">Nombre(s):</label>
                    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($old['nombre'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="apellido_paterno">Apellido paterno:</label>
                    <input type="text" id="apellido_paterno" name="apellido_paterno" value="<?= htmlspecialchars($old['apellido_paterno'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="apellido_materno">Apellido materno:</label>
                    <input type="text" id="apellido_materno" name="apellido_materno" value="<?= htmlspecialchars($old['apellido_materno'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="correo">Correo electrónico:</label>
                    <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($old['correo'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="contrasena">Contraseña (mínimo 6 caracteres):</label>
                    <input type="password" id="contrasena" name="contrasena" required>
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono (opcional):</label>
                    <input type="tel" id="telefono" name="telefono" value="<?= htmlspecialchars($old['telefono'] ?? '') ?>">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
            </form>

            <p class="auth-link">¿Ya tienes cuenta? <a href="index.php?controller=auth&action=showLoginForm">Inicia sesión aquí</a></p>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/auth_footer.php'; ?>