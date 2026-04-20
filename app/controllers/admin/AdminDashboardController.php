<?php

require_once __DIR__ . '/../../models/admin/AdminDashboardModel.php';

class AdminDashboardController
{
    private $model;

    public function __construct()
    {
        $this->model = new AdminDashboardModel();
    }

    public function index()
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
            header("Location: /admin/login");
            exit;
        }

        $activeClients    = $this->model->getActiveClientCount();
        $activeProviders  = $this->model->getActiveProviderCount();
        $activePosts      = $this->model->getActivePostCount();
        $totalPayment     = $this->model->getTotalPaymentReceived();
        $projectCounts    = $this->model->getProjectStatusCounts();
        $topBidProviders  = $this->model->getTopProvidersByBids(5);
        $topEarnProviders = $this->model->getTopProvidersByEarning(5);
        $topClients       = $this->model->getTopClientsBySpending(5);
        $postActivity     = $this->model->getPostCumulativeLast14Days();

        include __DIR__ . '/../../views/admin/dashboard.php';
    }
}
