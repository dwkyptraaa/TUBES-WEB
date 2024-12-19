<?php
// Mulai sesi
session_start();
include 'alert.php';
include 'config.php';

// Tangkap data yang dikirim melalui form
$fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
$username = mysqli_real_escape_string($conn, $_POST['signup_username']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['signup_password']);
$confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

// Validasi data
if (empty($fullname) || empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    // Menampilkan pesan error melalui modal
    echo "<script>
        document.getElementById('errorModal').style.display = 'flex';
        document.getElementById('modalMessage').textContent = 'Kesalahan';
        document.getElementById('modalDescription').textContent = 'Semua kolom harus diisi!';
        document.getElementById('closeBtn').onclick = function() {
            window.location.href = 'index.php';
        };
    </script>";
    exit();
}

if ($password !== $confirm_password) {
    // Menampilkan pesan error melalui modal
    echo "<script>
        document.getElementById('errorModal').style.display = 'flex';
        document.getElementById('modalMessage').textContent = 'Kesalahan';
        document.getElementById('modalDescription').textContent = 'Password dan konfirmasi password tidak cocok!';
        document.getElementById('closeBtn').onclick = function() {
            window.location.href = 'index.php';
        };
    </script>";
    exit();
}

// Cek apakah username sudah ada
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Menampilkan pesan error melalui modal
    echo "<script>
        document.getElementById('errorModal').style.display = 'flex';
        document.getElementById('modalMessage').textContent = 'Kesalahan';
        document.getElementById('modalDescription').textContent = 'Username sudah digunakan!';
        document.getElementById('closeBtn').onclick = function() {
            window.history.back();
        };
    </script>";
    exit();
}

// Cek apakah email sudah ada
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Menampilkan pesan error melalui modal
    echo "<script>
        document.getElementById('errorModal').style.display = 'flex';
        document.getElementById('modalMessage').textContent = 'Kesalahan';
        document.getElementById('modalDescription').textContent = 'Email sudah digunakan!';
        document.getElementById('closeBtn').onclick = function() {
            window.history.back();
        };
    </script>";
    exit();
}

// Masukkan data ke dalam database tanpa melakukan hashing password
$sql = "INSERT INTO users (fullname, username, email, password) VALUES ('$fullname', '$username', '$email', '$password')";

if ($conn->query($sql) === TRUE) {
    // Menampilkan pesan sukses melalui modal
    echo "<script>
        document.getElementById('errorModal').style.display = 'flex';
        document.getElementById('modalMessage').textContent = 'Pendaftaran Berhasil';
        document.getElementById('modalDescription').textContent = 'Pendaftaran berhasil! Silakan masuk.';
        document.getElementById('closeBtn').onclick = function() {
            window.location.href = 'index.php';
        };
    </script>";
} else {
    // Menampilkan pesan error melalui modal
    echo "<script>
        document.getElementById('errorModal').style.display = 'flex';
        document.getElementById('modalMessage').textContent = 'Kesalahan';
        document.getElementById('modalDescription').textContent = 'Terjadi kesalahan: " . $conn->error . "';
        document.getElementById('closeBtn').onclick = function() {
            window.location.href = 'index.php';
        };
    </script>";
}

// Tutup koneksi
$conn->close();
?>
