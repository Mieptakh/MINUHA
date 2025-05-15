<?php include 'includes/header.php'; ?>
<?php include 'admin/includes/db.php'; ?>

<!-- 1. Hero Section -->
<section id="mih-hero" class="mih-hero-section">
    <div class="container text-center mih-hero-content">
        <h1 class="mih-hero-title">Selamat Datang di MI Nurul Huda</h1>
        <p class="mih-hero-subtitle">Mencetak generasi islami, cerdas, dan berakhlak mulia.</p>
        <div class="mih-hero-buttons">
            <a href="/tentang" class="btn btn-primary">Lihat Profil</a>
            <a href="/kontak" class="btn btn-outline-secondary">Kontak Kami</a>
        </div>
    </div>
</section>

<!-- 2. Statistik Singkat -->
<section id="mih-statistics" class="mih-statistics-section">
    <div class="container">
        <div class="row text-center">
           <div class="mih-stat-item">
    <i class="fas fa-user-graduate fa-2x" style="color: var(--primary-color); margin-bottom: 10px;"></i>
    <h3 class="mih-stat-number" data-count="578">0</h3>
    <p class="mih-stat-label">Siswa Aktif</p>
</div>

<div class="mih-stat-item">
    <i class="fas fa-users fa-2x" style="color: var(--primary-color); margin-bottom: 10px;"></i>
    <h3 class="mih-stat-number" data-count="500">0</h3>
    <p class="mih-stat-label">Alumni</p>
</div>

<div class="mih-stat-item">
    <i class="fas fa-chalkboard-teacher fa-2x" style="color: var(--primary-color); margin-bottom: 10px;"></i>
    <h3 class="mih-stat-number" data-count="25">0</h3>
    <p class="mih-stat-label">Guru & Staff</p>
</div>

<div class="mih-stat-item">
    <i class="fas fa-school fa-2x" style="color: var(--primary-color); margin-bottom: 10px;"></i>
    <h3 class="mih-stat-number" data-count="19">0</h3>
    <p class="mih-stat-label">Kelas</p>
</div>

<div class="mih-stat-item">
    <i class="fas fa-layer-group fa-2x" style="color: var(--primary-color); margin-bottom: 10px;"></i>
    <h3 class="mih-stat-number" data-count="19">0</h3>
    <p class="mih-stat-label">Rombel</p>
</div>

<div class="mih-stat-item">
    <i class="fas fa-award fa-2x" style="color: var(--accent-color); margin-bottom: 10px;"></i>
    <h3 class="mih-stat-accreditation">A</h3>
    <p class="mih-stat-label">Akreditasi</p>
</div>

            
        </div>
    </div>
</section>


<!-- 3. Profil Singkat -->
<section id="mih-profile" class="mih-profile-section">
    <div class="container text-center">
        <h2 class="mih-section-title">Profil Singkat</h2>
        <p class="mih-profile-description">
            MI Nurul Huda berdiri sejak 1980 dan berkomitmen mencetak generasi unggul yang religius dan berakhlak. Fokus kami adalah pembentukan karakter melalui pendidikan Islam.
        </p>
        <p><strong>Visi:</strong> Terwujudnya manusia yang cerdas, bertaqwa, berakhlaqul karimah, dan berwawasan ahlusunnah wal jama'ah</p>
        <p><strong>Misi:</strong> Mengoptimalkan IMTAQ dan IPTEK.
Membudayakan Akhlakul Karimah, baik di sekolah, di rumah maupun masyarakat.
Membudayakan silaturahmi antar sekolah dan masyarakat</p>
        <a href="/tentang" class="btn btn-primary">Baca Selengkapnya</a>
    </div>
</section>

<!-- 4. Berita Terbaru -->
<section id="mih-news" class="mih-news-section py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mih-section-title">Berita Terbaru</h2>
        </div>

        <div class="row">
        <?php
        $sql = "SELECT id, judul, slug, isi, gambar, tanggal FROM berita ORDER BY tanggal DESC LIMIT 3";
        $result = $koneksi->query($sql);

            $index = 0;
            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
                    $index++;
                    $hideMobile = ($index > 1) ? 'hide-mobile' : '';
            ?>
                <div class="col-md-4 mb-4 <?= $hideMobile ?>">
                    <div class="mih-news-item card h-100">
                        <img src="uploads/<?= htmlspecialchars($row['gambar']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['judul']) ?>">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($row['judul']) ?></h5>
                            <p class="card-text mb-4"><?= nl2br(htmlspecialchars(substr($row['isi'], 0, 100))) ?>...</p>
                            <a href="baca-berita.php?slug=<?= urlencode($row['slug']) ?>" class="btn btn-dark">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
            <?php
                endwhile;
            else:
            ?>
                <div class="col-12">
                    <p>Belum ada berita terbaru.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="btn-news">
            <a href="/berita" class="btn btn-dark">Lihat Berita Lainnya</a>
        </div>
    </div>
</section>

