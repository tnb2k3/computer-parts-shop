<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Quản lý tài khoản' ?></title>
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
                    <h1>Quản lý tài khoản</h1>
                </div>

                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Họ tên</th>
                            <th>Role</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user->id ?></td>
                                <td><?= htmlspecialchars($user->username) ?></td>
                                <td><?= htmlspecialchars($user->email) ?></td>
                                <td><?= htmlspecialchars($user->full_name ?? '') ?></td>
                                <td>
                                    <span class="status-label <?= $user->role === 'admin' ? 'status-admin' : 'status-customer' ?>">
                                        <?= $user->role === 'admin' ? '👑 Admin' : '👤 Customer' ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($user->created_at)) ?></td>
                                <td class="action-buttons">
                                    <a href="/admin/user/form?id=<?= $user->id ?>" class="btn-small btn-edit">Sửa</a>
                                    <?php 
                                    $currentUser = $_SESSION['user'] ?? null;
                                    if (!$currentUser || (int)$currentUser['id'] !== (int)$user->id): 
                                    ?>
                                        <a href="/admin/user/delete/<?= $user->id ?>" class="btn-small btn-delete"
                                            onclick="return confirm('Bạn có chắc muốn xóa tài khoản này?')">Xóa</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>

</html>
