<?php
declare(strict_types=1);

require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/mailer.php';

$message = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $stmt = get_db()->prepare('SELECT id, first_name FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $code = generate_otp();
        $update = get_db()->prepare('UPDATE users SET reset_code = ?, reset_expiry = ? WHERE id = ?');
        $update->execute([$code, date('Y-m-d H:i:s', time() + 300), (int) $user['id']]);
        $_SESSION['reset_email'] = $email;
        if (!send_otp_email($email, (string) $user['first_name'], $code, 'reset password')) {
            $err = $_SESSION['mailer_error'] ?? 'Sistem sedang sibuk.';
            $_SESSION['dev_otp_notice'] = 'Gagal kirim email: ' . $err;
        }
        redirect_to('/Login_create/reset-password.php');
    }

    $message = 'Jika email terdaftar, kode reset akan dikirim.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - Wijaya Cars</title>
    <link rel="stylesheet" href="tema_lc.css">
</head>
<body>
<div class="split-screen">
    <div class="left-pane">
        <div class="form-container glass-panel">
            <div class="brand-text">WIJAYA CARS</div>
            <div class="header-text">
                <h1>Pulihkan Akun</h1>
                <p class="subtitle">Masukkan email untuk menerima OTP reset password.</p>
            </div>
            <?php if ($message): ?><div class="alert"><?= e($message); ?></div><?php endif; ?>
            <form method="post">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= e($email); ?>" required>
                </div>
                <button type="submit" class="btn-submit">Kirim OTP</button>
            </form>
            <div class="back-home"><a href="Login.php">Kembali ke Masuk</a></div>
        </div>
    </div>
    <div class="right-pane"></div>
</div>
</body>
</html>
