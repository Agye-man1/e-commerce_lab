<?php
require_once __DIR__ . '/../core/core.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(app_url('views/admin/product.php'));
}

require_once __DIR__ . '/../controllers/ProductController.php';

$controller = new ProductController();

$id = (int) ($_POST['product_id'] ?? 0);
$cat = (int) ($_POST['product_cat'] ?? 0);
$brand = (int) ($_POST['product_brand'] ?? 0);
$title = trim(strip_tags($_POST['product_title'] ?? ''));
$price = $_POST['product_price'] ?? '';
$desc = trim(strip_tags($_POST['product_desc'] ?? ''));
$keywords = trim(strip_tags($_POST['product_keywords'] ?? ''));
$existingImage = trim(strip_tags($_POST['existing_image'] ?? ''));

if ($id <= 0 || !$controller->getProductById($id)) {
    $_SESSION['error'] = 'Product not found.';
    redirect(app_url('views/admin/product.php'));
}

if ($title === '' || strlen($title) > 200) {
    $_SESSION['error'] = 'Please enter a product title (max 200 characters).';
    redirect(app_url('views/admin/product.php?edit_id=' . $id));
}

if (!is_numeric($price) || (float) $price < 0) {
    $_SESSION['error'] = 'Please enter a valid price.';
    redirect(app_url('views/admin/product.php?edit_id=' . $id));
}

$price = (float) $price;

if (strlen($desc) > 500) {
    $_SESSION['error'] = 'Description must be 500 characters or fewer.';
    redirect(app_url('views/admin/product.php?edit_id=' . $id));
}

if (strlen($keywords) > 100) {
    $_SESSION['error'] = 'Keywords must be 100 characters or fewer.';
    redirect(app_url('views/admin/product.php?edit_id=' . $id));
}

if (!$controller->getCategoryById($cat) || !$controller->getBrandById($brand)) {
    $_SESSION['error'] = 'Please select a valid category and brand.';
    redirect(app_url('views/admin/product.php?edit_id=' . $id));
}

$filename = $existingImage;
$allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/avif'];
$maxSize = 2 * 1024 * 1024;

$newUpload = isset($_FILES['product_image'])
    && $_FILES['product_image']['error'] !== UPLOAD_ERR_NO_FILE;

if ($newUpload) {
    if ($_FILES['product_image']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = 'Failed to upload the new image.';
        redirect(app_url('views/admin/product.php?edit_id=' . $id));
    }

    if (!in_array($_FILES['product_image']['type'], $allowed, true)) {
        $_SESSION['error'] = 'Image must be JPEG, PNG, GIF, WebP, or AVIF.';
        redirect(app_url('views/admin/product.php?edit_id=' . $id));
    }

    if ($_FILES['product_image']['size'] > $maxSize) {
        $_SESSION['error'] = 'Image must be 2MB or smaller.';
        redirect(app_url('views/admin/product.php?edit_id=' . $id));
    }

    $uploadDir = __DIR__ . '/../images/products/';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
        $_SESSION['error'] = 'Failed to save the product image.';
        redirect(app_url('views/admin/product.php?edit_id=' . $id));
    }

    $filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($_FILES['product_image']['name']));
    $destination = $uploadDir . $filename;

    if (!is_writable($uploadDir) || !move_uploaded_file($_FILES['product_image']['tmp_name'], $destination)) {
        $_SESSION['error'] = 'Failed to save the product image.';
        redirect(app_url('views/admin/product.php?edit_id=' . $id));
    }
}

if ($filename === '') {
    $_SESSION['error'] = 'A product image is required.';
    redirect(app_url('views/admin/product.php?edit_id=' . $id));
}

if ($controller->updateProduct($id, $cat, $brand, $title, $price, $desc, $filename, $keywords)) {
    $_SESSION['success'] = 'Product updated.';
} else {
    $_SESSION['error'] = 'Failed to update product. Please try again.';
}

redirect(app_url('views/admin/product.php'));
