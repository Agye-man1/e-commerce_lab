<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$controller = new ProductController();
$proId = (int) ($_GET['pro_id'] ?? 0);
$product = $proId > 0 ? $controller->getProductById($proId) : null;

if (!$product) {
    redirect(app_url('index.php'));
}

// "You may also like": other products in the same category
$related = array_values(array_filter(
    $controller->getProductsByCategory($product['product_cat']),
    function ($p) use ($proId) { return (int) $p['product_id'] !== $proId; }
));
$related = array_slice($related, 0, 4);

$tags = array_filter(array_map('trim', explode(',', $product['product_keywords'] ?? '')));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['product_title']); ?> | shoppn</title>
    <link rel="stylesheet" href="<?php echo app_url('css/style.css?v=4.0'); ?>">
</head>
<body>

    <?php require_once __DIR__ . '/layout/header.php'; ?>

    <main class="site-main site-container">

        <nav class="breadcrumb">
            <a href="<?php echo app_url('index.php'); ?>">Home</a>
            <span>/</span>
            <a href="<?php echo app_url('index.php?cat=' . (int) $product['product_cat']); ?>"><?php echo htmlspecialchars($product['cat_name']); ?></a>
            <span>/</span>
            <span><?php echo htmlspecialchars($product['product_title']); ?></span>
        </nav>

        <div class="pdp">
            <div class="pdp-media">
                <img src="<?php echo app_url('images/products/' . rawurlencode($product['product_image'] ?? '')); ?>"
                     alt="<?php echo htmlspecialchars($product['product_title']); ?>">
            </div>

            <div class="pdp-info">
                <a class="pdp-brand" href="<?php echo app_url('index.php?brand=' . (int) $product['product_brand']); ?>">
                    <?php echo htmlspecialchars($product['brand_name']); ?>
                </a>
                <h1 class="pdp-title"><?php echo htmlspecialchars($product['product_title']); ?></h1>
                <div class="pdp-price">GH&#8373; <?php echo number_format((float) $product['product_price'], 2); ?></div>

                <form id="add-cart-form" class="pdp-actions" method="GET" action="<?php echo app_url('actions/add_to_cart_action.php'); ?>">
                    <input type="hidden" name="add_cart" value="<?php echo (int) $product['product_id']; ?>">

                    <div class="pdp-qty-row">
                        <label for="pdp-qty">Quantity</label>
                        <div class="qty-stepper">
                            <button type="button" class="qty-step" data-step="-1" aria-label="Decrease quantity">&minus;</button>
                            <input id="pdp-qty" class="qty-input" type="number" name="qty" value="1" min="1" max="99" step="1" inputmode="numeric">
                            <button type="button" class="qty-step" data-step="1" aria-label="Increase quantity">+</button>
                        </div>
                    </div>
                    <div id="add-cart-js-error" class="alert alert-error" style="display: none;"></div>

                    <button type="submit" class="btn btn-accent btn-lg btn-block">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 6h15l-1.5 9h-12z"/><path d="M6 6 5 3H2"/><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/></svg>
                        Add to Cart
                    </button>
                    <a class="btn btn-outline btn-lg btn-block" href="<?php echo app_url('views/cart.php'); ?>">View Cart</a>
                </form>

                <ul class="pdp-perks">
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7h11v9H3z"/><path d="M14 10h4l3 3v3h-7"/><circle cx="7" cy="18" r="1.8"/><circle cx="17" cy="18" r="1.8"/></svg>
                        <span><strong>Delivery across Ghana</strong> &mdash; arranged at checkout</span>
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                        <span><strong>Secure payment</strong> &mdash; Mobile Money or card</span>
                    </li>
                </ul>

                <?php if (!empty($product['product_desc'])): ?>
                    <div class="pdp-section">
                        <h2>Description</h2>
                        <p><?php echo nl2br(htmlspecialchars($product['product_desc'])); ?></p>
                    </div>
                <?php endif; ?>

                <div class="pdp-section">
                    <h2>Details</h2>
                    <dl class="pdp-specs">
                        <dt>Brand</dt><dd><?php echo htmlspecialchars($product['brand_name']); ?></dd>
                        <dt>Category</dt><dd><?php echo htmlspecialchars($product['cat_name']); ?></dd>
                        <?php if (!empty($tags)): ?>
                            <dt>Tags</dt>
                            <dd class="pdp-tags">
                                <?php foreach ($tags as $tag): ?>
                                    <a href="<?php echo app_url('views/search_results.php?user_query=' . urlencode($tag)); ?>"><?php echo htmlspecialchars($tag); ?></a>
                                <?php endforeach; ?>
                            </dd>
                        <?php endif; ?>
                    </dl>
                </div>

                <?php if (is_admin()): ?>
                    <a class="pdp-admin-edit" href="<?php echo app_url('views/admin/product.php?edit_id=' . (int) $product['product_id']); ?>">Edit this product</a>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($related)): ?>
            <section class="section">
                <div class="section-head">
                    <h2>You May Also Like</h2>
                    <a class="section-link" href="<?php echo app_url('index.php?cat=' . (int) $product['product_cat']); ?>">View all <?php echo htmlspecialchars($product['cat_name']); ?></a>
                </div>
                <div class="pgrid">
                    <?php foreach ($related as $product): ?>
                        <?php include __DIR__ . '/layout/product_card.php'; ?>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

    </main>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>

    <script src="<?php echo app_url('js/validate.js'); ?>"></script>
</body>
</html>
