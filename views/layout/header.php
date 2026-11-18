<?php
require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../controllers/CartController.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

$cartController = new CartController();
$cartCount = $cartController->getCartCount(get_ip());
$cartTotal = $cartController->getCartTotal(get_ip());

$navProductController = new ProductController();
$navCategories = $navProductController->getAllCategories();

// Flash messages not already shown by the page itself
$flashError = $_SESSION['error'] ?? '';
$flashSuccess = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);

$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';

// Slide-out cart drawer (not needed on the cart page itself)
$showDrawer = substr($currentPath, -8) !== 'cart.php';
$drawerMsg = $_SESSION['cart_drawer'] ?? '';
unset($_SESSION['cart_drawer']);
$drawerOpen = $showDrawer && $drawerMsg !== '';
// Close links reload the current page (drawer closed) when JavaScript is unavailable
$drawerCloseUrl = htmlspecialchars($_SERVER['REQUEST_URI'] ?? app_url('index.php'));
$drawerItems = $showDrawer ? $cartController->getCartItems(get_ip()) : [];
$drawerUnits = 0;
foreach ($drawerItems as $drawerItem) {
    $drawerUnits += (int) $drawerItem['qty'];
}
$navActiveCat = (int) ($_GET['cat'] ?? 0);
$isHomePage = $navActiveCat === 0 && !isset($_GET['brand'])
    && (substr($currentPath, -9) === 'index.php' || substr($currentPath, -1) === '/');
?>
<header class="site-header">
    <div class="site-container mainbar">
        <a href="<?php echo app_url('index.php'); ?>" class="brand-logo" aria-label="shoppn home">
            <span class="brand-icon">S</span>
            <span>shoppn<span class="accent">.</span></span>
        </a>

        <form class="mainbar-search" method="GET" action="<?php echo app_url('views/search_results.php'); ?>" role="search">
            <input type="search" name="user_query" placeholder="Search for sneakers, apparel, brands..." aria-label="Search products"
                   value="<?php echo htmlspecialchars($_GET['user_query'] ?? ''); ?>">
            <button type="submit" aria-label="Search">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            </button>
        </form>

        <div class="mainbar-actions">
            <a href="<?php echo app_url('views/cart.php'); ?>" class="mainbar-cart"<?php echo $showDrawer ? ' data-drawer-open aria-controls="cart-drawer"' : ''; ?> aria-label="Cart, <?php echo (int) $cartCount; ?> items">
                <span class="mainbar-cart-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 6h15l-1.5 9h-12z"/><path d="M6 6 5 3H2"/><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/></svg>
                    <span class="mainbar-cart-count"><?php echo (int) $cartCount; ?></span>
                </span>
                <span class="mainbar-label">
                    <small>Cart</small>
                    <span>GH&#8373; <?php echo number_format((float) $cartTotal, 2); ?></span>
                </span>
            </a>

            <?php if (is_logged_in()): ?>
                <div class="mainbar-account">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21c1.5-4 4.5-6 8-6s6.5 2 8 6"/></svg>
                    <span class="mainbar-label">
                        <small>Welcome</small>
                        <span><?php echo htmlspecialchars($_SESSION['customer_name'] ?? 'Account'); ?></span>
                    </span>
                    <a href="<?php echo app_url('logout.php'); ?>" class="mainbar-logout">Logout</a>
                </div>
            <?php else: ?>
                <div class="mainbar-account">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21c1.5-4 4.5-6 8-6s6.5 2 8 6"/></svg>
                    <span class="mainbar-label">
                        <small>Hello, guest</small>
                        <span>
                            <a href="<?php echo app_url('views/register.php'); ?>">Register</a>
                            <span class="sep">|</span>
                            <a href="<?php echo app_url('views/login.php'); ?>">Login</a>
                        </span>
                    </span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <nav class="catnav" aria-label="Categories">
        <div class="site-container catnav-inner">
            <a href="<?php echo app_url('index.php'); ?>" class="<?php echo $isHomePage ? 'active' : ''; ?>">Home</a>
            <?php foreach ($navCategories as $navCat): ?>
                <a href="<?php echo app_url('index.php?cat=' . (int) $navCat['cat_id']); ?>"
                   class="<?php echo $navActiveCat === (int) $navCat['cat_id'] ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($navCat['cat_name']); ?>
                </a>
            <?php endforeach; ?>
            <?php if (is_admin()): ?>
                <span class="catnav-divider" aria-hidden="true"></span>
                <a href="<?php echo app_url('views/admin/brand.php'); ?>" class="catnav-admin">Brands</a>
                <a href="<?php echo app_url('views/admin/category.php'); ?>" class="catnav-admin">Categories</a>
                <a href="<?php echo app_url('views/admin/product.php'); ?>" class="catnav-admin">Products</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="cart-summary-bar">
        <div class="site-container">
            Shopping Cart &mdash; Total Items: <strong><?php echo (int) $cartCount; ?></strong>
            <span class="sep">|</span>
            Total Price: <strong>GH&#8373; <?php echo number_format((float) $cartTotal, 2); ?></strong>
            <span class="sep">|</span>
            <a href="<?php echo app_url('views/cart.php'); ?>">Go to Cart</a>
        </div>
    </div>
