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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Gunung - Eksplorasi Gunung Sulawesi Selatan</title>
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
    <!-- Header Start -->
    <header>
        <button class="hamburger" onclick="toggleSidebar()">&#9776;</button>
        <h1>Kelola Data Gunung</h1>
    </header>
    <!-- Header End -->
     
    <div class="container">
        <!-- Sidebar Start -->
        <aside id="sidebar" class="sidebar">
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
        <section id="content" class="content">
            <h1 style="margin-top: 70px;">Tambah Data Gunung di Sulawesi Selatan</h1>
            <!-- Formulir Tambah Objek Wisata -->
            <form id="tourist-form" action="insert_dataGunung.php" method="post" enctype="multipart/form-data">
                <h2>Tambah Data Gunung</h2>
                <label for="nama_gunung">Nama Gunung:</label>
                <input type="text" id="nama_gunung" name="nama_gunung" placeholder="Masukkan nama gunung..." required>

                <label for="deskripsi">Deskripsi Gunung:</label>
                <textarea id="deskripsi" name="deskripsi" placeholder="Masukkan deskripsi gunung..." rows="4" required></textarea>

                <label for="foto">Gambar Gunung:</label>
                <input type="file" id="foto" name="foto">

                <label for="peta">Peta Gunung:</label>
                <input type="file" id="peta" name="peta">

                <div class="add-button">
                    <button type="submit">Simpan</button>
                    <button type="reset">Batal</button>
                </div>
            </form>

            <table id="tourist-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Gunung</th>
                        <th>Gambar</th>
                        <th>Deskripsi</th>
                        <th>Peta</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "config.php";
                    $query = mysqli_query($conn, "SELECT * FROM data_gunung ORDER BY id_gunung DESC");
                    $no = 0;
                    while ($data = mysqli_fetch_array($query)) {
                        $no++;
                    ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><?= htmlspecialchars($data['nama_gunung']) ?></td>
                            <td><img src="assets/<?= htmlspecialchars($data['foto']) ?>" width="100" alt="Gambar Gunung"></td>
                            <td><?= htmlspecialchars($data['deskripsi']) ?></td>
                            <td><img src="assets/<?= htmlspecialchars($data['peta']) ?>" width="100" alt="Peta Gunung"></td>
                            <td>
                                <a class="tombol edit" href="edit_dataGunung.php?id=<?= $data['id_gunung'] ?>">Edit</a>
                                <a class="tombol hapus" href="hapus_dataGunung.php?id=<?= $data['id_gunung'] ?>&foto=<?= htmlspecialchars($data['foto']) ?>">Hapus</a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </section>
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
