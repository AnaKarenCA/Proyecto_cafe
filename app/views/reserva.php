<?php
$titulo = __('reserve_table');
include __DIR__ . '/layout/header.php';

$nombre = $nombre ?? '';
$telefono = $telefono ?? '';
$email = $email ?? '';
$fecha = $fecha ?? date('Y-m-d');
$personas = $personas ?? 1;
$hoy = date('Y-m-d');
$horaActual = (int)date('H');
$esDespuesHorario = ($horaActual >= 17);
?>

<div class="reserva-container">
    <h2 class="reserva-title"><?= __('reserve_table') ?></h2>
    <p class="reserva-subtitle"><?= __('choose_date_time') ?></p>

    <div class="reserva-form-wrapper">
        <form id="reserva-form" action="index.php?controller=reserva&action=store" method="POST" class="reserva-form-modern">
            <div class="form-row">
                <div class="form-group">
                    <label for="nombre"><?= __('full_name') ?> <span class="required">*</span></label>
                    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($nombre) ?>" required>
                </div>
                <div class="form-group">
                    <label for="telefono"><?= __('phone') ?> <span class="required">*</span></label>
                    <input type="tel" id="telefono" name="telefono" value="<?= htmlspecialchars($telefono) ?>" placeholder="(555) 123-4567">
                </div>
            </div>
            <div class="form-group">
                <label for="email"><?= __('email') ?> (<?= __('optional') ?>)</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="fecha"><?= __('date') ?> <span class="required">*</span></label>
                    <input type="date" id="fecha" name="fecha" min="<?= $hoy ?>" value="<?= htmlspecialchars($fecha) ?>" required>
                </div>
                <div class="form-group">
                    <label for="personas"><?= __('guests') ?> <span class="required">*</span></label>
                    <div class="quantity-selector">
                        <button type="button" class="qty-btn" data-action="decrement">-</button>
                        <input type="number" id="personas" name="personas" value="<?= $personas ?>" min="1" max="6" step="1">
                        <button type="button" class="qty-btn" data-action="increment">+</button>
                    </div>
                    <div class="people-limit-hint" style="display: none; color: #e74c3c; font-size: 0.8rem; margin-top: 5px;"><?= __('max_6_people') ?></div>
                </div>
            </div>

            <div class="form-group">
                <label><?= __('available_hours') ?></label>
                <div id="hours-grid" class="hours-grid">
                    <div class="loading-hours"><?= __('loading_hours') ?></div>
                </div>
                <input type="hidden" id="hora" name="hora" value="">
            </div>

            <div class="form-group">
                <label for="comentarios"><?= __('special_requests') ?></label>
                <textarea id="comentarios" name="comentarios" rows="2" placeholder="<?= __('optional_requests') ?>"></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary"><?= __('book_table') ?></button>
            </div>
        </form>
    </div>
</div>

