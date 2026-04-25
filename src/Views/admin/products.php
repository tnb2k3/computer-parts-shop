<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Quản lý sản phẩm' ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="admin-body">
    <div class="admin-container">
        <aside class="admin-sidebar">
            <h2>Admin Panel</h2>
            <nav class="admin-nav">
                <a href="/admin/dashboard" class="nav-item">📊 Dashboard</a>
                <a href="/admin/categories" class="nav-item">📁 Danh mục</a>
                <a href="/admin/products" class="nav-item active">📦 Sản phẩm</a>
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
                    <h1>Quản lý sản phẩm</h1>
                    <a href="/admin/product/form" class="btn btn-primary">+ Thêm sản phẩm mới</a>
                </div>

                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Giá</th>
                            <th>Tồn kho</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= $product->id ?></td>
                                <td><?= htmlspecialchars($product->name) ?></td>
                                <td><?= htmlspecialchars($product->category_name) ?></td>
                                <td><?= $product->getFormattedPrice() ?></td>
                                <td><?= $product->stock ?></td>
                                <td class="action-buttons">
                                    <a href="/admin/product/form?id=<?= $product->id ?>" class="btn-small btn-edit">Sửa</a>
                                    <a href="/admin/product/delete/<?= $product->id ?>" class="btn-small btn-delete" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
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
