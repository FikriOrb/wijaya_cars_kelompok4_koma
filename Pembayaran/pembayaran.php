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

    $stmt = $pdo->prepare('
        INSERT INTO orders (user_email, mobil, warna, velg, mesin, total_harga, status)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ');
    $stmt->execute([
        $user['email'],
        $checkout['mobil'],
        $checkout['warna'],
        $checkout['velg'],
        $checkout['mesin'],
        (int) $checkout['total'],
        'Menunggu Verifikasi',
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

    <div class="layout">
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
              <input type="text" placeholder="Nama Pemilik Kartu">
              <label>Card Number</label>
              <div class="input-icon">
                <input type="text" placeholder="0000 0000 0000 0000">
                <span class="icon">CARD</span>
              </div>
              <div class="row">
                <div>
                  <label>Expiry Date</label>
                  <input type="text" placeholder="MM / YY">
                </div>
                <div>
                  <label>CVC</label>
                  <input type="text" placeholder="123">
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
            </div>
          </div>
        </div>

        <div class="card glass-panel">
          <h3>Billing Address</h3>
          <label class="check">
            <input type="checkbox" checked>
            Alamat tagihan sama dengan alamat pengiriman
          </label>
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

          <form method="post" id="paymentForm">
            <input type="hidden" name="confirm_order" value="1">
            <button class="btn-pay" id="btnBayar" type="submit">Pay <?= rupiah((int) $checkout['total']); ?></button>
          </form>
          <p class="secure-text">Pembayaran dienkripsi dan aman.</p>
        </div>
      </aside>
    </div>
  </main>

<?php include __DIR__ . '/../footer.php'; ?>

  <script>
    document.getElementById('paymentForm').addEventListener('submit', function() {
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
