<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../koneksi.php';

$pdo = get_db();
$message = '';
$page = $_GET['p'] ?? 'orders';

// Logout Logic
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// LOGIKA POST REQUEST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_order_status'])) {
        $orderId = (int) $_POST['order_id'];
        $status = $_POST['status'];
        $stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');
        $stmt->execute([$status, $orderId]);
        $message = "Status order #WC-$orderId berhasil diperbarui menjadi '$status'.";
    } elseif (isset($_POST['add_car'])) {
        $carName = trim($_POST['car_name']);
        $price = (int) $_POST['price'];
        $category = $_POST['category'];
        
        $fileName = 'placeholder.jpg';
        if (isset($_FILES['car_image']) && $_FILES['car_image']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['car_image']['tmp_name'];
            $ext = pathinfo($_FILES['car_image']['name'], PATHINFO_EXTENSION);
            $fileName = 'car_' . time() . '_' . bin2hex(random_bytes(2)) . '.' . $ext;
            move_uploaded_file($tmpName, __DIR__ . '/../models/' . $fileName);
        }
        
        $stmt = $pdo->prepare('INSERT INTO cars (car_name, file_name, price, category) VALUES (?, ?, ?, ?)');
        $stmt->execute([$carName, $fileName, $price, $category]);
        $message = "Mobil '$carName' berhasil ditambahkan ke katalog.";
    } elseif (isset($_POST['delete_car'])) {
        $carId = (int) $_POST['car_id'];
        $stmt = $pdo->prepare('DELETE FROM cars WHERE id = ?');
        $stmt->execute([$carId]);
        $message = "Mobil berhasil dihapus dari katalog.";
    }
}

// FETCH DATA
$orders = [];
$users = [];
$cars = [];

