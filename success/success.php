<?php
declare(strict_types=1);

require_once __DIR__ . '/../auth.php';
$user = require_login();

$orderId = (int) ($_GET['order_id'] ?? 0);
$stmt = get_db()->prepare('SELECT * FROM orders WHERE id = ? AND user_email = ? LIMIT 1');
$stmt->execute([$orderId, $user['email']]);
$order = $stmt->fetch();

if (!$order) {
    redirect_to('/dashboard/index.php');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success - Wijaya Cars</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Pembayaran/pembayaran.css">
</head>
<body>
    <header class="payment-header">
        <a href="../dashboard/index.php"><img src="../models/Logo.png" alt="Wijaya Cars Logo"></a>
    </header>

    <main class="container success-wrapper">
        <div class="checkmark-circle"><div class="checkmark draw"></div></div>

        <div class="success-text">
            <h1>Payment Successful</h1>
            <p class="subtitle">Terima kasih, pesanan <b><?= e($order['mobil']); ?></b> Anda sedang diproses.</p>
            <p class="order-id">Order ID: <span>#WC-<?= (int) $order['id']; ?></span></p>
        </div>

        <div class="success-actions">
            <a href="../dashboard/index.php" class="btn-home">View Dashboard</a>
            <a href="../Gallery/gallery.php" class="btn-home secondary">Back to Gallery</a>
        </div>
    </main>

<?php include __DIR__ . '/../footer.php'; ?>
</body>
</html>
