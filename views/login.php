<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . '/../controllers/CustomerController.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new CustomerController();
    $result = $controller->login($_POST);

    if ($result['success']) {
        header('Location: ../index.php');
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
    <title>Login | Shoppn</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <h1>Login to Shoppn</h1>

    <?php if ($error !== ''): ?>
        <p style="color: red;">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="">

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>

    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>

</body>
</html>
