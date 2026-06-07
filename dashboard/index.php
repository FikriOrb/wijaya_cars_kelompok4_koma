<?php
declare(strict_types=1);

require_once __DIR__ . '/../auth.php';
$user = require_login();

$pdo = get_db();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim((string) ($_POST['first_name'] ?? ''));
    $lastName = trim((string) ($_POST['last_name'] ?? ''));
    $phone = preg_replace('/[^0-9+]/', '', (string) ($_POST['phone'] ?? ''));
    $alamat = trim((string) ($_POST['alamat'] ?? ''));
    $koordinat = trim((string) ($_POST['koordinat'] ?? ''));

    $ktpName = $user['ktp_image'] ?? null;
    if (isset($_FILES['ktp_upload']) && $_FILES['ktp_upload']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['ktp_upload']['tmp_name'];
        $ext = pathinfo($_FILES['ktp_upload']['name'], PATHINFO_EXTENSION);
        $ktpName = 'ktp_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        move_uploaded_file($tmpName, __DIR__ . '/../uploads/' . $ktpName);
    }

    if ($firstName !== '' && $lastName !== '') {
        $stmt = $pdo->prepare('UPDATE users SET first_name = ?, last_name = ?, phone = ?, alamat = ?, koordinat = ?, ktp_image = ? WHERE id = ?');
        $stmt->execute([$firstName, $lastName, $phone, $alamat, $koordinat, $ktpName, (int) $user['id']]);
        $message = 'Profil berhasil diperbarui.';
        $user = current_user();
    }
}

$ordersStmt = $pdo->prepare('SELECT * FROM orders WHERE user_email = ? ORDER BY created_at DESC');
$ordersStmt->execute([$user['email']]);
$orders = $ordersStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Wijaya Cars</title>
    <link rel="stylesheet" href="../Beranda/style.css">
    <link rel="stylesheet" href="../Login_create/tema_lc.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
</head>
<body class="dashboard-body">
<?php include __DIR__ . '/../navbar.php'; ?>

