<?php
// $navCategories is loaded by header.php; fall back to an empty list if a page skipped the header
$footerCategories = $navCategories ?? [];
?>
<footer class="site-footer">
    <div class="site-container footer-grid">
        <div class="footer-col footer-about">
            <a href="<?php echo app_url('index.php'); ?>" class="brand-logo">
                <span class="brand-icon">S</span>
                <span>shoppn<span class="accent">.</span></span>
            </a>
            <p>Sneakers, apparel and everyday essentials from the brands you love, delivered to your door.</p>
        </div>

        <div class="footer-col">
            <h4>Shop</h4>
            <ul>
                <li><a href="<?php echo app_url('index.php'); ?>">Featured</a></li>
                <?php foreach (array_slice($footerCategories, 0, 5) as $footCat): ?>
                    <li><a href="<?php echo app_url('index.php?cat=' . (int) $footCat['cat_id']); ?>"><?php echo htmlspecialchars($footCat['cat_name']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="footer-col">
            <h4>My Account</h4>
            <ul>
                <?php if (is_logged_in()): ?>
                    <li><a href="<?php echo app_url('views/cart.php'); ?>">My Cart</a></li>
                    <li><a href="<?php echo app_url('logout.php'); ?>">Logout</a></li>
                <?php else: ?>
                    <li><a href="<?php echo app_url('views/login.php'); ?>">Login</a></li>
                    <li><a href="<?php echo app_url('views/register.php'); ?>">Create an account</a></li>
                    <li><a href="<?php echo app_url('views/cart.php'); ?>">My Cart</a></li>
                <?php endif; ?>
                <?php if (is_admin()): ?>
                    <li><a href="<?php echo app_url('views/admin/product.php'); ?>">Manage Products</a></li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="footer-col">
            <h4>We Accept</h4>
            <div class="pay-badges">
                <span>MTN MoMo</span>
                <span>Telecel Cash</span>
                <span>Visa</span>
                <span>Mastercard</span>
            </div>
            <p class="footer-note">Secure checkout powered by Paystack.</p>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="site-container footer-bottom-inner">
            <span>&copy; <?php echo date('Y'); ?> shoppn. All rights reserved.</span>
            <span>Accra, Ghana</span>
        </div>
    </div>
</footer>

<script src="<?php echo app_url('js/cart.js?v=3.5'); ?>"></script>
