<?php include __DIR__ . '/../../layout/header.php'; ?>

<h2>Reservas</h2>

<table border="1">

<tr>
<th>ID</th>
<th>Nombre</th>
<th>Fecha</th>
<th>Hora</th>
<th>Personas</th>
</tr>

<?php foreach ($reservas as $r): ?>

<tr>

<td><?= $r['id_reserva'] ?></td>

<td><?= $r['nombre'] ?></td>

<td><?= $r['fecha'] ?></td>

<td><?= $r['hora'] ?></td>

<td><?= $r['personas'] ?></td>

</tr>

<?php endforeach; ?>

</table>

<?php include __DIR__ . '/../../layout/footer.php'; ?>