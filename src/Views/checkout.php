<div class="container">
    <h1>Thanh toán</h1>

    <div class="checkout-content">
        <div class="checkout-form">
            <h2>Thông tin người nhận</h2>
            <form action="/order/place" method="POST">
                <div class="form-group">
                    <label for="customer_name">Họ và tên *</label>
                    <input type="text" id="customer_name" name="customer_name" required 
                           value="<?= htmlspecialchars($user['full_name'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="customer_email">Email *</label>
                    <input type="email" id="customer_email" name="customer_email" required
                           value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="customer_phone">Số điện thoại *</label>
                    <input type="tel" id="customer_phone" name="customer_phone" required
                           value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="shipping_address">Địa chỉ giao hàng *</label>
                    <textarea id="shipping_address" name="shipping_address" rows="3" required><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="notes">Ghi chú</label>
                    <textarea id="notes" name="notes" rows="3"></textarea>
                </div>

                <div class="form-group payment-method-group">
                    <label>Phương thức thanh toán *</label>
                    <div class="payment-options">
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="cod" checked>
                            <span class="payment-option-content">
                                <span class="payment-icon">💵</span>
                                <span class="payment-info">
                                    <strong>Thanh toán khi nhận hàng (COD)</strong>
                                    <small>Thanh toán tiền mặt khi nhận được hàng</small>
                                </span>
                            </span>
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="qr_bank">
                            <span class="payment-option-content">
                                <span class="payment-icon">📱</span>
                                <span class="payment-info">
                                    <strong>Chuyển khoản QR Banking</strong>
                                    <small>Quét mã QR để thanh toán qua ngân hàng</small>
                                </span>
                            </span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-large">Đặt hàng</button>
            </form>
        </div>

        <div class="checkout-summary">
            <h2>Đơn hàng của bạn</h2>
            <div class="order-items">
                <?php foreach ($cartItems as $item): ?>
                    <div class="order-item">
                        <span><?= htmlspecialchars($item->product_name) ?> × <?= $item->quantity ?></span>
                        <span><?= $item->getFormattedSubtotal() ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="order-total">
                <strong>Tổng cộng:</strong>
                <strong><?= number_format($total, 0, ',', '.') ?> ₫</strong>
            </div>
        </div>
    </div>
</div>
