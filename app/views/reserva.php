<?php include 'layout/header.php'; ?>

    <div class="reservation-form">
        <h2 style="color: var(--color-dark-green); margin-bottom: 1.5rem;">Reserva tu mesa</h2>
        <?php if (isset($_GET['exito'])): ?>
            <p style="color: green; text-align: center;">✅ Reserva enviada con éxito.</p>
        <?php endif; ?>
        <form action="/reserva" method="POST">
            <div class="form-group">
                <label>Fecha</label>
                <input type="date" name="fecha" required>
            </div>
            <div class="form-group">
                <label>Hora</label>
                <input type="time" name="hora" required>
            </div>
            <div class="form-group">
                <label>Número de personas</label>
                <input type="number" name="personas" min="1" max="20" required>
            </div>
            <div class="form-group">
                <label>Tipo de mesa</label>
                <select name="tipo_mesa">
                    <option value="redonda">Redonda</option>
                    <option value="cuadrada">Cuadrada</option>
                </select>
            </div>
            <div class="form-group">
                <label>Detalles adicionales</label>
                <textarea name="detalles" rows="4" placeholder="Alergias, preferencias..."></textarea>
            </div>
            <button type="submit" class="submit-btn">Enviar solicitud</button>
        </form>
    </div>

<?php include 'layout/footer.php'; ?>