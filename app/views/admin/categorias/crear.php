<?php include __DIR__ . '/../../layout/header.php'; ?>

<h2>Crear Nueva Categoría</h2>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger">Error: El nombre es obligatorio.</div>
<?php endif; ?>

<form method="POST" action="index.php?controller=adminCategoria&action=guardar">
    <label>Nombre *</label>
    <input type="text" name="nombre" required>
    <br><br>

    <label>Descripción</label>
    <textarea name="descripcion" rows="3"></textarea>
    <br><br>

    <label>Icono (nombre del icono de Material Symbols)</label>
    <input type="text" name="icono" placeholder="Ej: local_cafe">
    <br><br>

    <label>Orden (para mostrar)</label>
    <input type="number" name="orden" value="0" min="0">
    <br><br>

    <button type="submit" class="btn btn-primary">Guardar categoría</button>
    <a href="index.php?controller=adminCategoria&action=index" class="btn btn-secondary">Cancelar</a>
</form>

<?php include __DIR__ . '/../../layout/footer.php'; ?>