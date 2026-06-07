<?php
declare(strict_types=1);

require_once __DIR__ . '/../auth.php';
$user = require_login();

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$pdo = get_db();
$stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ? AND user_email = ? LIMIT 1');
$stmt->execute([(int)$_GET['id'], $user['email']]);
$order = $stmt->fetch();

if (!$order) {
    echo "Pesanan tidak ditemukan atau Anda tidak memiliki akses.";
    exit;
}

// Simulated Tracking Statuses based on current status
$statuses = [
    'Menunggu Verifikasi' => 1,
    'Diproses' => 2,
    'Dikirim' => 3,
    'Selesai' => 4,
    'Dibatalkan' => -1
];

$currentStep = $statuses[$order['status']] ?? 1;

// Fetch Car Image (we can fetch it from DB or just use a placeholder if not stored)
$stmtCar = $pdo->prepare('SELECT file_name FROM cars WHERE car_name = ? LIMIT 1');
$stmtCar->execute([$order['mobil']]);
$carData = $stmtCar->fetch();
$carImage = $carData ? $carData['file_name'] : 'placeholder.jpg';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Pesanan #WC-<?= (int)$order['id']; ?> - Wijaya Cars</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../index.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { background-color: #050505; color: #fff; font-family: 'Inter', sans-serif; padding-top: 80px; }
    .container { max-width: 900px; margin: 0 auto; padding: 20px; }
    .glass-card { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 16px; padding: 30px; margin-bottom: 20px; }
    
    .header-row { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 20px; margin-bottom: 20px; }
    .header-row h1 { font-size: 1.5em; margin: 0; }
    .btn-back { background: rgba(255,255,255,0.1); color: #fff; padding: 8px 15px; border-radius: 8px; text-decoration: none; font-size: 0.9em; transition: 0.3s; }
    .btn-back:hover { background: rgba(255,255,255,0.2); }

    .car-info { display: flex; gap: 20px; align-items: center; }
    .car-info img { width: 150px; border-radius: 8px; }
    .car-details h3 { margin: 0 0 5px 0; font-size: 1.3em; }
    .car-details p { color: #aaa; margin: 0 0 5px 0; font-size: 0.9em; }
    .car-price { font-size: 1.2em; font-weight: bold; color: #fff; margin-top: 10px; letter-spacing: 0.5px; }

    /* Timeline Styles */
    .timeline { position: relative; margin: 40px 0; padding-left: 30px; list-style: none; }
    .timeline::before { content: ''; position: absolute; left: 6px; top: 0; bottom: 0; width: 2px; background: rgba(255,255,255,0.1); }
    
    .timeline-item { position: relative; margin-bottom: 30px; }
    .timeline-item::before {
        content: ''; position: absolute; left: -31px; top: 0; width: 14px; height: 14px;
        border-radius: 50%; background: #333; border: 2px solid #555; z-index: 2;
    }
    
    .timeline-item.active::before { background: #fff; border-color: #fff; box-shadow: 0 0 15px rgba(255, 255, 255, 0.4); }
    .timeline-item.completed::before { background: #fff; border-color: #fff; }
    .timeline-item.completed::after {
        content: ''; position: absolute; left: -24px; top: 14px; width: 2px; height: calc(100% + 16px);
        background: #fff; z-index: 1;
    }

    .timeline-content { padding-top: -5px; }
    .timeline-title { font-weight: bold; font-size: 1.1em; margin-bottom: 5px; color: #666; }
    .timeline-item.completed .timeline-title, .timeline-item.active .timeline-title { color: #fff; }
    .timeline-desc { color: #aaa; font-size: 0.9em; line-height: 1.5; }
    
    .timeline-date { font-size: 0.8em; color: #888; margin-top: 5px; }

  </style>
</head>
<body>
<?php include __DIR__ . '/../nav.php'; ?>

<div class="container">
    <div class="glass-card">
        <div class="header-row">
            <div>
                <h1>Order #WC-<?= (int)$order['id']; ?></h1>
                <p style="color: #aaa; margin: 5px 0 0 0; font-size: 0.9em;">Dibuat pada: <?= date('d F Y, H:i', strtotime($order['created_at'])); ?></p>
            </div>
            <a href="index.php" class="btn-back">← Kembali ke Profil</a>
        </div>

        <div class="car-info">
            <img src="../models/<?= e($carImage); ?>" alt="Car Image">
            <div class="car-details">
                <h3><?= e($order['mobil']); ?></h3>
                <p>Spesifikasi: <?= e($order['warna']); ?> | <?= e($order['velg']); ?> | <?= e($order['mesin']); ?></p>
                <div class="car-price">Total Tagihan: <?= rupiah((int)$order['total_harga']); ?></div>
            </div>
        </div>
    </div>

    <div class="glass-card">
        <h2 style="margin-top: 0; margin-bottom: 20px; font-size: 1.2em;">Status Perjalanan Pesanan</h2>
        
        <?php if ($currentStep === -1): ?>
            <div style="background: rgba(231, 76, 60, 0.1); border: 1px solid #e74c3c; padding: 20px; border-radius: 8px; text-align: center;">
                <h3 style="color: #e74c3c; margin: 0 0 10px 0;">Pesanan Dibatalkan ❌</h3>
                <p style="color: #aaa; margin: 0;">Pesanan ini telah dibatalkan. Silakan hubungi Customer Service untuk informasi lebih lanjut.</p>
            </div>
        <?php else: ?>
            <ul class="timeline">
                <li class="timeline-item <?= $currentStep >= 1 ? ($currentStep > 1 ? 'completed' : 'active') : '' ?>">
                    <div class="timeline-content">
                        <div class="timeline-title">Menunggu Verifikasi (KYC & Pembayaran)</div>
                        <div class="timeline-desc">Tim kami sedang melakukan verifikasi dokumen identitas (KTP) Anda serta mencocokkan mutasi pembayaran dengan Virtual Account. Proses ini memakan waktu 1x24 jam kerja.</div>
                        <div class="timeline-date"><?= date('d M Y, H:i', strtotime($order['created_at'])); ?></div>
                    </div>
                </li>
                
                <li class="timeline-item <?= $currentStep >= 2 ? ($currentStep > 2 ? 'completed' : 'active') : '' ?>">
                    <div class="timeline-content">
                        <div class="timeline-title">Pesanan Diproses (Perakitan & Customization)</div>
                        <div class="timeline-desc">Pembayaran dan dokumen legal telah terverifikasi. Kendaraan Anda saat ini sedang disiapkan, termasuk pemasangan modifikasi warna, velg, dan mesin sesuai spesifikasi pilihan Anda.</div>
                        <?php if ($currentStep >= 2): ?><div class="timeline-date">Estimasi: 3-5 Hari Kerja</div><?php endif; ?>
                    </div>
                </li>
                
                <li class="timeline-item <?= $currentStep >= 3 ? ($currentStep > 3 ? 'completed' : 'active') : '' ?>">
                    <div class="timeline-content">
                        <div class="timeline-title">Sedang Dikirim</div>
                        <div class="timeline-desc">Kendaraan telah lulus inspeksi kualitas (Quality Control) dan saat ini sedang dalam perjalanan menuju alamat pengiriman Anda menggunakan Towing VIP kami.</div>
                        <?php if ($currentStep >= 3): ?>
                            <div style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.2); padding: 12px; border-radius: 6px; margin-top: 10px; color: #ccc;">
                                <span style="display: block; margin-bottom: 5px; font-weight: bold; color: #fff; letter-spacing: 0.5px;">📍 ALAMAT PENGIRIMAN:</span>
                                <?= nl2br(e($order['alamat_pengiriman'])); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </li>
                
                <li class="timeline-item <?= $currentStep >= 4 ? 'completed active' : '' ?>">
                    <div class="timeline-content">
                        <div class="timeline-title">Pesanan Selesai</div>
                        <div class="timeline-desc">Kendaraan beserta dokumen legal (STNK & BPKB sementara) telah diserahterimakan kepada Anda. Selamat menikmati perjalanan mewah Anda bersama Wijaya Cars!</div>
                    </div>
                </li>
            </ul>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../footer.php'; ?>
</body>
</html>
