<?php
require 'function.php';

// Cek apakah user sudah login, jika sudah redirect ke index.php
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

// Cek cookie
if (isset($_COOKIE['id']) && isset($_COOKIE['token'])) {
    $id = $_COOKIE['id'];
    $token = $_COOKIE['token'];

    // Ambil data user berdasarkan id
    $result = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
    $row = mysqli_fetch_assoc($result);

    // Cek cookie dan token di database
    if ($token === $row['remember_token']) {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $row['username'];
    }
}

if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

// Cek apakah tombol login sudah ditekan
if (isset($_POST['login'])) {
    if (loginUser($_POST)) {
        echo "
            <script>
                alert('Login berhasil!');
                document.location.href = 'index.php';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('Username atau Password salah!');
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
    <title>Login</title>
</head>
<body>
    <h1>Halaman Login</h1>

    <form action="" method="post">
        <ul>
            <li>
                <label for="username">Username: </label>
                <input type="text" name="username" id="username" required>
            </li>
            <li>
                <label for="password">Password: </label>
                <input type="password" name="password" id="password" required>
            </li>
            <li>
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Remember me</label>
            </li>
            <li>
                <button type="submit" name="login">Login</button>
            </li>
        </ul>
    </form>

    <p>Belum punya akun? <a href="register.php">Daftar disini</a></p>
</body>
</html>
