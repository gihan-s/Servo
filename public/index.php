<?php
session_start();

require_once '../config.php';
require_once '../app/controllers/HomeController.php';
require_once '../app/controllers/RegisterController.php';
require_once '../app/controllers/NotFoundController.php';

// Get the URL path
$url = $_GET['url'] ?? 'home';

// Router
switch ($url) {
    case 'home':
        $controller = new HomeController();
        $controller->index();
        break;

    case 'register':
        $controller = new RegisterController();
        $controller->step1();
        break;

    case 'register/personalsubmit':
        $controller = new RegisterController();
        $controller->step1submit();
        break;

    case 'register/profile':
        $controller = new RegisterController();
        $controller->step2();
        break;

    case 'register/profilesubmit':
        $controller = new RegisterController();
        $controller->step2submit();
        break;

    default:
        $controller = new NotFoundController();
        $controller->index();
        break;
}
