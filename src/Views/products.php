<div class="container">
    <div class="products-page-layout">
        <!-- Sidebar -->
        <aside class="products-sidebar">
            <div class="sidebar-section">
                <h3 class="sidebar-title">Danh Mục</h3>
                <ul class="category-list">
                    <li>
                        <a href="/products" class="category-link <?= empty($currentCategory) ? 'active' : '' ?>">
                            Tất cả sản phẩm
                        </a>
                    </li>
                    <?php foreach ($categories as $category): ?>
                        <li>
                            <a href="/products?category=<?= $category->id ?>" 
                               class="category-link <?= ($currentCategory == $category->id) ? 'active' : '' ?>">
                                <?= htmlspecialchars($category->name) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="sidebar-section">
                <h3 class="sidebar-title">Sắp xếp theo</h3>
                <ul class="sort-list">
                    <?php 
                    $currentSort = $_GET['sort'] ?? '';
                    $baseUrl = '/products' . (!empty($currentCategory) ? '?category=' . $currentCategory : '');
                    $separator = !empty($currentCategory) ? '&' : '?';
                    ?>
                    <li>
                        <a href="<?= $baseUrl ?>" class="sort-link <?= empty($currentSort) ? 'active' : '' ?>">
                            Mặc định
                        </a>
                    </li>
                    <li>
                        <a href="<?= $baseUrl . $separator ?>sort=price_asc" 
                           class="sort-link <?= $currentSort === 'price_asc' ? 'active' : '' ?>">
                            Giá tăng dần
                        </a>
                    </li>
                    <li>
                        <a href="<?= $baseUrl . $separator ?>sort=price_desc" 
                           class="sort-link <?= $currentSort === 'price_desc' ? 'active' : '' ?>">
                            Giá giảm dần
                        </a>
                    </li>
                    <li>
                        <a href="<?= $baseUrl . $separator ?>sort=name_asc" 
                           class="sort-link <?= $currentSort === 'name_asc' ? 'active' : '' ?>">
                            Tên A-Z
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="products-main">
            <!-- Header with search result -->
            <div class="products-header">
                <h1><?= htmlspecialchars($title ?? 'Tất cả sản phẩm') ?></h1>
                <?php if (!empty($_GET['search'])): ?>
                    <div class="search-result-tag">
                        🔍 Kết quả cho: <strong>"<?= htmlspecialchars($_GET['search']) ?>"</strong>
                        <a href="/products" class="remove-search">✕</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="products-count">
                Hiển thị <?= count($products) ?> sản phẩm
            </div>

            <!-- Products Grid -->
            <?php if (empty($products)): ?>
                <div class="empty-products">
                    <div class="empty-icon">📦</div>
                    <h2>Không tìm thấy sản phẩm nào</h2>
                    <p>Vui lòng thử tìm kiếm với từ khóa khác hoặc xem tất cả sản phẩm</p>
                    <a href="/products" class="btn btn-primary">Xem tất cả sản phẩm</a>
                </div>
            <?php else: ?>
                <div class="products-grid-3">
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <?php if ($product->stock > 0 && $product->stock < 5): ?>
                                    <span class="badge badge-sale">SẮP HẾT</span>
                                <?php elseif (isset($product->created_at) && (time() - strtotime($product->created_at)) < 7*24*60*60): ?>
                                    <span class="badge badge-new">MỚI</span>
                                <?php endif; ?>
                                
                                <?php if ($product->image): ?>
                                    <img src="/images/products/<?= htmlspecialchars($product->image) ?>" alt="<?= htmlspecialchars($product->name) ?>">
                                <?php else: ?>
                                    <div class="no-image">📦</div>
                                <?php endif; ?>
                                
                                <?php if ($product->stock <= 0): ?>
                                    <span class="out-of-stock-overlay">Hết hàng</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="product-info">
                                <h3 class="product-name"><?= htmlspecialchars($product->name) ?></h3>
                                <p class="product-price"><?= $product->getFormattedPrice() ?></p>
                                
                                <?php if ($product->stock > 0): ?>
                                    <form action="/cart/add" method="POST">
                                        <input type="hidden" name="product_id" value="<?= $product->id ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn-add-cart">Thêm vào giỏ</button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn-add-cart disabled" disabled>Hết hàng</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>
