document.addEventListener('DOMContentLoaded', function () {
    // ======================= Statistik Counter =======================
    let hasAnimated = false;

    function animateStats() {
        if (hasAnimated) return;

        const stats = document.querySelectorAll('.mih-stat-number');
        stats.forEach((stat) => {
            const target = parseInt(stat.getAttribute('data-count'), 10) || 0;
            let count = 0;

            const duration = 200;
            const steps = Math.min(target, 100);
            const increment = Math.ceil(target / steps);
            const delay = Math.floor(duration / steps);

            const counter = setInterval(() => {
                count += increment;
                if (count >= target) {
                    stat.textContent = target;
                    clearInterval(counter);
                } else {
                    stat.textContent = count;
                }
            }, delay);
        });

        hasAnimated = true;
    }

    function checkVisibility() {
        const statsSection = document.getElementById('mih-statistics');
        if (!statsSection) return;

        const rect = statsSection.getBoundingClientRect();
        const windowHeight = window.innerHeight || document.documentElement.clientHeight;

        if (rect.top < windowHeight && rect.bottom >= 0) {
            animateStats();
        }
    }

    window.addEventListener('load', checkVisibility);
    window.addEventListener('scroll', checkVisibility);

});
