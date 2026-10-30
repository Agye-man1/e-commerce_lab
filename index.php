<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoppn | Home</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <h1>Welcome to Shoppn!</h1>

    <?php if (isset($_SESSION['customer_id'])): ?>

        <h2>Hello, <?php echo htmlspecialchars($_SESSION['customer_name']); ?>!</h2>

        <p>You are successfully logged in.</p>

        <a href="logout.php">Logout</a>

    <?php else: ?>

        <p>Welcome! Please log in or create an account.</p>

        <a href="views/login.php">Login</a>
        <a href="views/register.php">Register</a>

    <?php endif; ?>

</body>
</html>