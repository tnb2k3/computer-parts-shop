<div class="container">
    <h1>Giỏ hàng của bạn</h1>

    <?php if (empty($cartItems)): ?>
        <div class="empty-cart">
            <p>🛒 Giỏ hàng trống</p>
            <a href="/products" class="btn btn-primary">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <div class="cart-content">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <td>
                                <div class="cart-item">
                                    <?php if ($item->image): ?>
                                        <img src="/images/products/<?= htmlspecialchars($item->image) ?>" alt="<?= htmlspecialchars($item->product_name) ?>" class="cart-item-image">
                                    <?php endif; ?>
                                    <span><?= htmlspecialchars($item->product_name) ?></span>
                                </div>
                            </td>
                            <td><?= $item->getFormattedPrice() ?></td>
                            <td>
                                <form action="/cart/update" method="POST" class="quantity-form">
                                    <input type="hidden" name="product_id" value="<?= $item->product_id ?>">
                                    <input type="number" name="quantity" value="<?= $item->quantity ?>" min="1" onchange="this.form.submit()">
                                </form>
                            </td>
                            <td class="subtotal"><?= $item->getFormattedSubtotal() ?></td>
                            <td>
                                <a href="/cart/remove/<?= $item->product_id ?>" class="btn-remove" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">❌</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Messages -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <div class="cart-summary">
                <div class="cart-actions">
                    <a href="/products" class="btn btn-secondary">Tiếp tục mua sắm</a>
                    <a href="/cart/clear" class="btn btn-secondary" onclick="return confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')">Xóa giỏ hàng</a>
                </div>

                <div class="cart-total-section">
                    <!-- Coupon Section -->
                    <div class="coupon-section">
                        <h3>Mã giảm giá</h3>
                        <?php if ($coupon): ?>
                            <div class="coupon-applied">
                                <div class="coupon-info">
                                    <span class="coupon-code">🎟️ <?= htmlspecialchars($coupon['code']) ?></span>
                                    <?php if ($coupon['description']): ?>
                                        <span class="coupon-description"><?= htmlspecialchars($coupon['description']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <a href="/cart/remove-coupon" class="btn-remove-coupon">Xóa</a>
                            </div>
                        <?php else: ?>
                            <form action="/cart/apply-coupon" method="POST" class="coupon-form">
                                <input type="text" name="coupon_code" placeholder="Nhập mã giảm giá" required>
                                <button type="submit" class="btn btn-primary">Áp dụng</button>
                            </form>
                            
                            <!-- Available Coupons - Compact Buttons -->
                            <?php if (!empty($availableCoupons)): ?>
                                <div class="available-coupons">
                                    <p class="coupons-title">Mã có sẵn:</p>
                                    <div class="coupon-buttons">
                                        <?php foreach ($availableCoupons as $c): ?>
                                            <form action="/cart/apply-coupon" method="POST" class="coupon-btn-form">
                                                <input type="hidden" name="coupon_code" value="<?= htmlspecialchars($c->code) ?>">
                                                <button type="submit" class="coupon-btn" title="<?= htmlspecialchars($c->description ?: 'Áp dụng mã ' . $c->code) ?>">
                                                    <span class="coupon-btn-code"><?= htmlspecialchars($c->code) ?></span>
                                                    <span class="coupon-btn-value"><?php if ($c->type === 'percentage'): ?>-<?= (int)$c->value ?>%<?php else: ?>-<?= number_format($c->value/1000, 0) ?>K<?php endif; ?></span>
                                                </button>
                                            </form>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="price-breakdown">
                        <div class="price-row">
                            <span>Tạm tính:</span>
                            <span><?= number_format($subtotal, 0, ',', '.') ?> ₫</span>
                        </div>
                        
                        <?php if ($discountAmount > 0): ?>
                            <div class="price-row discount-row">
                                <span>Giảm giá:</span>
                                <span class="discount-amount">-<?= number_format($discountAmount, 0, ',', '.') ?> ₫</span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="price-row total-row">
                            <span>Tổng cộng:</span>
                            <span class="total-amount"><?= number_format($total, 0, ',', '.') ?> ₫</span>
                        </div>
                    </div>

                    <a href="/checkout" class="btn btn-primary btn-large btn-block">Tiếp tục thanh toán</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
