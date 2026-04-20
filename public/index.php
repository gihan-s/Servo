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
require_once '../app/controllers/PayHereWebhookController.php';
require_once '../app/controllers/ProviderController.php';
require_once '../app/controllers/EarningsController.php';
require_once '../app/controllers/FeedController.php';
require_once '../app/controllers/BidController.php';

require_once '../app/controllers/admin/AdminLoginController.php';
require_once '../app/controllers/admin/AdminDashboardController.php';
require_once '../app/controllers/admin/AdminProviderController.php';
require_once '../app/controllers/admin/AdminClientController.php';
require_once '../app/controllers/admin/AdminCategoryController.php';
require_once '../app/controllers/admin/AdminLocationController.php';

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

    case (preg_match('#^file/review-files/(.+)$#', $url, $matches) ? true : false):
        $controller = new FileController();
        $controller->showReviewFile($matches[1]);
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

    case 'projects/ongoing':
        $controller = new ProjectController();
        $controller->getOngoingProjects();
        break;

    case 'projects/pending-review':
        $controller = new ProjectController();
        $controller->getPendingReviewProjects();
        break;

    case 'projects/completed':
        $controller = new ProjectController();
        $controller->getCompletedProjects();
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

    case (preg_match('#^project/complete/(\d+)$#', $url, $m) ? true : false):
        (new ProjectController())->completeProject((int)$m[1]);
        break;

    case (preg_match('#^project/reopen/(\d+)$#', $url, $m) ? true : false):
        (new ProjectController())->reopenProject((int)$m[1]);
        break;

    case (preg_match('#^project/reviews/(\d+)$#', $url, $m) ? true : false):
        (new ProjectController())->getProjectReviews((int)$m[1]);
        break;

    case (preg_match('#^project/provider-review/(\d+)$#', $url, $m) ? true : false):
        (new ProjectController())->submitProviderReview((int)$m[1]);
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

    case (preg_match('#^payments/invoice/(\d+)$#', $url, $m) ? true : false):
        (new PaymentController())->invoice((int) $m[1]);
        break;

    case (preg_match('#^payments/pay/(\d+)$#', $url, $m) ? true : false):
        (new PaymentController())->pay((int) $m[1]);
        break;

    case (preg_match('#^payments/cancel/(\d+)$#', $url, $m) ? true : false):
        (new PaymentController())->cancel((int) $m[1]);
        break;

    case (preg_match('#^payments/refund/(\d+)$#', $url, $m) ? true : false):
        (new PaymentController())->refund((int) $m[1]);
        break;

    case 'payments/report':
        (new PaymentController())->report();
        break;

    case (preg_match('#^payments/payhere/(\d+)$#', $url, $m) ? true : false):
        (new PaymentController())->payhereRedirect((int) $m[1]);
        break;

    case 'payments/payhere-notify':
        (new PayHereWebhookController())->notify();
        break;

    case 'payments/payhere-return':
        (new PayHereWebhookController())->returnPage();
        break;

    case 'payments/payhere-cancel':
        (new PayHereWebhookController())->cancelPage();
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

    case 'providers/profile':
        $controller = new ProviderController();
        $controller->getProfile();
        break;

    case 'provider/incoming-requests':
        $controller = new ProviderController();
        $controller->getIncomingRequests();
        break;

    case 'provider/ongoing-projects':
        $controller = new ProviderController();
        $controller->getOngoingProjects();
        break;

    case 'provider/pending-review-projects':
        $controller = new ProviderController();
        $controller->getPendingReviewProjects();
        break;

    case 'provider/reject-request':
        $controller = new ProviderController();
        $controller->rejectRequest();
        break;

    case 'provider/accept-request':
        $controller = new ProviderController();
        $controller->acceptRequest();
        break;

    case 'provider/accepted-requests':
        $controller = new ProviderController();
        $controller->getAcceptedRequests();
        break;

    case 'provider/completed-projects':
        $controller = new ProviderController();
        $controller->getCompletedProjects();
        break;

    case 'earnings':
        $controller = new EarningsController();
        $controller->index();
        break;

    case 'earnings/report':
        (new EarningsController())->report();
        break;

    case (preg_match('#^earnings/receipt/(\d+)$#', $url, $m) ? true : false):
        (new EarningsController())->receipt((int) $m[1]);
        break;

    case 'feed':
        $controller = new FeedController();
        $controller->index();
        break;

    case 'feed/submit-bid':
        $controller = new FeedController();
        $controller->submitBid();
        break;

    case 'feed/edit-bid':
        $controller = new FeedController();
        $controller->editBid();
        break;

    case 'feed/cancel-bid':
        $controller = new FeedController();
        $controller->cancelBid();
        break;

    case 'bids':
        $controller = new BidsController();
        $controller->index();
        break;

    case 'bids/edit':
        $controller = new BidsController();
        $controller->edit();
        break;

    case 'bids/withdraw':
        $controller = new BidsController();
        $controller->withdraw();
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

    case 'admin/providers/ban':
        $controller = new AdminProviderController();
        $controller->ban();
        break;

    case 'admin/providers/unban':
        $controller = new AdminProviderController();
        $controller->unban();
        break;

    case 'admin/clients':
        $controller = new AdminClientController();
        $controller->index();
        break;

    case (preg_match('#^admin/clients/view/(\d+)$#', $url, $matches) ? true : false):
        $controller = new AdminClientController();
        $controller->view($matches[1]);
        break;

    case (preg_match('#^admin/clients/api/(\d+)$#', $url, $matches) ? true : false):
        $controller = new AdminClientController();
        $controller->api($matches[1]);
        break;

    case 'admin/clients/ban':
        $controller = new AdminClientController();
        $controller->ban();
        break;

    case 'admin/clients/unban':
        $controller = new AdminClientController();
        $controller->unban();
        break;

    case 'admin/categories':
        $controller = new AdminCategoryController();
        $controller->index();
        break;

    case 'admin/categories/create':
        $controller = new AdminCategoryController();
        $controller->create();
        break;

    case 'admin/categories/edit':
        $controller = new AdminCategoryController();
        $controller->edit();
        break;

    case 'admin/categories/delete':
        $controller = new AdminCategoryController();
        $controller->delete();
        break;

    case 'admin/locations':
        $controller = new AdminLocationController();
        $controller->index();
        break;

    case 'admin/locations/create':
        $controller = new AdminLocationController();
        $controller->create();
        break;

    case 'admin/locations/edit':
        $controller = new AdminLocationController();
        $controller->edit();
        break;

    case 'admin/locations/delete':
        $controller = new AdminLocationController();
        $controller->delete();
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
