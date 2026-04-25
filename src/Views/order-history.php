<div class="container">
    <h1>Lịch sử đơn hàng</h1>

    <?php if (empty($orders)): ?>
        <div class="empty-cart">
            <p>📦 Bạn chưa có đơn hàng nào</p>
            <a href="/products" class="btn btn-primary">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <div class="orders-list">
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-info">
                            <h3>Đơn hàng #<?= $order->id ?></h3>
                            <p class="order-date">
                                Ngày đặt: <?= date('d/m/Y H:i', strtotime($order->created_at)) ?>
                            </p>
                        </div>
                        <div class="order-status">
                            <?php
                            $statusLabels = [
                                'pending' => '⏳ Chờ xử lý',
                                'processing' => '📦 Đang xử lý',
                                'completed' => '✅ Hoàn thành',
                                'cancelled' => '❌ Đã hủy'
                            ];
                            $statusClass = 'status-' . $order->status;
                            ?>
                            <span class="order-status-badge <?= $statusClass ?>">
                                <?= $statusLabels[$order->status] ?? $order->status ?>
                            </span>
                        </div>
                    </div>

                    <!-- Chi tiết sản phẩm -->
                    <?php if (!empty($order->items)): ?>
                        <div class="order-items">
                            <h4>📦 Sản phẩm đã đặt</h4>
                            <table class="order-items-table">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Đơn giá</th>
                                        <th>SL</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($order->items as $item): ?>
                                        <tr>
                                            <td class="product-name"><?= htmlspecialchars($item['product_name']) ?></td>
                                            <td><?= number_format($item['price'], 0, ',', '.') ?>₫</td>
                                            <td class="quantity"><?= $item['quantity'] ?></td>
                                            <td class="subtotal"><?= number_format($item['subtotal'], 0, ',', '.') ?>₫</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                    <div class="order-details">
                        <div class="order-customer">
                            <p><strong>Người nhận:</strong> <?= htmlspecialchars($order->customer_name) ?></p>
                            <p><strong>Điện thoại:</strong> <?= htmlspecialchars($order->customer_phone) ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($order->customer_email) ?></p>
                            <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order->shipping_address) ?></p>
                        </div>

                        <?php if ($order->notes): ?>
                            <div class="order-notes">
                                <strong>Ghi chú:</strong> <?= htmlspecialchars($order->notes) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="order-total">
                        <strong>Tổng tiền:</strong>
                        <span class="price-large"><?= number_format($order->total, 0, ',', '.') ?> ₫</span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.orders-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.order-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #eee;
}

.order-info h3 {
    margin-bottom: 0.5rem;
    color: #d70018;
}

.order-date {
    color: #666;
    font-size: 0.875rem;
}

.order-status-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-processing {
    background: #cce5ff;
    color: #004085;
}

.status-completed {
    background: #d4edda;
    color: #155724;
}

.status-cancelled {
    background: #f8d7da;
    color: #721c24;
}

/* Order Items Table */
.order-items {
    margin: 1rem 0;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.order-items h4 {
    margin-bottom: 1rem;
    color: #333;
    font-size: 1rem;
}

.order-items-table {
    width: 100%;
    border-collapse: collapse;
}

.order-items-table th,
.order-items-table td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid #e0e0e0;
}

.order-items-table th {
    background: #eee;
    font-weight: 600;
    font-size: 0.875rem;
    color: #555;
}

.order-items-table td {
    font-size: 0.9rem;
}

.order-items-table .product-name {
    max-width: 250px;
}

.order-items-table .quantity {
    text-align: center;
    font-weight: 600;
}

.order-items-table .subtotal {
    font-weight: 600;
    color: #d70018;
}

.order-details {
    margin: 1rem 0;
}

.order-customer p {
    margin-bottom: 0.5rem;
    color: #333;
}

.order-notes {
    margin-top: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 4px;
    color: #666;
}

.order-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 2px solid #eee;
    font-size: 1.1rem;
}

.order-total .price-large {
    color: #d70018;
    font-size: 1.5rem;
    font-weight: bold;
}

@media (max-width: 600px) {
    .order-items-table th:nth-child(2),
    .order-items-table td:nth-child(2) {
        display: none;
    }
}
</style>

