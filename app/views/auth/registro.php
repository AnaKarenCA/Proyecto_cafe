<?php include '../layout/header.php'; ?>

<div style="display: flex; justify-content: center; align-items: center; min-height: 80vh;">
    <div style="background: var(--color-card); padding: 2rem; border-radius: 15px; box-shadow: var(--shadow); width: 100%; max-width: 400px;">
        <h2 style="color: var(--color-dark-green); text-align: center;">Registrarse</h2>
        <?php if (isset($error)): ?>
            <p style="color: red; text-align: center;"><?= $error ?></p>
        <?php endif; ?>
        <form action="/registro" method="POST">
            <div class="form-group">
                <label>Nombre completo</label>
                <input type="text" name="nombre" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="submit-btn" style="width: 100%;">Registrarse</button>
            <p style="text-align: center; margin-top: 1rem;">¿Ya tienes cuenta? <a href="/login">Inicia sesión</a></p>
        </form>
    </div>
</div>

<?php include '../layout/footer.php'; ?>