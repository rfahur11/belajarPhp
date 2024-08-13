<?php 
session_start();
// koneksi ke database
$host = "localhost";
$user = "root";
$password = "";
$database = "phpDasar";

$conn = mysqli_connect($host, $user, $password, $database);


function query($query) {
	global $conn;
	$result = mysqli_query($conn, $query);
	$rows = [];
	while( $row = mysqli_fetch_assoc($result) ) {
		$rows[] = $row;
	}
	return $rows;
}

// Fungsi untuk pagination
function getPaginationData($table, $perPage) {
    global $conn;

    // Hitung total jumlah data
    $queryTotal = "SELECT COUNT(*) as total FROM $table";
    $resultTotal = mysqli_query($conn, $queryTotal);
    $totalData = mysqli_fetch_assoc($resultTotal)['total'];

    // Hitung jumlah halaman
    $totalPages = ceil($totalData / $perPage);

    // Tentukan halaman aktif
    $currentPage = (isset($_GET["halaman"])) ? $_GET["halaman"] : 1;

    // Tentukan data awal yang akan ditampilkan berdasarkan halaman aktif
    $startData = ($perPage * $currentPage) - $perPage;

    // Ambil data sesuai dengan halaman aktif
    $queryData = "SELECT * FROM $table LIMIT $startData, $perPage";
    $data = query($queryData);

    return [
        'data' => $data,
        'totalPages' => $totalPages,
        'currentPage' => $currentPage
    ];
}


//Login Fuction
function loginUser($data) {
    global $conn;

    $username = htmlspecialchars($data['username']);
    $password = htmlspecialchars($data['password']);

    // Cek apakah username ada di database
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");

    // Jika username ditemukan
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            // Set session
            $_SESSION['login'] = true;
            $_SESSION['username'] = $username;

            // Cek remember me
            if (isset($data['remember'])) {
                // Generate token
                $token = bin2hex(random_bytes(32));

                // Simpan token ke database
                $query = "UPDATE users SET remember_token = '$token' WHERE id = {$row['id']}";
                mysqli_query($conn, $query);

                // Set cookies
                setcookie('id', $row['id'], time() + (86400 * 30), "/"); // 30 hari
                setcookie('token', $token, time() + (86400 * 30), "/");
            }

            return true;
        }
    }

    return false;
}


//Registratrion Function
function registerUser($data) {
    global $conn;

    $email = htmlspecialchars($data['email']);
    $username = htmlspecialchars($data['username']);
    $password = htmlspecialchars($data['password']);
    $password2 = htmlspecialchars($data['password2']);

    // Cek apakah password dan konfirmasi password sesuai
    if ($password !== $password2) {
        echo "<script>
                alert('Konfirmasi password tidak sesuai!');
              </script>";
        return false;
    }

    // Cek apakah email atau username sudah terdaftar
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' OR username = '$username'");
    if (mysqli_fetch_assoc($result)) {
        echo "<script>
                alert('Email atau Username sudah terdaftar!');
              </script>";
        return false;
    }

    // Enkripsi password sebelum disimpan ke database
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Tambahkan user baru ke database
    $query = "INSERT INTO users (email, username, password) VALUES ('$email', '$username', '$password')";
    mysqli_query($conn, $query);

    // Periksa apakah data berhasil ditambahkan
    return mysqli_affected_rows($conn);
}

//Fungsi Tambah data (Create Data)
function addRotiData($data) {
    global $conn;

    // Mengamankan input dari user menggunakan htmlspecialchars
    $gambarRoti = htmlspecialchars($data["gambarRoti"]);
    $namaRoti = htmlspecialchars($data["namaRoti"]);
    $nomorRoti = htmlspecialchars($data["nomorRoti"]);
    $deskripsiRoti = htmlspecialchars($data["deskripsiRoti"]);
    $rasaRoti = htmlspecialchars($data["rasaRoti"]);

    // Validasi gambar
    $gambar = $_FILES['gambarRoti']['name'];
    $tmpName = $_FILES['gambarRoti']['tmp_name'];
    $error = $_FILES['gambarRoti']['error'];
    $size = $_FILES['gambarRoti']['size'];

    // Mengecek apakah tidak ada gambar yang diupload
    if ($error === 4) {
        echo "<script>alert('Pilih gambar terlebih dahulu!');</script>";
        return false;
    }

    // Mengecek ekstensi file gambar yang valid
    $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $gambarExtension = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
    if (!in_array($gambarExtension, $validExtensions)) {
        echo "<script>alert('Yang Anda upload bukan gambar!');</script>";
        return false;
    }

    // Mengecek ukuran file gambar (maksimal 2MB)
    if ($size > 2000000) {
        echo "<script>alert('Ukuran gambar terlalu besar!');</script>";
        return false;
    }

    // Mengenerate nama baru untuk gambar agar unik
    $newGambarName = uniqid() . '.' . $gambarExtension;
    move_uploaded_file($tmpName, './img/' . $newGambarName);

    // Menyimpan data ke database
    $query = "INSERT INTO roti (gambarRoti, namaRoti, nomorRoti, deskripsiRoti, rasaRoti)
              VALUES ('$newGambarName', '$namaRoti', '$nomorRoti', '$deskripsiRoti', '$rasaRoti')";
    
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}


