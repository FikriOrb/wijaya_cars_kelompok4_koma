<?php
declare(strict_types=1);

require_once __DIR__ . '/../auth.php';
$user = require_login();

$colorOptions = [
    'black' => ['label' => 'Hitam (Standar)', 'price' => 0],
    'red' => ['label' => 'Merah + Rp 5.000.000', 'price' => 5000000],
    'white' => ['label' => 'Putih + Rp 3.000.000', 'price' => 3000000],
    'blue' => ['label' => 'Biru + Rp 4.000.000', 'price' => 4000000],
    'gray' => ['label' => 'Abu-abu + Rp 2.000.000', 'price' => 2000000],
];
$wheelOptions = [
    '18' => ['label' => 'Velg 18 (Standar)', 'price' => 0],
    '19' => ['label' => 'Velg 19 + Rp 7.000.000', 'price' => 7000000],
    '20' => ['label' => 'Velg 20 + Rp 12.000.000', 'price' => 12000000],
];
$engineOptions = [
    'standard' => ['label' => 'Mesin Standar', 'price' => 0],
    'turbo' => ['label' => 'Turbo + Rp 25.000.000', 'price' => 25000000],
    'v8' => ['label' => 'V8 + Rp 50.000.000', 'price' => 50000000],
];

$pdo = get_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prepare_checkout'])) {
    $carId = (int) ($_POST['car_id'] ?? 0);
    $colorKey = (string) ($_POST['color'] ?? 'black');
    $wheelKey = (string) ($_POST['wheel'] ?? '18');
    $engineKey = (string) ($_POST['engine'] ?? 'standard');

    if (!isset($colorOptions[$colorKey], $wheelOptions[$wheelKey], $engineOptions[$engineKey])) {
        redirect_to('/Gallery/gallery.php');
    }

    $stmt = $pdo->prepare('SELECT id, car_name, file_name, price FROM cars WHERE id = ? LIMIT 1');
    $stmt->execute([$carId]);
    $car = $stmt->fetch();

    if (!$car) {
        redirect_to('/Gallery/gallery.php');
    }

    $_SESSION['checkout'] = [
        'car_id' => (int) $car['id'],
        'mobil' => $car['car_name'],
        'gambar' => $car['file_name'],
        'warna' => $colorOptions[$colorKey]['label'],
        'velg' => $wheelOptions[$wheelKey]['label'],
        'mesin' => $engineOptions[$engineKey]['label'],
        'harga_dasar' => (int) $car['price'],
        'total' => (int) $car['price'] + $colorOptions[$colorKey]['price'] + $wheelOptions[$wheelKey]['price'] + $engineOptions[$engineKey]['price'],
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
    $checkout = $_SESSION['checkout'] ?? null;
    if (!$checkout) {
        redirect_to('/Gallery/gallery.php');
    }

    $buktiName = null;
    if (isset($_FILES['bukti_transfer']) && $_FILES['bukti_transfer']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['bukti_transfer']['tmp_name'];
        $ext = pathinfo($_FILES['bukti_transfer']['name'], PATHINFO_EXTENSION);
        $buktiName = 'tf_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        move_uploaded_file($tmpName, __DIR__ . '/../uploads/' . $buktiName);
    }

    $stmt = $pdo->prepare('
        INSERT INTO orders (user_email, mobil, warna, velg, mesin, total_harga, status, bukti_pembayaran, alamat_pengiriman, koordinat_pengiriman)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ');
    $stmt->execute([
        $user['email'],
        $checkout['mobil'],
        $checkout['warna'],
        $checkout['velg'],
        $checkout['mesin'],
        (int) $checkout['total'],
        'Menunggu Verifikasi',
        $buktiName,
        $user['alamat'] ?? '',
        $user['koordinat'] ?? ''
    ]);
    $orderId = (int) $pdo->lastInsertId();
    unset($_SESSION['checkout']);
    redirect_to('/success/success.php?order_id=' . $orderId);
}

$checkout = $_SESSION['checkout'] ?? null;
if (!$checkout) {
    redirect_to('/Gallery/gallery.php');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Secure Checkout - Wijaya Cars</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="pembayaran.css">
</head>
<body>
  <header class="payment-header">
    <a href="../Gallery/gallery.php"><img src="../models/Logo.png" alt="Wijaya Cars Logo"></a>
  </header>

  <main class="container">
    <div class="breadcrumbs">Shipping / <b>Payment</b> / Confirmation</div>

    <div class="page-title">
        <h1>Complete Your Order</h1>
        <p class="subtitle">Selesaikan pembayaran Anda untuk membawa pulang mobil impian.</p>
    </div>

    <form class="layout" method="post" id="paymentForm" enctype="multipart/form-data">
      <div class="left">
        <div class="card glass-panel">
          <h3>Payment Method</h3>
          <div class="tabs">
            <button type="button" class="tab active" onclick="switchTab('credit', this)">Credit Card</button>
            <button type="button" class="tab" onclick="switchTab('bank', this)">Bank Transfer</button>
          </div>

          <div id="credit-form" class="payment-content">
            <div class="form">
              <label>Name on Card</label>
              <input type="text" id="cc-name" placeholder="Nama Pemilik Kartu">
              <label>Card Number</label>
              <div class="input-icon">
                <input type="text" id="cc-num" placeholder="0000 0000 0000 0000" maxlength="19">
                <span class="icon">CARD</span>
              </div>
              <div class="row">
                <div>
                  <label>Expiry Date</label>
                  <input type="text" id="cc-exp" placeholder="MM / YY" maxlength="5">
                </div>
                <div>
                  <label>CVC</label>
                  <input type="text" id="cc-cvc" placeholder="123" maxlength="3">
                </div>
              </div>
            </div>
          </div>

          <div id="bank-form" class="payment-content" style="display: none;">
            <div class="form">
              <p class="muted-copy">Silakan transfer ke nomor Virtual Account. Pesanan akan diproses setelah pembayaran terverifikasi.</p>
              <label>Bank Destination</label>
              <select class="bank-select">
                <option>BCA Virtual Account</option>
                <option>Mandiri Bill</option>
                <option>BNI Virtual Account</option>
                <option>BRI Virtual Account</option>
              </select>
              <label>Virtual Account Number</label>
              <div class="input-icon">
                <input type="text" value="8800 1234 5678 9000" readonly>
                <span class="icon">COPY</span>
              </div>
              <label style="margin-top: 15px;">Bukti Pembayaran (Wajib)</label>
              <input type="file" id="bukti-transfer" name="bukti_transfer" accept="image/*" style="padding: 10px; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: #fff; width: 100%;">
            </div>
          </div>
        </div>

        <div class="card glass-panel">
          <h3>Shipping Address</h3>
          <?php if (empty($user['alamat'])): ?>
            <div class="alert" style="background: rgba(255,0,0,0.1); border: 1px solid red; padding: 15px; border-radius: 8px; margin-top: 10px;">
                <p>⚠️ Anda belum mengatur alamat pengiriman!</p>
                <a href="../dashboard/index.php" style="color: #ff4757; text-decoration: underline; display: inline-block; margin-top: 5px;">Atur Alamat di Profil sekarang</a>
            </div>
          <?php else: ?>
            <div style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); padding: 15px; border-radius: 8px; margin-top: 15px;">
                <p style="margin-bottom: 5px; color: #aaa;">Alamat Pengiriman:</p>
                <p style="font-weight: 500; line-height: 1.5; margin-bottom: 15px;"><?= nl2br(e($user['alamat'])); ?></p>
                
                <?php if (!empty($user['koordinat'])): ?>
                    <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($user['koordinat']); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; background: rgba(33, 150, 243, 0.2); color: #2196F3; padding: 8px 15px; border-radius: 6px; text-decoration: none; font-size: 0.9em;">
                        📍 Lihat Titik di Google Maps
                    </a>
                <?php endif; ?>
                
                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.1);">
                    <a href="../dashboard/index.php" style="color: #aaa; font-size: 0.9em; text-decoration: underline;">Edit Alamat di Profil</a>
                </div>
            </div>
            <label class="check" style="margin-top: 15px;">
                <input type="checkbox" checked required>
                Saya mengonfirmasi alamat pengiriman sudah benar
            </label>
          <?php endif; ?>
        </div>
      </div>

      <aside class="right">
        <div class="summary glass-panel">
          <h3>Order Summary</h3>
          <div class="product">
            <img src="../models/<?= e($checkout['gambar']); ?>" class="car-img" alt="<?= e($checkout['mobil']); ?>">
            <div>
              <h4><?= e($checkout['mobil']); ?></h4>
              <p class="muted">Spesifikasi Pilihan:</p>
              <ul class="spec-list">
                <li><?= e($checkout['warna']); ?></li>
                <li><?= e($checkout['velg']); ?></li>
                <li><?= e($checkout['mesin']); ?></li>
              </ul>
            </div>
          </div>

          <div class="line"><span>Car Price</span><span><?= rupiah((int) $checkout['harga_dasar']); ?></span></div>
          <div class="line total-line"><span>Total</span><span class="total-price"><?= rupiah((int) $checkout['total']); ?></span></div>

            <input type="hidden" name="confirm_order" value="1">
            <?php if (empty($user['ktp_image'])): ?>
                <div style="background: rgba(231, 76, 60, 0.1); border: 1px solid #e74c3c; padding: 10px; border-radius: 8px; margin-bottom: 10px; text-align: center;">
                    <p style="color: #e74c3c; font-size: 0.9em; margin: 0; font-weight: bold;">⛔ Akun Belum Terverifikasi (KYC)</p>
                    <p style="color: #e74c3c; font-size: 0.8em; margin: 5px 0 0 0;">Harap unggah KTP di halaman Profil untuk melanjutkan transaksi.</p>
                </div>
                <button class="btn-pay" type="button" disabled style="opacity: 0.5; cursor: not-allowed;">Verifikasi Dulu</button>
            <?php else: ?>
                <button class="btn-pay" id="btnBayar" type="submit">Pay <?= rupiah((int) $checkout['total']); ?></button>
            <?php endif; ?>
          <p class="secure-text">Pembayaran dienkripsi dan aman.</p>
        </div>
      </aside>
    </form>
  </main>

