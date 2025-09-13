<?php include 'includes/header.php'; ?>
<?php include 'admin/includes/db.php'; ?>

<?php
// Validasi slug
if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    echo "<p>Berita tidak ditemukan.</p>";
    include 'includes/footer.php';
    exit;
}

$slug = trim($_GET['slug']);
$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

// Ambil detail berita berdasarkan slug
$stmt = $koneksi->prepare("SELECT id, judul, isi, gambar, tanggal FROM berita WHERE slug = ?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Berita tidak ditemukan.</p>";
    include 'includes/footer.php';
    exit;
}

$berita = $result->fetch_assoc();

// Ambil 4 berita lainnya acak, kecuali yang sedang dibuka
$otherStmt = $koneksi->prepare("SELECT judul, slug, gambar, tanggal FROM berita WHERE slug != ? ORDER BY RAND() LIMIT 4");
$otherStmt->bind_param("s", $slug);
$otherStmt->execute();
$otherResult = $otherStmt->get_result();
?>

<section class="berita-detail-section">
  <div class="container berita-detail-container">

    <!-- Konten Utama -->
    <div class="berita-content">
      <h1><?= htmlspecialchars($berita['judul']) ?></h1>
      <div class="meta-info">
        <span class="tanggal"><i class="far fa-calendar-alt"></i> <?= date('d M Y, H:i', strtotime($berita['tanggal'])) ?></span>
      </div>

      <?php if (!empty($berita['gambar'])): ?>
        <img src="uploads/<?= htmlspecialchars($berita['gambar']) ?>" alt="<?= htmlspecialchars($berita['judul']) ?>" class="berita-image">
      <?php endif; ?>

      <p><?= nl2br(htmlspecialchars($berita['isi'])) ?></p>

      <!-- Tombol Share -->
      <div class="berita-share">
        <span>Bagikan:</span>
        <div class="share-buttons">
          <!-- WhatsApp -->
          <a href="https://wa.me/?text=<?= urlencode($berita['judul'] . ' - Baca selengkapnya: ' . $current_url) ?>" 
             target="_blank" 
             class="share-btn whatsapp"
             aria-label="Bagikan via WhatsApp">
            <i class="fab fa-whatsapp"></i>
            <span class="tooltip">WhatsApp</span>
          </a>
          
          <!-- Facebook -->
          <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($current_url) ?>&quote=<?= urlencode($berita['judul']) ?>" 
             target="_blank" 
             class="share-btn facebook"
             aria-label="Bagikan via Facebook">
            <i class="fab fa-facebook-f"></i>
            <span class="tooltip">Facebook</span>
          </a>
          
          <!-- Twitter -->
          <a href="https://twitter.com/intent/tweet?url=<?= urlencode($current_url) ?>&text=<?= urlencode($berita['judul']) ?>" 
             target="_blank" 
             class="share-btn twitter"
             aria-label="Bagikan via Twitter">
            <i class="fab fa-twitter"></i>
            <span class="tooltip">Twitter</span>
          </a>
          
          <!-- Instagram (via native app) -->
          <a href="instagram://share?url=<?= urlencode($current_url) ?>&title=<?= urlencode($berita['judul']) ?>" 
             onclick="window.open(this.href, '', 'width=500,height=500'); return false;"
             class="share-btn instagram"
             aria-label="Bagikan via Instagram">
            <i class="fab fa-instagram"></i>
            <span class="tooltip">Instagram</span>
          </a>
          
          <!-- Copy Link -->
          <button class="share-btn copy-link" 
                  onclick="copyToClipboard('<?= $current_url ?>')"
                  aria-label="Salin tautan">
            <i class="fas fa-link"></i>
            <span class="tooltip">Salin Tautan</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Saran Berita -->
    <aside class="berita-saran">
      <h3>Berita Lainnya</h3>
      <div class="berita-saran-list">
        <?php while ($saran = $otherResult->fetch_assoc()): ?>
          <a href="baca-berita?slug=<?= htmlspecialchars($saran['slug']) ?>" class="saran-item">
            <?php if (!empty($saran['gambar'])): ?>
              <img src="uploads/<?= htmlspecialchars($saran['gambar']) ?>" alt="<?= htmlspecialchars($saran['judul']) ?>">
            <?php endif; ?>
            <div class="saran-item-content">
              <h4><?= htmlspecialchars($saran['judul']) ?></h4>
              <span class="saran-tanggal"><?= date('d M Y', strtotime($saran['tanggal'])) ?></span>
            </div>
          </a>
        <?php endwhile; ?>
      </div>
    </aside>

  </div>
