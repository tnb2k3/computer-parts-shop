<div class="container">
    <h1><?= htmlspecialchars($title ?? 'Danh mục sản phẩm') ?></h1>

    <div class="categories-grid">
        <?php if (empty($categories)): ?>
            <p>Không có danh mục nào.</p>
        <?php else: ?>
            <?php foreach ($categories as $category): ?>
                <a href="/products?category=<?= $category->id ?>" class="category-card">
                    <h3><?= htmlspecialchars($category->name) ?></h3>
                    <?php if ($category->description): ?>
                        <p><?= htmlspecialchars($category->description) ?></p>
                    <?php endif; ?>
                    <p class="category-count">
                        <?= $category->product_count ?? 0 ?> sản phẩm
                    </p>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
