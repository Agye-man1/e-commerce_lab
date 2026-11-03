<?php
if (!isset($controller) || !($controller instanceof ProductController)) {
    require_once __DIR__ . '/../../controllers/ProductController.php';
    $controller = new ProductController();
}

$sidebarCategories = $controller->getAllCategories();
$sidebarBrands = $controller->getAllBrands();
$activeCat = (int) ($_GET['cat'] ?? 0);
$activeBrand = (int) ($_GET['brand'] ?? 0);
?>
<aside class="shop-sidebar">
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.6rem;">
            <h3 style="margin: 0; font-size: 0.95rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted);">
                Categories
            </h3>
            <?php if ($activeCat > 0 || $activeBrand > 0): ?>
                <a href="<?php echo app_url('index.php'); ?>" style="font-size: 0.75rem; color: var(--accent); font-weight: 600;">
                    Reset &times;
                </a>
            <?php endif; ?>
        </div>

        <ul class="sidebar-list">
            <li>
                <a
                    href="<?php echo app_url('index.php'); ?>"
                    class="<?php echo ($activeCat === 0 && $activeBrand === 0) ? 'active' : ''; ?>"
                >
                    All Catalog
                </a>
            </li>
            <?php foreach ($sidebarCategories as $category): ?>
                <li>
                    <a
                        href="<?php echo app_url('index.php?cat=' . (int) $category['cat_id']); ?>"
                        class="<?php echo $activeCat === (int) $category['cat_id'] ? 'active' : ''; ?>"
                    >
                        <?php echo htmlspecialchars($category['cat_name']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="card">
        <h3 style="margin: 0 0 0.6rem; font-size: 0.95rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted);">
            Brands
        </h3>
        <?php if (empty($sidebarBrands)): ?>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0;">No brands registered.</p>
        <?php else: ?>
            <ul class="sidebar-list">
                <?php foreach ($sidebarBrands as $brand): ?>
                    <li>
                        <a
                            href="<?php echo app_url('index.php?brand=' . (int) $brand['brand_id']); ?>"
                            class="<?php echo $activeBrand === (int) $brand['brand_id'] ? 'active' : ''; ?>"
                        >
                            <?php echo htmlspecialchars($brand['brand_name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</aside>
