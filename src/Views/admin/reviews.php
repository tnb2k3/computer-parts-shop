<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Quản lý đánh giá' ?></title>
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
                <a href="/admin/users" class="nav-item">👥 Tài khoản</a>
                <a href="/admin/reviews" class="nav-item active">⭐ Đánh giá</a>
                <hr>
                <a href="/" class="nav-item">🏠 Về trang chủ</a>
                <a href="/logout" class="nav-item">🚪 Đăng xuất</a>
            </nav>
        </aside>

        <main class="admin-main">
            <div class="admin-content">
                <h1>⭐ Quản lý đánh giá</h1>

                <?php if (empty($reviews)): ?>
                    <div class="empty-state">
                        <p>Chưa có đánh giá nào</p>
                    </div>
                <?php else: ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Sản phẩm</th>
                                <th>Người đánh giá</th>
                                <th>Đánh giá</th>
                                <th>Nội dung</th>
                                <th>Ngày</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reviews as $review): ?>
                                <tr>
                                    <td>#<?= $review->id ?></td>
                                    <td>
                                        <a href="/product/<?= $review->product_id ?>" target="_blank">
                                            <?= htmlspecialchars($review->product_name ?? 'N/A') ?>
                                        </a>
                                    </td>
                                    <td><?= htmlspecialchars($review->user_name ?? $review->username ?? 'N/A') ?></td>
                                    <td>
                                        <span class="review-stars">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <span class="star <?= $i <= $review->rating ? 'filled' : 'empty' ?>">★</span>
                                            <?php endfor; ?>
                                        </span>
                                    </td>
                                    <td class="review-comment">
                                        <?= htmlspecialchars(mb_substr($review->comment, 0, 100)) ?><?= mb_strlen($review->comment) > 100 ? '...' : '' ?>
                                    </td>
                                    <td><?= $review->getFormattedDate() ?></td>
                                    <td>
                                        <a href="/admin/review/delete/<?= $review->id ?>" 
                                           class="btn-delete" 
                                           onclick="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                                            🗑️ Xóa
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <style>
    .review-stars .star {
        font-size: 1rem;
    }
    .review-stars .star.filled {
        color: #ffc107;
    }
    .review-stars .star.empty {
        color: #ddd;
    }
    .review-comment {
        max-width: 300px;
        font-size: 0.9rem;
        color: #555;
    }
    .btn-delete {
        color: #dc3545;
        text-decoration: none;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.85rem;
    }
    .btn-delete:hover {
        background: #fee;
    }
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #999;
    }
    </style>
</body>
</html>
