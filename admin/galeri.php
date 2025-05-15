<?php
include 'includes/auth.php';
include 'includes/db.php';

// Secure file upload function
function secureUpload($file, $oldImage = null) {
    $uploadDir = '../uploads/';
    
    // Create directory if not exists
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $filename = time() . '-' . preg_replace('/[^a-zA-Z0-9_.-]/', '', basename($file['name']));
    $target = $uploadDir . $filename;
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    
    // Validate file
    $maxFileSize = 5 * 1024 * 1024; // 5MB
    $validMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
    
    // Use safer function for mime detection when available
    if (function_exists('finfo_file')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $fileMimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
    } else {
        $fileMimeType = mime_content_type($file['tmp_name']);
    }
    
    if (
        in_array($ext, $allowed) && 
        in_array($fileMimeType, $validMimeTypes) && 
        $file['size'] <= $maxFileSize
    ) {
        // Move file and process old image if successful
        if (move_uploaded_file($file['tmp_name'], $target)) {
            // Delete old image if exists
            if ($oldImage && file_exists('../uploads/' . $oldImage)) {
                unlink('../uploads/' . $oldImage);
            }
            return $filename;
        }
    }
    
    return false;
}

// Add Image
if (isset($_POST['tambah'])) {
    $keterangan = trim(htmlspecialchars($_POST['keterangan']));
    
    if (!empty($_FILES['gambar']['name'])) {
        $gambar = secureUpload($_FILES['gambar']);
        
        if ($gambar) {
            // Prepare and execute insert
            $stmt = $koneksi->prepare("INSERT INTO galeri (gambar, keterangan) VALUES (?, ?)");
            $stmt->bind_param("ss", $gambar, $keterangan);
            
            if ($stmt->execute()) {
                $_SESSION['alert'] = [
                    'type' => 'success',
                    'message' => 'Gambar berhasil diupload.'
                ];
            } else {
                $_SESSION['alert'] = [
                    'type' => 'error',
                    'message' => 'Gagal menyimpan gambar ke database.'
                ];
            }
            $stmt->close();
        } else {
            $_SESSION['alert'] = [
                'type' => 'warning',
                'message' => 'File tidak valid. Pastikan format jpg, png, atau gif dengan ukuran max 5MB.'
            ];
        }
    } else {
        $_SESSION['alert'] = [
            'type' => 'warning',
            'message' => 'Pilih gambar terlebih dahulu.'
        ];
    }
    
    header("Location: galeri.php");
    exit;
}

// Edit Image
if (isset($_POST['edit'])) {
    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    if (!$id) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'ID tidak valid.'
        ];
        header("Location: galeri.php");
        exit;
    }
    
    $keterangan = trim(htmlspecialchars($_POST['keterangan']));
    
    // Get old image data
    $stmt = $koneksi->prepare("SELECT gambar FROM galeri WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $gambarLama = $result->fetch_assoc();
    $stmt->close();
    
    if (!$gambarLama) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'Data gambar tidak ditemukan.'
        ];
        header("Location: galeri.php");
        exit;
    }
    
    $gambar = $gambarLama['gambar'];
    
    // If new image is uploaded
    if (!empty($_FILES['gambar']['name'])) {
        $newGambar = secureUpload($_FILES['gambar'], $gambarLama['gambar']);
        
        if ($newGambar) {
            $gambar = $newGambar;
        } else {
            $_SESSION['alert'] = [
                'type' => 'warning',
                'message' => 'File tidak valid. Perubahan gambar diabaikan.'
            ];
        }
    }
    
    // Update database
    $stmt = $koneksi->prepare("UPDATE galeri SET gambar = ?, keterangan = ? WHERE id = ?");
    $stmt->bind_param("ssi", $gambar, $keterangan, $id);
    
    if ($stmt->execute()) {
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Gambar berhasil diperbarui.'
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'Gagal memperbarui gambar.'
        ];
    }
    $stmt->close();
    
    header("Location: galeri.php");
    exit;
}

