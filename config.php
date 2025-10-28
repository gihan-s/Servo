<?php

// Database credentials
if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === 'servo.local') {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'servo');
} else {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'webuser');
    define('DB_PASS', 'Pass@Servo2025');
    define('DB_NAME', 'servo');
}



define('BASE_URL', '.');

date_default_timezone_set("Asia/Colombo");


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);