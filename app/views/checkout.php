<?php
$titulo = __('checkout_titulo');
// Estas variables vienen del controlador
$subtotal = $subtotal ?? 0;
$impuestos = $impuestos ?? 0;
$total = $total ?? 0;
include __DIR__ . '/layout/header.php';
?>

<style>
    /* Estilos específicos de checkout, usando tus variables */
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

    .btn-next { background: var(--primary); color: white; border: none; padding: 15px; border-radius: 12px; width: 100%; font-size: 1rem; font-weight: 600; cursor: pointer; margin-top: 20px; }
    .btn-back { background: none; color: var(--gray); border: none; margin-top: 10px; cursor: pointer; font-size: 0.9rem; text-decoration: underline; }

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
        <div class="phase active" id="phase1">
            <h2><?= __('order_type') ?></h2>
            <div class="form-group">
                <label><?= __('how_would_you_like_order') ?></label>
                <select id="order-type" onchange="toggleDeliveryAddress()">
                    <option value="pickup"><?= __('store_pickup') ?></option>
                    <option value="delivery"><?= __('home_delivery') ?></option>
                </select>
            </div>
            <label><?= __('pickup_delivery_date_time') ?></label>
            <div class="grid-2">
                <input type="date" id="order-date">
                <div style="display: flex; gap: 5px;">
                    <input type="number" placeholder="HH" min="1" max="12" id="order-hr">
                    <input type="number" placeholder="MM" min="0" max="59" id="order-min">
                    <select id="order-ampm" style="width: 80px;">
                        <option>AM</option>
                        <option>PM</option>
                    </select>
                </div>
            </div>
            <button class="btn-next" onclick="goToStep(2)"><?= __('customer_details') ?> &rarr;</button>
        </div>

        <div class="phase" id="phase2">
            <h2><?= __('customer_details') ?></h2>
            <div class="grid-2">
                <div class="form-group"><label><?= __('first_name') ?></label><input type="text" id="fname"></div>
                <div class="form-group"><label><?= __('last_name') ?></label><input type="text" id="lname"></div>
            </div>
            <div class="form-group">
                <label><?= __('phone') ?>*</label>
                <input type="tel" id="phone" placeholder="(000) 000-0000">
            </div>
            <div class="form-group">
                <label><?= __('email') ?>*</label>
                <input type="email" id="email" placeholder="example@example.com">
            </div>

            <div id="delivery-fields" style="display:none;">
                <label><?= __('delivery_address') ?></label>
                <input type="text" placeholder="<?= __('street_address') ?>" class="form-group">
                <input type="text" placeholder="<?= __('city') ?>" class="form-group">
                <div class="grid-2">
                    <input type="text" placeholder="<?= __('state') ?>">
                    <input type="text" placeholder="<?= __('zip_code') ?>">
                </div>
            </div>

            <button class="btn-next" onclick="goToStep(3)"><?= __('payment_method') ?> &rarr;</button>
            <center><button class="btn-back" onclick="goToStep(1)"><?= __('back') ?></button></center>
        </div>

        <div class="phase" id="phase3">
            <h2><?= __('payment_method') ?></h2>
            <div class="payment-method" onclick="selectMethod('efectivo')">
                <span>💵</span> <div><strong><?= __('cash') ?></strong><br><small><?= __('pay_on_delivery_pickup') ?></small></div>
            </div>
            <div id="extra-efectivo" style="display:none; padding: 10px; background: #fafafa; border-radius: 10px;">
                <label><input type="checkbox" id="no-cambio" onchange="toggleCambio()"> <?= __('exact_payment') ?></label>
                <input type="number" id="monto-cambio" placeholder="<?= __('amount_you_will_pay') ?>" style="margin-top:10px;">
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
                <input type="text" placeholder="<?= __('card_number') ?>" maxlength="16" class="form-group">
                <div class="grid-2">
                    <input type="text" placeholder="MM/AA">
                    <input type="text" placeholder="CVC">
                </div>
            </div>

            <button class="btn-next" onclick="goToStep(4)"><?= __('submit_order') ?></button>
            <center><button class="btn-back" onclick="goToStep(2)"><?= __('back') ?></button></center>
        </div>

        <div class="phase" id="phase4">
            <h2><?= __('order_confirmed') ?></h2>
            <div class="invoice" id="invoice-content">
                <center><strong>OMNIS CAFÉ</strong></center><br>
                <p><?= __('order_for') ?>: <span id="inv-full-name"></span></p>
                <p><?= __('type') ?>: <span id="inv-type"></span></p>
                <p><?= __('date') ?>: <span id="inv-date"></span></p>
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
    // Datos del carrito (deberías pasarlos desde PHP)
    const subtotal = <?= $subtotal ?? 0 ?>;
    const impuestos = <?= $impuestos ?? 0 ?>;
    const total = <?= $total ?? 0 ?>;

    function toggleDeliveryAddress() {
        const type = document.getElementById('order-type').value;
        document.getElementById('delivery-fields').style.display = (type === 'delivery') ? 'block' : 'none';
    }

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

    function generarFactura() {
        document.getElementById('inv-full-name').innerText = document.getElementById('fname').value + " " + document.getElementById('lname').value;
        document.getElementById('inv-type').innerText = document.getElementById('order-type').value.toUpperCase();
        document.getElementById('inv-date').innerText = document.getElementById('order-date').value + " " + document.getElementById('order-hr').value + ":" + document.getElementById('order-min').value + " " + document.getElementById('order-ampm').value;
        document.getElementById('inv-subtotal').innerText = subtotal.toFixed(2);
        document.getElementById('inv-tax').innerText = impuestos.toFixed(2);
        document.getElementById('inv-total').innerText = total.toFixed(2);
    }
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>