// Delete Image
if (isset($_GET['hapus'])) {
    $id = filter_var($_GET['hapus'], FILTER_VALIDATE_INT);
    if (!$id) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'ID tidak valid.'
        ];
        header("Location: galeri.php");
        exit;
    }
    
    // Get image name before deleting
    $stmt = $koneksi->prepare("SELECT gambar FROM galeri WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $gambar = $result->fetch_assoc();
    $stmt->close();
    
    if (!$gambar) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'Data gambar tidak ditemukan.'
        ];
        header("Location: galeri.php");
        exit;
    }
    
    // Delete from database
    $stmt = $koneksi->prepare("DELETE FROM galeri WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Delete image file
        if (!empty($gambar['gambar'])) {
            $filePath = '../uploads/' . $gambar['gambar'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Gambar berhasil dihapus.'
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'Gagal menghapus gambar.'
        ];
    }
    $stmt->close();
    
    header("Location: galeri.php");
    exit;
}

// Get gallery data with pagination
$page = isset($_GET['page']) ? filter_var($_GET['page'], FILTER_VALIDATE_INT) : 1;
if (!$page) $page = 1;
$limit = 8; // Items per page
$offset = ($page - 1) * $limit;

// Count total records for pagination
$countQuery = "SELECT COUNT(*) as total FROM galeri";
$countResult = $koneksi->query($countQuery);
$totalRecords = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $limit);

