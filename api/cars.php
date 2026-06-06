<?php
declare(strict_types=1);

require_once __DIR__ . '/../auth.php';

header('Content-Type: application/json');

$search = trim((string) ($_GET['search'] ?? ''));
$category = trim((string) ($_GET['category'] ?? ''));
$allowedCategories = ['SUV', 'Luxury', 'Sport'];

try {
    $db = get_db();
    $db->exec("CREATE TABLE IF NOT EXISTS cars (
        id INT AUTO_INCREMENT PRIMARY KEY,
        car_name VARCHAR(255) NOT NULL,
        file_name VARCHAR(255) NOT NULL,
        price BIGINT NOT NULL,
        category VARCHAR(50) NOT NULL
    )");

    $check = $db->query("SELECT COUNT(*) FROM cars")->fetchColumn();
    if ($check == 0) {
        $db->exec("INSERT INTO cars (car_name, file_name, price, category) VALUES 
            ('Porsche 911', 'porche.jpg', 2200000000, 'Sport'),
            ('Lamborghini Gallador', 'Lamborghini Gallador.jpg', 5800000000, 'Luxury'),
            ('Toyota Alphard 2.5 GAT', 'Toyota Alphard 2.5 GAT.jpg', 1100000000, 'Luxury'),
            ('BMW M850i', 'BMW M850i.jpg', 3200000000, 'Luxury'),
            ('Mobil Sport', 'Mobil_Sport.jpg', 1500000000, 'Sport')
        ");
    }
} catch (Throwable $e) {
    // Ignore error, let it fail on SELECT
}

$sql = 'SELECT id, car_name, file_name, price, category FROM cars WHERE 1=1';
$params = [];

if ($search !== '') {
    $sql .= ' AND car_name LIKE ?';
    $params[] = '%' . $search . '%';
}

if (in_array($category, $allowedCategories, true)) {
    $sql .= ' AND category = ?';
    $params[] = $category;
}

$sql .= ' ORDER BY car_name ASC';

$stmt = get_db()->prepare($sql);
$stmt->execute($params);

$cars = array_map(static function (array $car): array {
    $car['id'] = (int) $car['id'];
    $car['price'] = (int) $car['price'];
    $car['price_label'] = rupiah($car['price']);
    return $car;
}, $stmt->fetchAll());

echo json_encode(['cars' => $cars], JSON_THROW_ON_ERROR);
