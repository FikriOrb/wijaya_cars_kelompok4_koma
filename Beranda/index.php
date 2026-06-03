<?php require_once __DIR__ . '/../auth.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wijaya Cars</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <?php include __DIR__ . '/../navbar.php'; ?>

    <section class="hero">
        <div class="hero-content">
            <h1 class="title">WIJAYA<br>CARS</h1>

            <p class="desc">
                Discover the ultimate definition of luxury and speed. 
                We bring you a curated collection of the world's finest automobiles, 
                designed for those who demand excellence in every journey.
            </p>
            <a class="discover-btn" href="../Gallery/gallery.php">DISCOVER</a>
        </div>
        <div class="hero-video-container">
            <video class="hero-video" autoplay muted loop>
                <source src="https://files.catbox.moe/a1cgp7.mp4" type="video/mp4">
            </video>
        </div>
    </section>

    <section class="slide-two">

        <section class="specials">
            <div class="top-bar">
                <h2>TODAYS SPECIALS</h2>

                <div class="filters">
                    <a href="../Gallery/gallery.php" class="view-all">View All Cars</a>
                    <button>SVU</button>
                    <button>Luxury</button>
                </div>
            </div>

            <div class="card-container">
                <a href="../Gallery/gallery.php" class="card">
                    <img src="../models/porche.jpg" alt="car 1">
                    <h3>Porche 911</h3>
                    <div class="price">Rp 2.200.000.000</div>
                    <div class="stars">★★★★★</div>
                </a>

                <a href="../Gallery/gallery.php" class="card">
                    <img src="../models/Lamborghini Gallador.jpg" alt="car 2">
                    <h3>Lamborghini Gallador</h3>
                    <div class="price">Rp 5.800.000.000</div>
                    <div class="stars">★★★★★</div>
                </a>

                <a href="../Gallery/gallery.php" class="card">
                    <img src="../models/Toyota Alphard 2.5 GAT.jpg" alt="car 3">
                    <h3>Toyota Alphard 2.5 GAT</h3>
                    <div class="price">Rp 1.100.000.000</div>
                    <div class="stars">★★★★★</div>
                </a>
            </div>
        </section>

        <section class="banner">
            <div class="banner-left"></div>

            <div class="banner-right">
                <h1>WIJAYA<br>CARS</h1>
                <a href="#" class="follow-btn">Follow us</a>
            </div>
        </section>

        <section class="slide-three">
            <div class="slide-three-left">
                <h1>WIJAYA CARS QUALITY RIDES</h1>
                <p>
                    A small river named Duden flows by their place and supplies it with the
                    necessary regelialia. It is a paradise.
                </p>

                <div class="icons">
                    <a href="#" class="icon-item">
                        <img src="../models/Motors_icon.jpg">
                        <span>Motors</span>
                    </a>

                    <a href="#" class="icon-item">
                        <img src="../models/pick_up_icon.jpg">
                        <span>Pick up</span>
                    </a>

                    <a href="#" class="icon-item">
                        <img src="../models/Luxury_icon.png">
                        <span>Luxury</span>
                    </a>
                </div>
            </div>

            <div class="slide-three-right">
                <img class="img-back" src="../models/Mobil_Sport.jpg">
                <img class="img-front" src="../models/Mobil_sport2.jpg">
            </div>
        </section>

        <section class="bottom-section">

            <div class="bottom-left">
                <img src="../models/Logo.png" class="footer-logo">

                <p>Medan - Indonesia</p>
                <p>+62822-7456-7521</p>
                <p>madyashafwan@gmail.com</p>

                <div class="social-icons">
                    <i class="fab fa-whatsapp"></i>
                    <i class="fab fa-facebook"></i>
                    <i class="fab fa-instagram"></i>
                    <i class="fab fa-twitter"></i>
                </div>
            </div>

            <div class="bottom-right">
                <form class="contact-form">
                    <div class="row-2">
                        <input type="text" placeholder="first">
                        <input type="text" placeholder="last">
                    </div>
                    <div class="row-2">
                        <input type="email" placeholder="email">
                        <input type="text" placeholder="phone number">
                    </div>
                    <div class="row-1">
                        <input type="text" placeholder="address">
                        <button type="submit">Send</button>
                    </div>
                </form>
            </div>

        </section>

    <?php include __DIR__ . '/../footer.php'; ?>

</body>
</html>