// Get paginated records
$galeri = $koneksi->query("SELECT * FROM galeri ORDER BY tanggal_upload DESC LIMIT $offset, $limit");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Kegiatan</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/galeri.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><i class="fas fa-images"></i> Galeri Kegiatan</h2>
            <?php if ($totalRecords > 0): ?>
            <div class="counter"><?= $totalRecords ?> foto</div>
            <?php endif; ?>
        </div>

        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-upload"></i> Upload Gambar Baru</h3>
            </div>
            <form method="POST" enctype="multipart/form-data" class="upload-form" id="uploadForm">
                <div class="form-group">
                    <label for="keterangan">Keterangan Gambar</label>
                    <input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="Masukkan keterangan gambar" required>
                </div>
                <div class="form-group">
                    <label for="gambar">Pilih Gambar</label>
                    <div class="custom-file-input">
                        <label for="gambar">
                            <i class="fas fa-file-image"></i> Pilih Gambar
                        </label>
                        <input type="file" name="gambar" id="gambar" accept="image/jpeg,image/png,image/gif" required>
                    </div>
                    <div class="file-name" id="file-name">Belum ada file yang dipilih</div>
                    <div class="file-types">Format: JPG, PNG, GIF (max 5MB)</div>
                    <img id="uploadPreview" class="image-preview">
                </div>
                <button type="submit" name="tambah" class="btn btn-upload" id="uploadBtn">
                    <i class="fas fa-upload"></i> Upload Gambar
                </button>
            </form>
        </div>

        <?php if ($galeri->num_rows > 0): ?>
        <div class="table-container">
            <div class="card-header">
                <h3><i class="fas fa-th-list"></i> Daftar Galeri</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Keterangan</th>
                        <th>Tanggal Upload</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $galeri->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <img src="../uploads/<?= htmlspecialchars($row['gambar']) ?>" 
                                 class="gallery-image" 
                                 data-src="../uploads/<?= htmlspecialchars($row['gambar']) ?>"
                                 data-caption="<?= htmlspecialchars($row['keterangan']) ?>">
                        </td>
                        <td><?= htmlspecialchars($row['keterangan']) ?></td>
                        <td><?= date('d M Y H:i', strtotime($row['tanggal_upload'])) ?></td>
                        <td>
                            <div class="action-btns">
                                <button onclick="openEditModal(<?= $row['id'] ?>, '<?= addslashes($row['keterangan']) ?>', '../uploads/<?= $row['gambar'] ?>')" 
                                        class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <a href="?hapus=<?= $row['id'] ?>" 
                                   class="btn btn-danger delete-image">
                                    <i class="fas fa-trash"></i> Hapus
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            
            <!-- Pagination -->
            <?php if($totalPages > 1): ?>
            <div class="pagination-container">
                <ul class="pagination">
                    <li class="<?= ($page <= 1) ? 'disabled' : '' ?>">
                        <a href="<?= ($page <= 1) ? '#' : '?page='.($page-1) ?>">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                    
                    <?php for($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="<?= ($page == $i) ? 'active' : '' ?>">
                            <a href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <li class="<?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                        <a href="<?= ($page >= $totalPages) ? '#' : '?page='.($page+1) ?>">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <?php endif; ?>
        </div>
        <?php else: ?>
            <div class="card">
                <div class="empty-gallery">
                    <i class="fas fa-images"></i>
                    <h3>Belum Ada Gambar</h3>
                    <p>Silakan upload gambar untuk ditampilkan di galeri.</p>
                </div>
            </div>
        <?php endif; ?>

        <a href="dashboard.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <!-- Modal Edit -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-edit"></i> Edit Gambar</h3>
                <span class="close">&times;</span>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="editId">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editKeterangan">Keterangan</label>
                        <input type="text" name="keterangan" id="editKeterangan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Gambar Saat Ini</label>
                        <img id="currentImage" class="current-image">
                    </div>
                    <div class="form-group">
                        <label for="editGambar">Gambar Baru (Opsional)</label>
                        <div class="custom-file-input">
                            <label for="editGambar">
                                <i class="fas fa-file-image"></i> Ubah Gambar
                            </label>
                            <input type="file" name="gambar" id="editGambar" accept="image/jpeg,image/png,image/gif">
                        </div>
                        <div class="file-name" id="edit-file-name">Tidak ada file baru yang dipilih</div>
                        <img id="imagePreview" class="image-preview">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" name="edit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Image Preview Modal -->
    <div id="previewModal" class="preview-modal">
        <span class="preview-close">&times;</span>
        <div class="preview-content">
            <img id="previewImage" class="preview-image">
            <div class="preview-caption" id="previewCaption"></div>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div class="loading" id="loading">
        <div class="spinner"></div>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show alert from PHP session if exists
            <?php 
            if (isset($_SESSION['alert'])) {
                $alert = $_SESSION['alert'];
                unset($_SESSION['alert']);
            ?>
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: '<?= $alert['type'] ?>',
                    title: '<?= $alert['message'] ?>',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            <?php } ?>

            // File input handling for upload form
            const fileInput = document.getElementById('gambar');
            const fileNameDisplay = document.getElementById('file-name');
            const previewImage = document.getElementById('uploadPreview');
            
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const fileName = this.files[0].name;
                        fileNameDisplay.textContent = fileName;
                        
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImage.src = e.target.result;
                            previewImage.style.display = 'block';
                        }
                        reader.readAsDataURL(this.files[0]);
                    } else {
                        fileNameDisplay.textContent = 'Belum ada file yang dipilih';
                        previewImage.style.display = 'none';
                    }
                });
            }

            // Show loading spinner when submitting forms
            const uploadForm = document.getElementById('uploadForm');
            const editForm = document.getElementById('editForm');
            const loading = document.getElementById('loading');
            
            if (uploadForm) {
                uploadForm.addEventListener('submit', function() {
                    loading.style.display = 'flex';
                });
            }
            
            if (editForm) {
                editForm.addEventListener('submit', function() {
                    loading.style.display = 'flex';
                });
            }

            // Confirm delete with SweetAlert
            document.querySelectorAll('.delete-image').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const href = this.getAttribute('href');
                    
                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        text: 'Yakin ingin menghapus gambar ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
                        cancelButtonText: '<i class="fas fa-times"></i> Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            loading.style.display = 'flex';
                            window.location.href = href;
                        }
                    });
                });
                       });

            // Preview gambar saat mengedit (jika form edit digunakan)
            const editFileInput = document.getElementById('editGambar');
            const editPreviewImage = document.getElementById('editUploadPreview');
            const editFileNameDisplay = document.getElementById('edit-file-name');

            if (editFileInput) {
                editFileInput.addEventListener('change', function () {
                    if (this.files && this.files[0]) {
                        const fileName = this.files[0].name;
                        editFileNameDisplay.textContent = fileName;

                        const reader = new FileReader();
                        reader.onload = function (e) {
                            editPreviewImage.src = e.target.result;
                            editPreviewImage.style.display = 'block';
                        }
                        reader.readAsDataURL(this.files[0]);
                    } else {
                        editFileNameDisplay.textContent = 'Belum ada file yang dipilih';
                        editPreviewImage.style.display = 'none';
                    }
                });
            }

            // Reset form upload saat modal ditutup
            const uploadModal = document.getElementById('uploadModal');
            if (uploadModal) {
                uploadModal.addEventListener('hidden.bs.modal', function () {
                    const form = document.getElementById('uploadForm');
                    if (form) form.reset();

                    fileNameDisplay.textContent = 'Belum ada file yang dipilih';
                    previewImage.style.display = 'none';
                });
            }

            // Reset form edit saat modal ditutup
            const editModal = document.getElementById('editModal');
            if (editModal) {
                editModal.addEventListener('hidden.bs.modal', function () {
                    const form = document.getElementById('editForm');
                    if (form) form.reset();

                    editFileNameDisplay.textContent = 'Belum ada file yang dipilih';
                    editPreviewImage.style.display = 'none';
                });
            }

        });