<!-- 5. Program Unggulan -->
<section id="mih-programs" class="mih-program-section">
  <div class="container text-center">
    <h2 class="mih-section-title">Program Unggulan</h2>
    <div class="row mih-program-items">
      
      <div class="mih-program-item">
        <i class="fas fa-quran fa-3x mih-program-icon" style="color: var(--primary-color);"></i>
        <h5 class="mih-program-title">Program Tahfidz</h5>
        <p class="mih-program-description">
          Menghafal Al-Qur'an dari juz 30 hingga target tahunan.
        </p>
      </div>

      <div class="mih-program-item">
        <i class="fas fa-laptop-code fa-3x mih-program-icon" style="color: var(--primary-color);"></i>
        <h5 class="mih-program-title">Kelas Digital</h5>
        <p class="mih-program-description">
          Pembelajaran interaktif dengan perangkat dan media digital.
        </p>
      </div>

      <div class="mih-program-item">
        <i class="fas fa-users fa-3x mih-program-icon" style="color: var(--primary-color);"></i>
        <h5 class="mih-program-title">Ekstrakurikuler</h5>
        <p class="mih-program-description">
          Pramuka, seni, olahraga, dan keagamaan setiap minggu.
        </p>
      </div>

    </div>
  </div>
</section>


<!-- 6. Testimoni -->
<section id="mih-testimonial" class="mih-testimonial-section">
  <div class="container">
    <h2 class="mih-section-title text-center">Kata Alumni & Orang Tua</h2>
    <div class="row mih-testimonial-items">

      <!-- Testimoni 1 -->
      <div class="mih-testimonial-item text-center">
        <img src="images/uploads/testimoni/fperson.png" alt="Ibu Aisyah" class="mih-testimonial-img">
        <blockquote class="mih-testimonial-text truncate">
          "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sagittis, justo sed dignissim lacinia, sapien nunc convallis risus, id tempor lacus velit a justo. Donec commodo, nibh a bibendum feugiat, lorem sem malesuada enim, vitae dapibus orci nulla in tellus. Curabitur vitae lectus a magna malesuada feugiat. Quisque elementum justo at augue faucibus, ac dignissim arcu tempor. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam. Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo."
        </blockquote>
        <button class="read-more-btn">Baca Selengkapnya</button>
        <p class="mih-testimonial-author">Ibu Aisyah, Orang Tua Siswa</p>
      </div>

      <!-- Testimoni 2 -->
      <div class="mih-testimonial-item text-center">
        <img src="images/uploads/testimoni/dperson.png" alt="Ahmad" class="mih-testimonial-img">
        <blockquote class="mih-testimonial-text truncate">
          "Awalnya saya cukup khawatir bagaimana anak saya akan beradaptasi dengan lingkungan baru. Tapi setelah beberapa bulan belajar di madrasah ini, saya bisa melihat perubahan yang luar biasa. Ia jadi lebih disiplin, rajin mengaji bahkan di rumah, dan mulai menunjukkan rasa tanggung jawab yang tinggi terhadap tugas-tugasnya. Saya merasa pendidikan di sini tidak hanya fokus pada akademik, tetapi juga sangat memperhatikan pembentukan karakter dan akhlak anak. Sebagai orang tua, saya merasa sangat tenang dan bersyukur telah mempercayakan pendidikan anak saya di tempat ini."
        </blockquote>
        <button class="read-more-btn">Baca Selengkapnya</button>
        <p class="mih-testimonial-author">Ahmad, Alumni</p>
      </div>

      <!-- Testimoni 3 -->
      <div class="mih-testimonial-item text-center">
        <img src="images/uploads/testimoni/image.png" alt="Ust. Fikri" class="mih-testimonial-img">
        <blockquote class="mih-testimonial-text truncate">
          "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sagittis, justo sed dignissim lacinia, sapien nunc convallis risus, id tempor lacus velit a justo. Donec commodo, nibh a bibendum feugiat, lorem sem malesuada enim, vitae dapibus orci nulla in tellus. Curabitur vitae lectus a magna malesuada feugiat. Quisque elementum justo at augue faucibus, ac dignissim arcu tempor. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam. Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo."
        </blockquote>
        <button class="read-more-btn">Baca Selengkapnya</button>
        <p class="mih-testimonial-author">Ust. Fikri, Guru</p>
      </div>

    </div>
  </div>
</section>

<!-- Script Read More -->
<script>
document.querySelectorAll('.read-more-btn').forEach(button => {
  button.addEventListener('click', () => {
    const testimonialItem = button.closest('.mih-testimonial-item'); // Ambil parent card
    const testimonialText = testimonialItem.querySelector('.mih-testimonial-text');
    
    // Cek apakah card ini sudah "expanded", jika sudah sembunyikan dan reset
    if (testimonialItem.classList.contains('expanded')) {
      testimonialItem.classList.remove('expanded');
      button.textContent = 'Baca Selengkapnya';
    } else {
      // Hapus kelas expanded dari semua card
      document.querySelectorAll('.mih-testimonial-item').forEach(item => {
        item.classList.remove('expanded');
        item.querySelector('.read-more-btn').textContent = 'Baca Selengkapnya';
      });
      
      // Tambahkan kelas expanded pada card yang diklik
      testimonialItem.classList.add('expanded');
      button.textContent = 'Sembunyikan';
    }
  });
});
</script>

