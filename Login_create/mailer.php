<?php
declare(strict_types=1);

require_once __DIR__ . '/../koneksi.php';

const SMTP_HOST = 'smtp.gmail.com';
const SMTP_PORT = 587;
const SMTP_FROM_NAME = 'Wijaya Cars';

define('SMTP_USERNAME', $_ENV['WIJAYA_SMTP_USERNAME'] ?? 'carswijaya@gmail.com');
define('SMTP_PASSWORD', $_ENV['WIJAYA_SMTP_PASSWORD'] ?? ''); // HARUS MENGGUNAKAN APP PASSWORD
define('SMTP_FROM', SMTP_USERNAME);

function send_otp_email(string $to, string $name, string $code, string $purpose = 'verifikasi akun'): bool
{
    $subject = 'Kode OTP Wijaya Cars - Verifikasi Akun';
    $body = "
    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 10px;'>
        <h2 style='color: #d32f2f; text-align: center;'>WIJAYA CARS</h2>
        <p>Halo <b>{$name}</b>,</p>
        <p>Berikut adalah kode OTP untuk <b>{$purpose}</b> Anda. Jangan beritahu kode ini kepada siapa pun!</p>
        <div style='text-align: center; margin: 30px 0;'>
            <span style='background-color: #f5f5f5; padding: 15px 30px; font-size: 24px; font-weight: bold; letter-spacing: 5px; border-radius: 5px; color: #333;'>{$code}</span>
        </div>
        <p style='color: #777; font-size: 12px; text-align: center;'>Kode ini akan kedaluwarsa dalam 5 menit.</p>
        <hr style='border: 0; border-top: 1px solid #eee; margin-top: 30px;'>
        <p style='color: #999; font-size: 11px; text-align: center;'>&copy; " . date('Y') . " Wijaya Cars. All rights reserved.</p>
    </div>
    ";

    $autoload = __DIR__ . '/../vendor/autoload.php';
    if (file_exists($autoload)) {
        require_once $autoload;
    }

    if (class_exists('\\PHPMailer\\PHPMailer\\PHPMailer')) {
        try {
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = SMTP_USERNAME !== '';
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = SMTP_PORT;
            $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
            $mail->addAddress($to, $name);
            $mail->Subject = $subject;
            $mail->isHTML(true);
            $mail->Body = $body;
            $mail->AltBody = "Halo {$name},\n\nKode OTP untuk {$purpose} Anda adalah {$code}. Kode berlaku selama 5 menit.\n\nWijaya Cars";
            $mail->send();
            return true;
        } catch (Throwable $e) {
            error_log('Wijaya Cars SMTP error: ' . $e->getMessage());
            return false;
        }
    }

    error_log('Wijaya Cars SMTP error: PHPMailer is not installed or autoload failed.');

    if (SMTP_USERNAME === '') {
        return false;
    }

    return @mail($to, $subject, $body, 'From: ' . SMTP_FROM);
}
