<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Quản lý danh mục' ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body class="admin-body">
    <div class="admin-container">
        <aside class="admin-sidebar">
            <h2>Admin Panel</h2>
            <nav class="admin-nav">
                <a href="/admin/dashboard" class="nav-item">📊 Dashboard</a>
                <a href="/admin/categories" class="nav-item active">📁 Danh mục</a>
                <a href="/admin/products" class="nav-item">📦 Sản phẩm</a>
                <a href="/admin/orders" class="nav-item">🛒 Đơn hàng</a>
                <a href="/admin/users" class="nav-item">👥 Tài khoản</a>
                <a href="/admin/reviews" class="nav-item">⭐ Đánh giá</a>
                <hr>
                <a href="/" class="nav-item">🏠 Về trang chủ</a>
                <a href="/logout" class="nav-item">🚪 Đăng xuất</a>
            </nav>
        </aside>

        <main class="admin-main">
            <div class="admin-content">
                <div class="admin-header">
                    <h1>Quản lý danh mục</h1>
                    <a href="/admin/category/form" class="btn btn-primary">+ Thêm danh mục mới</a>
                </div>

                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên danh mục</th>
                            <th>Mô tả</th>
                            <th>Số sản phẩm</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?= $category->id ?></td>
                                <td><?= htmlspecialchars($category->name) ?></td>
                                <td><?= htmlspecialchars($category->description ?? '') ?></td>
                                <td><?= $category->product_count ?></td>
                                <td class="action-buttons">
                                    <a href="/admin/category/form?id=<?= $category->id ?>"
                                        class="btn-small btn-edit">Sửa</a>
                                    <a href="/admin/category/delete/<?= $category->id ?>" class="btn-small btn-delete"
                                        onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
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