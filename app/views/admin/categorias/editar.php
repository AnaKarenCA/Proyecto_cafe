<?php include __DIR__ . '/../../layout/header.php'; ?>

<h2>Editar Categoría</h2>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger">Error: El nombre es obligatorio.</div>
<?php endif; ?>

<form method="POST" action="index.php?controller=adminCategoria&action=actualizar">
    <input type="hidden" name="id" value="<?= $categoria['id_categoria'] ?>">

    <label>Nombre *</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($categoria['nombre_categoria']) ?>" required>
    <br><br>

    <label>Descripción</label>
    <textarea name="descripcion" rows="3"><?= htmlspecialchars($categoria['descripcion'] ?? '') ?></textarea>
    <br><br>

    <label>Icono</label>
    <input type="text" name="icono" value="<?= htmlspecialchars($categoria['icono'] ?? '') ?>">
    <br><br>

    <label>Orden</label>
    <input type="number" name="orden" value="<?= $categoria['orden'] ?>" min="0">
    <br><br>

    <button type="submit" class="btn btn-primary">Actualizar categoría</button>
    <a href="index.php?controller=adminCategoria&action=index" class="btn btn-secondary">Cancelar</a>
</form>

<?php include __DIR__ . '/../../layout/footer.php'; ?>