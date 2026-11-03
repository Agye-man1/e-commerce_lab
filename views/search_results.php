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
    <link rel="stylesheet" href="<?php echo app_url('css/style.css?v=2.2'); ?>">
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
                            Search Sneaker Catalog
                        <?php endif; ?>
                    </h1>
                    <p class="page-description" style="margin: 0;">
                        <?php if ($userQuery !== ''): ?>
                            Found <?php echo count($products); ?> <?php echo count($products) === 1 ? 'sneaker' : 'sneakers'; ?> matching your query.
                        <?php else: ?>
                            Enter a sneaker brand, model, or keyword to search.
                        <?php endif; ?>
                    </p>
                </div>

                <?php if ($userQuery === '' || empty($products)): ?>
                    <div class="card empty-state" style="padding: 4rem 2rem; text-align: center;">
                        <div style="font-size: 2.5rem; margin-bottom: 1rem; color: var(--text-muted);">&#9671;</div>
                        <h3 style="margin-bottom: 0.5rem;">No matching sneakers</h3>
                        <p style="max-width: 420px; margin: 0 auto 1.5rem; color: var(--text-secondary);">
                            We couldn't find any sneakers matching your search query. Try checking your spelling or search by general brands like Nike, Adidas, or Puma.
                        </p>
                        <a href="<?php echo app_url('index.php'); ?>" class="btn btn-primary btn-sm">
                            Browse All Sneakers
                        </a>
                    </div>
                <?php else: ?>
                    <div class="product-grid">
                        <?php foreach ($products as $product): ?>
                            <article class="sneaker-card">
                                <div class="card-top-bar">
                                    <span class="card-badge-status">Just in</span>
                                    <button type="button" class="card-heart-btn" title="Add to wishlist" aria-label="Add to wishlist">&#9825;</button>
                                </div>

                                <div class="sneaker-img-wrap">
                                    <img
                                        src="<?php echo app_url('images/products/' . rawurlencode($product['product_image'] ?? '')); ?>"
                                        alt="<?php echo htmlspecialchars($product['product_title']); ?>"
                                        loading="lazy"
                                    >
                                </div>

                                <div class="sneaker-info">
                                    <h3 class="sneaker-title" title="<?php echo htmlspecialchars($product['product_title']); ?>">
                                        <?php echo htmlspecialchars($product['product_title']); ?>
                                    </h3>

                                    <div class="rating-row">
                                        <span class="rating-star">&#9733;</span>
                                        <strong>4.9</strong>
                                        <span>&bull; Verified Stock</span>
                                    </div>

                                    <div class="sneaker-price">
                                        GH&#8373; <?php echo htmlspecialchars(number_format((float) $product['product_price'], 2)); ?>
                                    </div>

                                    <div class="sneaker-actions">
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
