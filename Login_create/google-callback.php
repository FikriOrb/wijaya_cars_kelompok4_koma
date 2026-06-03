<?php
declare(strict_types=1);

require_once __DIR__ . '/../auth.php';

$state = (string) ($_GET['state'] ?? '');
$code = (string) ($_GET['code'] ?? '');

if ($code === '' || $state === '' || !hash_equals((string) ($_SESSION['google_oauth_state'] ?? ''), $state)) {
    redirect_to('/Login_create/Login.php');
}

// Set GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET in your environment or .env file
$googleClientId     = getenv('GOOGLE_CLIENT_ID') ?: 'YOUR_GOOGLE_CLIENT_ID';
$googleClientSecret = getenv('GOOGLE_CLIENT_SECRET') ?: 'YOUR_GOOGLE_CLIENT_SECRET';

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$redirectUri = $protocol . '://' . $_SERVER['HTTP_HOST'] . BASE_URL . '/Login_create/google-callback.php';

$tokenPayload = http_build_query([
    'code' => $code,
    'client_id' => $googleClientId,
    'client_secret' => $googleClientSecret,
    'redirect_uri' => $redirectUri,
    'grant_type' => 'authorization_code',
]);

$ch = curl_init('https://oauth2.googleapis.com/token');
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $tokenPayload,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
]);
$tokenResponse = curl_exec($ch);
curl_close($ch);
$token = json_decode((string) $tokenResponse, true);

if (empty($token['access_token'])) {
    redirect_to('/Login_create/Login.php');
}

$ch = curl_init('https://www.googleapis.com/oauth2/v3/userinfo');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $token['access_token']],
]);
$profileResponse = curl_exec($ch);
curl_close($ch);
$profile = json_decode((string) $profileResponse, true);

if (empty($profile['email']) || empty($profile['sub'])) {
    redirect_to('/Login_create/Login.php');
}

$pdo = get_db();
$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? OR google_id = ? LIMIT 1');
$stmt->execute([$profile['email'], $profile['sub']]);
$existing = $stmt->fetch();

if ($existing) {
    $update = $pdo->prepare('UPDATE users SET google_id = ?, profile_pic = ?, is_verified = 1 WHERE id = ?');
    $update->execute([$profile['sub'], $profile['picture'] ?? null, (int) $existing['id']]);
    login_user((int) $existing['id']);
} else {
    $first = (string) ($profile['given_name'] ?? $profile['name'] ?? 'Google');
    $last = (string) ($profile['family_name'] ?? 'User');
    $insert = $pdo->prepare('
        INSERT INTO users (google_id, first_name, last_name, email, profile_pic, is_verified)
        VALUES (?, ?, ?, ?, ?, 1)
    ');
    $insert->execute([$profile['sub'], $first, $last, $profile['email'], $profile['picture'] ?? null]);
    login_user((int) $pdo->lastInsertId());
}

redirect_to('/dashboard/index.php');
