<?php $titulo = "Iniciar sesión"; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="auth-container">
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
            <input type="text" id="cuenta" name="cuenta" required aria-required="true">
        </div>
        <div class="form-group">
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required aria-required="true">
        </div>
        <button type="submit" class="btn btn-primary">Iniciar sesión</button>
    </form>

    <p class="auth-link">¿No tienes cuenta? <a href="index.php?controller=auth&action=showRegisterForm">Regístrate aquí</a></p>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>