<?php
session_start();
include 'alert.php';

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

// Ambil username dari session
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User - Eksplorasi Gunung Sulawesi Selatan</title>
    <link rel="stylesheet" href="assets/styleDashboard.css">
</head>
<body>
    <!-- Header Start -->
    <header>
        <button class="hamburger" onclick="toggleSidebar()">&#9776;</button>
        <h1>Dashboard User</h1>
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
        <section id="content" class="content">
            <!-- Pesan Selamat Datang -->
            <div class="welcome-message">
                <?php
                echo "Selamat datang, <strong>$username</strong>! Anda login sebagai User.";
                ?>
            </div>
        </section>
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
