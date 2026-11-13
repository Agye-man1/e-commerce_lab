<?php
require_once __DIR__ . '/../../core/core.php';
require_admin();

require_once __DIR__ . '/../../controllers/ProductController.php';

$controller = new ProductController();
$products = $controller->getAllProducts();
$categories = $controller->getAllCategories();
$brands = $controller->getAllBrands();

$editProduct = null;
$editId = (int) ($_GET['edit_id'] ?? 0);
if ($editId > 0) {
    $editProduct = $controller->getProductById($editId);
}

$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

$formAction = $editProduct
    ? app_url('actions/update_product_action.php')
    : app_url('actions/add_product_action.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products — shoppn</title>
    <link rel="stylesheet" href="<?php echo app_url('css/style.css?v=3.3'); ?>">
</head>
<body>

    <?php require_once __DIR__ . '/../layout/header.php'; ?>

    <main class="site-main site-container">

        <div class="page-header">
            <h1 class="page-title">Product Catalog</h1>
            <p class="page-description">Add and edit products. A brand and category must exist first.</p>
        </div>

        <nav class="admin-subnav">
            <a href="<?php echo app_url('views/admin/brand.php'); ?>" class="admin-tab">
                <span>Brands</span>
                <span class="count"><?php echo count($brands); ?></span>
            </a>
            <a href="<?php echo app_url('views/admin/category.php'); ?>" class="admin-tab">
                <span>Categories</span>
                <span class="count"><?php echo count($categories); ?></span>
            </a>
            <a href="<?php echo app_url('views/admin/product.php'); ?>" class="admin-tab active">
                <span>Products</span>
                <span class="count"><?php echo count($products); ?></span>
            </a>
        </nav>

        <?php if ($error !== ''): ?>
            <div class="alert alert-error">
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <?php if ($success !== ''): ?>
            <div class="alert alert-success">
                <span><?php echo htmlspecialchars($success); ?></span>
            </div>
        <?php endif; ?>

        <?php if (empty($brands) || empty($categories)): ?>
            <div class="alert alert-error">
                <span>Add at least one brand and one category before creating products.</span>
            </div>
        <?php endif; ?>

        <div class="metrics-row">
            <div class="metric-card">
                <div class="metric-label">Total Products</div>
                <div class="metric-value"><?php echo count($products); ?></div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Status</div>
                <div class="metric-value" style="font-size: 1.15rem; color: var(--accent);">
                    <?php echo $editProduct ? 'Editing Product #' . (int) $editProduct['product_id'] : 'Ready to Add'; ?>
                </div>
            </div>
        </div>

        <div class="admin-grid">

            <div class="card sticky-form">
                <div class="card-header">
                    <h2><?php echo $editProduct ? 'Edit Product' : 'Add New Product'; ?></h2>
                    <p class="card-subtitle">
                        <?php echo $editProduct ? 'Update this product and save your changes.' : 'Fill in the fields to add a product.'; ?>
                    </p>
                </div>

                <div id="product-js-error" class="alert alert-error" style="display: none;"></div>

                <form
                    id="product-form"
                    method="POST"
                    action="<?php echo htmlspecialchars($formAction); ?>"
                    enctype="multipart/form-data"
                >
                    <?php if ($editProduct): ?>
                        <input type="hidden" name="product_id" value="<?php echo (int) $editProduct['product_id']; ?>">
                        <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($editProduct['product_image'] ?? ''); ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="product_title">Product Title</label>
                        <input
                            id="product_title"
                            type="text"
                            name="product_title"
                            maxlength="200"
                            required
                            value="<?php echo htmlspecialchars($editProduct['product_title'] ?? ''); ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label for="product_price">Price</label>
                        <input
                            id="product_price"
                            type="number"
                            name="product_price"
                            min="0"
                            step="0.01"
                            required
                            value="<?php echo htmlspecialchars($editProduct['product_price'] ?? ''); ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label for="product_cat">Category</label>
                        <select id="product_cat" name="product_cat" required>
                            <option value="">Select category</option>
                            <?php foreach ($categories as $category): ?>
                                <option
                                    value="<?php echo (int) $category['cat_id']; ?>"
                                    <?php echo (isset($editProduct['product_cat']) && (int) $editProduct['product_cat'] === (int) $category['cat_id']) ? 'selected' : ''; ?>
                                >
                                    <?php echo htmlspecialchars($category['cat_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="product_brand">Brand</label>
                        <select id="product_brand" name="product_brand" required>
                            <option value="">Select brand</option>
                            <?php foreach ($brands as $brand): ?>
                                <option
                                    value="<?php echo (int) $brand['brand_id']; ?>"
                                    <?php echo (isset($editProduct['product_brand']) && (int) $editProduct['product_brand'] === (int) $brand['brand_id']) ? 'selected' : ''; ?>
                                >
                                    <?php echo htmlspecialchars($brand['brand_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="product_desc">Description</label>
                        <textarea
                            id="product_desc"
                            name="product_desc"
                            maxlength="500"
                            rows="4"
                        ><?php echo htmlspecialchars($editProduct['product_desc'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="product_keywords">Keywords</label>
                        <input
                            id="product_keywords"
                            type="text"
                            name="product_keywords"
                            maxlength="100"
                            value="<?php echo htmlspecialchars($editProduct['product_keywords'] ?? ''); ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label for="product_image">Product Image</label>
                        <?php if (!empty($editProduct['product_image'])): ?>
                            <p class="card-subtitle">Current image:</p>
                            <img
                                class="product-preview"
                                src="<?php echo app_url('images/products/' . rawurlencode($editProduct['product_image'])); ?>"
                                alt="Current product image"
                            >
                        <?php endif; ?>
                        <input
                            id="product_image"
                            type="file"
                            name="product_image"
                            accept="image/jpeg,image/png,image/gif,image/webp,image/avif"
                            <?php echo $editProduct ? '' : 'required'; ?>
                        >
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <?php echo $editProduct ? 'Save Changes' : 'Create Product'; ?>
                        </button>

                        <?php if ($editProduct): ?>
                            <a href="<?php echo app_url('views/admin/product.php'); ?>" class="btn btn-outline">
                                Cancel
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <div>
                <div class="table-wrapper">
                    <div class="table-toolbar">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <h3 style="margin: 0;">Existing Products</h3>
                            <span class="badge" style="background: var(--bg-muted); color: var(--text-secondary);">
                                <span id="product-count"><?php echo count($products); ?></span> total
                            </span>
                        </div>

                        <?php if (!empty($products)): ?>
                            <div class="input-search-wrap" style="width: 220px;">
                                <span class="input-search-icon">&#9906;</span>
                                <input 
                                    type="search" 
                                    id="table-filter" 
                                    placeholder="Filter products..." 
                                    autocomplete="off"
                                >
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if (empty($products)): ?>
                        <div class="empty-state">
                            <p>No products yet. Use the form on the left to add your first product.</p>
                        </div>
                    <?php else: ?>
                        <table id="data-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Brand</th>
                                    <th>Price</th>
                                    <th style="width: 100px; text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr class="table-row-item">
                                        <td class="name-cell">
                                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                                <?php if (!empty($product['product_image'])): ?>
                                                    <img
                                                        class="product-thumb"
                                                        src="<?php echo app_url('images/products/' . rawurlencode($product['product_image'])); ?>"
                                                        alt=""
                                                        loading="lazy"
                                                    >
                                                <?php endif; ?>
                                                <span style="font-weight: 600;"><?php echo htmlspecialchars($product['product_title']); ?></span>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($product['cat_name']); ?></td>
                                        <td><?php echo htmlspecialchars($product['brand_name']); ?></td>
                                        <td>GH&#8373; <?php echo htmlspecialchars(number_format((float) $product['product_price'], 2)); ?></td>
                                        <td style="text-align: right;">
                                            <a
                                                href="<?php echo app_url('views/admin/product.php?edit_id=' . (int) $product['product_id']); ?>"
                                                class="btn btn-outline btn-sm"
                                            >
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>

        </div>

    </main>

    <?php require_once __DIR__ . '/../layout/footer.php'; ?>

    <script src="<?php echo app_url('js/validate.js'); ?>"></script>
</body>
</html>
