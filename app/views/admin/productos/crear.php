<?php include __DIR__ . '/../../layout/header.php'; ?>

<h2>Crear Producto</h2>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger">Error al guardar. Verifique los datos.</div>
<?php endif; ?>

<form method="POST" action="index.php?controller=adminProducto&action=guardar" enctype="multipart/form-data">
    <label>Nombre</label>
    <input type="text" name="nombre" required>
    <br><br>

    <label>Descripción</label>
    <textarea name="descripcion"></textarea>
    <br><br>

    <label>Precio</label>
    <input type="number" step="0.01" name="precio" required>
    <br><br>

    <label>Categoría</label>
    <select name="categoria" required>
        <option value="">Seleccione</option>
        <?php foreach ($categorias as $cat): ?>
            <option value="<?= $cat['id_categoria'] ?>"><?= htmlspecialchars($cat['nombre_categoria']) ?></option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <label>Imagen (opcional, máx 2MB, JPG/PNG/GIF/WEBP)</label>
    <input type="file" name="imagen" accept="image/*">
    <br><br>

    <button type="submit">Guardar producto</button>
</form>

<?php include __DIR__ . '/../../layout/footer.php'; ?>