<?php include __DIR__ . '/../footer.php'; ?>

  <script>
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
      const isCreditActive = document.getElementById('credit-form').style.display !== 'none';
      
      if (isCreditActive) {
        const name = document.getElementById('cc-name').value.trim();
        const number = document.getElementById('cc-num').value.trim();
        const exp = document.getElementById('cc-exp').value.trim();
        const cvc = document.getElementById('cc-cvc').value.trim();
        
        if (!name || !number || !exp || !cvc) {
          e.preventDefault();
          alert('💳 Peringatan Keamanan: Mohon isi semua data Kartu Kredit Anda terlebih dahulu!');
          return;
        }
        
        if (number.replace(/\s/g, '').length < 16) {
          e.preventDefault();
          alert('💳 Peringatan: Nomor kartu kredit tidak valid! (Harus 16 digit)');
          return;
        }
      } else {
        const bukti = document.getElementById('bukti-transfer');
        if (!bukti || bukti.files.length === 0) {
          e.preventDefault();
          alert('📄 Peringatan: Mohon lampirkan Bukti Pembayaran transfer Anda!');
          return;
        }
      }

      const hasAddress = <?= empty($user['alamat']) ? 'false' : 'true' ?>;
      if (!hasAddress) {
          e.preventDefault();
          alert('🚚 Peringatan: Mohon atur Alamat Pengiriman Anda di halaman Profil terlebih dahulu sebelum melakukan pembayaran!');
          return;
      }

      const button = document.getElementById('btnBayar');
      button.textContent = 'Processing...';
      button.disabled = true;
      button.classList.add('is-loading');
    });

    function switchTab(method, element) {
      document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
      element.classList.add('active');
      document.getElementById('credit-form').style.display = method === 'credit' ? 'block' : 'none';
      document.getElementById('bank-form').style.display = method === 'bank' ? 'block' : 'none';
    }
  </script>
</body>
</html>
