/**
 * Shoppn - quantity steppers (− / +) for the product page and cart.
 * In the cart, a stepper with data-autosubmit="<form id>" submits that form
 * right away, so validate.js still runs before the quantity is saved.
 */
document.addEventListener('DOMContentLoaded', function () {
    const MIN = 1;
    const MAX = 99;

    document.querySelectorAll('.qty-stepper').forEach(function (stepper) {
        const input = stepper.querySelector('.qty-input');
        const autoSubmitId = stepper.getAttribute('data-autosubmit');

        stepper.querySelectorAll('.qty-step').forEach(function (button) {
            button.addEventListener('click', function () {
                const current = parseInt(input.value, 10) || MIN;
                const next = Math.min(MAX, Math.max(MIN, current + parseInt(button.getAttribute('data-step'), 10)));
                if (next === current) {
                    return;
                }
                input.value = next;

                if (autoSubmitId) {
                    const form = document.getElementById(autoSubmitId);
                    if (form) {
                        form.requestSubmit();
                    }
                }
            });
        });
    });
});

/**
 * Slide-out cart drawer. The server renders it already open after an item is
 * added (class "is-open"); the header cart icon opens it, and the close button,
 * the dark overlay or the Escape key close it.
 */
document.addEventListener('DOMContentLoaded', function () {
    const drawer = document.getElementById('cart-drawer');
    if (!drawer) {
        return;
    }
    const overlay = document.querySelector('.drawer-overlay');
    let lastFocus = null;

    function setOpen(open) {
        drawer.classList.toggle('is-open', open);
        if (overlay) {
            overlay.classList.toggle('is-open', open);
        }
        drawer.setAttribute('aria-hidden', open ? 'false' : 'true');
        document.body.classList.toggle('drawer-lock', open);
        if (open) {
            lastFocus = document.activeElement;
            const closeBtn = drawer.querySelector('.drawer-close');
            if (closeBtn) {
                closeBtn.focus();
            }
        } else if (lastFocus) {
            lastFocus.focus();
        }
    }

    document.querySelectorAll('[data-drawer-open]').forEach(function (link) {
        link.addEventListener('click', function (event) {
            event.preventDefault();
            setOpen(true);
        });
    });

    document.querySelectorAll('[data-drawer-close]').forEach(function (el) {
        el.addEventListener('click', function (event) {
            event.preventDefault();
            setOpen(false);
        });
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && drawer.classList.contains('is-open')) {
            setOpen(false);
        }
    });

    if (drawer.classList.contains('is-open')) {
        setOpen(true);
    }
});
