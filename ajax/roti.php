<?php
require '../function.php';

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$jumlahDataPerHalaman = 5;
$jumlahData = count(query("SELECT * FROM roti WHERE 
    namaRoti LIKE '%$keyword%' OR 
    deskripsiRoti LIKE '%$keyword%' OR 
    rasaRoti LIKE '%$keyword%'
"));

$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
$awalData = ($jumlahDataPerHalaman * $page) - $jumlahDataPerHalaman;

$roti = query("SELECT * FROM roti WHERE 
    namaRoti LIKE '%$keyword%' OR 
    deskripsiRoti LIKE '%$keyword%' OR 
    rasaRoti LIKE '%$keyword%'
    LIMIT $awalData, $jumlahDataPerHalaman
");

// Tampilkan hasil pencarian dan pagination
?>

<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>No.</th>
        <th>Gambar</th>
        <th>Nama</th>
        <th>Nomor Roti</th>
        <th>Deskripsi</th>
        <th>Rasa</th>
        <th>Aksi</th>
    </tr>

    <?php $i = $awalData + 1; ?>
    <?php foreach ($roti as $row) : ?>
        <tr>
            <td><?= $i++; ?></td>
            <td><img src="<?= $row['gambarRoti']; ?>" width="50"></td>
            <td><?= $row['namaRoti']; ?></td>
            <td><?= $row['nomorRoti']; ?></td>
            <td><?= $row['deskripsiRoti']; ?></td>
            <td><?= $row['rasaRoti']; ?></td>
            <td>
                <a href="update.php?id=<?= $row['id']; ?>">ubah</a> |
                <a href="delete.php?id=<?= $row['id']; ?>" onclick="return confirm('yakin?');">hapus</a>
            </td>
        </tr>
    <?php endforeach; ?>

</table>

<div class="pagination">
    <?php if ($jumlahHalaman > 1): ?>
        <?php for ($i = 1; $i <= $jumlahHalaman; $i++) : ?>
            <a href="#" class="page-link" data-page="<?= $i; ?>"><?= $i; ?></a>
        <?php endfor; ?>
    <?php endif; ?>
</div>
