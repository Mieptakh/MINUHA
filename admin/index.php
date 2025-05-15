<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <!-- Link ke Google Fonts untuk font Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Tambahkan FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

<div class="login-container">
    <!-- Ganti logo.png dengan logo madrasah -->
    <img src="/images/logo/9899minuhalogo.png" alt="Logo Sekolah" class="logo">
    <h2>Login Admin</h2>

    <?php if (isset($_SESSION['login_error'])): ?>
        <p class="error"><?= $_SESSION['login_error']; unset($_SESSION['login_error']); ?></p>
    <?php endif; ?>

    <form method="POST" action="login-process" autocomplete="off">
        <div class="input-container">
            <input type="text" name="username" placeholder="Username" required autocomplete="username">
        </div>
        
        <div class="input-container">
            <input type="password" id="password" name="password" placeholder="Password" required autocomplete="current-password">
            <span class="password-toggle" onclick="togglePassword()">
                <i class="fas fa-eye"></i> <!-- Ikon mata dari FontAwesome -->
            </span>
        </div>

        <button type="submit">Masuk</button>
    </form>

    <div class="footer-text">Copyright &copy; <?= date("Y") ?> Mi Nurul Huda | Developed by MHTeams</div>
</div>

<script>
    function togglePassword() {
        var passwordField = document.getElementById('password');
        var passwordToggle = document.querySelector('.password-toggle i');
        if (passwordField.type === "password") {
            passwordField.type = "text";
            passwordToggle.classList.remove('fa-eye');
            passwordToggle.classList.add('fa-eye-slash'); // Ganti ikon ketika password terlihat
        } else {
            passwordField.type = "password";
            passwordToggle.classList.remove('fa-eye-slash');
            passwordToggle.classList.add('fa-eye'); // Kembali ke ikon mata saat password tersembunyi
        }
    }
</script>

</body>
</html>
