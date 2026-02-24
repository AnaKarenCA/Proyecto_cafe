// main.js - Funcionalidades globales y accesibilidad

document.addEventListener('DOMContentLoaded', function() {
    // ========== ACCESIBILIDAD ==========
    const accessibilityBtn = document.getElementById('accessibilityBtn');
    const accessibilityPanel = document.getElementById('accessibilityPanel');
    const fontIncreaseBtn = document.getElementById('fontIncrease');
    const fontDecreaseBtn = document.getElementById('fontDecrease');
    const darkModeToggle = document.getElementById('darkModeToggle');
    const screenReaderBtn = document.getElementById('screenReader');

    // Cargar preferencias guardadas
    (function loadPreferences() {
        // Tamaño de fuente
        let fontSize = localStorage.getItem('fontSize');
        if (fontSize) {
            document.documentElement.style.fontSize = fontSize + 'px';
        }
        // Modo oscuro
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode');
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

        // Cerrar al hacer clic fuera
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

    // Alternar modo oscuro/claro
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            const isDarkMode = document.body.classList.contains('dark-mode');
            localStorage.setItem('darkMode', isDarkMode);
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

    // ========== NUEVO CARRUSEL (estilo slide con vista previa) ==========
    (function() {
        const next = document.querySelector('.carousel-next');
        const prev = document.querySelector('.carousel-prev');
        const slides = document.querySelector('.carousel-new .slides');

        if (!next || !prev || !slides) return;

        next.addEventListener('click', function() {
            const items = document.querySelectorAll('.carousel-new .slide-item');
            if (items.length) {
                slides.appendChild(items[0]); // Mueve el primer item al final
            }
        });

        prev.addEventListener('click', function() {
            const items = document.querySelectorAll('.carousel-new .slide-item');
            if (items.length) {
                slides.prepend(items[items.length - 1]); // Mueve el último al principio
            }
        });
    })();

    // ========== FUNCIONALIDAD ADICIONAL ==========
    
    // Confirmación antes de eliminar item del carrito
    const removeLinks = document.querySelectorAll('.remove-link');
    removeLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('¿Estás seguro de eliminar este producto del carrito?')) {
                e.preventDefault();
            }
        });
    });

    // Auto-ocultar alertas después de 5 segundos
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
    
    // ========== CARRUSEL (estilo slide con vista previa) ==========
(function() {
    const next = document.querySelector('.carousel-next');
    const prev = document.querySelector('.carousel-prev');
    const slides = document.querySelector('.carousel-new .slides');
    
    if (!next || !prev || !slides) return;

    // Función para actualizar la clase 'main' al slide que está en segunda posición
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

    // Rotar hacia adelante (siguiente)
    function moveNext() {
        const items = document.querySelectorAll('.carousel-new .slide-item');
        if (items.length) {
            slides.appendChild(items[0]);
            updateMainSlide();
        }
    }

    // Rotar hacia atrás (anterior)
    function movePrev() {
        const items = document.querySelectorAll('.carousel-new .slide-item');
        if (items.length) {
            slides.prepend(items[items.length - 1]);
            updateMainSlide();
        }
    }

    // Función para mover un slide específico a la posición principal (segundo lugar)
    function moveSlideToMain(targetSlide) {
        const items = Array.from(document.querySelectorAll('.carousel-new .slide-item'));
        const targetIndex = items.indexOf(targetSlide);
        if (targetIndex === -1 || targetIndex === 1) return; // Ya está en principal

        if (targetIndex < 1) {
            // El slide está antes de la posición principal: necesitamos rotar hacia atrás
            const steps = 1 - targetIndex; // cuántas veces hacia atrás
            for (let i = 0; i < steps; i++) {
                movePrev();
            }
        } else {
            // El slide está después: rotar hacia adelante
            const steps = targetIndex - 1;
            for (let i = 0; i < steps; i++) {
                moveNext();
            }
        }
    }

    // Eventos de botones
    next.addEventListener('click', moveNext);
    prev.addEventListener('click', movePrev);

    // Inicializar la clase 'main' al primer slide principal
    updateMainSlide();

    // Delegación de eventos para clics en los slides
    slides.addEventListener('click', function(e) {
        // Buscar si el clic fue en un .slide-item o en su interior
        const slide = e.target.closest('.slide-item');
        if (slide) {
            // Si el clic fue en el botón "Ver detalles", no queremos cambiar el slide
            if (e.target.tagName === 'BUTTON' || e.target.closest('button') || e.target.tagName === 'A' || e.target.closest('a')) {
                return; // Dejar que el enlace funcione normalmente
            }
            moveSlideToMain(slide);
        }
    });
})();
});