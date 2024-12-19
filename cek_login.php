<?php
session_start();
include 'alert.php';
include 'config.php'; // Koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi input
    if (!isset($_POST['login_username'], $_POST['login_password'])) {
        exit('Harap isi username dan password!');
    }

    $username = $_POST['login_username'];
    $password = $_POST['login_password'];

    // Cek username di database
    if ($stmt = $conn->prepare("SELECT * FROM users WHERE username = ?")) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Jika username ditemukan
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if ($password === $user['password']) {
                // Set session login
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['last_login_time'] = time();

                 // Jika "Remember Me" dicentang
                if (isset($_POST['remember'])) {
                    setcookie("username", $username, time() + 60, "/");
                }

                // Redirect sesuai role
                if ($user['role'] === 'admin') {
                    header("Location: dashboard_admin.php");
                } else {
                    header("Location: dashboard_user.php");
                }
                exit();
            } else {
                // Username atau Password salah
                echo "<script>
        document.getElementById('errorModal').style.display = 'flex';
        document.getElementById('modalMessage').textContent = 'Kesalahan';
        document.getElementById('modalDescription').textContent = 'Username atau Password yang Anda masukkan salah.';
        document.getElementById('closeBtn').onclick = function() {
            window.history.back();
        };
        </script>";
            }
        } else {
            // Username tidak ditemukan
            echo "<script>
        document.getElementById('errorModal').style.display = 'flex';
        document.getElementById('modalMessage').textContent = 'Kesalahan';
        document.getElementById('modalDescription').textContent = 'Username tidak ditemukan.';
        document.getElementById('closeBtn').onclick = function() {
            window.history.back();
        };
        </script>";
        }
        $stmt->close();
    } else {
        // Jika terjadi kesalahan pada query
        echo "Terjadi kesalahan pada database: " . $conn->error;
    }
}
?>
