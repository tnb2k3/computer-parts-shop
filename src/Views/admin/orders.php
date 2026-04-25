<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Quản lý đơn hàng' ?></title>
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
                <a href="/admin/orders" class="nav-item active">🛒 Đơn hàng</a>
                <a href="/admin/users" class="nav-item">👥 Tài khoản</a>
                <a href="/admin/reviews" class="nav-item">⭐ Đánh giá</a>
                <hr>
                <a href="/" class="nav-item">🏠 Về trang chủ</a>
                <a href="/logout" class="nav-item">🚪 Đăng xuất</a>
            </nav>
        </aside>

        <main class="admin-main">
            <div class="admin-content">
                <h1>Quản lý đơn hàng</h1>

                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Thanh toán</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>#<?= $order->id ?></td>
                                <td>
                                    <?= htmlspecialchars($order->customer_name) ?><br>
                                    <small><?= htmlspecialchars($order->customer_phone) ?></small>
                                </td>
                                <td><?= $order->getFormattedTotal() ?></td>
                                <td>
                                    <span class="payment-method-badge <?= $order->payment_method ?>">
                                        <?= $order->payment_method === 'qr_bank' ? '📱 QR' : '💵 COD' ?>
                                    </span>
                                    <br>
                                    <small class="payment-status-<?= $order->payment_status ?>">
                                        <?= $order->getPaymentStatusLabel() ?>
                                    </small>
                                </td>
                                <td>
                                    <?php if ($order->status === 'completed' || $order->status === 'cancelled'): ?>
                                        <!-- Trạng thái cuối - chỉ hiện label -->
                                        <span class="status-label status-<?= $order->status ?>"><?= $order->getStatusLabel() ?></span>
                                    <?php else: ?>
                                        <!-- Dropdown chỉ hiện các trạng thái tiếp theo -->
                                        <form action="/admin/order/status" method="POST" style="display:inline;">
                                            <input type="hidden" name="order_id" value="<?= $order->id ?>">
                                            <select name="status" onchange="this.form.submit()" class="status-select">
                                                <?php if ($order->status === 'pending'): ?>
                                                    <option value="pending" selected>Chờ xử lý</option>
                                                    <option value="processing">Đang xử lý</option>
                                                    <option value="completed">Hoàn thành</option>
                                                    <option value="cancelled">Đã hủy</option>
                                                <?php elseif ($order->status === 'processing'): ?>
                                                    <option value="processing" selected>Đang xử lý</option>
                                                    <option value="completed">Hoàn thành</option>
                                                    <option value="cancelled">Đã hủy</option>
                                                <?php endif; ?>
                                            </select>
                                        </form>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($order->created_at)) ?></td>
                                <td>
                                    <button onclick="toggleOrderDetails(<?= $order->id ?>)" class="btn-small">Chi
                                        tiết</button>
                                </td>
                            </tr>
                            <tr id="order-details-<?= $order->id ?>" class="order-details" style="display:none;">
                                <td colspan="7">
                                    <div class="order-details-content">
                                        <h4>Chi tiết đơn hàng #<?= $order->id ?></h4>
                                        <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order->shipping_address) ?></p>
                                        <p><strong>Email:</strong> <?= htmlspecialchars($order->customer_email) ?></p>
                                        <p><strong>Phương thức TT:</strong> <?= $order->getPaymentMethodLabel() ?></p>
                                        <p><strong>Trạng thái TT:</strong> <?= $order->getPaymentStatusLabel() ?></p>
                                        <?php if ($order->notes): ?>
                                            <p><strong>Ghi chú:</strong> <?= htmlspecialchars($order->notes) ?></p>
                                        <?php endif; ?>
                                        <h5>Sản phẩm:</h5>
                                        <ul>
                                            <?php foreach ($order->items as $item): ?>
                                                <li><?= htmlspecialchars($item['product_name']) ?> × <?= $item['quantity'] ?> =
                                                    <?= number_format($item['subtotal'], 0, ',', '.') ?> ₫</li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        function toggleOrderDetails(orderId) {
            const row = document.getElementById('order-details-' + orderId);
            row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
        }
    </script>
</body>

</html>