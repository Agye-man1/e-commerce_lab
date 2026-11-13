<?php
require_once __DIR__ . '/core/core.php';
require_once __DIR__ . '/controllers/ProductController.php';

$controller = new ProductController();

$catId = (int) ($_GET['cat'] ?? 0);
$brandId = (int) ($_GET['brand'] ?? 0);

$pageHeading = 'Products';
$products = [];

if ($catId > 0) {
    $category = $controller->getCategoryById($catId);
    if ($category) {
        $pageHeading = $category['cat_name'];
        $products = $controller->getProductsByCategory($catId);
    }
} elseif ($brandId > 0) {
    $brand = $controller->getBrandById($brandId);
    if ($brand) {
        $pageHeading = $brand['brand_name'];
        $products = $controller->getProductsByBrand($brandId);
    }
} else {
    $pageHeading = 'Featured Products';
    $products = $controller->getFeaturedProducts(8);
}

$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);

require_once __DIR__ . '/views/home.php';
