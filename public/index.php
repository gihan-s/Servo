<?php
session_start();

require_once '../config.php';
require_once '../app/controllers/HomeController.php';
require_once '../app/controllers/RegisterController.php';
require_once '../app/controllers/NotFoundController.php';
require_once '../app/controllers/ProfileController.php';
require_once '../app/controllers/FileController.php';
require_once '../app/controllers/DashboardController.php';
require_once '../app/controllers/ProjectController.php';
require_once '../app/controllers/PostController.php';

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
        $controller = new RegisterController();
        $controller->checkEmail();
        break;

    case 'register/check-nic':
        $controller = new RegisterController();
        $controller->checkNIC();
        break;

    case (preg_match('#^file/temp-images/(.+)$#', $url, $matches) ? true : false):
        $controller = new FileController();
        $controller->showUserImage($matches[1]);
        break;

    case 'profile':
        $controller = new ProfileController();
        $controller->view();
        break;

    case 'register/documents':
        $controller = new RegisterController();
        $controller->documents();
        break;

    case 'register/documentsubmit':
        $controller = new RegisterController();
        $controller->documentsubmit();
        break;

    case 'register/services':
        $controller = new RegisterController();
        $controller->services();
        break;

    case 'register/get-cities':
        $controller = new RegisterController();
        $controller->getCities();
        break;

    case 'register/get-skills':
        $controller = new RegisterController();
        $controller->getSkills();
        break;

    case 'register/servicesubmit':
        $controller = new RegisterController();
        $controller->servicesubmit();
        break;

    case 'profile/update':
        $controller = new ProfileController();
        $controller->update();
        break;

    case 'profile/account':
        $controller = new ProfileController();
        $controller->account();
        break;

    case 'profile/send-reset-code':
        $controller = new ProfileController();
        $controller->sendResetCode();
        break;

    case 'dashboard':
        $controller = new DashboardController();
        $controller->index();
        break;

    case 'projects':
        $controller = new ProjectController();
        $controller->index();
        break;

    case 'posts':
        $controller = new PostController();
        $controller->index();
        break;

    default:
        $controller = new NotFoundController();
        $controller->index();
        break;
}
