<?php
require_once '../config.php';
require_once '../app/controllers/HomeController.php';
require_once '../app/controllers/UsersController.php';

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
        echo "<h1>Methana Nah. Himathge Ass eke balanna</h1>";
        break;
}
