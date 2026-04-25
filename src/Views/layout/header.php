<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Shop Linh Kiện Máy Tính' ?></title>
    <link rel="stylesheet" href="/css/style.css?v=<?= time() ?>">
    <script src="/js/carousel.js" defer></script>
    <script src="/js/main.js" defer></script>
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="/">SakinoStore</a>
                </div>

                <nav class="nav">
                    <a href="/">Trang chủ</a>
                    <a href="/products">Sản phẩm</a>
                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                        <a href="/admin">Admin</a>
                    <?php endif; ?>
                </nav>

                <div class="header-actions">
                    <form action="/products" method="GET" class="search-form">
                        <input type="text" name="search" placeholder="Tìm kiếm sản phẩm..."
                            value="<?= $_GET['search'] ?? '' ?>">
                        <button type="submit">🔍</button>
                    </form>

                    <a href="/cart" class="cart-link">
                        🛒 Giỏ hàng
                        <?php
                        $cartCount = 0;
                        if (isset($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $item) {
                                $cartCount += $item['quantity'];
                            }
                        }
                        if ($cartCount > 0):
                        ?>
                            <span class="cart-count"><?= $cartCount ?></span>
                        <?php endif; ?>
                    </a>

                    <?php if (isset($_SESSION['user'])): ?>
                        <div class="user-menu">
                            <span>👤 <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
                            <div class="dropdown">
                                <a href="/profile">Thông tin</a>
                                <a href="/order/history">Đơn hàng</a>
                                <a href="/logout">Đăng xuất</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="/login" class="btn-login">Đăng nhập</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <main class="main-content">