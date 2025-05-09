-- -- Buat database

-- -- Buat tabel users
-- id 
-- username 
-- email 
-- password
-- role 
-- created_at

-- -- Insert admin default

-- Buat database
CREATE DATABASE IF NOT EXISTS tugas_login;

-- Gunakan database
USE tugas_login;

-- Buat tabel users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert admin default
INSERT INTO users (username, email, password, role) VALUES (
    'admin',
    'admin@admin.com',
    'admin123',
    'admin'
);
