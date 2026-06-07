<?php
declare(strict_types=1);

$env_path = __DIR__ . '/.env';
if (file_exists($env_path)) {
    $lines = file($env_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $_ENV[trim($parts[0])] = trim($parts[1]);
        }
    }
}

$isLocalhost = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1', '::1']);

if ($isLocalhost) {
    // Config untuk XAMPP Lokal
    define('DB_HOST', '127.0.0.1');
    define('DB_NAME', 'wijaya_cars');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('BASE_URL', '/wijaya_v2');
} else {
    // Config 100% Akurat untuk Server Baru InfinityFree (sql200)
    define('DB_HOST', 'sql200.infinityfree.com');
    define('DB_NAME', 'if0_42112247_wijaya');
    define('DB_USER', 'if0_42112247');
    define('DB_PASS', 'jaNWWikwV8V3gTG');
    define('BASE_URL', '');
}

// Google OAuth Config
define('GOOGLE_CLIENT_ID', $_ENV['GOOGLE_CLIENT_ID'] ?? 'YOUR_GOOGLE_CLIENT_ID');
define('GOOGLE_CLIENT_SECRET', $_ENV['GOOGLE_CLIENT_SECRET'] ?? 'YOUR_GOOGLE_CLIENT_SECRET');

function get_db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    try {
        try {
            // Coba koneksi langsung ke database (Untuk server production seperti InfinityFree)
            $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            // Jika gagal (database belum ada), coba buat database (Untuk localhost/development)
            $server = new PDO('mysql:host=' . DB_HOST . ';charset=utf8mb4', DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            $server->exec('CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
            
            $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        }

        ensure_schema($pdo);
        return $pdo;
    } catch (PDOException $e) {
        http_response_code(500);
        exit('Database connection failed: ' . $e->getMessage());
    }
}

function ensure_schema(PDO $pdo): void
{
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            google_id VARCHAR(255) NULL,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            phone VARCHAR(30) NULL,
            alamat TEXT NULL,
            koordinat VARCHAR(100) NULL,
            password VARCHAR(255) NULL,
            profile_pic TEXT NULL,
            verification_code VARCHAR(6) NULL,
            code_expiry DATETIME NULL,
            otp_last_sent DATETIME NULL,
            reset_code VARCHAR(6) NULL,
            reset_expiry DATETIME NULL,
            is_verified TINYINT(1) NOT NULL DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS cars (
            id INT AUTO_INCREMENT PRIMARY KEY,
            car_name VARCHAR(150) NOT NULL,
            file_name VARCHAR(255) NOT NULL,
            price BIGINT NOT NULL,
            category ENUM('SUV','Luxury','Sport') NOT NULL DEFAULT 'Luxury',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_email VARCHAR(255) NOT NULL,
            mobil VARCHAR(150) NOT NULL,
            warna VARCHAR(100) NOT NULL,
            velg VARCHAR(100) NOT NULL,
            mesin VARCHAR(100) NOT NULL,
            total_harga BIGINT NOT NULL,
            status VARCHAR(50) NOT NULL DEFAULT 'Menunggu Verifikasi',
            bukti_pembayaran VARCHAR(255) NULL,
            alamat_pengiriman TEXT NULL,
            koordinat_pengiriman VARCHAR(100) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_orders_user_email (user_email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    try {
        $pdo->exec("ALTER TABLE orders ADD COLUMN bukti_pembayaran VARCHAR(255) NULL");
        $pdo->exec("ALTER TABLE users ADD COLUMN alamat TEXT NULL");
        $pdo->exec("ALTER TABLE users ADD COLUMN koordinat VARCHAR(100) NULL");
        $pdo->exec("ALTER TABLE orders ADD COLUMN alamat_pengiriman TEXT NULL");
        $pdo->exec("ALTER TABLE orders ADD COLUMN koordinat_pengiriman VARCHAR(100) NULL");
    } catch (PDOException $e) {
        // Abaikan jika kolom sudah ada
    }

    $count = (int) $pdo->query('SELECT COUNT(*) FROM cars')->fetchColumn();
    if ($count > 0) {
        return;
    }

    $cars = [
        ['Alfa Romeo Giulia', 'alfa_romeo_giulia.png.png.png', 850000000, 'Sport'],
        ['Audi A8', 'audi_a8.png.png.png', 1750000000, 'Luxury'],
        ['Bentley Bentayga', 'bentley_bentayga.png.png.png', 6900000000, 'SUV'],
        ['BMW X5', 'bmw_x5.png.png.png', 1450000000, 'SUV'],
        ['Cadillac Escalade', 'cadillac_escalade.png.png.png', 2900000000, 'SUV'],
        ['Ferrari 488', 'ferrari_488.png.png.png', 10500000000, 'Sport'],
        ['Ford Mustang', 'ford_mustang.png.png.png', 1200000000, 'Sport'],
        ['Honda Civic Type R', 'honda_civic_type_r.png.png.png', 650000000, 'Sport'],
        ['Jaguar E-Type', 'jaguar_e_type.png.png.png', 1400000000, 'Luxury'],
        ['Lamborghini Huracan', 'lamborghini_huracan.png.png.png', 5800000000, 'Sport'],
        ['Lexus LC500', 'lexus_lc500.png.png.png', 2450000000, 'Luxury'],
        ['Mazda MX-5', 'mazda_mx5.png.png.png', 650000000, 'Sport'],
        ['McLaren 720s', 'mclaren_720s.png.png.png', 7200000000, 'Sport'],
        ['Mercedes C350', 'mercedes_c350_avantgarde.png.png.png', 1950000000, 'Luxury'],
        ['Porsche Cayenne', 'porsche_cayenne.png.png.png', 2200000000, 'SUV'],
        ['Range Rover', 'range_rover.png.png.png', 2900000000, 'SUV'],
        ['Tesla Model S', 'tesla_model_s.png.png.png', 2450000000, 'Luxury'],
        ['Toyota Tacoma', 'toyota_tacoma.png.png.png', 620000000, 'SUV'],
        ['BMW M850i', 'BMW M850i.jpg', 2450000000, 'Luxury'],
        ['Lamborghini Gallador', 'Lamborghini Gallador.jpg', 5800000000, 'Sport'],
        ['Porsche 911', 'porche.jpg', 2200000000, 'Sport'],
        ['Toyota Alphard', 'Toyota Alphard 2.5 GAT.jpg', 1100000000, 'Luxury'],
    ];

    $stmt = $pdo->prepare('INSERT INTO cars (car_name, file_name, price, category) VALUES (?, ?, ?, ?)');
    foreach ($cars as $car) {
        $stmt->execute($car);
    }
}

function rupiah(int|float|string $amount): string
{
    return 'Rp ' . number_format((float) $amount, 0, ',', '.');
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
