<?php
session_start(); // Mulai sesi
include 'alert.php';
include 'config.php';

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

// Ambil data username dari sesi
$username = $_SESSION['username'];

// Query untuk mengambil data gunung dari database
$sql = "SELECT * FROM data_gunung"; // Ganti sesuai nama tabel Anda
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jelajahi Gunung - Eksplorasi Sulawesi Selatan</title>
    <style>
        /* Reset Margin dan Padding */
* {
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    overflow-x: hidden; /* Hindari scroll horizontal */
}

/* Header */
header {
    background-color: #2980b9;
    color: white;
    text-align: center;
    padding: 15px 0;
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
}

.hamburger {
    position: absolute;
    top: 50%;
    left: 10px;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
}

/* Sidebar */
.sidebar {
    width: 0;
    background-color: #2c3e50;
    height: 100%;
    position: fixed;
    margin-top: 60px;
    top: 0;
    left: 0;
    overflow-y: hidden;
    padding-top: 60px;
    transition: width 0.3s ease;
    z-index: 999;
}

.sidebar.active {
    width: 250px;
}

.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar ul li {
    padding: 10px 20px;
}

.sidebar ul li a {
    color: white;
    text-decoration: none;
    font-size: 18px;
    display: block;
    padding: 10px;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.sidebar ul li a:hover {
    background-color: #2980b9;
}

/* Konten Utama */
.content {
    margin-top: 80px;
    margin-left: 0;
    padding: 20px;
    transition: margin-left 0.3s ease;
}

.content.sidebar-active {
    margin-left: 250px;
}

/* Tombol Detail */
.detail-button {
    display: inline-block;
    padding: 12px 20px;
    background-color: #2980b9;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-size: 16px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    border: none;
    outline: none;
}

/* Efek Hover pada Tombol */
.detail-button:hover {
    background-color: #1a6a9a;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transform: translateY(-1px); /* Efek angkat tombol */
}

/* Efek Fokus pada Tombol */
.detail-button:focus {
    background-color: #1a6a9a;
    outline: none;
}

/* Mountain Item */
.mountain-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.mountain-item {
    border: 1px solid #ddd;
    padding: 15px;
    background-color: #fff;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.mountain-item img {
    width: 100%;
    max-width: 400px;
    height: 250px;
    object-fit: cover;
    border-radius: 8px;
}

.mountain-item h2 {
    font-size: 1.5rem;
    margin-bottom: 10px;
}

.mountain-item p {
    font-size: 1rem;
    margin-bottom: 15px;
}
.no-data {
    text-align: center;
    color: #7f8c8d;
    font-size: 1.2rem;
    margin-top: 30px;
    padding: 20px;
    background-color: #ecf0f1;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid #ddd;
}

/* Responsif */
@media (max-width: 768px) {
  .sidebar {
      position: absolute;
      height: auto;
  }

  .content {
      padding: 60px 10px 10px; /* Sesuaikan padding atas untuk header */
  }
}
    </style>
</head>
<body>
    <!-- Header Start -->
    <header>
        <button class="hamburger" onclick="toggleSidebar()">&#9776;</button>
        <h1>Jelajahi Gunung</h1>
    </header>
    <!-- Header End -->

    <!-- Sidebar Start -->
    <aside id="sidebar" class="sidebar">
        <ul>
            <li><a href="dashboard_user.php">Dashboard</a></li>
            <li><a href="daftar_gunung.php">Jelajahi Gunung</a></li>
            <li><a href="logout.php">Keluar</a></li>
        </ul>
    </aside>
    <!-- Sidebar End -->

    <!-- Konten Utama Start -->
    <div id="content" class="content">
        <div class="mountain-container">
            <?php if ($result->num_rows > 0): ?>
                <?php $no = 1; ?>
                <?php while ($data = $result->fetch_assoc()): ?>
                    <div class="mountain-item">
                        <h2><?= $no ?>. <?= htmlspecialchars($data['nama_gunung']) ?></h2>
                        <?php if (!empty($data['foto'])): ?>
                            <img src="assets/<?= htmlspecialchars($data['foto']) ?>" alt="Gambar Gunung">
                        <?php else: ?>
                            <img src="assets/placeholder.jpg" alt="Tidak Ada Gambar">
                        <?php endif; ?>
                        <p><?= htmlspecialchars(mb_strimwidth($data['deskripsi'], 0, 100, '...')) ?></p>
                        <button type="button" onclick="location.href='detail_gunung.php?id_gunung=<?= htmlspecialchars($data['id_gunung']) ?>';" class="detail-button">Lihat Detail</button>
                    </div>
                    <?php $no++; ?>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-data">
                <p>Tidak ada data gunung yang tersedia.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Konten Utama End -->

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
<?php
$conn->close();
?>
