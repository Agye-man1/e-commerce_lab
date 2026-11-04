/**
 * Shoppn - Client-side Form Validation
 * Meets the lab requirements for client-side regex and field validation.
 */

document.addEventListener('DOMContentLoaded', function () {

    // Helper: show inline error
    function showError(elementId, message) {
        let el = document.getElementById(elementId);
        if (!el) {
            el = document.createElement('div');
            el.id = elementId;
            el.className = 'alert alert-error';
            el.style.marginTop = '0.5rem';
            el.style.marginBottom = '1rem';
        }
        el.textContent = message;
        el.style.display = 'block';
        return el;
    }

    function clearError(elementId) {
        const el = document.getElementById(elementId);
        if (el) {
            el.textContent = '';
            el.style.display = 'none';
        }
    }

    // ── Brand Form Validation ───────────────────────────────────────────────
    const brandForm = document.getElementById('brand-form');
    if (brandForm) {
        brandForm.addEventListener('submit', function (e) {
            const input = document.getElementById('brand_name');
            const val = input ? input.value.trim() : '';
            const errorContainer = document.getElementById('brand-js-error');

            if (val.length < 2) {
                e.preventDefault();
                if (errorContainer) {
                    errorContainer.textContent = 'Brand name must be at least 2 characters.';
                    errorContainer.style.display = 'block';
                } else {
                    alert('Brand name must be at least 2 characters.');
                }
                if (input) input.focus();
                return false;
            }

            if (val.length > 100) {
                e.preventDefault();
                if (errorContainer) {
                    errorContainer.textContent = 'Brand name cannot exceed 100 characters.';
                    errorContainer.style.display = 'block';
                }
                if (input) input.focus();
                return false;
            }

            if (errorContainer) {
                errorContainer.style.display = 'none';
            }
        });
    }

    // ── Category Form Validation ────────────────────────────────────────────
    const categoryForm = document.getElementById('category-form');
    if (categoryForm) {
        categoryForm.addEventListener('submit', function (e) {
            const input = document.getElementById('cat_name');
            const val = input ? input.value.trim() : '';
            const errorContainer = document.getElementById('category-js-error');

            if (val.length < 2) {
                e.preventDefault();
                if (errorContainer) {
                    errorContainer.textContent = 'Category name must be at least 2 characters.';
                    errorContainer.style.display = 'block';
                } else {
                    alert('Category name must be at least 2 characters.');
                }
                if (input) input.focus();
                return false;
            }

            if (val.length > 100) {
                e.preventDefault();
                if (errorContainer) {
                    errorContainer.textContent = 'Category name cannot exceed 100 characters.';
                    errorContainer.style.display = 'block';
                }
                if (input) input.focus();
                return false;
            }

            if (errorContainer) {
                errorContainer.style.display = 'none';
            }
        });
    }

    // ── Product Form Validation ─────────────────────────────────────────────
    const productForm = document.getElementById('product-form');
    if (productForm) {
        productForm.addEventListener('submit', function (e) {
            const title = document.getElementById('product_title');
            const price = document.getElementById('product_price');
            const cat = document.getElementById('product_cat');
            const brand = document.getElementById('product_brand');
            const image = document.getElementById('product_image');
            const errorContainer = document.getElementById('product-js-error');
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/avif'];
            const maxSize = 2 * 1024 * 1024;
            let errorMsg = '';

            if (!title || title.value.trim().length < 2) {
                errorMsg = 'Product title must be at least 2 characters.';
            } else if (!price || price.value === '' || Number(price.value) < 0) {
                errorMsg = 'Please enter a valid price.';
            } else if (!cat || cat.value === '') {
                errorMsg = 'Please select a category.';
            } else if (!brand || brand.value === '') {
                errorMsg = 'Please select a brand.';
            } else if (image && image.files.length > 0) {
                const file = image.files[0];
                if (allowedTypes.indexOf(file.type) === -1) {
                    errorMsg = 'Image must be JPEG, PNG, GIF, WebP, or AVIF.';
                } else if (file.size > maxSize) {
                    errorMsg = 'Image must be 2MB or smaller.';
                }
            } else if (image && image.required && image.files.length === 0) {
                errorMsg = 'Please upload a product image.';
            }

            if (errorMsg) {
                e.preventDefault();
                if (errorContainer) {
                    errorContainer.textContent = errorMsg;
                    errorContainer.style.display = 'block';
                }
                return false;
            }

            if (errorContainer) {
                errorContainer.style.display = 'none';
            }
        });
    }

    // ── Register Form Validation ────────────────────────────────────────────
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const phoneRegex = /^[0-9+\-\s]{7,15}$/;
            const passRegex = /^(?=.*\d).{8,}$/;

            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const passInput = document.getElementById('password');
            const contactInput = document.getElementById('contact');
            const errorContainer = document.getElementById('register-js-error');

            let errorMsg = '';

            if (nameInput && nameInput.value.trim().length < 2) {
                errorMsg = 'Full Name must be at least 2 characters.';
            } else if (emailInput && !emailRegex.test(emailInput.value.trim())) {
                errorMsg = 'Please enter a valid email address.';
            } else if (passInput && !passRegex.test(passInput.value)) {
                errorMsg = 'Password must be at least 8 characters and contain at least one digit.';
            } else if (contactInput && !phoneRegex.test(contactInput.value.trim())) {
                errorMsg = 'Please enter a valid phone number (7 to 15 digits).';
            }

            if (errorMsg) {
                e.preventDefault();
                if (errorContainer) {
                    errorContainer.textContent = errorMsg;
                    errorContainer.style.display = 'block';
                } else {
                    alert(errorMsg);
                }
                return false;
            }

            if (errorContainer) {
                errorContainer.style.display = 'none';
            }
        });
    }

    // ── Login Form Validation ───────────────────────────────────────────────
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const emailInput = document.getElementById('email');
            const passInput = document.getElementById('password');
            const errorContainer = document.getElementById('login-js-error');

            let errorMsg = '';

            if (emailInput && !emailRegex.test(emailInput.value.trim())) {
                errorMsg = 'Please enter a valid email address.';
            } else if (passInput && passInput.value.trim() === '') {
                errorMsg = 'Please enter your password.';
            }

            if (errorMsg) {
                e.preventDefault();
                if (errorContainer) {
                    errorContainer.textContent = errorMsg;
                    errorContainer.style.display = 'block';
                }
                return false;
            }

            if (errorContainer) {
                errorContainer.style.display = 'none';
            }
        });
    }

    // ── Live Table Filter (Admin Tables) ───────────────────────────────────
    const filterInput = document.getElementById('table-filter');
    if (filterInput) {
        filterInput.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('.table-row-item');
            let visibleCount = 0;

            rows.forEach(function (row) {
                const cell = row.querySelector('.name-cell') || row.querySelector('.brand-name-cell') || row.querySelector('.category-name-cell');
                const name = cell ? cell.textContent.toLowerCase() : '';
                if (name.includes(query)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const countBadge = document.getElementById('item-count') || document.getElementById('brand-count') || document.getElementById('category-count') || document.getElementById('product-count');
            if (countBadge) {
                countBadge.textContent = visibleCount;
            }
        });
    }

});

