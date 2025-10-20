<?php
require_once '../config.php';
require_once '../app/controllers/HomeController.php';
require_once '../app/controllers/UsersController.php';
require_once '../app/controllers/NotFoundController.php';

// Get the URL path
$url = $_GET['url'] ?? 'home';

// Router
switch ($url) {
    case 'home':
        $controller = new HomeController();
        $controller->index();
        break;

    case 'users':
        $controller = new UsersController();
        $controller->index();
        break;

    case (preg_match('/users\/show\/(\d+)/', $url, $matches) ? true : false):
        $controller = new UsersController();
        $controller->show($matches[1]); // pass ID from URL
        break;

    default:
        $controller = new NotFoundController();
        $controller->index();
        break;
}
