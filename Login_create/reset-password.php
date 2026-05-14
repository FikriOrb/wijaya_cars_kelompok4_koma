<?php
declare(strict_types=1);

require_once __DIR__ . '/../auth.php';

$email = (string) ($_SESSION['reset_email'] ?? '');
if ($email === '') {
    redirect_to('/Login_create/forgot-password.php');
}

$error = '';
$notice = (string) ($_SESSION['dev_otp_notice'] ?? '');
unset($_SESSION['dev_otp_notice']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = preg_replace('/\D/', '', (string) ($_POST['code'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    $stmt = get_db()->prepare('SELECT id, reset_code, reset_expiry FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !hash_equals((string) $user['reset_code'], $code) || strtotime((string) $user['reset_expiry']) < time()) {
        $error = 'Kode reset tidak valid atau sudah kedaluwarsa.';
    } elseif (strlen($password) < 8) {
        $error = 'Password minimal 8 karakter.';
    } else {
        $update = get_db()->prepare('UPDATE users SET password = ?, reset_code = NULL, reset_expiry = NULL, is_verified = 1 WHERE id = ?');
        $update->execute([password_hash($password, PASSWORD_DEFAULT), (int) $user['id']]);
        unset($_SESSION['reset_email']);
        redirect_to('/Login_create/Login.php');
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Wijaya Cars</title>
    <link rel="stylesheet" href="tema_lc.css">
</head>
<body>
<div class="split-screen">
    <div class="left-pane">
        <div class="form-container glass-panel">
            <div class="brand-text">WIJAYA CARS</div>
            <div class="header-text">
                <h1>Reset Password</h1>
                <p class="subtitle">Kode reset berlaku 5 menit untuk <?= e($email); ?>.</p>
            </div>
            <?php if ($error): ?><div class="alert error"><?= e($error); ?></div><?php endif; ?>
            <?php if ($notice): ?><div class="alert"><?= e($notice); ?></div><?php endif; ?>
            <form method="post">
                <div class="form-group">
                    <label for="code">Reset Code</label>
                    <input type="text" id="code" name="code" maxlength="6" pattern="[0-9]{6}" required>
                </div>
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" minlength="8" required>
                </div>
                <button type="submit" class="btn-submit">Update Password</button>
            </form>
        </div>
    </div>
    <div class="right-pane"></div>
</div>
</body>
</html>
