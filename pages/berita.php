<?php include __DIR__ . '/../includes/header.php'; ?>
<?php include __DIR__ . '/../admin/includes/db.php'; ?>

<?php
// Ambil mode sorting (asc/desc), default: DESC
$sort = (isset($_GET['sort']) && strtolower($_GET['sort']) === 'asc') ? 'ASC' : 'DESC';

// Ambil halaman aktif dari URL, default: 1
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 6;
$offset = ($page - 1) * $limit;

// Total berita
$total_result = $koneksi->query("SELECT COUNT(*) as total FROM berita");
$total_berita = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_berita / $limit);

// Ambil data berita dengan limit dan offset
$sql = "SELECT id, judul, slug, isi, gambar, tanggal 
        FROM berita 
        ORDER BY tanggal $sort 
        LIMIT $limit OFFSET $offset";
$berita = $koneksi->query($sql);
?>

<!-- HERO SECTION -->
<section class="mih-hero-berita">
  <div class="container">
    <h1>Berita & Informasi</h1>
    <p>Kumpulan kabar terbaru dari sekolah kami</p>
  </div>
</section>

<!-- BERITA SECTION -->
<section id="mih-news" class="py-5">
  <div class="mih-news-container">
    <h2 class="mih-section-title">Semua Berita</h2>

    <!-- Sorting Form -->
    <form method="GET" class="mih-sorting-form">
      <label for="sort">Urutkan:</label>
      <select name="sort" id="sort" onchange="this.form.submit()">
        <option value="desc" <?= $sort === 'DESC' ? 'selected' : '' ?>>Terbaru</option>
        <option value="asc"  <?= $sort === 'ASC'  ? 'selected' : '' ?>>Terlama</option>
      </select>
      <input type="hidden" name="page" value="<?= $page ?>">
    </form>

    <!-- News Cards -->
    <div class="mih-news-grid">
      <?php if ($berita && $berita->num_rows > 0): ?>
        <?php while ($row = $berita->fetch_assoc()): ?>
          <article class="mih-news-card">
            <?php if (!empty($row['gambar'])): ?>
              <img 
                src="/uploads/<?= htmlspecialchars($row['gambar']) ?>" 
                alt="<?= htmlspecialchars($row['judul']) ?>" 
                class="mih-card-image"
              >
            <?php endif; ?>
            <div class="mih-card-body">
              <h3 class="mih-card-title"><?= htmlspecialchars($row['judul']) ?></h3>
              <p class="mih-card-text"><?= nl2br(htmlspecialchars(substr($row['isi'], 0, 120))) ?>…</p>
              <time class="mih-card-date"><?= date('d F Y', strtotime($row['tanggal'])) ?></time>
              <a href="/baca-berita?slug=<?= urlencode($row['slug']) ?>" class="mih-card-button">Baca Selengkapnya</a>
            </div>
          </article>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-center">Belum ada berita yang tersedia.</p>
      <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
      <ul class="mih-pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <li class="<?= $i === $page ? 'active' : '' ?>">
            <a href="/berita?sort=<?= strtolower($sort) ?>&page=<?= $i ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>
      </ul>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>