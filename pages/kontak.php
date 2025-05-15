<?php include 'includes/header.php'; ?>

<section class="contact-section">
    <h2 class="contact-title">Hubungi Kami</h2>
    <p class="contact-subtitle">Ada pertanyaan, kritik, atau saran? Jangan ragu untuk menghubungi kami melalui formulir di bawah ini.</p>

    <div class="contact-container">
        <!-- Form Kontak -->
        <form method="POST" action="form-handler/contact-process.php" class="contact-form">
            <div class="form-group">
                <label for="nama"><i class="fas fa-user"></i> Nama Lengkap</label>
                <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap Anda" required>
            </div>
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email Aktif</label>
                <input type="email" id="email" name="email" placeholder="Alamat email Anda" required>
            </div>
            <div class="form-group">
                <label for="pesan"><i class="fas fa-comment-dots"></i> Pesan Anda</label>
                <textarea id="pesan" name="pesan" rows="5" placeholder="Tuliskan pesan Anda di sini..." required></textarea>
            </div>
            <button type="submit" class="btn-submit"><i class="fas fa-paper-plane"></i> Kirim Pesan</button>
        </form>

        <!-- Info Kontak Tambahan -->
        <div class="contact-info">
            <h3>Info Kontak</h3>
            <p><i class="fas fa-map-marker-alt"></i> Jl. Ngampelsari No.03, Sidoarjo, Jawa Timur</p>
            <p><i class="fas fa-phone-alt"></i> 0816-1514-6817</p>
            <p><i class="fas fa-envelope"></i> minuhacandi9@gmail.com</p>
            <h4>Jam Operasional</h4>
            <p><i class="fas fa-clock"></i> Senin - Kamis: 07.00 - 13:30</p>
            <p><i class="fas fa-clock"></i> Jum'at: 07.00 - 13:30</p>
            <p><i class="fas fa-clock"></i> Sabtu: 07.00 - 12.00</p>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