</header>

<?php if ($flashError !== '' || $flashSuccess !== ''): ?>
    <div class="site-container flash-wrap">
        <?php if ($flashError !== ''): ?>
            <div class="alert alert-error"><span><?php echo htmlspecialchars($flashError); ?></span></div>
        <?php endif; ?>
        <?php if ($flashSuccess !== ''): ?>
            <div class="alert alert-success"><span><?php echo htmlspecialchars($flashSuccess); ?></span></div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php if ($showDrawer): ?>
    <div class="drawer-overlay<?php echo $drawerOpen ? ' is-open' : ''; ?>" data-drawer-close></div>
    <aside id="cart-drawer" class="cart-drawer<?php echo $drawerOpen ? ' is-open' : ''; ?>"
           role="dialog" aria-modal="true" aria-labelledby="cart-drawer-title" aria-hidden="<?php echo $drawerOpen ? 'false' : 'true'; ?>">
        <div class="drawer-head">
            <h2 id="cart-drawer-title">Your Cart <span>(<?php echo $drawerUnits; ?>)</span></h2>
            <a href="<?php echo $drawerCloseUrl; ?>" class="drawer-close" data-drawer-close aria-label="Close cart">&times;</a>
        </div>

        <?php if ($drawerMsg !== ''): ?>
            <div class="drawer-added">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 5 5 9-10"/></svg>
                <?php echo htmlspecialchars($drawerMsg); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($drawerItems)): ?>
            <div class="drawer-empty">
                <p>Your cart is empty.</p>
                <a href="<?php echo $drawerCloseUrl; ?>" class="btn btn-accent" data-drawer-close>Start Shopping</a>
            </div>
        <?php else: ?>
            <ul class="drawer-items">
                <?php foreach ($drawerItems as $drawerItem): ?>
                    <?php $dPid = (int) $drawerItem['p_id']; ?>
                    <li class="drawer-item">
                        <a href="<?php echo app_url('views/single_product.php?pro_id=' . $dPid); ?>" class="drawer-thumb">
                            <img src="<?php echo app_url('images/products/' . rawurlencode($drawerItem['product_image'] ?? '')); ?>" alt="">
                        </a>
                        <div class="drawer-item-info">
                            <a href="<?php echo app_url('views/single_product.php?pro_id=' . $dPid); ?>"><?php echo htmlspecialchars($drawerItem['product_title']); ?></a>
                            <small>Qty <?php echo (int) $drawerItem['qty']; ?> &times; GH&#8373; <?php echo number_format((float) $drawerItem['product_price'], 2); ?></small>
                        </div>
                        <strong class="drawer-item-total">GH&#8373; <?php echo number_format((int) $drawerItem['qty'] * (float) $drawerItem['product_price'], 2); ?></strong>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="drawer-foot">
                <div class="drawer-subtotal">
                    <span>Subtotal</span>
                    <strong>GH&#8373; <?php echo number_format((float) $cartTotal, 2); ?></strong>
                </div>
                <p class="drawer-note">Delivery calculated at checkout</p>
                <a href="<?php echo app_url('views/checkout.php'); ?>" class="btn btn-accent btn-lg btn-block">Checkout</a>
                <a href="<?php echo app_url('views/cart.php'); ?>" class="btn btn-outline btn-lg btn-block">View &amp; Edit Cart</a>
                <a href="<?php echo $drawerCloseUrl; ?>" class="drawer-continue" data-drawer-close>Continue Shopping</a>
            </div>
        <?php endif; ?>
    </aside>
<?php endif; ?>
