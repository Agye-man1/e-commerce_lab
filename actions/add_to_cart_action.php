<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';
require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

$back = safe_referer(app_url('index.php'));

$productId = (int) ($_GET['add_cart'] ?? 0);

// Optional quantity from the product page picker; listing links send none (defaults to 1)
$qty = 1;
if (isset($_GET['qty'])) {
    $qty = filter_var($_GET['qty'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 99]]);
    if ($qty === false) {
        $_SESSION['error'] = 'Quantity must be a whole number from 1 to 99.';
        redirect($back);
    }
}

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

if ($cartController->addToCart($productId, $qty, $ip, $customerId)) {
    $addedMsg = $qty === 1 ? 'Item added to your cart.' : $qty . ' items added to your cart.';
    // Shown inside the slide-out cart drawer, which opens on the page we return to
    $_SESSION['cart_drawer'] = $addedMsg;
    if (!empty($staleSession)) {
        $_SESSION['success'] = 'Your login session had expired — please log in again.';
    }
} else {
    $_SESSION['error'] = 'Could not add the item to your cart. Please try again.';
}

redirect($back);
