<?php
// Display only: checkout_action.php has already checked the customer and
// cart and prepared the payment reference in the session.
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../core/paystack_config.php';
require_once __DIR__ . '/../controllers/CartController.php';

require_login();

$cart = new CartController();
$items = $cart->getCartItems(get_ip());
$total = $cart->getCartTotal(get_ip());

// Opened directly, or the cart is now empty: go through checkout again
if (empty($_SESSION['pay_ref']) || empty($_SESSION['customer_email']) || empty($items)) {
    redirect(app_url('views/checkout.php'));
}

$email = $_SESSION['customer_email'];
$payRef = $_SESSION['pay_ref'];
$amountPesewas = (int) round($total * 100);   // Paystack wants pesewas (GH₵ × 100)
$keysReady = strpos(PAYSTACK_PUBLIC_KEY, 'pk_') === 0 && strpos(PAYSTACK_PUBLIC_KEY, 'xxxx') === false;

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
    <title>Checkout | shoppn</title>
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
            <a href="<?php echo app_url('views/cart.php'); ?>">Shopping Cart</a>
            <span>/</span>
            <span>Checkout</span>
        </nav>

        <div class="listing-head">
            <h1>Checkout</h1>
            <span><?php echo $unitCount; ?> <?php echo $unitCount === 1 ? 'item' : 'items'; ?></span>
        </div>

        <?php if (!$keysReady): ?>
            <div class="alert alert-error"><span>Payments are not set up yet: add your Paystack test keys to core/paystack_config.php.</span></div>
        <?php endif; ?>

        <div class="cart-layout">
            <div>
                <div class="table-wrapper cart-table summary-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td>
                                        <span class="cart-product">
                                            <img class="cart-thumb"
                                                 src="<?php echo app_url('images/products/' . rawurlencode($item['product_image'] ?? '')); ?>"
                                                 alt="">
                                            <span><?php echo htmlspecialchars($item['product_title']); ?></span>
                                        </span>
                                    </td>
                                    <td data-label="Quantity"><?php echo (int) $item['qty']; ?></td>
                                    <td data-label="Unit Price">GH&#8373; <?php echo number_format((float) $item['product_price'], 2); ?></td>
                                    <td data-label="Total" class="cart-line-total">GH&#8373; <?php echo number_format((int) $item['qty'] * (float) $item['product_price'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="cart-actions">
                    <a href="<?php echo app_url('views/cart.php'); ?>" class="btn btn-ghost">&larr; Back to Cart</a>
                </div>
            </div>

            <aside class="card order-summary">
                <h2>Payment</h2>
                <dl>
                    <dt>Customer</dt>
                    <dd><?php echo htmlspecialchars($_SESSION['customer_name'] ?? ''); ?></dd>
                    <dt>Email</dt>
                    <dd class="pay-email"><?php echo htmlspecialchars($email); ?></dd>
                    <dt>Items (<?php echo $unitCount; ?>)</dt>
                    <dd>GH&#8373; <?php echo number_format((float) $total, 2); ?></dd>
                    <dt>Delivery</dt>
                    <dd class="muted">Free</dd>
                </dl>
                <div class="order-summary-total">
                    <span>Total</span>
                    <strong>GH&#8373; <?php echo number_format((float) $total, 2); ?></strong>
                </div>

                <button type="button" id="pay-button" class="btn btn-accent btn-lg btn-block" <?php echo $keysReady ? '' : 'disabled'; ?>>
                    Pay GH&#8373; <?php echo number_format((float) $total, 2); ?>
                </button>
                <div id="pay-js-error" class="alert alert-error" style="display: none; margin-top: 0.75rem;"></div>

                <p class="order-summary-note">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                    Secured by Paystack &middot; Mobile Money &amp; cards
                </p>
            </aside>
        </div>

    </main>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>

    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script>
        function payWithPaystack() {
            if (typeof PaystackPop === 'undefined') {
                var box = document.getElementById('pay-js-error');
                box.textContent = 'Could not load Paystack. Check your internet connection and try again.';
                box.style.display = 'block';
                return;
            }

            var handler = PaystackPop.setup({
                key: <?php echo json_encode(PAYSTACK_PUBLIC_KEY); ?>,
                email: <?php echo json_encode($email); ?>,
                amount: <?php echo $amountPesewas; ?>,
                currency: <?php echo json_encode(PAYSTACK_CURRENCY); ?>,
                ref: <?php echo json_encode($payRef); ?>,
                callback: function (response) {
                    // Paystack says it succeeded; the server verifies it before saving the order
                    window.location = <?php echo json_encode(app_url('actions/process_payment_action.php')); ?> +
                        '?ref=' + encodeURIComponent(response.reference);
                },
                onClose: function () {
                    alert('Payment cancelled.');
                    // Paystack may not accept the same reference twice, so start a fresh checkout
                    window.location = <?php echo json_encode(app_url('views/checkout.php')); ?>;
                }
            });
            handler.openIframe();
        }

        document.getElementById('pay-button').addEventListener('click', payWithPaystack);
    </script>
</body>
</html>
