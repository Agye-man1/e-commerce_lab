<?php
require_once __DIR__ . '/../core/core.php';

// Message set by process_payment_action.php (read it before the header clears flash messages)
$message = $_SESSION['error'] ?? 'Your payment could not be completed.';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed | shoppn</title>
    <link rel="stylesheet" href="<?php echo app_url('css/style.css?v=4.0'); ?>">
</head>
<body>

    <?php require_once __DIR__ . '/layout/header.php'; ?>

    <main class="site-main site-container">

        <section class="invoice card invoice-failed">
            <div class="invoice-head">
                <div class="invoice-check" aria-hidden="true">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg>
                </div>
                <div>
                    <h1>Payment failed</h1>
                    <p><?php echo htmlspecialchars($message); ?></p>
                </div>
            </div>

            <p class="invoice-note">Your cart has been kept, so you can try again. No order was created.</p>

            <div class="invoice-actions">
                <a href="<?php echo app_url('views/cart.php'); ?>" class="btn btn-outline">Back to Cart</a>
                <a href="<?php echo app_url('views/checkout.php'); ?>" class="btn btn-accent">Try Again</a>
            </div>
        </section>

    </main>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>

</body>
</html>
