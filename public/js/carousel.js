/**
 * Carousel/Slider Functionality
 * FPTShop-style image carousel with auto-play
 */

class Carousel {
    constructor(containerSelector) {
        this.container = document.querySelector(containerSelector);
        if (!this.container) return;

        this.slides = this.container.querySelectorAll('.hero-slide');
        this.currentSlide = 0;
        this.autoPlayInterval = null;
        this.autoPlayDelay = 5000; // 5 seconds

        this.init();
    }

    init() {
        this.createControls();
        this.createDots();
        this.showSlide(0);
        this.startAutoPlay();

        // Pause on hover
        this.container.addEventListener('mouseenter', () => this.stopAutoPlay());
        this.container.addEventListener('mouseleave', () => this.startAutoPlay());
    }

    createControls() {
        // Previous button
        const prevBtn = document.createElement('button');
        prevBtn.className = 'carousel-arrow prev';
        prevBtn.innerHTML = '&#8249;';
        prevBtn.addEventListener('click', () => this.prevSlide());

        // Next button
        const nextBtn = document.createElement('button');
        nextBtn.className = 'carousel-arrow next';
        nextBtn.innerHTML = '&#8250;';
        nextBtn.addEventListener('click', () => this.nextSlide());

        this.container.appendChild(prevBtn);
        this.container.appendChild(nextBtn);
    }

    createDots() {
        const dotsContainer = document.createElement('div');
        dotsContainer.className = 'carousel-controls';

        for (let i = 0; i < this.slides.length; i++) {
            const dot = document.createElement('span');
            dot.className = 'carousel-dot';
            dot.addEventListener('click', () => this.showSlide(i));
            dotsContainer.appendChild(dot);
        }

        this.container.appendChild(dotsContainer);
        this.dots = dotsContainer.querySelectorAll('.carousel-dot');
    }

    showSlide(index) {
        // Remove active class from all slides and dots
        this.slides.forEach(slide => slide.classList.remove('active'));
        this.dots.forEach(dot => dot.classList.remove('active'));

        // Add active class to current slide and dot
        this.slides[index].classList.add('active');
        this.dots[index].classList.add('active');

        this.currentSlide = index;
    }

    nextSlide() {
        let next = this.currentSlide + 1;
        if (next >= this.slides.length) {
            next = 0;
        }
        this.showSlide(next);
    }

    prevSlide() {
        let prev = this.currentSlide - 1;
        if (prev < 0) {
            prev = this.slides.length - 1;
        }
        this.showSlide(prev);
    }

    startAutoPlay() {
        this.autoPlayInterval = setInterval(() => {
            this.nextSlide();
        }, this.autoPlayDelay);
    }

    stopAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
        }
    }
}

/**
 * Product Carousel - horizontal scrolling
 */
class ProductCarousel {
    constructor(containerSelector) {
        this.container = document.querySelector(containerSelector);
        if (!this.container) return;

        this.track = this.container.querySelector('.products-grid');
        this.items = this.track.querySelectorAll('.product-card');
        this.currentIndex = 0;

        if (this.items.length > 4) {
            this.createNavigationButtons();
        }
    }

    createNavigationButtons() {
        const prevBtn = document.createElement('button');
        prevBtn.className = 'carousel-nav-btn prev';
        prevBtn.innerHTML = '&#8249;';
        prevBtn.addEventListener('click', () => this.scroll('prev'));

        const nextBtn = document.createElement('button');
        nextBtn.className = 'carousel-nav-btn next';
        nextBtn.innerHTML = '&#8250;';
        nextBtn.addEventListener('click', () => this.scroll('next'));

        this.container.style.position = 'relative';
        this.container.appendChild(prevBtn);
        this.container.appendChild(nextBtn);
    }

    scroll(direction) {
        const itemWidth = this.items[0].offsetWidth;
        const gap = 24; // 1.5rem gap
        const scrollAmount = itemWidth + gap;

        if (direction === 'next') {
            this.track.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        } else {
            this.track.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        }
    }
}

// Initialize carousels when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    // Initialize hero carousel
    new Carousel('.hero-carousel');

    // Initialize product carousels
    document.querySelectorAll('.product-carousel').forEach(carousel => {
        new ProductCarousel(carousel);
    });
});