<main class="dashboard-shell">
    <section class="dashboard-hero glass-panel">
        <div>
            <p class="eyebrow">Personal Dashboard</p>
            <h1><?= e($user['first_name'] . ' ' . $user['last_name']); ?></h1>
            <p><?= e($user['email']); ?></p>
        </div>
        <?php if (!empty($user['profile_pic'])): ?>
            <img class="profile-photo" src="<?= e($user['profile_pic']); ?>" alt="Profile photo">
        <?php endif; ?>
    </section>

    <section class="dashboard-grid">
        <form class="glass-panel dashboard-card" method="post" enctype="multipart/form-data">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2>Profile & Identity</h2>
                <?php if (!empty($user['ktp_image'])): ?>
                    <span style="background: rgba(255, 255, 255, 0.1); color: #fff; padding: 5px 12px; border-radius: 4px; font-size: 0.8em; font-weight: 600; border: 1px solid rgba(255, 255, 255, 0.3); letter-spacing: 1px;">✓ KYC VERIFIED</span>
                <?php else: ?>
                    <span style="background: rgba(231, 76, 60, 0.1); color: #e74c3c; padding: 5px 12px; border-radius: 4px; font-size: 0.8em; font-weight: 600; border: 1px solid rgba(231, 76, 60, 0.3); letter-spacing: 1px;">✕ UNVERIFIED</span>
                <?php endif; ?>
            </div>
            <?php if ($message): ?><div class="alert"><?= e($message); ?></div><?php endif; ?>

            <label style="margin-top: 15px;">Dokumen Identitas (KTP) - Wajib untuk Transaksi</label>
            <?php if (!empty($user['ktp_image'])): ?>
                <div style="background: rgba(255,255,255,0.03); padding: 12px; border-radius: 6px; margin-bottom: 15px; border: 1px solid rgba(255, 255, 255, 0.1);">
                    <p style="color: #ccc; font-size: 0.9em; margin: 0; display: flex; align-items: center; gap: 8px;">
                        <span style="display: inline-block; width: 8px; height: 8px; background: #fff; border-radius: 50%;"></span>
                        Dokumen identitas telah diunggah dan terverifikasi.
                    </p>
                </div>
            <?php else: ?>
                <input type="file" id="ktp_upload" name="ktp_upload" accept="image/*" style="padding: 10px; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 6px; color: #fff; width: 100%; margin-bottom: 15px;" required>
            <?php endif; ?>
            
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" value="<?= e($user['first_name']); ?>" required>
            
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" value="<?= e($user['last_name']); ?>" required>
            
            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" value="<?= e($user['phone'] ?? ''); ?>">

            <label for="alamat">Alamat Lengkap</label>
            <textarea id="alamat" name="alamat" rows="3" style="width:100%; padding:10px; background:rgba(255,255,255,0.05); color:#fff; border:1px solid rgba(255,255,255,0.1); border-radius:6px;" placeholder="Ketik alamat pengiriman lengkap Anda..."><?= e($user['alamat'] ?? ''); ?></textarea>

            <label>Titik Lokasi Maps</label>
            <button type="button" id="toggleMapBtn" style="width:100%; padding:12px; background:transparent; color:#fff; border:1px solid rgba(255, 255, 255, 0.3); border-radius:6px; margin-bottom:15px; cursor:pointer; font-weight:500; transition: 0.3s; letter-spacing: 0.5px;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">📍 Buka Peta untuk Pilih Lokasi</button>
            <div id="mapContainer" style="display: none; margin-bottom: 15px;">
                <div id="map" style="height: 300px; width: 100%; border-radius: 6px; border:1px solid rgba(255,255,255,0.1);"></div>
            </div>
            <input type="hidden" id="koordinat" name="koordinat" value="<?= e($user['koordinat'] ?? ''); ?>">
            
            <button type="submit" class="btn-submit" style="border-radius: 6px; letter-spacing: 1px; font-weight: 600;">SAVE PROFILE & ADDRESS</button>
        </form>

        <div class="glass-panel dashboard-card orders-card">
            <h2>Order History</h2>
            <?php foreach ($orders as $order): ?>
                <div class="order-row" style="align-items: center; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 15px; margin-bottom: 15px;">
                    <div style="flex: 1;">
                        <strong style="font-size: 1.1em; color: #fff; letter-spacing: 0.5px;">#WC-<?= (int) $order['id']; ?> <?= e($order['mobil']); ?></strong>
                        <div style="color: #888; font-size: 0.85em; margin-top: 6px;">Spesifikasi: <?= e($order['warna']); ?> / <?= e($order['velg']); ?> / <?= e($order['mesin']); ?></div>
                        <div style="color: #666; font-size: 0.8em; margin-top: 4px;">Tanggal: <?= date('d M Y', strtotime($order['created_at'])); ?></div>
                    </div>
                    <div style="text-align: right; margin-right: 20px;">
                        <b style="color: #fff; font-size: 1.1em; letter-spacing: 0.5px;"><?= rupiah((int) $order['total_harga']); ?></b>
                        <div style="margin-top: 6px;">
                            <span style="background: rgba(255, 255, 255, 0.05); color: #ccc; padding: 4px 10px; border-radius: 4px; border: 1px solid rgba(255, 255, 255, 0.1); font-size: 0.75em; text-transform: uppercase; letter-spacing: 1px;"><?= e($order['status']); ?></span>
                        </div>
                    </div>
                    <div>
                        <a href="order_detail.php?id=<?= (int) $order['id']; ?>" style="background: transparent; color: #fff; padding: 8px 20px; border-radius: 4px; border: 1px solid #fff; text-decoration: none; font-size: 0.85em; font-weight: 600; display: inline-block; transition: 0.3s; text-transform: uppercase; letter-spacing: 1px;" onmouseover="this.style.background='#fff'; this.style.color='#000'" onmouseout="this.style.background='transparent'; this.style.color='#fff'">View Status</a>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (count($orders) === 0): ?>
                <p class="subtitle">Belum ada order.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../footer.php'; ?>
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var defaultLat = 3.5952; // Medan
        var defaultLng = 98.6722;
        var savedCoord = document.getElementById('koordinat').value;
        
        if (savedCoord) {
            var parts = savedCoord.split(',');
            if (parts.length === 2) {
                defaultLat = parseFloat(parts[0]);
                defaultLng = parseFloat(parts[1]);
            }
        }

        var map = L.map('map').setView([defaultLat, defaultLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var marker = L.marker([defaultLat, defaultLng], {draggable: true}).addTo(map);

        function updateCoordinates(lat, lng) {
            document.getElementById('koordinat').value = lat + ',' + lng;
            getAddressFromCoords(lat, lng);
        }

        function getAddressFromCoords(lat, lng) {
            var url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`;
            
            // Tambahkan loading indicator ke textarea
            var alamatInput = document.getElementById('alamat');
            var oldVal = alamatInput.value;
            alamatInput.value = "Sedang mencari alamat otomatis...";
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data && data.display_name) {
                        alamatInput.value = data.display_name;
                    } else {
                        alamatInput.value = oldVal;
                    }
                })
                .catch(err => {
                    console.error("Geocoding failed:", err);
                    alamatInput.value = oldVal;
                });
        }

        marker.on('dragend', function(e) {
            var position = marker.getLatLng();
            updateCoordinates(position.lat, position.lng);
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateCoordinates(e.latlng.lat, e.latlng.lng);
        });
        
        // Dapatkan lokasi pengguna saat ini (Opsional/Auto-locate)
        map.locate({setView: false, maxZoom: 16});
        map.on('locationfound', function(e) {
            if (!savedCoord) { // Hanya pindahkan jika belum ada koordinat tersimpan
                map.setView(e.latlng, 15);
                marker.setLatLng(e.latlng);
                updateCoordinates(e.latlng.lat, e.latlng.lng);
            }
        });

        // Logika Buka/Tutup Peta
        document.getElementById('toggleMapBtn').addEventListener('click', function() {
            var container = document.getElementById('mapContainer');
            if (container.style.display === 'none') {
                container.style.display = 'block';
                this.innerHTML = '❌ Tutup Peta';
                // Wajib dipanggil setelah div peta ditampilkan agar peta merender ulang
                setTimeout(function() { map.invalidateSize(); }, 300);
            } else {
                container.style.display = 'none';
                this.innerHTML = '📍 Buka Peta untuk Pilih Lokasi';
            }
        });
    });
</script>
</body>
</html>
