<?php
declare(strict_types=1);

require_once __DIR__ . '/koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function current_user(): ?array
{
    if (empty($_SESSION['user_id'])) {
        return null;
    }

    $stmt = get_db()->prepare('SELECT id, first_name, last_name, email, phone, profile_pic, is_verified FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([(int) $_SESSION['user_id']]);
    $user = $stmt->fetch();

    if (!$user) {
        unset($_SESSION['user_id']);
        return null;
    }

    return $user;
}

function require_login(): array
{
    $user = current_user();
    if (!$user) {
        header('Location: ' . BASE_URL . '/Login_create/Login.php');
        exit;
    }

    return $user;
}

function login_user(int $userId): void
{
    session_regenerate_id(true);
    $_SESSION['user_id'] = $userId;
}

function logout_user(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool) $params['secure'], (bool) $params['httponly']);
    }
    session_destroy();
}

function redirect_to(string $path): void
{
    // Hindari double slash (//) yang dianggap browser sebagai URL beda domain (protocol-relative)
    $url = rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
    header('Location: ' . $url);
    exit;
}

function generate_otp(): string
{
    return (string) random_int(100000, 999999);
}

function can_send_otp(?string $lastSent): bool
{
    if (!$lastSent) {
        return true;
    }

    return strtotime($lastSent) <= time() - 60;
}
