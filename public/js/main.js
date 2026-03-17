// main.js - Funcionalidades globales, accesibilidad y carrito dinámico

document.addEventListener('DOMContentLoaded', function() {
    // ========== ACCESIBILIDAD ==========
    const accessibilityBtn = document.getElementById('accessibilityBtn');
    const accessibilityPanel = document.getElementById('accessibilityPanel');
    const fontIncreaseBtn = document.getElementById('fontIncrease');
    const fontDecreaseBtn = document.getElementById('fontDecrease');
    const screenReaderBtn = document.getElementById('screenReader');

    // Cargar preferencias guardadas
    (function loadPreferences() {
        let fontSize = localStorage.getItem('fontSize');
        if (fontSize) {
            document.documentElement.style.fontSize = fontSize + 'px';
        }
        // Tema oscuro - usando el switch
        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle) {
            const savedTheme = localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                document.body.classList.add('dark-mode');
                themeToggle.checked = true;
            }
        }
    })();

    // Mostrar/ocultar panel de accesibilidad
    if (accessibilityBtn && accessibilityPanel) {
        accessibilityBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const expanded = this.getAttribute('aria-expanded') === 'true' ? false : true;
            this.setAttribute('aria-expanded', expanded);
            accessibilityPanel.hidden = !expanded;
        });

        document.addEventListener('click', function(e) {
            if (!accessibilityBtn.contains(e.target) && !accessibilityPanel.contains(e.target)) {
                accessibilityBtn.setAttribute('aria-expanded', 'false');
                accessibilityPanel.hidden = true;
            }
        });
    }

    // Aumentar fuente
    if (fontIncreaseBtn) {
        fontIncreaseBtn.addEventListener('click', function() {
            let current = parseInt(localStorage.getItem('fontSize') || 16);
            if (current < 24) {
                current += 2;
                document.documentElement.style.fontSize = current + 'px';
                localStorage.setItem('fontSize', current);
            }
        });
    }

    // Disminuir fuente
    if (fontDecreaseBtn) {
        fontDecreaseBtn.addEventListener('click', function() {
            let current = parseInt(localStorage.getItem('fontSize') || 16);
            if (current > 12) {
                current -= 2;
                document.documentElement.style.fontSize = current + 'px';
                localStorage.setItem('fontSize', current);
            }
        });
    }

    // Leer pantalla con Web Speech API
    if (screenReaderBtn) {
        screenReaderBtn.addEventListener('click', function() {
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
                const mainContent = document.querySelector('.main-content');
                let text = mainContent ? mainContent.innerText : document.body.innerText;
                text = text.replace(/\s+/g, ' ').trim();
                if (text.length > 0) {
                    const utterance = new SpeechSynthesisUtterance(text);
                    utterance.lang = 'es-ES';
                    utterance.rate = 1;
                    utterance.pitch = 1;
                    utterance.volume = 1;
                    window.speechSynthesis.speak(utterance);
                } else {
                    alert('No hay contenido para leer.');
                }
            } else {
                alert('Lo sentimos, tu navegador no soporta la lectura por voz.');
            }
        });
    }

    // ========== SWITCH DE TEMA (UIVERSE) ==========
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('change', function() {
            if (this.checked) {
                document.body.classList.add('dark-mode');
                localStorage.setItem('theme', 'dark');
            } else {
                document.body.classList.remove('dark-mode');
                localStorage.setItem('theme', 'light');
            }
        });
    }

    // ========== DROPDOWN DE IDIOMAS ACCESIBLE ==========
    const languageBtn = document.querySelector('.language-btn');
    const languageMenu = document.querySelector('.language-menu');

    if (languageBtn && languageMenu) {
        languageBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const expanded = this.getAttribute('aria-expanded') === 'true' ? false : true;
            this.setAttribute('aria-expanded', expanded);
            languageMenu.hidden = !expanded;
        });

        document.addEventListener('click', function(e) {
            if (!languageBtn.contains(e.target) && !languageMenu.contains(e.target)) {
                languageBtn.setAttribute('aria-expanded', 'false');
                languageMenu.hidden = true;
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && languageMenu.hidden === false) {
                languageBtn.setAttribute('aria-expanded', 'false');
                languageMenu.hidden = true;
                languageBtn.focus();
            }
        });
    }

    // ========== TOGGLE CARRITO (ABRIR/CERRAR) ==========
    const cartToggle = document.getElementById('cartToggle');
    const cartSidebar = document.querySelector('.cart-sidebar');
    const cartClose = document.getElementById('cartClose');
    const mainContent = document.querySelector('.main-content');

    function openCart() {
        if (cartSidebar) {
            cartSidebar.classList.add('open');
            if (mainContent) mainContent.classList.add('shifted');
        }
    }

    function closeCart() {
        if (cartSidebar) {
            cartSidebar.classList.remove('open');
            if (mainContent) mainContent.classList.remove('shifted');
        }
    }

    if (cartToggle && cartSidebar) {
        cartToggle.addEventListener('click', function(e) {
            e.preventDefault();
            if (cartSidebar.classList.contains('open')) {
                closeCart();
            } else {
                openCart();
            }
        });

        if (cartClose) {
            cartClose.addEventListener('click', closeCart);
        }

        document.addEventListener('click', function(e) {
            if (!cartSidebar.contains(e.target) && !cartToggle.contains(e.target) && cartSidebar.classList.contains('open')) {
                closeCart();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && cartSidebar.classList.contains('open')) {
                closeCart();
            }
        });
    }

    // ========== ANIMACIÓN DE ENTRADA PARA PÁGINAS DE AUTENTICACIÓN ==========
    if (document.body.classList.contains('auth-page')) {
        document.body.classList.add('fade-in');
        setTimeout(() => {
            document.body.classList.remove('fade-in');
        }, 500);
    }

    // ========== CARRUSEL ==========
    (function() {
        const next = document.querySelector('.carousel-next');
        const prev = document.querySelector('.carousel-prev');
        const slides = document.querySelector('.carousel-new .slides');
        
        if (!next || !prev || !slides) return;

        function updateMainSlide() {
            const items = document.querySelectorAll('.carousel-new .slide-item');
            items.forEach((item, index) => {
                if (index === 1) {
                    item.classList.add('main');
                } else {
                    item.classList.remove('main');
                }
            });
        }

        function moveNext() {
            const items = document.querySelectorAll('.carousel-new .slide-item');
            if (items.length) {
                slides.appendChild(items[0]);
                updateMainSlide();
            }
        }

        function movePrev() {
            const items = document.querySelectorAll('.carousel-new .slide-item');
            if (items.length) {
                slides.prepend(items[items.length - 1]);
                updateMainSlide();
            }
        }

        function moveSlideToMain(targetSlide) {
            const items = Array.from(document.querySelectorAll('.carousel-new .slide-item'));
            const targetIndex = items.indexOf(targetSlide);
            if (targetIndex === -1 || targetIndex === 1) return;

            if (targetIndex < 1) {
                const steps = 1 - targetIndex;
                for (let i = 0; i < steps; i++) {
                    movePrev();
                }
            } else {
                const steps = targetIndex - 1;
                for (let i = 0; i < steps; i++) {
                    moveNext();
                }
            }
        }

        next.addEventListener('click', moveNext);
        prev.addEventListener('click', movePrev);
        updateMainSlide();

        slides.addEventListener('click', function(e) {
            const slide = e.target.closest('.slide-item');
            if (slide) {
                if (e.target.tagName === 'BUTTON' || e.target.closest('button') || e.target.tagName === 'A' || e.target.closest('a')) {
                    return;
                }
                moveSlideToMain(slide);
            }
        });
    })();

    // ========== CONFIRMACIÓN PARA ELIMINAR ==========
    const removeLinks = document.querySelectorAll('.remove-link');
    removeLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('¿Estás seguro de eliminar este producto del carrito?')) {
                e.preventDefault();
            }
        });
    });

    // ========== AUTO-OCULTAR ALERTAS ==========
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });

    // ========== CARRITO DINÁMICO (AJAX) ==========
    if (!cartSidebar) return;

    function refreshCartView() {
        fetch('index.php?controller=carrito&action=render')
            .then(response => response.text())
            .then(html => {
                const newContent = document.createElement('div');
                newContent.innerHTML = html;
                cartSidebar.innerHTML = newContent.querySelector('.cart-sidebar').innerHTML;
            })
            .catch(error => console.error('Error al refrescar el carrito:', error));
    }

    function actualizarCantidad(itemId, nuevaCantidad) {
        const formData = new FormData();
        formData.append('id', itemId);
        formData.append('cantidad', nuevaCantidad);

        fetch('index.php?controller=carrito&action=actualizarAjax', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                refreshCartView();
            } else {
                alert('Error al actualizar la cantidad');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function eliminarItem(itemId) {
        fetch(`index.php?controller=carrito&action=quitarAjax&id=${itemId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                refreshCartView();
            } else {
                alert('Error al eliminar el producto');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    cartSidebar.addEventListener('click', function(e) {
        if (e.target.classList.contains('qty-btn') && e.target.classList.contains('minus')) {
            e.preventDefault();
            const itemId = e.target.dataset.id;
            const input = cartSidebar.querySelector(`.quantity-input[data-id="${itemId}"]`);
            if (input) {
                let newVal = parseInt(input.value) - 1;
                if (newVal >= 1) {
                    input.value = newVal;
                    actualizarCantidad(itemId, newVal);
                }
            }
        }
        else if (e.target.classList.contains('qty-btn') && e.target.classList.contains('plus')) {
            e.preventDefault();
            const itemId = e.target.dataset.id;
            const input = cartSidebar.querySelector(`.quantity-input[data-id="${itemId}"]`);
            if (input) {
                let newVal = parseInt(input.value) + 1;
                if (newVal <= 10) {
                    input.value = newVal;
                    actualizarCantidad(itemId, newVal);
                }
            }
        }
        else if (e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
            e.preventDefault();
            const btn = e.target.closest('.remove-item');
            if (btn && confirm('¿Eliminar este producto del carrito?')) {
                const itemId = btn.dataset.id;
                eliminarItem(itemId);
            }
        }
    });

    cartSidebar.addEventListener('change', function(e) {
        if (e.target.classList.contains('quantity-input')) {
            const input = e.target;
            const itemId = input.dataset.id;
            let newVal = parseInt(input.value);
            if (isNaN(newVal) || newVal < 1) newVal = 1;
            if (newVal > 10) newVal = 10;
            input.value = newVal;
            actualizarCantidad(itemId, newVal);
        }
    });
    
});