<?php
// Variables provided by index.php: $controller, $catId, $brandId, $pageHeading, $products, $error, $success
$isLanding = ($catId ?? 0) === 0 && ($brandId ?? 0) === 0;

if ($isLanding) {
    // Category tiles: use the first product in each category as its picture
    $tiles = [];
    foreach ($controller->getAllCategories() as $tileCat) {
        $catProducts = $controller->getProductsByCategory($tileCat['cat_id']);
        $tiles[] = [
            'cat'   => $tileCat,
            'count' => count($catProducts),
            'image' => $catProducts[0]['product_image'] ?? '',
        ];
    }
    $heroProduct = $products[0] ?? null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isLanding ? 'shoppn | Sneakers, apparel & essentials' : 'shoppn | ' . htmlspecialchars($pageHeading); ?></title>
    <link rel="stylesheet" href="<?php echo app_url('css/style.css?v=4.2'); ?>">
    <link rel="icon" type="image/svg+xml" href="<?php echo app_url('images/brand/favicon.svg'); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo app_url('images/brand/favicon-32.png'); ?>">
    <link rel="apple-touch-icon" href="<?php echo app_url('images/brand/apple-touch-icon.png'); ?>">
</head>
<body>

    <?php require_once __DIR__ . '/layout/header.php'; ?>

    <main class="site-main site-container">

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><span><?php echo htmlspecialchars($error); ?></span></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><span><?php echo htmlspecialchars($success); ?></span></div>
        <?php endif; ?>

        <?php if ($isLanding): ?>

            <!-- Hero -->
            <section class="hero">
                <div class="hero-copy">
                    <span class="hero-eyebrow">New In</span>
                    <h1>Fresh fits<br>for every day</h1>
                    <p>Sneakers, apparel and everyday essentials from the brands you love, at prices that make sense.</p>
                    <div class="hero-cta">
                        <a href="#featured" class="btn btn-accent btn-lg">Shop Now</a>
                        <?php if (!empty($tiles)): ?>
                            <a href="#categories" class="btn btn-ghost btn-lg">Browse Categories</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ($heroProduct): ?>
                    <a class="hero-visual" href="<?php echo app_url('views/single_product.php?pro_id=' . (int) $heroProduct['product_id']); ?>">
                        <img src="<?php echo app_url('images/products/' . rawurlencode($heroProduct['product_image'] ?? '')); ?>"
                             alt="<?php echo htmlspecialchars($heroProduct['product_title']); ?>">
                        <span class="hero-tag">
                            <small><?php echo htmlspecialchars($heroProduct['product_title']); ?></small>
                            GH&#8373; <?php echo number_format((float) $heroProduct['product_price'], 2); ?>
                        </span>
                    </a>
                <?php endif; ?>
            </section>

            <!-- Trust strip -->
            <section class="perks">
                <div class="perk">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7h11v9H3z"/><path d="M14 10h4l3 3v3h-7"/><circle cx="7" cy="18" r="1.8"/><circle cx="17" cy="18" r="1.8"/></svg>
                    <div><strong>Nationwide Delivery</strong><span>To every region in Ghana</span></div>
                </div>
                <div class="perk">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                    <div><strong>Secure Payment</strong><span>Mobile Money &amp; cards</span></div>
                </div>
                <div class="perk">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 3 2.6 5.5 6 .8-4.4 4.2 1.1 6L12 16.6 6.7 19.5l1.1-6L3.4 9.3l6-.8z"/></svg>
                    <div><strong>Authentic Brands</strong><span>Only genuine products</span></div>
                </div>
                <div class="perk">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12a8 8 0 1 0 2.3-5.7"/><path d="M4 4v4h4"/></svg>
                    <div><strong>Guest Checkout</strong><span>Shop without an account</span></div>
                </div>
            </section>

            <!-- Categories -->
            <?php if (!empty($tiles)): ?>
                <section class="section" id="categories">
                    <div class="section-head">
                        <h2>Shop by Category</h2>
                    </div>
                    <div class="cat-grid">
                        <?php foreach ($tiles as $tile): ?>
                            <a class="cat-tile" href="<?php echo app_url('index.php?cat=' . (int) $tile['cat']['cat_id']); ?>">
                                <span class="cat-tile-img">
                                    <?php if ($tile['image'] !== ''): ?>
                                        <img src="<?php echo app_url('images/products/' . rawurlencode($tile['image'])); ?>" alt="" loading="lazy">
                                    <?php else: ?>
                                        <span class="cat-tile-letter"><?php echo htmlspecialchars(strtoupper(substr($tile['cat']['cat_name'], 0, 1))); ?></span>
                                    <?php endif; ?>
                                </span>
                                <span class="cat-tile-text">
                                    <strong><?php echo htmlspecialchars($tile['cat']['cat_name']); ?></strong>
                                    <small><?php echo $tile['count']; ?> <?php echo $tile['count'] === 1 ? 'item' : 'items'; ?></small>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <!-- Featured -->
            <section class="section" id="featured">
                <div class="section-head">
                    <h2>Featured Products</h2>
                    <?php if (is_admin()): ?>
                        <a href="<?php echo app_url('views/admin/product.php'); ?>" class="section-link">+ Add Product</a>
                    <?php endif; ?>
                </div>

                <?php if (empty($products)): ?>
                    <div class="card empty-state">
                        <h3>No products yet</h3>
                        <p>Check back soon &mdash; new arrivals are on the way.</p>
                    </div>
                <?php else: ?>
                    <div class="pgrid">
                        <?php foreach ($products as $product): ?>
                            <?php include __DIR__ . '/layout/product_card.php'; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

        <?php else: ?>

            <nav class="breadcrumb">
                <a href="<?php echo app_url('index.php'); ?>">Home</a>
                <span>/</span>
                <span><?php echo htmlspecialchars($pageHeading); ?></span>
            </nav>

            <div class="shop-layout">
                <?php require_once __DIR__ . '/layout/sidebar.php'; ?>

                <section>
                    <div class="listing-head">
                        <h1><?php echo htmlspecialchars($pageHeading); ?></h1>
                        <span><?php echo count($products); ?> <?php echo count($products) === 1 ? 'product' : 'products'; ?></span>
                    </div>

                    <?php if (empty($products)): ?>
                        <div class="card empty-state">
                            <h3>No products found</h3>
                            <p>There are no products in this collection yet. Try another category or brand.</p>
                            <a href="<?php echo app_url('index.php'); ?>" class="btn btn-primary btn-sm">Back to Home</a>
                        </div>
                    <?php else: ?>
                        <div class="pgrid pgrid-3">
                            <?php foreach ($products as $product): ?>
                                <?php include __DIR__ . '/layout/product_card.php'; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </section>
            </div>

        <?php endif; ?>

    </main>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>

</body>
</html>
