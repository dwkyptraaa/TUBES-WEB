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

// Mengambil data objek wisata berdasarkan id yang dikirimkan melalui URL
$query = mysqli_query($conn, "SELECT * FROM data_gunung WHERE id_gunung='$_GET[id]'");
$data = mysqli_fetch_array($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Gunung - Eksplorasi Gunung Sulawesi Selatan</title>
    <link rel="stylesheet" href="assets/styleKelolaData.css">
    <style>
         textarea {
    width: 100%;
    padding: 8px 12px;
    font-size: 14px;
    font-family: Arial, sans-serif;
    color: #555;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    resize: none; /* Mencegah pengguna mengubah ukuran */
}
    </style>
</head>
<body>
<body>
     <!-- Header Start -->
    <header>
        <button class="hamburger" onclick="toggleSidebar()">&#9776;</button>
        <h1>Edit Data Gunung</h1>
    </header>
    <!-- Header End -->

    <div class="container">
        <!-- Sidebar Start -->
        <aside class="sidebar">
            <nav>
                <ul>
                    <li><a href="dashboard_admin.php">Dashboard</a></li>
                    <li><a href="kelola_dataGunung.php">Kelola Data Gunung</a></li>
                    <li><a href="daftar_gunung_admin.php">Jelajahi Gunung</a></li>
                    <li><a href="logout.php">Keluar</a></li>
                </ul>
            </nav>
        </aside>
        <!-- Sidebar End -->

        <!-- Konten Utama Start -->
        <section class="content">
            <h1 style="margin-top: 70px;">Edit Data Gunung</h1>
            <form action="update_dataGunung.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_gunung" value="<?= htmlspecialchars($data['id_gunung']) ?>">

                <label for="nama_gunung">Nama Gunung:</label>
                <input type="text" name="nama_gunung" value="<?= htmlspecialchars($data['nama_gunung']) ?>" required>

                <label for="deskripsi">Deskripsi:</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>

                <label for="foto">Gambar Gunung:</label>
                <img src="assets/<?= htmlspecialchars($data['foto']) ?>" width="150">
                <input type="file" name="foto">

                <label for="peta">Peta Gunung:</label>
                <img src="assets/<?= htmlspecialchars($data['peta']) ?>" width="150">
                <input type="file" name="peta">

                <button type="submit">Simpan</button>
                <button type="button" onclick="location.href='kelola_dataGunung.php';">Batal</button>
            </form>
        </section>
        <!-- Konten Utama End -->
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const content = document.querySelector('.content');
            sidebar.classList.toggle('active');
            content.classList.toggle('sidebar-active');
        }
    </script>
</body>
</html>
