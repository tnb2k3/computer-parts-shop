<div class="container">
    <div class="auth-page">
        <div class="auth-form">
            <h1>Đăng ký tài khoản</h1>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <?php if ($_GET['error'] == 'email_exists'): ?>
                        Email đã được sử dụng!
                    <?php elseif ($_GET['error'] == 'username_exists'): ?>
                        Tên đăng nhập đã tồn tại!
                    <?php else: ?>
                        Đã có lỗi xảy ra. Vui lòng thử lại!
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <form action="/register" method="POST">
                <div class="form-group">
                    <label for="username">Tên đăng nhập *</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu *</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="full_name">Họ và tên</label>
                    <input type="text" id="full_name" name="full_name">
                </div>

                <div class="form-group">
                    <label for="phone">Số điện thoại</label>
                    <input type="tel" id="phone" name="phone">
                </div>

                <div class="form-group">
                    <label for="address">Địa chỉ</label>
                    <textarea id="address" name="address" rows="2"></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Đăng ký</button>
            </form>

            <p class="auth-link">
                Đã có tài khoản? <a href="/login">Đăng nhập</a>
            </p>
        </div>
    </div>
</div>
