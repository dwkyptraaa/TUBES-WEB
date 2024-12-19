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
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user' || isSessionExpired()) {
    session_unset();
    session_destroy();
    setcookie("username", "", time() - 3600, "/");
    echo "<script>
        document.getElementById('errorModal').style.display = 'flex';
        document.getElementById('modalMessage').textContent = 'Akses Dibatasi';
        document.getElementById('modalDescription').textContent = 'Anda harus login sebagai User!';
        document.getElementById('closeBtn').onclick = function() {
            window.location = 'index.php';
        };
    </script>";
    exit();
}
$username = $_SESSION['username'];

// Validasi apakah ID ada di URL
if (!isset($_GET['id_gunung']) || empty($_GET['id_gunung'])) {
    echo "<script>
        document.getElementById('errorModal').style.display = 'flex';
        document.getElementById('modalMessage').textContent = 'ID tidak valid';
        document.getElementById('modalDescription').textContent = 'Silahkan masukkan ID yang valid!';
        document.getElementById('closeBtn').onclick = function() {
            window.location = 'daftar_gunung.php';
        };
    </script>";
}

// Ambil dan sanitasi ID
$id = mysqli_real_escape_string($conn, $_GET['id_gunung']);

// Query untuk mengambil data gunung berdasarkan ID
$query = "SELECT * FROM data_gunung WHERE id_gunung = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Jika data tidak ditemukan
if (!$data) {
    echo "<script>
        document.getElementById('errorModal').style.display = 'flex';
        document.getElementById('modalMessage').textContent = 'Data tidak di temukan';
        document.getElementById('modalDescription').textContent = 'Coba lagi!';
        document.getElementById('closeBtn').onclick = function() {
            window.location = 'daftar_gunung.php';
        };
    </script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Gunung - Eksplorasi Gunung Sulawesi Selatan</title>
    <link rel="stylesheet" href="assets/styleDashboard.css">
    <style>
        /* Style untuk kontainer utama */
        .mountain-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20px;
        }

        /* Style untuk gambar gunung */
        .mountain-container img {
            max-width: 100%;
            height: auto;
            border-radius: 10px; /* Opsional, membuat gambar sudut melengkung */
            margin-bottom: 15px;
        }

        /* Style untuk teks deskripsi */
        .mountain-container .description-title {
            font-weight: bold;
            font-size: 18px;
            margin-top: 10px;
        }

        .mountain-container p {
            font-size: 16px;
            line-height: 1.6;
            max-width: 800px; /* Membatasi lebar teks agar tidak terlalu lebar */
            text-align: justify;
            margin: 0 auto;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .mountain-container p {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <!-- Header Start -->
    <header>
        <button class="hamburger" onclick="toggleSidebar()">&#9776;</button>
        <h1>Detail Gunung</h1>
    </header>
    <!-- Header End -->

    <div class="container">
        <!-- Sidebar Start -->
        <aside id="sidebar" class="sidebar">
            <nav>
                <ul>
                    <li><a href="dashboard_user.php">Dashboard</a></li>
                    <li><a href="daftar_gunung.php">Jelajahi Gunung</a></li>
                    <li><a href="logout.php">Keluar</a></li>
                </ul>
            </nav>
        </aside>
        <!-- Sidebar End -->

        <!-- Konten Utama Start -->
        <div id="content" class="content">
            <div class="mountain-container">
                <div class="mountain-item">
                    <h2><?= htmlspecialchars($data['nama_gunung']) ?></h2>
                    <?php if (!empty($data['foto']) && file_exists("assets/" . $data['foto'])): ?>
                        <img src="assets/<?= htmlspecialchars($data['foto']) ?>" alt="Gambar Gunung">
                    <?php else: ?>
                        <img src="assets/placeholder.jpg" alt="Tidak Ada Gambar">
                    <?php endif; ?>
                    <!-- Deskripsi Bold -->
                    <div class="description-title">Deskripsi</div>
                    <br>
                    <p><?= nl2br(htmlspecialchars($data['deskripsi'])) ?></p>
                    <br>
                    <div class="description-title">Peta Pendakian</div>
                    <br>
                    <?php if (!empty($data['peta']) && file_exists("assets/" . $data['peta'])): ?>
                        <img style="width: 500px; border-radius:10px" src="assets/<?= htmlspecialchars($data['peta']) ?>" alt="Peta Gunung">
                    <?php else: ?>
                        <img src="assets/placeholder.jpg" alt="Tidak Ada Peta">
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- Konten Utama End -->
    </div>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            sidebar.classList.toggle('active');
            content.classList.toggle('sidebar-active');
        }
    </script>
</body>
</html>
