<?php
include 'includes/auth.php';
include 'includes/db.php';

function buatSlug($string) {
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

$editMode = false;
$editData = null;
$error = '';
$success = '';

// Default categories since we don't have a kategori table
$kategori_options = [
    1 => 'Berita Sekolah',
    2 => 'Kegiatan',
    3 => 'Prestasi'
];

// Pagination settings
$per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); // Ensure page is at least 1
$offset = ($page - 1) * $per_page;

// Tambah berita
if (isset($_POST['tambah']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = sanitize($_POST['judul']);
    $slug = buatSlug($judul);
    $isi = sanitize($_POST['isi']);
    $penulis_id = $_SESSION['user_id'] ?? 1; // Default to 1 if session not set
    $kategori_id = isset($_POST['kategori_id']) ? intval($_POST['kategori_id']) : 1;
    $gambar = '';

    // Upload gambar jika ada
    if (!empty($_FILES['gambar']['name'])) {
        $uploadDir = '../uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = time() . '-' . preg_replace("/[^A-Za-z0-9.]/", '', basename($_FILES['gambar']['name']));
        $target = $uploadDir . $filename;

        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            if ($_FILES['gambar']['size'] <= 5242880) { // 5MB = 5 * 1024 * 1024 = 5242880 bytes
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
                    $gambar = $filename;
                } else {
                    $error = "Gagal mengupload gambar.";
                }
            } else {
                $error = "Ukuran file maksimal 5MB.";
            }
        } else {
            $error = "Hanya file JPG, JPEG, PNG, dan GIF yang diperbolehkan.";
        }
    }

    if (empty($error)) {
        $stmt = $koneksi->prepare("INSERT INTO berita (judul, slug, isi, gambar, penulis_id, kategori_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssii", $judul, $slug, $isi, $gambar, $penulis_id, $kategori_id);

        if ($stmt->execute()) {
            $success = "Berita berhasil ditambahkan!";
        } else {
            $error = "Gagal menambahkan berita: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Hapus berita
if (isset($_GET['hapus']) && is_numeric($_GET['hapus'])) {
    $id = intval($_GET['hapus']);

    $stmt = $koneksi->prepare("SELECT gambar FROM berita WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $oldImage = '';
    if ($row = $result->fetch_assoc()) {
        $oldImage = $row['gambar'];
    }
    $stmt->close();

    $stmt = $koneksi->prepare("DELETE FROM berita WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if (!empty($oldImage)) {
            $imagePath = '../uploads/' . $oldImage;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $success = "Berita berhasil dihapus!";
    } else {
        $error = "Gagal menghapus berita: " . $stmt->error;
    }
    $stmt->close();
}

// Edit mode
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $editMode = true;
    $id = intval($_GET['edit']);

    $stmt = $koneksi->prepare("SELECT * FROM berita WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $editData = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$editData) {
        $error = "Berita tidak ditemukan.";
        $editMode = false;
    }
}

// Simpan hasil edit
if (isset($_POST['update']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $judul = sanitize($_POST['judul']);
    $slug = buatSlug($judul);
    $isi = sanitize($_POST['isi']);
    $penulis_id = $_SESSION['user_id'] ?? 1; // Default to 1 if session not set
    $kategori_id = isset($_POST['kategori_id']) ? intval($_POST['kategori_id']) : 1;
    $gambar = $_POST['gambar_lama'] ?? '';

    if (!empty($_FILES['gambar']['name'])) {
        $uploadDir = '../uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = time() . '-' . preg_replace("/[^A-Za-z0-9.]/", '', basename($_FILES['gambar']['name']));
        $target = $uploadDir . $filename;

        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            if ($_FILES['gambar']['size'] <= 5242880) {
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
                    if (!empty($_POST['gambar_lama'])) {
                        $oldImage = '../uploads/' . $_POST['gambar_lama'];
                        if (file_exists($oldImage)) {
                            unlink($oldImage);
                        }
                    }
                    $gambar = $filename;
                } else {
                    $error = "Gagal mengupload gambar.";
                }
            } else {
                $error = "Ukuran file maksimal 5MB.";
            }
        } else {
            $error = "Hanya file JPG, JPEG, PNG, dan GIF yang diperbolehkan.";
        }
    }

    if (empty($error)) {
        $stmt = $koneksi->prepare("UPDATE berita SET judul = ?, slug = ?, isi = ?, gambar = ?, penulis_id = ?, kategori_id = ? WHERE id = ?");
        $stmt->bind_param("ssssiii", $judul, $slug, $isi, $gambar, $penulis_id, $kategori_id, $id);

        if ($stmt->execute()) {
            $success = "Berita berhasil diperbarui!";
        } else {
            $error = "Gagal memperbarui berita: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Get total count for pagination
$total_result = $koneksi->query("SELECT COUNT(*) as total FROM berita");
$total_row = $total_result->fetch_assoc();
$total = $total_row['total'];
$total_pages = ceil($total / $per_page);

// Get paginated news
$berita = $koneksi->query("SELECT * FROM berita ORDER BY tanggal DESC LIMIT $offset, $per_page");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Berita</title>
    <link rel="stylesheet" href="css/berita.css">
</head>
<body>

<div class="container">
    <div class="header">
        <h2><?= $editMode ? 'Edit Berita' : 'Tambah Berita Baru' ?></h2>
        <div class="counter">Total: <?= $total ?></div>
    </div>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <div class="card">
        <form method="POST" enctype="multipart/form-data">
            <?php if ($editMode): ?>
                <input type="hidden" name="id" value="<?= $editData['id'] ?>">
                <input type="hidden" name="gambar_lama" value="<?= htmlspecialchars($editData['gambar'] ?? '') ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="judul">Judul Berita</label>
                <input type="text" id="judul" name="judul" class="form-control" required value="<?= htmlspecialchars($editData['judul'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="isi">Isi Berita</label>
                <textarea id="isi" name="isi" class="form-control" rows="8" required><?= htmlspecialchars($editData['isi'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="kategori_id">Kategori</label>
                <select id="kategori_id" name="kategori_id" class="form-control" required>
                    <?php foreach ($kategori_options as $id => $nama): ?>
                        <option value="<?= $id ?>" <?= (isset($editData['kategori_id']) && $editData['kategori_id'] == $id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($nama) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="gambar">Gambar</label>
                <div class="custom-file-input">
                    <label for="gambar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z"/>
                            <path d="M7.646 4.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V14.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3z"/>
                        </svg>
                        Pilih Gambar
                    </label>
                    <input type="file" id="gambar" name="gambar" accept="image/jpeg, image/png, image/gif">
                </div>
                <div class="file-types">Format: JPG, JPEG, PNG, GIF. Ukuran maksimal: 5MB</div>
                
                <?php if ($editMode && !empty($editData['gambar'])): ?>
                    <div class="current-image-container">
                        <p>Gambar saat ini:</p>
                        <img src="../uploads/<?= htmlspecialchars($editData['gambar']) ?>" alt="Current Image" class="current-image">
                    </div>
                <?php endif; ?>
                
                <img id="imagePreview" src="#" alt="Preview" class="image-preview" style="display: none;">
            </div>

            <div class="form-group">
                <button type="submit" name="<?= $editMode ? 'update' : 'tambah' ?>" class="btn btn-success">
                    <?= $editMode ? 'Simpan Perubahan' : 'Tambah Berita' ?>
                </button>

                <?php if ($editMode): ?>
                    <a href="berita.php" class="btn btn-secondary">Batal</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Daftar Berita</h3>
        </div>
        
        <?php if ($berita && $berita->num_rows > 0): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Gambar</th>
                            <th>Kategori</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $berita->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['judul']) ?></td>
                                <td>
                                    <?php if (!empty($row['gambar'])): ?>
                                        <img src="../uploads/<?= htmlspecialchars($row['gambar']) ?>" alt="thumbnail" class="thumbnail">
                                    <?php else: ?>
                                        <span>-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($kategori_options[$row['kategori_id']] ?? 'Tidak ada kategori') ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($row['tanggal'])) ?></td>
                                <td>
                                    <div class="action-btns">
                                        <a href="?edit=<?= $row['id'] ?>" class="btn btn-primary">Edit</a>
                                        <a href="?hapus=<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('Anda yakin ingin menghapus berita ini?')">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php if ($page > 1): ?>
                        <li><a href="?page=<?= $page - 1 ?>">&laquo; Prev</a></li>
                    <?php else: ?>
                        <li class="disabled"><span>&laquo; Prev</span></li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li <?= $i == $page ? 'class="active"' : '' ?>>
                            <a href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <li><a href="?page=<?= $page + 1 ?>">Next &raquo;</a></li>
                    <?php else: ?>
                        <li class="disabled"><span>Next &raquo;</span></li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php else: ?>
            <div class="empty-gallery">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
                <p>Belum ada berita yang ditambahkan</p>
            </div>
        <?php endif; ?>
    </div>
    <a href="dashboard.php" class="btn-back">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#0369a1">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
    </svg>
    Kembali ke Dashboard
</a>
</div>

<script>
const imageInput = document.getElementById('gambar');
const imagePreview = document.getElementById('imagePreview');

imageInput.addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();

        reader.onload = function (event) {
            imagePreview.src = event.target.result; // Set the image source to the uploaded image
            imagePreview.style.display = 'block';  // Show the image preview
        }

        reader.readAsDataURL(file);
    } else {
        imagePreview.style.display = 'none'; // Hide the preview if no image is selected
        imagePreview.src = '#'; // Reset the image source
    }
});
</script>

<script>
    // Image preview functionality
    document.getElementById('gambar').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });

    // Show current image preview if in edit mode
    <?php if ($editMode && !empty($editData['gambar'])): ?>
        document.getElementById('gambar').addEventListener('change', function() {
            document.querySelector('.current-image-container').style.display = 'none';
        });
    <?php endif; ?>
</script>

</body>
</html>