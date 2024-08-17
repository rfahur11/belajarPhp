<?php
require 'function.php';

// Cek apakah tombol submit sudah ditekan atau belum
if( isset($_POST["submit"]) ) {
	
	// Cek apakah data berhasil ditambahkan atau tidak
	if( addRotiData($_POST) > 0 ) {
		echo "
			<script>
				alert('Data berhasil ditambahkan!');
				document.location.href = 'index.php';
			</script>
		";
	} else {
		echo "
			<script>
				alert('Data gagal ditambahkan!');
				document.location.href = 'index.php';
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
	<title>Tambah Data Roti</title>
	<link rel="stylesheet" href="style.css">
	<style>
		img.preview {
			max-width: 200px;
			max-height: 200px;
			margin-top: 10px;
			border: 2px solid #d2b48c;
			border-radius: 10px;
		}
	</style>
	<script>
		function previewImage() {
			const input = document.querySelector('#gambarRoti');
			const preview = document.querySelector('.img-preview');
			
			const reader = new FileReader();
			reader.readAsDataURL(input.files[0]);
			
			reader.onload = function(e) {
				preview.src = e.target.result;
			}
		}
	</script>
</head>
<body>
<div class="create-container">
	<h1>Tambah Data Roti</h1>
	
	<form action="" method="post" enctype="multipart/form-data">
		<ul>
			<li>
				<label for="namaRoti">Nama Roti: </label>
				<input type="text" name="namaRoti" id="namaRoti" required>
			</li>
			<li>
				<label for="nomorRoti">Nomor Roti: </label>
				<input type="text" name="nomorRoti" id="nomorRoti" required>
			</li>
			<li>
				<label for="deskripsiRoti">Deskripsi Roti: </label>
				<input type="text" name="deskripsiRoti" id="deskripsiRoti" required>
			</li>
			<li>
				<label for="rasaRoti">Rasa Roti: </label>
				<input type="text" name="rasaRoti" id="rasaRoti" required>
			</li>
			<li>
				<label for="gambarRoti">Gambar Roti: </label>
				<input type="file" name="gambarRoti" id="gambarRoti" onchange="previewImage()" required>
				<br>
				<img src="" alt="Preview" class="img-preview preview">
			</li>
			<li>
				<button type="submit" name="submit">Tambah Data!</button>
			</li>
		</ul>
	</form>
	</div>

</body>
</html>

