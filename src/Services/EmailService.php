<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private string $smtpHost;
    private string $smtpUsername;
    private string $smtpPassword;
    private int $smtpPort;
    private string $fromEmail;
    private string $fromName;

    public function __construct()
    {
        // Load environment variables from .env file (for local development)
        $envFile = __DIR__ . '/../../.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                if (strpos($line, '=') === false) continue;
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                // Only set if not already set by system environment
                if (!getenv($key)) {
                    $_ENV[$key] = $value;
                    putenv("$key=$value");
                }
            }
        }

        // Priority: system env (cloud) > .env file (local)
        $this->smtpHost = getenv('SMTP_HOST') ?: ($_ENV['SMTP_HOST'] ?? 'smtp.gmail.com');
        $this->smtpPort = (int)(getenv('SMTP_PORT') ?: ($_ENV['SMTP_PORT'] ?? 587));
        $this->smtpUsername = getenv('SMTP_USERNAME') ?: ($_ENV['SMTP_USERNAME'] ?? '');
        $this->smtpPassword = getenv('SMTP_PASSWORD') ?: ($_ENV['SMTP_PASSWORD'] ?? '');
        $this->fromEmail = getenv('MAIL_FROM_EMAIL') ?: ($_ENV['MAIL_FROM_EMAIL'] ?? $this->smtpUsername);
        $this->fromName = getenv('MAIL_FROM_NAME') ?: ($_ENV['MAIL_FROM_NAME'] ?? 'Computer Shop');
    }

    /**
     * Send OTP verification email to user
     */
    public function sendVerificationEmail(string $toEmail, string $toName, string $otpCode): bool
    {
        $subject = "Mã xác thực OTP - Computer Shop";
        
        $body = $this->getOtpEmailTemplate($toName, $otpCode);
        
        return $this->send($toEmail, $toName, $subject, $body);
    }

    /**
     * Send email using PHPMailer
     */
    private function send(string $toEmail, string $toName, string $subject, string $body): bool
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = $this->smtpHost;
            $mail->SMTPAuth = true;
            $mail->Username = $this->smtpUsername;
            $mail->Password = $this->smtpPassword;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $this->smtpPort;
            $mail->CharSet = 'UTF-8';
            $mail->Timeout = 15; // 15 second timeout to prevent 504 Gateway Timeout

            // Recipients
            $mail->setFrom($this->fromEmail, $this->fromName);
            $mail->addAddress($toEmail, $toName);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = strip_tags(str_replace('<br>', "\n", $body));

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Email sending failed: " . $mail->ErrorInfo);
            return false;
        }
    }

    /**
     * Get OTP email HTML template
     */
    private function getOtpEmailTemplate(string $name, string $otpCode): string
    {
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #d70018, #ff4757); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #fff; padding: 30px; border: 1px solid #ddd; border-top: none; }
        .otp-box { background: #f8f9fa; border: 2px dashed #d70018; border-radius: 12px; padding: 25px; text-align: center; margin: 25px 0; }
        .otp-code { font-size: 36px; font-weight: bold; letter-spacing: 12px; color: #d70018; font-family: 'Courier New', monospace; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        .warning { background: #fff3cd; padding: 12px; border-radius: 8px; color: #856404; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🖥️ Computer Shop</h1>
        </div>
        <div class="content">
            <h2>Xin chào {$name}!</h2>
            <p>Cảm ơn bạn đã đăng ký tài khoản tại <strong>Computer Shop</strong>.</p>
            <p>Đây là mã OTP xác thực email của bạn:</p>
            <div class="otp-box">
                <div class="otp-code">{$otpCode}</div>
            </div>
            <p>Nhập mã này vào trang xác thực để hoàn tất đăng ký.</p>
            <div class="warning">
                <p>⏰ <strong>Lưu ý:</strong> Mã OTP này sẽ hết hạn sau <strong>10 phút</strong>.</p>
                <p>🔒 Không chia sẻ mã này cho bất kỳ ai.</p>
            </div>
            <hr>
            <p style="color: #666; font-size: 13px;">Nếu bạn không đăng ký tài khoản này, vui lòng bỏ qua email này.</p>
        </div>
        <div class="footer">
            <p>© 2024 Computer Shop. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
HTML;
    }
}
