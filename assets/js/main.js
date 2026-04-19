/**
 * main.js — Öğrenci Not Takip Sistemi
 * İnteraktif bileşenler ve animasyonlar
 */

document.addEventListener('DOMContentLoaded', function() {
    // ============ NAVBAR SCROLL ============
    const navbar = document.getElementById('mainNavbar');
    if (navbar) {
        let lastScroll = 0;
        window.addEventListener('scroll', function() {
            const currentScroll = window.scrollY;
            if (currentScroll > 10) {
                navbar.style.background = 'rgba(15, 15, 24, 0.95)';
                navbar.style.borderBottomColor = 'rgba(139, 92, 246, 0.15)';
            } else {
                navbar.style.background = 'rgba(15, 15, 24, 0.85)';
                navbar.style.borderBottomColor = 'rgba(139, 92, 246, 0.12)';
            }
            lastScroll = currentScroll;
        });
    }

    // ============ FLASH MESSAGE AUTO-DISMISS ============
    const flash = document.querySelector('.flash-msg');
    if (flash) {
        setTimeout(function() {
            flash.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            flash.style.opacity = '0';
            flash.style.transform = 'translateX(-50%) translateY(-20px)';
            setTimeout(function() { flash.remove(); }, 400);
        }, 4000);
    }

    // ============ FORM INPUT ANIMATION ============
    document.querySelectorAll('.form-input, .form-select').forEach(function(input) {
        input.addEventListener('focus', function() {
            this.closest('.form-group')?.classList.add('focused');
        });
        input.addEventListener('blur', function() {
            this.closest('.form-group')?.classList.remove('focused');
        });
    });

    // ============ STAT CARD COUNTER ANIMATION ============
    const observerOptions = { threshold: 0.3 };
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.stat-card, .course-card, .video-card').forEach(function(card) {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(card);
    });

    // ============ PROGRESS BAR ANIMATION ============
    const progressObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                const fill = entry.target.querySelector('.progress-fill');
                if (fill) {
                    const targetWidth = fill.style.width;
                    fill.style.width = '0';
                    setTimeout(function() {
                        fill.style.width = targetWidth;
                    }, 100);
                }
                progressObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.progress-bar').forEach(function(bar) {
        progressObserver.observe(bar);
    });

    // ============ GPA RING ANIMATION ============
    const gpaRing = document.querySelector('.gpa-ring');
    if (gpaRing) {
        const targetRotation = gpaRing.style.getPropertyValue('--gpa-rotation');
        gpaRing.style.setProperty('--gpa-rotation', '0deg');
        setTimeout(function() {
            gpaRing.style.setProperty('--gpa-rotation', targetRotation);
        }, 300);
    }

    // ============ KEYBOARD SHORTCUTS ============
    document.addEventListener('keydown', function(e) {
        // Alt + 1-5 for quick navigation
        if (e.altKey) {
            const shortcuts = {
                '1': 'home',
                '2': 'courses',
                '3': 'grades',
                '4': 'average',
                '5': 'videos'
            };
            if (shortcuts[e.key]) {
                e.preventDefault();
                window.location.href = 'index.php?page=' + shortcuts[e.key];
            }
        }
    });

    // ============ TOOLTIP ============
    document.querySelectorAll('[title]').forEach(function(el) {
        el.addEventListener('mouseenter', function(e) {
            const text = this.getAttribute('title');
            if (!text) return;
            
            this.setAttribute('data-title', text);
            this.removeAttribute('title');
            
            const tooltip = document.createElement('div');
            tooltip.className = 'custom-tooltip';
            tooltip.textContent = text;
            tooltip.style.cssText = `
                position: fixed;
                background: var(--nt-surface-3);
                color: var(--nt-text);
                padding: 4px 10px;
                border-radius: 6px;
                font-size: 0.7rem;
                font-weight: 500;
                z-index: 10001;
                pointer-events: none;
                white-space: nowrap;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                border: 1px solid var(--nt-border);
                animation: fadeIn 0.15s ease;
            `;
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.left = rect.left + rect.width / 2 - tooltip.offsetWidth / 2 + 'px';
            tooltip.style.top = rect.top - tooltip.offsetHeight - 8 + 'px';
            
            this._tooltip = tooltip;
        });
        
        el.addEventListener('mouseleave', function() {
            if (this._tooltip) {
                this._tooltip.remove();
                this._tooltip = null;
            }
            const title = this.getAttribute('data-title');
            if (title) {
                this.setAttribute('title', title);
                this.removeAttribute('data-title');
            }
        });
    });
});
