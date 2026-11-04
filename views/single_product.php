<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$controller = new ProductController();
$proId = (int) ($_GET['pro_id'] ?? 0);
$product = $proId > 0 ? $controller->getProductById($proId) : null;

if (!$product) {
    redirect(app_url('index.php'));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['product_title']); ?> | shoppn</title>
    <link rel="stylesheet" href="<?php echo app_url('css/style.css?v=2.3'); ?>">
</head>
<body>

    <?php require_once __DIR__ . '/layout/header.php'; ?>

    <main class="site-main site-container">

        <!-- Breadcrumb -->
        <nav style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 2rem;">
            <a href="<?php echo app_url('index.php'); ?>" style="color: var(--text-secondary);">Home</a>
            <span>/</span>
            <a href="<?php echo app_url('index.php?cat=' . (int) $product['product_cat']); ?>" style="color: var(--text-secondary);">
                <?php echo htmlspecialchars($product['cat_name']); ?>
            </a>
            <span>/</span>
            <span style="color: var(--text-primary); font-weight: 500;">
                <?php echo htmlspecialchars($product['product_title']); ?>
            </span>
        </nav>

        <div class="product-detail-layout">
            <!-- Left: Product Image Card -->
            <div class="product-detail-image-box">
                <img
                    class="product-detail-image"
                    src="<?php echo app_url('images/products/' . rawurlencode($product['product_image'] ?? '')); ?>"
                    alt="<?php echo htmlspecialchars($product['product_title']); ?>"
                >
            </div>

            <!-- Right: Product Information & Purchase -->
            <div class="card" style="padding: 2.25rem;">
                <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem; flex-wrap: wrap;">
                    <span class="badge" style="background: var(--bg-muted); color: var(--text-secondary);">
                        <?php echo htmlspecialchars($product['brand_name']); ?>
                    </span>
                    <span class="badge" style="background: var(--accent-subtle); color: var(--accent);">
                        <?php echo htmlspecialchars($product['cat_name']); ?>
                    </span>
                </div>

                <h1 style="font-size: 2rem; margin-bottom: 1rem; letter-spacing: -0.03em;">
                    <?php echo htmlspecialchars($product['product_title']); ?>
                </h1>

                <div style="font-size: 1.75rem; font-weight: 700; color: var(--text-primary); margin-bottom: 1.5rem; letter-spacing: -0.02em;">
                    GH&#8373; <?php echo htmlspecialchars(number_format((float) $product['product_price'], 2)); ?>
                </div>

                <?php if (!empty($product['product_desc'])): ?>
                    <div style="margin-bottom: 2rem; padding-top: 1.25rem; border-top: 1px solid var(--border-subtle);">
                        <h3 style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); margin-bottom: 0.6rem;">
                            Description
                        </h3>
                        <p style="line-height: 1.7; color: var(--text-secondary); margin: 0;">
                            <?php echo nl2br(htmlspecialchars($product['product_desc'])); ?>
                        </p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($product['product_keywords'])): ?>
                    <div style="margin-bottom: 2rem;">
                        <h3 style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); margin-bottom: 0.6rem;">
                            Tags &amp; Keywords
                        </h3>
                        <div style="display: flex; gap: 0.4rem; flex-wrap: wrap;">
                            <?php foreach (explode(',', $product['product_keywords']) as $tag): ?>
                                <?php $cleanTag = trim($tag); if ($cleanTag === '') continue; ?>
                                <span class="code-pill" style="font-size: 0.8rem;"><?php echo htmlspecialchars($cleanTag); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div style="display: flex; gap: 1rem; align-items: center; padding-top: 1.5rem; border-top: 1px solid var(--border-subtle); flex-wrap: wrap;">
                    <a 
                        class="btn btn-primary" 
                        style="padding: 0.8rem 2rem; font-size: 1rem;" 
                        href="<?php echo app_url('actions/add_to_cart_action.php?add_cart=' . (int) $product['product_id']); ?>"
                    >
                        Add to Cart
                    </a>

                    <a 
                        class="btn btn-outline" 
                        href="<?php echo app_url('index.php'); ?>"
                    >
                        &larr; Back to Shop
                    </a>

                    <?php if (is_admin()): ?>
                        <a 
                            class="btn btn-secondary btn-sm" 
                            style="margin-left: auto;" 
                            href="<?php echo app_url('views/admin/product.php?edit_id=' . (int) $product['product_id']); ?>"
                        >
                            Edit Product
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </main>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>

</body>
</html>
