<?php
declare(strict_types=1);

require_once __DIR__ . '/../auth.php';

$error = '';
$identifier = '';
$_SESSION['google_oauth_state'] = bin2hex(random_bytes(16));
$googleClientId = defined('GOOGLE_CLIENT_ID') ? GOOGLE_CLIENT_ID : 'YOUR_GOOGLE_CLIENT_ID';
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$redirectUri = $protocol . '://' . $_SERVER['HTTP_HOST'] . BASE_URL . '/Login_create/google-callback.php';
$googleParams = http_build_query([
    'client_id' => $googleClientId,
    'redirect_uri' => $redirectUri,
    'response_type' => 'code',
    'scope' => 'openid email profile',
    'state' => $_SESSION['google_oauth_state'],
    'access_type' => 'offline',
    'prompt' => 'select_account',
]);
$googleUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . $googleParams;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim((string) ($_POST['identifier'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    $stmt = get_db()->prepare('SELECT * FROM users WHERE email = ? OR phone = ? LIMIT 1');
    $stmt->execute([$identifier, $identifier]);
    $user = $stmt->fetch();

    if (!$user || empty($user['password']) || !password_verify($password, $user['password'])) {
        $error = 'Email/phone atau password salah.';
    } elseif ((int) $user['is_verified'] !== 1) {
        $_SESSION['pending_verification_email'] = $user['email'];
        redirect_to('/Login_create/verify-otp.php');
    } else {
        login_user((int) $user['id']);
        redirect_to('/Beranda/index.php');
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Wijaya Cars</title>
    <link rel="stylesheet" href="tema_lc.css">
</head>
<body>
    <div class="split-screen">
        <div class="left-pane">
            <div class="form-container glass-panel">
                <div class="brand-text">WIJAYA CARS</div>

                <div class="header-text">
                    <h1>Welcome Back</h1>
                    <p class="subtitle">New here? <a href="Create_account.php" class="link">Create an account</a></p>
                </div>

                <?php if ($error): ?>
                    <div class="alert error"><?= e($error); ?></div>
                <?php endif; ?>

                <form method="post" autocomplete="on">
                    <div class="form-group">
                        <label for="identifier">Email / Phone</label>
                        <input type="text" id="identifier" name="identifier" value="<?= e($identifier); ?>" placeholder="Enter your email or phone" required>
                    </div>

                    <div class="form-group">
                        <div class="field-header">
                            <label for="password">Password</label>
                            <a href="forgot-password.php" class="forgot-pass">Forgot password?</a>
                        </div>
                        <input type="password" id="password" name="password" placeholder="Password" required>
                    </div>

                    <button type="submit" class="btn-submit">Log In</button>
                    <a href="<?= e($googleUrl); ?>" class="btn-google">Continue with Google</a>
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
