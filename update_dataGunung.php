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

// Ambil data dari form
$id_gunung = mysqli_real_escape_string($conn, $_POST['id_gunung']);
$nama_gunung = mysqli_real_escape_string($conn, $_POST['nama_gunung']);
$deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

// Variabel untuk menyimpan pesan error
$error = "";

// Proses upload file foto (jika ada) Start
$foto_baru = "";
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $foto = $_FILES['foto']['name'];
    $lokasi = $_FILES['foto']['tmp_name'];
    $tipefile = $_FILES['foto']['type'];
    $ukuranfile = $_FILES['foto']['size'];

    // Validasi tipe dan ukuran file
    if (!in_array($tipefile, ["image/jpeg", "image/jpg", "image/png"])) {
        $error = "Tipe file foto tidak didukung. Hanya JPEG, JPG, dan PNG yang diperbolehkan.";
    } elseif ($ukuranfile >= 1000000) { // Maksimum 1MB
        $error = "Ukuran file foto lebih dari 1 MB.";
    } else {
        // Hapus file foto lama
        $querySelect = mysqli_query($conn, "SELECT foto FROM data_gunung WHERE id_gunung = '$id_gunung'");
        $data = mysqli_fetch_array($querySelect);
        if (file_exists("assets/$data[foto]") && $data['foto'] != "") {
            unlink("assets/$data[foto]");
        }

        // Simpan file foto baru
        $ext = pathinfo($foto, PATHINFO_EXTENSION);
        $foto_baru = basename($foto, ".$ext") . time() . ".$ext";
        move_uploaded_file($lokasi, "assets/$foto_baru");
    }
}
// Proses upload file foto (jika ada) End

// Proses upload file peta (jika ada) Start
$peta_baru = "";
if (isset($_FILES['peta']) && $_FILES['peta']['error'] === UPLOAD_ERR_OK) {
    $peta = $_FILES['peta']['name'];
    $lokasi = $_FILES['peta']['tmp_name'];
    $tipefile = $_FILES['peta']['type'];
    $ukuranfile = $_FILES['peta']['size'];

    // Validasi tipe dan ukuran file
    if (!in_array($tipefile, ["image/jpeg", "image/jpg", "image/png"])) {
        $error = "Tipe file peta tidak didukung. Hanya JPEG, JPG, dan PNG yang diperbolehkan.";
    } elseif ($ukuranfile >= 1000000) { // Maksimum 1MB
        $error = "Ukuran file peta lebih dari 1 MB.";
    } else {
        // Hapus file peta lama
        $querySelect = mysqli_query($conn, "SELECT peta FROM data_gunung WHERE id_gunung = '$id_gunung'");
        $data = mysqli_fetch_array($querySelect);
        if (file_exists("assets/$data[peta]") && $data['peta'] != "") {
            unlink("assets/$data[peta]");
        }

        // Simpan file peta baru
        $ext = pathinfo($peta, PATHINFO_EXTENSION);
        $peta_baru = basename($peta, ".$ext") . time() . ".$ext";
        move_uploaded_file($lokasi, "assets/$peta_baru");
    }
}
// Proses upload file peta (jika ada) End

// Update database Start
if ($error == "") {
    $query = "UPDATE data_gunung SET 
        nama_gunung = '$nama_gunung', 
        deskripsi = '$deskripsi'";

    // Tambahkan update untuk foto jika ada
    if ($foto_baru != "") {
        $query .= ", foto = '$foto_baru'";
    }

    // Tambahkan update untuk peta jika ada
    if ($peta_baru != "") {
        $query .= ", peta = '$peta_baru'";
    }

    $query .= " WHERE id_gunung = '$id_gunung'";

    if (mysqli_query($conn, $query)) {
        echo "<script>
    document.getElementById('errorModal').style.display = 'flex';
    document.getElementById('modalMessage').textContent = 'Update Data';
    document.getElementById('modalDescription').textContent = 'Data berhasil di ubah!';
    document.getElementById('closeBtn').onclick = function() {
        window.location = 'kelola_dataGunung.php';
    };
</script>";
exit();
    } else {
        echo "<script>alert('Tidak dapat menyimpan data');</script>";
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "<script>alert('$error');</script>";
    echo "<meta http-equiv='refresh' content='0; url=kelola_dataGunung.php'>";
}
// Update database End

mysqli_close($conn);
?>
