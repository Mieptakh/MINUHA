<?php
// Menyertakan koneksi database
include __DIR__ . '/../admin/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form dan hindari kemungkinan XSS
    $nama = htmlspecialchars(trim($_POST['nama']));
    $email = htmlspecialchars(trim($_POST['email']));
    $pesan = htmlspecialchars(trim($_POST['pesan']));

    // Validasi data untuk mencegah input yang tidak sah
    if (!empty($nama) && !empty($email) && !empty($pesan)) {
        
        // Pastikan email valid
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

            // Menggunakan prepared statement untuk menghindari SQL Injection
            $stmt = $koneksi->prepare("INSERT INTO kontak (nama, email, pesan) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nama, $email, $pesan);

            // Eksekusi query dan cek hasilnya
            if ($stmt->execute()) {
                // Tampilkan pesan sukses dengan styling dan redirect
                echo '
                <!DOCTYPE html>
                <html lang="id">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Pesan Terkirim</title>
                    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
                    <style>
                        body {
                            font-family: "Poppins", sans-serif;
                            background: linear-gradient(135deg, #ff9a8b, #ff6a88, #ff5e62);
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            height: 100vh;
                            margin: 0;
                            overflow: hidden;
                        }
                        .container {
                            text-align: center;
                            background: #fff;
                            border-radius: 20px;
                            padding: 40px 30px;
                            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
                            animation: fadeIn 0.6s ease-out;
                            max-width: 400px;
                        }
                        @keyframes fadeIn {
                            from { opacity: 0; transform: translateY(20px); }
                            to { opacity: 1; transform: translateY(0); }
                        }
                        .icon-check {
                            width: 80px;
                            height: 80px;
                            margin: 0 auto 20px;
                            animation: scaleIn 0.8s ease-out;
                        }
                        @keyframes scaleIn {
                            from { transform: scale(0); opacity: 0; }
                            to { transform: scale(1); opacity: 1; }
                        }
                        .icon-check circle {
                            fill: #28a745;
                        }
                        .icon-check path {
                            stroke: #fff;
                            stroke-width: 4;
                            stroke-linecap: round;
                            stroke-linejoin: round;
                            fill: none;
                            stroke-dasharray: 40;
                            stroke-dashoffset: 40;
                            animation: dash 1s forwards ease-in-out;
                        }
                        @keyframes dash {
                            to {
                                stroke-dashoffset: 0;
                            }
                        }
                        h2 {
                            color: #28a745;
                            font-size: 24px;
                            margin-bottom: 20px;
                            font-weight: 600;
                        }
                        p {
                            font-size: 16px;
                            color: #555;
                            margin-bottom: 20px;
                        }
                        .btn {
                            background-color: #28a745;
                            color: white;
                            padding: 12px 20px;
                            border-radius: 8px;
                            font-weight: 600;
                            text-decoration: none;
                            transition: background-color 0.3s;
                        }
                        .btn:hover {
                            background-color: #218838;
                        }
                    </style>
                    <script>
                        setTimeout(function() {
                            window.location.href = "/kontak.php"; // Ganti ke halaman yang sesuai
                        }, 3500);
                    </script>
                </head>
                <body>
                    <div class="container">
                        <svg class="icon-check" viewBox="0 0 64 64">
                            <circle cx="32" cy="32" r="32"/>
                            <path d="M18 34 L28 44 L46 22"/>
                        </svg>
                        <h2>Terima Kasih!</h2>
                        <p>Pesan Anda berhasil dikirim. Anda akan dialihkan dalam beberapa detik...</p>
                        <a href="/kontak.php" class="btn">Kembali ke Halaman Kontak</a>
                    </div>
                </body>
                </html>';
            } else {
                echo "Terjadi kesalahan, coba lagi.";
            }

            // Tutup statement setelah eksekusi
            $stmt->close();
        } else {
            echo "Email tidak valid.";
        }
    } else {
        echo "Semua kolom harus diisi.";
    }
}
?>
