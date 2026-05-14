<?php
declare(strict_types=1);

require_once __DIR__ . '/../auth.php';
require_login();

$carId = (int) ($_GET['car_id'] ?? 0);
$stmt = get_db()->prepare('SELECT id, car_name, file_name, price, category FROM cars WHERE id = ? LIMIT 1');
$stmt->execute([$carId]);
$car = $stmt->fetch();

if (!$car) {
    redirect_to('/Gallery/gallery.php');
}

$colorOptions = [
    'black' => ['label' => 'Hitam (Standar)', 'price' => 0, 'class' => 'black'],
    'red' => ['label' => 'Merah + Rp 5.000.000', 'price' => 5000000, 'class' => 'red'],
    'white' => ['label' => 'Putih + Rp 3.000.000', 'price' => 3000000, 'class' => 'white'],
    'blue' => ['label' => 'Biru + Rp 4.000.000', 'price' => 4000000, 'class' => 'blue'],
    'gray' => ['label' => 'Abu-abu + Rp 2.000.000', 'price' => 2000000, 'class' => 'gray'],
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
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Konfigurasi Mobil - Wijaya Cars</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="modif.css?v=20260515-3">
</head>
<body>
<header class="modif-header">
    <a href="../Gallery/gallery.php"><img src="../models/Logo.png" alt="Wijaya Cars Logo"></a>
</header>

<main class="container">
  <div class="title">
    <p class="eyebrow"><?= e($car['category']); ?></p>
    <h1><?= e($car['car_name']); ?></h1>
    <p>Sesuaikan tampilan dan performa sesuai karakter Anda.</p>
  </div>

  <form class="content-wrapper" id="modifForm" method="post" action="../Pembayaran/pembayaran.php">
      <input type="hidden" name="car_id" value="<?= (int) $car['id']; ?>">
      <input type="hidden" name="prepare_checkout" value="1">

      <div class="car-preview glass-panel">
        <img src="../models/<?= e($car['file_name']); ?>" alt="<?= e($car['car_name']); ?>">
      </div>

      <div class="options">
        <section class="box glass-panel">
          <h3>Warna Eksterior</h3>
          <div class="colors" data-option-group="color">
            <?php foreach ($colorOptions as $key => $option): ?>
              <label class="color <?= e($option['class']); ?> <?= $key === 'black' ? 'active-color' : ''; ?>" title="<?= e($option['label']); ?>" tabindex="0" role="button">
                <input type="radio" name="color" value="<?= e($key); ?>" data-label="<?= e($option['label']); ?>" data-price="<?= (int) $option['price']; ?>" <?= $key === 'black' ? 'checked' : ''; ?>>
              </label>
            <?php endforeach; ?>
          </div>
          <div class="price-note" id="infoWarna">Hitam (Standar)</div>
        </section>

        <section class="box glass-panel">
          <h3>Ukuran Velg</h3>
          <div class="wheels" data-option-group="wheel">
            <?php foreach ($wheelOptions as $key => $option): ?>
              <label class="wheel <?= $key == 18 ? 'active' : ''; ?>" tabindex="0" role="button">
                <input type="radio" name="wheel" value="<?= e((string)$key); ?>" data-label="<?= e($option['label']); ?>" data-price="<?= (int) $option['price']; ?>" <?= $key == 18 ? 'checked' : ''; ?>>
                <?= e((string)$key); ?>&quot;
              </label>
            <?php endforeach; ?>
          </div>
          <div class="price-note" id="infoVelg">Velg 18 (Standar)</div>
        </section>

        <section class="box glass-panel">
          <h3>Tipe Mesin</h3>
          <div class="wheels" data-option-group="engine">
            <?php foreach ($engineOptions as $key => $option): ?>
              <label class="wheel <?= $key === 'standard' ? 'active' : ''; ?>" tabindex="0" role="button">
                <input type="radio" name="engine" value="<?= e($key); ?>" data-label="<?= e($option['label']); ?>" data-price="<?= (int) $option['price']; ?>" <?= $key === 'standard' ? 'checked' : ''; ?>>
                <?= e($key === 'standard' ? 'Standar' : strtoupper($key)); ?>
              </label>
            <?php endforeach; ?>
          </div>
          <div class="price-note" id="infoMesin">Mesin Standar</div>
        </section>

        <section class="footer-box glass-panel">
            <div class="total">
              <h4>Estimasi Harga</h4>
              <h2 id="totalHarga"><?= rupiah((int) $car['price']); ?></h2>
            </div>
            <button class="btn btn-next" type="submit">
              <span>Selanjutnya</span>
              <small>Lanjut ke pembayaran</small>
            </button>
        </section>
      </div>
  </form>
</main>

<div class="sticky-checkout">
  <div>
    <span>Estimasi Harga</span>
    <strong id="stickyTotal"><?= rupiah((int) $car['price']); ?></strong>
  </div>
  <button class="sticky-next" id="stickyNext" type="submit" form="modifForm">Selanjutnya</button>
</div>

<script>
const basePrice = <?= (int) $car['price']; ?>;
const form = document.getElementById('modifForm');
const totalEl = document.getElementById('totalHarga');
const stickyTotalEl = document.getElementById('stickyTotal');
const labels = {
  color: document.getElementById('infoWarna'),
  wheel: document.getElementById('infoVelg'),
  engine: document.getElementById('infoMesin')
};

document.querySelectorAll('[data-option-group] input[type="radio"]').forEach(input => {
  input.addEventListener('change', () => syncOption(input));
});

document.querySelectorAll('[data-option-group] label').forEach(option => {
  option.addEventListener('keydown', event => {
    if (event.key === 'Enter' || event.key === ' ') {
      event.preventDefault();
      const input = option.querySelector('input[type="radio"]');
      if (input) {
        input.checked = true;
        input.dispatchEvent(new Event('change', { bubbles: true }));
      }
    }
  });
});

function syncOption(input) {
  const option = input ? input.closest('label') : null;
  const group = input ? input.closest('[data-option-group]') : null;
  if (!input || !option || !group) return;

  group.querySelectorAll('label').forEach(label => label.classList.remove('active', 'active-color'));
  option.classList.add(group.dataset.optionGroup === 'color' ? 'active-color' : 'active');
  if (labels[group.dataset.optionGroup]) {
    labels[group.dataset.optionGroup].textContent = input.dataset.label;
  }
  updateTotal();
}

function updateTotal() {
  const optionTotal = [...document.querySelectorAll('input[type="radio"]:checked')]
    .reduce((sum, input) => sum + Number(input.dataset.price || 0), 0);
  totalEl.textContent = formatRupiah(basePrice + optionTotal);
  stickyTotalEl.textContent = formatRupiah(basePrice + optionTotal);
}

function formatRupiah(value) {
  return 'Rp ' + value.toLocaleString('id-ID');
}
</script>
</body>
</html>
