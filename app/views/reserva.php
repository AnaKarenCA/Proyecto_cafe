<?php $titulo = "Reservar mesa"; ?>
<?php include __DIR__ . '/layout/header.php'; ?>

<div class="reserva-container">

    <h2>Reserva tu mesa</h2>

    <!-- SELECTOR DE FECHA (AUTO ENVÍO) -->
    <form id="formFecha" method="GET" style="margin-bottom:20px;">
        <input type="hidden" name="controller" value="reserva">
        <input type="hidden" name="action" value="index">

        <label>Seleccionar fecha:</label>
        <input type="date"
               name="fecha"
               value="<?= htmlspecialchars($fecha) ?>"
               min="<?= date('Y-m-d') ?>"
               onchange="document.getElementById('formFecha').submit();"
               required>
    </form>

    <h3>Disponibilidad para <?= htmlspecialchars($fecha) ?></h3>

    <div class="semaforo-container">

    <?php
    // HORARIO REAL DEL CAFÉ
    $horaInicio = 9;
    $horaFin = 17;
    $maxMesas = 10;

    $hoy = date('Y-m-d');
    $horaActual = date('H:i');

    for ($h = $horaInicio; $h < $horaFin; $h++):

        $hora = str_pad($h, 2, "0", STR_PAD_LEFT) . ":00";

        // BLOQUEAR HORAS PASADAS SI ES HOY
        $esPasada = ($fecha === $hoy && $hora <= $horaActual);

        $ocupadas = $horasOcupadas[$hora] ?? 0;
        $disponibles = $maxMesas - $ocupadas;

        if ($esPasada) {
            $color = "gris";
            $texto = "Hora pasada";
        } elseif ($disponibles <= 0) {
            $color = "rojo";
            $texto = "Sin disponibilidad";
        } elseif ($disponibles <= 3) {
            $color = "amarillo";
            $texto = "$disponibles mesas disponibles";
        } else {
            $color = "verde";
            $texto = "Disponible ($disponibles)";
        }
    ?>

        <div class="bloque-hora <?= $color ?>"
             data-hora="<?= $hora ?>"
             data-fecha="<?= $fecha ?>"
             <?= (!$esPasada && $color !== 'rojo') ? 'onclick="seleccionarHora(this)"' : '' ?>>

            <div class="hora"><?= $hora ?></div>
            <div class="estado"><?= $texto ?></div>

        </div>

    <?php endfor; ?>
    </div>

    <?php $old = $_SESSION['old'] ?? []; unset($_SESSION['old']); ?>

    <!-- FORMULARIO -->
    <form action="index.php?controller=reserva&action=store"
          method="POST"
          class="reserva-form">

        <div class="form-group">
            <label>Nombre completo:</label>
            <input type="text"
                   name="nombre"
                   value="<?= htmlspecialchars($_SESSION['usuario_nombre'] ?? '') ?>"
                   required>
        </div>

        <div class="form-group">
            <label>Correo electrónico:</label>
            <input type="email"
                   name="email"
                   required>
        </div>

        <div class="form-group">
            <label>Fecha:</label>
            <input type="date"
                   id="fechaReserva"
                   name="fecha"
                   value="<?= htmlspecialchars($fecha) ?>"
                   required>
        </div>

        <div class="form-group">
            <label>Hora:</label>
            <input type="time"
                   id="hora"
                   name="hora"
                   required>
        </div>

        <div class="form-group">
            <label>Número de personas:</label>
            <input type="number"
                   name="personas"
                   value="1"
                   min="1"
                   max="6"
                   required>
        </div>

        <button type="submit" class="btn btn-primary">
            Confirmar reserva
        </button>

    </form>

</div>

<script>
function seleccionarHora(elemento) {

    const hora = elemento.dataset.hora;
    const fecha = elemento.dataset.fecha;

    document.getElementById("hora").value = hora;
    document.getElementById("fechaReserva").value = fecha;

    document.querySelector(".reserva-form").scrollIntoView({
        behavior: "smooth"
    });

    document.querySelectorAll(".bloque-hora").forEach(b => {
        b.classList.remove("seleccionado");
    });

    elemento.classList.add("seleccionado");
}
</script>
<?php if (isset($_SESSION['reserva_success'])): 
    $data = $_SESSION['reserva_success'];
    unset($_SESSION['reserva_success']);
?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
        icon: 'success',
        title: '¡Reserva Confirmada! ☕',
        html: `
            <b>Nombre:</b> <?= htmlspecialchars($data['nombre']) ?><br>
            <b>Fecha:</b> <?= htmlspecialchars($data['fecha']) ?><br>
            <b>Hora:</b> <?= htmlspecialchars($data['hora']) ?><br>
            <b>Personas:</b> <?= htmlspecialchars($data['personas']) ?>
        `,
        confirmButtonText: 'Perfecto',
        confirmButtonColor: '#6f4e37',
        background: '#fffaf5'
    });
});
</script>
<?php endif; ?>

<?php if (isset($_SESSION['reserva_error'])): 
    $error = $_SESSION['reserva_error'];
    unset($_SESSION['reserva_error']);
?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        html: '<?= $error ?>',
        confirmButtonColor: '#c0392b'
    });
});
</script>
<?php endif; ?>
<?php include __DIR__ . '/layout/footer.php'; ?>