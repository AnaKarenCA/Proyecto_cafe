// Función para abrir/cerrar sidebar
function toggleCart() {
    const sidebar = document.getElementById("cartSidebar");
    if (sidebar.classList.contains("open")) {
        sidebar.classList.remove("open");
        return;
    }

    fetch("index.php?controller=carrito&action=render")
        .then(r => r.text())
        .then(html => {
            sidebar.innerHTML = html;
            sidebar.classList.add("open");
            // El botón cerrar ya tiene evento por delegación, no necesitamos asignarlo aquí
        });
}

function closeCart() {
    const sidebar = document.getElementById("cartSidebar");
    sidebar.classList.remove("open");
}

// Actualizar contador de items en el ícono del carrito
function actualizarContador() {
    fetch('index.php?controller=carrito&action=totalItems')
        .then(r => r.json())
        .then(data => {
            if (data.total !== undefined) {
                document.getElementById('cartCount').innerText = data.total;
            }
        })
        .catch(err => console.error('Error al actualizar contador', err));
}

// Función para recargar el sidebar (por ejemplo después de eliminar o actualizar)
function refreshCartSidebar() {
    if (document.getElementById('cartSidebar').classList.contains('open')) {
        fetch("index.php?controller=carrito&action=render")
            .then(r => r.text())
            .then(html => {
                document.getElementById('cartSidebar').innerHTML = html;
                // No necesitas reasignar eventos, la delegación los maneja
            });
    }
}

// Delegación de eventos para los botones del carrito (usando document)
document.addEventListener('click', function(e) {
    // Botones + y -
    if (e.target.classList.contains('qty-btn')) {
        e.preventDefault();
        const btn = e.target;
        const clave = btn.dataset.clave;
        const input = document.querySelector(`.qty-input[data-clave="${clave}"]`);
        if (!input) return;

        let cantidad = parseInt(input.value);
        if (btn.classList.contains('plus')) {
            cantidad = Math.min(cantidad + 1, 10);
        } else if (btn.classList.contains('minus')) {
            cantidad = Math.max(cantidad - 1, 1);
        } else {
            return;
        }

        fetch('index.php?controller=carrito&action=actualizarCantidad', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ clave: clave, cantidad: cantidad })
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                input.value = cantidad;
                refreshCartSidebar(); // Recarga el sidebar para mostrar nuevos totales
                actualizarContador(); // Actualiza el contador del ícono
            } else {
                Swal.fire('Error', data.message || 'No se pudo actualizar', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire('Error de conexión', '', 'error');
        });
    }

    // Botón eliminar
    if (e.target.classList.contains('remove-item-btn')) {
        e.preventDefault();
        const btn = e.target;
        const clave = btn.dataset.clave;

        Swal.fire({
            title: '¿Eliminar producto?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('index.php?controller=carrito&action=quitar&id=' + encodeURIComponent(clave))
                    .then(r => {
                        if (r.ok) {
                            refreshCartSidebar();
                            actualizarContador();
                        } else {
                            Swal.fire('Error', 'No se pudo eliminar', 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('Error de conexión', '', 'error');
                    });
            }
        });
    }
});

// Capturar formularios de agregar (welcome, categorías)
document.addEventListener('DOMContentLoaded', function() {
    const addForms = document.querySelectorAll('.add-to-cart-form');
    addForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);
            const productoId = formData.get('id');
            if (!productoId) return;

            fetch('index.php?controller=carrito&action=agregar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ producto_id: parseInt(productoId), cantidad: 1 })
            })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Producto agregado',
                        timer: 1200,
                        showConfirmButton: false
                    });
                    actualizarContador();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'No se pudo agregar'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({ icon: 'error', title: 'Error de conexión' });
            });
        });
    });

    actualizarContador();
});