<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../core/paystack_config.php';
require_once __DIR__ . '/../controllers/CartController.php';

require_login();

$failedPage = app_url('views/payment_failed.php');

function payment_failed($message, $failedPage) {
    $_SESSION['error'] = $message;
    redirect($failedPage);
}

// 1. Reference from Paystack's success callback
$reference = trim($_GET['ref'] ?? '');
if (!preg_match('/^[A-Za-z0-9_-]{6,100}$/', $reference)) {
    payment_failed('Invalid payment reference.', $failedPage);
}

// It must be the reference we created on the payment page for this session.
// This stops someone reusing an old reference to get a second order for free.
if (!isset($_SESSION['pay_ref']) || !hash_equals($_SESSION['pay_ref'], $reference)) {
    payment_failed('This payment reference is not valid for your current checkout.', $failedPage);
}

$cart = new CartController();
$ip = get_ip();
$total = $cart->getCartTotal($ip);
$expectedPesewas = (int) round($total * 100);

if ($expectedPesewas <= 0) {
    payment_failed('Your cart is empty, so there is nothing to pay for.', $failedPage);
}

// 2. Verify the transaction with Paystack on the server — never trust the browser callback alone
$ch = curl_init('https://api.paystack.co/transaction/verify/' . rawurlencode($reference));
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . PAYSTACK_SECRET_KEY],
    CURLOPT_TIMEOUT        => 30,
]);
$body = curl_exec($ch);
$curlError = curl_error($ch);
curl_close($ch);

if ($body === false) {
    error_log('Paystack verify failed for ' . $reference . ': ' . $curlError);
    payment_failed('We could not reach Paystack to confirm your payment. If you were charged, contact us with reference ' . $reference . '.', $failedPage);
}

$result = json_decode($body, true);
$data = $result['data'] ?? [];

$verified = !empty($result['status'])
    && ($data['status'] ?? '') === 'success'
    && (int) ($data['amount'] ?? 0) === $expectedPesewas
    && strtoupper($data['currency'] ?? '') === PAYSTACK_CURRENCY;

if (!$verified) {
    error_log('Paystack verify rejected ' . $reference . ': ' . $body);
    payment_failed('Payment was not successful or did not match your cart total. Reference: ' . $reference . '.', $failedPage);
}

// 3. Paystack confirmed it — record the order
$orderId = $cart->processPayment($_SESSION['customer_id'], $ip, $reference, $total, PAYSTACK_CURRENCY);

if (!$orderId) {
    payment_failed('Your payment went through but we could not save your order. Please contact us with reference ' . $reference . '.', $failedPage);
}

unset($_SESSION['pay_ref']);

// Optional confirmation email — the order is already saved, so a mail failure does not undo it
$emailSent = $cart->sendOrderConfirmation($orderId, $_SESSION['customer_email'] ?? '', $_SESSION['customer_name'] ?? '');

$_SESSION['success'] = 'Payment successful. Thank you for your order!'
    . ($emailSent ? ' A confirmation email has been sent to ' . $_SESSION['customer_email'] . '.' : '');

redirect(app_url('views/payment_success.php?order_id=' . (int) $orderId));