if ($page === 'orders') {
    $stmt = $pdo->query('
        SELECT o.*, u.first_name, u.last_name, u.phone, u.ktp_image 
        FROM orders o 
        LEFT JOIN users u ON o.user_email = u.email 
        ORDER BY o.created_at DESC
    ');
    $orders = $stmt->fetchAll();
} elseif ($page === 'users') {
    $stmt = $pdo->query('SELECT * FROM users ORDER BY created_at DESC');
    $users = $stmt->fetchAll();
} elseif ($page === 'cars') {
    $stmt = $pdo->query('SELECT * FROM cars ORDER BY id DESC');
    $cars = $stmt->fetchAll();
}

function rph($amount) {
    return 'Rp ' . number_format((float) $amount, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Admin - Wijaya Cars</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #000000;
            --surface: #0d0d0d;
            --surface-hover: #1a1a1a;
            --primary: #ffffff;
            --primary-hover: #cccccc;
            --text-main: #ffffff;
            --text-muted: #888888;
            --border: rgba(255, 255, 255, 0.15);
            --danger: #ef4444;
            --success: #10b981;
            --warning: #f59e0b;
        }

        * { box-sizing: border-box; font-family: 'Inter', sans-serif; margin: 0; padding: 0; }
        body { display: flex; background: var(--bg-color); color: var(--text-main); min-height: 100vh; overflow: hidden; }
        
        /* Sidebar Glassmorphism */
        .sidebar { width: 280px; background: rgba(0, 0, 0, 0.8); backdrop-filter: blur(20px); border-right: 1px solid var(--border); display: flex; flex-direction: column; z-index: 10; }
        .sidebar-header { padding: 30px 25px; border-bottom: 1px solid var(--border); }
        .sidebar-header h2 { font-size: 24px; font-weight: 800; color: #fff; letter-spacing: -0.5px; display: flex; align-items: center; gap: 10px; }
        .sidebar-header p { font-size: 13px; color: var(--text-muted); margin-top: 5px; font-weight: 500; }
        .nav-links { flex: 1; padding: 25px 15px; display: flex; flex-direction: column; gap: 8px; }
        .nav-links a { display: flex; align-items: center; gap: 12px; padding: 14px 20px; color: var(--text-muted); text-decoration: none; font-size: 15px; font-weight: 600; transition: all 0.3s ease; border-radius: 12px; }
        .nav-links a:hover { background: rgba(255, 255, 255, 0.05); color: var(--primary); transform: translateX(5px); }
        .nav-links a.active { background: var(--primary); color: #000; box-shadow: 0 10px 20px -10px rgba(255,255,255,0.2); }
        .logout-btn { padding: 25px 15px; border-top: 1px solid var(--border); }
        .logout-btn a { display: flex; align-items: center; justify-content: center; gap: 10px; background: transparent; color: var(--danger); padding: 14px; border-radius: 12px; text-decoration: none; font-weight: 700; transition: 0.3s; border: 1px solid rgba(239, 68, 68, 0.5); }
        .logout-btn a:hover { background: var(--danger); color: white; }

        /* Main Content */
        .main-content { flex: 1; padding: 40px 50px; overflow-y: auto; height: 100vh; position: relative; }
        .header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px; }
        .header h1 { font-size: 32px; font-weight: 800; color: #fff; letter-spacing: -1px; }
        
        .stat-card { background: rgba(255,255,255,0.02); padding: 20px 25px; border-radius: 16px; border: 1px solid var(--border); display: flex; flex-direction: column; gap: 5px; backdrop-filter: blur(10px); }
        .stat-card span { font-size: 13px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
        .stat-card strong { font-size: 36px; color: #fff; line-height: 1; font-weight: 800; }

        .alert-success { background: rgba(255, 255, 255, 0.05); color: #fff; padding: 16px 20px; border-radius: 12px; margin-bottom: 30px; border: 1px solid rgba(255, 255, 255, 0.3); font-weight: 500; display: flex; align-items: center; gap: 10px; }

        /* Card & Table */
        .card { background: var(--surface); border-radius: 20px; border: 1px solid var(--border); overflow: hidden; }
        .card-header { padding: 25px 30px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }
        .card-header h3 { font-size: 18px; color: #fff; font-weight: 700; }
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th, td { padding: 18px 30px; border-bottom: 1px solid var(--border); vertical-align: middle; }
        th { background: rgba(255,255,255,0.02); color: var(--text-muted); font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; }
        td { font-size: 14px; color: #e2e8f0; }
        tr:last-child td { border-bottom: none; }
        tr:hover { background: rgba(255,255,255,0.03); }

        /* Badges */
        .badge { display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-warning { background: rgba(245, 158, 11, 0.1); color: var(--warning); border: 1px solid rgba(245, 158, 11, 0.3); }
        .badge-info { background: rgba(255, 255, 255, 0.1); color: #fff; border: 1px solid rgba(255, 255, 255, 0.3); }
        .badge-purple { background: rgba(255, 255, 255, 0.2); color: #fff; border: 1px solid rgba(255, 255, 255, 0.5); }
        .badge-success { background: rgba(16, 185, 129, 0.1); color: var(--success); border: 1px solid rgba(16, 185, 129, 0.3); }
        .badge-danger { background: rgba(239, 68, 68, 0.1); color: var(--danger); border: 1px solid rgba(239, 68, 68, 0.3); }
        
        .badge-pay { background: rgba(255,255,255,0.1); color: #fff; }

        /* Form Elements */
        .status-select { padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-color); color: white; font-size: 13px; font-weight: 500; width: 100%; outline: none; margin-bottom: 10px; cursor: pointer; transition: 0.2s; }
        .status-select:focus { border-color: var(--primary); }
        .btn { padding: 10px 16px; background: var(--primary); color: #000; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; transition: 0.3s; display: inline-flex; align-items: center; justify-content: center; gap: 8px; }
        .btn:hover { background: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 10px 20px -10px rgba(255,255,255,0.2); }
        .btn-danger { background: transparent; color: var(--danger); border: 1px solid rgba(239, 68, 68, 0.5); }
        .btn-danger:hover { background: var(--danger); color: white; }
        
        .proof-img { width: 60px; height: 40px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border); cursor: pointer; transition: 0.3s; }
        .proof-img:hover { opacity: 0.8; transform: scale(1.1); }
        .profile-img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid var(--border); }

        /* Modal */
        .modal { display: none; position: fixed; z-index: 1000; inset: 0; background-color: rgba(0,0,0,0.85); backdrop-filter: blur(8px); }
        .modal-content { margin: auto; display: block; max-width: 90%; max-height: 90%; margin-top: 5vh; border-radius: 12px; box-shadow: 0 25px 50px -12px rgba(0,0,0,1); }
        .close { position: absolute; top: 30px; right: 50px; color: #fff; font-size: 40px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .close:hover { color: var(--danger); transform: rotate(90deg); }

        /* Form Add Car */
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-size: 13px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .form-group input, .form-group select { width: 100%; padding: 14px 16px; border-radius: 10px; border: 1px solid var(--border); background: rgba(0,0,0,0.2); color: white; font-size: 14px; outline: none; transition: 0.2s; }
        .form-group input:focus, .form-group select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2); }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            <h2><span style="font-size: 28px;">⚡</span> WijayaOS</h2>
            <p>Admin Operations Center</p>
        </div>
        <div class="nav-links">
            <a href="?p=orders" class="<?= $page === 'orders' ? 'active' : '' ?>">📦 Manajemen Pesanan</a>
            <a href="?p=users" class="<?= $page === 'users' ? 'active' : '' ?>">👥 Database Pelanggan</a>
            <a href="?p=cars" class="<?= $page === 'cars' ? 'active' : '' ?>">🚘 Katalog Kendaraan</a>
        </div>
        <div class="logout-btn">
            <a href="?logout=true">Keluar Sistem</a>
        </div>
    </aside>

    <main class="main-content">
        <!-- Glow Effect Background -->
        <div style="position: absolute; top: -100px; right: -100px; width: 400px; height: 400px; background: rgba(255, 255, 255, 0.1); border-radius: 50%; filter: blur(150px); z-index: -1;"></div>

        <?php if ($page === 'orders'): ?>
            <div class="header">
                <div>
                    <h1>Manajemen Pesanan</h1>
                    <p style="color: var(--text-muted); margin-top: 8px;">Kelola status pesanan, metode pembayaran, dan pengiriman.</p>
                </div>
                <div class="stat-card">
                    <span>Total Transaksi Masuk</span>
                    <strong><?= count($orders); ?></strong>
                </div>
            </div>

            <?php if ($message): ?><div class="alert-success"><?= e($message); ?></div><?php endif; ?>

            <div class="card">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Klien</th>
                                <th>Spesifikasi Kendaraan</th>
                                <th>Pembayaran</th>
                                <th>Dokumen</th>
                                <th>Status Order</th>
                                <th style="width: 200px;">Aksi Eksekutif</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>
                                        <strong style="color: #fff; font-size: 16px;">#WC-<?= (int)$order['id']; ?></strong><br>
                                        <span style="font-size: 12px; color: var(--text-muted); margin-top: 4px; display: block;"><?= date('d M Y H:i', strtotime($order['created_at'])); ?></span>
                                    </td>
                                    <td>
                                        <strong style="color: #fff;"><?= e($order['first_name'] . ' ' . $order['last_name']); ?></strong><br>
                                        <span style="font-size: 12px; color: var(--text-muted);"><?= e($order['user_email']); ?></span><br>
                                        <span style="font-size: 12px; color: var(--text-muted);">📞 <?= e($order['phone']); ?></span>
                                    </td>
                                    <td>
                                        <strong style="color: #fff; font-size: 15px;"><?= e($order['mobil']); ?></strong><br>
                                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 6px; line-height: 1.6;">
                                            🎨 <?= e($order['warna']); ?><br>
                                            🛞 <?= e($order['velg']); ?><br>
                                            ⚙️ <?= e($order['mesin']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <strong style="color: var(--success); font-size: 16px;"><?= rph((int)$order['total_harga']); ?></strong><br>
                                        <span class="badge badge-pay" style="margin-top: 6px;"><?= e($order['metode_pembayaran'] ?? 'Lunas'); ?></span>
                                    </td>
                                    <td>
                                        <div style="display: flex; flex-direction: column; gap: 8px;">
                                            <?php if (!empty($order['bukti_pembayaran'])): ?>
                                                <img src="../uploads/<?= e($order['bukti_pembayaran']); ?>" class="proof-img" onclick="showModal(this.src)" title="Bukti Transfer">
                                            <?php else: ?>
                                                <span class="badge badge-danger">No TF</span>
                                            <?php endif; ?>
                                            <?php if (!empty($order['ktp_image'])): ?>
                                                <img src="../uploads/<?= e($order['ktp_image']); ?>" class="proof-img" onclick="showModal(this.src)" title="KTP Pelanggan">
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php
                                            $badgeClass = 'badge-warning';
                                            switch($order['status']) {
                                                case 'Dikonfirmasi': $badgeClass = 'badge-info'; break;
                                                case 'Sedang Diproses': $badgeClass = 'badge-purple'; break;
                                                case 'Dalam Pengiriman': $badgeClass = 'badge-warning'; break;
                                                case 'Selesai': $badgeClass = 'badge-success'; break;
                                                case 'Dibatalkan': $badgeClass = 'badge-danger'; break;
                                            }
                                        ?>
                                        <span class="badge <?= $badgeClass; ?>"><?= e($order['status']); ?></span>
                                    </td>
                                    <td>
                                        <form method="post">
                                            <input type="hidden" name="update_order_status" value="1">
                                            <input type="hidden" name="order_id" value="<?= (int)$order['id']; ?>">
                                            <select name="status" class="status-select">
                                                <option value="Menunggu Verifikasi" <?= $order['status'] === 'Menunggu Verifikasi' ? 'selected' : ''; ?>>Menunggu Verifikasi</option>
                                                <option value="Dikonfirmasi" <?= $order['status'] === 'Dikonfirmasi' ? 'selected' : ''; ?>>Dikonfirmasi</option>
                                                <option value="Sedang Diproses" <?= $order['status'] === 'Sedang Diproses' ? 'selected' : ''; ?>>Sedang Diproses</option>
                                                <option value="Dalam Pengiriman" <?= $order['status'] === 'Dalam Pengiriman' ? 'selected' : ''; ?>>Dalam Pengiriman</option>
                                                <option value="Selesai" <?= $order['status'] === 'Selesai' ? 'selected' : ''; ?>>Selesai</option>
                                                <option value="Dibatalkan" <?= $order['status'] === 'Dibatalkan' ? 'selected' : ''; ?>>Dibatalkan</option>
                                            </select>
                                            <button type="submit" class="btn" style="width: 100%;">Terapkan Status</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php elseif ($page === 'users'): ?>
            <div class="header">
                <div>
                    <h1>Database Pelanggan</h1>
                    <p style="color: var(--text-muted); margin-top: 8px;">Informasi mendetail tentang akun pengguna yang terdaftar.</p>
                </div>
                <div class="stat-card">
                    <span>Total Pengguna Aktif</span>
                    <strong><?= count($users); ?></strong>
                </div>
            </div>

            <div class="card">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Profil</th>
                                <th>Informasi Kontak</th>
                                <th>Alamat Terdaftar</th>
                                <th>Status Verifikasi</th>
                                <th>Bergabung Sejak</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 15px;">
                                            <img src="<?= !empty($u['profile_pic']) ? e($u['profile_pic']) : 'https://ui-avatars.com/api/?name='.urlencode($u['first_name'].'+'.$u['last_name']).'&background=random' ?>" class="profile-img">
                                            <div>
                                                <strong style="color: #fff;"><?= e($u['first_name'] . ' ' . $u['last_name']); ?></strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span style="color: #e2e8f0; font-weight: 500;"><?= e($u['email']); ?></span><br>
                                        <span style="font-size: 13px; color: var(--text-muted); margin-top: 4px; display: inline-block;">📞 <?= e($u['phone'] ?? '-'); ?></span>
                                    </td>
                                    <td style="max-width: 250px;">
                                        <?php if (!empty($u['alamat'])): ?>
                                            <p style="font-size: 13px; line-height: 1.5; color: var(--text-muted);"><?= e($u['alamat']); ?></p>
                                        <?php else: ?>
                                            <span style="color: var(--border);">Belum diatur</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($u['ktp_image'])): ?>
                                            <span class="badge badge-success">KTP Valid</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Unverified</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="color: var(--text-muted); font-size: 13px;">
                                        <?= date('d M Y', strtotime($u['created_at'])); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php elseif ($page === 'cars'): ?>
            <div class="header">
                <div>
                    <h1>Katalog Kendaraan</h1>
                    <p style="color: var(--text-muted); margin-top: 8px;">Tambahkan atau hapus mobil yang tersedia di *showroom*.</p>
                </div>
                <div class="stat-card">
                    <span>Total Koleksi Kendaraan</span>
                    <strong><?= count($cars); ?></strong>
                </div>
            </div>

            <?php if ($message): ?><div class="alert-success"><?= e($message); ?></div><?php endif; ?>

            <div style="display: flex; gap: 30px; align-items: flex-start;">
                <!-- Form Add Car -->
                <div class="card" style="width: 350px; flex-shrink: 0;">
                    <div class="card-header">
                        <h3>➕ Tambah Mobil Baru</h3>
                    </div>
                    <div style="padding: 25px;">
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" name="add_car" value="1">
                            
                            <div class="form-group">
                                <label>Nama Mobil Lengkap</label>
                                <input type="text" name="car_name" required placeholder="Contoh: Porsche 911 GT3">
                            </div>
                            
                            <div class="form-group">
                                <label>Harga Dasar (Rp)</label>
                                <input type="number" name="price" required placeholder="Contoh: 3500000000" min="0">
                            </div>
                            
                            <div class="form-group">
                                <label>Kategori</label>
                                <select name="category" required>
                                    <option value="Luxury">Luxury</option>
                                    <option value="Sport">Sport</option>
                                    <option value="SUV">SUV</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Gambar Mobil Baru (PNG/JPG)</label>
                                <input type="file" name="car_image" accept="image/*" required style="padding: 10px;">
                            </div>
                            
                            <button type="submit" class="btn" style="width: 100%; padding: 14px; font-size: 14px;">Publikasikan ke Katalog</button>
                        </form>
                    </div>
                </div>

                <!-- Table Cars -->
                <div class="card" style="flex: 1;">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Aset Visual</th>
                                    <th>Nama Kendaraan</th>
                                    <th>Kategori</th>
                                    <th>Harga Jual (Base)</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cars as $car): ?>
                                    <tr>
                                        <td>
                                            <img src="../models/<?= e($car['file_name']); ?>" style="width: 100px; height: 60px; object-fit: cover; border-radius: 8px;">
                                        </td>
                                        <td>
                                            <strong style="color: #fff; font-size: 15px;"><?= e($car['car_name']); ?></strong>
                                        </td>
                                        <td>
                                            <?php
                                                $catClass = 'badge-info';
                                                if($car['category'] === 'Sport') $catClass = 'badge-danger';
                                                if($car['category'] === 'SUV') $catClass = 'badge-warning';
                                            ?>
                                            <span class="badge <?= $catClass ?>"><?= e($car['category']); ?></span>
                                        </td>
                                        <td><strong style="color: var(--success);"><?= rph((int)$car['price']); ?></strong></td>
                                        <td>
                                            <form method="post" onsubmit="return confirm('Hapus mobil ini dari katalog?');">
                                                <input type="hidden" name="delete_car" value="1">
                                                <input type="hidden" name="car_id" value="<?= (int)$car['id']; ?>">
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        <?php endif; ?>
    </main>

    <!-- Modal Gambar -->
    <div id="imgModal" class="modal" onclick="this.style.display='none'">
        <span class="close" title="Tutup">&times;</span>
        <img class="modal-content" id="img01">
    </div>

    <script>
        function showModal(src) {
            var modal = document.getElementById("imgModal");
            var modalImg = document.getElementById("img01");
            modal.style.display = "block";
            modalImg.src = src;
        }
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                document.getElementById("imgModal").style.display = "none";
            }
        });
    </script>
</body>
</html>
