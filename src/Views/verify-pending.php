<div class="container">
    <div class="auth-page">
        <div class="verify-pending-card">
            <div class="verify-icon">📧</div>
            <h1>Kiểm tra email của bạn</h1>
            
            <?php if ($emailFailed ?? false): ?>
                <div class="alert alert-error">
                    ⚠️ Có lỗi khi gửi email. Vui lòng thử gửi lại hoặc liên hệ hỗ trợ.
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['resent'])): ?>
                <div class="alert alert-success">
                    ✅ Email xác thực đã được gửi lại!
                </div>
            <?php endif; ?>
            
            <p class="verify-message">
                Chúng tôi đã gửi email xác thực đến địa chỉ:
            </p>
            <p class="verify-email">
                <strong><?= htmlspecialchars($email ?? '') ?></strong>
            </p>
            <p class="verify-instruction">
                Vui lòng kiểm tra hộp thư (và thư mục spam) để tìm email xác thực.<br>
                Click vào link trong email để hoàn tất đăng ký.
            </p>
            
            <div class="verify-note">
                <p>⏰ Link xác thực sẽ hết hạn sau <strong>24 giờ</strong></p>
            </div>
            
            <div class="verify-actions">
                <form action="/resend-verification" method="POST" class="resend-form">
                    <input type="hidden" name="email" value="<?= htmlspecialchars($email ?? '') ?>">
                    <button type="submit" class="btn btn-secondary">📤 Gửi lại email</button>
                </form>
                <a href="/login" class="btn btn-outline">← Về trang đăng nhập</a>
            </div>
        </div>
    </div>
</div>

<style>
.verify-pending-card {
    max-width: 500px;
    margin: 3rem auto;
    background: white;
    padding: 3rem;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.verify-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.verify-pending-card h1 {
    color: #333;
    margin-bottom: 1.5rem;
    font-size: 1.8rem;
}

.verify-message {
    color: #666;
    margin-bottom: 0.5rem;
}

.verify-email {
    color: #d70018;
    font-size: 1.1rem;
    margin-bottom: 1.5rem;
    word-break: break-all;
}

.verify-instruction {
    color: #666;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.verify-note {
    background: #fff3cd;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.verify-note p {
    margin: 0;
    color: #856404;
}

.verify-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.resend-form {
    margin: 0;
}

.alert {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
}

.alert-error {
    background: #fee;
    color: #c00;
    border: 1px solid #fcc;
}

.alert-success {
    background: #efe;
    color: #080;
    border: 1px solid #cfc;
}
</style>
