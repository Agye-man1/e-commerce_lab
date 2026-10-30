<?php
$host = $_SERVER['SERVER_NAME'] ?? php_uname('n');
$isLocal = in_array($host, ['localhost', '127.0.0.1', '::1'], true)
    || stripos((string) $host, 'MacBook') !== false;

if ($isLocal) {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'shoppn');
} else {
    // On the school server, MySQL is local to that machine.
    define('DB_HOST', 'localhost');
    define('DB_USER', 'kofi.agyeman');
    define('DB_PASS', 'testkofi');
    define('DB_NAME', 'ecommerce_2026A_kofi_agyeman');
}
