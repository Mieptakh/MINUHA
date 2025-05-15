<?php
include 'includes/auth.php'; // Your authentication script
include 'includes/db.php'; // Your database connection script

// Get the admin name from the session and escape it to prevent XSS
$adminName = htmlspecialchars($_SESSION['admin']);

// Query to get the total count of berita (news)
$query_berita = "SELECT COUNT(*) AS total_berita FROM berita";
$result_berita = $koneksi->query($query_berita);
$berita_count = $result_berita->fetch_assoc()['total_berita'];

// Query to get the total count of galeri (gallery)
$query_galeri = "SELECT COUNT(*) AS total_galeri FROM galeri";
$result_galeri = $koneksi->query($query_galeri);
$galeri_count = $result_galeri->fetch_assoc()['total_galeri'];

// Query to get the total count of kontak (contact messages)
$query_kontak = "SELECT COUNT(*) AS total_kontak FROM kontak";
$result_kontak = $koneksi->query($query_kontak);
$kontak_count = $result_kontak->fetch_assoc()['total_kontak'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/dash.css">
</head>
<body>

    <div class="container">
        <div class="header">
            <div class="header-content">
                <h1>Dasbor Admin</h1>
                <p class="welcome-message">Selamat datang kembali, <strong><?= $adminName ?></strong></p>
            </div>
            <div class="logout-section">
                <a href="logout" class="btn btn-danger">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </a>
            </div>
        </div>

        <div class="dashboard-grid">
            <!-- Manajemen Berita Card -->
            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-newspaper"></i>
                </div>
                <h3 class="card-title">Manajemen Berita</h3>
                <p class="card-description">Buat, edit, dan kelola semua artikel berita di situs web Anda dengan mudah dan efisien.</p>
                <p class="card-total">Total Berita: <?= $berita_count ?></p>
                <a href="berita" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Kelola Berita
                </a>
            </div>

            <!-- Galeri Card -->
            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-image"></i>
                </div>
                <h3 class="card-title">Galeri</h3>
                <p class="card-description">Unggah, atur, dan tampilkan gambar dalam galeri foto Anda dengan antarmuka yang intuitif.</p>
                <p class="card-total">Total Galeri: <?= $galeri_count ?></p>
                <a href="galeri" class="btn btn-primary">
                    <i class="fas fa-images"></i> Kelola Galeri
                </a>
            </div>

            <!-- Pesan Kontak Card -->
            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3 class="card-title">Pesan Kontak</h3>
                <p class="card-description">Lihat, balas, dan kelola pesan dari pengunjung Anda secara efektif.</p>
                <p class="card-total">Total Pesan: <?= $kontak_count ?></p>
                <a href="kontak" class="btn btn-primary">
                    <i class="fas fa-comments"></i> Lihat Pesan
                </a>
            </div>
        </div>
    </div>


        <div class="quick-actions">
            <h2>Aksi Cepat</h2>
            <div class="action-buttons">
                <a href="berita" class="action-btn">
                    <i class="fas fa-plus"></i> Tambah Berita
                </a>
                <a href="galeri" class="action-btn">
                    <i class="fas fa-upload"></i> Unggah Gambar
                </a>
                <a href="kontak" class="action-btn">
                    <i class="fas fa-inbox"></i> Periksa Pesan
                </a>
                <!-- <a href="settings.php" class="action-btn">
                    <i class="fas fa-cog"></i> Pengaturan
                </a> -->
            </div>
        </div>
    </div>
</body>
</html>