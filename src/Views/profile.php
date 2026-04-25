<div class="container">
    <div class="profile-page">
        <div class="profile-header">
            <div class="profile-avatar">
                <div class="avatar-circle">
                    <?= strtoupper(substr($user['full_name'] ?? $user['username'] ?? 'U', 0, 1)) ?>
                </div>
            </div>
            <div class="profile-info">
                <h1><?= htmlspecialchars($user['full_name'] ?? $user['username'] ?? 'Người dùng') ?></h1>
                <p class="profile-role">
                    <?php if (($user['role'] ?? '') === 'admin'): ?>
                        <span class="role-badge role-admin">👑 Quản trị viên</span>
                    <?php else: ?>
                        <span class="role-badge role-customer">👤 Khách hàng</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                ✅ Thông tin đã được cập nhật thành công!
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">
                ❌ Đã có lỗi xảy ra. Vui lòng thử lại!
            </div>
        <?php endif; ?>

        <div class="profile-content">
            <div class="profile-section">
                <h2>📋 Thông tin cá nhân</h2>
                <form action="/profile/update" method="POST" class="profile-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="full_name">Họ và tên</label>
                            <input type="text" id="full_name" name="full_name" 
                                   value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" 
                                   placeholder="Nhập họ và tên">
                        </div>
                        <div class="form-group">
                            <label for="username">Tên đăng nhập</label>
                            <input type="text" id="username" name="username" 
                                   value="<?= htmlspecialchars($user['username'] ?? '') ?>" 
                                   readonly class="read-only">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" 
                                   value="<?= htmlspecialchars($user['email'] ?? '') ?>" 
                                   readonly class="read-only">
                            <?php if (!empty($user['email_verified'])): ?>
                                <span class="verified-badge">✅ Đã xác thực</span>
                            <?php else: ?>
                                <span class="unverified-badge">⚠️ Chưa xác thực</span>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="phone">Số điện thoại</label>
                            <input type="tel" id="phone" name="phone" 
                                   value="<?= htmlspecialchars($user['phone'] ?? '') ?>" 
                                   placeholder="Nhập số điện thoại">
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="address">Địa chỉ giao hàng</label>
                        <textarea id="address" name="address" rows="3" 
                                  placeholder="Nhập địa chỉ giao hàng"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            💾 Lưu thông tin
                        </button>
                    </div>
                </form>
            </div>

            <div class="profile-section">
                <h2>🔒 Đổi mật khẩu</h2>
                <form action="/profile/change-password" method="POST" class="profile-form">
                    <div class="form-group">
                        <label for="current_password">Mật khẩu hiện tại</label>
                        <input type="password" id="current_password" name="current_password" 
                               placeholder="Nhập mật khẩu hiện tại" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="new_password">Mật khẩu mới</label>
                            <input type="password" id="new_password" name="new_password" 
                                   placeholder="Nhập mật khẩu mới" required minlength="6">
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Xác nhận mật khẩu</label>
                            <input type="password" id="confirm_password" name="confirm_password" 
                                   placeholder="Nhập lại mật khẩu mới" required minlength="6">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-secondary">
                            🔐 Đổi mật khẩu
                        </button>
                    </div>
                </form>
            </div>

            <div class="profile-quick-links">
                <h2>🔗 Liên kết nhanh</h2>
                <div class="quick-links-grid">
                    <a href="/order/history" class="quick-link-card">
                        <span class="quick-link-icon">📦</span>
                        <span class="quick-link-text">Lịch sử đơn hàng</span>
                    </a>
                    <a href="/cart" class="quick-link-card">
                        <span class="quick-link-icon">🛒</span>
                        <span class="quick-link-text">Giỏ hàng</span>
                    </a>
                    <a href="/products" class="quick-link-card">
                        <span class="quick-link-icon">💻</span>
                        <span class="quick-link-text">Sản phẩm</span>
                    </a>
                    <a href="/logout" class="quick-link-card logout">
                        <span class="quick-link-icon">🚪</span>
                        <span class="quick-link-text">Đăng xuất</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-page {
    padding: 2rem 0;
    max-width: 900px;
    margin: 0 auto;
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 2rem;
    padding: 2rem;
    background: linear-gradient(135deg, #d70018 0%, #ff4757 100%);
    border-radius: 16px;
    color: white;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(215, 0, 24, 0.3);
}

.avatar-circle {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: bold;
    border: 4px solid rgba(255, 255, 255, 0.5);
    backdrop-filter: blur(10px);
}

.profile-info h1 {
    margin: 0 0 0.5rem 0;
    font-size: 1.75rem;
}

.role-badge {
    display: inline-block;
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
}

.role-admin {
    background: rgba(255, 215, 0, 0.2);
    border: 1px solid rgba(255, 215, 0, 0.5);
}

.role-customer {
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.5);
}

.profile-content {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.profile-section {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.profile-section h2 {
    margin: 0 0 1.5rem 0;
    font-size: 1.25rem;
    color: #333;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f0f0f0;
}

.profile-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    position: relative;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    font-weight: 600;
    color: #333;
    font-size: 0.9rem;
}

.form-group input,
.form-group textarea {
    padding: 0.875rem 1rem;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    font-family: inherit;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: #d70018;
    outline: none;
    box-shadow: 0 0 0 4px rgba(215, 0, 24, 0.1);
}

.form-group input.read-only {
    background: #f5f5f5;
    color: #666;
    cursor: not-allowed;
}

.verified-badge,
.unverified-badge {
    position: absolute;
    right: 10px;
    top: 38px;
    font-size: 0.75rem;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
}

.verified-badge {
    background: #d4edda;
    color: #155724;
}

.unverified-badge {
    background: #fff3cd;
    color: #856404;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    padding-top: 1rem;
}

.btn {
    padding: 0.875rem 2rem;
    border: none;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #d70018 0%, #ff4757 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(215, 0, 24, 0.4);
}

.btn-secondary {
    background: linear-gradient(135deg, #555 0%, #777 100%);
    color: white;
}

.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
}

.profile-quick-links {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.profile-quick-links h2 {
    margin: 0 0 1.5rem 0;
    font-size: 1.25rem;
    color: #333;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f0f0f0;
}

.quick-links-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
}

.quick-link-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 12px;
    text-decoration: none;
    color: #333;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.quick-link-card:hover {
    background: #fff;
    border-color: #d70018;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.quick-link-icon {
    font-size: 2rem;
}

.quick-link-text {
    font-weight: 500;
    font-size: 0.9rem;
    text-align: center;
}

.quick-link-card.logout {
    border-color: #f8d7da;
    background: #fff5f5;
}

.quick-link-card.logout:hover {
    border-color: #dc3545;
    background: #fff;
}

.alert {
    padding: 1rem 1.5rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
    font-weight: 500;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

@media (max-width: 768px) {
    .profile-header {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
        padding: 1.5rem;
    }

    .avatar-circle {
        width: 80px;
        height: 80px;
        font-size: 2rem;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .quick-links-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .profile-section {
        padding: 1.5rem;
    }
}
</style>
