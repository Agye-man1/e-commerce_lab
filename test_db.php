<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "PHP is running!<br>";

require_once __DIR__ . '/core/db_class.php';

echo "Database class loaded!<br>";

$db = new Database();

echo "Database connection successful!";

?>
