<?php
/**
 * views/home.php - Modern Sneaker Storefront View
 * Faithful implementation of the reference UI design system.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shoppn | Authentic Sneaker & Footwear Drops</title>
    <link rel="stylesheet" href="<?php echo app_url('css/style.css?v=2.2'); ?>">
</head>
<body>

    <?php require_once __DIR__ . '/layout/header.php'; ?>

    <main class="site-main site-container">

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <span><?php echo htmlspecialchars($success); ?></span>
            </div>
        <?php endif; ?>

        <?php if (empty($isFiltered)): ?>
            <!-- ── Sneaker Hero Grid (Reference Match) ─────────────────────────── -->
            <section class="sneaker-hero-grid">
                <!-- Main Air Jordan Spotlight Card -->
                <article class="hero-main-card">
                    <div class="hero-main-content">
                        <span class="hero-kicker">NEW ARRIVAL!</span>
                        <h1 class="hero-main-title">AIR JORDAN 6 G X EASTSIDE</h1>
                        <p class="hero-main-desc">
                            Feel unbeatable from the tee box to the final putt in a design that's pure early MJ: speed, class, and premium performance.
                        </p>
                        <a href="#catalog" class="btn btn-primary">
                            View product
                        </a>
                    </div>
                    <div class="hero-main-image-wrap">
                        <img 
                            class="hero-sneaker-img" 
                            src="<?php echo app_url('images/products/airforce.webp'); ?>" 
                            alt="Air Jordan 6 G x Eastside Golf"
                        >
                    </div>
                </article>

                <!-- Secondary Sneaker Fest Promo Card -->
                <article class="hero-promo-card">
                    <div>
                        <h2 class="promo-title">DISC UP TO 50% FOR SNEAKERS FEST 2026</h2>
                        <p class="promo-desc">
                            Join the sneaker fest on 23rd October. Exclusive drops, rare collabs, and community showcases supported by shoppn.
                        </p>
                    </div>
                    <div>
                        <a href="#deals" class="btn btn-white">
                            Event details
                        </a>
                    </div>
                    <img 
                        class="promo-image-corner" 
                        src="<?php echo app_url('images/products/airforce.webp'); ?>" 
                        alt="Sneaker Fest Promo"
                        aria-hidden="true"
                    >
                </article>
            </section>

            <!-- ── "Today Best Deals!" Flash Row ───────────────────────────────── -->
            <?php if (!empty($deals)): ?>
                <section id="deals" style="margin-bottom: 3.5rem;">
                    <div class="section-title-row">
                        <h2 style="font-size: 1.45rem; font-weight: 800; letter-spacing: -0.02em; margin: 0;">Today Best Deals!</h2>
                        <span class="deals-timer-badge">
                            <span>&#9201;</span> Ends in 12:10:09
                        </span>
                    </div>

                    <div class="deals-grid">
                        <?php foreach ($deals as $deal): ?>
                            <article class="sneaker-card">
                                <div class="card-top-bar">
                                    <span class="card-badge-status">Just in</span>
                                    <button type="button" class="card-heart-btn" title="Add to wishlist" aria-label="Add to wishlist">&#9825;</button>
                                </div>

                                <div class="sneaker-img-wrap">
                                    <img 
                                        src="<?php echo app_url('images/products/' . rawurlencode($deal['product_image'] ?? '')); ?>" 
                                        alt="<?php echo htmlspecialchars($deal['product_title']); ?>"
                                        loading="lazy"
                                    >
                                </div>

                                <div class="sneaker-info">
                                    <h3 class="sneaker-title" title="<?php echo htmlspecialchars($deal['product_title']); ?>">
                                        <?php echo htmlspecialchars($deal['product_title']); ?>
                                    </h3>

                                    <div class="rating-row">
                                        <span class="rating-star">&#9733;</span>
                                        <strong>4.9</strong>
                                        <span>&bull; <?php echo ((int) $deal['product_id'] * 127 + 230); ?> Items Sold</span>
                                    </div>

                                    <div class="price-row">
                                        <span class="price-original">
                                            GH&#8373; <?php echo htmlspecialchars(number_format(((float) $deal['product_price']) * 1.25, 2)); ?>
                                        </span>
                                        <span class="price-current">
                                            GH&#8373; <?php echo htmlspecialchars(number_format((float) $deal['product_price'], 2)); ?>
                                        </span>
                                    </div>

                                    <div class="sneaker-actions">
                                        <a 
                                            class="btn btn-outline btn-sm" 
                                            href="<?php echo app_url('views/single_product.php?pro_id=' . (int) $deal['product_id']); ?>"
                                        >
                                            Details
                                        </a>
                                        <a 
                                            class="btn btn-primary btn-sm" 
                                            href="<?php echo app_url('actions/add_to_cart_action.php?add_cart=' . (int) $deal['product_id']); ?>"
                                        >
                                            Add to Cart
                                        </a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
        <?php endif; ?>

        <!-- ── Store Editorial Intro & Filter Bar ──────────────────────────── -->
        <section id="catalog">
            <div class="store-editorial-intro">
                <h2 class="store-editorial-title">Shop Now, Goodlook Later</h2>
                <p class="store-editorial-subtitle">
                    We have a curated collection for you! Let's explore and find your dream sneakers, verified authentic.
                </p>
            </div>

            <!-- Filter Pills Bar (Dynamically Driven by Categories & Brands) -->
            <div class="filter-pills-bar">
                <a 
                    href="<?php echo app_url('index.php#catalog'); ?>" 
                    class="filter-pill <?php echo (empty($catId) && empty($brandId)) ? 'active' : ''; ?>"
                >
                    All Sneakers
                </a>

                <?php foreach ($allCategories as $category): ?>
                    <a 
                        href="<?php echo app_url('index.php?cat=' . (int) $category['cat_id'] . '#catalog'); ?>" 
                        class="filter-pill <?php echo ($catId === (int) $category['cat_id']) ? 'active' : ''; ?>"
                    >
                        <?php echo htmlspecialchars($category['cat_name']); ?>
                    </a>
                <?php endforeach; ?>

                <?php foreach ($allBrands as $brand): ?>
                    <a 
                        href="<?php echo app_url('index.php?brand=' . (int) $brand['brand_id'] . '#catalog'); ?>" 
                        class="filter-pill <?php echo ($brandId === (int) $brand['brand_id']) ? 'active' : ''; ?>"
                    >
                        &#10003; <?php echo htmlspecialchars($brand['brand_name']); ?>
                    </a>
                <?php endforeach; ?>

                <?php if (!empty($isFiltered)): ?>
                    <a 
                        href="<?php echo app_url('index.php#catalog'); ?>" 
                        class="filter-pill" 
                        style="border-color: var(--accent-coral); color: var(--accent-coral);"
                    >
                        Reset Filter &times;
                    </a>
                <?php endif; ?>
            </div>

            <!-- Catalog Sub-header -->
            <div class="section-title-row" style="margin-bottom: 1.5rem;">
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; margin: 0 0 0.25rem;">
                        <?php echo htmlspecialchars($pageHeading); ?>
                    </h3>
                    <p style="font-size: 0.875rem; color: var(--text-secondary); margin: 0;">
                        <?php if (!empty($products)): ?>
                            Showing <?php echo count($products); ?> authentic sneaker <?php echo count($products) === 1 ? 'edition' : 'editions'; ?>.
                        <?php else: ?>
                            Explore verified drops and deadstock sneakers.
                        <?php endif; ?>
                    </p>
                </div>

                <?php if (is_admin()): ?>
                    <a href="<?php echo app_url('views/admin/product.php'); ?>" class="btn btn-outline btn-sm">
                        + Add Product
                    </a>
                <?php endif; ?>
            </div>

            <!-- Sneaker Catalog Cards Grid -->
            <?php if (empty($products)): ?>
                <div class="card empty-state" style="padding: 4rem 2rem; text-align: center;">
                    <div style="font-size: 2.5rem; margin-bottom: 1rem; color: var(--text-muted);">&#9671;</div>
                    <h3 style="margin-bottom: 0.5rem;">No sneakers found</h3>
                    <p style="max-width: 420px; margin: 0 auto 1.5rem; color: var(--text-secondary);">
                        There are currently no products listed under this filter. Reset your selection to explore the full collection.
                    </p>
                    <a href="<?php echo app_url('index.php#catalog'); ?>" class="btn btn-primary btn-sm">
                        View All Sneakers
                    </a>
                </div>
            <?php else: ?>
                <div class="product-grid">
                    <?php foreach ($products as $product): ?>
                        <article class="sneaker-card">
                            <div class="card-top-bar">
                                <span class="card-badge-status">Just in</span>
                                <button type="button" class="card-heart-btn" title="Add to wishlist" aria-label="Add to wishlist">&#9825;</button>
                            </div>

                            <div class="sneaker-img-wrap">
                                <img 
                                    src="<?php echo app_url('images/products/' . rawurlencode($product['product_image'] ?? '')); ?>" 
                                    alt="<?php echo htmlspecialchars($product['product_title']); ?>"
                                    loading="lazy"
                                >
                            </div>

                            <div class="sneaker-info">
                                <h3 class="sneaker-title" title="<?php echo htmlspecialchars($product['product_title']); ?>">
                                    <?php echo htmlspecialchars($product['product_title']); ?>
                                </h3>

                                <div class="rating-row">
                                    <span class="rating-star">&#9733;</span>
                                    <strong>4.9</strong>
                                    <span>&bull; Verified Stock</span>
                                </div>

                                <div class="sneaker-price">
                                    GH&#8373; <?php echo htmlspecialchars(number_format((float) $product['product_price'], 2)); ?>
                                </div>

                                <div class="sneaker-actions">
                                    <a 
                                        class="btn btn-outline btn-sm" 
                                        href="<?php echo app_url('views/single_product.php?pro_id=' . (int) $product['product_id']); ?>"
                                    >
                                        Details
                                    </a>
                                    <a 
                                        class="btn btn-primary btn-sm" 
                                        href="<?php echo app_url('actions/add_to_cart_action.php?add_cart=' . (int) $product['product_id']); ?>"
                                    >
                                        Add to Cart
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <?php if (empty($isFiltered)): ?>
            <!-- ── Brand Showcase Section (Reference Match) ────────────────────── -->
            <section class="brand-showcase-section">
                <div class="brand-showcase-header">
                    <h2 class="brand-showcase-title">More of The Amazing Brand</h2>
                    <p class="brand-showcase-subtitle">
                        Collaborating with high quality, certified sneaker brands around the world.
                    </p>
                </div>

                <div class="brand-cards-grid">
                    <?php 
                    // Showcase up to 2 prominent brands dynamically
                    $spotlightBrands = array_slice($allBrands, 0, 2);
                    foreach ($spotlightBrands as $sb): 
                    ?>
                        <article class="brand-card">
                            <div class="brand-card-top">
                                <div class="brand-card-info">
                                    <div class="brand-card-icon">
                                        <?php echo htmlspecialchars(strtoupper(substr($sb['brand_name'], 0, 1))); ?>
                                    </div>
                                    <div>
                                        <div class="brand-card-name">
                                            <span><?php echo htmlspecialchars($sb['brand_name']); ?></span>
                                            <span class="verified-check">&#10004;</span>
                                        </div>
                                        <div class="brand-card-meta">
                                            <span class="rating-star">&#9733;</span> 4.9 &bull; 125M Followers
                                        </div>
                                    </div>
                                </div>
                                <a 
                                    href="<?php echo app_url('index.php?brand=' . (int) $sb['brand_id'] . '#catalog'); ?>" 
                                    class="btn btn-outline btn-sm"
                                >
                                    Visit
                                </a>
                            </div>

                            <div class="brand-thumbs-row">
                                <div class="brand-thumb-item">
                                    <img src="<?php echo app_url('images/products/airforce.webp'); ?>" alt="Sneaker preview" loading="lazy">
                                </div>
                                <div class="brand-thumb-item">
                                    <img src="<?php echo app_url('images/products/airforce.webp'); ?>" alt="Sneaker preview" loading="lazy">
                                </div>
                                <div class="brand-thumb-item">
                                    <img src="<?php echo app_url('images/products/airforce.webp'); ?>" alt="Sneaker preview" loading="lazy">
                                </div>
                                <div class="brand-thumb-item">
                                    <img src="<?php echo app_url('images/products/airforce.webp'); ?>" alt="Sneaker preview" loading="lazy">
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <!-- Dark Promotional Callout (Reference Match) -->
                <div class="dark-promo-banner">
                    <div class="dark-promo-content">
                        <h3 class="dark-promo-title">BRINGING YOU TO UPDATE AUTHENTIC FOOTWEAR</h3>
                        <p class="dark-promo-desc">
                            The best selection on shoppn. Verified deadstock, collector editions, and lightning delivery across Ghana. Check out the latest drop!
                        </p>
                        <a href="#catalog" class="btn btn-white">
                            Explore Collection
                        </a>
                    </div>
                </div>
            </section>
        <?php endif; ?>

    </main>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>

</body>
</html>
