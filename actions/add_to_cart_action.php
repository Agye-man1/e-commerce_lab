<?php
require_once __DIR__ . '/../core/core.php';

$_SESSION['error'] = 'Cart is not set up yet.';
redirect(app_url('index.php'));
