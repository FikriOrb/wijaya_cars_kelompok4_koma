<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Wijaya Cars</title>
    <link rel="stylesheet" href="../Beranda/style.css">
    <link rel="stylesheet" href="style-contact.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/wijaya_v2/navbar.php'; ?>

    <section class="contact-hero">
        <div class="contact-hero-text">
            <h1>GET IN TOUCH</h1>
            <p>Kami siap membantu Anda menemukan mobil impian. Hubungi kami atau kunjungi showroom kami.</p>
        </div>
    </section>

    <section class="contact-section">
        <div class="contact-wrapper">
            
            <div class="contact-info">
                <h2>Contact Information</h2>
                <p class="sub-text">Kunjungi showroom kami untuk melihat koleksi terbaru atau jadwalkan test drive.</p>
                
                <div class="info-item">
                    <div class="icon-box"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="info-text">
                        <h3>Address</h3>
                        <p>Jl. Gatot Subroto No. 123, Medan, Sumatera Utara, Indonesia</p>
                    </div>
                </div>

                <div class="info-item">
                    <div class="icon-box"><i class="fas fa-phone-alt"></i></div>
                    <div class="info-text">
                        <h3>Phone</h3>
                        <p>+62 822-7456-7521</p>
                        <p>+62 61-888-999 (Office)</p>
                    </div>
                </div>

                <div class="info-item">
                    <div class="icon-box"><i class="fas fa-envelope"></i></div>
                    <div class="info-text">
                        <h3>Email</h3>
                        <p>madyashafwan@gmail.com</p>
                        <p>sales@wijayacars.com</p>
                    </div>
                </div>

                <div class="map-container">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3981.990425027581!2d98.6631897749732!3d3.589665396384462!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30313032123f1234%3A0x1234567890abcdef!2sMedan%2C%20North%20Sumatra!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

            <div class="contact-form-area">
                <h2>Send Us a Message</h2>
                <form class="main-form">
                    <div class="input-group">
                        <label>Full Name</label>
                        <input type="text" placeholder="Masukkan nama Anda">
                    </div>
                    
                    <div class="input-group">
                        <label>Email Address</label>
                        <input type="email" placeholder="Masukkan email aktif">
                    </div>

                    <div class="input-group">
                        <label>Subject</label>
                        <select>
                            <option>General Inquiry</option>
                            <option>Test Drive Booking</option>
                            <option>Price Quote</option>
                            <option>Complaint</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label>Message</label>
                        <textarea rows="5" placeholder="Tulis pesan Anda di sini..."></textarea>
                    </div>

                    <button type="submit" class="submit-btn">Send Message</button>
                </form>
            </div>

        </div>
    </section>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/wijaya_v2/footer.php'; ?>

</body>
</html>