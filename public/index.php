<?php
session_start();

require_once '../config.php';
require_once '../app/controllers/HomeController.php';
require_once '../app/controllers/RegisterController.php';
require_once '../app/controllers/NotFoundController.php';
require_once '../app/controllers/ProfileController.php';
require_once '../app/controllers/FileController.php';

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

    case 'register/password':
        $controller = new RegisterController();
        $controller->password();
        break;

    case 'register/passwordsubmit':
        $controller = new RegisterController();
        $controller->passwordsubmit();
        break;

    case 'register/check-email':
        require_once '../app/controllers/RegisterController.php';
        $controller = new RegisterController();
        $controller->checkEmail();
        break;


    case (preg_match('#^file/temp-images/(.+)$#', $url, $matches) ? true : false):
        $controller = new FileController();
        $controller->showUserImage($matches[1]);
        break;

    default:
        $controller = new NotFoundController();
        $controller->index();
        break;
}
