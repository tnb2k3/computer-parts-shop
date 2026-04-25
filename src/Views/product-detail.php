<div class="container">
    <div class="product-detail">
        <div class="product-detail-image">
            <?php if ($product->image): ?>
                <img src="/images/products/<?= htmlspecialchars($product->image) ?>" alt="<?= htmlspecialchars($product->name) ?>">
            <?php else: ?>
                <div class="no-image-large">📦</div>
            <?php endif; ?>
        </div>

        <div class="product-detail-info">
            <h1><?= htmlspecialchars($product->name) ?></h1>
            <p class="category">Danh mục: <?= htmlspecialchars($product->category_name) ?></p>
            <p class="price-large"><?= $product->getFormattedPrice() ?></p>
            
            <div class="stock-status">
                <?php if ($product->isInStock()): ?>
                    <span class="in-stock">✓ Còn hàng (<?= $product->stock ?> sản phẩm)</span>
                <?php else: ?>
                    <span class="out-of-stock">✗ Hết hàng</span>
                <?php endif; ?>
            </div>

            <div class="description">
                <h3>Mô tả sản phẩm</h3>
                <p><?= nl2br(htmlspecialchars($product->description ?? 'Chưa có mô tả')) ?></p>
            </div>

            <?php if ($product->isInStock()): ?>
                <form action="/cart/add" method="POST" class="add-to-cart-form">
                    <input type="hidden" name="product_id" value="<?= $product->id ?>">
                    <div class="quantity-selector">
                        <label for="quantity">Số lượng:</label>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?= $product->stock ?>">
                    </div>
                    <button type="submit" class="btn btn-primary btn-large">🛒 Thêm vào giỏ hàng</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Reviews Section -->
    <section class="reviews-section">
        <h2>⭐ Đánh giá sản phẩm</h2>
        
        <!-- Review Summary & Form -->
        <div class="review-main">
            <!-- Left: Summary -->
            <div class="review-summary-box">
                <?php if ($reviewCount > 0): ?>
                    <div class="rating-big">
                        <span class="rating-number"><?= number_format($averageRating, 1) ?></span>
                        <span class="rating-max">/5</span>
                    </div>
                    <div class="rating-stars-big">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="star <?= $i <= round($averageRating) ? 'filled' : 'empty' ?>">★</span>
                        <?php endfor; ?>
                    </div>
                    <div class="rating-count"><?= $reviewCount ?> đánh giá</div>
                    
                    <div class="rating-bars">
                        <?php for ($star = 5; $star >= 1; $star--): ?>
                            <?php 
                            $count = $ratingCounts[$star] ?? 0;
                            $percentage = $reviewCount > 0 ? ($count / $reviewCount) * 100 : 0;
                            ?>
                            <div class="rating-bar-row">
                                <span class="star-num"><?= $star ?>★</span>
                                <div class="bar-track">
                                    <div class="bar-fill" style="width: <?= $percentage ?>%"></div>
                                </div>
                                <span class="bar-count"><?= $count ?></span>
                            </div>
                        <?php endfor; ?>
                    </div>
                <?php else: ?>
                    <div class="no-reviews-box">
                        <span class="no-review-icon">📝</span>
                        <p>Chưa có đánh giá</p>
                        <small>Hãy là người đầu tiên đánh giá!</small>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Right: Review Form -->
            <div class="review-form-box">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if (!$userHasReviewed): ?>
                        <h3>✍️ Viết đánh giá</h3>
                        
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
                            <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>
                        
                        <form action="/review/create" method="POST" class="review-form-compact">
                            <input type="hidden" name="product_id" value="<?= $product->id ?>">
                            
                            <div class="star-rating-row">
                                <span class="rating-label">Đánh giá:</span>
                                <div class="star-rating-input-compact">
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                        <input type="radio" name="rating" id="star<?= $i ?>" value="<?= $i ?>" <?= $i === 5 ? 'checked' : '' ?>>
                                        <label for="star<?= $i ?>" class="star-btn">★</label>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            
                            <textarea name="comment" rows="3" required placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm..."></textarea>
                            
                            <button type="submit" class="btn btn-primary">📤 Gửi đánh giá</button>
                        </form>
                    <?php else: ?>
                        <div class="already-reviewed">
                            <span>✅</span>
                            <p>Bạn đã đánh giá sản phẩm này</p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="login-prompt">
                        <span>🔐</span>
                        <p><a href="/login">Đăng nhập</a> để viết đánh giá</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Reviews List -->
        <?php if (!empty($reviews)): ?>
            <div class="reviews-list-section">
                <h3>💬 Đánh giá từ khách hàng (<?= count($reviews) ?>)</h3>
                <div class="reviews-grid">
                    <?php foreach ($reviews as $review): ?>
                        <div class="review-card">
                            <div class="review-card-header">
                                <div class="reviewer-avatar"><?= strtoupper(substr($review->user_name ?? $review->username ?? 'U', 0, 1)) ?></div>
                                <div class="reviewer-meta">
                                    <span class="reviewer-name"><?= htmlspecialchars($review->user_name ?? $review->username ?? 'Người dùng') ?></span>
                                    <span class="review-date"><?= $review->getFormattedDate() ?></span>
                                </div>
                                <div class="review-stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span class="star <?= $i <= $review->rating ? 'filled' : 'empty' ?>">★</span>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <p class="review-text"><?= nl2br(htmlspecialchars($review->comment)) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <style>
    .reviews-section {
        margin-top: 3rem;
        padding: 2rem;
        background: #f8f9fa;
        border-radius: 16px;
    }
    .reviews-section h2 {
        margin-bottom: 1.5rem;
        color: #333;
    }
    .review-main {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }
    .review-summary-box {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .rating-big {
        margin-bottom: 0.5rem;
    }
    .rating-number {
        font-size: 3rem;
        font-weight: bold;
        color: #d70018;
    }
    .rating-max {
        font-size: 1.5rem;
        color: #999;
    }
    .rating-stars-big .star {
        font-size: 1.5rem;
    }
    .rating-stars-big .star.filled { color: #ffc107; }
    .rating-stars-big .star.empty { color: #ddd; }
    .rating-count {
        color: #666;
        margin: 0.5rem 0 1rem;
    }
    .rating-bars {
        text-align: left;
    }
    .rating-bar-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.3rem;
        font-size: 0.85rem;
    }
    .star-num {
        width: 30px;
        color: #666;
    }
    .bar-track {
        flex: 1;
        height: 8px;
        background: #e0e0e0;
        border-radius: 4px;
        overflow: hidden;
    }
    .bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #ffc107, #ffdb4d);
        border-radius: 4px;
    }
    .bar-count {
        width: 20px;
        text-align: right;
        color: #999;
        font-size: 0.8rem;
    }
    .no-reviews-box {
        padding: 2rem 1rem;
        color: #999;
    }
    .no-review-icon {
        font-size: 3rem;
        display: block;
        margin-bottom: 0.5rem;
    }
    .review-form-box {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .review-form-box h3 {
        margin-bottom: 1rem;
        color: #333;
    }
    .star-rating-row {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .rating-label {
        color: #666;
    }
    .star-rating-input-compact {
        display: flex;
        flex-direction: row-reverse;
    }
    .star-rating-input-compact input {
        display: none;
    }
    .star-btn {
        font-size: 1.8rem;
        color: #ddd;
        cursor: pointer;
        transition: color 0.2s;
        padding: 0 2px;
    }
    .star-rating-input-compact input:checked ~ .star-btn,
    .star-rating-input-compact .star-btn:hover,
    .star-rating-input-compact .star-btn:hover ~ .star-btn {
        color: #ffc107;
    }
    .review-form-compact textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 8px;
        resize: vertical;
        font-family: inherit;
        margin-bottom: 1rem;
    }
    .review-form-compact textarea:focus {
        outline: none;
        border-color: #d70018;
    }
    .already-reviewed, .login-prompt {
        text-align: center;
        padding: 2rem;
        color: #666;
    }
    .already-reviewed span, .login-prompt span {
        font-size: 2.5rem;
        display: block;
        margin-bottom: 0.5rem;
    }
    .reviews-list-section h3 {
        margin-bottom: 1rem;
        color: #333;
    }
    .reviews-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1rem;
    }
    .review-card {
        background: white;
        padding: 1rem;
        border-radius: 10px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    }
    .review-card-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }
    .reviewer-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #d70018, #ff4757);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1rem;
    }
    .reviewer-meta {
        flex: 1;
    }
    .reviewer-name {
        display: block;
        font-weight: 600;
        color: #333;
    }
    .review-date {
        font-size: 0.8rem;
        color: #999;
    }
    .review-stars .star {
        font-size: 0.9rem;
    }
    .review-stars .star.filled { color: #ffc107; }
    .review-stars .star.empty { color: #ddd; }
    .review-text {
        color: #555;
        line-height: 1.5;
        font-size: 0.95rem;
        margin: 0;
    }
    @media (max-width: 768px) {
        .review-main {
            grid-template-columns: 1fr;
        }
    }
    </style>

    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
        <section class="related-products">
            <h2>Sản phẩm liên quan</h2>
            <div class="products-grid">
                <?php foreach ($relatedProducts as $related): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <?php if ($related->image): ?>
                                <img src="/images/products/<?= htmlspecialchars($related->image) ?>" alt="<?= htmlspecialchars($related->name) ?>">
                            <?php else: ?>
                                <div class="no-image">📦</div>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <h3><?= htmlspecialchars($related->name) ?></h3>
                            <p class="price"><?= $related->getFormattedPrice() ?></p>
                            <a href="/product/<?= $related->id ?>" class="btn btn-secondary">Xem chi tiết</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</div>