<style>
.reserva-container { max-width: 700px; margin: 0 auto; padding: 20px; }
.reserva-title { text-align: center; margin-bottom: 10px; font-size: 2rem; color: var(--text-primary); }
.reserva-subtitle { text-align: center; margin-bottom: 30px; color: var(--text-secondary); }
.reserva-form-modern { background: var(--bg-surface); padding: 30px; border-radius: var(--border-radius); box-shadow: 0 4px 20px var(--shadow); }
.form-row { display: flex; gap: 20px; margin-bottom: 20px; }
.form-group { flex: 1; }
.form-group label { display: block; margin-bottom: 5px; font-weight: 600; color: var(--text-primary); }
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid var(--accent-warm); border-radius: var(--border-radius); background: var(--bg-light); color: var(--text-primary); font-size: 1rem; }
.form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: var(--accent-strong); }
.required { color: #e74c3c; }
.quantity-selector { display: flex; align-items: center; gap: 10px; }
.quantity-selector input { width: 60px; text-align: center; padding: 8px; }
.qty-btn { width: 32px; height: 32px; background: var(--accent-warm); border: none; border-radius: 50%; cursor: pointer; color: var(--text-light); font-size: 1.2rem; }
.qty-btn:hover { background: var(--accent-strong); }
.hours-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 12px; margin-top: 10px; }
.hour-block { text-align: center; padding: 10px; border-radius: 12px; cursor: pointer; transition: all 0.2s; font-weight: 500; border: 1px solid var(--accent-warm); background: var(--bg-surface); color: var(--text-primary); }
.hour-block.available { background: #2ecc71; color: white; border-color: #2ecc71; }
.hour-block.limited { background: #f39c12; color: white; border-color: #f39c12; }
.hour-block.full { background: #e74c3c; color: white; border-color: #e74c3c; cursor: not-allowed; opacity: 0.6; }
.hour-block.selected { border: 3px solid var(--accent-strong); transform: scale(1.02); box-shadow: 0 0 0 2px var(--accent-strong); }
.hour-block.available:hover, .hour-block.limited:hover { transform: translateY(-2px); box-shadow: 0 4px 8px var(--shadow); }
.loading-hours { text-align: center; padding: 20px; color: var(--text-secondary); }
.form-actions { margin-top: 30px; text-align: center; }
.btn-primary { background: var(--accent-strong); color: var(--text-light); padding: 12px 30px; border: none; border-radius: 30px; font-size: 1.1rem; font-weight: bold; cursor: pointer; transition: background 0.3s; }
.btn-primary:hover { background: var(--accent-warm); }
@media (max-width: 768px) { .form-row { flex-direction: column; gap: 15px; } .hours-grid { grid-template-columns: repeat(auto-fill, minmax(70px, 1fr)); } }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fechaInput = document.getElementById('fecha');
    const horasGrid = document.getElementById('hours-grid');
    const horaHidden = document.getElementById('hora');
    const personasInput = document.getElementById('personas');
    const peopleLimitHint = document.querySelector('.people-limit-hint');
    const form = document.getElementById('reserva-form');

    // Control de cantidad de personas
    function updatePeopleHint() {
        let val = parseInt(personasInput.value);
        if (val === 6) {
            peopleLimitHint.style.display = 'block';
        } else {
            peopleLimitHint.style.display = 'none';
        }
    }
    personasInput.addEventListener('input', function() {
        let val = parseInt(this.value);
        if (isNaN(val)) val = 1;
        if (val > 6) val = 6;
        if (val < 1) val = 1;
        this.value = val;
        updatePeopleHint();
    });
    document.querySelector('[data-action="decrement"]').addEventListener('click', () => {
        let val = parseInt(personasInput.value);
        if (val > 1) personasInput.value = val - 1;
        updatePeopleHint();
    });
    document.querySelector('[data-action="increment"]').addEventListener('click', () => {
        let val = parseInt(personasInput.value);
        if (val < 6) personasInput.value = val + 1;
        updatePeopleHint();
    });
    updatePeopleHint();

    // Cargar disponibilidad de horas
    function loadHours() {
        const fecha = fechaInput.value;
        if (!fecha) return;

        horasGrid.innerHTML = '<div class="loading-hours"><?= __('loading_hours') ?></div>';

        fetch(`index.php?controller=reserva&action=availability&fecha=${fecha}`)
            .then(response => response.json())
            .then(data => {
                horasGrid.innerHTML = '';
                if (data.hours && data.hours.length) {
                    data.hours.forEach(hour => {
                        const block = document.createElement('div');
                        block.className = `hour-block ${hour.status}`;
                        block.textContent = hour.time;
                        block.dataset.time = hour.time;
                        block.dataset.available = hour.available;
                        if (hour.status !== 'full') {
                            block.addEventListener('click', () => {
                                document.querySelectorAll('.hour-block').forEach(b => b.classList.remove('selected'));
                                block.classList.add('selected');
                                horaHidden.value = hour.time;
                            });
                        }
                        horasGrid.appendChild(block);
                    });
                } else {
                    horasGrid.innerHTML = '<div class="loading-hours"><?= __('no_availability') ?></div>';
                }
            })
            .catch(error => {
                console.error('Error al cargar horas:', error);
                horasGrid.innerHTML = '<div class="loading-hours"><?= __('error_loading_hours') ?></div>';
            });
    }

    fechaInput.addEventListener('change', function() {
        horaHidden.value = '';
        document.querySelectorAll('.hour-block').forEach(b => b.classList.remove('selected'));
        loadHours();
    });

    // Verificar si es después del horario y ofrecer cambiar a mañana (solo si la fecha actual es hoy)
    const today = '<?= $hoy ?>';
    <?php if ($esDespuesHorario): ?>
    if (fechaInput.value === today) {
        Swal.fire({
            title: '<?= __('after_hours_title') ?>',
            text: '<?= __('after_hours_message') ?>',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: '<?= __('tomorrow') ?>',
            cancelButtonText: '<?= __('stay_today') ?>'
        }).then((result) => {
            if (result.isConfirmed) {
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                const yyyy = tomorrow.getFullYear();
                const mm = String(tomorrow.getMonth() + 1).padStart(2, '0');
                const dd = String(tomorrow.getDate()).padStart(2, '0');
                fechaInput.value = `${yyyy}-${mm}-${dd}`;
                loadHours();
            } else {
                loadHours();
            }
        });
    } else {
        loadHours();
    }
    <?php else: ?>
    loadHours();
    <?php endif; ?>

    // Envío del formulario con SweetAlert para inicio de sesión
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const nombre = document.getElementById('nombre').value.trim();
        const telefono = document.getElementById('telefono').value.trim();
        const email = document.getElementById('email').value.trim();
        const fecha = fechaInput.value;
        const hora = horaHidden.value;
        const personas = personasInput.value;

        if (!nombre) {
            Swal.fire('<?= __('error') ?>', '<?= __('name_required') ?>', 'error');
            return;
        }
        if (!hora) {
            Swal.fire('<?= __('error') ?>', '<?= __('select_hour') ?>', 'error');
            return;
        }

        const data = { nombre, telefono, email, fecha, hora, personas };

        fetch(form.action, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: '<?= __('reservation_confirmed') ?>',
                    html: `
                        <strong><?= __('name') ?>:</strong> ${result.data.nombre}<br>
                        <strong><?= __('date') ?>:</strong> ${result.data.fecha}<br>
                        <strong><?= __('time') ?>:</strong> ${result.data.hora}<br>
                        <strong><?= __('guests') ?>:</strong> ${result.data.personas}
                    `,
                    confirmButtonText: '<?= __('ok') ?>'
                }).then(() => {
                    window.location.href = 'index.php?controller=welcome&action=index';
                });
            } else if (result.redirect) {
                // Mostrar alerta preguntando si desea iniciar sesión
                Swal.fire({
                    title: '<?= __('login_required_title') ?>',
                    text: '<?= __('login_required_message') ?>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '<?= __('login') ?>',
                    cancelButtonText: '<?= __('cancel') ?>'
                }).then((action) => {
                    if (action.isConfirmed) {
                        window.location.href = result.redirect;
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '<?= __('error') ?>',
                    text: result.message || '<?= __('reservation_failed') ?>'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: '<?= __('connection_error') ?>',
                text: '<?= __('try_again') ?>'
            });
        });
    });
});
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>