<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

$cart = new CartController();
$ip = get_ip();
$items = $cart->getCartItems($ip);
$total = $cart->getCartTotal($ip);

$unitCount = 0;
foreach ($items as $item) {
    $unitCount += (int) $item['qty'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart | shoppn</title>
    <link rel="stylesheet" href="<?php echo app_url('css/style.css?v=4.2'); ?>">
    <link rel="icon" type="image/svg+xml" href="<?php echo app_url('images/brand/favicon.svg'); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo app_url('images/brand/favicon-32.png'); ?>">
    <link rel="apple-touch-icon" href="<?php echo app_url('images/brand/apple-touch-icon.png'); ?>">
</head>
<body>

    <?php require_once __DIR__ . '/layout/header.php'; ?>

    <main class="site-main site-container">

        <nav class="breadcrumb">
            <a href="<?php echo app_url('index.php'); ?>">Home</a>
            <span>/</span>
            <span>Shopping Cart</span>
        </nav>

        <div class="listing-head">
            <h1>Shopping Cart</h1>
            <span><?php echo count($items); ?> <?php echo count($items) === 1 ? 'product' : 'products'; ?></span>
        </div>

        <?php if (empty($items)): ?>
            <div class="card empty-state">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="color: var(--text-muted); margin-bottom: 1rem;"><path d="M6 6h15l-1.5 9h-12z"/><path d="M6 6 5 3H2"/><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/></svg>
                <h3>Your cart is empty</h3>
                <p>Looks like you haven't added anything yet.</p>
                <a href="<?php echo app_url('index.php'); ?>" class="btn btn-accent">Start Shopping</a>
            </div>
        <?php else: ?>

            <!-- Two separate forms: inputs below attach to them via the form="" attribute -->
            <form id="remove-form" method="POST" action="<?php echo app_url('actions/remove_from_cart_action.php'); ?>"></form>
            <form id="update-form" method="POST" action="<?php echo app_url('actions/update_qty_action.php'); ?>"></form>

            <div id="cart-js-error" class="alert alert-error" style="display: none;"></div>

            <div class="cart-layout">
                <div>
                    <div class="table-wrapper cart-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Remove</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <?php $pid = (int) $item['p_id']; ?>
                                    <tr>
                                        <td data-label="Remove">
                                            <input type="checkbox" name="remove[]" value="<?php echo $pid; ?>" form="remove-form"
                                                   aria-label="Remove <?php echo htmlspecialchars($item['product_title']); ?>">
                                        </td>
                                        <td>
                                            <a class="cart-product" href="<?php echo app_url('views/single_product.php?pro_id=' . $pid); ?>">
                                                <img class="cart-thumb"
                                                     src="<?php echo app_url('images/products/' . rawurlencode($item['product_image'] ?? '')); ?>"
                                                     alt="">
                                                <span><?php echo htmlspecialchars($item['product_title']); ?></span>
                                            </a>
                                        </td>
                                        <td data-label="Quantity">
                                            <input type="hidden" name="update[]" value="<?php echo $pid; ?>" form="update-form">
                                            <div class="qty-stepper" data-autosubmit="update-form">
                                                <button type="button" class="qty-step" data-step="-1" aria-label="Decrease quantity">&minus;</button>
                                                <input class="qty-input" type="number" name="qty[]" min="1" max="99" step="1"
                                                       value="<?php echo (int) $item['qty']; ?>" form="update-form"
                                                       inputmode="numeric" aria-label="Quantity">
                                                <button type="button" class="qty-step" data-step="1" aria-label="Increase quantity">+</button>
                                            </div>
                                        </td>
                                        <td data-label="Unit Price">GH&#8373; <?php echo number_format((float) $item['product_price'], 2); ?></td>
                                        <td data-label="Total" class="cart-line-total">GH&#8373; <?php echo number_format((int) $item['qty'] * (float) $item['product_price'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="cart-actions">
                        <button type="submit" form="remove-form" class="btn btn-outline">Remove from Cart</button>
                        <button type="submit" form="update-form" class="btn btn-outline">Update Quantity</button>
                        <a href="<?php echo app_url('index.php'); ?>" class="btn btn-ghost push-right">&larr; Continue Shopping</a>
                    </div>
                </div>

                <aside class="card order-summary">
                    <h2>Order Summary</h2>
                    <dl>
                        <dt>Items (<?php echo $unitCount; ?>)</dt>
                        <dd>GH&#8373; <?php echo number_format((float) $total, 2); ?></dd>
                        <dt>Delivery</dt>
                        <dd class="muted">Calculated at checkout</dd>
                    </dl>
                    <div class="order-summary-total">
                        <span>Subtotal</span>
                        <strong>GH&#8373; <?php echo number_format((float) $total, 2); ?></strong>
                    </div>
                    <a href="<?php echo app_url('views/checkout.php'); ?>" class="btn btn-accent btn-lg btn-block">Checkout</a>
                    <p class="order-summary-note">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                        Secure checkout &middot; Mobile Money &amp; cards
                    </p>
                </aside>
            </div>

        <?php endif; ?>

    </main>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>

    <script src="<?php echo app_url('js/validate.js'); ?>"></script>
</body>
</html>
