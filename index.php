<?php
// Ambil path URI tanpa query string dan trim slash depan-belakang
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, '/');

// Daftar route halaman publik
$routes = [
    '' => 'pages/index.php',
    'tentang' => 'pages/tentang.php',
    'berita' => 'pages/berita.php',
    'baca-berita' => 'pages/baca-berita.php',
    'galeri' => 'pages/galeri.php',
    'kontak' => 'pages/kontak.php',
];

// Daftar route halaman admin
$adminRoutes = [
    'admin' => 'admin/index.php',
    'admin/dashboard' => 'admin/dashboard.php',
    'admin/berita' => 'admin/berita.php',
    'admin/galeri' => 'admin/galeri.php',
    'admin/kontak' => 'admin/kontak.php',
    'admin/login-process' => 'admin/login-process.php',
    'admin/logout' => 'admin/logout.php',
];

// Gabungkan semua route menjadi satu array
$allRoutes = array_merge($routes, $adminRoutes);

/**
 * Fungsi menampilkan halaman error dinamis
 * @param int $code HTTP status code error yang ingin ditampilkan
 * @return void
 */
function showErrorPage(int $code = 404): void {
    http_response_code($code);

    $errorPage = __DIR__ . '/pages/error.php';

    if (file_exists($errorPage)) {
        // Kirim kode error sebagai variabel lokal ke halaman error
        $errorCode = $code;
        include $errorPage;
    } else {
        // Fallback sederhana jika halaman error.php tidak ditemukan
        echo "<!DOCTYPE html><html lang='id'><head><meta charset='UTF-8'><title>$code - Terjadi Kesalahan</title></head><body>";
        echo "<h1>$code - Terjadi Kesalahan</h1><p>Maaf, terjadi kesalahan pada server.</p>";
        echo "<a href='/'>Kembali ke Beranda</a>";
        echo "</body></html>";
    }
    exit;
}

// Jika URI adalah kode error 3 digit, langsung tampilkan halaman error
if (preg_match('/^\d{3}$/', $uri)) {
    $code = (int) $uri;
    $validErrors = [400,401,403,404,405,408,429,500,502,503,504];

    if (in_array($code, $validErrors, true)) {
        showErrorPage($code);
    } else {
        // Kode error tidak valid, fallback ke 404
        showErrorPage(404);
    }
}

// Routing halaman biasa
if (array_key_exists($uri, $allRoutes)) {
    $fileToInclude = __DIR__ . '/' . $allRoutes[$uri];

    if (file_exists($fileToInclude)) {
        $currentPage = basename($fileToInclude);

        ob_start();
        include $fileToInclude;
        $content = ob_get_clean();

        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <title>MI Nurul Huda</title>
            <link rel="stylesheet" href="/css/main.css" />
            <?php 
            // Load CSS khusus berdasarkan halaman untuk optimasi
            switch ($currentPage) {
                case 'index.php':
                    echo '<link rel="stylesheet" href="/css/home.css" />';
                    break;
                case 'tentang.php':
                    echo '<link rel="stylesheet" href="/css/about.css" />';
                    break;
                case 'galeri.php':
                    echo '<link rel="stylesheet" href="/css/galeri.css" />';
                    break;
                case 'berita.php':
                    echo '<link rel="stylesheet" href="/css/berita.css" />';
                    break;
                case 'baca-berita.php':
                    echo '<link rel="stylesheet" href="/css/baca-berita.css" />';
                    break;
                case 'kontak.php':
                    echo '<link rel="stylesheet" href="/css/kontak.css" />';
                    break;
            }
            ?>

            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
        </head>
        <body>
            <?= $content ?>
        </body>
        </html>
        <?php
    } else {
        // Jika file tidak ditemukan, tampilkan error server 500
        showErrorPage(500);
    }
} else {
    // Jika URI tidak ditemukan di routing, tampilkan error 404
    showErrorPage(404);
}
