"use strict";

document.addEventListener('DOMContentLoaded', function() {
    const next = document.querySelector(".carousel-next");
    const prev = document.querySelector(".carousel-prev");
    const slides = document.querySelector(".carousel-slides");

    if (!next || !prev || !slides) return;

    function moveNext() {
        const items = document.querySelectorAll(".carousel-slide");
        if (items.length) {
            slides.appendChild(items[0]);
        }
    }

    function movePrev() {
        const items = document.querySelectorAll(".carousel-slide");
        if (items.length) {
            slides.prepend(items[items.length - 1]);
        }
    }

    function moveToSlide(targetSlide) {
        const items = Array.from(document.querySelectorAll(".carousel-slide"));
        const targetIndex = items.indexOf(targetSlide);
        if (targetIndex === -1 || targetIndex === 1) return; // La posición 1 ya es la principal

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

    next.addEventListener("click", moveNext);
    prev.addEventListener("click", movePrev);

    // Delegación de eventos para clics en las imágenes
    slides.addEventListener("click", function(e) {
        const slide = e.target.closest(".carousel-slide");
        if (slide) {
            // Evitar que el clic en el botón o enlace también active el movimiento
            if (e.target.tagName === 'A' || e.target.closest('a') || e.target.tagName === 'BUTTON' || e.target.closest('button')) {
                return;
            }
            moveToSlide(slide);
        }
    });
});