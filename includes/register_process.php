<!-- <?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // buat perilaku ketika username sudah ada
    // buat perilaku ketika password tidak sama
    // buat perilaku ketika register berhasil
    // buat perilaku ketika register gagal
}
?>  -->

<?php
// Hubungkan ke database
require_once 'config.php';

// Cek jika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil dan filter data input
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validasi dasar
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Semua field harus diisi.";
        header("Location: ../register.php?error=" . urlencode($error));
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
        header("Location: ../register.php?error=" . urlencode($error));
        exit;
    }

    if ($password !== $confirm_password) {
        $error = "Password dan konfirmasi tidak sama.";
        header("Location: ../register.php?error=" . urlencode($error));
        exit;
    }

    // Cek apakah email sudah ada
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $error = "Email sudah terdaftar.";
        header("Location: ../register.php?error=" . urlencode($error));
        exit;
    }

    // Simpan user ke database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $role = 'admin';

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        // Sukses registrasi
        header("Location: ../login.php?success=1");
        exit;
    } else {
        $error = "Gagal registrasi.";
        header("Location: ../register.php?error=" . urlencode($error));
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    // Akses langsung tidak diizinkan
    header("Location: ../register.php");
    exit;
}
