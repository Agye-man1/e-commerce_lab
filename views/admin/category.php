<?php
require_once __DIR__ . '/../../core/core.php';
require_admin();

require_once __DIR__ . '/../../controllers/ProductController.php';

$controller = new ProductController();
$categories = $controller->getAllCategories();
$brands = $controller->getAllBrands();
$products = $controller->getAllProducts();

$editCategory = null;
$editId = (int) ($_GET['edit_id'] ?? 0);
if ($editId > 0) {
    $editCategory = $controller->getCategoryById($editId);
}

$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

$formAction = $editCategory
    ? app_url('actions/update_category_action.php')
    : app_url('actions/add_category_action.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories — shoppn</title>
    <link rel="stylesheet" href="<?php echo app_url('css/style.css?v=4.2'); ?>">
    <link rel="icon" type="image/svg+xml" href="<?php echo app_url('images/brand/favicon.svg'); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo app_url('images/brand/favicon-32.png'); ?>">
    <link rel="apple-touch-icon" href="<?php echo app_url('images/brand/apple-touch-icon.png'); ?>">
</head>
<body>

    <?php require_once __DIR__ . '/../layout/header.php'; ?>

    <main class="site-main site-container">

        <div class="page-header">
            <h1 class="page-title">Category Architecture</h1>
            <p class="page-description">Organize categories for clear store structure and navigation.</p>
        </div>

        <!-- Admin Sub-Navigation Tabs -->
        <nav class="admin-subnav">
            <a href="<?php echo app_url('views/admin/brand.php'); ?>" class="admin-tab">
                <span>Brands</span>
                <span class="count"><?php echo count($brands); ?></span>
            </a>
            <a href="<?php echo app_url('views/admin/category.php'); ?>" class="admin-tab active">
                <span>Categories</span>
                <span class="count"><?php echo count($categories); ?></span>
            </a>
            <a href="<?php echo app_url('views/admin/product.php'); ?>" class="admin-tab">
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

        <!-- Quick Metrics -->
        <div class="metrics-row">
            <div class="metric-card">
                <div class="metric-label">Total Categories</div>
                <div class="metric-value"><?php echo count($categories); ?></div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Status</div>
                <div class="metric-value" style="font-size: 1.15rem; color: var(--accent);">
                    <?php echo $editCategory ? 'Editing Category #' . (int) $editCategory['cat_id'] : 'Ready to Add'; ?>
                </div>
            </div>
        </div>

        <div class="admin-grid">

            <!-- Category Form -->
            <div class="card sticky-form">
                <div class="card-header">
                    <h2><?php echo $editCategory ? 'Edit Category' : 'Add New Category'; ?></h2>
                    <p class="card-subtitle">
                        <?php echo $editCategory ? 'Modify category naming across the store.' : 'Create a new category in the directory.'; ?>
                    </p>
                </div>

                <div id="category-js-error" class="alert alert-error" style="display: none;"></div>

                <form id="category-form" method="POST" action="<?php echo htmlspecialchars($formAction); ?>">
                    <?php if ($editCategory): ?>
                        <input type="hidden" name="cat_id" value="<?php echo (int) $editCategory['cat_id']; ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="cat_name">Category Name</label>
                        <input
                            id="cat_name"
                            type="text"
                            name="cat_name"
                            maxlength="100"
                            placeholder="e.g. Electronics, Footwear, Audio"
                            required
                            autofocus
                            value="<?php echo htmlspecialchars($editCategory['cat_name'] ?? ''); ?>"
                        >
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <?php echo $editCategory ? 'Save Changes' : 'Create Category'; ?>
                        </button>

                        <?php if ($editCategory): ?>
                            <a href="<?php echo app_url('views/admin/category.php'); ?>" class="btn btn-outline">
                                Cancel
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Categories Table -->
            <div>
                <div class="table-wrapper">
                    <div class="table-toolbar">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <h3 style="margin: 0;">Existing Categories</h3>
                            <span class="badge" style="background: var(--bg-muted); color: var(--text-secondary);">
                                <span id="category-count"><?php echo count($categories); ?></span> total
                            </span>
                        </div>

                        <?php if (!empty($categories)): ?>
                            <div class="input-search-wrap" style="width: 220px;">
                                <span class="input-search-icon">&#9906;</span>
                                <input 
                                    type="search" 
                                    id="table-filter" 
                                    placeholder="Filter categories..." 
                                    autocomplete="off"
                                >
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if (empty($categories)): ?>
                        <div class="empty-state">
                            <p>No categories created yet. Use the form on the left to add your first category.</p>
                        </div>
                    <?php else: ?>
                        <table id="data-table">
                            <thead>
                                <tr>
                                    <th style="width: 90px;">Identifier</th>
                                    <th>Category Name</th>
                                    <th style="width: 100px; text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $category): ?>
                                    <tr class="table-row-item">
                                        <td>
                                            <span class="code-pill">#<?php echo str_pad((string) $category['cat_id'], 2, '0', STR_PAD_LEFT); ?></span>
                                        </td>
                                        <td class="category-name-cell" style="font-weight: 600;">
                                            <?php echo htmlspecialchars($category['cat_name']); ?>
                                        </td>
                                        <td style="text-align: right;">
                                            <a 
                                                href="<?php echo app_url('views/admin/category.php?edit_id=' . (int) $category['cat_id']); ?>" 
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
