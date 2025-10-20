<?php

// Database credentials
if ($_SERVER['SERVER_NAME'] === 'localhost') {
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
