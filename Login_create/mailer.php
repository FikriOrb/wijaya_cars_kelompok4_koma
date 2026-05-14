<?php
declare(strict_types=1);

require_once __DIR__ . '/../koneksi.php';

const SMTP_HOST = 'smtp.gmail.com';
const SMTP_PORT = 587;
const SMTP_FROM_NAME = 'Wijaya Cars';

define('SMTP_USERNAME', getenv('WIJAYA_SMTP_USERNAME') ?: 'carswijaya@gmail.com');
define('SMTP_PASSWORD', getenv('WIJAYA_SMTP_PASSWORD') ?: 'Wijayacars*****5');
define('SMTP_FROM', SMTP_USERNAME);

function send_otp_email(string $to, string $name, string $code, string $purpose = 'verifikasi akun'): bool
{
    $subject = 'Kode OTP Wijaya Cars';
    $body = "Halo {$name},\n\nKode OTP untuk {$purpose} Anda adalah {$code}. Kode berlaku selama 5 menit.\n\nWijaya Cars";

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
            $mail->Body = $body;
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
