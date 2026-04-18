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
require_once '../app/controllers/FeedController.php';
require_once '../app/controllers/BidController.php';

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

    case (preg_match('#^file/project-updates/(.+)$#', $url, $matches) ? true : false):
        $controller = new FileController();
        $controller->showProjectUpdateFile($matches[1]);
        break;

    case (preg_match('#^file/project-requirements/(.+)$#', $url, $matches) ? true : false):
        $controller = new FileController();
        $controller->showProjectRequirementFile($matches[1]);
        break;


    case (preg_match('#^file/category-icons/(.+)$#', $url, $matches) ? true : false):
        $controller = new FileController();
        $controller->getCategoryIcons($matches[1]);
        break;

    case 'profile':
        $controller = new ProfileController();
        $controller->view();
        break;

    case 'profile/add-service':
        $controller = new ProfileController();
        $controller->addService();
        break;

    case 'profile/remove-service':
        $controller = new ProfileController();
        $controller->removeService();
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

    case 'profile/change-profile-pic':
        $controller = new ProfileController();
        $controller->changeProfilePicture();
        break;

    case 'profile/update-password':
        $controller = new ProfileController();
        $controller->changePassword();
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

    case 'project/submit-requirements-update': // legacy — keep for backwards compat
    case 'project/add-requirement':
        (new ProjectController())->addRequirementAction();
        break;

    case 'project/update-requirement-status':
        (new ProjectController())->updateRequirementStatusAction();
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

    case 'requests/direct-request':
        $controller = new PostController();
        $controller->createDirectRequest();
        break;

    case (preg_match('#^requests/view/(\d+)$#', $url, $m) ? true : false):
        (new PostController())->viewPost((int)$m[1]);
        break;

    case (preg_match('#^/?project/getrequirements/(\d+)$#', $url, $m) ? true : false):
        (new ProjectController())->getRequirementsByPost((int)$m[1]);
        break;

    case (preg_match('#^/?project/details/(\d+)$#', $url, $m) ? true : false):
        (new ProjectController())->getProjectDetails((int)$m[1]);
        break;

    case (preg_match('#^requests/delete/(\d+)$#', $url, $m) ? true : false):
        (new PostController())->deletePost((int)$m[1]);
        break;

    case (preg_match('#^requests/cancel/(\d+)$#', $url, $m) ? true : false):
        (new ProjectController())->cancelRequest((int)$m[1]);
        break;
    case (preg_match('#^requests/payment/(\d+)$#', $url, $m) ? true : false):
        (new ProjectController())->initiatePayment((int)$m[1]);
        break;

    case (preg_match('#^project/cancel/(\d+)$#', $url, $m) ? true : false):
        (new ProjectController())->cancelProject((int)$m[1]);
        break;

    case (preg_match('#^project/update-progress/(\d+)$#', $url, $m) ? true : false):
        (new ProjectController())->updateProgress((int)$m[1]);
        break;

    case (preg_match('#^project/submit-review/(\d+)$#', $url, $m) ? true : false):
        (new ProjectController())->submitForReview((int)$m[1]);
        break;

    case (preg_match('#^requests/update/(\d+)$#', $url, $m) ? true : false):
        (new PostController())->updatePost((int)$m[1]);
        break;

    case (preg_match('#^requests/publish/(\d+)$#', $url, $m) ? true : false):
        (new PostController())->publishById((int)$m[1]);
        break;

    case (preg_match('#^requests/send-request/(\d+)$#', $url, $m) ? true : false):
        (new PostController())->sendRequestToProvider((int)$m[1]);
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

    case 'providers/search':
        $controller = new ProviderController();
        $controller->search();
        break;

    case 'providers/services':
        $controller = new ProviderController();
        $controller->getServices();
        break;

    case 'provider/incoming-requests':
        $controller = new ProviderController();
        $controller->getIncomingRequests();
        break;

    case 'provider/ongoing-projects':
        $controller = new ProviderController();
        $controller->getOngoingProjects();
        break;

    case 'provider/reject-request':
        $controller = new ProviderController();
        $controller->rejectRequest();
        break;

    case 'provider/accept-request':
        $controller = new ProviderController();
        $controller->acceptRequest();
        break;

    case 'earnings':
        $controller = new EarningsController();
        $controller->index();
        break;

    case 'feed':
        $controller = new FeedController();
        $controller->index();
        break;

    case 'feed/submit-bid':
        $controller = new FeedController();
        $controller->submitBid();
        break;

    case 'bids':
        $controller = new BidsController();
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

    case 'messages/get-messages':
        $controller = new MessageController();
        $controller->getMessages();
        break;

    case 'messages/start-conversation':
        $controller = new MessageController();
        $controller->startConversation();
        break;

    case 'messages/get-user':
        $controller = new MessageController();
        $controller->getUser();
        break;

    case 'messages/unread-count':
        $controller = new MessageController();
        $controller->getUnreadCount();
        break;

    case 'test/inputs':
        include '../inputs.html';
        break;

    case (preg_match('#^payment-gateway/(.+)$#', $url, $matches) ? true : false):
        $file = '../payment-gateway/' . $matches[1] . '.php';
        if (file_exists($file)) {
            include $file;
            break;
        }

    default:
        $controller = new NotFoundController();
        $controller->index();
        break;
}
