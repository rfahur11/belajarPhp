<?php
require 'function.php';

// Cek apakah user sudah login atau belum
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

// Tentukan jumlah data per halaman
$jumlahDataPerHalaman = 5;

// Cek apakah ada keyword pencarian
$keyword = isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '';

// Hitung total data
if ($keyword) {
    $totalQuery = "SELECT COUNT(*) as total FROM roti WHERE 
                    namaRoti LIKE '%$keyword%' OR
                    nomorRoti LIKE '%$keyword%' OR
                    deskripsiRoti LIKE '%$keyword%' OR
                    rasaRoti LIKE '%$keyword%'";
} else {
    $totalQuery = "SELECT COUNT(*) as total FROM roti";
}
$resultTotal = mysqli_query($conn, $totalQuery);
$jumlahData = mysqli_fetch_assoc($resultTotal)['total'];
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);

// Tentukan halaman aktif
$halamanAktif = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$awalData = ($halamanAktif - 1) * $jumlahDataPerHalaman;

// Ambil data dari database sesuai halaman yang aktif dan keyword pencarian
if ($keyword) {
    $query = "SELECT * FROM roti WHERE 
                namaRoti LIKE '%$keyword%' OR
                nomorRoti LIKE '%$keyword%' OR
                deskripsiRoti LIKE '%$keyword%' OR
                rasaRoti LIKE '%$keyword%'
              LIMIT $awalData, $jumlahDataPerHalaman";
} else {
    $query = "SELECT * FROM roti LIMIT $awalData, $jumlahDataPerHalaman";
}
$roti = query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Roti</title>
    <script src="js/script.js" defer></script>
</head>
<body>
    <h1>Daftar Roti</h1>

    <a href="logout.php">Logout</a> <br>
    <a href="create.php">Tambah Data</a> <br>

    <!-- Form Pencarian -->
    <form action="" method="get">
        <input type="text" name="keyword" id="keyword" size="40" autofocus placeholder="Masukkan keyword pencarian..." autocomplete="off" value="<?= htmlspecialchars($keyword); ?>">
        <button type="submit" id="tombol-cari">Cari!</button>
    </form>
    <br>

    <div id="container">
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>No.</th>
                <th>Nama Roti</th>
                <th>Nomor Roti</th>
                <th>Deskripsi Roti</th>
                <th>Rasa Roti</th>
                <th>Gambar Roti</th>
                <th>Aksi</th>
            </tr>
            <?php $i = $awalData + 1; ?>
            <?php foreach ($roti as $row) : ?>
            <tr>
                <td><?= $i; ?></td>
                <td><?= htmlspecialchars($row["namaRoti"]); ?></td>
                <td><?= htmlspecialchars($row["nomorRoti"]); ?></td>
                <td><?= htmlspecialchars($row["deskripsiRoti"]); ?></td>
                <td><?= htmlspecialchars($row["rasaRoti"]); ?></td>
                <td><img src="img/<?= htmlspecialchars($row["gambarRoti"]); ?>" width="50"></td>
                <td>
                    <a href="update.php?id=<?= $row["id"]; ?>">Ubah</a> |
                    <a href="delete.php?id=<?= $row["id"]; ?>" onclick="return confirm('Yakin ingin menghapus data ini?');">Hapus</a>
                </td>
            </tr>
            <?php $i++; ?>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Navigasi Pagination -->
    <div id="paginationLinks">
        <?php if ($halamanAktif > 1) : ?>
            <a href="?halaman=<?= $halamanAktif - 1; ?>&keyword=<?= htmlspecialchars($keyword); ?>">&laquo;</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $jumlahHalaman; $i++) : ?>
            <?php if ($i == $halamanAktif) : ?>
                <a href="?halaman=<?= $i; ?>&keyword=<?= htmlspecialchars($keyword); ?>" style="font-weight: bold; color: red;"><?= $i; ?></a>
            <?php else : ?>
                <a href="?halaman=<?= $i; ?>&keyword=<?= htmlspecialchars($keyword); ?>"><?= $i; ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($halamanAktif < $jumlahHalaman) : ?>
            <a href="?halaman=<?= $halamanAktif + 1; ?>&keyword=<?= htmlspecialchars($keyword); ?>">&raquo;</a>
        <?php endif; ?>
    </div>

    <script src="js/script.js" defer></script>
</body>
</html>
