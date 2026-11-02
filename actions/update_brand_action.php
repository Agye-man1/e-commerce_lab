<?php
require_once __DIR__ . '/../core/core.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(app_url('views/admin/brand.php'));
}

require_once __DIR__ . '/../controllers/ProductController.php';

$id = (int) ($_POST['brand_id'] ?? 0);
$name = trim(strip_tags($_POST['brand_name'] ?? ''));

if ($id <= 0 || $name === '' || strlen($name) > 100) {
    $_SESSION['error'] = 'Invalid brand data.';
    redirect(app_url('views/admin/brand.php'));
}

$controller = new ProductController();

if (!$controller->getBrandById($id)) {
    $_SESSION['error'] = 'Brand not found.';
    redirect(app_url('views/admin/brand.php'));
}

if ($controller->updateBrand($id, $name)) {
    $_SESSION['success'] = 'Brand updated.';
} else {
    $_SESSION['error'] = 'Failed to update brand. Please try again.';
}

redirect(app_url('views/admin/brand.php'));
