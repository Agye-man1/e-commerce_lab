<?php
require_once __DIR__ . '/../../core/core.php';

$currentUri = $_SERVER['REQUEST_URI'] ?? '';
$currentPath = parse_url($currentUri, PHP_URL_PATH) ?? '';
$homePath = rtrim(app_url(''), '/');
$isHome = strpos($currentPath, 'index.php') !== false || rtrim($currentPath, '/') === $homePath || $currentPath === '/' || $currentPath === '';
$isBrands = strpos($currentPath, 'brand.php') !== false;
$isCategories = strpos($currentPath, 'category.php') !== false;
$isProducts = strpos($currentPath, 'product.php') !== false;
$userInitial = is_logged_in() && !empty($_SESSION['customer_name']) ? strtoupper(mb_substr($_SESSION['customer_name'], 0, 1)) : 'U';
?>
<div class="top-announcement-bar">
    <div class="site-container top-announcement-content">
        <div class="announcement-links">
            <a href="<?php echo is_admin() ? app_url('views/admin/product.php') : app_url('views/login.php'); ?>">Start Selling</a>
            <span class="announcement-sep">&bull;</span>
            <a href="mailto:support@shoppn.local">Help Center</a>
            <span class="announcement-sep">&bull;</span>
            <a href="<?php echo app_url('index.php'); ?>">Official Sneaker Drops</a>
        </div>
        <div class="announcement-meta">
            <span>&#127468;&#127469; Ghana &bull; English (GH&#8373;)</span>
        </div>
    </div>
</div>

<header class="site-header">
    <div class="site-container nav-container">
        <a href="<?php echo app_url('index.php'); ?>" class="brand-logo">
            <span class="brand-symbol">&#9881;</span>
            <span>shoppn<span class="accent">.</span></span>
        </a>

        <form class="header-search" method="GET" action="<?php echo app_url('views/search_results.php'); ?>">
            <div class="input-pill-search">
                <span class="search-icon">&#9906;</span>
                <input type="search" name="user_query" placeholder='try "Nike Air Jordan"' value="<?php echo htmlspecialchars($_GET['user_query'] ?? ''); ?>" autocomplete="off">
            </div>
        </form>

        <nav class="nav-menu">
            <a href="<?php echo app_url('index.php'); ?>" class="nav-link <?php echo $isHome ? 'active' : ''; ?>">Home</a>

            <?php if (is_admin()): ?>
                <a href="<?php echo app_url('views/admin/brand.php'); ?>" class="nav-link <?php echo $isBrands ? 'active' : ''; ?>">Brands</a>
                <a href="<?php echo app_url('views/admin/category.php'); ?>" class="nav-link <?php echo $isCategories ? 'active' : ''; ?>">Categories</a>
                <a href="<?php echo app_url('views/admin/product.php'); ?>" class="nav-link <?php echo $isProducts ? 'active' : ''; ?>">Products</a>
            <?php endif; ?>
        </nav>

        <div class="nav-actions">
            <button type="button" class="nav-icon-btn" title="Notifications" aria-label="Notifications">
                &#128276;
            </button>

            <a href="<?php echo app_url('index.php'); ?>" class="nav-cart-pill" title="Shopping Cart">
                <span>&#128717;</span>
                <span>0 Items</span>
            </a>

            <?php if (is_logged_in()): ?>
                <div class="user-pill">
                    <span class="avatar-circle"><?php echo htmlspecialchars($userInitial); ?></span>
                    <span><?php echo htmlspecialchars($_SESSION['customer_name'] ?? 'Account'); ?></span>
                    <?php if (is_admin()): ?>
                        <span class="badge badge-admin"><span class="badge-dot"></span> Admin</span>
                    <?php endif; ?>
                </div>
                <a href="<?php echo app_url('logout.php'); ?>" class="btn btn-outline btn-sm">Logout</a>
            <?php else: ?>
                <a href="<?php echo app_url('views/login.php'); ?>" class="btn btn-outline btn-sm">Log in</a>
                <a href="<?php echo app_url('views/register.php'); ?>" class="btn btn-primary btn-sm">Get Started</a>
            <?php endif; ?>
        </div>
    </div>
</header>
