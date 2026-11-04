<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shoppn | <?php echo htmlspecialchars($pageHeading); ?></title>
    <link rel="stylesheet" href="<?php echo app_url('css/style.css?v=2.3'); ?>">
</head>
<body>

    <?php require_once __DIR__ . '/layout/header.php'; ?>

    <main class="site-main site-container">

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <span><?php echo htmlspecialchars($success); ?></span>
            </div>
        <?php endif; ?>

        <div class="shop-layout">
            <?php require_once __DIR__ . '/layout/sidebar.php'; ?>

            <section>
                <div class="page-header" style="margin-bottom: 1.5rem;">
                    <div style="display: flex; align-items: baseline; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                        <div>
                            <h1 class="page-title" style="margin-bottom: 0.25rem;"><?php echo htmlspecialchars($pageHeading); ?></h1>
                            <p class="page-description" style="margin: 0;">
                                <?php if (!empty($products)): ?>
                                    Showing <?php echo count($products); ?> <?php echo count($products) === 1 ? 'item' : 'items'; ?> in this collection.
                                <?php else: ?>
                                    Explore curated products from our verified brands.
                                <?php endif; ?>
                            </p>
                        </div>

                        <?php if (is_admin()): ?>
                            <a href="<?php echo app_url('views/admin/product.php'); ?>" class="btn btn-outline btn-sm">
                                + Add Product
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (empty($products)): ?>
                    <div class="card empty-state" style="padding: 4rem 2rem;">
                        <div style="font-size: 2.5rem; margin-bottom: 1rem; color: var(--text-muted);">&#9671;</div>
                        <h3 style="margin-bottom: 0.5rem;">No products found</h3>
                        <p style="max-width: 420px; margin: 0 auto 1.5rem; color: var(--text-secondary);">
                            There are currently no products listed in this collection. Select another category or return to the main catalog.
                        </p>
                        <a href="<?php echo app_url('index.php'); ?>" class="btn btn-primary btn-sm">
                            View All Catalog
                        </a>
                    </div>
                <?php else: ?>
                    <div class="product-grid">
                        <?php foreach ($products as $product): ?>
                            <article class="card product-card">
                                <div class="product-image-wrap">
                                    <img
                                        class="product-card-image"
                                        src="<?php echo app_url('images/products/' . rawurlencode($product['product_image'] ?? '')); ?>"
                                        alt="<?php echo htmlspecialchars($product['product_title']); ?>"
                                        loading="lazy"
                                    >
                                </div>

                                <div class="product-info">
                                    <h3 class="product-title" title="<?php echo htmlspecialchars($product['product_title']); ?>">
                                        <?php echo htmlspecialchars($product['product_title']); ?>
                                    </h3>

                                    <div class="product-price">
                                        GH&#8373; <?php echo htmlspecialchars(number_format((float) $product['product_price'], 2)); ?>
                                    </div>

                                    <div class="product-actions">
                                        <a 
                                            class="btn btn-outline btn-sm" 
                                            href="<?php echo app_url('views/single_product.php?pro_id=' . (int) $product['product_id']); ?>"
                                        >
                                            Details
                                        </a>
                                        <a 
                                            class="btn btn-primary btn-sm" 
                                            href="<?php echo app_url('actions/add_to_cart_action.php?add_cart=' . (int) $product['product_id']); ?>"
                                        >
                                            Add to Cart
                                        </a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        </div>

    </main>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>

</body>
</html>
