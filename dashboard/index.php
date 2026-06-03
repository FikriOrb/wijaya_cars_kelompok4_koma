<?php
declare(strict_types=1);

require_once __DIR__ . '/../auth.php';
$user = require_login();

$pdo = get_db();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim((string) ($_POST['first_name'] ?? ''));
    $lastName = trim((string) ($_POST['last_name'] ?? ''));
    $phone = preg_replace('/[^0-9+]/', '', (string) ($_POST['phone'] ?? ''));

    if ($firstName !== '' && $lastName !== '') {
        $stmt = $pdo->prepare('UPDATE users SET first_name = ?, last_name = ?, phone = ? WHERE id = ?');
        $stmt->execute([$firstName, $lastName, $phone, (int) $user['id']]);
        $message = 'Profil berhasil diperbarui.';
        $user = current_user();
    }
}

$ordersStmt = $pdo->prepare('SELECT * FROM orders WHERE user_email = ? ORDER BY created_at DESC');
$ordersStmt->execute([$user['email']]);
$orders = $ordersStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Wijaya Cars</title>
    <link rel="stylesheet" href="../Beranda/style.css">
    <link rel="stylesheet" href="../Login_create/tema_lc.css">
</head>
<body class="dashboard-body">
<?php include __DIR__ . '/../navbar.php'; ?>

<main class="dashboard-shell">
    <section class="dashboard-hero glass-panel">
        <div>
            <p class="eyebrow">Personal Dashboard</p>
            <h1><?= e($user['first_name'] . ' ' . $user['last_name']); ?></h1>
            <p><?= e($user['email']); ?></p>
        </div>
        <?php if (!empty($user['profile_pic'])): ?>
            <img class="profile-photo" src="<?= e($user['profile_pic']); ?>" alt="Profile photo">
        <?php endif; ?>
    </section>

    <section class="dashboard-grid">
        <form class="glass-panel dashboard-card" method="post">
            <h2>Profile</h2>
            <?php if ($message): ?><div class="alert"><?= e($message); ?></div><?php endif; ?>
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" value="<?= e($user['first_name']); ?>" required>
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" value="<?= e($user['last_name']); ?>" required>
            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" value="<?= e($user['phone'] ?? ''); ?>">
            <button type="submit" class="btn-submit">Save Profile</button>
        </form>

        <div class="glass-panel dashboard-card orders-card">
            <h2>Order History</h2>
            <?php foreach ($orders as $order): ?>
                <div class="order-row">
                    <div>
                        <strong>#WC-<?= (int) $order['id']; ?> <?= e($order['mobil']); ?></strong>
                        <span><?= e($order['warna']); ?> / <?= e($order['velg']); ?> / <?= e($order['mesin']); ?></span>
                    </div>
                    <div>
                        <b><?= rupiah((int) $order['total_harga']); ?></b>
                        <small><?= e($order['status']); ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (count($orders) === 0): ?>
                <p class="subtitle">Belum ada order.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../footer.php'; ?>
</body>
</html>
