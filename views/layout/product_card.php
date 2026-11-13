<?php
/**
 * Reusable product card. Expects $product (array) to be set by the including view.
 */
$cardId = (int) $product['product_id'];
$cardUrl = app_url('views/single_product.php?pro_id=' . $cardId);
?>
<article class="pcard">
    <a class="pcard-media" href="<?php echo $cardUrl; ?>">
        <img
            src="<?php echo app_url('images/products/' . rawurlencode($product['product_image'] ?? '')); ?>"
            alt="<?php echo htmlspecialchars($product['product_title']); ?>"
            loading="lazy"
        >
    </a>
    <div class="pcard-body">
        <?php if (!empty($product['brand_name'])): ?>
            <span class="pcard-brand"><?php echo htmlspecialchars($product['brand_name']); ?></span>
        <?php endif; ?>
        <h3 class="pcard-title">
            <a href="<?php echo $cardUrl; ?>" title="<?php echo htmlspecialchars($product['product_title']); ?>">
                <?php echo htmlspecialchars($product['product_title']); ?>
            </a>
        </h3>
        <div class="pcard-price">GH&#8373; <?php echo number_format((float) $product['product_price'], 2); ?></div>
        <a class="btn btn-primary pcard-cta" href="<?php echo app_url('actions/add_to_cart_action.php?add_cart=' . $cardId); ?>">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 6h15l-1.5 9h-12z"/><path d="M6 6 5 3H2"/><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/></svg>
            Add to Cart
        </a>
    </div>
</article>
