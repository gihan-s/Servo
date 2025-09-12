<?php
declare(strict_types=1);

// Front controller
ini_set('display_errors', '1');
error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/config/config.php';
require BASE_PATH . '/core/Autoload.php';

use Core\Router;

// Initialize Router and load routes
$router = new Router();
require BASE_PATH . '/routes/web.php';

// Dispatch
// Normalize URI to ignore the subfolder (e.g., /bsk or /bsk/public)
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/');
// Derive app base (e.g., /bsk) when script is /bsk/public/index.php
$appBase = $basePath;
if ($appBase !== '' && substr($appBase, -7) === '/public') {
    $appBase = substr($appBase, 0, -7);
}
if ($appBase && str_starts_with($requestUri, $appBase)) {
    $requestUri = substr($requestUri, strlen($appBase));
}
if ($requestUri === '' || $requestUri === false) { $requestUri = '/'; }

$router->dispatch($_SERVER['REQUEST_METHOD'], $requestUri);


