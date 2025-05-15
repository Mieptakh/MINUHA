document.addEventListener('DOMContentLoaded', function () {
    const burgerMenu = document.getElementById('burger-menu');
    const mainNav = document.getElementById('main-nav');

    if (burgerMenu && mainNav) {
        burgerMenu.addEventListener('click', function () {
            burgerMenu.classList.toggle('header__burger--active');
            mainNav.classList.toggle('header__nav--active');
            // Tidak mengubah overflow
        });

        const navLinks = document.querySelectorAll('.header__nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function () {
                if (window.innerWidth <= 768) {
                    burgerMenu.classList.remove('header__burger--active');
                    mainNav.classList.remove('header__nav--active');
                    // Tidak mengubah overflow
                }
            });
        });
    }
});
