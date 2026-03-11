<?php include __DIR__ . '/../../layout/header.php'; ?>

<h2>Administrar Productos</h2>

<a href="index.php?controller=adminProducto&action=crear">Crear nuevo producto</a>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Imagen</th>         <!-- NUEVA COLUMNA -->
        <th>Nombre</th>
        <th>Precio</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($productos as $p): ?>
    <tr>
        <td><?= $p['id_producto'] ?></td>

        <!-- Mostrar imagen (usando $p, no $producto) -->
        <td>
            <?php if (!empty($p['imagen'])): ?>
                <img src="/img/productos/<?= htmlspecialchars($p['imagen']) ?>" 
                     width="50" alt="<?= htmlspecialchars($p['nombre']) ?>">
            <?php else: ?>
                Sin imagen
            <?php endif; ?>
        </td>

        <td><?= htmlspecialchars($p['nombre']) ?></td>

        <td>$<?= isset($p['precio']) ? number_format($p['precio'], 2) : '0.00' ?></td>

        <td>
            <a href="index.php?controller=adminProducto&action=editar&id=<?= $p['id_producto'] ?>">Editar</a>
            |
            <a href="index.php?controller=adminProducto&action=eliminar&id=<?= $p['id_producto'] ?>"
               onclick="return confirm('¿Eliminar producto?')">Eliminar</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php include __DIR__ . '/../../layout/footer.php'; ?>