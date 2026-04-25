<div class="container">
    <div class="auth-page">
        <div class="auth-form">
            <h1>Đăng nhập</h1>
            
            <?php if (isset($_GET['error'])): ?>
                <?php if ($_GET['error'] === 'unverified'): ?>
                    <div class="alert alert-warning">
                        📧 Email của bạn chưa được xác thực. Vui lòng kiểm tra hộp thư để xác thực.
                        <form action="/resend-verification" method="POST" style="margin-top: 10px;">
                            <input type="email" name="email" placeholder="Nhập email của bạn" required style="padding: 8px; border-radius: 4px; border: 1px solid #ddd; width: 60%;">
                            <button type="submit" class="btn btn-small btn-secondary">Gửi lại email</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="alert alert-error">Email hoặc mật khẩu không đúng!</div>
                <?php endif; ?>
            <?php endif; ?>

            <form action="/login" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
            </form>

            <p class="auth-link">
                Chưa có tài khoản? <a href="/register">Đăng ký ngay</a>
            </p>
        </div>
    </div>
</div>
