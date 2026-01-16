<?php

require_once __DIR__ . '/../models/PostModel.php';
require_once __DIR__ . '/../models/PaymentModel.php';
require_once __DIR__ . '/../models/ProjectModel.php';

class DashboardController extends BaseController
{
    private $postModel;
    private $paymentModel;
    private $projectModel;

    public function __construct()
    {
        parent::__construct(); // inherit BaseController instead of overriding
        $this->postModel = new PostModel();
        $this->paymentModel = new PaymentModel();
        $this->projectModel = new ProjectModel();
    }

    // GET /dashboard
    public function index()
    {
        $this->ensureAuth();

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];

        // Choose view by role
        if ($role === 'Client') {
            $activeRequestCount = $this->postModel->countActiveRequests($userId);
            $recentRequests = $this->postModel->getRecentRequests($userId);
            $pendingPaymentCount = $this->paymentModel->getPaymentsCountByClientId($userId, 'Pending');
            $recentPayments = $this->paymentModel->getRecentPaymentsByClientId($userId, 3);
            $totalSpent = $this->paymentModel->getTotalSpentByClientId($userId);
            $totalProjectCount = $this->projectModel->countProjectsByClientId($userId);
            $activeProjects = $this->projectModel->getProjectsByClientId($userId, 3, 'Active');

            foreach ($recentRequests as &$request) {
                $request['Time_Ago'] = timeAgo($request['Created_At']);
                $request['Time_Left'] = timeLeft($request['End_At']);
            }
            unset($request);

            $viewFile = __DIR__ . '/../views/client/Dashboard/index.php';
        } elseif ($role === 'Provider') {
            $viewFile = __DIR__ . '/../views/provider/Dashboard/index.php';
        } else {
            http_response_code(403);
            echo "Invalid role";
            return;
        }

        include $viewFile;
    }

}