<?php include __DIR__ . '/../../layout/header.php'; ?>

<h2>Editar Producto</h2>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger">Error al guardar. Verifique los datos.</div>
<?php endif; ?>

<form method="POST" action="index.php?controller=adminProducto&action=actualizar" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $producto['id_producto'] ?>">

    <label>Nombre</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
    <br><br>

    <label>Descripción</label>
    <textarea name="descripcion"><?= htmlspecialchars($producto['descripcion']) ?></textarea>
    <br><br>

    <label>Precio</label>
    <input type="number" step="0.01" name="precio" value="<?= $producto['precio'] ?>" required>
    <br><br>

    <label>Categoría</label>
    <select name="categoria">
        <?php foreach ($categorias as $cat): ?>
            <option value="<?= $cat['id_categoria'] ?>" <?= $cat['id_categoria'] == $producto['id_categoria'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['nombre_categoria']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <label>Imagen actual</label>
    <br>
    <?php if ($imagenExistente): ?>
        <img src="<?= $imagenWebPath . $producto['imagen'] ?>" width="120" alt="Imagen del producto">
    <?php else: ?>
        <p>Sin imagen</p>
    <?php endif; ?>
    <br><br>

    <label>Cambiar imagen (opcional, máx 2MB, JPG/PNG/GIF/WEBP)</label>
    <input type="file" name="imagen" accept="image/*">
    <br><br>

    <button type="submit">Actualizar producto</button>
</form>

<?php include __DIR__ . '/../../layout/footer.php'; ?>