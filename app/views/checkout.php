<?php
$titulo = __('checkout_titulo');
$subtotal = $subtotal ?? 0;
$impuestos = $impuestos ?? 0;
$total = $total ?? 0;
$usuario = $usuario ?? [];
$hoy = $hoy ?? date('Y-m-d');
include __DIR__ . '/layout/header.php';
?>

<style>
    :root {
        --primary: var(--accent-strong);
        --secondary: var(--accent-warm);
        --bg: var(--bg-light);
        --white: var(--white);
        --success: #27ae60;
        --gray: #95a5a6;
    }

    .checkout-container { max-width: 700px; margin: 20px auto; }

    .stepper { display: flex; justify-content: space-between; margin-bottom: 30px; position: relative; max-width: 400px; margin-left: auto; margin-right: auto; }
    .stepper::before { content: ""; position: absolute; top: 50%; left: 0; right: 0; height: 2px; background: #ddd; z-index: 1; }
    .step { width: 30px; height: 30px; background: #ddd; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 2; font-size: 0.8rem; font-weight: bold; color: white; transition: 0.3s; }
    .step.active { background: var(--primary); transform: scale(1.2); }
    .step.completed { background: var(--success); }

    .card { background: var(--white); padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); min-height: 500px; display: flex; flex-direction: column; }
    h2 { font-family: 'Playfair Display', serif; margin-bottom: 20px; color: var(--primary); border-bottom: 2px solid var(--bg); padding-bottom: 10px; }

    .phase { display: none; }
    .phase.active { display: block; animation: fadeIn 0.4s ease; }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
    .form-group { margin-bottom: 15px; }
    label { display: block; font-size: 0.85rem; margin-bottom: 5px; color: #555; font-weight: 600; }
    input, select, textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 10px; font-size: 0.95rem; font-family: inherit; background-color: var(--white); color: var(--text-dark); }
    input:focus { outline: none; border-color: var(--secondary); }

    /* Botones con flecha */
    .btn-next, .btn-back {
        --primary-color: var(--accent-strong);
        --secondary-color: #fff;
        --hover-color: #111;
        --arrow-width: 10px;
        --arrow-stroke: 2px;
        box-sizing: border-box;
        border: 0;
        border-radius: 20px;
        color: var(--secondary-color);
        padding: 1em 1.8em;
        background: var(--primary-color);
        display: flex;
        transition: 0.2s background;
        align-items: center;
        gap: 0.6em;
        font-weight: bold;
        cursor: pointer;
        margin-top: 20px;
        width: 100%;
        justify-content: center;
    }

    .btn-next .arrow-wrapper, .btn-back .arrow-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .btn-next .arrow, .btn-back .arrow {
        margin-top: 1px;
        width: var(--arrow-width);
        background: var(--primary-color);
        height: var(--arrow-stroke);
        position: relative;
        transition: 0.2s;
    }

    .btn-next .arrow::before {
        content: "";
        box-sizing: border-box;
        position: absolute;
        border: solid var(--secondary-color);
        border-width: 0 var(--arrow-stroke) var(--arrow-stroke) 0;
        display: inline-block;
        top: -3px;
        right: 3px;
        transition: 0.2s;
        padding: 3px;
        transform: rotate(-45deg);
    }

    .btn-back .arrow::before {
        content: "";
        box-sizing: border-box;
        position: absolute;
        border: solid var(--secondary-color);
        border-width: var(--arrow-stroke) 0 0 var(--arrow-stroke);
        display: inline-block;
        top: -3px;
        left: 3px;
        transition: 0.2s;
        padding: 3px;
        transform: rotate(-45deg);
    }

    .btn-next:hover, .btn-back:hover {
        background-color: var(--hover-color);
    }

    .btn-next:hover .arrow, .btn-back:hover .arrow {
        background: var(--secondary-color);
    }

    .btn-next:hover .arrow:before {
        right: 0;
    }

    .btn-back:hover .arrow:before {
        left: 0;
    }

    .btn-back {
        background: var(--gray);
        margin-top: 10px;
    }

    .btn-back .arrow-wrapper {
        order: -1;
    }

    /* Radios animados */
    .radio-input {
        display: flex;
        flex-direction: row;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        font-size: 16px;
        font-weight: 600;
        color: white;
        gap: 12px;
        margin-bottom: 20px;
    }

    .radio-input input[type="radio"] {
        display: none;
    }

    .radio-input label {
        display: flex;
        align-items: center;
        padding: 10px;
        border: 1px solid #ccc;
        background-color: #212121;
        border-radius: 5px;
        cursor: pointer;
        position: relative;
        transition: all 0.3s ease-in-out;
        color: white;
    }

    .radio-input label:before {
        content: "";
        display: block;
        position: absolute;
        top: 50%;
        left: 0;
        transform: translate(-50%, -50%);
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #fff;
        border: 2px solid #ccc;
        transition: all 0.3s ease-in-out;
    }

    .radio-input input[type="radio"]:checked + label:before {
        background-color: green;
        top: 0;
    }

    .radio-input input[type="radio"]:checked + label {
        background-color: royalblue;
        color: #fff;
        border-color: rgb(129, 235, 129);
        animation: radio-translate 0.5s ease-in-out;
    }

    @keyframes radio-translate {
        0% { transform: translateX(0); }
        50% { transform: translateY(-10px); }
        100% { transform: translateX(0); }
    }

    /* Estilos de métodos de pago (sin cambios) */
    .payment-method { border: 2px solid #eee; padding: 15px; border-radius: 12px; margin-bottom: 10px; cursor: pointer; display: flex; align-items: center; transition: 0.3s; }
    .payment-method.selected { border-color: var(--primary); background: #fdf5f0; }
    .copy-box { background: #f8f8f8; padding: 10px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; margin-top: 5px; font-family: monospace; border: 1px solid #eee; }
    .invoice { border: 2px dashed #ccc; padding: 20px; border-radius: 10px; font-family: monospace; background: #fff; margin-bottom: 20px; }
    .reminders { background: #fff8e1; padding: 15px; border-radius: 10px; border-left: 4px solid #ffc107; font-size: 0.85rem; color: #6d4c41; }
    .reminders li { margin-left: 20px; margin-bottom: 5px; }
</style>

<div class="checkout-container">
    <div class="stepper">
        <div class="step active" id="s1">1</div>
        <div class="step" id="s2">2</div>
        <div class="step" id="s3">3</div>
        <div class="step" id="s4">4</div>
    </div>

    <div class="card">
        <!-- Fase 1 -->
        <div class="phase active" id="phase1">
            <h2><?= __('order_type') ?></h2>
            <div class="radio-input">
                <input type="radio" name="tipo_entrega" id="pickup" value="pickup" checked>
                <label for="pickup"><?= __('store_pickup') ?></label>
                <input type="radio" name="tipo_entrega" id="delivery" value="delivery">
                <label for="delivery"><?= __('home_delivery') ?></label>
            </div>
            <div class="form-group">
                <label><?= __('pickup_delivery_date_time') ?></label>
                <div class="grid-2">
                    <input type="date" id="order-date" value="<?= $hoy ?>">
                    <div style="display: flex; gap: 5px;">
                        <input type="number" id="order-hr" placeholder="HH" min="1" max="12" value="12">
                        <input type="number" id="order-min" placeholder="MM" min="0" max="59" value="00">
                        <select id="order-ampm" style="width: 80px;">
                            <option>AM</option>
                            <option selected>PM</option>
                        </select>
                    </div>
                </div>
            </div>
            <button class="btn-next" onclick="goToStep(2)">
                <span><?= __('customer_details') ?></span>
                <div class="arrow-wrapper"><div class="arrow"></div></div>
            </button>
        </div>

        <!-- Fase 2 -->
        <div class="phase" id="phase2">
            <h2><?= __('customer_details') ?></h2>
            <div class="grid-2">
                <div class="form-group">
                    <label><?= __('first_name') ?></label>
                    <input type="text" id="fname" value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label><?= __('last_name') ?></label>
                    <input type="text" id="lname" value="<?= htmlspecialchars(($usuario['apellido_paterno'] ?? '') . ' ' . ($usuario['apellido_materno'] ?? '')) ?>">
                </div>
            </div>
            <div class="form-group">
                <label><?= __('phone') ?>*</label>
                <input type="tel" id="phone" placeholder="(000) 000-0000" value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label><?= __('email') ?>*</label>
                <input type="email" id="email" placeholder="example@example.com" value="<?= htmlspecialchars($usuario['correo'] ?? '') ?>">
            </div>

            <div id="delivery-fields" style="display:none;">
                <label><?= __('delivery_address') ?></label>
                <input type="text" placeholder="<?= __('street_address') ?>" class="form-group" id="address-street">
                <input type="text" placeholder="<?= __('city') ?>" class="form-group" id="address-city">
                <div class="grid-2">
                    <input type="text" placeholder="<?= __('state') ?>" id="address-state">
                    <input type="text" placeholder="<?= __('zip_code') ?>" id="address-zip">
                </div>
            </div>

            <button class="btn-next" onclick="goToStep(3)">
                <span><?= __('payment_method') ?></span>
                <div class="arrow-wrapper"><div class="arrow"></div></div>
            </button>
            <button class="btn-back" onclick="goToStep(1)">
                <div class="arrow-wrapper"><div class="arrow"></div></div>
                <span><?= __('back') ?></span>
            </button>
        </div>

<!-- Fase 3 -->
<div class="phase" id="phase3">
    <h2><?= __('payment_method') ?></h2>
    
    <!-- Resumen de compra (mostrar antes del pago) -->
    <div class="checkout-summary" style="background: var(--bg-surface); padding: 15px; border-radius: 10px; margin-bottom: 20px;">
        <h3>Resumen de tu compra</h3>
        <p><strong>Subtotal:</strong> $<?= number_format($subtotal, 2) ?></p>
        <p><strong><?= $taxName ?>:</strong> $<?= number_format($impuestos, 2) ?></p>
        <p><strong>Total:</strong> $<?= number_format($total, 2) ?></p>
    </div>

    <div class="payment-method" onclick="selectMethod('efectivo')">
        <span>💵</span> <div><strong><?= __('cash') ?></strong><br><small><?= __('pay_on_delivery_pickup') ?></small></div>
    </div>
    <div id="extra-efectivo" style="display:none; padding: 10px; background: #fafafa; border-radius: 10px;">
        <label><input type="checkbox" id="no-cambio" onchange="toggleCambio()"> <?= __('exact_payment') ?></label>
        <input type="number" id="monto-cambio" placeholder="<?= __('amount_you_will_pay') ?>" style="margin-top:10px;" disabled>
    </div>

    <div class="payment-method" onclick="selectMethod('transfe')">
        <span>🏛️</span> <div><strong><?= __('bank_transfer') ?></strong><br><small><?= __('instant_confirmation') ?></small></div>
    </div>
    <div id="extra-transfe" style="display:none; padding: 10px;">
        <div class="copy-box">
            <span id="clabe">CLABE: 012345678901234567</span>
            <button onclick="copyText('clabe')" style="cursor:pointer; border:none; background:var(--primary); color:white; padding:2px 8px; border-radius:4px;"><?= __('copy') ?></button>
        </div>
    </div>

    <div class="payment-method" onclick="selectMethod('tarjeta')">
        <span>💳</span> <div><strong><?= __('credit_debit_card') ?></strong><br><small><?= __('secure_transaction') ?></small></div>
    </div>
    <div id="extra-tarjeta" style="display:none; padding: 10px;">
        <input type="text" placeholder="<?= __('card_number') ?>" maxlength="16" class="form-group" id="card-number">
        <div class="grid-2">
            <input type="text" placeholder="MM/AA" id="card-expiry">
            <input type="text" placeholder="CVC" id="card-cvc">
        </div>
    </div>

    <button class="btn-next" onclick="validarYEnviar()">
        <span><?= __('submit_order') ?></span>
        <div class="arrow-wrapper"><div class="arrow"></div></div>
    </button>
    <button class="btn-back" onclick="goToStep(2)">
        <div class="arrow-wrapper"><div class="arrow"></div></div>
        <span><?= __('back') ?></span>
    </button>
</div>

        <!-- Fase 4 -->
        <div class="phase" id="phase4">
            <h2><?= __('order_confirmed') ?></h2>
            <div class="invoice" id="invoice-content">
                <center><strong>OMNIS CAFÉ</strong></center><br>
                <p><?= __('order_for') ?>: <span id="inv-full-name"></span></p>
                <p><?= __('type') ?>: <span id="inv-type"></span></p>
                <p><?= __('date') ?>: <span id="inv-date"></span></p>
                <p><?= __('order_id') ?>: <span id="inv-order-id"></span></p>
                <hr style="margin:10px 0;">
                <p><?= __('subtotal') ?>: $<span id="inv-subtotal"></span></p>
                <p><?= __('taxes_fee') ?>: $<span id="inv-tax"></span></p>
                <p><strong><?= __('total') ?>: $<span id="inv-total"></span></strong></p>
            </div>

            <div class="reminders">
                <strong><?= __('reminders') ?>:</strong>
                <ul>
                    <li><?= __('reminder_1') ?></li>
                    <li><?= __('reminder_2') ?></li>
                    <li><?= __('reminder_3') ?></li>
                </ul>
            </div>
            <button class="btn-next" onclick="location.href='/'" style="background:#444;"><?= __('back_to_home') ?></button>
        </div>
    </div>
</div>

<script>
    let metodoActual = '';
    let pedidoId = null;

    const subtotal = <?= $subtotal ?>;
    const impuestos = <?= $impuestos ?>;
    const total = <?= $total ?>;

    function toggleDeliveryAddress() {
        const type = document.querySelector('input[name="tipo_entrega"]:checked').value;
        document.getElementById('delivery-fields').style.display = (type === 'delivery') ? 'block' : 'none';
    }

    document.querySelectorAll('input[name="tipo_entrega"]').forEach(radio => {
        radio.addEventListener('change', toggleDeliveryAddress);
    });

    function goToStep(step) {
        document.querySelectorAll('.phase').forEach(p => p.classList.remove('active'));
        document.getElementById('phase' + step).classList.add('active');

        document.querySelectorAll('.step').forEach((s, idx) => {
            s.classList.remove('active', 'completed');
            if(idx + 1 === step) s.classList.add('active');
            if(idx + 1 < step) s.classList.add('completed');
        });

        if(step === 4) generarFactura();
    }

    function selectMethod(m) {
        metodoActual = m;
        document.querySelectorAll('.payment-method').forEach(el => el.classList.remove('selected'));
        event.currentTarget.classList.add('selected');
        document.getElementById('extra-efectivo').style.display = (m === 'efectivo') ? 'block' : 'none';
        document.getElementById('extra-transfe').style.display = (m === 'transfe') ? 'block' : 'none';
        document.getElementById('extra-tarjeta').style.display = (m === 'tarjeta') ? 'block' : 'none';
    }

    function toggleCambio() {
        document.getElementById('monto-cambio').disabled = document.getElementById('no-cambio').checked;
    }

    function copyText(id) {
        const text = document.getElementById(id).innerText.replace('CLABE: ', '');
        navigator.clipboard.writeText(text);
        alert("<?= __('copied') ?>");
    }

    function enviarPedido() {
        const tipoEntrega = document.querySelector('input[name="tipo_entrega"]:checked').value;
        const fecha = document.getElementById('order-date').value;
        const hora = document.getElementById('order-hr').value.padStart(2,'0') + ':' + document.getElementById('order-min').value.padStart(2,'0') + ':00';

        const nombre = document.getElementById('fname').value;
        const apellido = document.getElementById('lname').value;
        const telefono = document.getElementById('phone').value;
        const email = document.getElementById('email').value;

        let direccion = '';
        if (tipoEntrega === 'delivery') {
            direccion = document.getElementById('address-street').value + ', ' +
                       document.getElementById('address-city').value + ', ' +
                       document.getElementById('address-state').value + ' ' +
                       document.getElementById('address-zip').value;
        }

        const metodoPago = metodoActual;
        let metodoPagoDetalle = '';
        if (metodoPago === 'efectivo') {
            metodoPagoDetalle = document.getElementById('no-cambio').checked ? 'exacto' : 'con cambio';
            if (!document.getElementById('no-cambio').checked) {
                metodoPagoDetalle += ', monto: ' + document.getElementById('monto-cambio').value;
            }
        } else if (metodoPago === 'transfe') {
            metodoPagoDetalle = 'transferencia bancaria';
        } else if (metodoPago === 'tarjeta') {
            metodoPagoDetalle = 'tarjeta: ****' + document.getElementById('card-number').value.slice(-4);
        }

        const data = {
            tipo_entrega: tipoEntrega,
            fecha_entrega: fecha,
            hora_entrega: hora,
            nombre: nombre,
            apellido: apellido,
            telefono: telefono,
            email: email,
            direccion: direccion,
            metodo_pago: metodoPago,
            metodo_pago_detalle: metodoPagoDetalle
        };

        fetch('index.php?controller=checkout&action=procesar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                pedidoId = data.id_pedido;
                goToStep(4);
            } else {
                Swal.fire('Error', data.error || 'No se pudo procesar el pedido', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire('Error de conexión', '', 'error');
        });
    }

    function generarFactura() {
        document.getElementById('inv-full-name').innerText = document.getElementById('fname').value + " " + document.getElementById('lname').value;
        document.getElementById('inv-type').innerText = document.querySelector('input[name="tipo_entrega"]:checked').value.toUpperCase();
        document.getElementById('inv-date').innerText = document.getElementById('order-date').value + " " + document.getElementById('order-hr').value + ":" + document.getElementById('order-min').value + " " + document.getElementById('order-ampm').value;
        document.getElementById('inv-subtotal').innerText = subtotal.toFixed(2);
        document.getElementById('inv-tax').innerText = impuestos.toFixed(2);
        document.getElementById('inv-total').innerText = total.toFixed(2);
        if (pedidoId) {
            document.getElementById('inv-order-id').innerText = pedidoId;
        }
    }
    function toggleCambio() {
    const montoInput = document.getElementById('monto-cambio');
    montoInput.disabled = document.getElementById('no-cambio').checked;
    if (montoInput.disabled) {
        montoInput.value = '';
    }
}

function validarHorario() {
    const fecha = document.getElementById('order-date').value;
    const hora = document.getElementById('order-hr').value.padStart(2,'0');
    const min = document.getElementById('order-min').value.padStart(2,'0');
    const ampm = document.getElementById('order-ampm').value;

    // Convertir a hora 24h
    let hora24 = parseInt(hora);
    if (ampm === 'PM' && hora24 !== 12) hora24 += 12;
    if (ampm === 'AM' && hora24 === 12) hora24 = 0;

    const horaCompleta = hora24 * 100 + parseInt(min); // formato HHMM para comparar

    const horaInicio = 9 * 100;   // 9:00 AM
    const horaFin = 17 * 100;      // 5:00 PM

    if (horaCompleta < horaInicio || horaCompleta >= horaFin) {
        Swal.fire({
            title: 'Horario no disponible',
            text: 'Nuestro horario de atención es de 9:00 AM a 5:00 PM. ¿Deseas pedir para mañana?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, para mañana',
            cancelButtonText: 'Elegir otra hora'
        }).then((result) => {
            if (result.isConfirmed) {
                // Calcular fecha de mañana
                const hoy = new Date();
                const manana = new Date(hoy);
                manana.setDate(manana.getDate() + 1);
                const año = manana.getFullYear();
                const mes = (manana.getMonth() + 1).toString().padStart(2, '0');
                const dia = manana.getDate().toString().padStart(2, '0');
                document.getElementById('order-date').value = `${año}-${mes}-${dia}`;
                // Establecer hora por defecto (por ejemplo 12:00 PM)
                document.getElementById('order-hr').value = '12';
                document.getElementById('order-min').value = '00';
                document.getElementById('order-ampm').value = 'PM';
            }
        });
        return false;
    }
    return true;
}

function validarYEnviar() {
    if (validarHorario()) {
        enviarPedido();
    }
}
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>