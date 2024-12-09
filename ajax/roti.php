<?php
require '../function.php';

// Ambil data keyword dan halaman dari request GET
$keyword = isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '';
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;

$jumlahDataPerHalaman = 5; // jumlah data per halaman
$awalData = ($jumlahDataPerHalaman * $halaman) - $jumlahDataPerHalaman;

// Query untuk mendapatkan total data berdasarkan keyword
$totalQuery = "SELECT COUNT(*) FROM roti WHERE 
                namaRoti LIKE '%$keyword%' OR
                nomorRoti LIKE '%$keyword%' OR
                deskripsiRoti LIKE '%$keyword%' OR
                rasaRoti LIKE '%$keyword%'";
$totalResult = mysqli_query($conn, $totalQuery);
$totalData = mysqli_fetch_array($totalResult)[0];
$jumlahHalaman = ceil($totalData / $jumlahDataPerHalaman);

// Query untuk mendapatkan data berdasarkan keyword dan pagination
$query = "SELECT * FROM roti WHERE 
            namaRoti LIKE '%$keyword%' OR
            nomorRoti LIKE '%$keyword%' OR
            deskripsiRoti LIKE '%$keyword%' OR
            rasaRoti LIKE '%$keyword%'
          LIMIT $awalData, $jumlahDataPerHalaman";
$result = mysqli_query($conn, $query);

// Tampilkan data
if (mysqli_num_rows($result) > 0) {
    echo '<table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>No.</th>
                <th>Nama Roti</th>
                <th>Nomor Roti</th>
                <th>Deskripsi Roti</th>
                <th>Rasa Roti</th>
                <th>Gambar Roti</th>
                <th>Aksi</th>
            </tr>';

    $i = $awalData + 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>
                <td>' . $i . '</td>
                <td>' . htmlspecialchars($row["namaRoti"]) . '</td>
                <td>' . htmlspecialchars($row["nomorRoti"]) . '</td>
                <td>' . htmlspecialchars($row["deskripsiRoti"]) . '</td>
                <td>' . htmlspecialchars($row["rasaRoti"]) . '</td>
                <td><img src="img/' . htmlspecialchars($row["gambarRoti"]) . '" width="50"></td>
                <td>
                    <a href="update.php?id=' . $row["id"] . '">Ubah</a> |
                    <a href="delete.php?id=' . $row["id"] . '" onclick="return confirm(\'Yakin ingin menghapus data ini?\');">Hapus</a>
                </td>
            </tr>';
        $i++;
    }
    echo '</table>';

    // Tampilkan pagination
    echo '<div class="pagination">';
    if ($halaman > 1) {
        echo '<a href="" data-page="' . ($halaman - 1) . '">&laquo; Prev</a>';
    }
    for ($i = 1; $i <= $jumlahHalaman; $i++) {
        $activeClass = ($i == $halaman) ? 'style="font-weight: bold; color: red;"' : '';
        echo '<a href="" data-page="' . $i . '" ' . $activeClass . '>' . $i . '</a> ';
    }
    if ($halaman < $jumlahHalaman) {
        echo '<a href="" data-page="' . ($halaman + 1) . '">Next &raquo;</a>';
    }
    echo '</div>';
} else {
    echo 'Tidak ada data ditemukan.';
}
?>
