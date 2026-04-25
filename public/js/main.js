/**
 * Main JavaScript for FPTShop-style E-commerce
 * AJAX Cart, Animations, and Interactive Features
 */

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function () {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

/**
 * AJAX Add to Cart
 */
function addToCartAjax(productId, quantity = 1) {
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', quantity);

    fetch('/cart/add', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update cart count
                updateCartCount(data.cartCount);

                // Show success notification
                showNotification('Đã thêm vào giỏ hàng!', 'success');
            } else {
                showNotification(data.message || 'Có lỗi xảy ra!', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Có lỗi xảy ra! Vui lòng thử lại.', 'error');
        });
}

/**
 * Update cart count badge
 */
function updateCartCount(count) {
    const cartCountElement = document.querySelector('.cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = count;

        // Animate the badge
        cartCountElement.style.transform = 'scale(1.3)';
        setTimeout(() => {
            cartCountElement.style.transform = 'scale(1)';
        }, 200);
    }
}

/**
 * Show notification toast
 */
function showNotification(message, type = 'success') {
    // Remove existing notifications
    const existing = document.querySelector('.notification-toast');
    if (existing) {
        existing.remove();
    }

    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification-toast notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-icon">${type === 'success' ? '✓' : '✕'}</span>
            <span class="notification-message">${message}</span>
        </div>
    `;

    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 80px;
        right: 20px;
        background: ${type === 'success' ? '#4caf50' : '#f44336'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        z-index: 10000;
        animation: slideInRight 0.3s ease;
    `;

    document.body.appendChild(notification);

    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

/**
 * Hover-intent helper for smoother dropdown interactions
 */
function setupSmoothDropdown({
    wrapperSelector,
    menuSelector,
    toggleSelector = null,
    openClass = 'dropdown-open',
    menuActiveClass = '',
    hideDelay = 200,
}) {
    const wrappers = document.querySelectorAll(wrapperSelector);

    wrappers.forEach((wrapper) => {
        const menu = wrapper.querySelector(menuSelector);
        if (!menu) {
            return;
        }

        let hideTimer;

        const openMenu = () => {
            clearTimeout(hideTimer);
            wrapper.classList.add(openClass);
            if (menuActiveClass) {
                menu.classList.add(menuActiveClass);
            }
        };

        const closeMenu = (immediate = false) => {
            const performClose = () => {
                wrapper.classList.remove(openClass);
                if (menuActiveClass) {
                    menu.classList.remove(menuActiveClass);
                }
            };

            if (immediate) {
                clearTimeout(hideTimer);
                performClose();
            } else {
                hideTimer = setTimeout(performClose, hideDelay);
            }
        };

        wrapper.addEventListener('mouseenter', openMenu);
        wrapper.addEventListener('mouseleave', () => closeMenu(false));
        wrapper.addEventListener('focusin', openMenu);
        wrapper.addEventListener('focusout', () => closeMenu(false));

        if (toggleSelector) {
            const toggle = wrapper.querySelector(toggleSelector);
            if (toggle) {
                toggle.addEventListener('click', (event) => {
                    event.preventDefault();
                    if (wrapper.classList.contains(openClass)) {
                        closeMenu(true);
                    } else {
                        openMenu();
                    }
                });
            }
        }

        document.addEventListener('click', (event) => {
            if (!wrapper.contains(event.target)) {
                closeMenu(true);
            }
        });
    });
}

/**
 * Countdown Timer for Flash Sales
 */
class CountdownTimer {
    constructor(selector, endTime) {
        this.element = document.querySelector(selector);
        if (!this.element) return;

        this.endTime = new Date(endTime).getTime();
        this.update();
        this.interval = setInterval(() => this.update(), 1000);
    }

    update() {
        const now = new Date().getTime();
        const distance = this.endTime - now;

        if (distance < 0) {
            clearInterval(this.interval);
            this.element.innerHTML = '<span class="countdown-item"><span>00</span><small>Giờ</small></span><span class="countdown-item"><span>00</span><small>Phút</small></span><span class="countdown-item"><span>00</span><small>Giây</small></span>';
            return;
        }

        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        this.element.innerHTML = `
            <span class="countdown-item">
                <span>${String(hours).padStart(2, '0')}</span>
                <small>Giờ</small>
            </span>
            <span class="countdown-item">
                <span>${String(minutes).padStart(2, '0')}</span>
                <small>Phút</small>
            </span>
            <span class="countdown-item">
                <span>${String(seconds).padStart(2, '0')}</span>
                <small>Giây</small>
            </span>
        `;
    }
}

/**
 * Image Gallery for Product Detail
 */
class ImageGallery {
    constructor(containerSelector) {
        this.container = document.querySelector(containerSelector);
        if (!this.container) return;

        this.mainImage = this.container.querySelector('.main-image');
        this.thumbnails = this.container.querySelectorAll('.thumbnail-image');

        this.thumbnails.forEach(thumb => {
            thumb.addEventListener('click', (e) => {
                const newSrc = e.target.dataset.fullImage || e.target.src;
                this.mainImage.src = newSrc;

                // Update active thumbnail
                this.thumbnails.forEach(t => t.classList.remove('active'));
                e.target.classList.add('active');
            });
        });
    }
}

/**
 * Quantity Selector with +/- buttons
 */
function setupQuantitySelectors() {
    document.querySelectorAll('.quantity-selector').forEach(selector => {
        const input = selector.querySelector('input[type="number"]');
        if (!input) return;

        // Create wrapper if it doesn't exist
        if (!selector.querySelector('.quantity-controls')) {
            const controls = document.createElement('div');
            controls.className = 'quantity-controls';
            controls.style.cssText = 'display: flex; align-items: center; gap: 0.5rem;';

            const decreaseBtn = document.createElement('button');
            decreaseBtn.type = 'button';
            decreaseBtn.textContent = '-';
            decreaseBtn.className = 'qty-btn';
            decreaseBtn.style.cssText = 'width: 35px; height: 35px; border: 2px solid #ddd; background: white; border-radius: 4px; cursor: pointer; font-size: 1.2rem; font-weight: bold;';

            const increaseBtn = document.createElement('button');
            increaseBtn.type = 'button';
            increaseBtn.textContent = '+';
            increaseBtn.className = 'qty-btn';
            increaseBtn.style.cssText = 'width: 35px; height: 35px; border: 2px solid #ddd; background: white; border-radius: 4px; cursor: pointer; font-size: 1.2rem; font-weight: bold;';

            input.style.cssText = 'width: 70px; text-align: center; border: 2px solid #ddd; border-radius: 4px; padding: 0.5rem; font-size: 1rem;';

            const inputClone = input.cloneNode(true);
            input.parentNode.replaceChild(controls, input);

            controls.appendChild(decreaseBtn);
            controls.appendChild(inputClone);
            controls.appendChild(increaseBtn);

            // Add event listeners
            decreaseBtn.addEventListener('click', () => {
                const currentValue = parseInt(inputClone.value) || 1;
                const min = parseInt(inputClone.min) || 1;
                if (currentValue > min) {
                    inputClone.value = currentValue - 1;
                }
            });

            increaseBtn.addEventListener('click', () => {
                const currentValue = parseInt(inputClone.value) || 1;
                const max = parseInt(inputClone.max) || 999;
                if (currentValue < max) {
                    inputClone.value = currentValue + 1;
                }
            });
        }
    });
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    // Setup quantity selectors
    setupQuantitySelectors();

    // Setup image gallery if exists
    new ImageGallery('.product-image-gallery');

    // Setup countdown timers
    document.querySelectorAll('.countdown[data-endtime]').forEach(countdown => {
        new CountdownTimer(countdown, countdown.dataset.endtime);
    });

    // Add animations on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'fadeInUp 0.6s ease';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.product-card, .category-card').forEach(card => {
        observer.observe(card);
    });
});

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .notification-content {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .notification-icon {
        font-size: 1.5rem;
        font-weight: bold;
    }
    
    .qty-btn:hover {
        background: #f0f0f0 !important;
        border-color: #d70018 !important;
    }
`;
document.head.appendChild(style);

console.log('FPTShop-style features loaded!');

// Enhance header dropdown interactions
document.addEventListener('DOMContentLoaded', function () {
    setupSmoothDropdown({
        wrapperSelector: '.nav-item-with-dropdown',
        menuSelector: '.mega-menu-dropdown',
        toggleSelector: '#header-categories-toggle',
        openClass: 'dropdown-open',
        menuActiveClass: 'active',
        hideDelay: 220,
    });

    setupSmoothDropdown({
        wrapperSelector: '.user-menu',
        menuSelector: '.dropdown',
        openClass: 'dropdown-open',
        menuActiveClass: 'active',
        hideDelay: 180,
    });
});

/**
 * Image Gallery for Product Detail
 */
class ImageGallery {
    constructor(containerSelector) {
        this.container = document.querySelector(containerSelector);
        if (!this.container) return;

        this.mainImage = this.container.querySelector('.main-image');
        this.thumbnails = this.container.querySelectorAll('.thumbnail-image');

        this.thumbnails.forEach(thumb => {
            thumb.addEventListener('click', (e) => {
                const newSrc = e.target.dataset.fullImage || e.target.src;
                this.mainImage.src = newSrc;

                // Update active thumbnail
                this.thumbnails.forEach(t => t.classList.remove('active'));
                e.target.classList.add('active');
            });
        });
    }
}

/**
 * Quantity Selector with +/- buttons
 */
function setupQuantitySelectors() {
    document.querySelectorAll('.quantity-selector').forEach(selector => {
        const input = selector.querySelector('input[type="number"]');
        if (!input) return;

        // Create wrapper if it doesn't exist
        if (!selector.querySelector('.quantity-controls')) {
            const controls = document.createElement('div');
            controls.className = 'quantity-controls';
            controls.style.cssText = 'display: flex; align-items: center; gap: 0.5rem;';

            const decreaseBtn = document.createElement('button');
            decreaseBtn.type = 'button';
            decreaseBtn.textContent = '-';
            decreaseBtn.className = 'qty-btn';
            decreaseBtn.style.cssText = 'width: 35px; height: 35px; border: 2px solid #ddd; background: white; border-radius: 4px; cursor: pointer; font-size: 1.2rem; font-weight: bold;';

            const increaseBtn = document.createElement('button');
            increaseBtn.type = 'button';
            increaseBtn.textContent = '+';
            increaseBtn.className = 'qty-btn';
            increaseBtn.style.cssText = 'width: 35px; height: 35px; border: 2px solid #ddd; background: white; border-radius: 4px; cursor: pointer; font-size: 1.2rem; font-weight: bold;';

            input.style.cssText = 'width: 70px; text-align: center; border: 2px solid #ddd; border-radius: 4px; padding: 0.5rem; font-size: 1rem;';

            const inputClone = input.cloneNode(true);
            input.parentNode.replaceChild(controls, input);

            controls.appendChild(decreaseBtn);
            controls.appendChild(inputClone);
            controls.appendChild(increaseBtn);

            // Add event listeners
            decreaseBtn.addEventListener('click', () => {
                const currentValue = parseInt(inputClone.value) || 1;
                const min = parseInt(inputClone.min) || 1;
                if (currentValue > min) {
                    inputClone.value = currentValue - 1;
                }
            });

            increaseBtn.addEventListener('click', () => {
                const currentValue = parseInt(inputClone.value) || 1;
                const max = parseInt(inputClone.max) || 999;
                if (currentValue < max) {
                    inputClone.value = currentValue + 1;
                }
            });
        }
    });
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    // Setup quantity selectors
    setupQuantitySelectors();

    // Setup image gallery if exists
    new ImageGallery('.product-image-gallery');

    // Setup countdown timers
    document.querySelectorAll('.countdown[data-endtime]').forEach(countdown => {
        new CountdownTimer(countdown, countdown.dataset.endtime);
    });

    // Add animations on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'fadeInUp 0.6s ease';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.product-card, .category-card').forEach(card => {
        observer.observe(card);
    });
});

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .notification-content {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .notification-icon {
        font-size: 1.5rem;
        font-weight: bold;
    }
    
    .qty-btn:hover {
        background: #f0f0f0 !important;
        border-color: #d70018 !important;
    }
`;
document.head.appendChild(style);

console.log('FPTShop-style features loaded!');
