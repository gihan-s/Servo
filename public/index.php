<?php
session_start();

require_once '../config.php';
require_once '../app/controllers/BaseController.php';
require_once '../app/controllers/HomeController.php';
require_once '../app/controllers/RegisterController.php';
require_once '../app/controllers/NotFoundController.php';
require_once '../app/controllers/ProfileController.php';
require_once '../app/controllers/FileController.php';
require_once '../app/controllers/DashboardController.php';
require_once '../app/controllers/ProjectController.php';
require_once '../app/controllers/PostController.php';
require_once '../app/controllers/LoginController.php';
require_once '../app/controllers/MessageController.php';
require_once '../app/controllers/NotificationController.php';
require_once '../app/controllers/PaymentController.php';
require_once '../app/controllers/ProviderController.php';
require_once '../app/controllers/EarningsController.php';

require_once '../app/controllers/admin/AdminLoginController.php';
require_once '../app/controllers/admin/AdminDashboardController.php';
require_once '../app/controllers/admin/AdminProviderController.php';

// Get the URL path
$url = $_GET['url'] ?? 'home';
$url = strtolower($url);

// Router
switch ($url) {
    case 'home':
        $controller = new HomeController();
        $controller->index();
        break;

    case 'login':
        $controller = new LoginController();
        $controller->view();
        break;

    case 'login/authenticate':
        $controller = new LoginController();
        $controller->authenticate();
        break;

    case 'logout':
        $controller = new LoginController();
        $controller->logout();
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

    case 'register/send-email-otp':
        $controller = new RegisterController();
        $controller->sendEmailOTP();
        break;

    case 'register/verify-email-otp':
        $controller = new RegisterController();
        $controller->verifyEmailOTP();
        break;

    case (preg_match('#^file/temp-images/(.+)$#', $url, $matches) ? true : false):
        $controller = new FileController();
        $controller->showTempImage($matches[1]);
        break;

    case (preg_match('#^file/user-files/(.+)$#', $url, $matches) ? true : false):
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

    case 'profile/delete-account':
        $controller = new ProfileController();
        $controller->deleteAccount();
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

    case 'projects/list':
        $controller = new ProjectController();
        $controller->getPosts();
        break;

    case 'requests':
        $controller = new PostController();
        $controller->index();
        break;

    case 'requests/list':
        $controller = new PostController();
        $controller->getPosts();
        break;

    case 'requests/get-skills':
        $controller = new PostController();
        $controller->getSkills();
        break;

    case 'requests/create':
        $controller = new PostController();
        $controller->create();
        break;

    case (preg_match('#^requests/view/(\d+)$#', $url, $m) ? true : false):
        (new PostController())->viewPost((int)$m[1]);
        break;

    case (preg_match('#^requests/delete/(\d+)$#', $url, $m) ? true : false):
        (new PostController())->deletePost((int)$m[1]);
        break;

    case (preg_match('#^requests/cancel/(\d+)$#', $url, $m) ? true : false):
        (new ProjectController())->cancelRequest((int)$m[1]);
        break;

    case (preg_match('#^requests/update/(\d+)$#', $url, $m) ? true : false):
        (new PostController())->updatePost((int)$m[1]);
        break;

    case (preg_match('#^requests/publish/(\d+)$#', $url, $m) ? true : false):
        (new PostController())->publishById((int)$m[1]);
        break;

    case (preg_match('#^requests/update-expired/(\d+)$#', $url, $m) ? true : false):
        (new PostController())->markAsExpired((int)$m[1]);
        break;

    case 'messages':
        $controller = new MessageController();
        $controller->index();
        break;

    case 'notifications':
        $controller = new NotificationController();
        $controller->index();
        break;

    case 'payments':
        $controller = new PaymentController();
        $controller->index();
        break;

    case 'providers':
        $controller = new ProviderController();
        $controller->index();
        break;

    case 'earnings':
        $controller = new EarningsController();
        $controller->index();
        break;

    case 'admin/login':
        $controller = new AdminLoginController();
        $controller->index();
        break;

    case 'admin/login/authenticate':
        $controller = new AdminLoginController();
        $controller->authenticate();
        break;

    case 'admin/logout':
        $controller = new AdminLoginController();
        $controller->logout();
        break;

    case 'admin/dashboard':
        $controller = new AdminDashboardController();
        $controller->index();
        break;

    case 'admin/providers':
        $controller = new AdminProviderController();
        $controller->index();
        break;

    case (preg_match('#^admin/providers/view/(\d+)$#', $url, $matches) ? true : false):
        $controller = new AdminProviderController();
        $controller->view($matches[1]);
        break;

    case 'admin/providers/provider-review':
        $controller = new AdminProviderController();
        $controller->review();
        break;

    default:
        $controller = new NotFoundController();
        $controller->index();
        break;
}
