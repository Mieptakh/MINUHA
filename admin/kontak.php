<?php
session_start();

// Implement proper authentication check
if (!isset($_SESSION['admin']) || empty($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

include 'includes/db.php';

// Hapus pesan
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $stmt = $koneksi->prepare("DELETE FROM kontak WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Pesan berhasil dihapus.'
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'Gagal menghapus pesan.'
        ];
    }
    $stmt->close();
    
    // Redirect to prevent form resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Ambil data kontak
$kontak = $koneksi->query("SELECT * FROM kontak ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Kontak</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/kontak.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><i class="fas fa-envelope"></i> Pesan Masuk</h2>
        </div>

        <?php if ($kontak->num_rows > 0): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Pesan</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $kontak->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= nl2br(htmlspecialchars(substr($row['pesan'], 0, 100))) ?><?= strlen($row['pesan']) > 100 ? '...' : '' ?></td>
                        <td><?= $row['tanggal'] ?></td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="showDetailModal(
                                '<?= htmlspecialchars(addslashes($row['nama'])) ?>', 
                                '<?= htmlspecialchars(addslashes($row['email'])) ?>', 
                                '<?= htmlspecialchars(addslashes($row['pesan'])) ?>'
                            )">
                                <i class="fas fa-eye"></i>
                            </button>
                            <a href="mailto:<?= htmlspecialchars($row['email']) ?>" class="btn btn-sm btn-success" title="Balas">
                                <i class="fas fa-reply"></i>
                            </a>
                            <a href="?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger delete-message" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <i class="fas fa-inbox"></i> Belum ada pesan masuk.
            </div>
        <?php endif; ?>

        <a href="dashboard.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Modal Detail Pesan -->
    <div id="detailModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5><i class="fas fa-circle-info"></i> Detail Pesan</h5>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body">
                <div class="modal-body-content">
                    <h6 id="modalNama"></h6>
                    <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                    <p><strong>Isi Pesan:</strong></p>
                    <p id="modalPesan"></p>
                </div>
            </div>
        </div>
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

            // Confirm delete with SweetAlert
            document.querySelectorAll('.delete-message').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const href = this.getAttribute('href');
                    
                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        text: 'Yakin ingin menghapus pesan ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = href;
                        }
                    });
                });
            });
        });

        function showDetailModal(nama, email, pesan) {
            const modal = document.getElementById('detailModal');
            document.getElementById('modalNama').innerText = nama;
            document.getElementById('modalEmail').innerText = email;
            document.getElementById('modalPesan').innerText = pesan;
            
            // Trigger reflow to enable animation
            modal.offsetWidth;
            
            modal.style.display = 'block';
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('detailModal');
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';}, 300);
        }

        window.onclick = function(event) {
            const modal = document.getElementById('detailModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>