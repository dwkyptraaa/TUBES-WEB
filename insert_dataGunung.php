<?php
session_start();
include 'alert.php';
include "config.php";

function isSessionExpired()
{
    if(isset($_SESSION['last_login_time'])){
        return (time() - $_SESSION['last_login_time']) > 60;
    }
    return true;
}

// Cek cookie "Remember Me"
if (!isset($_SESSION['username']) && isset($_COOKIE['username'])) {
    $_SESSION['username'] = $_COOKIE['username'];
    $_SESSION['last_login_time'] = time();
}
// Cek apakah pengguna sudah login menggunakan session dan role admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin' || isSessionExpired()) {
    session_unset();
    session_destroy();
    setcookie("username", "", time() - 3600, "/");
    echo "<script>
        document.getElementById('errorModal').style.display = 'flex';
        document.getElementById('modalMessage').textContent = 'Akses Dibatasi';
        document.getElementById('modalDescription').textContent = 'Anda harus login sebagai Admin!';
        document.getElementById('closeBtn').onclick = function() {
            window.location = 'index.php';
        };
    </script>";
    exit();
}

// Ambil data dari formulir
$nama_gunung = $_POST['nama_gunung'];
$deskripsi = $_POST['deskripsi'];

// Variabel untuk menyimpan nama file upload
$foto = "";
$peta = "";

// Proses upload file gambar (foto gunung)
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $foto = basename($_FILES['foto']['name']);
    $foto_target = "assets/" . $foto;

    // Pindahkan file ke folder tujuan
    if (!move_uploaded_file($_FILES['foto']['tmp_name'], $foto_target)) {
        echo "<script>
            document.getElementById('errorModal').style.display = 'flex';
            document.getElementById('modalMessage').textContent = 'Error Upload Foto';
            document.getElementById('modalDescription').textContent = 'Gagal mengupload foto! Silakan coba lagi.';
            document.getElementById('closeBtn').onclick = function() {
                window.history.back();
            };
        </script>";
        exit();
    }
} else {
    echo "<script>
        document.getElementById('errorModal').style.display = 'flex';
        document.getElementById('modalMessage').textContent = 'Foto Belum Di-upload';
        document.getElementById('modalDescription').textContent = 'Foto gunung belum di-upload!';
        document.getElementById('closeBtn').onclick = function() {
            window.history.back();
        };
    </script>";
    exit();
}

// Proses upload file peta (tidak wajib)
if (isset($_FILES['peta']) && $_FILES['peta']['error'] === UPLOAD_ERR_OK) {
    $peta = basename($_FILES['peta']['name']); // Ambil nama file
    $peta_target = "assets/" . $peta;

    // Pindahkan file ke folder tujuan
    if (!move_uploaded_file($_FILES['peta']['tmp_name'], $peta_target)) {
        $peta = ""; // Kosongkan jika gagal upload
    }
}

// Simpan data ke database menggunakan prepared statement untuk mencegah SQL Injection
$query = "INSERT INTO data_gunung (nama_gunung, deskripsi, foto, peta) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $query);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ssss", $nama_gunung, $deskripsi, $foto, $peta);
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
            document.getElementById('errorModal').style.display = 'flex';
            document.getElementById('modalMessage').textContent = 'Berhasil Menambahkan Data';
            document.getElementById('modalDescription').textContent = 'Data gunung berhasil ditambahkan!';
            document.getElementById('closeBtn').onclick = function() {
                window.location = 'kelola_dataGunung.php';
            };
        </script>";
    } else {
        echo "<script>
            document.getElementById('errorModal').style.display = 'flex';
            document.getElementById('modalMessage').textContent = 'Gagal Menambahkan Data';
            document.getElementById('modalDescription').textContent = 'Gagal menambahkan data gunung, coba lagi.';
            document.getElementById('closeBtn').onclick = function() {
                window.history.back();
            };
        </script>";
    }
    mysqli_stmt_close($stmt);
} else {
    echo "<script>
        document.getElementById('errorModal').style.display = 'flex';
        document.getElementById('modalMessage').textContent = 'Kesalahan Query';
        document.getElementById('modalDescription').textContent = 'Kesalahan pada query database!';
        document.getElementById('closeBtn').onclick = function() {
            window.history.back();
        };
    </script>";
}

mysqli_close($conn);
?>
