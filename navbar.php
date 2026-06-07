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
        <li><a href="<?= BASE_URL ?>/Beranda/index.php" class="<?= (strpos($requestPath, '/Beranda/') !== false) ? 'active' : ''; ?>">Beranda</a></li>
        <li><a href="<?= BASE_URL ?>/Gallery/gallery.php" class="<?= ($current_page === 'gallery.php') ? 'active' : ''; ?>">Galeri</a></li>
        <li><a href="<?= BASE_URL ?>/about/about.php" class="<?= ($current_page === 'about.php') ? 'active' : ''; ?>">Tentang</a></li>
        <li><a href="<?= BASE_URL ?>/contact_us/contact.php" class="<?= ($current_page === 'contact.php') ? 'active' : ''; ?>">Kontak</a></li>
        <?php if ($navUser): ?>
            <li><a href="<?= BASE_URL ?>/dashboard/index.php" class="<?= (strpos($requestPath, '/dashboard/') !== false) ? 'active' : ''; ?>">Dasbor</a></li>
        <?php endif; ?>
    </ul>

    <?php if ($navUser): ?>
        <a href="<?= BASE_URL ?>/Login_create/logout.php" class="login-btn">Keluar</a>
    <?php else: ?>
        <a href="<?= BASE_URL ?>/Login_create/Login.php" class="login-btn">Masuk</a>
    <?php endif; ?>
</nav>