function upload() {

	$namaFile = $_FILES['gambar']['name'];
	$ukuranFile = $_FILES['gambar']['size'];
	$error = $_FILES['gambar']['error'];
	$tmpName = $_FILES['gambar']['tmp_name'];

	// cek apakah tidak ada gambar yang diupload
	if( $error === 4 ) {
		echo "<script>
				alert('pilih gambar terlebih dahulu!');
			  </script>";
		return false;
	}

	// cek apakah yang diupload adalah gambar
	$ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
	$ekstensiGambar = explode('.', $namaFile);
	$ekstensiGambar = strtolower(end($ekstensiGambar));
	if( !in_array($ekstensiGambar, $ekstensiGambarValid) ) {
		echo "<script>
				alert('yang anda upload bukan gambar!');
			  </script>";
		return false;
	}

	// cek jika ukurannya terlalu besar
	if( $ukuranFile > 1000000 ) {
		echo "<script>
				alert('ukuran gambar terlalu besar!');
			  </script>";
		return false;
	}

	// lolos pengecekan, gambar siap diupload
	// generate nama gambar baru
	$namaFileBaru = uniqid();
	$namaFileBaru .= '.';
	$namaFileBaru .= $ekstensiGambar;

	move_uploaded_file($tmpName, 'img/' . $namaFileBaru);

	return $namaFileBaru;
}


function hapus($id) {
	global $conn;
	mysqli_query($conn, "DELETE FROM roti WHERE id = $id");
	return mysqli_affected_rows($conn);
}

//Update function
function updateRotiData($data) {
    global $conn;

    // Mengamankan input dari user menggunakan htmlspecialchars
    $id = $data["id"];
    $gambarRotiLama = htmlspecialchars($data["gambarRotiLama"]);
    $namaRoti = htmlspecialchars($data["namaRoti"]);
    $nomorRoti = htmlspecialchars($data["nomorRoti"]);
    $deskripsiRoti = htmlspecialchars($data["deskripsiRoti"]);
    $rasaRoti = htmlspecialchars($data["rasaRoti"]);

    // Cek apakah user memilih gambar baru atau tidak
    if ($_FILES['gambarRoti']['error'] === 4) {
        $gambarRoti = $gambarRotiLama;
    } else {
        // Validasi gambar baru
        $gambar = $_FILES['gambarRoti']['name'];
        $tmpName = $_FILES['gambarRoti']['tmp_name'];
        $size = $_FILES['gambarRoti']['size'];
        $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $gambarExtension = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));

        // Mengecek ekstensi file gambar yang valid
        if (!in_array($gambarExtension, $validExtensions)) {
            echo "<script>alert('Yang Anda upload bukan gambar!');</script>";
            return false;
        }

        // Mengecek ukuran file gambar (maksimal 2MB)
        if ($size > 2000000) {
            echo "<script>alert('Ukuran gambar terlalu besar!');</script>";
            return false;
        }

        // Mengenerate nama baru untuk gambar agar unik
        $newGambarName = uniqid() . '.' . $gambarExtension;
        move_uploaded_file($tmpName, './img/' . $newGambarName);
        $gambarRoti = $newGambarName;

        // Hapus gambar lama jika sudah diganti
        if (file_exists('./img/' . $gambarRotiLama)) {
            unlink('./img/' . $gambarRotiLama);
        }
    }

    // Menyusun query untuk memperbarui data
    $query = "UPDATE roti SET
                gambarRoti = '$gambarRoti',
                namaRoti = '$namaRoti',
                nomorRoti = '$nomorRoti',
                deskripsiRoti = '$deskripsiRoti',
                rasaRoti = '$rasaRoti'
              WHERE id = $id";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

//Search Roti
function searchRoti($keyword) {
    global $conn;

    // Mengamankan input dari user menggunakan htmlspecialchars
    $keyword = htmlspecialchars($keyword);

    // Query pencarian data
    $query = "SELECT * FROM roti
              WHERE 
              namaRoti LIKE '%$keyword%' OR
              nomorRoti LIKE '%$keyword%' OR
              deskripsiRoti LIKE '%$keyword%' OR
              rasaRoti LIKE '%$keyword%'";

    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    
    return $rows;
}