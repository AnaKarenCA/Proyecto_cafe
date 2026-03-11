<?php include __DIR__ . '/../../layout/header.php'; ?>

<h2>Pedidos</h2>

<table border="1">

<tr>
<th>ID</th>
<th>Usuario</th>
<th>Total</th>
<th>Estado</th>
</tr>

<?php foreach ($pedidos as $p): ?>

<tr>

<td><?= $p['id_pedido'] ?></td>

<td><?= $p['id_usuario'] ?></td>

<td>$<?= $p['total'] ?></td>

<td><?= $p['estado'] ?></td>

</tr>

<?php endforeach; ?>

</table>

<?php include __DIR__ . '/../../layout/footer.php'; ?>