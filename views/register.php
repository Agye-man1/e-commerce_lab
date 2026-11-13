<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new CustomerController();
    $result = $controller->register($_POST);

    if ($result['success']) {
        $success = $result['message'];
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
    <title>Create Account — shoppn</title>
    <link rel="stylesheet" href="<?php echo app_url('css/style.css?v=3.3'); ?>">
</head>
<body>

    <div class="auth-wrapper">
        <div class="auth-card" style="max-width: 500px;">

            <div class="auth-header">
                <a href="<?php echo app_url('index.php'); ?>" class="brand-logo" style="font-size: 1.5rem; margin-bottom: 1rem;">
                    shoppn<span>.</span>
                </a>
                <h1>Create an account</h1>
                <p>Join shoppn to create your account</p>
            </div>

            <?php if ($error !== ''): ?>
                <div class="alert alert-error">
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <?php if ($success !== ''): ?>
                <div class="alert alert-success">
                    <span>
                        <?php echo htmlspecialchars($success); ?> 
                        <a href="<?php echo app_url('views/login.php'); ?>" style="font-weight: 600; text-decoration: underline; margin-left: 0.5rem;">
                            Sign in now &rarr;
                        </a>
                    </span>
                </div>
            <?php endif; ?>

            <div id="register-js-error" class="alert alert-error" style="display: none;"></div>

            <form id="register-form" method="POST" action="">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input 
                        id="name"
                        type="text" 
                        name="name" 
                        placeholder="Kwame Mensah"
                        required 
                        value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="email">Email address</label>
                    <input 
                        id="email"
                        type="email" 
                        name="email" 
                        placeholder="you@example.com"
                        required 
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password (at least 8 characters)</label>
                    <input 
                        id="password"
                        type="password" 
                        name="password" 
                        placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                        minlength="8"
                        required
                    >
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input 
                            id="country"
                            type="text" 
                            name="country" 
                            placeholder="Ghana"
                            required 
                            value="<?php echo htmlspecialchars($_POST['country'] ?? ''); ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label for="city">City</label>
                        <input 
                            id="city"
                            type="text" 
                            name="city" 
                            placeholder="Accra"
                            required 
                            value="<?php echo htmlspecialchars($_POST['city'] ?? ''); ?>"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="contact">Contact Number</label>
                    <input 
                        id="contact"
                        type="text" 
                        name="contact" 
                        placeholder="+233 24 000 0000"
                        required 
                        value="<?php echo htmlspecialchars($_POST['contact'] ?? ''); ?>"
                    >
                </div>

                <button type="submit" class="btn btn-primary btn-block" style="margin-top: 1.5rem;">
                    Create Account
                </button>
            </form>

            <div class="auth-footer">
                Already have an account? 
                <a href="<?php echo app_url('views/login.php'); ?>" style="font-weight: 500;">
                    Sign in
                </a>
            </div>

        </div>
    </div>

    <script src="<?php echo app_url('js/validate.js'); ?>"></script>
</body>
</html>
