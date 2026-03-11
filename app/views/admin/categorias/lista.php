<?php include __DIR__ . '/../../layout/header.php'; ?>

<h2>Administrar Categorías</h2>

<a href="index.php?controller=adminCategoria&action=crear" class="btn btn-primary">Crear nueva categoría</a>

<table border="1" cellpadding="10" style="margin-top:20px;">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Icono</th>
        <th>Orden</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($categorias as $cat): ?>
    <tr>
        <td><?= $cat['id_categoria'] ?></td>
        <td><?= htmlspecialchars($cat['nombre_categoria']) ?></td>
        <td><?= htmlspecialchars($cat['descripcion'] ?? '') ?></td>
        <td><?= htmlspecialchars($cat['icono'] ?? '') ?></td>
        <td><?= $cat['orden'] ?></td>
        <td>
            <a href="index.php?controller=adminCategoria&action=editar&id=<?= $cat['id_categoria'] ?>">Editar</a>
            |
            <a href="index.php?controller=adminCategoria&action=eliminar&id=<?= $cat['id_categoria'] ?>"
               onclick="return confirm('¿Eliminar esta categoría? Se eliminarán también sus productos.')">Eliminar</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php include __DIR__ . '/../../layout/footer.php'; ?>