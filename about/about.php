<?php require_once __DIR__ . '/../auth.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - Wijaya Cars</title>
    <link rel="stylesheet" href="../Beranda/style.css">
    <link rel="stylesheet" href="style-about.css">
</head>
<body>

    <?php include __DIR__ . '/../navbar.php'; ?>

    <section class="about-hero">
        <div class="about-hero-content">
            <h1>MENGGERAKKAN MASA DEPAN</h1>
            <p>Wijaya Cars bukan sekadar dealer mobil. Kami adalah kurator kemewahan dan performa yang menghubungkan Anda dengan mesin impian.</p>
        </div>
    </section>

    <section class="story-section">
        <div class="story-container">
            <div class="story-image">
                <img src="../models/Lamborghini Gallador.jpg" alt="Our Story">
            </div>
            <div class="story-text">
                <h2>CERITA KAMI</h2>
                <p>
                    Didirikan di Medan pada tahun 2025, Wijaya Cars dimulai dari sebuah visi sederhana: menghadirkan pengalaman berkendara kelas dunia ke Indonesia. 
                    <br><br>
                    Kami memahami bahwa mobil bukan hanya alat transportasi, melainkan simbol pencapaian dan gaya hidup. Dengan koleksi terkurasi mulai dari SUV tangguh hingga Supercar eksotis, kami menjamin kualitas di setiap detil mesin.
                </p>
                <div class="stats">
                    <div class="stat-box">
                        <h3>500+</h3>
                        <span>Mobil Terjual</span>
                    </div>
                    <div class="stat-box">
                        <h3>100%</h3>
                        <span>Kepuasan</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="vision-section">
        <h2 class="section-title">MENGAPA MEMILIH WIJAYA CARS</h2>
        
        <div class="vision-cards">
            <div class="v-card">
                <h3>Inspeksi Kualitas</h3>
                <p>Setiap unit melewati 150+ titik inspeksi standar internasional sebelum masuk ke showroom kami.</p>
            </div>
            <div class="v-card">
                <h3>Jaminan Legalitas</h3>
                <p>Jaminan dokumen 100% asli dan legalitas kendaraan yang transparan demi ketenangan pikiran Anda.</p>
            </div>
            <div class="v-card">
                <h3>Layanan Premium</h3>
                <p>Layanan purna jual eksklusif dan konsultasi gratis seumur hidup untuk setiap pembelian unit.</p>
            </div>
        </div>
    </section>

<?php include __DIR__ . '/../footer.php'; ?>

</body>
</html>
