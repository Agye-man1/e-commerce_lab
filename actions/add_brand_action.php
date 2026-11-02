<?php
require_once __DIR__ . '/../core/core.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(app_url('views/admin/brand.php'));
}

require_once __DIR__ . '/../controllers/ProductController.php';

$name = trim(strip_tags($_POST['brand_name'] ?? ''));

if ($name === '' || strlen($name) > 100) {
    $_SESSION['error'] = 'Please enter a valid brand name (max 100 characters).';
    redirect(app_url('views/admin/brand.php'));
}

$controller = new ProductController();

if ($controller->addBrand($name)) {
    $_SESSION['success'] = 'Brand added.';
} else {
    $_SESSION['error'] = 'Failed to add brand. Please try again.';
}

redirect(app_url('views/admin/brand.php'));
