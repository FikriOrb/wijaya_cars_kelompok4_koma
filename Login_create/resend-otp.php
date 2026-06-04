<?php
declare(strict_types=1);

require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/mailer.php';

$email = (string) ($_SESSION['pending_verification_email'] ?? '');
if ($email === '') {
    redirect_to('/Login_create/Login.php');
}

$stmt = get_db()->prepare('SELECT id, first_name, otp_last_sent FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && can_send_otp($user['otp_last_sent'])) {
    $code = generate_otp();
    $update = get_db()->prepare('UPDATE users SET verification_code = ?, code_expiry = ?, otp_last_sent = NOW() WHERE id = ?');
    $update->execute([$code, date('Y-m-d H:i:s', time() + 300), (int) $user['id']]);
    if (!send_otp_email($email, (string) $user['first_name'], $code)) {
        $err = $_SESSION['mailer_error'] ?? 'Sistem sedang sibuk.';
        $_SESSION['dev_otp_notice'] = 'Gagal kirim email: ' . $err;
    }
} else {
    $_SESSION['dev_otp_notice'] = 'Tunggu 60 detik sebelum meminta OTP baru.';
}

redirect_to('/Login_create/verify-otp.php');
