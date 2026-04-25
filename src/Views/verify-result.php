<div class="container">
    <div class="auth-page">
        <div class="verify-result-card <?= $success ? 'success' : 'error' ?>">
            <div class="verify-result-icon">
                <?= $success ? '✅' : '❌' ?>
            </div>
            <h1><?= htmlspecialchars($title ?? 'Kết quả xác thực') ?></h1>
            <p class="verify-result-message">
                <?= htmlspecialchars($message ?? '') ?>
            </p>
            
            <div class="verify-result-actions">
                <?php if ($success): ?>
                    <a href="/login" class="btn btn-primary btn-large">🔑 Đăng nhập ngay</a>
                <?php else: ?>
                    <a href="/register" class="btn btn-primary">📝 Đăng ký lại</a>
                    <a href="/login" class="btn btn-outline">← Về trang đăng nhập</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.verify-result-card {
    max-width: 500px;
    margin: 3rem auto;
    background: white;
    padding: 3rem;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.verify-result-card.success {
    border-top: 4px solid #28a745;
}

.verify-result-card.error {
    border-top: 4px solid #dc3545;
}

.verify-result-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.verify-result-card h1 {
    margin-bottom: 1rem;
    font-size: 1.8rem;
}

.verify-result-card.success h1 {
    color: #28a745;
}

.verify-result-card.error h1 {
    color: #dc3545;
}

.verify-result-message {
    color: #666;
    line-height: 1.6;
    margin-bottom: 2rem;
    font-size: 1.1rem;
}

.verify-result-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.btn-large {
    padding: 1rem 2rem;
    font-size: 1.1rem;
}
</style>
