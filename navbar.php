<?php
require_once __DIR__ . '/auth.php';
$requestPath = $_SERVER['REQUEST_URI'] ?? '';
$current_page = basename($_SERVER['PHP_SELF']);
$navUser = current_user();
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/navbar.css">

<nav class="navbar">
    <div class="logo">
        <a href="<?= BASE_URL ?>/Beranda/index.php"><img src="<?= BASE_URL ?>/models/Logo.png" alt="Wijaya Cars Logo"></a>
    </div>

    <ul class="nav-links">
        <li><a href="<?= BASE_URL ?>/Beranda/index.php" class="<?= (strpos($requestPath, '/Beranda/') !== false) ? 'active' : ''; ?>">Home</a></li>
        <li><a href="<?= BASE_URL ?>/Gallery/gallery.php" class="<?= ($current_page === 'gallery.php') ? 'active' : ''; ?>">Gallery</a></li>
        <li><a href="<?= BASE_URL ?>/about/about.php" class="<?= ($current_page === 'about.php') ? 'active' : ''; ?>">About</a></li>
        <li><a href="<?= BASE_URL ?>/contact_us/contact.php" class="<?= ($current_page === 'contact.php') ? 'active' : ''; ?>">Contact us</a></li>
        <?php if ($navUser): ?>
            <li><a href="<?= BASE_URL ?>/dashboard/index.php" class="<?= (strpos($requestPath, '/dashboard/') !== false) ? 'active' : ''; ?>">Dashboard</a></li>
        <?php endif; ?>
    </ul>

    <?php if ($navUser): ?>
        <a href="<?= BASE_URL ?>/Login_create/logout.php" class="login-btn">Logout</a>
    <?php else: ?>
        <a href="<?= BASE_URL ?>/Login_create/Login.php" class="login-btn">Login</a>
    <?php endif; ?>
</nav>
