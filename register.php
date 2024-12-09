<?php
require 'function.php';

// Cek apakah user sudah login
if (isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

// Cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST['register'])) {
    // Panggil fungsi registerUser untuk menyimpan data user
    if (registerUser($_POST) > 0) {
        echo "
            <script>
                alert('User baru berhasil ditambahkan!');
                document.location.href = 'login.php';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('User gagal ditambahkan!');
            </script>
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="register-container">
        <h1>Halaman Registrasi</h1>

        <form action="" method="post">
            <ul>
                <li>
                    <label for="email">Email: </label>
                    <input type="email" name="email" id="email" required>
                </li>
                <li>
                    <label for="username">Username: </label>
                    <input type="text" name="username" id="username" required>
                </li>
                <li>
                    <label for="password">Password: </label>
                    <input type="password" name="password" id="password" required>
                </li>
                <li>
                    <label for="password2">Konfirmasi Password: </label>
                    <input type="password" name="password2" id="password2" required>
                </li>
                <li>
                    <button type="submit" name="register">Register</button>
                </li>
            </ul>
        </form>

        <p class="login-link">Sudah punya akun? <a href="login.php">Login disini</a></p>
    </div>
</body>
</html>

