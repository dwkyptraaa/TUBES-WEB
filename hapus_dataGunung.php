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
// Ambil data id_gunung dan nama file dari URL
$id_gunung = mysqli_real_escape_string($conn, $_GET['id']);
$foto = isset($_GET['foto']) ? mysqli_real_escape_string($conn, $_GET['foto']) : "";
$peta = isset($_GET['peta']) ? mysqli_real_escape_string($conn, $_GET['peta']) : "";

// Menghapus file foto jika ada
if ($foto != "" && file_exists("assets/$foto")) {
    unlink("assets/$foto");
}

// Menghapus file peta jika ada
if ($peta != "" && file_exists("assets/$peta")) {
    unlink("assets/$peta");
}

// Hapus data dari database
$query = mysqli_query($conn, "DELETE FROM data_gunung WHERE id_gunung='$id_gunung'");

if ($query) {
    echo "<script>
    document.getElementById('errorModal').style.display = 'flex';
    document.getElementById('modalMessage').textContent = 'Penghapusan Berhasil';
    document.getElementById('modalDescription').textContent = 'Data berhasil dihapus!';
    document.getElementById('closeBtn').onclick = function() {
        window.location = 'kelola_dataGunung.php';
    };
</script>";
exit();
} else {
    echo "<script>
    document.getElementById('errorModal').style.display = 'flex';
    document.getElementById('modalMessage').textContent = 'Penghapusan Gagal';
    document.getElementById('modalDescription').textContent = 'Tidak dapat menghapus data!';
    document.getElementById('closeBtn').onclick = function() {
        window.location = 'kelola_dataGunung.php';
    };
</script>";
exit();
}
?>
