<?php
session_start();

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
    <title>Register | Shoppn</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <h1>Create Your Shoppn Account</h1>

    <?php if ($error !== ''): ?>
        <p style="color: red;">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php endif; ?>

    <?php if ($success !== ''): ?>
        <p style="color: green;">
            <?php echo htmlspecialchars($success); ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="">

        <label>Full Name</label>
        <input type="text" name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Country</label>
        <input type="text" name="country" required>

        <label>City</label>
        <input type="text" name="city" required>

        <label>Contact</label>
        <input type="text" name="contact" required>

        <button type="submit">Register</button>

    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>

</body>
</html>
