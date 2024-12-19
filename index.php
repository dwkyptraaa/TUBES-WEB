<!DOCTYPE html>
<html>
<head>
<title>Eksplorasi Gunung Sulawesi Selatan</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="assets/style.css">
<style>
  /* Signup Form Style Start */
#signUpForm {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.8);
  justify-content: center;
  align-items: center;
  z-index: 1000;
  color: white;
  text-align: center;
  padding: 40px;
  box-sizing: border-box;
  animation: fadeIn 1s ease-in-out;
}

.signup-form {
  background-color: rgba(0, 0, 0, 0.85);
  padding: 50px;
  border-radius: 10px;
  width: 100%;
  max-width: 400px;
  box-sizing: border-box;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
  animation: zoomIn 0.5s ease-out;
}

.signup-form h2 {
  font-size: 2.5em;
  margin-bottom: 20px;
  text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
}

.signup-form input {
  width: 100%;
  padding: 15px;
  margin: 10px 0;
  font-size: 1.2em;
  border-radius: 5px;
  border: 2px solid #ddd;
  background-color: transparent;
  color: white;
  transition: border-color 0.3s ease;
}

.signup-form input:focus {
  border-color: #2196F3;
  outline: none;
}

.signup-form button {
  padding: 15px;
  font-size: 1.5em;
  background-color: #2196F3;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  width: 100%;
  margin-top: 20px;
  transition: background-color 0.3s ease;
}

.signup-form button:hover {
  background-color: #009719;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
}

/* Close button for signup */
.close-btn {
  position: absolute;
  top: 10px;
  right: 50px;
  font-size: 2em;
  color: white;
  cursor: pointer;
  transition: color 0.3s ease;
}

.close-btn:hover {
  color: #2196F3;
}
/* Signup Form Style End */
</style>
</head>
<body>

<!-- Landing Section Start -->
<div class="bgimg w3-display-container w3-animate-opacity">
  <div class="w3-display-middle">
    <h1 class="w3-animate-top">Eksplorasi Gunung Sulawesi Selatan: Keindahan Alam yang Menantang</h1>
    <p>Menjelajahi keindahan alam yang memukau di Sulawesi Selatan.</p>
    <hr>
    <button class="start-btn" onclick="scrollToAbout()">Mulai</button>
  </div>
  <button class="login-btn" onclick="showLoginForm()">Login</button>
</div>
<!-- Landing Section End -->

<!-- About Section Start -->
<div id="about" class="about-section">
  <div class="about-container">
    <h2 class="about-title">Tentang Kami</h2>
    <p class="about-description">
      Eksplorasi Gunung Sulawesi Selatan adalah platform untuk pencinta alam yang ingin menemukan keindahan pegunungan di Sulawesi Selatan. Kami menyediakan informasi terkini dan terpercaya untuk perjalanan Anda.
    </p>
    <div class="about-features">
      <div class="feature-card">
        <img class="feature-img" src="assets/mountain1.jpg" alt="Gunung Latimojong" style="height:145px">
        <h3>Gunung Latimojong</h3>
        <p>Gunung tertinggi di Sulawesi Selatan dengan panorama memukau.</p>
      </div>
      <div class="feature-card">
        <img class="feature-img" src="assets/mountain2.jpg" alt="Gunung Bawakaraeng">
        <h3>Gunung Bawakaraeng</h3>
        <p>Destinasi populer bagi pendaki pemula hingga profesional.</p>
      </div>
      <div class="feature-card">
        <img class="feature-img" src="assets/mountain3.jpg" alt="Gunung Bulu Baria">
        <h3>Gunung Bulu Baria</h3>
        <p>Tujuan favorit dengan keindahan jalur pendakian yang menantang dan memukau.</p>
      </div>
    </div>
  </div>
</div>
<!-- About Section End -->

<!-- Login Form Start -->
<div id="loginForm" class="w3-display-container" style="display: none;">
  <div class="login-form">
    <h2 style="font-weight: bold">Login</h2>
    <form method="POST" action="cek_login.php">
      <input type="text" name="login_username" id="username" placeholder="Username" value="<?php echo isset($_COOKIE['username']) ? $_COOKIE['username'] : ''; ?>" required>
      <input type="password" name="login_password" id="password" placeholder="Password" required>

      <!-- Remember Me -->
      <label class="remember-me-label">
        <input type="checkbox" name="remember" id="remember"> Remember Me
      </label>
      <button type="submit">Login</button>
    </form>
    <!-- Create Account Link -->
    <div class="create-account">
      <p>Belum punya akun? <a href="#" style="color: #2196F3;" onclick="showSignUpForm()">Buat akun</a></p>
    </div>
    <!-- Close button for login form -->
    <span class="close-btn" onclick="hideLoginForm()">&times;</span>
  </div>
</div>
<!-- Login Form End -->

<!-- Sign Up Form Start -->
<div id="signUpForm" class="w3-display-container" style="display: none;">
  <div class="signup-form">
    <h2 style="font-weight: bold">Buat Akun</h2>
    <form method="POST" action="cek_register.php">
      <input type="text" name="fullname" id="fullname" placeholder="Nama Lengkap" required>
      <input type="text" name="signup_username" id="signup_username" placeholder="Username" required>
      <input type="email" name="email" id="email" placeholder="Email" required>
      <input type="password" name="signup_password" id="signup_password" placeholder="Password" required>
      <input type="password" name="confirm_password" id="confirm_password" placeholder="Konfirmasi Password" required>

      <!-- Checkbox untuk persyaratan dan kebijakan -->
      <label class="terms-label">
        <input type="checkbox" name="terms" id="terms" required> Saya setuju dengan <a href="syaratKetentuan.php" style="color: #2196F3;">Syarat dan Ketentuan</a>
      </label>
      <button type="submit">Daftar</button>
    </form>
    <!-- Login Link -->
    <div class="login-link">
      <p>Sudah punya akun? <a href="#" style="color: #2196F3;" onclick="showLoginForm()">Masuk</a></p>
    </div>
    <!-- Close button for signup form -->
    <span class="close-btn" onclick="hideSignUpForm()">&times;</span>
  </div>
</div>
<!-- Sign Up Form End -->

<div class="footer-section" style="text-align: center; padding: 20px; background-color: #f1f1f1; margin-top: 50px;">
  <p style="font-size: 1.2em; color: #333;">
    <strong>Ingin tahu lebih banyak detail tentang gunung-gunung di Sulawesi Selatan?</strong> 
    <br> Silakan <a href="#" style="color: #2196F3; font-weight: bold;" onclick="showLoginForm()">Login</a> untuk informasi lebih lanjut!
  </p>
</div>

<script>
// Scroll to About Section
function scrollToAbout() {
    const aboutSection = document.getElementById('about');
    aboutSection.scrollIntoView({ behavior: 'smooth' });
}

// Show Login Form
function showLoginForm() {
    document.getElementById('loginForm').style.display = 'flex';
    document.getElementById('signUpForm').style.display = 'none'; // Hide SignUp form when Login is displayed
}

// Hide Login Form
function hideLoginForm() {
    document.getElementById('loginForm').style.display = 'none';
}

// Show Sign Up Form
function showSignUpForm() {
    document.getElementById('signUpForm').style.display = 'flex';
    document.getElementById('loginForm').style.display = 'none'; // Hide Login form when SignUp is displayed
}

// Hide Sign Up Form
function hideSignUpForm() {
    document.getElementById('signUpForm').style.display = 'none';
}
</script>

</body>
</html>
