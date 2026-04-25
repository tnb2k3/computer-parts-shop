<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Chỉnh sửa tài khoản' ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body class="admin-body">
    <div class="admin-container">
        <aside class="admin-sidebar">
            <h2>Admin Panel</h2>
            <nav class="admin-nav">
                <a href="/admin/dashboard" class="nav-item">📊 Dashboard</a>
                <a href="/admin/categories" class="nav-item">📁 Danh mục</a>
                <a href="/admin/products" class="nav-item">📦 Sản phẩm</a>
                <a href="/admin/orders" class="nav-item">🛒 Đơn hàng</a>
                <a href="/admin/users" class="nav-item active">👥 Tài khoản</a>
                <a href="/admin/reviews" class="nav-item">⭐ Đánh giá</a>
                <hr>
                <a href="/" class="nav-item">🏠 Về trang chủ</a>
                <a href="/logout" class="nav-item">🚪 Đăng xuất</a>
            </nav>
        </aside>

        <main class="admin-main">
            <div class="admin-content">
                <div class="admin-header">
                    <h1><?= $editUser ? 'Chỉnh sửa tài khoản' : 'Thêm tài khoản' ?></h1>
                    <a href="/admin/users" class="btn btn-secondary">← Quay lại</a>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <?php if ($editUser): ?>
                <form method="POST" class="admin-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="username">Username *</label>
                            <input type="text" id="username" name="username" required
                                value="<?= htmlspecialchars($editUser->username ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required
                                value="<?= htmlspecialchars($editUser->email ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="full_name">Họ tên</label>
                            <input type="text" id="full_name" name="full_name"
                                value="<?= htmlspecialchars($editUser->full_name ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="phone">Số điện thoại</label>
                            <input type="text" id="phone" name="phone"
                                value="<?= htmlspecialchars($editUser->phone ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Địa chỉ</label>
                        <textarea id="address" name="address" rows="3"><?= htmlspecialchars($editUser->address ?? '') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="role">Vai trò *</label>
                        <select id="role" name="role" required>
                            <option value="customer" <?= ($editUser->role ?? 'customer') === 'customer' ? 'selected' : '' ?>>
                                👤 Customer (Khách hàng)
                            </option>
                            <option value="admin" <?= ($editUser->role ?? '') === 'admin' ? 'selected' : '' ?>>
                                👑 Admin (Quản trị viên)
                            </option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">💾 Lưu thay đổi</button>
                        <a href="/admin/users" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
                <?php else: ?>
                    <div class="alert alert-error">
                        Không tìm thấy tài khoản hoặc không thể tạo tài khoản mới từ admin.<br>
                        Người dùng cần tự đăng ký tài khoản.
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>

</html>
