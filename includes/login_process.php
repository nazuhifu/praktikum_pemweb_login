<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    // Validasi input kosong
    if (empty($username) || empty($password)) {
        header("Location: ../login.php?error=" . urlencode("Username dan password harus diisi."));
        exit;
    }

    // Cek apakah username ada di database
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Cek password
        if (password_verify($password, $user['password'])) {
            // Login berhasil
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header("Location: ../dashboard.php");
            exit;
        } else {
            // Password salah
            header("Location: ../login.php?error=" . urlencode("Password salah."));
            exit;
        }
    } else {
        // Username tidak ditemukan
        header("Location: ../login.php?error=" . urlencode("Username tidak ditemukan."));
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    // Akses langsung tidak diperbolehkan
    header("Location: ../login.php");
    exit;
}
