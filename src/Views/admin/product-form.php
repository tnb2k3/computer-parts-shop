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
                <h1><?= $title ?></h1>

                <form action="/admin/product/form<?= isset($product) ? '?id=' . $product->id : '' ?>" method="POST" class="admin-form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Tên sản phẩm *</label>
                        <input type="text" id="name" name="name" required value="<?= htmlspecialchars($product->name ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="category_id">Danh mục *</label>
                        <select id="category_id" name="category_id" required>
                            <option value="">-- Chọn danh mục --</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category->id ?>" <?= (isset($product) && $product->category_id == $category->id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Mô tả</label>
                        <textarea id="description" name="description" rows="4"><?= htmlspecialchars($product->description ?? '') ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="price">Giá (VNĐ) *</label>
                            <input type="number" id="price" name="price" required min="0" step="1000" value="<?= $product->price ?? '' ?>">
                        </div>

                        <div class="form-group">
                            <label for="stock">Tồn kho *</label>
                            <input type="number" id="stock" name="stock" required min="0" value="<?= $product->stock ?? 0 ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="image">Hình ảnh sản phẩm</label>
                        <?php if (!empty($product->image)): ?>
                            <div class="current-image" style="margin-bottom: 1rem;">
                                <p><strong>Ảnh hiện tại:</strong></p>
                                <img src="/images/products/<?= htmlspecialchars($product->image) ?>" 
                                     alt="<?= htmlspecialchars($product->name ?? '') ?>" 
                                     style="max-width: 200px; border-radius: 8px; border: 1px solid #ddd;">
                            </div>
                        <?php endif; ?>
                        <input type="file" id="image" name="image" accept="image/*">
                        <small>Chọn file ảnh (JPG, PNG, GIF). Tối đa 5MB.</small>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Lưu</button>
                        <a href="/admin/products" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
