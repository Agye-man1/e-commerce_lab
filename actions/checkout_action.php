<?php
// Start a checkout: check the customer and cart, create a fresh payment
// reference, then show the payment page. No SQL or HTML here.
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

require_login();

// Orders are saved against the customer, so the account must exist in this database
$customer = (new CustomerController())->getCustomerById($_SESSION['customer_id']);
if (!$customer) {
    unset($_SESSION['customer_id'], $_SESSION['customer_name'], $_SESSION['customer_email'], $_SESSION['user_role']);
    $_SESSION['error'] = 'Your session has expired. Please log in again.';
    $_SESSION['after_login'] = app_url('views/checkout.php');
    redirect(app_url('views/login.php'));
}

$cart = new CartController();
if ($cart->getCartCount(get_ip()) === 0) {
    $_SESSION['error'] = 'Your cart is empty.';
    redirect(app_url('views/cart.php'));
}

// Paystack needs the customer's email
$_SESSION['customer_email'] = $customer['customer_email'];

// A fresh reference for this checkout. process_payment_action.php only accepts
// this exact reference, so an old one cannot be reused for a free order.
$_SESSION['pay_ref'] = $cart->createPaymentReference();

redirect(app_url('views/payment.php'));