</script>

<script>
    // Fungsi membuka modal edit dan isi data ke form
    function openEditModal(id, keterangan, gambarUrl) {
        document.getElementById('editId').value = id;
        document.getElementById('editKeterangan').value = keterangan;
        document.getElementById('currentImage').src = gambarUrl;
        document.getElementById('editModal').style.display = 'block';
        document.body.classList.add('modal-open');
    }

    // Tutup modal edit
    document.querySelectorAll('.close, .close-modal').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('editModal').style.display = 'none';
            document.body.classList.remove('modal-open');

            // Reset form
            document.getElementById('editForm').reset();
            document.getElementById('edit-file-name').textContent = 'Tidak ada file baru yang dipilih';
            document.getElementById('imagePreview').style.display = 'none';
        });
    });

    // Preview gambar besar saat gambar diklik
    document.querySelectorAll('.gallery-image').forEach(img => {
        img.addEventListener('click', () => {
            const modal = document.getElementById('previewModal');
            document.getElementById('previewImage').src = img.dataset.src;
            document.getElementById('previewCaption').textContent = img.dataset.caption;
            modal.style.display = 'flex';
        });
    });

    // Tutup preview gambar besar
    document.querySelector('.preview-close').addEventListener('click', () => {
        document.getElementById('previewModal').style.display = 'none';
    });

    // Perbaiki preview gambar di form edit
    const editFileInput = document.getElementById('editGambar');
    const editPreviewImage = document.getElementById('imagePreview'); // Ganti dari editUploadPreview
    const editFileNameDisplay = document.getElementById('edit-file-name');

    if (editFileInput) {
        editFileInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const fileName = this.files[0].name;
                editFileNameDisplay.textContent = fileName;

                const reader = new FileReader();
                reader.onload = function (e) {
                    editPreviewImage.src = e.target.result;
                    editPreviewImage.style.display = 'block';
                }
                reader.readAsDataURL(this.files[0]);
            } else {
                editFileNameDisplay.textContent = 'Tidak ada file yang dipilih';
                editPreviewImage.style.display = 'none';
            }
        });
    }

    // Tutup modal jika klik di luar konten
    window.addEventListener('click', function(event) {
        const editModal = document.getElementById('editModal');
        const previewModal = document.getElementById('previewModal');
        if (event.target === editModal) {
            editModal.style.display = 'none';
            document.body.classList.remove('modal-open');
        }
        if (event.target === previewModal) {
            previewModal.style.display = 'none';
        }
    });
</script>
