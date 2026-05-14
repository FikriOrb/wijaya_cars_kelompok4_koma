<?php
declare(strict_types=1);

require_once __DIR__ . '/../auth.php';

header('Content-Type: application/json');

$search = trim((string) ($_GET['search'] ?? ''));
$category = trim((string) ($_GET['category'] ?? ''));
$allowedCategories = ['SUV', 'Luxury', 'Sport'];

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
