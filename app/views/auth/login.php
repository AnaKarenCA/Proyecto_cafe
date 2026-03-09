<?php $titulo = "Iniciar sesión"; ?>
<?php include __DIR__ . '/../layout/auth_header.php'; ?>

<div class="auth-split-container">
    <div class="auth-form-side">
        <div class="auth-form-wrapper">
            <h2>Iniciar sesión</h2>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <form action="index.php?controller=auth&action=login" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="cuenta">Usuario o email:</label>
                    <input type="text" id="cuenta" name="cuenta" required>
                </div>
                <div class="form-group">
                    <label for="contrasena">Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Iniciar sesión</button>
            </form>

            <p class="auth-link">¿No tienes cuenta? <a href="index.php?controller=auth&action=showRegisterForm">Regístrate aquí</a></p>
        </div>
    </div>
    <div class="auth-image-side" style="background-image: url('img/login-image.jpg');"></div>
</div>

<?php include __DIR__ . '/../layout/auth_footer.php'; ?>