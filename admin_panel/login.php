<?php
session_start();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Hardcoded credentials for simplicity
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Username atau Password salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Wijaya Cars</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Inter', sans-serif; margin: 0; padding: 0; }
        body { background: #0f172a; display: flex; justify-content: center; align-items: center; height: 100vh; color: #f8fafc; }
        .login-box { background: #1e293b; padding: 40px; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5); width: 100%; max-width: 400px; text-align: center; border: 1px solid #334155; }
        .login-box h2 { font-size: 24px; font-weight: 700; margin-bottom: 5px; color: #f8fafc; }
        .login-box p { color: #94a3b8; font-size: 14px; margin-bottom: 30px; }
        .input-group { text-align: left; margin-bottom: 20px; }
        .input-group label { display: block; margin-bottom: 8px; font-size: 14px; color: #cbd5e1; font-weight: 500; }
        .input-group input { width: 100%; padding: 12px 15px; border-radius: 8px; border: 1px solid #475569; background: #0f172a; color: white; font-size: 15px; outline: none; transition: 0.2s; }
        .input-group input:focus { border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3); }
        .btn-login { width: 100%; padding: 12px; border: none; background: #3b82f6; color: white; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; transition: 0.2s; margin-top: 10px; }
        .btn-login:hover { background: #2563eb; transform: translateY(-2px); }
        .error { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Wijaya Admin Portal</h2>
        <p>Restricted Access Only</p>
        
        <?php if ($error): ?>
            <div class="error"><?= $error; ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" required placeholder="Masukkan username admin">
            </div>
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Masukkan password">
            </div>
            <button type="submit" class="btn-login">Login ke Dashboard</button>
        </form>
    </div>
</body>
</html>
