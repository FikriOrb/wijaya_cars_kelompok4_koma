<?php
declare(strict_types=1);

require_once __DIR__ . '/../auth.php';

$email = (string) ($_SESSION['pending_verification_email'] ?? '');
if ($email === '') {
    redirect_to('/Login_create/Login.php');
}

$error = '';
$notice = (string) ($_SESSION['dev_otp_notice'] ?? '');
unset($_SESSION['dev_otp_notice']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = preg_replace('/\D/', '', (string) ($_POST['code'] ?? ''));
    $stmt = get_db()->prepare('SELECT id, verification_code, code_expiry FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || strlen($code) !== 6) {
        $error = 'Kode OTP tidak valid.';
    } elseif (!hash_equals((string) $user['verification_code'], $code)) {
        $error = 'Kode OTP tidak sesuai.';
    } elseif (strtotime((string) $user['code_expiry']) < time()) {
        $error = 'Kode OTP sudah kedaluwarsa.';
    } else {
        $update = get_db()->prepare('UPDATE users SET is_verified = 1, verification_code = NULL, code_expiry = NULL WHERE id = ?');
        $update->execute([(int) $user['id']]);
        unset($_SESSION['pending_verification_email']);
        login_user((int) $user['id']);
        redirect_to('/dashboard/index.php');
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - Wijaya Cars</title>
    <link rel="stylesheet" href="tema_lc.css">
</head>
<body>
    <div class="split-screen">
        <div class="left-pane">
            <div class="form-container glass-panel">
                <div class="brand-text">WIJAYA CARS</div>
                <div class="header-text">
                    <h1>Email OTP</h1>
                    <p class="subtitle">Masukkan 6 digit kode yang dikirim ke <?= e($email); ?>.</p>
                </div>

                <?php if ($error): ?><div class="alert error"><?= e($error); ?></div><?php endif; ?>
                <?php if ($notice): ?><div class="alert"><?= e($notice); ?></div><?php endif; ?>

                <form method="post">
                    <div class="form-group">
                        <label for="code">Verification Code</label>
                        <input type="text" id="code" name="code" inputmode="numeric" maxlength="6" pattern="[0-9]{6}" placeholder="000000" required>
                    </div>
                    <div class="otp-countdown" data-seconds="300">05:00</div>
                    <button type="submit" class="btn-submit">Verify Account</button>
                </form>

                <div class="back-home">
                    <a href="resend-otp.php">Resend OTP</a>
                </div>
            </div>
        </div>
        <div class="right-pane"></div>
    </div>
    <script>
        const timer = document.querySelector('.otp-countdown');
        let left = Number(timer.dataset.seconds);
        setInterval(() => {
            left = Math.max(0, left - 1);
            const minutes = String(Math.floor(left / 60)).padStart(2, '0');
            const seconds = String(left % 60).padStart(2, '0');
            timer.textContent = `${minutes}:${seconds}`;
        }, 1000);
    </script>
</body>
</html>
