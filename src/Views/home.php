<div class="container">
    <!-- Top Promotional Banner -->
    <div class="top-banner" style="margin-bottom: 1rem;">
        🎉 Khuyến mãi đặc biệt - Giảm giá lên đến 50% cho các sản phẩm công nghệ! 🎉
    </div>

    <!-- Hero Carousel -->
    <div class="hero-carousel">
        <div class="hero-slide active">
            <img src="/images/banners/tech-sale.png" alt="Tech Sale">
        </div>
        <div class="hero-slide">
            <img src="/images/banners/laptop-gaming.png" alt="Laptop Gaming">
        </div>
        <div class="hero-slide">
            <img src="/images/banners/flash-sale.png" alt="Flash Sale">
        </div>
    </div>

    <!-- Categories Section -->
    <section class="categories-section">
        <div class="section-header">
            <h2>Danh mục sản phẩm</h2>
            <a href="/categories" class="view-all-link">Xem tất cả →</a>
        </div>
        <div class="categories-grid">
            <?php foreach ($categories as $category): ?>
                <a href="/products?category=<?= $category->id ?>" class="category-card">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">
                        <?php
                        // Icon cho từng category
                        $icons = [
                            'CPU' => '🖥️',
                            'GPU' => '🎮',
                            'RAM' => '💾',
                            'Mainboard' => '⚙️',
                            'SSD' => '💿',
                            'Case' => '🗄️',
                            'PSU' => '⚡',
                            'Cooling' => '❄️'
                        ];
                        echo $icons[$category->name] ?? '💿';
                        ?>
                    </div>
                    <h3><?= htmlspecialchars($category->name) ?></h3>
                    <p><?= $category->product_count ?> sản phẩm</p>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Flash Sale Section -->
    <section class="flash-sale">
        <div class="flash-sale-header">
            <h2 class="flash-sale-title">
                ⚡ FLASH SALE
            </h2>
            <div class="countdown" data-endtime="<?= date('Y-m-d H:i:s', strtotime('+6 hours')) ?>">
                <!-- Countdown will be inserted by JavaScript -->
            </div>
        </div>
        <div class="products-grid">
            <?php
            $flashSaleProducts = array_slice($featuredProducts, 0, 4);
            foreach ($flashSaleProducts as $product):
            ?>
                <div class="product-card">
                    <div class="product-image">
                        <!-- Hot/Sale Badge -->
                        <span class="badge badge-hot">HOT</span>

                        <?php if ($product->image): ?>
                            <img src="/images/products/<?= htmlspecialchars($product->image) ?>" alt="<?= htmlspecialchars($product->name) ?>">
                        <?php else: ?>
                            <div class="no-image">📦</div>
                        <?php endif; ?>

                        <?php if ($product->stock <= 0): ?>
                            <span class="out-of-stock">Hết hàng</span>
                        <?php endif; ?>
                    </div>
                    <div class="product-info">
                        <p class="category"><?= htmlspecialchars($product->category_name) ?></p>
                        <h3><?= htmlspecialchars($product->name) ?></h3>

                        <div>
                            <span class="price"><?= $product->getFormattedPrice() ?></span>
                            <span class="discount-percent">-20%</span>
                        </div>

                        <p class="stock">
                            <?php if ($product->stock > 0): ?>
                                ✓ Còn <?= $product->stock ?> sản phẩm
                            <?php else: ?>
                                ✕ Hết hàng
                            <?php endif; ?>
                        </p>

                        <div class="product-actions">
                            <a href="/product/<?= $product->id ?>" class="btn btn-secondary">Xem chi tiết</a>
                            <?php if ($product->stock > 0): ?>
                                <form action="/cart/add" method="POST" style="display:inline; width: 100%;">
                                    <input type="hidden" name="product_id" value="<?= $product->id ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-primary">Thêm vào giỏ</button>
                                </form>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>Hết hàng</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Promotional Banner -->
    <div class="promo-banner">
        🎁 Miễn phí vận chuyển cho đơn hàng trên 500,000đ - Hỗ trợ trả góp 0% 🎁
    </div>

    <!-- Featured Products -->
    <section class="products-section">
        <div class="section-header">
            <h2>Sản phẩm nổi bật</h2>
            <a href="/products" class="view-all-link">Xem tất cả →</a>
        </div>
        <div class="products-grid">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        <!-- Badge for new products -->
                        <?php if ($product->created_at && (time() - strtotime($product->created_at)) < 7 * 24 * 60 * 60): ?>
                            <span class="badge badge-new">MỚI</span>
                        <?php endif; ?>

                        <?php if ($product->image): ?>
                            <img src="/images/products/<?= htmlspecialchars($product->image) ?>" alt="<?= htmlspecialchars($product->name) ?>">
                        <?php else: ?>
                            <div class="no-image">📦</div>
                        <?php endif; ?>

                        <?php if ($product->stock <= 0): ?>
                            <span class="out-of-stock">Hết hàng</span>
                        <?php endif; ?>
                    </div>
                    <div class="product-info">
                        <p class="category"><?= htmlspecialchars($product->category_name) ?></p>
                        <h3><?= htmlspecialchars($product->name) ?></h3>
                        <p class="price"><?= $product->getFormattedPrice() ?></p>
                        <p class="stock">
                            <?php if ($product->stock > 0): ?>
                                <span style="color: #4caf50;">✓</span> Còn hàng
                            <?php else: ?>
                                <span style="color: #999;">✕</span> Hết hàng
                            <?php endif; ?>
                        </p>
                        <div class="product-actions">
                            <a href="/product/<?= $product->id ?>" class="btn btn-secondary">Xem chi tiết</a>
                            <?php if ($product->stock > 0): ?>
                                <form action="/cart/add" method="POST" style="display:inline; width: 100%;">
                                    <input type="hidden" name="product_id" value="<?= $product->id ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-primary">Thêm vào giỏ</button>
                                </form>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>Hết hàng</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="features-section" style="margin: 3rem 0;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
            <div
                style="background: white; padding: 2rem; border-radius: 12px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🚚</div>
                <h3 style="margin-bottom: 0.5rem;">Giao hàng nhanh</h3>
                <p style="color: #666;">Miễn phí vận chuyển đơn > 500k</p>
            </div>
            <div
                style="background: white; padding: 2rem; border-radius: 12px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">✓</div>
                <h3 style="margin-bottom: 0.5rem;">Chính hãng 100%</h3>
                <p style="color: #666;">Sản phẩm chính hãng, bảo hành đầy đủ</p>
            </div>
            <div
                style="background: white; padding: 2rem; border-radius: 12px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">💳</div>
                <h3 style="margin-bottom: 0.5rem;">Trả góp 0%</h3>
                <p style="color: #666;">Hỗ trợ trả góp lãi suất 0%</p>
            </div>
            <div
                style="background: white; padding: 2rem; border-radius: 12px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🎁</div>
                <h3 style="margin-bottom: 0.5rem;">Ưu đãi hấp dẫn</h3>
                <p style="color: #666;">Nhiều chương trình khuyến mãi</p>
            </div>
        </div>
    </section>
</div>

<!-- Load JavaScript for carousel -->
<script src="/js/carousel.js"></script>
<script src="/js/main.js"></script>