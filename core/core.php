<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Africa/Accra');

/**
 * Dynamically resolves the base web URL of the application.
 * Specifically configured for live host: http://169.239.251.102:442/~kofi.agyeman/shoppn/
 * as well as localhost: http://localhost/shoppn/
 */
function get_base_url() {
    static $baseUrl = null;
    if ($baseUrl !== null) {
        return $baseUrl;
    }

    // 1. Manual override constant if set
    if (defined('APP_BASE_URL')) {
        $baseUrl = rtrim(APP_BASE_URL, '/');
        return $baseUrl;
    }

    $uri = $_SERVER['REQUEST_URI'] ?? '';
    $script = $_SERVER['SCRIPT_NAME'] ?? '';

    // 2. School server direct match (~kofi.agyeman/shoppn)
    if (strpos($uri, '~kofi.agyeman') !== false || strpos($script, '~kofi.agyeman') !== false) {
        $baseUrl = '/~kofi.agyeman/shoppn';
        return $baseUrl;
    }

    // 3. Dynamic match based on project folder name
    $dirName = basename(dirname(__DIR__)); // 'shoppn'
    foreach ([$script, $uri] as $target) {
        if (!empty($target) && preg_match('#^(.*?/' . preg_quote($dirName, '#') . ')#i', $target, $matches)) {
            $baseUrl = rtrim($matches[1], '/');
            return $baseUrl;
        }
    }

    // 4. Default fallback for local XAMPP
    $baseUrl = '/shoppn';
    return $baseUrl;
}

function app_url($path = '') {
    $base = get_base_url();
    $cleanPath = ltrim($path, '/');
    if ($cleanPath === '') {
        return $base === '' ? '/' : $base . '/';
    }
    return ($base === '' ? '' : $base) . '/' . $cleanPath;
}

function is_logged_in() {
    return isset($_SESSION['customer_id']);
}

function is_admin() {
    return is_logged_in() && (int) ($_SESSION['user_role'] ?? 0) === 1;
}

function require_login() {
    if (!is_logged_in()) {
        $_SESSION['error'] = 'Please log in to continue.';
        header('Location: ' . app_url('views/login.php'));
        exit;
    }
}

function require_admin() {
    if (!is_admin()) {
        $_SESSION['error'] = 'Admin access required.';
        header('Location: ' . app_url('index.php'));
        exit;
    }
}

function redirect($url) {
    header('Location: ' . $url);
    exit;
}

/**
 * Client IP used to identify a guest cart.
 * Uses REMOTE_ADDR only — forwarded headers can be spoofed by the client.
 */
function get_ip() {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
}

/**
 * Returns the referring page if it is on this site, otherwise the fallback.
 * Prevents redirecting users to external sites via a forged Referer header.
 */
function safe_referer($fallback) {
    $ref = $_SERVER['HTTP_REFERER'] ?? '';
    $refHost = parse_url($ref, PHP_URL_HOST);
    $host = parse_url('http://' . ($_SERVER['HTTP_HOST'] ?? ''), PHP_URL_HOST);
    return ($ref !== '' && $refHost !== null && $refHost === $host) ? $ref : $fallback;
}
