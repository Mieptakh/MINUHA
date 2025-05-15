<?php
// Pastikan variabel $errorCode ada dan valid, default ke 404 jika tidak
$code = (isset($errorCode) && is_int($errorCode)) ? $errorCode : 404;

// Daftar pesan error default berdasarkan kode
$errorMessages = [
    400 => 'Permintaan tidak valid.',
    401 => 'Anda harus login untuk mengakses halaman ini.',
    403 => 'Akses ditolak.',
    404 => 'Halaman yang Anda tuju tidak ditemukan atau mungkin sudah dipindahkan.',
    405 => 'Metode HTTP tidak diizinkan.',
    408 => 'Permintaan timeout.',
    429 => 'Terlalu banyak permintaan. Coba lagi nanti.',
    500 => 'Terjadi kesalahan server internal.',
    502 => 'Gateway error.',
    503 => 'Layanan sedang tidak tersedia. Coba lagi nanti.',
    504 => 'Gateway timeout.',
];

// Pesan fallback jika kode error tidak dikenal
$message = $errorMessages[$code] ?? 'Terjadi kesalahan yang tidak diketahui.';

// Tentukan ikon FontAwesome berdasarkan kategori error
if ($code >= 500) {
    $iconClass = 'fas fa-server'; // Server error
} elseif ($code >= 400 && $code < 500) {
    switch ($code) {
        case 401:
            $iconClass = 'fas fa-user-lock';
            break;
        case 403:
            $iconClass = 'fas fa-ban';
            break;
        case 404:
            $iconClass = 'fas fa-exclamation-triangle';
            break;
        case 405:
            $iconClass = 'fas fa-ban';
            break;
        case 429:
            $iconClass = 'fas fa-hourglass-half';
            break;
        default:
            $iconClass = 'fas fa-exclamation-circle'; // General client error
            break;
    }
} else {
    // Default icon untuk error lain (1xx, 2xx, 3xx jarang dipakai untuk halaman error)
    $iconClass = 'fas fa-info-circle';
}

// Set HTTP response code header sesuai error
http_response_code($code);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title><?= htmlspecialchars($code) ?> - Terjadi Kesalahan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    /* Style tetap sama seperti sebelumnya */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f9fafb;
      color: #1e293b;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      min-height: 100vh;
      padding: 2rem;
      text-align: center;
      animation: fadeIn 0.8s ease-out;
    }

    .icon-wrapper {
      font-size: 6rem;
      color: #dc2626;
      margin-bottom: 1.5rem;
      animation: popIn 0.6s ease-in-out;
    }

    h1 {
      font-size: 4rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    p {
      font-size: 1.25rem;
      color: #475569;
      margin-bottom: 2rem;
      max-width: 500px;
      line-height: 1.5;
    }

    a {
      text-decoration: none;
      background-color: #1a5f7a;
      color: #ffffff;
      padding: 0.85rem 1.75rem;
      border-radius: 0.75rem;
      font-weight: 600;
      font-size: 1rem;
      transition: all 0.3s ease;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    a:hover {
      background-color: #15707d;
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes popIn {
      0% {
        opacity: 0;
        transform: scale(0.7);
      }
      100% {
        opacity: 1;
        transform: scale(1);
      }
    }

    @media (max-width: 480px) {
      h1 {
        font-size: 2.8rem;
      }

      p {
        font-size: 1rem;
      }

      .icon-wrapper {
        font-size: 4.5rem;
      }
    }
  </style>
</head>
<body>
  <div class="icon-wrapper">
    <i class="<?= htmlspecialchars($iconClass) ?>"></i>
  </div>
  <h1><?= htmlspecialchars($code) ?></h1>
  <p><?= htmlspecialchars($message) ?></p>
  <a href="/">Kembali ke Beranda</a>
</body>
</html>
