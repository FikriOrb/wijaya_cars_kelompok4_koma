CREATE DATABASE IF NOT EXISTS wijaya_cars CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE wijaya_cars;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    google_id VARCHAR(255) NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(30) NULL,
    password VARCHAR(255) NULL,
    profile_pic TEXT NULL,
    verification_code VARCHAR(6) NULL,
    code_expiry DATETIME NULL,
    otp_last_sent DATETIME NULL,
    reset_code VARCHAR(6) NULL,
    reset_expiry DATETIME NULL,
    is_verified TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    car_name VARCHAR(150) NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    price BIGINT NOT NULL,
    category ENUM('SUV','Luxury','Sport') NOT NULL DEFAULT 'Luxury',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_email VARCHAR(255) NOT NULL,
    mobil VARCHAR(150) NOT NULL,
    warna VARCHAR(100) NOT NULL,
    velg VARCHAR(100) NOT NULL,
    mesin VARCHAR(100) NOT NULL,
    total_harga BIGINT NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'Menunggu Verifikasi',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_orders_user_email (user_email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
