<?php
// Menyimpan konfigurasi database
$host = 'sql212.infinityfree.com';
$user = 'if0_38147269';
$pass = 'Qmj1impTzafs';
$db   = 'if0_38147269_minuhaweb';

// Mencoba koneksi ke database
$koneksi = new mysqli($host, $user, $pass, $db);

// Cek apakah koneksi berhasil
if ($koneksi->connect_error) {
    // Menampilkan pesan error jika koneksi gagal
    die('Koneksi ke database gagal: ' . $koneksi->connect_error);
}

// Mengatur karakter set ke UTF-8 untuk mendukung karakter multibahasa
$koneksi->set_charset("utf8");

// Menambahkan beberapa pengaturan tambahan (opsional)
$koneksi->query("SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'");

// Pastikan bahwa koneksi berhasil
if ($koneksi->ping()) {
    // Menginformasikan bahwa koneksi ke database berhasil (opsional)
    // echo "Koneksi ke database berhasil!";
} else {
    // Menangani kasus ketika koneksi gagal
    die("Error: Koneksi gagal di ping.");
}

// Koneksi berhasil
?>
