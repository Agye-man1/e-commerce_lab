<?php
// Checkout entry point (the Cart's "Checkout" button links here).
// The checkout action does the checks and prepares the payment.
require_once __DIR__ . '/../core/core.php';

redirect(app_url('actions/checkout_action.php'));
