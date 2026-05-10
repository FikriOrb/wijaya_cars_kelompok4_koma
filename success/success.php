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
        <a href="../Beranda/index.php">
            <img src="../models/Logo.png" alt="Wijaya Cars Logo">
        </a>
    </header>

    <main class="container success-wrapper">
        
        <div class="checkmark-circle">
            <div class="checkmark draw"></div>
        </div>

        <div class="success-text">
            <h1>Payment Successful!</h1>
            <p class="subtitle">Terima kasih, pesanan <b id="namaMobil" style="color: #fff;">Mobil</b> Anda sedang diproses.</p>
            <p class="order-id">Order ID: <span id="orderId">#WC-882910</span></p>
        </div>

        <div class="success-actions">
            <a href="../Beranda/index.php" class="btn-home" onclick="clearData()">Back to Home</a>
        </div>

    </main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/wijaya_v2/footer.php'; ?>

    <script>
        // Ambil data dari LocalStorage
        const data = JSON.parse(localStorage.getItem("dataCheckout"));
        
        if (data) {
            document.getElementById("namaMobil").innerText = data.mobil;
        }

        // Generate Order ID Acak
        const randomId = Math.floor(100000 + Math.random() * 900000);
        document.getElementById("orderId").innerText = "#WC-" + randomId;

        // Fungsi untuk menghapus data keranjang saat kembali ke Home
        function clearData() {
            localStorage.removeItem("dataCheckout");
            localStorage.removeItem("mobilDipilih");
        }
    </script>
</body>
</html>