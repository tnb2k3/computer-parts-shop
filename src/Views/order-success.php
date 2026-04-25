<div class="container">
    <div class="success-page">
        <div class="success-icon">✓</div>
        <h1>Đặt hàng thành công!</h1>
        <p>Cảm ơn bạn đã đặt hàng. Chúng tôi sẽ liên hệ với bạn sớm nhất.</p>

        <div class="order-info">
            <h2>Thông tin đơn hàng #<?= $order->id ?></h2>
            <p><strong>Người nhận:</strong> <?= htmlspecialchars($order->customer_name) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($order->customer_email) ?></p>
            <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($order->customer_phone) ?></p>
            <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order->shipping_address) ?></p>
            <p><strong>Tổng tiền:</strong> <?= $order->getFormattedTotal() ?></p>
            <p><strong>Trạng thái:</strong> <?= $order->getStatusLabel() ?></p>
        </div>

        <div class="order-items">
            <h3>Sản phẩm đã đặt</h3>
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order->items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= number_format($item['price'], 0, ',', '.') ?> ₫</td>
                            <td><?= number_format($item['subtotal'], 0, ',', '.') ?> ₫</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="success-actions">
            <a href="/" class="btn btn-primary">Về trang chủ</a>
            <a href="/products" class="btn btn-secondary">Tiếp tục mua sắm</a>
        </div>
    </div>
</div>
