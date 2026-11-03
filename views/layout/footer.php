<footer class="site-footer">
    <div class="site-container">
        <div class="footer-features-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; padding-bottom: 2rem; margin-bottom: 2rem; border-bottom: 1px solid var(--border-subtle);">
            <div>
                <strong style="display: block; font-size: 0.95rem; margin-bottom: 0.25rem;">&#10003; 100% Authentic Sneakers</strong>
                <span style="font-size: 0.8rem; color: var(--text-secondary);">Every pair undergoes rigorous physical verification before dispatch.</span>
            </div>
            <div>
                <strong style="display: block; font-size: 0.95rem; margin-bottom: 0.25rem;">&#128666; Express Delivery</strong>
                <span style="font-size: 0.8rem; color: var(--text-secondary);">Fast door-to-door delivery across Accra, Kumasi, and nationwide.</span>
            </div>
            <div>
                <strong style="display: block; font-size: 0.95rem; margin-bottom: 0.25rem;">&#128274; Transparent Pricing</strong>
                <span style="font-size: 0.8rem; color: var(--text-secondary);">All pricing in Ghana Cedis (GH&#8373;) with verified inventory.</span>
            </div>
        </div>

        <div class="footer-container">
            <p>&copy; <?php echo date('Y'); ?> shoppn. Authentic Sneaker Storefront &bull; MVC E-Commerce.</p>
            <div class="footer-links">
                <a href="<?php echo app_url('index.php'); ?>">Home</a>
                <?php if (is_admin()): ?>
                    <a href="<?php echo app_url('views/admin/brand.php'); ?>">Brands</a>
                    <a href="<?php echo app_url('views/admin/category.php'); ?>">Categories</a>
                    <a href="<?php echo app_url('views/admin/product.php'); ?>">Products</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</footer>
