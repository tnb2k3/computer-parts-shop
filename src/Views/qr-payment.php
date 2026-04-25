<div class="container">
    <div class="qr-payment-page">
        <div class="qr-payment-header">
            <h1>📱 Thanh toán QR Banking</h1>
            <p>Quét mã QR bên dưới để thanh toán đơn hàng #<?= $order->id ?></p>
        </div>

        <div class="qr-payment-content">
            <div class="qr-code-section">
                <div class="qr-code-container">
                    <img src="<?= htmlspecialchars($qrUrl) ?>" alt="QR Code thanh toán" class="qr-code-image">
                </div>
                
                <div class="bank-info">
                    <h3>Thông tin chuyển khoản</h3>
                    <div class="info-row">
                        <span class="label">Ngân hàng:</span>
                        <span class="value"><?= htmlspecialchars($bankId) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Số tài khoản:</span>
                        <span class="value"><?= htmlspecialchars($accountNo) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Chủ tài khoản:</span>
                        <span class="value"><?= htmlspecialchars($accountName) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Số tiền:</span>
                        <span class="value amount"><?= $order->getFormattedTotal() ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Nội dung CK:</span>
                        <span class="value content"><?= htmlspecialchars($description) ?></span>
                    </div>
                </div>
            </div>

            <div class="payment-instructions">
                <h3>📋 Hướng dẫn thanh toán</h3>
                <ol>
                    <li>Mở ứng dụng ngân hàng hoặc ví điện tử hỗ trợ VietQR</li>
                    <li>Chọn <strong>Quét mã QR</strong> hoặc <strong>Chuyển tiền</strong></li>
                    <li>Quét mã QR hiển thị trên màn hình</li>
                    <li>Kiểm tra thông tin và xác nhận thanh toán</li>
                    <li>Sau khi thanh toán thành công, nhấn nút "Tôi đã thanh toán" bên dưới</li>
                </ol>
                
                <div class="supported-apps">
                    <p><strong>Ứng dụng hỗ trợ:</strong> BIDV SmartBanking, MoMo, ZaloPay, VNPay, và hầu hết các ngân hàng Việt Nam</p>
                </div>
            </div>
        </div>

        <div class="qr-payment-actions">
            <form action="/order/confirm-payment/<?= $order->id ?>" method="POST" style="display: inline;">
                <button type="submit" class="btn btn-primary btn-large">✓ Tôi đã thanh toán</button>
            </form>
            <a href="/" class="btn btn-secondary">Về trang chủ</a>
        </div>

        <div class="payment-note">
            <p>⚠️ <strong>Lưu ý:</strong> Vui lòng nhập đúng nội dung chuyển khoản "<strong><?= htmlspecialchars($description) ?></strong>" để đơn hàng được xử lý nhanh chóng.</p>
        </div>
    </div>
</div>