</section>

<script>
// Fungsi untuk copy link
function copyToClipboard(text) {
  navigator.clipboard.writeText(text).then(function() {
    // Show copied tooltip
    const tooltip = document.createElement('div');
    tooltip.className = 'copied-tooltip';
    tooltip.textContent = 'Tautan disalin!';
    document.body.appendChild(tooltip);
    
    // Position tooltip near the button
    const btn = event.currentTarget;
    const rect = btn.getBoundingClientRect();
    tooltip.style.left = `${rect.left + window.scrollX}px`;
    tooltip.style.top = `${rect.top + window.scrollY - 40}px`;
    
    // Remove after animation
    setTimeout(() => {
      tooltip.classList.add('fade-out');
      setTimeout(() => tooltip.remove(), 300);
    }, 1500);
  }).catch(function(err) {
    console.error('Gagal menyalin: ', err);
  });
}
</script>

<style>
/* ======= PROFESSIONAL NEWS DETAIL STYLING ======= */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');

:root {
  --primary-color: #1a5f7a;     /* Biru Tua */
  --accent-color: #57a6a1;      /* Tosca Hijau Laut */
  --bg-light: #f7f9fb;
  --text-main: #333;
  --text-muted: #666;
  --radius-lg: 20px;
  --shadow-soft: 0 10px 30px rgba(0, 0, 0, 0.05);
  --font-main: 'Poppins', sans-serif;
  --white: #ffffff;
  --light-gray: #e0e5ec;
  --border-radius: 12px;
  --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

/* [Previous CSS styles remain exactly the same until .share-buttons] */

/* Enhanced Share Buttons */
.share-buttons {
  display: flex;
  flex-wrap: wrap;
  gap: 0.8rem;
}

.share-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background-color: var(--light-gray);
  color: var(--text-muted);
  font-size: 1.1rem;
  transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
  text-decoration: none;
  position: relative;
  overflow: hidden;
  border: none;
  cursor: pointer;
}

.share-btn::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.2);
  transform: scale(0);
  border-radius: 50%;
  transition: transform 0.3s ease;
}

.share-btn:hover::after {
  transform: scale(1.5);
  opacity: 0;
}

.share-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.whatsapp { background-color: #25D366; color: white; }
.facebook { background-color: #1877F2; color: white; }
.twitter { background-color: #1DA1F2; color: white; }
.instagram { 
  background: linear-gradient(45deg, #405DE6, #5851DB, #833AB4, #C13584, #E1306C, #FD1D1D);
  color: white;
}
.copy-link { background-color: var(--primary-color); color: white; }

.share-btn i {
  transition: transform 0.2s ease;
}

.share-btn:hover i {
  transform: scale(1.1);
}

/* Share Button Tooltip */
.share-btn .tooltip {
  position: absolute;
  bottom: -35px;
  left: 50%;
  transform: translateX(-50%);
  background: var(--primary-color);
  color: white;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 0.7rem;
  opacity: 0;
  visibility: hidden;
  transition: all 0.2s ease;
  white-space: nowrap;
  pointer-events: none;
}

.share-btn:hover .tooltip {
  opacity: 1;
  visibility: visible;
  bottom: -30px;
}

/* Copied Tooltip Animation */
.copied-tooltip {
  position: absolute;
  background: var(--primary-color);
  color: white;
  padding: 8px 12px;
  border-radius: 4px;
  font-size: 0.9rem;
  z-index: 1000;
  animation: fadeIn 0.3s ease;
}

.copied-tooltip.fade-out {
  animation: fadeOut 0.3s ease;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeOut {
  from { opacity: 1; transform: translateY(0); }
  to { opacity: 0; transform: translateY(-10px); }
}

/* [Rest of your previous CSS styles remain exactly the same] */
</style>

<?php include 'includes/footer.php'; ?>