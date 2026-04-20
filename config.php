<?php

function loadEnv($path)
{
    if (!file_exists($path)) {
        die('Error: .env file not found at ' . $path);
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        if (!array_key_exists($key, $_ENV)) {
            $_ENV[$key] = $value;
        }
    }
}

loadEnv(__DIR__ . '/.env');

if (!isset($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME'], $_ENV['BASE_URL'])) {
    die('Error: Missing required environment variables. Please check your .env file.');
}

define('DB_HOST', $_ENV['DB_HOST']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS']);
define('DB_NAME', $_ENV['DB_NAME']);
define('BASE_URL', $_ENV['BASE_URL']);
define('APP_URL',  $_ENV['APP_URL'] ?? '');
define('APP_SECRET', $_ENV['APP_SECRET']);
define('WEBSOCKET_URL', $_ENV['WEBSOCKET_URL']);

// PayHere payment gateway
define('PAYHERE_MERCHANT_ID',     $_ENV['PAYHERE_MERCHANT_ID']     ?? '');
define('PAYHERE_MERCHANT_SECRET', $_ENV['PAYHERE_MERCHANT_SECRET'] ?? '');
define('PAYHERE_SANDBOX',         filter_var($_ENV['PAYHERE_SANDBOX'] ?? 'true', FILTER_VALIDATE_BOOLEAN));
define('PAYHERE_CHECKOUT_URL',    PAYHERE_SANDBOX
    ? 'https://sandbox.payhere.lk/pay/checkout'
    : 'https://www.payhere.lk/pay/checkout');

date_default_timezone_set("Asia/Colombo");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);