// ---------- CARRUSEL ----------
let currentSlide = 0;
const slides = document.querySelectorAll('.carousel-item');
const totalSlides = slides.length;

function moveSlide(direction) {
    currentSlide += direction;
    if (currentSlide < 0) currentSlide = totalSlides - 1;
    if (currentSlide >= totalSlides) currentSlide = 0;
    document.querySelector('.carousel-inner').style.transform = `translateX(-${currentSlide * 100}%)`;
}

// ---------- CARRITO LATERAL ----------
const cartIcon = document.querySelector('.cart-icon');
const cartSidebar = document.querySelector('.cart-sidebar');
const closeCart = document.querySelector('.close-cart');

if (cartIcon) {
    cartIcon.addEventListener('click', () => {
        cartSidebar.classList.add('open');
        actualizarCarrito();
    });
}
if (closeCart) {
    closeCart.addEventListener('click', () => {
        cartSidebar.classList.remove('open');
    });
}

// ---------- AGREGAR AL CARRITO (AJAX) ----------
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('add-btn')) {
        e.preventDefault();
        const btn = e.target;
        const productId = btn.dataset.id;
        const card = btn.closest('.product-card');
        const quantityElem = card.querySelector('.qty');
        const quantity = quantityElem ? parseInt(quantityElem.innerText) : 1;

        fetch('/carrito/agregar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `producto_id=${productId}&cantidad=${quantity}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                card.classList.add('added-animation');
                setTimeout(() => card.classList.remove('added-animation'), 500);
                actualizarCarrito();
            } else if (data.error === 'No autenticado') {
                alert('Debes iniciar sesión para agregar productos al carrito');
                window.location.href = '/login';
            }
        });
    }
});

// ---------- ACTUALIZAR CARRITO ----------
function actualizarCarrito() {
    fetch('/carrito/obtener')
        .then(res => res.json())
        .then(items => {
            const cartContainer = document.querySelector('.cart-items');
            const subtotalSpan = document.getElementById('cart-subtotal');
            const ivaSpan = document.getElementById('cart-iva');
            const totalSpan = document.getElementById('cart-total');
            if (!cartContainer) return;

            let html = '';
            let subtotal = 0;
            items.forEach(item => {
                subtotal += item.precio * item.cantidad;
                html += `
                    <div class="cart-item" data-item-id="${item.id}">
                        <img src="https://images.unsplash.com/photo-1541167760496-1628856ab772?w=100" alt="${item.nombre}">
                        <div class="cart-item-details">
                            <div class="cart-item-name">${item.nombre}</div>
                            <div class="cart-item-price">$${item.precio.toFixed(2)}</div>
                            <div class="cart-item-quantity">
                                <button class="qty-minus" data-item-id="${item.id}">-</button>
                                <span class="qty">${item.cantidad}</span>
                                <button class="qty-plus" data-item-id="${item.id}">+</button>
                                <i class="fas fa-trash remove-item" data-item-id="${item.id}"></i>
                            </div>
                        </div>
                    </div>
                `;
            });
            cartContainer.innerHTML = html || '<p style="text-align:center;">Carrito vacío</p>';
            const iva = subtotal * 0.16;
            const total = subtotal + iva;
            if (subtotalSpan) subtotalSpan.innerText = `$${subtotal.toFixed(2)}`;
            if (ivaSpan) ivaSpan.innerText = `$${iva.toFixed(2)}`;
            if (totalSpan) totalSpan.innerText = `$${total.toFixed(2)}`;
        });
}

// ---------- MODIFICAR CANTIDAD / ELIMINAR DESDE CARRITO ----------
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('qty-plus')) {
        const itemId = e.target.dataset.itemId;
        const qtySpan = e.target.parentElement.querySelector('.qty');
        let qty = parseInt(qtySpan.innerText);
        qty++;
        fetch('/carrito/actualizar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `item_id=${itemId}&cantidad=${qty}`
        }).then(() => actualizarCarrito());
    }

    if (e.target.classList.contains('qty-minus')) {
        const itemId = e.target.dataset.itemId;
        const qtySpan = e.target.parentElement.querySelector('.qty');
        let qty = parseInt(qtySpan.innerText);
        if (qty > 1) {
            qty--;
            fetch('/carrito/actualizar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `item_id=${itemId}&cantidad=${qty}`
            }).then(() => actualizarCarrito());
        }
    }

    if (e.target.classList.contains('remove-item')) {
        const itemId = e.target.dataset.itemId;
        fetch('/carrito/eliminar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `item_id=${itemId}`
        }).then(() => actualizarCarrito());
    }
});

// ---------- ACCESIBILIDAD ----------
const accessBtn = document.querySelector('.accessibility-btn');
const accessMenu = document.querySelector('.accessibility-menu');

if (accessBtn) {
    accessBtn.addEventListener('click', () => {
        accessMenu.classList.toggle('show');
    });
}

document.addEventListener('click', (e) => {
    if (accessMenu && accessMenu.classList.contains('show')) {
        if (!accessMenu.contains(e.target) && !accessBtn.contains(e.target)) {
            accessMenu.classList.remove('show');
        }
    }
});

// Dark Mode
document.getElementById('darkModeToggle')?.addEventListener('click', function() {
    document.body.classList.toggle('dark-mode');
    const icon = document.querySelector('.accessibility-btn i');
    if (document.body.classList.contains('dark-mode')) {
        icon.classList.remove('fa-universal-access');
        icon.classList.add('fa-sun');
    } else {
        icon.classList.remove('fa-sun');
        icon.classList.add('fa-universal-access');
    }
});

// Aumentar fuente
let fontSize = 16;
document.getElementById('increaseFont')?.addEventListener('click', function() {
    fontSize += 2;
    if (fontSize > 30) fontSize = 30;
    document.documentElement.style.fontSize = fontSize + 'px';
});

// Leer pantalla
document.getElementById('readScreen')?.addEventListener('click', function() {
    const text = document.body.innerText;
    const utterance = new SpeechSynthesisUtterance(text);
    utterance.lang = 'es-ES';
    window.speechSynthesis.cancel();
    window.speechSynthesis.speak(utterance);
});

// Inicializar carrito si existe
if (document.querySelector('.cart-sidebar')) {
    actualizarCarrito();
}