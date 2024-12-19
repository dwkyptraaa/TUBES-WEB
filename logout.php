<?php
session_start();
include 'alert.php';
// Cek jika user sudah login
if (!isset($_SESSION['username'])) {
    echo "<script>
    document.getElementById('errorModal').style.display = 'flex';
    document.getElementById('modalMessage').textContent = 'Akses Dibatasi';
    document.getElementById('modalDescription').textContent = 'Anda harus login terlebih dahulu!';
    document.getElementById('closeBtn').onclick = function() {
        window.location = 'index.php';
    };
</script>";
exit();
}
session_unset();
session_destroy();
if (isset($_COOKIE['username'])) {
    setcookie("username", "", time() - 3600, "/");
}
// Redirect ke halaman login
echo "<script>
    document.getElementById('errorModal').style.display = 'flex';
    document.getElementById('modalMessage').textContent = 'Logout';
    document.getElementById('modalDescription').textContent = 'Anda telah logout!';
    document.getElementById('closeBtn').onclick = function() {
        window.location = 'index.php';
    };
</script>";
exit();
?>
