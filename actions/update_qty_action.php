<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

$cartPage = app_url('views/cart.php');
$maxQty = 99;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect($cartPage);
}

$productIds = $_POST['update'] ?? [];
$quantities = $_POST['qty'] ?? [];

// update[] and qty[] are parallel arrays: update[i] is the product, qty[i] its new quantity
if (!is_array($productIds) || !is_array($quantities) || count($productIds) !== count($quantities) || count($productIds) === 0) {
    $_SESSION['error'] = 'Invalid cart update.';
    redirect($cartPage);
}

$productIds = array_values($productIds);
$quantities = array_values($quantities);

// Validate everything first, so a bad value doesn't leave the cart half-updated
$updates = [];
foreach ($productIds as $i => $rawId) {
    $productId = filter_var($rawId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $qty = filter_var(trim((string) $quantities[$i]), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => $maxQty]]);

    if ($productId === false || $qty === false) {
        $_SESSION['error'] = 'Each quantity must be a whole number from 1 to ' . $maxQty . '.';
        redirect($cartPage);
    }

    $updates[$productId] = $qty;
}

$cartController = new CartController();
$ip = get_ip();
$failed = 0;

foreach ($updates as $productId => $qty) {
    if (!$cartController->updateQty($productId, $qty, $ip)) {
        $failed++;
    }
}

if ($failed === 0) {
    $_SESSION['success'] = 'Cart updated.';
} else {
    $_SESSION['error'] = 'Some quantities could not be updated. Please try again.';
}

redirect($cartPage);
