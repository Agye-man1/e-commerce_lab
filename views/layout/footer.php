<footer class="site-footer">
    <div class="site-container footer-container">
        <p>&copy; <?php echo date('Y'); ?> shoppn. All rights reserved.</p>
        <div class="footer-links">
            <a href="<?php echo app_url('index.php'); ?>">Home</a>
            <?php if (is_admin()): ?>
                <a href="<?php echo app_url('views/admin/brand.php'); ?>">Brands</a>
                <a href="<?php echo app_url('views/admin/category.php'); ?>">Categories</a>
                <a href="<?php echo app_url('views/admin/product.php'); ?>">Products</a>
            <?php endif; ?>
        </div>
    </div>
</footer>
