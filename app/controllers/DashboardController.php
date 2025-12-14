<?php

require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../models/ProviderModel.php';
require_once __DIR__ . '/../models/PostModel.php';
require_once __DIR__ . '/../models/PaymentModel.php';
require_once __DIR__ . '/../models/ProjectModel.php';
require_once __DIR__ . '/../core/helpers.php';

class DashboardController
{
    private $clientModel;
    private $providerModel;
    private $postModel;
    private $paymentModel;
    private $projectModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->providerModel = new ProviderModel();
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
                if ($request['posted_date'])
                    $request['time_ago'] = timeAgo($request['posted_date']);
                if ($request['expiry_date'])
                    $request['time_left'] = timeLeft($request['expiry_date']);
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

    private function ensureAuth(): void
    {
        if (empty($_SESSION['user_id']) || empty($_SESSION['role'])) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Please log in first'];
            header("Location: /login");
            exit;
        }
    }


}