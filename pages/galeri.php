<?php include 'includes/header.php'; ?>
<?php include 'admin/includes/db.php'; ?>

<section class="hero-galeri">
    <div class="hero-overlay">
        <div class="container">
            <h1 class="hero-title">Galeri Kegiatan Sekolah</h1>
            <p class="hero-subtitle">Kenangan terbaik dari setiap momen kegiatan kami.</p>
        </div>
    </div>
</section>

<section id="page-gallery" class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Galeri Kegiatan Sekolah</h2>
        
        <div class="gallery-container">
            <?php
            $items_per_page = 12;
            $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
            $offset = ($current_page - 1) * $items_per_page;
            
            $galeri = $koneksi->query("SELECT * FROM galeri ORDER BY tanggal_upload DESC LIMIT $offset, $items_per_page");
            if ($galeri->num_rows > 0):
                $items = [];
                while ($item = $galeri->fetch_assoc()):
                    $items[] = $item;
            ?>
                <div class="gallery-card" 
                     onclick="openModal(<?= htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8') ?>, <?= htmlspecialchars(json_encode($items), ENT_QUOTES, 'UTF-8') ?>)">
                    <img src="uploads/<?= htmlspecialchars($item['gambar']) ?>" 
                         class="gallery-img" 
                         alt="<?= htmlspecialchars($item['keterangan']) ?>">
                    <div class="gallery-caption">
                        <p><?= htmlspecialchars($item['keterangan']) ?></p>
                    </div>
                </div>
            <?php
                endwhile;
            else:
            ?>
                <div class="empty-gallery">
                    <i class="fas fa-images fa-3x mb-3" style="color: var(--accent-color);"></i>
                    <p>Belum ada foto galeri yang tersedia.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php
            $total_items = $koneksi->query("SELECT COUNT(*) AS count FROM galeri")->fetch_assoc()['count'];
            $total_pages = ceil($total_items / $items_per_page);
            
            if ($current_page > 1):
            ?>
                <a href="?page=<?= $current_page - 1 ?>">&laquo; Previous</a>
            <?php endif; ?>
            
            <?php for ($page = 1; $page <= $total_pages; $page++): ?>
                <a href="?page=<?= $page ?>" class="<?= $page == $current_page ? 'active' : '' ?>"><?= $page ?></a>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <a href="?page=<?= $current_page + 1 ?>">Next &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Modal Structure -->
<div id="galleryModal" class="modal-overlay">
    <span class="modal-close" onclick="closeModal()">&times;</span>
    <div class="modal-content">
        <div class="modal-nav modal-prev" onclick="navigateModal(-1)">&#10094;</div>
        <img id="modalImage" class="modal-img" src="" alt="">
        <div class="modal-nav modal-next" onclick="navigateModal(1)">&#10095;</div>
        <p id="modalCaption" class="modal-caption"></p>
    </div>
</div>

<script>
// Modal and Gallery Navigation
let currentIndex = 0;
let galleryItems = [];

function openModal(item, items) {
    galleryItems = items;
    currentIndex = items.findIndex(i => i.id === item.id);
    updateModal();
    document.getElementById('galleryModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('galleryModal').classList.remove('active');
    document.body.style.overflow = 'auto';
}

function navigateModal(direction) {
    currentIndex += direction;
    
    if (currentIndex < 0) {
        currentIndex = galleryItems.length - 1;
    } else if (currentIndex >= galleryItems.length) {
        currentIndex = 0;
    }
    
    updateModal();
}

function updateModal() {
    const item = galleryItems[currentIndex];
    document.getElementById('modalImage').src = 'uploads/' + item.gambar;
    document.getElementById('modalImage').alt = item.keterangan;
    document.getElementById('modalCaption').textContent = item.keterangan;
}

// Close modal when clicking outside image
document.getElementById('galleryModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('galleryModal');
    if (modal.classList.contains('active')) {
        if (e.key === 'Escape') {
            closeModal();
        } else if (e.key === 'ArrowLeft') {
            navigateModal(-1);
        } else if (e.key === 'ArrowRight') {
            navigateModal(1);
        }
    }
});
</script>

<?php include 'includes/footer.php'; ?>
