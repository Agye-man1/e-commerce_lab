<?php
require_once __DIR__ . '/../core/core.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(app_url('views/admin/category.php'));
}

require_once __DIR__ . '/../controllers/ProductController.php';

$id = (int) ($_POST['cat_id'] ?? 0);
$name = trim(strip_tags($_POST['cat_name'] ?? ''));

if ($id <= 0 || $name === '' || strlen($name) > 100) {
    $_SESSION['error'] = 'Invalid category data.';
    redirect(app_url('views/admin/category.php'));
}

$controller = new ProductController();

if (!$controller->getCategoryById($id)) {
    $_SESSION['error'] = 'Category not found.';
    redirect(app_url('views/admin/category.php'));
}

if ($controller->updateCategory($id, $name)) {
    $_SESSION['success'] = 'Category updated.';
} else {
    $_SESSION['error'] = 'Failed to update category. Please try again.';
}

redirect(app_url('views/admin/category.php'));
