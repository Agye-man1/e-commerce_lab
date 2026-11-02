<?php
require_once __DIR__ . '/core/core.php';
require_once __DIR__ . '/controllers/ProductController.php';

$controller = new ProductController();
$brands = $controller->getAllBrands();
$categories = $controller->getAllCategories();

$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shoppn — Curated Everyday Goods</title>
    <link rel="stylesheet" href="<?php echo app_url('css/style.css'); ?>">
</head>
<body>

    <?php require_once __DIR__ . '/views/layout/header.php'; ?>

    <main class="site-main site-container">

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

        <!-- Hero Section -->
        <section class="hero-section">
            <span class="hero-badge">
                <span class="badge-dot" style="background: #10b981;"></span>
                <span>Curated Essentials &bull; Edition <?php echo date('Y'); ?></span>
            </span>
            <h1 class="hero-title">Curated goods for considered living.</h1>
            <p class="hero-subtitle">
                Understated minimalism, authenticated partner brands, and a seamless shopping experience engineered for everyday life.
            </p>

            <div class="hero-actions">
                <?php if (is_logged_in()): ?>
                    <?php if (is_admin()): ?>
                        <a href="<?php echo app_url('views/admin/brand.php'); ?>" class="btn btn-primary">
                            <span>Manage Brands</span>
                            <span class="code-pill" style="background: rgba(255,255,255,0.2); border: none; color: #fff;">
                                <?php echo count($brands); ?>
                            </span>
                        </a>
                        <a href="<?php echo app_url('views/admin/category.php'); ?>" class="btn btn-outline">
                            <span>Manage Categories</span>
                            <span class="code-pill">
                                <?php echo count($categories); ?>
                            </span>
                        </a>
                    <?php else: ?>
                        <span class="btn btn-secondary">
                            Welcome back, <?php echo htmlspecialchars($_SESSION['customer_name']); ?>
                        </span>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?php echo app_url('views/register.php'); ?>" class="btn btn-primary">Create Account</a>
                    <a href="<?php echo app_url('views/login.php'); ?>" class="btn btn-outline">Sign In</a>
                <?php endif; ?>
            </div>
        </section>

        <!-- Brand Showcase Pills (if brands exist in DB) -->
        <?php if (!empty($brands)): ?>
            <div class="card" style="margin-bottom: 2rem; padding: 1.5rem 1.75rem;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
                    <div style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted);">
                        Partner Brands
                    </div>
                    <?php if (is_admin()): ?>
                        <a href="<?php echo app_url('views/admin/brand.php'); ?>" style="font-size: 0.8rem; color: var(--accent); font-weight: 600;">
                            + Add Brand
                        </a>
                    <?php endif; ?>
                </div>

                <div style="display: flex; gap: 0.6rem; flex-wrap: wrap;">
                    <?php foreach ($brands as $brand): ?>
                        <span class="code-pill" style="font-size: 0.85rem; padding: 0.35rem 0.75rem; background: var(--bg-surface); font-family: inherit; font-weight: 500;">
                            <?php echo htmlspecialchars($brand['brand_name']); ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Categories Section (if categories exist) -->
        <?php if (!empty($categories)): ?>
            <div style="margin-bottom: 2.5rem;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
                    <div>
                        <h2 style="font-size: 1.25rem; margin-bottom: 0.2rem;">Categories</h2>
                        <p style="font-size: 0.875rem; color: var(--text-secondary); margin: 0;">Organized store collections.</p>
                    </div>
                    <?php if (is_admin()): ?>
                        <a href="<?php echo app_url('views/admin/category.php'); ?>" class="btn btn-outline btn-sm">
                            Manage Categories
                        </a>
                    <?php endif; ?>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem;">
                    <?php foreach ($categories as $cat): ?>
                        <div class="card" style="padding: 1.25rem; display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <span style="font-size: 0.75rem; color: var(--text-muted); display: block;">Category</span>
                                <span style="font-weight: 600; font-size: 0.95rem;"><?php echo htmlspecialchars($cat['cat_name']); ?></span>
                            </div>
                            <span style="color: var(--text-muted); font-size: 0.9rem;">&bull;</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </main>

    <?php require_once __DIR__ . '/views/layout/footer.php'; ?>

</body>
</html>
