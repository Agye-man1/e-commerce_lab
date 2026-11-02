<?php
require_once __DIR__ . '/../../core/core.php';
require_admin();

require_once __DIR__ . '/../../controllers/ProductController.php';

$controller = new ProductController();
$brands = $controller->getAllBrands();
$categories = $controller->getAllCategories();

$editBrand = null;
$editId = (int) ($_GET['edit_id'] ?? 0);
if ($editId > 0) {
    $editBrand = $controller->getBrandById($editId);
}

$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

$formAction = $editBrand
    ? app_url('actions/update_brand_action.php')
    : app_url('actions/add_brand_action.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Brands — shoppn</title>
    <link rel="stylesheet" href="<?php echo app_url('css/style.css'); ?>">
</head>
<body>

    <?php require_once __DIR__ . '/../layout/header.php'; ?>

    <main class="site-main site-container">

        <div class="page-header">
            <h1 class="page-title">Brand Directory</h1>
            <p class="page-description">Configure the partner brands and manufacturers represented across the store.</p>
        </div>

        <!-- Admin Sub-Navigation Tabs -->
        <nav class="admin-subnav">
            <a href="<?php echo app_url('views/admin/brand.php'); ?>" class="admin-tab active">
                <span>Brands</span>
                <span class="count"><?php echo count($brands); ?></span>
            </a>
            <a href="<?php echo app_url('views/admin/category.php'); ?>" class="admin-tab">
                <span>Categories</span>
                <span class="count"><?php echo count($categories); ?></span>
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
                <div class="metric-label">Total Brands</div>
                <div class="metric-value"><?php echo count($brands); ?></div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Status</div>
                <div class="metric-value" style="font-size: 1.15rem; color: var(--accent);">
                    <?php echo $editBrand ? 'Editing Brand #' . (int) $editBrand['brand_id'] : 'Ready to Add'; ?>
                </div>
            </div>
        </div>

        <div class="admin-grid">

            <!-- Brand Form -->
            <div class="card sticky-form">
                <div class="card-header">
                    <h2><?php echo $editBrand ? 'Edit Brand' : 'Add New Brand'; ?></h2>
                    <p class="card-subtitle">
                        <?php echo $editBrand ? 'Modifying details for this brand.' : 'Register a new brand in the database.'; ?>
                    </p>
                </div>

                <div id="brand-js-error" class="alert alert-error" style="display: none;"></div>

                <form id="brand-form" method="POST" action="<?php echo htmlspecialchars($formAction); ?>">
                    <?php if ($editBrand): ?>
                        <input type="hidden" name="brand_id" value="<?php echo (int) $editBrand['brand_id']; ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="brand_name">Brand Name</label>
                        <input
                            id="brand_name"
                            type="text"
                            name="brand_name"
                            maxlength="100"
                            placeholder="e.g. Sony, Nike, Apple"
                            required
                            autofocus
                            value="<?php echo htmlspecialchars($editBrand['brand_name'] ?? ''); ?>"
                        >
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <?php echo $editBrand ? 'Save Changes' : 'Create Brand'; ?>
                        </button>

                        <?php if ($editBrand): ?>
                            <a href="<?php echo app_url('views/admin/brand.php'); ?>" class="btn btn-outline">
                                Cancel
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Brands Table -->
            <div>
                <div class="table-wrapper">
                    <div class="table-toolbar">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <h3 style="margin: 0;">Existing Brands</h3>
                            <span class="badge" style="background: var(--bg-muted); color: var(--text-secondary);">
                                <span id="brand-count"><?php echo count($brands); ?></span> total
                            </span>
                        </div>

                        <?php if (!empty($brands)): ?>
                            <div class="input-search-wrap" style="width: 220px;">
                                <span class="input-search-icon">&#9906;</span>
                                <input 
                                    type="search" 
                                    id="table-filter" 
                                    placeholder="Filter brands..." 
                                    autocomplete="off"
                                >
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if (empty($brands)): ?>
                        <div class="empty-state">
                            <p>No brands registered yet. Use the form on the left to add your first brand.</p>
                        </div>
                    <?php else: ?>
                        <table id="data-table">
                            <thead>
                                <tr>
                                    <th style="width: 90px;">Identifier</th>
                                    <th>Brand Name</th>
                                    <th style="width: 100px; text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($brands as $brand): ?>
                                    <tr class="table-row-item">
                                        <td>
                                            <span class="code-pill">#<?php echo str_pad((string) $brand['brand_id'], 2, '0', STR_PAD_LEFT); ?></span>
                                        </td>
                                        <td class="brand-name-cell" style="font-weight: 600;">
                                            <?php echo htmlspecialchars($brand['brand_name']); ?>
                                        </td>
                                        <td style="text-align: right;">
                                            <a 
                                                href="<?php echo app_url('views/admin/brand.php?edit_id=' . (int) $brand['brand_id']); ?>" 
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
