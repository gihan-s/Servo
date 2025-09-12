<?php
// Base URL detection (optional)
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
// If running from /bsk or /bsk/public, compute base path correctly
define('BASE_URL', rtrim($scheme . '://' . $host. '/bsk'));
