    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Shop Linh Kiện Máy Tính</h3>
                    <p>Chuyên cung cấp linh kiện máy tính chính hãng, giá tốt nhất thị trường.</p>
                </div>

                <div class="footer-section">
                    <h4>Liên kết nhanh</h4>
                    <ul>
                        <li><a href="/">Trang chủ</a></li>
                        <li><a href="/products">Sản phẩm</a></li>
                        <li class="footer-dropdown-item">
                            <a href="#" class="footer-dropdown-toggle" id="footer-category-toggle">
                                Danh mục <span class="dropdown-arrow">▼</span>
                            </a>
                            <ul class="footer-dropdown-menu" id="footer-category-menu">
                                <?php if (!empty($headerCategories)): ?>
                                    <?php foreach ($headerCategories as $category): ?>
                                        <li>
                                            <a href="/products?category_id=<?= $category->id ?>">
                                                <?= htmlspecialchars($category->name) ?>
                                                <?php if (isset($category->product_count) && $category->product_count > 0): ?>
                                                    <span class="category-count">(<?= $category->product_count ?>)</span>
                                                <?php endif; ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li><a href="#">Chưa có danh mục</a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <li><a href="/cart">Giỏ hàng</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>Hỗ trợ khách hàng</h4>
                    <ul>
                        <li>📞 Hotline: 1900-xxxx</li>
                        <li>📧 Email: support@computershop.com</li>
                        <li>📍 Địa chỉ: TP. Hồ Chí Minh</li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>Kết nối với chúng tôi</h4>
                    <div class="social-links">
                        <a href="https://www.facebook.com/profile.php?id=61582092960574">Facebook</a>
                        <a href="#">YouTube</a>
                        <a href="#">Instagram</a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2024 Computer Shop. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="/js/main.js"></script>
    </body>

    </html>