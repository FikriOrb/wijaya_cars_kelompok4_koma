<?php
declare(strict_types=1);

require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/mailer.php';

$error = '';
$notice = '';
$values = [
    'first_name' => '',
    'last_name' => '',
    'email' => '',
    'phone' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values['first_name'] = trim((string) ($_POST['first_name'] ?? ''));
    $values['last_name'] = trim((string) ($_POST['last_name'] ?? ''));
    $values['email'] = strtolower(trim((string) ($_POST['email'] ?? '')));
    $values['phone'] = preg_replace('/[^0-9+]/', '', (string) ($_POST['phone'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($values['first_name'] === '' || $values['last_name'] === '' || !filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Lengkapi nama dan email valid.';
    } elseif (strlen($password) < 8) {
        $error = 'Password minimal 8 karakter.';
    } else {
        $pdo = get_db();
        $code = generate_otp();
        $expiry = date('Y-m-d H:i:s', time() + 300);

        try {
            $stmt = $pdo->prepare('
                INSERT INTO users (first_name, last_name, email, phone, password, verification_code, code_expiry, otp_last_sent)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
            ');
            $stmt->execute([
                $values['first_name'],
                $values['last_name'],
                $values['email'],
                $values['phone'],
                password_hash($password, PASSWORD_DEFAULT),
                $code,
                $expiry,
            ]);

            $_SESSION['pending_verification_email'] = $values['email'];
            $sent = send_otp_email($values['email'], $values['first_name'], $code);
            if (!$sent) {
                $err = $_SESSION['mailer_error'] ?? 'Sistem sedang sibuk, silakan coba lagi.';
                $_SESSION['dev_otp_notice'] = 'Gagal kirim email: ' . $err;
            }
            redirect_to('/Login_create/verify-otp.php');
        } catch (PDOException $e) {
            $error = 'Email sudah terdaftar.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Wijaya Cars</title>
    <link rel="stylesheet" href="tema_lc.css">
</head>
<body>
    <div class="split-screen">
        <div class="left-pane">
            <div class="form-container glass-panel">
                <div class="brand-text">WIJAYA CARS</div>

                <div class="header-text">
                    <h1>Create Account</h1>
                    <p class="subtitle">Already a member? <a href="Login.php" class="link">Log in</a></p>
                </div>

                <?php if ($error): ?>
                    <div class="alert error"><?= e($error); ?></div>
                <?php endif; ?>
                <?php if ($notice): ?>
                    <div class="alert"><?= e($notice); ?></div>
                <?php endif; ?>

                <form method="post" autocomplete="on">
                    <div class="name-row">
                        <div>
                            <label for="first-name">First Name</label>
                            <input type="text" id="first-name" name="first_name" value="<?= e($values['first_name']); ?>" placeholder="John" required>
                        </div>
                        <div>
                            <label for="last-name">Last Name</label>
                            <input type="text" id="last-name" name="last_name" value="<?= e($values['last_name']); ?>" placeholder="Doe" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= e($values['email']); ?>" placeholder="john.doe@example.com" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" name="phone" value="<?= e($values['phone']); ?>" placeholder="+62" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Create a password" required minlength="8">
                        <p class="password-hint">Minimum 8 characters.</p>
                    </div>

                    <button type="submit" class="btn-submit">Create Account</button>
                </form>

                <div class="back-home">
                    <a href="../Beranda/index.php">Back to Home</a>
                </div>
            </div>
        </div>

        <div class="right-pane"></div>
    </div>
</body>
</html>
