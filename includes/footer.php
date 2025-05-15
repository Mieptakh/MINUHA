<footer class="site-footer">
    <div class="footer-container">

        <!-- Footer Logo and Description -->
        <div class="footer-logo">
            <img class="footer-logo-link" src="/images/logo/9899minuhalogo.png" alt="Logo MI Nurul Huda" class="logo-img">
            <div>
                <span class="footer-logo-text">MI Nurul Huda</span>
            </div>
            <div class="footer-description">
                <p class="description-text">
                    MI Nurul Huda adalah lembaga pendidikan yang berfokus pada pengembangan karakter dan kualitas akademik siswa. Didirikan untuk memberikan pendidikan yang berkualitas kepada generasi penerus bangsa.
                </p>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="footer-section">
            <h3 class="footer-title">Hubungi Kami</h3>
            <div class="footer-content">
                <p><i class="fas fa-map-marker-alt"></i> Jl. Ngampelsari No.03, RT.01/RW.03, Ngampelsari, Kec. Candi, Kabupaten Sidoarjo, Jawa Timur 61271</p>
                <p><i class="fas fa-phone"></i> 0816-1514-6817</p>
                <p><i class="fas fa-envelope"></i> 
                    <a href="#" id="email-link" class="a-footer">[email protected]</a>
                </p>
            </div>
        </div>

        <script>
            // Pecah email supaya tidak diambil spammer
            const user = "minuhacandi9";
            const domain = "gmail.com";
            const email = user + "@" + domain;
            const link = document.getElementById("email-link");
            link.textContent = email;
            link.href = "mailto:" + email;
        </script>

        <!-- Google Maps Embed -->
        <div class="footer-section">
            <h3 class="footer-title">Lokasi Kami</h3>
            <div class="footer-map">
                <iframe
                    width="100%"
                    height="226"
                    style="border:0;"
                    loading="lazy"
                    allowfullscreen
                    referrerpolicy="no-referrer-when-downgrade"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.3141278351255!2d112.716467!3d-7.493215!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7e7c0f3c40829%3A0xa83d3d837843ccc!2sMI%20Nurul%20Huda!5e0!3m2!1sid!2sid!4v1715244500000!5m2!1sid!2sid">
                </iframe>
            </div>
        </div>

        <!-- Quick Links Section -->
        <div class="footer-section">
            <h3 class="footer-title">Tautan Cepat</h3>
            <ul class="footer-links">
                <li><a href="/">Beranda</a></li>
                <li><a href="/tentang">Tentang</a></li>
                <li><a href="/berita">Berita</a></li>
                <li><a href="/galeri">Galeri</a></li>
                <li><a href="/kontak">Kontak</a></li>
            </ul>
        </div>

        <!-- Social Media Links -->
        <div class="footer-section">
            <h3 class="footer-title">Ikuti Kami</h3>
            <div class="footer-social">
                <a href="https://facebook.com/" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="https://instagram.com/" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="https://youtube.com/" target="_blank" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                <a href="https://wa.me/6281234567890" target="_blank" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
    </div>
    
    <!-- Footer Bottom Section -->
    <div class="footer-bottom">
        <div class="footer-copyright">
            <p>&copy; 2025 MI Nurul Huda. All rights reserved.</p>
            <p class="developer-credit">
                Dikembangkan dengan <i class="fas fa-heart" aria-hidden="true"></i> oleh 
                <a href="https://mhteams.rf.gd" target="_blank" class="developer-link">MHTeams</a> secara spesial
            </p>
        </div>
    </div>

    <!-- Back to Top Button -->
    <button id="back-to-top" class="back-to-top" title="Kembali ke atas">
        <i class="fas fa-arrow-up"></i>
    </button>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const backToTopButton = document.getElementById('back-to-top');
            if (backToTopButton) {
                window.addEventListener('scroll', function () {
                    if (window.pageYOffset > 300) {
                        backToTopButton.classList.add('visible');
                    } else {
                        backToTopButton.classList.remove('visible');
                    }
                });

                backToTopButton.addEventListener('click', function () {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }
        });
    </script>

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="/js/burger-handler.js"></script>

    <?php
    // Cek halaman aktif berdasarkan URI
    $currentPath = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    if ($currentPath === '') {
        echo '<script src="/js/script.js"></script>';
    }
    ?>
</footer>
