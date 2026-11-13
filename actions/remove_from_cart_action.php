<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

$cartPage = app_url('views/cart.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect($cartPage);
}

$selected = $_POST['remove'] ?? [];
if (!is_array($selected) || count($selected) === 0) {
    $_SESSION['error'] = 'Tick at least one item to remove.';
    redirect($cartPage);
}

$cartController = new CartController();
$ip = get_ip();
$removed = 0;

foreach ($selected as $value) {
    // Must be a positive whole number
    $productId = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($productId === false) {
        continue;
    }

    if ($cartController->removeFromCart($productId, $ip)) {
        $removed++;
    }
}

if ($removed > 0) {
    $_SESSION['success'] = $removed === 1 ? 'Item removed from your cart.' : $removed . ' items removed from your cart.';
} else {
    $_SESSION['error'] = 'No items were removed. Please try again.';
}

redirect($cartPage);
