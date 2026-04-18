<?php

require_once __DIR__ . '/../models/EarningsModel.php';

class EarningsController extends BaseController
{
    private $earningsModel;

    public function __construct()
    {
        parent::__construct();
        $this->earningsModel = new EarningsModel();
    }

    // GET /earnings
    public function index()
    {
        $this->ensureAuth();

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];

        if ($role !== 'Provider') {
            $this->notFound();
            return;
        }

        // Metrics
        $totalEarnings      = $this->earningsModel->getTotalEarnings($userId);
        $earningsChange     = $this->earningsModel->getMonthlyEarningsChange($userId);
        $pendingPayout      = $this->earningsModel->getPendingPayout($userId);
        $avgProjectValue    = $this->earningsModel->getAvgProjectValue($userId);
        $completedProjects  = $this->earningsModel->getCompletedProjectsCount($userId);
        $completedThisMonth = $this->earningsModel->getCompletedThisMonth($userId);

        // Chart data (all periods)
        $chartData = $this->earningsModel->getMonthlyChartData($userId);

        // Recent transactions
        $transactions = $this->earningsModel->getRecentTransactions($userId, 5);

        $viewFile = __DIR__ . '/../views/provider/Earnings/index.php';
        include $viewFile;
    }
}
