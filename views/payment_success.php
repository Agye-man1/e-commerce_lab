<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

require_login();

$orderId = (int) ($_GET['order_id'] ?? 0);
$result = $orderId > 0 ? (new CartController())->getOrderById($orderId) : null;

// Customers can only see their own invoices (admins can see any)
if (!$result || ((int) $result['order']['customer_id'] !== (int) $_SESSION['customer_id'] && !is_admin())) {
    $_SESSION['error'] = 'Order not found.';
    redirect(app_url('index.php'));
}

$order = $result['order'];
$items = $result['items'];

$itemsTotal = 0.0;
foreach ($items as $item) {
    $itemsTotal += (int) $item['qty'] * (float) $item['product_price'];
}
// The amount actually paid (from the payment table); fall back to the item total
$paid = $order['amt'] !== null ? (float) $order['amt'] : $itemsTotal;
$currency = $order['currency'] ?? 'GHS';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #<?php echo (int) $order['invoice_no']; ?> | shoppn</title>
    <link rel="stylesheet" href="<?php echo app_url('css/style.css?v=4.2'); ?>">
    <link rel="icon" type="image/svg+xml" href="<?php echo app_url('images/brand/favicon.svg'); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo app_url('images/brand/favicon-32.png'); ?>">
    <link rel="apple-touch-icon" href="<?php echo app_url('images/brand/apple-touch-icon.png'); ?>">
</head>
<body>

    <?php require_once __DIR__ . '/layout/header.php'; ?>

    <main class="site-main site-container">

        <section class="invoice card">
            <div class="invoice-head">
                <div class="invoice-check" aria-hidden="true">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5 9-10"/></svg>
                </div>
                <div>
                    <h1>Thank you for your order</h1>
                    <p>Your payment was received and your order is confirmed.</p>
                </div>
            </div>

            <dl class="invoice-meta">
                <div><dt>Invoice No.</dt><dd>#<?php echo (int) $order['invoice_no']; ?></dd></div>
                <div><dt>Order ID</dt><dd><?php echo (int) $order['order_id']; ?></dd></div>
                <div><dt>Date</dt><dd><?php echo htmlspecialchars(date('j M Y', strtotime($order['order_date']))); ?></dd></div>
                <div><dt>Status</dt><dd class="invoice-status"><?php echo htmlspecialchars(ucfirst($order['order_status'])); ?></dd></div>
                <div><dt>Customer</dt><dd><?php echo htmlspecialchars($_SESSION['customer_name'] ?? ''); ?></dd></div>
            </dl>

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

            <div class="invoice-total">
                <span>Total paid (<?php echo htmlspecialchars($currency); ?>)</span>
                <strong>GH&#8373; <?php echo number_format($paid, 2); ?></strong>
            </div>

            <div class="invoice-actions">
                <button type="button" class="btn btn-outline" onclick="window.print()">Print Invoice</button>
                <a href="<?php echo app_url('index.php'); ?>" class="btn btn-accent">Continue Shopping</a>
            </div>
        </section>

    </main>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>

</body>
</html>
