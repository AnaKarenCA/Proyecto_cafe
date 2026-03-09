<?php $titulo = "Mis reservas"; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="perfil-container">

    <div class="perfil-card">
        <h2>Mis reservas</h2>

        <?php if (empty($reservas)): ?>
            <div class="alerta-vacia">
                No tienes reservas registradas.
            </div>
        <?php else: ?>

            <div class="tabla-wrapper">
                <table class="tabla-pedidos">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Personas</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservas as $r): ?>
                            <tr>
                                <td>#<?= $r['id_reserva'] ?></td>
                                <td><?= date('d/m/Y', strtotime($r['fecha'])) ?></td>
                                <td><?= date('H:i', strtotime($r['hora'])) ?></td>
                                <td><?= $r['personas'] ?></td>
                                <td>
                                    <span class="estado <?= $r['estado'] ?>">
                                        <?= ucfirst($r['estado']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>

    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>