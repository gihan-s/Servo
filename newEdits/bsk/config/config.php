<?php
// Adjust to your local DB credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'servo');
define('DB_USER', 'root');
define('DB_PASS', '');

// Base URL detection (optional)
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
// If running from /bsk or /bsk/public, compute base path correctly
$script = $_SERVER['SCRIPT_NAME'] ?? '/';
$dir = str_replace('\\', '/', dirname($script));
// Normalize when script is /bsk/public/index.php → base should be /bsk
if (preg_match('#/public$#', $dir)) {
    $dir = substr($dir, 0, -7);
}
define('BASE_URL', rtrim($scheme . '://' . $host . rtrim($dir, '/'), '/'));