<!-- 7. Fasilitas -->
<section id="mih-facilities" class="mih-facilities-section">
  <div class="container text-center">
    <h2 class="mih-section-title mb-4">Fasilitas Kami</h2>
    <div class="row">
      <div class="mih-facility-item">
        <img src="uploads/1746258590-poster2.jpeg" alt="Perpustakaan" class="mih-facility-image mb-3">
        <h5 class="mih-facility-title">Perpustakaan</h5>
        <p class="mih-facility-description">Perpustakaan yang lengkap dengan koleksi buku pendidikan dan referensi.</p>
      </div>
      <div class="mih-facility-item">
        <img src="uploads/1746258590-poster2.jpeg" alt="Laboratorium" class="mih-facility-image mb-3">
        <h5 class="mih-facility-title">Laboratorium</h5>
        <p class="mih-facility-description">Laboratorium IPA dan komputer untuk mendukung pembelajaran praktis.</p>
      </div>
      <div class="mih-facility-item">
        <img src="uploads/1746258590-poster2.jpeg" alt="Lapangan Olahraga" class="mih-facility-image mb-3">
        <h5 class="mih-facility-title">Lapangan Olahraga</h5>
        <p class="mih-facility-description">Lapangan olahraga yang mendukung kegiatan ekstrakurikuler dan kesehatan siswa.</p>
      </div>
    </div>
  </div>
</section>

<!-- 8. Galeri -->
<section id="mih-gallery" class="mih-gallery-section">
  <div class="container text-center">
    <h2 class="mih-section-title mb-4">Galeri Kegiatan</h2>
    <div class="mih-gallery-items">
      <?php
      $galeri = $koneksi->query("SELECT * FROM galeri ORDER BY tanggal_upload DESC LIMIT 3");
      if ($galeri && $galeri->num_rows > 0):
          $index = 0;
          while ($item = $galeri->fetch_assoc()):
      ?>
        <div class="mih-gallery-item <?= $index >= 3 ? 'hide-on-mobile' : '' ?>">
          <img src="uploads/<?= htmlspecialchars($item['gambar']) ?>"
               alt="<?= htmlspecialchars($item['keterangan']) ?>"
               class="mih-gallery-image"
               data-full="uploads/<?= htmlspecialchars($item['gambar']) ?>">
          <p class="mih-gallery-label"><?= htmlspecialchars($item['keterangan']) ?></p>
        </div>
      <?php
          $index++;
          endwhile;
      else:
      ?>
        <div>
          <p class="text-muted">Belum ada kegiatan yang ditampilkan dalam galeri.</p>
        </div>
      <?php endif; ?>
    </div>
    <div class="mih-gallery-button">
      <a href="/galeri" class="btn btn-dark">Lihat Galeri Lainnya</a>
    </div>
  </div>
</section>


<!-- Modal Gambar Universal -->
<div id="universalImageModal" class="mih-modal">
  <div class="mih-modal-content-wrapper">
    <span class="mih-modal-close" id="modalCloseBtn">&times;</span>
    <img class="mih-modal-image" id="modalImg" alt="Preview">
    <div id="modalCaption" class="mih-modal-caption"></div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const modal = document.getElementById("universalImageModal");
  const modalImg = document.getElementById("modalImg");
  const modalCaption = document.getElementById("modalCaption");
  const closeBtn = document.getElementById("modalCloseBtn");

  // Fungsi buka modal universal
  function openImageModal(src, alt = "") {
    modal.classList.add("active");
    modalImg.src = src;
    modalCaption.textContent = alt || 'Gambar';
  }

  // Semua gambar dengan class berikut bisa membuka modal
  document.querySelectorAll('.mih-gallery-image, .mih-facility-image').forEach(img => {
    img.addEventListener('click', () => {
      const src = img.getAttribute('data-full') || img.src;
      const alt = img.alt || '';
      openImageModal(src, alt);
    });
  });

  // Tutup modal saat klik tombol close
  closeBtn.addEventListener('click', () => {
    modal.classList.remove("active");
    modalImg.src = '';
  });

  // Tutup modal saat klik luar gambar
  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.classList.remove("active");
      modalImg.src = '';
    }
  });
});
</script>


<!-- 9. PPDB
<section id="mih-ppdb" class="mih-ppdb-section">
    <div class="container text-center">
        <h2 class="mih-section-title mb-3">Penerimaan Peserta Didik Baru</h2>
        <p>Pendaftaran tahun ajaran 2025/2026 telah dibuka! Bergabunglah bersama kami membangun generasi islami.</p>
        <div class="mih-ppdb-buttons">
            <a href="ppdb.php" class="btn btn-dark">Daftar Sekarang</a>
            <a href="formulir.pdf" class="btn btn-outline-secondary" download>Unduh Formulir</a>
        </div>
        <p class="mih-ppdb-deadline mt-2"><small>Deadline: 30 Juni 2025</small></p>
    </div>
</section> -->

<?php include 'includes/footer.php'; ?>
