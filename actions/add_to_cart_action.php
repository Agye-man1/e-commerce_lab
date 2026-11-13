<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';
require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

$back = safe_referer(app_url('index.php'));

$productId = (int) ($_GET['add_cart'] ?? 0);

if ($productId <= 0) {
    $_SESSION['error'] = 'Invalid product.';
    redirect($back);
}

$productController = new ProductController();
if (!$productController->getProductById($productId)) {
    $_SESSION['error'] = 'That product no longer exists.';
    redirect($back);
}

$cartController = new CartController();
$ip = get_ip();

if ($cartController->isInCart($productId, $ip)) {
    $_SESSION['error'] = 'This item is already in your cart. Go to cart to update quantity.';
    redirect($back);
}

$customerId = null;
if (is_logged_in()) {
    $customerController = new CustomerController();
    if ($customerController->getCustomerById($_SESSION['customer_id'])) {
        $customerId = (int) $_SESSION['customer_id'];
    } else {
        // Session points to an account that no longer exists in this database
        // (e.g. deleted, or session from a different DB). Log the user out.
        unset($_SESSION['customer_id'], $_SESSION['customer_name'], $_SESSION['customer_email'], $_SESSION['user_role']);
        $staleSession = true;
    }
}

if ($cartController->addToCart($productId, 1, $ip, $customerId)) {
    $_SESSION['success'] = !empty($staleSession)
        ? 'Item added to your cart. Your login session had expired — please log in again.'
        : 'Item added to your cart.';
} else {
    $_SESSION['error'] = 'Could not add the item to your cart. Please try again.';
}

redirect($back);
