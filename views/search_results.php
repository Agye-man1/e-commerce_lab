<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$controller = new ProductController();
$userQuery = trim($_GET['user_query'] ?? '');
$products = $userQuery !== '' ? $controller->searchProducts($userQuery) : [];
$pageHeading = $userQuery !== '' ? 'Search results for "' . $userQuery . '"' : 'Product Search';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageHeading); ?> | shoppn</title>
    <link rel="stylesheet" href="<?php echo app_url('css/style.css?v=3.7'); ?>">
</head>
<body>

    <?php require_once __DIR__ . '/layout/header.php'; ?>

    <main class="site-main site-container">
        <nav class="breadcrumb">
            <a href="<?php echo app_url('index.php'); ?>">Home</a>
            <span>/</span>
            <span>Search</span>
        </nav>

        <div class="shop-layout">
            <?php require_once __DIR__ . '/layout/sidebar.php'; ?>

            <section>
                <div class="listing-head">
                    <h1>
                        <?php if ($userQuery !== ''): ?>
                            Results for &ldquo;<?php echo htmlspecialchars($userQuery); ?>&rdquo;
                        <?php else: ?>
                            Search
                        <?php endif; ?>
                    </h1>
                    <?php if ($userQuery !== ''): ?>
                        <span><?php echo count($products); ?> <?php echo count($products) === 1 ? 'product' : 'products'; ?></span>
                    <?php endif; ?>
                </div>

                <?php if ($userQuery === '' || empty($products)): ?>
                    <div class="card empty-state">
                        <h3><?php echo $userQuery === '' ? 'What are you looking for?' : 'No matching products'; ?></h3>
                        <p>
                            <?php echo $userQuery === ''
                                ? 'Type a product name, brand or keyword in the search bar above.'
                                : 'We couldn\'t find anything for that search. Check the spelling or try a more general word.'; ?>
                        </p>
                        <a href="<?php echo app_url('index.php'); ?>" class="btn btn-primary btn-sm">Browse All Products</a>
                    </div>
                <?php else: ?>
                    <div class="pgrid pgrid-3">
                        <?php foreach ($products as $product): ?>
                            <?php include __DIR__ . '/layout/product_card.php'; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </main>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>

</body>
</html>
