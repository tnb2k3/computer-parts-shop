<?php

namespace App\Services;

class EmailService
{
    private string $resendApiKey;
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
                if (!getenv($key)) {
                    $_ENV[$key] = $value;
                    putenv("$key=$value");
                }
            }
        }

        $this->resendApiKey = getenv('RESEND_API_KEY') ?: ($_ENV['RESEND_API_KEY'] ?? '');
        $this->fromEmail = getenv('MAIL_FROM_EMAIL') ?: ($_ENV['MAIL_FROM_EMAIL'] ?? 'onboarding@resend.dev');
        $this->fromName = getenv('MAIL_FROM_NAME') ?: ($_ENV['MAIL_FROM_NAME'] ?? 'Computer Shop');
    }

    /**
     * Send OTP verification email to user
     */
    public function sendVerificationEmail(string $toEmail, string $toName, string $otpCode): bool
    {
        $subject = "Mã xác thực OTP - Computer Shop";
        $body = $this->getOtpEmailTemplate($toName, $otpCode);
        
        return $this->sendViaResend($toEmail, $subject, $body);
    }

    /**
     * Send email via Resend API (fast, reliable HTTP API)
     */
    private function sendViaResend(string $toEmail, string $subject, string $htmlBody): bool
    {
        if (empty($this->resendApiKey)) {
            error_log("Resend API key not configured");
            return false;
        }

        $data = json_encode([
            'from' => $this->fromName . ' <' . $this->fromEmail . '>',
            'to' => [$toEmail],
            'subject' => $subject,
            'html' => $htmlBody,
        ]);

        $ch = curl_init('https://api.resend.com/emails');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->resendApiKey,
                'Content-Type: application/json',
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CONNECTTIMEOUT => 5,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            error_log("Resend API curl error: " . $error);
            return false;
        }

        if ($httpCode >= 200 && $httpCode < 300) {
            return true;
        }

        error_log("Resend API error (HTTP $httpCode): " . $response);
        return false;
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
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #d70018, #ff4757); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { background: #fff; padding: 30px; border: 1px solid #ddd; border-top: none; }
        .otp-box { background: #f8f9fa; border: 2px dashed #d70018; border-radius: 12px; padding: 25px; text-align: center; margin: 25px 0; }
        .otp-code { font-size: 36px; font-weight: bold; letter-spacing: 12px; color: #d70018; font-family: 'Courier New', monospace; }
        .footer { text-align: center; padding: 20px; color: #999; font-size: 12px; background: #fff; border: 1px solid #ddd; border-top: none; border-radius: 0 0 10px 10px; }
        .warning { background: #fff3cd; padding: 12px; border-radius: 8px; color: #856404; margin-top: 20px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🖥️ SakinoStore</h1>
            <p style="margin: 5px 0 0; opacity: 0.9;">Xác thực tài khoản</p>
        </div>
        <div class="content">
            <h2 style="margin-top: 0;">Xin chào {$name}!</h2>
            <p>Cảm ơn bạn đã đăng ký tài khoản tại <strong>SakinoStore</strong>.</p>
            <p>Đây là mã OTP xác thực email của bạn:</p>
            <div class="otp-box">
                <div class="otp-code">{$otpCode}</div>
            </div>
            <p>Nhập mã này vào trang xác thực để hoàn tất đăng ký.</p>
            <div class="warning">
                ⏰ Mã OTP này sẽ hết hạn sau <strong>10 phút</strong>.<br>
                🔒 Không chia sẻ mã này cho bất kỳ ai.
            </div>
            <hr style="margin-top: 25px; border: none; border-top: 1px solid #eee;">
            <p style="color: #999; font-size: 13px;">Nếu bạn không đăng ký tài khoản này, vui lòng bỏ qua email này.</p>
        </div>
        <div class="footer">
            <p>© 2024 SakinoStore. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
HTML;
    }
}
