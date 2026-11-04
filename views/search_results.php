<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$controller = new ProductController();
$userQuery = trim($_GET['user_query'] ?? '');
$products = $userQuery !== '' ? $controller->searchProducts($userQuery) : [];
$pageHeading = $userQuery !== '' ? 'Search results for "' . $userQuery . '"' : 'Product Search';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageHeading); ?> | shoppn</title>
    <link rel="stylesheet" href="<?php echo app_url('css/style.css?v=2.3'); ?>">
</head>
<body>

    <?php require_once __DIR__ . '/layout/header.php'; ?>

    <main class="site-main site-container">
        <div class="shop-layout">
            <?php require_once __DIR__ . '/layout/sidebar.php'; ?>

            <section>
                <div class="page-header" style="margin-bottom: 1.5rem;">
                    <h1 class="page-title" style="margin-bottom: 0.25rem;">
                        <?php if ($userQuery !== ''): ?>
                            Results for &ldquo;<?php echo htmlspecialchars($userQuery); ?>&rdquo;
                        <?php else: ?>
                            Search Catalog
                        <?php endif; ?>
                    </h1>
                    <p class="page-description" style="margin: 0;">
                        <?php if ($userQuery !== ''): ?>
                            Found <?php echo count($products); ?> <?php echo count($products) === 1 ? 'product' : 'products'; ?> matching your search.
                        <?php else: ?>
                            Enter a keyword, product name, or category to find products.
                        <?php endif; ?>
                    </p>
                </div>

                <?php if ($userQuery === '' || empty($products)): ?>
                    <div class="card empty-state" style="padding: 4rem 2rem;">
                        <div style="font-size: 2.5rem; margin-bottom: 1rem; color: var(--text-muted);">&#9671;</div>
                        <h3 style="margin-bottom: 0.5rem;">No matching products</h3>
                        <p style="max-width: 420px; margin: 0 auto 1.5rem; color: var(--text-secondary);">
                            We couldn't find any products matching your query. Try checking your spelling or search by general keywords.
                        </p>
                        <a href="<?php echo app_url('index.php'); ?>" class="btn btn-primary btn-sm">
                            Browse All Products
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
