<?php
require_once __DIR__ . '/core/core.php';
require_once __DIR__ . '/controllers/ProductController.php';

$controller = new ProductController();

$catId = (int) ($_GET['cat'] ?? 0);
$brandId = (int) ($_GET['brand'] ?? 0);

$pageHeading = 'All Sneaker Catalog';
$products = [];
$deals = [];
$isFiltered = ($catId > 0 || $brandId > 0);

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
    $pageHeading = 'Featured Sneaker Drops';
    $products = $controller->getAllProducts();
    $deals = $controller->getFeaturedProducts(4);
}

$allCategories = $controller->getAllCategories() ?: [];
$allBrands = $controller->getAllBrands() ?: [];

$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);

require_once __DIR__ . '/views/home.php';
