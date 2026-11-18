<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

// Message from require_login() or another page, e.g. "Please log in to continue."
$error = $_SESSION['error'] ?? '';
$notice = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new CustomerController();
    $result = $controller->login($_POST);

    if ($result['success']) {
        // Return to the page that asked for login (e.g. checkout), if it was one of ours
        $next = $_SESSION['after_login'] ?? '';
        unset($_SESSION['after_login']);
        $ourPath = parse_url(app_url(''), PHP_URL_PATH);
        if ($next === '' || strpos($next, $ourPath) !== 0 || strpos($next, '//') !== false) {
            $next = app_url('index.php');
        }
        header('Location: ' . $next);
        exit;
    } else {
        $error = $result['error'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — shoppn</title>
    <link rel="stylesheet" href="<?php echo app_url('css/style.css?v=4.2'); ?>">
    <link rel="icon" type="image/svg+xml" href="<?php echo app_url('images/brand/favicon.svg'); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo app_url('images/brand/favicon-32.png'); ?>">
    <link rel="apple-touch-icon" href="<?php echo app_url('images/brand/apple-touch-icon.png'); ?>">
</head>
<body>

    <div class="auth-wrapper">
        <div class="auth-card">

            <a href="<?php echo app_url('index.php'); ?>" class="auth-back">&larr; Back to shop</a>

            <div class="auth-header">
                <a href="<?php echo app_url('index.php'); ?>" class="brand-logo" style="font-size: 1.5rem; margin-bottom: 1rem;">
                    <?php include __DIR__ . '/layout/logo_mark.php'; ?>
                    <span>shoppn<span class="accent">.</span></span>
                </a>
                <h1>Welcome back</h1>
                <p>Enter your email and password to access your account</p>
            </div>

            <?php if ($error !== ''): ?>
                <div class="alert alert-error">
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <?php if ($notice !== ''): ?>
                <div class="alert alert-success">
                    <span><?php echo htmlspecialchars($notice); ?></span>
                </div>
            <?php endif; ?>

            <div id="login-js-error" class="alert alert-error" style="display: none;"></div>

            <form id="login-form" method="POST" action="">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input 
                        id="email"
                        type="email" 
                        name="email" 
                        placeholder="you@example.com"
                        required 
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        id="password"
                        type="password" 
                        name="password" 
                        placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary btn-block" style="margin-top: 1.5rem;">
                    Sign In
                </button>
            </form>

            <div class="auth-footer">
                Don't have an account? 
                <a href="<?php echo app_url('views/register.php'); ?>" style="font-weight: 500;">
                    Create account
                </a>
            </div>

        </div>
    </div>

    <script src="<?php echo app_url('js/validate.js'); ?>"></script>
</body>
</html>
