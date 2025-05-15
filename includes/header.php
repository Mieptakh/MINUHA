<?php
// Tentukan halaman aktif dari REQUEST_URI, misalnya: "/tentang", "/berita", dst.
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Ambil segmen pertama dari URI, kalau misalnya URL-nya "/baca-berita/detail", maka hanya ambil "baca-berita"
$segments = explode('/', $uri);
$currentPage = $segments[0] ?? ''; // contoh: 'tentang', 'berita', dst.
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MI Nurul Huda</title>

    <!-- CSS Utama -->
    <link rel="stylesheet" href="/css/main.css" />

    <!-- CSS Dinamis berdasarkan halaman -->
    <?php if ($currentPage === ''): ?>
        <link rel="stylesheet" href="/css/home.css" />
    <?php elseif ($currentPage === 'tentang'): ?>
        <link rel="stylesheet" href="/css/about.css" />
    <?php elseif ($currentPage === 'berita'): ?>
        <link rel="stylesheet" href="/css/berita.css" />
    <?php elseif ($currentPage === 'baca-berita'): ?>
        <link rel="stylesheet" href="/css/baca-berita.css" />
    <?php elseif ($currentPage === 'galeri'): ?>
        <link rel="stylesheet" href="/css/galeri.css" />
    <?php elseif ($currentPage === 'kontak'): ?>
        <link rel="stylesheet" href="/css/kontak.css" />
    <?php endif; ?>

    <!-- Font & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>

<header class="header">
    <div class="header__container">
        <h1 class="header__logo">MI Nurul Huda</h1>
        
        <!-- Burger Menu Icon -->
        <button id="burger-menu" class="header__burger" aria-label="Toggle menu">
            <span class="header__burger-line"></span>
            <span class="header__burger-line"></span>
            <span class="header__burger-line"></span>
        </button>
        
        <nav id="main-nav" class="header__nav">
            <a href="/" class="header__nav-link <?= $currentPage === '' ? 'active' : '' ?>">Beranda</a>
            <a href="/tentang" class="header__nav-link <?= $currentPage === 'tentang' ? 'active' : '' ?>">Tentang</a>
            <a href="/berita" class="header__nav-link <?= $currentPage === 'berita' ? 'active' : '' ?>">Berita</a>
            <a href="/galeri" class="header__nav-link <?= $currentPage === 'galeri' ? 'active' : '' ?>">Galeri</a>
            <a href="/kontak" class="header__nav-link <?= $currentPage === 'kontak' ? 'active' : '' ?>">Kontak</a>
        </nav>
    </div>
</header>

<main>
