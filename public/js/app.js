document.addEventListener('DOMContentLoaded', function() {

    // =============================================
    // 1. DARK / LIGHT MODE TOGGLE
    // =============================================
    const themeToggleBtns = document.querySelectorAll('.theme-toggle');
    const html = document.documentElement;

    // Load saved theme, fallback to OS preference
    const savedTheme = localStorage.getItem('vendorconnect-theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const currentTheme = savedTheme || (prefersDark ? 'dark' : 'light');
    applyTheme(currentTheme);

    themeToggleBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const newTheme = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            applyTheme(newTheme);
            localStorage.setItem('vendorconnect-theme', newTheme);
        });
    });

    function applyTheme(theme) {
        html.setAttribute('data-theme', theme);
        themeToggleBtns.forEach(btn => {
            btn.textContent = theme === 'dark' ? '☀️' : '🌙';
            btn.setAttribute('title', theme === 'dark' ? 'Switch to Light Mode' : 'Switch to Dark Mode');
        });
    }

    // =============================================
    // 2. FILE UPLOAD PREVIEW
    // =============================================
    const fileInputs = document.querySelectorAll('.file-drop-area input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const dropArea = this.closest('.file-drop-area');
                const p = dropArea.querySelector('p');
                const span = dropArea.querySelector('span');
                if (p) p.innerHTML = `<strong style="color:var(--primary)">✓ ${file.name}</strong>`;
                if (span) span.textContent = `${(file.size / 1024).toFixed(1)} KB`;
                dropArea.style.borderColor = 'var(--primary)';
                dropArea.style.backgroundColor = 'var(--primary-muted)';
            }
        });
    });

    // =============================================
    // 3. SCROLL ANIMATIONS (IntersectionObserver)
    // =============================================
    const animatedEls = document.querySelectorAll('.animate-on-scroll');
    if (animatedEls.length > 0 && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
        animatedEls.forEach(el => observer.observe(el));
    } else {
        // Fallback: show all immediately
        animatedEls.forEach(el => el.classList.add('is-visible'));
    }

    // =============================================
    // 4. MOBILE SIDEBAR TOGGLE (Admin)
    // =============================================
    const sidebarToggle = document.getElementById('mobile-sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            if (overlay) overlay.classList.toggle('active');
        });
    }
    if (overlay) {
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });
    }

    // =============================================
    // 5. BUSINESS CATEGORY "OTHER" TOGGLE
    // =============================================
    const categorySelect = document.getElementById('businessCategorySelect');
    const otherInput = document.getElementById('businessCategoryOther');
    if (categorySelect && otherInput) {
        categorySelect.addEventListener('change', function () {
            if (this.value === 'Other') {
                otherInput.style.display = 'block';
                otherInput.required = true;
            } else {
                otherInput.style.display = 'none';
                otherInput.required = false;
                otherInput.value = '';
            }
        });
    }
});
