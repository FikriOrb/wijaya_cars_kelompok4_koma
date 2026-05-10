<?php
// Mendapatkan nama file yang sedang dibuka
$current_page = basename($_SERVER['PHP_SELF']);
?>

<link rel="stylesheet" href="/wijaya_v2/navbar.css">

<nav class="navbar">
    <div class="logo">
        <img src="/wijaya_v2/models/Logo.png" alt="Logo">
    </div>

    <ul class="nav-links">
        <li><a href="/wijaya_v2/Beranda/index.php" class="<?= ($current_page == 'index.php') ? 'active' : ''; ?>">Home</a></li>
        <li><a href="/wijaya_v2/Gallery/gallery.php" class="<?= ($current_page == 'gallery.php') ? 'active' : ''; ?>">Gallery</a></li>
        <li><a href="/wijaya_v2/about/about.php" class="<?= ($current_page == 'about.php') ? 'active' : ''; ?>">About</a></li>
        <li><a href="/wijaya_v2/contact_us/contact.php" class="<?= ($current_page == 'contact.php') ? 'active' : ''; ?>">Contact us</a></li>
    </ul>

    <a href="/wijaya_v2/Login_create/Login.php" class="login-btn">Login</a>
</nav>